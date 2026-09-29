<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 *
 * @var bool $show_downloads Controls whether the downloads table should be rendered.
 */

 // phpcs:disable WooCommerce.Commenting.CommentHooks.MissingHookComment

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

$order_items        = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$downloads          = $order->get_downloadable_items();
$actions            = array_filter(
	wc_get_account_orders_actions( $order ),
	function ( $key ) {
		return 'view' !== $key;
	},
	ARRAY_FILTER_USE_KEY
);

// We make sure the order belongs to the user. This will also be true if the user is a guest, and the order belongs to a guest (userID === 0).
// Logged-in only: guests already get the customer/billing blocks below on the thank-you page.
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<section class="woocommerce-order-details">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

	<div class="woocommerce-table woocommerce-table--order-details shop_table order_details">
		<?php
		do_action( 'woocommerce_order_details_before_order_table_items', $order );

		foreach ( $order_items as $item_id => $item ) {
			$product = $item->get_product();

			wc_get_template(
				'order/order-details-item.php',
				array(
					'order'              => $order,
					'item_id'            => $item_id,
					'item'               => $item,
					'show_purchase_note' => $show_purchase_note,
					'purchase_note'      => $product ? $product->get_purchase_note() : '',
					'product'            => $product,
				)
			);
		}

		do_action( 'woocommerce_order_details_after_order_table_items', $order );
		?>
		<div class="order-details-wrapper">
			<?php if ( ! empty( $actions ) ) : ?>
				<div class="order-details order-actions d-flex justify-content-between py-2">
					<p class="order-actions--heading"><?php esc_html_e( 'Actions', 'woocommerce' ); ?>:</p>
					<p class="text-right">
						<?php
						$wp_button_class = wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '';
						foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
							if ( empty( $action['aria-label'] ) ) {
								// Generate the aria-label based on the action name.
								/* translators: %1$s Action name, %2$s Order number. */
								$action_aria_label = sprintf( __( '%1$s order number %2$s', 'woocommerce' ), $action['name'], $order->get_order_number() );
							} else {
								$action_aria_label = $action['aria-label'];
							}
							echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button' . esc_attr( $wp_button_class ) . ' button ' . sanitize_html_class( $key ) . ' order-actions-button " aria-label="' . esc_attr( $action_aria_label ) . '">' . esc_html( $action['name'] ) . '</a>';
							unset( $action_aria_label );
						}
						?>
					</p>
				</div>
			<?php endif; ?>
			<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					?>
					<div class="order-details d-flex justify-content-between py-2">
						<p><?php echo esc_html( $total['label'] ); ?></p>
						<p class="text-right"><?php echo wp_kses_post( $total['value'] ); ?></p>
					</div>
					<?php
				}
			?>
			<?php if ( $order->get_customer_note() ) : ?>
				<div class="p-2">
					<p><strong><?php _e( 'Note:', 'bgh-theme' ); ?></strong></p>
					<p>
					<?php
					$customer_note = wc_wptexturize_order_note( $order->get_customer_note() );
					echo wp_kses( nl2br( $customer_note ), array( 'br' => array() ) );
					?>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<?php
		// GET POSTI NOUTOPISTE NAME & ADDRESS
		// show_order_meta_posti_noutopiste - located at inc/woocommerce/thank-you.php

		$noutopiste = show_order_meta_noutopiste(array('smartpost-uf-noutopiste-result', 'noutopiste-result'), $order->get_id());

		if ( $noutopiste ): ?>

			<?php 
			$url_noutopiste = urlencode($noutopiste[1]);

			echo '<div class="box-shadow mb-3 py-3 px-2"><p><strong>';
			_e('Pick-up point', 'bgh-theme');
			echo '</strong></p><p>'.$noutopiste[0].'</p>';
			echo '<p>'. $noutopiste[1].'</p></div>';

			$maps_key = get_option( 'suomen_kolibri_google_maps_key' );
			if ( $maps_key ) :
			?>
			<div class="box-shadow mb-3">
				<p class="px-2 py-3"><strong><?php _e('Pick-up point on the map', 'bgh-theme'); ?></strong></p>
				<iframe
				  width="100%"
				  height="286"
				  frameborder="0" style="border:0"
				  src="https://www.google.com/maps/embed/v1/search?key=<?php echo esc_attr( $maps_key ); ?>&q=<?php echo $url_noutopiste; ?>" allowfullscreen>
				</iframe>
			</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if(!is_account_page()): ?>
			<div class="mb-3">
				<?php 
				// GET CUSTOMER DETAILS
				$full_name = $order->get_formatted_billing_full_name();
				$last_name = $order->get_billing_last_name();
				$email = $order->get_billing_email();
				$phone = $order->get_billing_phone();
				?>
				<p class="customer-details"><strong><?php _e('Customer details', 'bgh-theme'); ?></strong></p>
				<p><?php echo $full_name; ?></p>
				<p><?php echo $email; ?></p>
				<p><?php echo $phone; ?></p>
			</div>
			<div class="mb-3">
				<?php 
				// GET CUSTOMER DETAILS
				$address = $order->get_address(); ?>
				<p class="customer-details"><strong><?php echo _x('Billing information', 'Order received page', 'bgh-theme'); ?></strong></p>
				<p><?php echo $address["email"]; ?></p>
				<p><?php echo $address["first_name"] . ' ' . $address["last_name"]; ?></p>
				<p><?php echo $address["address_1"] . ', ' . $address["postcode"] . ' ' . $address["city"]; ?></p>
				<p><?php echo $address["country"]; ?></p>
				<p><?php echo $address["phone"]; ?></p>
			</div>
		<?php endif; ?>
	</div>
	<?php
	// ASK USER TO REGISTER IF NOT LOGGED ID

	$my_account_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
	
	if (!is_user_logged_in() && get_option('woocommerce_enable_myaccount_registration') == 'yes' ): ?>

		<div class="order-received--register mb-3">
			<p><strong><?php _e('Save time with every order', 'bgh-theme'); ?></strong></p>
			<p><?php _e('Register account to save your shipping and billing address. From your account, you can see your purchase history and download receipts. Registration takes less than a minute and will make your purchases more enjoyable in the future.', 'bgh-theme'); ?></p>
			<a class="button primary-cta mt-3" href="<?php echo $my_account_url ?>"><?php _e('Register', 'bgh-theme'); ?></a>
		</div>

	<?php endif;?>
	<?php
	$coupon_code = '';
	foreach ( $order->get_coupon_codes() as $coupon_code_item ) {
		$coupon   = new WC_Coupon( $coupon_code_item );
		$coupon_code = $coupon->get_code();
	}
	?>
	<span class="bgh_orderdata"  data-transaction_id="<?php if ( $order->get_order_number() ) { echo $order->get_order_number(); } else { echo $order->get_id(); } ?>" data-transaction_value="<?php echo $order->get_total(); ?>" data-currency="<?php echo $order->get_currency(); ?>" data-tax="<?php echo $order->get_total_tax(); ?>" data-shipping="<?php echo $order->get_shipping_total(); ?>" data-coupon-code="<?php echo esc_attr( $coupon_code ); ?>">
		<?php 

		$list_id = '';
		$list_name = 'Confirmation page';			
		$list_index = 1;

		foreach ( $order_items as $item_id => $item ) {
			$product = $item->get_product();
			if ( ! $product ) {
				$list_index++;
				continue;
			}

			$categories_array = array();
			$category1        = '';
			$category2        = '';
			$category3        = '';
			$category4        = '';

			$parent_id = $product->get_parent_id();

			// Variant product data
			if ( $parent_id ) {
				$parentproduct = $parent_id;
				$brand         = get_brand_name( $parentproduct );
				$category      = get_category_for_product( $parentproduct );
				$attributes    = $product->get_attributes();
				$variations    = is_array( $attributes ) ? implode( ',', $attributes ) : '';

			// Simple product data
			} else {
				$brand      = get_brand_name( $product->get_id() );
				$category   = get_category_for_product( $product->get_id() );
				$variations = '';
			}

			// Product categories separated
			if ( $parent_id ) {
				$current_id = $parent_id;
			} else {
				$current_id = $product->get_id();
			}

			// Get product categories
			$terms       = get_the_terms( $current_id, 'product_cat' );
			$termsamount = 0;

			if ( is_array( $terms ) && ! is_wp_error( $terms ) ) {
				$termsamount = count( $terms );

				// Create an array for the category names, id's and parent id's
				$number = 0;
				for ( $i = 1; $i <= $termsamount; $i++ ) {
					if ( ! isset( $terms[ $number ] ) ) {
						break;
					}
					$categories_array[] = array(
						'nimi'     => $terms[ $number ]->name,
						'parentid' => $terms[ $number ]->parent,
						'id'       => $terms[ $number ]->term_id,
					);
					$number++;
				}

				// Arrange the array so that the parent category comes first
				$number = 0;
				for ( $i = 1; $i <= $termsamount; $i++ ) {
					if ( ! isset( $categories_array[ $number ]['parentid'] ) ) {
						break;
					}
					$parentnumber = $categories_array[ $number ]['parentid'];
					if ( 0 === (int) $parentnumber ) {
						$primary_category = $categories_array[ $number ];
						array_splice( $categories_array, $number, 1 );
						array_unshift( $categories_array, $primary_category );
					}
					$number++;
				}

				// Arrange the rest of the array so it will be in hierarchical order
				if ( $termsamount > 1 && isset( $categories_array[0]['id'] ) ) {
					$primary_category_id = $categories_array[0]['id'];
					for ( $i = 1; $i < $termsamount; $i++ ) {
						if ( ! isset( $categories_array[ $i ]['parentid'] ) ) {
							continue;
						}
						$parentnumber = $categories_array[ $i ]['parentid'];
						if ( (int) $primary_category_id === (int) $parentnumber ) {
							$secondary_category = $categories_array[ $i ];
							array_splice( $categories_array, $i, 1 );
							array_splice( $categories_array, 1, 0, array( $secondary_category ) );
						}
					}
					if ( $termsamount > 2 && isset( $categories_array[1]['id'] ) ) {
						$secondary_category_id = $categories_array[1]['id'];
						for ( $i = 2; $i < $termsamount; $i++ ) {
							if ( ! isset( $categories_array[ $i ]['parentid'] ) ) {
								continue;
							}
							$parentnumber = $categories_array[ $i ]['parentid'];
							if ( (int) $secondary_category_id === (int) $parentnumber ) {
								$tertiary_category = $categories_array[ $i ];
								array_splice( $categories_array, $i, 1 );
								array_splice( $categories_array, 2, 0, array( $tertiary_category ) );
							}
						}
					}
				}

				for ( $i = 0; $i < $termsamount; $i++ ) {
					$number = $i + 1;
					if ( isset( $categories_array[ $i ]['nimi'] ) ) {
						${'category' . $number} = $categories_array[ $i ]['nimi'];
					}
				}
			}

			// Get product type
			if ( $product->is_downloadable() ) {
				$product_type = 'Downloadable';
			} elseif ( $product->is_virtual() ) {
				$product_type = 'Virtual';
			} else {
				$product_type = 'Product';
			}

			if ( $product->is_on_sale() ) {
				$coupon_product = 'On Sale';
			} else {
				$coupon_product = '';
			}

			$sku                      = $product->get_sku();
			$id                       = $product->get_id();
			$name                     = $product->get_title();
			$price                    = $product->get_price();
			$stock                    = $product->get_stock_quantity();
			$cat                      = $category;
			$var                      = $variations;
			$currency                 = get_woocommerce_currency();
			$original_price           = $product->get_regular_price();
			$product_url              = get_permalink( $product->get_id() );
			$product_sale             = $coupon_product;
			$product_in_cart_quantity = $item->get_quantity();
			$fb_id                    = $product->is_type( 'simple' ) ? $product->get_sku() . '_' . $product->get_id() : 'wc_post_id_' . $product->get_id();

			echo '
					<div class="product-data">
					<span class="bgh_order_items" style="display:none; visibility: hidden;"
						data-product_sku="' . esc_attr( $sku ) . '"
						data-product_id="' . esc_attr( $id ) . '"
						data-product_name="' . esc_attr( $name ) . '"
						data-product_price="' . esc_attr( $price ) . '"
						data-product_regular_price="' . esc_attr( $original_price ) . '"
						data-product_quantity="' . esc_attr( $product_in_cart_quantity ) . '"
						data-product_stock="' . esc_attr( $stock ) . '"
						data-product_brand="' . esc_attr( $brand ) . '"
						data-product_categories="' . esc_attr( $cat ) . '"
						data-product_variations="' . esc_attr( $var ) . '"
						data-product_currency="' . esc_attr( $currency ) . '"
						data-product_category1="' . esc_attr( $category1 ) . '"
						data-product_category2="' . esc_attr( $category2 ) . '"
						data-product_category3="' . esc_attr( $category3 ) . '"
						data-product_category4="' . esc_attr( $category4 ) . '"
						data-product_listname="' . esc_attr( $list_name ) . '"
						data-product_list_id="' . esc_attr( $list_id ) . '"
						data-product_list_index="' . esc_attr( $list_index ) . '"
						data-product_type="' . esc_attr( $product_type ) . '"
						data-product_url="' . esc_url( $product_url ) . '"
						data-product_sale="' . esc_attr( $product_sale ) . '"
						data-product_bought_amount="' . esc_attr( $product_in_cart_quantity ) . '"
						data-product_fb_content_id="' . esc_attr( $fb_id ) . '"
					></span>
					</div>
				';

			$list_index++;
		}
		?>
	</span>
	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
