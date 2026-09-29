<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order row">
	<div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
		<div class="white">
			<?php
			if ( $order ) :

				do_action( 'woocommerce_before_thankyou', $order->get_id() );
				?>

				<?php if ( $order->has_status( 'failed' ) ) : ?>
					<div class="mb-3">
						<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed woocommerce-error"><?php echo esc_html_x( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'Thank you page', 'bgh-theme' ); ?></p>

						<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
							<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay primary-cta"><?php esc_html_e( 'Pay', 'bgh-theme' ); ?></a>
							<?php if ( is_user_logged_in() ) : ?>
								<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay primary-cta"><?php esc_html_e( 'My account', 'bgh-theme' ); ?></a>
							<?php endif; ?>
						</p>
					</div>

				<?php else : ?>
					<div class="mb-3">
						<?php
							$customer = $order->get_billing_first_name();

						?>

						<h1><i class="fal fa-check"></i> <?php esc_html_e( 'Thank you for your order', 'bgh-theme' ); echo ' ' . esc_html( $customer ); ?></h1>

						<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html_x( 'Your order has been confirmed with the information below.', 'Thank you page', 'bgh-theme' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
					</div>
				<?php endif; ?>

				<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

			<?php else : ?>

				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html_x( 'Thank you. Your order has been received.', 'Thank you page', 'bgh-theme' ), false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

			<?php endif; ?>
		</div>
	</div>
</div>
