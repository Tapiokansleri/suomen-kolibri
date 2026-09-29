<?php
/**
 * Email Order Items
 *
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

foreach ( $items as $item_id => $item ) :
	$product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );

	if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		continue;
	}

	$purchase_note = ( is_object( $product ) && $product->exists() ) ? $product->get_purchase_note() : '';
	?>
	<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
		<td class="td" style="text-align:left; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;"><?php

			if ( $show_image && is_object( $product ) && $product->exists() ) {
				echo apply_filters( 'woocommerce_order_item_thumbnail', '<div style="margin-bottom: 5px"><img src="' . ( $product->get_image_id() ? current( wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' ) ) : wc_placeholder_img_src() ) . '" alt="' . esc_attr__( 'Product Image', 'woocommerce' ) . '" height="' . esc_attr( $image_size[1] ) . '" width="' . esc_attr( $image_size[0] ) . '" style="vertical-align:middle; margin-right: 10px;" /></div>', $item );
			}

			echo apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false );

			if ( $show_sku && is_object( $product ) && $product->get_sku() ) {
				echo ' (#' . esc_html( $product->get_sku() ) . ')';
			}

			do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );

			wc_display_item_meta(
				$item,
				array(
					'before'    => '<br/><small>',
					'after'     => '</small>',
					'separator' => '<br/>',
				)
			);

			if ( $show_download_links && $order->is_download_permitted() ) {
				$downloads = $item->get_item_downloads();
				if ( ! empty( $downloads ) ) {
					echo '<br/><small>';
					foreach ( $downloads as $download ) {
						echo '<a href="' . esc_url( $download['download_url'] ) . '">' . esc_html( $download['name'] ) . '</a><br/>';
					}
					echo '</small>';
				}
			}

			do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );

			?></td>
		<td class="td" style="text-align:left; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo esc_html( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ); ?></td>
		<td class="td" style="text-align:left; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php
			$qty = (float) $item->get_quantity();
			if ( $qty > 0 ) {
				$price = (float) $item->get_total() / $qty;
				echo wp_kses_post( wc_price( $price ) );
			}
			?>
		</td>
		<td class="td" style="text-align:left; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></td>
	</tr>
	<?php

	if ( $show_purchase_note && $purchase_note ) :
		?>
		<tr>
			<td colspan="3" style="text-align:left; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
		</tr>
	<?php endif; ?>

<?php endforeach; ?>
