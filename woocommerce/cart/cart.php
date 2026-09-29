<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;
$design = get_option('eeco_cart_design');
$products_in_cart = WC()->cart->get_cart_contents_count();

do_action( 'woocommerce_before_cart' );
	get_template_part('template-parts/cart/'.$design, '', array('products_in_cart' => $products_in_cart));
do_action( 'woocommerce_after_cart' ); ?>
