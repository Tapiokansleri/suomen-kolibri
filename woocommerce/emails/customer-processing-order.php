<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.4.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email );

$order_date = $order->get_date_created();
?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>
<p style="font-size: 11px;"><?php esc_html_e( 'Tilausvahvistus - Suomen Kolibri Oy', 'woocommerce' ); ?></p>
<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, true, false, $email ); ?>

<h4><?php printf( __( 'Tilaus: %s', 'woocommerce' ), esc_html( $order->get_order_number() ) ); ?><?php if ( $order_date ) : ?> (<?php
	$timestamp = $order_date->getTimestamp();
	printf( '<time datetime="%s">%s</time>', esc_attr( date_i18n( 'c', $timestamp ) ), esc_html( date_i18n( wc_date_format(), $timestamp ) ) );
?>)<?php endif; ?></h4>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;font-size: 11px;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;font-size: 11px;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;font-size: 11px;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th scope="col" width="40px" style="text-align:left; border: 1px solid #eee;font-size: 11px;"><?php _e( 'à hinta', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;font-size: 11px;"><?php _e( 'Yhteensä', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo wc_get_email_order_items( $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</tbody>
	<tfoot>
		<?php
		if ( $totals = $order->get_order_item_totals() ) {
			$i = 0;
			foreach ( $totals as $total ) {
				$i++;
				if ( $total['label'] === 'alv.:' ) {
					$total['label'] = 'Arvonlisävero:';
				}
				?>
				<tr>
					<th scope="row" colspan="3" style="text-align:left; border: 1px solid #eee;font-size: 11px; <?php if ( 1 === $i ) { echo 'border-top-width: 4px;'; } ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
					<td style="text-align:left; border: 1px solid #eee;font-size: 11px; <?php if ( 1 === $i ) { echo 'border-top-width: 4px;'; } ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
				</tr>
				<?php
			}
		}
		?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, true, false, $email ); ?>

<?php
/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, true, false, $email );
?>

<h4><?php _e( 'Customer details', 'woocommerce' ); ?></h4>

<?php
$billing_ytunnus = $order->get_meta( '_billing_ytunnus' );
if ( $billing_ytunnus ) :
	?>
	<p style="font-size: 11px;"><strong><?php _e( 'Y-tunnus:', 'woocommerce' ); ?></strong> <?php echo esc_html( $billing_ytunnus ); ?></p>
<?php endif; ?>
<?php if ( $order->get_billing_email() ) : ?>
	<p style="font-size: 11px;"><strong><?php _e( 'Email:', 'woocommerce' ); ?></strong> <?php echo esc_html( $order->get_billing_email() ); ?></p>
<?php endif; ?>
<?php if ( $order->get_billing_phone() ) : ?>
	<p style="font-size: 11px;"><strong><?php _e( 'Tel:', 'woocommerce' ); ?></strong> <?php echo esc_html( $order->get_billing_phone() ); ?></p>
<?php endif; ?>

<?php
wc_get_template(
	'emails/email-addresses.php',
	array(
		'order'         => $order,
		'sent_to_admin' => $sent_to_admin,
	)
);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation"><tr><td class="email-additional-content">' : '';
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
	echo $email_improvements_enabled ? '</td></tr></table>' : '';
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
