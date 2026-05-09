<?php
/**
 * Admin Settings Class
 *
 * @package Smart_WhatsApp_Order_Button
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SWOB_Admin_Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_settings_page() {
		add_options_page(
			__( 'WhatsApp Order Button', 'wac-button-for-woocommerce' ),
			__( 'WhatsApp Order', 'wac-button-for-woocommerce' ),
			'manage_options',
			'wac-button-for-woocommerce',
			array( $this, 'settings_page_html' )
		);
	}

	public function register_settings() {
		register_setting( 'swob_settings_group', 'swob_whatsapp_number', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'swob_settings_group', 'swob_button_text', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'swob_settings_group', 'swob_message_template', array( 'sanitize_callback' => 'wp_kses_post' ) );
		register_setting( 'swob_settings_group', 'swob_enable_button', array( 'sanitize_callback' => 'absint' ) );
		register_setting( 'swob_settings_group', 'swob_button_color', array( 'sanitize_callback' => 'sanitize_text_field' ) );
		register_setting( 'swob_settings_group', 'swob_show_desktop', array( 'sanitize_callback' => 'absint' ) );
		register_setting( 'swob_settings_group', 'swob_show_mobile', array( 'sanitize_callback' => 'absint' ) );
		register_setting( 'swob_settings_group', 'swob_sticky_mobile', array( 'sanitize_callback' => 'absint' ) );
		register_setting( 'swob_settings_group', 'swob_button_position', array( 'sanitize_callback' => 'sanitize_text_field' ) );

		add_settings_section(
			'swob_main_section',
			__( 'Main Settings', 'wac-button-for-woocommerce' ),
			array( $this, 'main_section_cb' ),
			'wac-button-for-woocommerce'
		);

		add_settings_field( 'swob_enable_button', __( 'Enable Button', 'wac-button-for-woocommerce' ), array( $this, 'checkbox_cb' ), 'wac-button-for-woocommerce', 'swob_main_section', array( 'label_for' => 'swob_enable_button' ) );
		add_settings_field( 'swob_whatsapp_number', __( 'WhatsApp Number', 'wac-button-for-woocommerce' ), array( $this, 'text_cb' ), 'wac-button-for-woocommerce', 'swob_main_section', array( 'label_for' => 'swob_whatsapp_number', 'description' => __( 'Include country code without + or 00 (e.g., 1234567890).', 'wac-button-for-woocommerce' ) ) );
		add_settings_field( 'swob_button_text', __( 'Button Text', 'wac-button-for-woocommerce' ), array( $this, 'text_cb' ), 'wac-button-for-woocommerce', 'swob_main_section', array( 'label_for' => 'swob_button_text', 'default' => __( 'Order on WhatsApp', 'wac-button-for-woocommerce' ) ) );
		add_settings_field( 'swob_message_template', __( 'Message Template', 'wac-button-for-woocommerce' ), array( $this, 'textarea_cb' ), 'wac-button-for-woocommerce', 'swob_main_section', array( 'label_for' => 'swob_message_template', 'description' => __( 'Use placeholders: {product_name}, {product_price}, {product_url}.', 'wac-button-for-woocommerce' ) ) );
		add_settings_field( 'swob_button_color', __( 'Button Color', 'wac-button-for-woocommerce' ), array( $this, 'color_cb' ), 'wac-button-for-woocommerce', 'swob_main_section', array( 'label_for' => 'swob_button_color', 'default' => '#25D366' ) );
		add_settings_field( 'swob_show_desktop', __( 'Show on Desktop', 'wac-button-for-woocommerce' ), array( $this, 'checkbox_cb' ), 'wac-button-for-woocommerce', 'swob_main_section', array( 'label_for' => 'swob_show_desktop', 'default' => 1 ) );
		add_settings_field( 'swob_show_mobile', __( 'Show on Mobile', 'wac-button-for-woocommerce' ), array( $this, 'checkbox_cb' ), 'wac-button-for-woocommerce', 'swob_main_section', array( 'label_for' => 'swob_show_mobile', 'default' => 1 ) );
		add_settings_field( 'swob_sticky_mobile', __( 'Enable Sticky on Mobile', 'wac-button-for-woocommerce' ), array( $this, 'checkbox_cb' ), 'wac-button-for-woocommerce', 'swob_main_section', array( 'label_for' => 'swob_sticky_mobile', 'description' => __( 'Show as a sticky button at the bottom of the screen on mobile devices.', 'wac-button-for-woocommerce' ) ) );
		add_settings_field( 'swob_button_position', __( 'Button Position', 'wac-button-for-woocommerce' ), array( $this, 'select_cb' ), 'wac-button-for-woocommerce', 'swob_main_section', array( 'label_for' => 'swob_button_position', 'options' => array( 'after' => __( 'After Add to Cart', 'wac-button-for-woocommerce' ), 'before' => __( 'Before Add to Cart', 'wac-button-for-woocommerce' ) ) ) );
	}

	public function main_section_cb() {
		echo '<p>' . esc_html__( 'Configure your WhatsApp order button settings below.', 'wac-button-for-woocommerce' ) . '</p>';
	}

	public function text_cb( $args ) {
		$value = get_option( $args['label_for'], isset( $args['default'] ) ? $args['default'] : '' );
		?>
		<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	public function color_cb( $args ) {
		$value = get_option( $args['label_for'], isset( $args['default'] ) ? $args['default'] : '#25D366' );
		?>
		<input type="color" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	public function textarea_cb( $args ) {
		$default = "Hello, I want to order this product:\nProduct Name: {product_name}\nPrice: {product_price}\nProduct URL: {product_url}";
		$value = get_option( $args['label_for'], $default );
		?>
		<textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" rows="5" class="large-text code"><?php echo esc_textarea( $value ); ?></textarea>
		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	public function checkbox_cb( $args ) {
		$default = isset( $args['default'] ) ? $args['default'] : 0;
		$value = get_option( $args['label_for'], $default );
		?>
		<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="1" <?php checked( 1, $value, true ); ?>>
		<?php if ( isset( $args['description'] ) ) : ?>
			<span class="description"><?php echo esc_html( $args['description'] ); ?></span>
		<?php endif; ?>
		<?php
	}

	public function select_cb( $args ) {
		$value = get_option( $args['label_for'], 'after' );
		?>
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>">
			<?php foreach ( $args['options'] as $key => $label ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value, $key, true ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	public function settings_page_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'WhatsApp Order Button Settings', 'wac-button-for-woocommerce' ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'swob_settings_group' );
				do_settings_sections( 'wac-button-for-woocommerce' );
				submit_button( __( 'Save Settings', 'wac-button-for-woocommerce' ) );
				?>
			</form>
		</div>
		<?php
	}
}
