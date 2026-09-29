<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="row">
	<div class="col-12">
		<?php
		do_action( 'woocommerce_before_checkout_form', $checkout );

		// If checkout registration is disabled and not logged in, the user cannot checkout.
		if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
			echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', _x( 'You must be logged in to checkout.', 'Checkout', 'bgh-theme' ) ) );
			return;
		} ?>
	</div>
</div>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">
	<?php
	// CHECK IF CART CONTAINS ONLY VIRTUAL PRODUCTS 
	$only_virtual = bgh_check_if_only_virtual(); ?>
	<div class="col-lg-7 order-2 order-lg-1">
		<div class="checkout-wrapper">
			<?php if ( $checkout->get_checkout_fields() ) : ?>
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				<div class="checkout-step-1 mb-3" id="customer_details">
					<div class="box-border">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>

						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						<?php do_action('woocommerce_checkout_after_customer_details'); ?>
						<button type="button" class="button primary-cta">
							<?php if ($only_virtual) { 
								_e('Continue to payment method', 'bgh-theme'); 
							} else { 
								_e('Continue to shipping method', 'bgh-theme'); 
							} ?>
						</button>
					</div>
					<span class="customer-details hidden invisible d-none"><?php _e('Customer details', 'bgh-theme'); ?></span>
				</div>
			<?php endif; ?>

			<?php if (!$only_virtual): ?>
				<div class="checkout-step-2 mb-3 opacity">
					<div class="box-border">
						<h3><?php _e('2. Choose shipping method', 'bgh-theme'); ?></h3>
						<?php $shipping_images = get_option('bgh_checkout_settings_shipping_images');
						if($shipping_images && is_array($shipping_images)){
							foreach ($shipping_images as $key => $shipping_image) {
								?><img src="<?php echo wp_get_attachment_url($shipping_image); ?>" alt="<?php echo get_post_meta( $shipping_image, '_wp_attachment_image_alt', true); ?>" class="method-img"><?php
							}
						} ?>
						<div class="methods-wrapper d-none">
							<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

								<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

								<?php wc_cart_totals_shipping_html(); ?>

								<?php
								// PLACE SHIPPING METHODS HERE
								do_action( 'woocommerce_review_order_after_shipping' ); ?>

							<?php endif; ?>
						</div>
						<div class="button-wrapper d-none">
							<button type="button" class="button primary-cta d-none"><?php _e('Continue to payment method', 'bgh-theme'); ?></button>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="checkout-step-3 opacity">
				<div class="box-border">
					<h3>
						<?php if ($only_virtual) { 
							_e('2. Choose payment method', 'bgh-theme'); 
						} else { 
							_e('3. Choose payment method', 'bgh-theme'); 
						} ?>
					</h3>
					<?php 
						$payment_images = get_option('bgh_checkout_settings_payment_images');
						if($payment_images && is_array($payment_images)){
							foreach ($payment_images as $key => $payment_image) {
								?><img src="<?php echo wp_get_attachment_url($payment_image); ?>" alt="<?php echo get_post_meta( $payment_image, '_wp_attachment_image_alt', true); ?>" class="method-img"><?php
							}
						}
					?>
					<div class="payment-wrapper d-none">
						<?php 
						// Output the Payment Methods
						woocommerce_checkout_payment();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-5 order-1 order-lg-2 order-summary-table">
		<div class="checkout-review-wrapper">
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<div id="order_review" class="woocommerce-checkout-review-order">
				<h3><?php _e('Summary', 'bgh-theme'); ?></h3>
				<?php
				// CHANGE THE LOCATION OF SHIPPING METHODS
				remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
				do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

			<div class="mobile-order-review box-border d-lg-none">
				<p class="summary-tag"><?php _e('Summary', 'bgh-theme'); ?></p>
				<?php
				// CHANGE THE LOCATION OF SHIPPING METHODS
				remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
				do_action( 'woocommerce_checkout_order_review' ); ?>
				<button type="button" class="show-more d-none" data-hide-details="<?php _e('Hide', 'bgh-theme'); ?>"><?php echo _x('View all products', 'Checkout mobile order review', 'bgh-theme'); ?>
					<i class="fa fa-angle-down"></i>
				</button>
			</div>
			<?php 
			$arguments = get_option('bgh_checkout_settings_arguments');
			
			if($arguments): ?>
				<div class="sales-arguments">
					<?php foreach($arguments as $argument):?>
						<p><i class="far fa-check-circle"></i> <?php echo $argument ?></p>
					<?php endforeach;?>
				</div>
			<?php endif;?>
		</div>
		<div class="edit hidden invisible d-none"><?php _e('Edit', 'bgh-theme'); ?></div>
	</div>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
