<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>
	<div class="clear"></div>
	<div id="product-tabs" class="woocommerce-tabs wc-tabs-wrapper mt-5">
		<?php $count = 0; ?>
		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
		<?php $count++; ?>
			<div class="card">
				<div class="card-header" id="heading<?php echo $count; ?>">
					<h5 class="mb-0">
						<button class="<?php if ($count > 1) { echo 'collapsed'; } ?>" data-toggle="collapse" data-target="#collapse<?php echo $count; ?>" aria-expanded="<?php if ($count > 1) { echo 'false'; } else { echo 'true'; } ?>" aria-controls="collapse<?php echo $count; ?>">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
						</button>
					</h5>
				</div>

				<div id="collapse<?php echo $count; ?>" class="collapse <?php if ($count == 1) { echo 'show'; } ?>" aria-labelledby="heading<?php echo $count; ?>">
					<div class="card-body">
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>
<?php endif; ?>
