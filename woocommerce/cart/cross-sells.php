<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) :
	if(isset($posts_per_page) && $posts_per_page === 8): ?>
		<h3><?php echo get_option('bgh_cross_sell_under_cart_header') ?></h3>
	<?php else: ?>
		<h3><?php echo get_option('bgh_cross_sell_under_summary_header') ?></h3>
	<?php endif;
	$carousel_mobile_width = get_option('eeco_carousel_mobile_width'); ?>
	<div class="scroll <?php echo $carousel_mobile_width?>">
		<?php woocommerce_product_loop_start();
			foreach ( $cross_sells as $cross_sell ) : ?>
				<div class="item col">  
					<?php
					$post_object = get_post( $cross_sell->get_id() );

					setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

					wc_get_template_part( 'content', 'product' ); ?>
				</div>
			<?php endforeach;
		woocommerce_product_loop_end(); ?>
	</div>
	<?php if(count($cross_sells) > 4) {
		eeco_carousel_arrows();
	}

endif;

wp_reset_postdata();
