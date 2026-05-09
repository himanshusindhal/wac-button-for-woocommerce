<?php
/**
 * Frontend Logic Class
 *
 * @package Smart_WhatsApp_Order_Button
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SWOB_Frontend {

	public function __construct() {
		$enable_button = get_option( 'swob_enable_button', 0 );
		if ( ! $enable_button ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		$position = get_option( 'swob_button_position', 'after' );
		$hook = 'woocommerce_after_add_to_cart_button';
		if ( 'before' === $position ) {
			$hook = 'woocommerce_before_add_to_cart_button';
		}
		
		add_action( $hook, array( $this, 'display_whatsapp_button' ) );
		add_shortcode( 'whatsapp_order_button', array( $this, 'shortcode_whatsapp_button' ) );
	}

	public function enqueue_scripts() {
		if ( ! is_product() && ! is_singular( 'product' ) ) {
			global $post;
			if ( ! is_a( $post, 'WP_Post' ) || ! has_shortcode( $post->post_content, 'whatsapp_order_button' ) ) {
				return;
			}
		}

		wp_enqueue_style( 'swob-style', SWOB_PLUGIN_URL . 'assets/css/style.css', array(), SWOB_VERSION );
		wp_enqueue_script( 'swob-script', SWOB_PLUGIN_URL . 'assets/js/script.js', array(), SWOB_VERSION, true );
		
		wp_localize_script( 'swob-script', 'swobData', array(
			'messageTemplate' => get_option( 'swob_message_template', '' ),
			'whatsappNumber'  => get_option( 'swob_whatsapp_number', '' ),
		) );
	}

	public function display_whatsapp_button() {
		$product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return;
		}

		echo $this->get_button_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function shortcode_whatsapp_button( $atts ) {
		$atts = shortcode_atts( array(
			'product_id' => 0,
		), $atts, 'whatsapp_order_button' );

		$product_id = intval( $atts['product_id'] );
		
		if ( ! $product_id ) {
			$product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;
		} else {
			$product = wc_get_product( $product_id );
		}

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return '';
		}

		return $this->get_button_html( $product );
	}

	private function get_button_html( $product ) {
		$number = get_option( 'swob_whatsapp_number', '' );
		if ( empty( $number ) ) {
			return '';
		}

		$text = get_option( 'swob_button_text', __( 'Order on WhatsApp', 'wac-button-for-woocommerce' ) );
		$template = get_option( 'swob_message_template', "Hello, I want to order this product:\nProduct Name: {product_name}\nPrice: {product_price}\nProduct URL: {product_url}" );
		$button_color = get_option( 'swob_button_color', '#25D366' );
		if ( empty( $button_color ) ) {
			$button_color = '#25D366';
		}
		
		$show_desktop = get_option( 'swob_show_desktop', 1 );
		$show_mobile  = get_option( 'swob_show_mobile', 1 );
		$sticky_mobile= get_option( 'swob_sticky_mobile', 0 );

		// Clean the number
		$number = preg_replace( '/[^0-9]/', '', $number );

		$product_name  = $product->get_name();
		$product_price = html_entity_decode( wp_strip_all_tags( wc_price( wc_get_price_to_display( $product ) ) ) );
		$product_url   = get_permalink( $product->get_id() );

		// Convert <br> and <p> tags to newlines
		$template = preg_replace( '/<br\s*\/?>/i', "\n", $template );
		$template = preg_replace( '/<\/p>\s*<p>/i', "\n\n", $template );
		$template = preg_replace( '/<\/?p>/i', '', $template );

		// Normalize line breaks and handle literal '\n' if typed
		$template = str_replace( array( "\r\n", "\r", '\\n' ), "\n", $template );

		// Replace placeholders
		$message = str_replace(
			array( '{product_name}', '{product_price}', '{product_url}' ),
			array( $product_name, $product_price, $product_url ),
			$template
		);

		// Ensure all other HTML is stripped
		$message = wp_strip_all_tags( $message );

		$whatsapp_url = 'https://wa.me/' . $number . '?text=' . rawurlencode( $message );

		$classes = array( 'swob-whatsapp-button', 'single_add_to_cart_button', 'button', 'alt' );
		if ( ! $show_desktop ) {
			$classes[] = 'swob-hide-desktop';
		}
		if ( ! $show_mobile ) {
			$classes[] = 'swob-hide-mobile';
		}
		if ( $show_mobile && $sticky_mobile ) {
			$classes[] = 'swob-sticky-mobile';
		}

		$icon = '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.663-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>';

		$inline_style = sprintf(
			'background-color: %s !important; border-color: %s !important; color: #ffffff !important; line-height: 1 !important;',
			esc_attr( $button_color ),
			esc_attr( $button_color )
		);

		ob_start();
		?>
		<a href="<?php echo esc_url( $whatsapp_url ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" style="<?php echo esc_attr( $inline_style ); ?>" target="_blank" rel="noopener noreferrer" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
			<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="swob-text" style="display: inline-block; vertical-align: middle; line-height: 1;"><?php echo esc_html( $text ); ?></span>
		</a>
		<?php
		return ob_get_clean();
	}
}
