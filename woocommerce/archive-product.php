<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

// bGH theme settings variables 
$show_sidebar = get_option('bgh_product_archive_show_sidebar'); 

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked eeco_main_container_row - 1
 * @hooked eeco_display_yoast_breadcrumb - 5
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */

if ($show_sidebar) {
	do_action( 'woocommerce_sidebar' );
}
	
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked eeco_check_sidebar - 1
	 * @hooked eeco_wrap_cat_image_and_description_open - 2
	 * @hooked woocommerce_category_image - 5
	 * @hooked bgh_category_short_desc - 10
	 * @hooked eeco_wrap_cat_image_and_description_close - 25
	 * @hooked eeco_archive_product_filters - 30
	 */
	
	do_action( 'woocommerce_archive_description' );
			
	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked eeco_wrap_before_shop_loop_items_open - 1
	 * @hooked in FEATURED & SALE ARCHIVE - bgh_before_shop_loop - 5
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 * @hooked in FEATURED & SALE ARCHIVE - bgh_after_shop_loop - 35
	 * @hooked in FEATURED & SALE ARCHIVE - bgh_before_sale_products_loop - 40
	 * @hooked eeco_enable_layout_switch - 90
	 * @hooked eeco_wrap_before_shop_loop_items_close - 100
	 */
	do_action( 'woocommerce_before_shop_loop' );
		
	woocommerce_product_loop_start();
		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();
				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );
				
				wc_get_template_part( 'content', 'product' );
			}
		}
	woocommerce_product_loop_end();
		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked eeco_archive_pagination - 1
		 * @hooked eeco_close_products_row - 2
		 * @hooked in FEATURED & SALE ARCHIVE - bgh_after_sale_products_loop - 5
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );

} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 * @hooked eeco_category_long_description - 25
 */
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
