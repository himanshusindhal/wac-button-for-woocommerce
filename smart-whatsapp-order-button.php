<?php
/**
 * Plugin Name: Smart Order Button
 * Plugin URI:  https://wordpress.org/plugins/smart-order-button/
 * Description: Allow WooCommerce users to send product details directly to WA from the product page.
 * Version:     1.0.0
 * Author:      Manus AI
 * Author URI:  https://manus.ai/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: smart-whatsapp-order-button
 *
 * @package Smart_Order_Button
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SWOB_VERSION', '1.0.0' );
define( 'SWOB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SWOB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Plugin Initialization
 */
function swob_init() {
	// Check if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'swob_woocommerce_missing_notice' );
		return;
	}

	require_once SWOB_PLUGIN_DIR . 'includes/class-admin-settings.php';
	require_once SWOB_PLUGIN_DIR . 'includes/class-frontend.php';

	if ( is_admin() ) {
		new SWOB_Admin_Settings();
	}
	
	new SWOB_Frontend();
}
add_action( 'plugins_loaded', 'swob_init' );

/**
 * Notice if WooCommerce is missing
 */
function swob_woocommerce_missing_notice() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php esc_html_e( 'Smart WhatsApp Order Button requires WooCommerce to be installed and active.', 'smart-whatsapp-order-button' ); ?></p>
	</div>
	<?php
}
