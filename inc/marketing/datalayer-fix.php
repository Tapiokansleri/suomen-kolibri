<?php
/**
 * Child theme fixes for parent Google Analytics datalayer.
 *
 * The parent theme datalayer can emit PHP warnings when product shortcodes are
 * rendered outside a normal storefront request (REST draft save, Relevanssi indexing).
 * This file disables or replaces those hooks in those contexts and adds safe
 * category handling for products without product_cat terms.
 */

/**
 * Whether parent product datalayer output should be skipped.
 *
 * @return bool
 */
function eeco_child_should_disable_bgh_product_data() {
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return true;
	}

	if ( class_exists( 'Kolibri_AI_Articles_Post_Builder' ) && Kolibri_AI_Articles_Post_Builder::is_draft_insert_isolated() ) {
		return true;
	}

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return true;
	}

	/**
	 * Filter whether the parent datalayer product hooks should be disabled.
	 *
	 * @param bool $disable Whether hooks should be disabled.
	 */
	return (bool) apply_filters( 'eeco_child_should_disable_bgh_product_data', false );
}

/**
 * Remove parent shop-loop datalayer hooks.
 */
function eeco_child_remove_bgh_shop_loop_hooks() {
	remove_action( 'woocommerce_before_shop_loop_item', 'bgh_add_product_data' );
	remove_action( 'woocommerce_single_product_summary', 'bgh_add_product_data', 70 );
	remove_action( 'woocommerce_livesearch_product_data', 'bgh_add_product_data' );
}

/**
 * Disable parent hooks when this is not a normal storefront product view.
 */
function eeco_child_maybe_disable_bgh_product_data() {
	if ( ! eeco_child_should_disable_bgh_product_data() ) {
		return;
	}

	eeco_child_remove_bgh_shop_loop_hooks();
}

add_action( 'init', 'eeco_child_maybe_disable_bgh_product_data', 1 );
add_action( 'after_setup_theme', 'eeco_child_replace_bgh_shop_loop_hooks', 100 );
add_action( 'woocommerce_before_shop_loop_item', 'eeco_child_maybe_disable_bgh_product_data', 0 );
add_action( 'woocommerce_single_product_summary', 'eeco_child_maybe_disable_bgh_product_data', 65 );
add_action( 'woocommerce_livesearch_product_data', 'eeco_child_maybe_disable_bgh_product_data', 0 );

/**
 * Replace parent shop-loop hooks with a child-safe implementation.
 */
function eeco_child_replace_bgh_shop_loop_hooks() {
	eeco_child_remove_bgh_shop_loop_hooks();

	if ( eeco_child_should_disable_bgh_product_data() ) {
		return;
	}

	add_action( 'woocommerce_before_shop_loop_item', 'eeco_child_bgh_add_product_data' );
	add_action( 'woocommerce_single_product_summary', 'eeco_child_bgh_add_product_data', 70 );
	add_action( 'woocommerce_livesearch_product_data', 'eeco_child_bgh_add_product_data' );
}

add_action( 'wp_loaded', 'eeco_child_replace_bgh_shop_loop_hooks', 20 );

/**
 * Safe shop-loop datalayer output.
 */
function eeco_child_bgh_add_product_data() {
	if ( eeco_child_should_disable_bgh_product_data() ) {
		return;
	}

	global $product;

	$data = eeco_child_create_product_data_for_analytics( $product );
	if ( ! $data ) {
		return;
	}

	?>
	<span class="bgh_productdata" style="display:none; visibility: hidden;"
		data-product_fb_content_id="<?php echo $product->is_type( 'variable' ) ? 'wc_post_id_' . esc_attr( $data['id'] ) : esc_attr( $data['sku'] ) . '_' . esc_attr( $data['id'] ); ?>"
		data-product_fb_product_type="<?php echo $product->is_type( 'variable' ) ? 'product_group' : 'product'; ?>"
		data-product_sku="<?php echo esc_attr( $data['sku'] ); ?>"
		data-product_id="<?php echo esc_attr( $data['id'] ); ?>"
		data-product_name="<?php echo esc_attr( $data['name'] ); ?>"
		data-product_price="<?php echo esc_attr( $data['price'] ); ?>"
		data-product_regular_price="<?php echo esc_attr( $data['original_price'] ); ?>"
		data-product_stock="<?php echo esc_attr( $data['stock'] ); ?>"
		data-product_brand="<?php echo esc_attr( $data['brand'] ); ?>"
		data-product_categories="<?php echo esc_attr( $data['cat'] ); ?>"
		data-product_variations="<?php echo esc_attr( $data['var'] ); ?>"
		data-product_currency="<?php echo esc_attr( $data['currency'] ); ?>"
		data-product_category1="<?php echo esc_attr( $data['category1'] ); ?>"
		data-product_category2="<?php echo esc_attr( $data['category2'] ); ?>"
		data-product_category3="<?php echo esc_attr( $data['category3'] ); ?>"
		data-product_category4="<?php echo esc_attr( $data['category4'] ); ?>"
		data-product_listname="<?php echo esc_attr( $data['list_name'] ); ?>"
		data-product_list_id="<?php echo esc_attr( $data['list_id'] ); ?>"
		data-product_list_index="<?php echo esc_attr( $data['list_index'] ); ?>"
		data-product_type="<?php echo esc_attr( $data['product_type'] ); ?>"
		data-product_url="<?php echo esc_url( $data['product_url'] ); ?>"
		data-product_sale="<?php echo esc_attr( $data['product_sale'] ); ?>"
		data-product_in_cart_quantity="<?php echo esc_attr( $data['product_in_cart_quantity'] ); ?>">
	</span>
	<?php
}

/**
 * Safe product analytics payload with guarded category handling.
 *
 * @param WC_Product|null $product Product object.
 * @return array|false
 */
function eeco_child_create_product_data_for_analytics( $product ) {
	if ( ! is_object( $product ) ) {
		return false;
	}

	if ( $product->get_parent_id() ) {
		$brand      = get_brand_name( $product->get_parent_id() );
		$variations = implode( ',', $product->get_attributes() );
	} else {
		$brand      = get_brand_name( $product->get_id() );
		$variations = '';
	}

	if ( $product->is_downloadable() ) {
		$product_type = 'Downloadable';
	} elseif ( $product->is_virtual() ) {
		$product_type = 'Virtual';
	} else {
		$product_type = 'Product';
	}

	$coupon_product = $product->is_on_sale() ? 'On Sale' : '';

	$products_in_cart = 0;
	if ( function_exists( 'WC' ) && WC()->cart ) {
		$cart            = WC()->cart->get_cart();
		$product_cart_id = WC()->cart->generate_cart_id( $product->get_id() );
		if ( WC()->cart->find_product_in_cart( $product_cart_id ) ) {
			$cart_item = $cart[ $product_cart_id ] ?? null;
			if ( is_array( $cart_item ) && isset( $cart_item['quantity'] ) ) {
				$products_in_cart = $cart_item['quantity'];
			}
		}
	}

	$current_id       = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	$terms            = get_the_terms( $current_id, 'product_cat' );
	$termsamount      = 0;
	$categories_array = array();
	$category         = '';
	$category1        = $category2 = $category3 = $category4 = '';

	if ( is_array( $terms ) && ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$termsamount++;
		}

		$number = 0;
		for ( $i = 1; $i <= $termsamount; $i++ ) {
			$categories_array[] = array(
				'nimi'     => $terms[ $number ]->name,
				'parentid' => $terms[ $number ]->parent,
				'id'       => $terms[ $number ]->term_id,
			);
			$number++;
		}

		$number = 0;
		for ( $i = 1; $i <= $termsamount; $i++ ) {
			$parentnumber = isset( $categories_array[ $number ] ) && is_array( $categories_array[ $number ] )
				? $categories_array[ $number ]['parentid']
				: 0;
			if ( 0 == $parentnumber ) {
				$primary_category = $categories_array[ $number ];
				array_splice( $categories_array, $number, 1 );
				array_unshift( $categories_array, $primary_category );
			}
			$number++;
		}

		if ( $termsamount > 1 ) {
			$primary_category_id = ( isset( $categories_array[0] ) && is_array( $categories_array[0] ) )
				? $categories_array[0]['id']
				: 0;
			for ( $i = 1; $i <= $termsamount; $i++ ) {
				$parentnumber = isset( $categories_array[ $i ] ) && is_array( $categories_array[ $i ] )
					? $categories_array[ $i ]['parentid']
					: '';
				if ( $primary_category_id == $parentnumber ) {
					$secondary_category = $categories_array[ $i ];
					array_splice( $categories_array, $i, 1 );
					array_splice( $categories_array, 1, 0, array( $secondary_category ) );
				}
			}
			if ( $termsamount > 2 ) {
				$secondary_category_id = ( isset( $categories_array[1] ) && is_array( $categories_array[1] ) )
					? $categories_array[1]['id']
					: 0;
				for ( $i = 2; $i <= $termsamount; $i++ ) {
					$parentnumber = isset( $categories_array[ $i ] ) && is_array( $categories_array[ $i ] )
						? $categories_array[ $i ]['parentid']
						: '';
					if ( $secondary_category_id == $parentnumber ) {
						$tertiary_category = $categories_array[ $i ];
						array_splice( $categories_array, $i, 1 );
						array_splice( $categories_array, 2, 0, array( $tertiary_category ) );
					}
				}
			}
		}

		for ( $i = 0; $i <= $termsamount; $i++ ) {
			$number = $i + 1;
			${"category$number"} = ( isset( $categories_array[ $i ] ) && is_array( $categories_array[ $i ] ) )
				? $categories_array[ $i ]['nimi']
				: '';
		}

		$category = ( isset( $categories_array[0] ) && is_array( $categories_array[0] ) )
			? $categories_array[0]['nimi']
			: '';
		for ( $i = 1; $i < $termsamount && $i < 5; $i++ ) {
			if ( isset( $categories_array[ $i ] ) && is_array( $categories_array[ $i ] ) ) {
				$category .= '/' . $categories_array[ $i ]['nimi'];
			}
		}
	}

	return array(
		'sku'                        => $product->get_sku(),
		'id'                         => $product->get_id(),
		'name'                       => $product->get_title(),
		'price'                      => $product->get_price(),
		'stock'                      => $product->get_stock_quantity(),
		'brand'                      => $brand,
		'cat'                        => $category,
		'var'                        => $variations,
		'currency'                   => get_woocommerce_currency(),
		'category1'                  => $category1,
		'category2'                  => $category2,
		'category3'                  => $category3,
		'category4'                  => $category4,
		'list_name'                  => '',
		'list_id'                    => '',
		'list_index'                 => '',
		'original_price'             => $product->get_regular_price(),
		'product_type'               => $product_type,
		'product_url'                => get_permalink( $product->get_id() ),
		'product_sale'               => $coupon_product,
		'product_in_cart_quantity'   => $products_in_cart,
	);
}
