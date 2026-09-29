<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.6.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$address  = $order->get_formatted_billing_address();
$shipping = $order->get_formatted_shipping_address();

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

/**
 * Filter whether to display the section divider in the email body.
 *
 * @since 10.6.0
 * @param bool $display_section_divider Whether to display the section divider. Default true.
 */
$display_section_divider = (bool) apply_filters( 'woocommerce_email_body_display_section_divider', true );

?>
<?php // The theme's layout has no divider above the addresses in the classic email design. ?>
<?php if ( $email_improvements_enabled && $display_section_divider ) : ?>
	<hr style="border: 0; border-top: 1px solid #1E1E1E; border-top-color: rgba(30, 30, 30, 0.2); margin: 20px 0;">
<?php endif; ?>
<table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top;" border="0" role="presentation">
	<tr>
		<td class="font-family text-align-left" valign="top" width="50%">
			<?php if ( $email_improvements_enabled ) { ?>
				<b class="address-title"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></b>
			<?php } else { ?>
				<h4><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h4>
			<?php } ?>

			<p style="font-size: 11px;">
				<?php echo wp_kses_post( $address ? $address : esc_html__( 'N/A', 'woocommerce' ) ); ?>
				<?php
				/**
				 * Fires after the core address fields in emails.
				 *
				 * @since 8.6.0
				 *
				 * @param string $type Address type. Either 'billing' or 'shipping'.
				 * @param WC_Order $order Order instance.
				 * @param bool $sent_to_admin If this email is being sent to the admin or not.
				 * @param bool $plain_text If this email is plain text or not.
				 */
				do_action( 'woocommerce_email_customer_address_section', 'billing', $order, $sent_to_admin, false );
				?>
			</p>
		</td>
		<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping ) : ?>
			<td class="font-family text-align-left" valign="top" width="50%">
				<?php if ( $email_improvements_enabled ) { ?>
					<b class="address-title"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></b>
				<?php } else { ?>
					<h4><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h4>
				<?php } ?>

				<p style="font-size: 11px;">
					<?php echo wp_kses_post( $shipping ); ?>
					<?php
					/**
					 * Fires after the core address fields in emails.
					 *
					 * @since 8.6.0
					 *
					 * @param string $type Address type. Either 'billing' or 'shipping'.
					 * @param WC_Order $order Order instance.
					 * @param bool $sent_to_admin If this email is being sent to the admin or not.
					 * @param bool $plain_text If this email is plain text or not.
					 */
					do_action( 'woocommerce_email_customer_address_section', 'shipping', $order, $sent_to_admin, false );
					?>
				</p>
			</td>
		<?php endif; ?>
	</tr>
</table>
<?php echo $email_improvements_enabled ? '<br>' : ''; ?>
