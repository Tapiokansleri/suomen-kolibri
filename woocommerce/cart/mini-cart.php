<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.7.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>
	<div class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );
		$categories = [];

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			$terms = wp_get_post_terms($product_id, 'product_cat' );

			if(isset($categories[$terms[0]->name])){
				$categories[$terms[0]->name][] = $cart_item;
			}
			else{
				$categories[$terms[0]->name] = array($cart_item);
			}
		}
		
		ksort($categories);

		foreach($categories as $term => $items) {
			//echo '<h3 class="cart-category">'.$term.' ('.count($categories[$term]).')</h3>';
			foreach($items as $cart_item) {
				$cart_item_key = $cart_item['key'];  
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					// Get the product name - for variations, this already includes the variation attributes
					$product_name = $_product->get_name();
					
					// Apply the filter to ensure consistent naming
					$product_name = apply_filters( 'woocommerce_cart_item_name', $product_name, $cart_item, $cart_item_key );
					$thumbnail    = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					
					$product_price = apply_filters( 'woocommerce_cart_item_price', $_product->get_price_html(), $cart_item, $cart_item_key );

					if(class_exists('WC_Name_Your_Price') && WC_Name_Your_Price_Helpers::is_nyp($product_id)){
						$product_price = number_format($cart_item['price'], 2, ',', '');
					}

					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<div class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
						<?php do_action('bgh_add_minicart_data', $cart_item); ?>
							<?php if ( empty( $product_permalink ) ) : ?>
								<div class="img-wrapper">
									<?php echo $thumbnail ?>
								</div>
								<div class="mini-cart-details">
									<?php echo $product_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
								<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity line-price mb-2">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
							<?php else : ?>
								<div class="img-wrapper">
									<?php echo $thumbnail ?>
								</div>
								<div class="mini-cart-details">
									<div class="mini-cart-product-details">
										<a href="<?php echo esc_url( $product_permalink ); ?>" class="mini-cart-item__name_link d-block text-dark"><?php echo $product_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
										<?php 
										if ( $_product->get_price() != 0 ) {
											echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity line-price mb-2">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); 
										} else {
											echo 'Pyydä tarjous!';
										}
										?>
									</div>
									<?php 
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" rel="nofollow"><i class="far fa-times"></i></a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									__( 'Remove this item', 'woocommerce' ),
									esc_attr( $product_id ),
									esc_attr( $cart_item_key ),
									esc_attr( $_product->get_sku() )
								), $cart_item_key ); ?>
								</div>
							<?php endif; ?>
						<?php if(!is_cart()): ?>
							<div class="mini-cart-qty">
								<?php
								if(!isset($cart_item["is_gift"]) || !$cart_item["is_gift"]){
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'   => "$cart_item_key",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'min_value'    => '0',
											'product_name' => $_product->get_name(),
										), $_product, false );
									}
									echo '<div class="qty-holder">';
										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
									echo '</div>';
								} ?>
							</div>
						<?php endif;?>
					</div>
					<?php
				}
			}
		}
		do_action( 'woocommerce_mini_cart_contents' ); ?>
	</div>

	<p class="woocommerce-mini-cart__total total my-3"><strong><?php _e( 'Subtotal', 'woocommerce' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
