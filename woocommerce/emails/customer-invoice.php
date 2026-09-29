<?php
/**
 * Customer invoice email
 *
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email );

$order_date = $order->get_date_created();
?>

<?php if ( $order->has_status( 'pending' ) ) : ?>

	<p><?php printf( __( 'An order has been created for you on %s. To pay for this order please use the following link: %s', 'woocommerce' ), esc_html( get_bloginfo( 'name' ) ), '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'pay', 'woocommerce' ) . '</a>' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2 style="font-size: 14px;"><?php echo esc_html__( 'Order:', 'woocommerce' ) . ' ' . esc_html( $order->get_order_number() ); ?><?php if ( $order_date ) : ?> (<?php
	$timestamp = $order_date->getTimestamp();
	printf( '<time datetime="%s">%s</time>', esc_attr( date_i18n( 'c', $timestamp ) ), esc_html( date_i18n( wc_date_format(), $timestamp ) ) );
?>)<?php endif; ?></h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'woocommerce' ); ?></th>
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
					<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( 1 === $i ) { echo 'border-top-width: 4px;'; } ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
					<td style="text-align:left; border: 1px solid #eee; <?php if ( 1 === $i ) { echo 'border-top-width: 4px;'; } ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
				</tr>
				<?php
			}
		}
		?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>
