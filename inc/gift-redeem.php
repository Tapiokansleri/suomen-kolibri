<?php

function eeco_child_gift_coupon() {
	
	if ( isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'eeco_apply_gift_code') ) {
		setcookie('eeco_gift_coupon_code', $_POST['eeco_gift_code'], 0, '/');
		wp_redirect( get_permalink( get_page_by_path( 'valitse-tuotteet' )) );
		exit;
	}
}
add_action('eeco_redeem_gift', 'eeco_child_gift_coupon');

function eeco_child_redeemable_products() {
	
	if (isset($_COOKIE['eeco_gift_coupon_code'])) {
		remove_filter('woocommerce_get_shop_coupon_data', 'eeco_child_gift_coupon');
		$coupon = new WC_Coupon( $_COOKIE['eeco_gift_coupon_code'] );
		
		$product_ids = $coupon->get_product_ids();
		
		

		$loop = new WP_Query( array(
			'post_type' => 'product',
			'post__in' => $product_ids,
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC'
		) );
		?>
		<div class="row">
			<div class="col-12">
				<?php the_content(); ?>
			</div>
		</div>
		<div id="products" class="row">
			<?php
			while( $loop->have_posts() ) : $loop->the_post();
				global $product; ?>
				<div class="col-lg-4 col-6 eeco-grid-view">
					<?php 
						do_action( 'woocommerce_before_shop_loop_item' );
						do_action( 'woocommerce_before_shop_loop_item_title' );
						do_action( 'woocommerce_shop_loop_item_title' );
						woocommerce_template_loop_add_to_cart();
						do_action( 'woocommerce_after_shop_loop_item' );
						
					?>
				</div>
			<?php endwhile;
			wp_reset_postdata(); ?>
		</div>
	<?php
	} else {
		?>
		<div class="row">
			<div class="col-12">
				<?php the_field('no_coupon_error'); ?>
		</div>
		<?php
	}
}