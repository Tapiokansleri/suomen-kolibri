<?php
/**
 * The cart is always loaded before a page is rendered.
 *
 * WooCommerce does not load the cart for addresses that look like REST requests, i.e. that contain "/wp-json/" anywhere
 * (bots probe addresses such as /something/wp-json/oembed/...). WordPress still renders those addresses as a 404 page, and
 * the header of the parent theme shows the cart count, so the page stopped with a PHP fatal error (HTTP 500). Loading the
 * cart in that case makes them normal 404 pages.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'template_redirect',
	static function () {
		if ( function_exists( 'WC' ) && function_exists( 'wc_load_cart' ) && null === WC()->cart ) {
			wc_load_cart();
		}
	},
	5
);
