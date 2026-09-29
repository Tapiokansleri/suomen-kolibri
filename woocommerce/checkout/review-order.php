<?php
/**
 * Review order table
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="shop_table woocommerce-checkout-review-order-table mt-3">
	<div class="review-order-row product_table">
		<div class="scroll_indicator_container">
			<div class="scroll_indicator"><?php echo _x('Scroll to view more products', 'Checkout review order', 'bgh-theme'); ?> <i class="fal fa-arrow-down"></i></div>
		</div>
		<?php do_action( 'woocommerce_review_order_before_cart_contents' );
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) : ?>
					<div class="review-col-item">
						<div class="img-wrapper">
							<?php
								$thumb = get_the_post_thumbnail($cart_item['product_id'], array( 50, 50));
								echo $thumb;
							?>
						</div>
						<div class=" product-name">
							<?php do_action( 'eeco_add_product_data_checkout', $cart_item, $cart_item_key ); ?>
							<p><?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; ?>
							<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
							</p>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
						</div>
						<div class="product-total">
							<p><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></p>
						</div>
					</div>
			<?php endif; ?>
			<?php endforeach; ?>

			<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>
		<hr>
	</div>
	<div class="review-order-row cart-subtotal">
		<div class="review-order-header">
			<p><strong><?php _e( 'Subtotal', 'bgh-theme' ); ?></strong></p>
		</div>
		<div class="review-order-value">
			<p><strong><?php wc_cart_totals_subtotal_html(); ?></strong></p>
		</div>
	</div>
	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="review-order-row cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<p class="review-order-header"> <?php wc_cart_totals_coupon_label( $coupon ); ?></p>
			<?php wc_cart_totals_coupon_html( $coupon ); ?>
		</div>
	<?php endforeach; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="review-order-row fee">
			<div class="review-order-header"><p><?php echo esc_html( $fee->name ); ?></p></div>
			<div class="review-order-value"><p><?php wc_cart_totals_fee_html( $fee ); ?></p></div>
		</div>
	<?php endforeach; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
	<div class="review-order-row shipping-cost">
		<div class="review-order-header"><p><?php _e('Shipping costs', 'bgh-theme'); ?></p></div>
		<div class="review-order-value">
			<?php
			$current_shipping_cost = WC()->cart->get_cart_shipping_total();
			echo '<p>'.$current_shipping_cost.'</p>'; ?>
		</div>
	</div>
	<?php
	if ( wc_tax_enabled() && ! empty( WC()->cart->get_tax_totals() ) ) {
		$taxable_address = WC()->customer->get_taxable_address();
		$estimated_text  = '';

		if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
			/* translators: %s location. */
			$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
		}

		if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
			foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				?>
				<div class="review-order-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<div class="review-order-header"><p><?php echo esc_html( $tax->label ) . wp_kses_post( $estimated_text ); ?></p></div>
					<div class="review-order-value"><p><?php echo wp_kses_post( $tax->formatted_amount ); ?></p></div>
				</div>
				<?php
			}
		} else {
			?>
			<div class="review-order-row tax-total">
				<div class="review-order-header"><p><?php echo esc_html( WC()->countries->tax_or_vat() ) . wp_kses_post( $estimated_text ); ?></p></div>
				<div class="review-order-value"><p><?php wc_cart_totals_taxes_total_html(); ?></p></div>
			</div>
			<?php
		}
	}
	?>
	<div class="review-order-row order-total">
		<div class="review-order-header"><p class="totals-p-tag"><?php _e( 'Total', 'bgh-theme' ); ?></p></div>
		<div class="review-order-value"><p class="totals_container"><?php wc_cart_totals_order_total_html(); ?></p></div>
	</div>
	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
</section>
