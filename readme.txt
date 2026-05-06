=== Click2Chat Order for WooCommerce ===
Contributors: sindhalhimanshu
Tags: woocommerce, wa, order, button, chat
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allow WooCommerce users to send product details directly to WA from the product page.

== Description ==

Click2Chat Order for WooCommerce is a lightweight, production-ready WordPress plugin that integrates a customizable WhatsApp order button into your WooCommerce product pages. When clicked, it redirects users to WhatsApp with a pre-filled, dynamic message containing the product name, price, and URL.

### Features
* Seamless integration with WooCommerce "Add to Cart" area.
* Dynamic auto-fill message with placeholders `{product_name}`, `{product_price}`, `{product_url}`.
* Customizable button text and WhatsApp number.
* Mobile optimized: Optional sticky button for mobile devices.
* Shortcode support `[whatsapp_order_button]`.
* Lightweight and performant: No jQuery dependency, minimal CSS.
* Secure and strictly follows WordPress coding standards.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/click2chat-order-for-woocommerce` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to Settings > WhatsApp Order.
4. Enter your WhatsApp number (with country code) and customize the message template.

== Frequently Asked Questions ==

= Does it require WooCommerce? =
Yes, the plugin depends on WooCommerce to fetch product details and hook into the product pages.

= Can I use it on pages other than product pages? =
You can use the shortcode `[whatsapp_order_button product_id="123"]` to show the button on other pages, providing the product ID.

== Screenshots ==

1. The admin settings panel.
2. The WhatsApp button on a product page.
3. Mobile view with the sticky button.

== Changelog ==

= 1.0.0 =
* Initial release.
