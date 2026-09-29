<div class="cart-table design-2">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="cart-table--header">
                    <h1><?php the_title(); echo ' <span class="items-in-cart">('.$args['products_in_cart'].' '. ( ( $args['products_in_cart'] == 1 ) ? _x('product', 'One product in the cart', 'bgh-theme') : _x('products', 'X amount of products in the cart', 'bgh-theme') ).')'; ?></span></h1>
                    <a href="<?php echo esc_url( wc_get_checkout_url() );?>" class="button primary-cta"><?php esc_html_e( 'Proceed to checkout', 'bgh-theme' ); ?></a>
                </div>
                <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                    <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
                        <?php 
                        do_action( 'woocommerce_before_cart_table' );
                        do_action( 'woocommerce_before_cart_contents' );

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

                        foreach($categories as $term => $items){
                            foreach($items as $cart_item) {
                                $cart_item_key = $cart_item['key'];
                                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
            
                                    $product_price     = apply_filters( 'woocommerce_cart_item_price', $_product->get_price_html(), $cart_item, $cart_item_key );
                                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key ); ?>
    
                                    <div class="product-line <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                        <div class="img-wrapper">
                                            <?php
                                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                            if ( ! $product_permalink ) {
                                                    echo wp_kses_post( $thumbnail );
                                            } else {
                                                printf( '<a class="cart_item__img_link" href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
                                            } ?>
                                        </div>
                                        <div class="cart-product-details">
                                            <div class="product" data-title="<?php esc_attr_e( 'Product', 'bgh-theme' ); ?>" >
                                                <?php
                                                if ( ! $product_permalink ) {
                                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                                } else {
                                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="cart-item cart_item__name_link" href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                                }
    
                                                do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
    
                                                // Meta data.
                                                echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
    
                                                // Backorder notification.
                                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'bgh-theme' ) . '</p>' ) );
                                                }
                                                // @codingStandardsIgnoreLine
                                                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                                    '<a href="%s" class="remove-product small" aria-label="%s" data-product_id="%s" data-product_sku="%s" rel="nofollow"><i class="far fa-trash-alt"></i> '.__('Remove', 'bgh-theme').'</a>',
                                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                    _x( 'Remove this item', 'Cart', 'bgh-theme' ),
                                                    esc_attr( $product_id ),
                                                    esc_attr( $_product->get_sku() )
                                                ), $cart_item_key );
                                                ?>
                                            </div>
                                            <div class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'bgh-theme' ); ?>">
                                                <div class="cart-qty">
                                                    <?php
                                                    if(!isset($cart_item["is_gift"]) || !$cart_item["is_gift"]){
                                                        if ( $_product->is_sold_individually() ) {
                                                            $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                        } else {
                                                            $product_quantity = woocommerce_quantity_input( array(
                                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                                'input_value'  => $cart_item['quantity'],
                                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                                'min_value'    => '0',
                                                                'product_name' => $_product->get_name(),
                                                            ), $_product, false );
                                                        }
                                                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                                    } ?>
                                                </div>
                                                <div class="line-price small">
													<?php if ( $_product->get_price() != 0 ) : ?>
														<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?><br>
														<span><strong><?php _e('Total', 'bgh-theme'); ?></strong></span>
														<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok. ?>
													<?php else :
														echo 'Pyydä tarjous';
													endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; 
                            }
                        }
                        do_action( 'woocommerce_cart_contents' ); ?>
                        <div class="cart-actions actions">
                            <button type="submit" class="button primary-cta" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'bgh-theme' ); ?>"><?php esc_html_e( 'Update cart', 'bgh-theme' ); ?></button>
                        </div>
                        <?php 
                        do_action( 'woocommerce_cart_actions' );
                        wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php do_action( 'woocommerce_after_cart_table' ); ?>
<div class="cart-collaterals design-2">
    <?php
    /**
     * Cart collaterals hook.
     *
     * @hooked woocommerce_cross_sell_display
     * @hooked woocommerce_cart_totals - 10
     */
    do_action( 'woocommerce_cart_collaterals' ); ?>
</div>
<?php if (get_option('bgh_under_cart_recommendations_enabled') || get_option('bgh_show_cross_sell_under_cart') || get_option('bgh_show_recently_viewed_under_cart')): ?>
    <div class="cart-carousels">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php if (get_option('bgh_under_cart_recommendations_enabled')): ?>
                        <?php do_action('bgh_badges_is_carousel', true) ?>
                        <div class="product-carousel">
                            <div class="carousel-row bgh-custom-list">
                                <?php get_template_part('template-parts/hand-picked'); ?>
                            </div>
                        </div>
                        <?php do_action('bgh_badges_is_carousel', false) ?>
                    <?php endif; ?>
                    <?php if (get_option('bgh_show_cross_sell_under_cart')): ?>
                        <?php do_action('bgh_badges_is_carousel', true) ?>
                        <div class="product-carousel">
                            <div class="carousel-row cross-sells bgh-cross-sell">
                                <?php 
                                // DEFINE CROSS SELL ARGUMENTS
                                woocommerce_cross_sell_display( $posts_per_page = 8, $orderby = 'rand' ); ?>
                            </div>
                        </div>
                        <?php do_action('bgh_badges_is_carousel', false) ?>
                    <?php endif; ?>
                    <?php if (get_option('bgh_show_recently_viewed_under_cart')): ?>
                        <div class="product-carousel">
                            <div class="carousel-row bgh-recently-viewed">
                                <h3><?php echo get_option('bgh_recent_under_cart_header') ?></h3>
                                <?php get_template_part('/template-parts/recently-viewed'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>