<?php
/**
 * Fallback created date for the parent theme's "new" badge.
 *
 * Bgh_Wc_Badges::bgh_add_new_badge() (parent inc/woocommerce/global/badges.php)
 * leaves $timestamp_created undefined when a product has no created date, which
 * logs "Undefined variable $timestamp_created". The badges render at priority 7,
 * so the epoch is supplied only between priorities 6 and 8. The result is the
 * same as before (no "new" badge for such products), without the warning.
 *
 * @package Eeco_Theme_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function eeco_child_badge_date_fallback( $date ) {
	return $date ? $date : new WC_DateTime( '@0' );
}

function eeco_child_badge_date_fallback_on() {
	add_filter( 'woocommerce_product_get_date_created', 'eeco_child_badge_date_fallback' );
	add_filter( 'woocommerce_product_variation_get_date_created', 'eeco_child_badge_date_fallback' );
}

function eeco_child_badge_date_fallback_off() {
	remove_filter( 'woocommerce_product_get_date_created', 'eeco_child_badge_date_fallback' );
	remove_filter( 'woocommerce_product_variation_get_date_created', 'eeco_child_badge_date_fallback' );
}

foreach ( array( 'woocommerce_before_shop_loop_item_title', 'woocommerce_before_single_product_summary' ) as $eeco_child_badge_hook ) {
	add_action( $eeco_child_badge_hook, 'eeco_child_badge_date_fallback_on', 6 );
	add_action( $eeco_child_badge_hook, 'eeco_child_badge_date_fallback_off', 8 );
}
unset( $eeco_child_badge_hook );
