<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) :

	//STYLE VARIABLES
	$carousel_mobile_width = get_option('eeco_carousel_mobile_width');
	//CONTENT
	$title = get_option('eeco_product_page_up_sell_title'); ?>
	<section class="up-sells">
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
								foreach ( $upsells as $upsell ) : ?>
									<div class="item col">  
										<?php
										$post_object = get_post( $upsell->get_id() );

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
