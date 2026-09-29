<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'bgh-theme' ),
			'shipping' => __( 'Shipping address', 'bgh-theme' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'bgh-theme' ),
		),
		$customer_id
	);
}

$oldcol = 1;
$col    = 1;

$shipping_address = wc_get_account_formatted_address( 'shipping' );
$billing_address  = wc_get_account_formatted_address( 'billing' ); ?>

<p><?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html_x( 'The following addresses will be used on the checkout page by default.', 'My address page in my account', 'bgh-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

<div class="my-addresses">
	<ul class="nav nav-pills" id="pills-tab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><?php esc_html_e( 'Billing Address', 'bgh-theme' ); ?></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><?php esc_html_e( 'Shipping Address', 'bgh-theme' ); ?></a>
		</li>
	</ul>

	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane white fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
			<?php
			if ( ! empty( $billing_address ) ) {
				echo '<p>' . wp_kses_post( $billing_address ) . '</p>';
			}

			/**
			 * Used to output content after core address fields.
			 *
			 * @param string $name Address type.
			 * @since 8.7.0
			 */
			do_action( 'woocommerce_my_account_after_my_address', 'billing' );
			?>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" class="edit button primary-cta"><?php echo $billing_address ? esc_html__( 'Edit', 'bgh-theme' ) : esc_html__( 'Add', 'bgh-theme' ); ?></a>
		</div>
		<div class="tab-pane white fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
			<?php
			if ( ! empty( $shipping_address ) ) {
				echo '<p>' . wp_kses_post( $shipping_address ) . '</p>';
			}

			/** This action is documented above. */
			do_action( 'woocommerce_my_account_after_my_address', 'shipping' );
			?>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping' ) ); ?>" class="edit button primary-cta"><?php echo $shipping_address ? esc_html__( 'Edit', 'bgh-theme' ) : esc_html__( 'Add', 'bgh-theme' ); ?></a>
		</div>
	</div>
</div>
<?php
if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	echo '</div>';
}
