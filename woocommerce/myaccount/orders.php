<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>
	<?php
	foreach ( $customer_orders->orders as $customer_order ) :
		$order         = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$item_count    = $order->get_item_count();
		$product_count = count( $order->get_items() ) == 1 ? '' : sprintf( '+ %s %s', count( $order->get_items() ) - 1, _x( 'other products', 'My account orders', 'bgh-theme' ) );
		?>
		<div class="mb-3 order-row">
			<div class="order-row-header p-3">
				<?php printf( '<p>%s #%s</p>', __( 'Order', 'bgh-theme' ), $order->get_order_number() ); ?>
				<p><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></p>
			</div>
			<div class="order-row-details p-3">
				<div class="order-details-products">
					<?php
					foreach ( $order->get_items() as $item_id => $item ) {
						$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
						$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
						$product           = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );
						if ( $product ) {
							echo $product->get_image( $thumbnail_size );
							echo '<p><strong>' . $product->get_name() . '</strong><br>';
							echo $product_count . '</p>';
						}
						break;
					}
					?>
				</div>
				<div class="order-details-price">
					<?php
					$order_status = $order->get_status();
					if ( 'completed' == $order_status ) :
						?>
						<span class="status-dot ready"></span>
						<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
					<?php elseif ( 'processing' == $order_status ) : ?>
						<span class="status-dot processing"></span>
						<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
					<?php elseif ( 'cancelled' == $order_status ) : ?>
						<span class="status-dot cancelled"></span>
						<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
					<?php endif; ?>
					<p><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></p>
				</div>
			</div>
			<div class="order-buttons p-3">
				<?php
				$actions = wc_get_account_orders_actions( $order );
				$view    = $actions['view'];
				if ( function_exists( 'eeco_get_pdf_receipt' ) ) {
					$receipt_url = eeco_get_pdf_receipt( $order->get_order_number() );
					if ( $receipt_url ) {
						echo '<a class="button primary-cta mb-3 mb-md-0" target="_blank" href="' . esc_url( $receipt_url ) . '">' . __( 'Download', 'bgh-theme' ) . '</a>';
					}
				}

				if ( empty( $view['aria-label'] ) ) {
					// Generate the aria-label based on the action name.
					/* translators: %1$s Action name, %2$s Order number. */
					$view_aria_label = sprintf( __( '%1$s order number %2$s', 'woocommerce' ), $view['name'], $order->get_order_number() );
				} else {
					$view_aria_label = $view['aria-label'];
				}
				?>
				<a class="button primary-cta" href="<?php echo esc_url( $view['url'] ); ?>" aria-label="<?php echo esc_attr( $view_aria_label ); ?>"><?php echo esc_html( $view['name'] ); ?></a>
				<?php unset( $view_aria_label ); ?>
			</div>
		</div>
	<?php endforeach; ?>
	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination mt-5 d-flex justify-content-between">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button primary-cta<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'bgh-theme' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button primary-cta<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'bgh-theme' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info" role="status">
		<p><?php esc_html_e( 'No order has been made yet.', 'bgh-theme' ); ?></p>
		<a class="woocommerce-Button button primary-cta<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Go shop', 'bgh-theme' ); ?></a>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
