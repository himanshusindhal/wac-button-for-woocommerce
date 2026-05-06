<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package Smart_WhatsApp_Order_Button
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete options from database.
delete_option( 'swob_whatsapp_number' );
delete_option( 'swob_button_text' );
delete_option( 'swob_message_template' );
delete_option( 'swob_enable_button' );
delete_option( 'swob_mobile_only' );
delete_option( 'swob_button_position' );
