<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="container cart_totals">
    <div class="row">
        <div class="col-lg-4">
			<h3 class="mb-3"><?php echo _x('Haluatko jättää tarjouskyselyn?', 'ostoskori', 'bgh-theme-child'); ?></h3>
			<button type="button" class="button secondary-cta cart-quote" data-toggle="modal" data-target="#quoteCart"><?php echo _x('Jätä tarjouskysely', 'Ostoskori', 'bgh-theme-child'); ?></button>
			<?php if ( get_option('bgh_show_cross_sell_subtotal') ): ?>
				<div class="cart-cross-sells">
					<?php // DISPlAY CROSS SELLS
					woocommerce_cross_sell_display(  $posts_per_page = 1, $orderby = 'rand' ); ?>
				</div>
			<?php endif; ?>
        </div>
        <div class="col-lg">
			<div class="row">
				<div class="cart-row shipping" data-title="<?php esc_attr_e( 'Shipping', 'bgh-theme' ); ?>">
					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
						<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
						<?php wc_cart_totals_shipping_html(); ?>
						<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

					<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
						<?php woocommerce_shipping_calculator(); ?>
					<?php endif; ?>
				</div>
				<div class="<?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?> cart-totals">
					<?php do_action( 'woocommerce_before_cart_totals' ); ?>

					<div class="cart-total-title">
						<h3><?php _e( 'Cart totals', 'bgh-theme' ); ?></h3>
					</div>
					<?php if ( wc_coupons_enabled() ) : ?>
						<form class="woocommerce-coupon-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
							<div class="my-3 coupon">
								<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'bgh-theme' ); ?>" /> 
								<button type="submit" class="button secondary-cta mt-3" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'bgh-theme' ); ?>"><?php esc_attr_e( 'Apply coupon', 'bgh-theme' ); ?></button>
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						</form>
					<?php endif; ?>
					<hr>
					<div class="cart-items">
						<div class="cart-row cart-subtotal">
							<div class="cart-row-header" data-title="<?php esc_attr_e( 'Subtotal', 'bgh-theme' ); ?>">
								<p><?php _e( 'Subtotal', 'bgh-theme' ); ?></p>
							</div>
							<div class="cart-row-value">
								<p><?php wc_cart_totals_subtotal_html(); ?></p>
							</div>
							<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
								<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
									<p class="coupon"><?php wc_cart_totals_coupon_label( $coupon ); ?></p>
									<?php wc_cart_totals_coupon_html( $coupon ); ?>
								</div>
							<?php endforeach; ?>
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
									<div class="cart-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
										<div class="cart-row-header" data-title="<?php echo esc_attr( $tax->label ); ?>">
											<p><?php echo esc_html( $tax->label ) . wp_kses_post( $estimated_text ); ?></p>
										</div>
										<div class="cart-row-value">
											<p><?php echo wp_kses_post( $tax->formatted_amount ); ?></p>
										</div>
									</div>
									<?php
								}
							} else {
								?>
								<div class="cart-row tax-total">
									<div class="cart-row-header" data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>">
										<p><?php echo esc_html( WC()->countries->tax_or_vat() ) . wp_kses_post( $estimated_text ); ?></p>
									</div>
									<div class="cart-row-value">
										<p><?php wc_cart_totals_taxes_total_html(); ?></p>
									</div>
								</div>
								<?php
							}
						}
						?>
						<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
						<hr>
						<div class="cart-row order-total">
							<div class="cart-row-header" data-title="<?php esc_attr_e( 'Total', 'bgh-theme' ); ?>">
								<p><strong><?php _e( 'Total', 'bgh-theme' ); ?></strong></p>
							</div>
							<div class="cart-row-value">
								<p><?php wc_cart_totals_order_total_html(); ?></p>
							</div>
						</div>
						<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
						<div class="cart-row wc-proceed-to-checkout">
							<?php do_action('eeco_before_proceed_to_checkout'); ?>
							<a href="<?php echo esc_url( wc_get_checkout_url() );?>" class="button primary-cta"><?php esc_html_e( 'Proceed to checkout', 'bgh-theme' ); ?></a>
						</div>
					</div>
					<?php do_action( 'woocommerce_after_cart_totals' ); ?>
				</div>
			</div>
        </div>
    </div>
</div>