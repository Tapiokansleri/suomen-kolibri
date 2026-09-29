<?php global $wpo_wcpdf; ?>
<table class="head container">
	<tr>
		<td class="header">
		<?php
		if( $wpo_wcpdf->get_header_logo_id() ) {
			$wpo_wcpdf->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) );
		}
		?>
		</td>
		<td class="shop-info">
			
			<div class="shop-address"><?php $wpo_wcpdf->shop_address(); ?></div>
		</td>
	</tr>
	<tr>
		<td>
			<h3 class="document-type-label">
			<?php if( $wpo_wcpdf->get_header_logo_id() ) echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) ); ?>
			</h3>
			<?php do_action( 'wpo_wcpdf_after_document_label', 'invoice' ); ?>
		</td>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td>
			<div class="order-information">
			<?php if ( isset($wpo_wcpdf->settings->template_settings['display_number']) && $wpo_wcpdf->settings->template_settings['display_number'] == 'invoice_number') { ?>
				<span class="order-number-label"><?php _e( 'Invoice Number:', 'wpo_wcpdf' ); ?></span>
				<span class="order-number">101-<?php $wpo_wcpdf->invoice_number(); ?></span><br />
			<?php } ?>
			<?php if ( isset($wpo_wcpdf->settings->template_settings['display_date']) && $wpo_wcpdf->settings->template_settings['display_date'] == 'invoice_date') { ?>
				<span class="order-date-label"><?php _e( 'Invoice Date:', 'wpo_wcpdf' ); ?></span>
				<span class="order-date"><?php $wpo_wcpdf->invoice_date(); ?></span><br />
			<?php } ?>
				<span class="order-number-label"><?php _e( 'Order Number:', 'wpo_wcpdf' ); ?></span>
				<span class="order-number"><?php $wpo_wcpdf->order_number(); ?></span><br />
				<span class="order-date-label"><?php _e( 'Order Date:', 'wpo_wcpdf' ); ?></span>
				<span class="order-date"><?php $wpo_wcpdf->order_date(); ?></span><br />
				<span class="order-payment-label"><?php _e( 'Payment Method:', 'wpo_wcpdf' ); ?></span>
				<span class="order-payment"><?php $wpo_wcpdf->payment_method(); ?></span><br />
			</div>
		</td>
		<td>
			<div class="recipient-address"><?php $wpo_wcpdf->billing_address(); ?></div>
			<div class="recipient-phone"><?php $wpo_wcpdf->billing_phone(); ?></div>
			<?php $vatno = get_post_meta( $wpo_wcpdf->export->order_ids[0], '_billing_ytunnus', true ); ?>
			<?php if( $vatno ) : ?>
			<div class="vat-id">Y-Tunnus: <?php echo $vatno; ?></div>
			<?php endif; ?>
		</td>
	</tr>
</table><!-- head container -->

<?php do_action( 'wpo_wcpdf_before_order_details', 'invoice' ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="product-label"><?php _e('Product', 'wpo_wcpdf'); ?></th>
			<th class="quantity-label"><?php _e('Quantity', 'wpo_wcpdf'); ?></th>
			<th class="price-label"><?php _e('à', 'wpo_wcpdf'); ?></th>
			<th class="price-label"><?php _e('Hinta alv. 0%', 'wpo_wcpdf'); ?></th>
			<th class="tax-label"><?php _e('Alv 24%', 'wpo_wcpdf'); ?>
		</tr>
	</thead>
	<tbody>
		<?php $items = $wpo_wcpdf->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item ) : ?><tr>
			<td class="description">
				<?php $description_label = __( 'Description', 'wpo_wcpdf' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span><span class="item-meta"><?php echo $item['meta']; ?></span>
				<dl class="meta">
					<?php $description_label = __( 'SKU', 'wpo_wcpdf' ); // registering alternate label translation ?>
					<?php if( !empty( $item['sku'] ) ) : ?><dt><?php _e( 'SKU:', 'wpo_wcpdf' ); ?></dt><dd><?php echo $item['sku']; ?></dd><?php endif; ?>
					<?php if( !empty( $item['weight'] ) ) : ?><dt><?php _e( 'Weight:', 'wpo_wcpdf' ); ?></dt><dd><?php echo $item['weight']; ?><?php echo get_option('woocommerce_weight_unit'); ?></dd><?php endif; ?>
				</dl>
			</td>
			<td class="quantity"><?php echo $item['quantity']; ?></td>
			<td class="price"><?php echo $item['ex_single_price']; ?></td>
			<td class="price"><?php echo $item['order_price']; ?></td>
			<td class="line-tax"><?php echo $item['item']->get_subtotal_tax(); ?> €</td>
		</tr><?php endforeach; endif; ?>
	</tbody>
	<tfoot>
		<tr class="no-borders">
			<td class="no-borders" colspan="4">
				<table class="totals">
					<tfoot>
						<?php foreach( $wpo_wcpdf->get_woocommerce_totals() as $key => $total ) : ?>
						<tr class="<?php echo $key; ?>">
							<td class="no-borders">&nbsp;</td>
							<th class="description"><?php echo $total['label']; ?></th>
							<td class="price"><?php echo $total['value']; ?></td>
						</tr>
						<?php endforeach; ?>
					</tfoot>
				</table>
			</td>

		</tr>
	</tfoot>
</table><!-- order-details -->

<?php do_action( 'wpo_wcpdf_after_order_details', 'invoice' ); ?>

<table class="notes container">
	<tr>
		<td colspan="3">
			<div class="notes-shipping">
				<?php if ( $wpo_wcpdf->get_shipping_notes() ) : ?>
					<h3><?php _e( 'Customer Notes', 'wpo_wcpdf' ); ?></h3>
					<?php $wpo_wcpdf->shipping_notes(); ?>
				<?php endif; ?>
			</div>
		</td>
	</tr>
</table><!-- notes container -->


<?php if ( $wpo_wcpdf->get_footer() ): ?>
<div id="footer">
	<table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td align="left" valign="top" id="credit" style="font-family: Arial;
font-size: 10px; line-height:125%;">
                                                 Suomen&nbsp;Kolibri&nbsp;Oy<br>
												 Kalevantie&nbsp;7<br>
												 33100&nbsp;TAMPERE</td>
												 <td valign="top" style="font-family: Arial;
font-size: 10px; line-height:125%;">+358 (0)3 3454 5000<br>kolibri@suomenkolibri.fi<br>
												 www.suomenkolibri.fi</td>
												 <td valign="top" style="font-family: Arial;
font-size: 10px; line-height:125%;">Kotipaikka&nbsp;Tampere<br>
Y-tunnus:&nbsp;0833183-2<br>
Alv.rek</td>
<td valign="top" style="font-family: Arial;
font-size: 10px; line-height:125%;">Pankkiyhteydet:<br>
Pankki: TSOP<br>
IBAN: FI09 5731 8220 0140 03<br>
BIC: OKOYFIHH<br>
</td>

												 





                                                        </td>
                                                    </tr>
                                                </table>
</div><!-- #letter-footer -->
<?php endif; ?>
