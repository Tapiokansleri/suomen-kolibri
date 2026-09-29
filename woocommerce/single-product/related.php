<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     10.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : 
	/**
	 * Ensure all images of related products are lazy loaded by increasing the
	 * current media count to WordPress's lazy loading threshold if needed.
	 * Because wp_increase_content_media_count() is a private function, we
	 * check for its existence before use.
	 */
	if ( function_exists( 'wp_increase_content_media_count' ) ) {
		$content_media_count = wp_increase_content_media_count( 0 );
		if ( $content_media_count < wp_omit_loading_attr_threshold() ) {
			wp_increase_content_media_count( wp_omit_loading_attr_threshold() - $content_media_count );
		}
	}

	//STYLE VARIABLES
	$carousel_mobile_width = get_option('eeco_carousel_mobile_width');

	//CONTENT
	$title = get_option('eeco_product_page_related_products_title'); ?>
	<section class="up-sells related">
		<div class="container">
			<div class="row justify-content-center">	
				<div class="col-12">
					<?php echo !empty($title) ? '<h2>'.$title.'</h2>' : ''; ?>
					<?php woocommerce_product_loop_start(); ?>
						<?php do_action('bgh_badges_is_carousel', true) ?>
						<div class="carousel-row">
							<div class="scroll <?php echo $carousel_mobile_width?>">
								<?php 
								$count = 0; 
								foreach ( $related_products as $related_product ) : ?>
									<div class="item col">  
										<?php
										$post_object = get_post( $related_product->get_id() );

										setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

										wc_get_template_part( 'content', 'product' ); ?>
									</div>
								<?php 
								$count++;
								endforeach; ?>
							</div>
							<?php if ($count > 4) {
								eeco_carousel_arrows();
							} ?>
						</div>
						<?php do_action('bgh_badges_is_carousel', false) ?>
					<?php woocommerce_product_loop_end(); ?>
				</div>
			</div>
		</div>
	</section>
<?php endif;

wp_reset_postdata();
