<?php

// Add button for quote request modal after add to cart button
function eeco_child_add_gf_quote_button() {
	
	global $product;
	$catalog_visibility = $product->get_catalog_visibility();

	if ( $catalog_visibility != 'hidden') :
	?>
		<button type="button" class="button secondary-cta quote-modal" data-toggle="modal" data-target="#quoteModal"><?php echo _x('Jätä tarjouskysely', 'Tuotesivu', 'bgh-theme-child');?></button>
	<?php
	endif;
}
add_action('woocommerce_after_add_to_cart_button', 'eeco_child_add_gf_quote_button');

function eeco_child_quote_modal() {
	?>
	<div class="modal fade" id="quoteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
    		<div class="modal-content">
      			<div class="modal-header">
        			<h5 class="modal-title" id="exampleModalLabel"><?php echo _x('Lähetä tarjouspyyntö tuotteesta: ', 'Tuotesivu', 'bgh-theme-child'); the_title(); ?></h5>
        			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          				<span aria-hidden="true">&times;</span>
        			</button>
      			</div>
      			<div class="modal-body">
					<?php echo do_shortcode('[gravityform id="1" title="false" description="false" ajax="true"]'); ?>
	  			</div>
      		</div>
      	</div>
    </div>
	<?php
}
add_action( 'wp_footer', 'eeco_child_quote_modal');

function eeco_child_add_product_title_gf_field( $value ) {
	
	$value = get_the_title();
	return $value;
}
add_filter('gform_field_value_gf_product_title', 'eeco_child_add_product_title_gf_field', 10, 1);


// IF PRODUCT PRICE SET 0.00 Change price as quote request
function eeco_child_product_zero_price( $price ) {
	global $product;

	if ( $product ) {
		$product_price = $product->get_price();
		$catalog_visibility = $product->get_catalog_visibility();

		if ( $product_price == 0 && $catalog_visibility != 'hidden') {
			$price = 'Pyydä tarjous!';
		} else if ( $product_price == 0 && $catalog_visibility == 'hidden') {
			$price = '';
		}
	}

	return $price;
	
}
add_filter( 'woocommerce_get_price_html', 'eeco_child_product_zero_price', 10, 1 );




// Show primary category in breadcrumbs
function eeco_child_breadcrumb_links( $links ) {
    global $product;
    if ( is_product() ) { 

        $primary_cat = get_post_meta( $product->get_id(), 'eeco_primary_category', true );
        if ( empty( $primary_cat ) ) {
            return $links;
        }

        $primary_term = get_term( $primary_cat );
        if ( is_wp_error( $primary_term ) || ! $primary_term ) {
            return $links;
        }

        $primary_term_parent = wp_get_term_taxonomy_parent_id( $primary_cat, 'product_cat' );
        $category_ids = $product->get_category_ids();
        $primary_ancestors = get_ancestors( $primary_cat, 'product_cat' );
        $primary_term_parent_term = $primary_term_parent ? get_term( $primary_term_parent ) : null;

        if ( $primary_term_parent_term && is_wp_error( $primary_term_parent_term ) ) {
            $primary_term_parent_term = null;
        }
        foreach($links as $key => $link){
            if(isset($link['term_id'])){
                if($link['term_id'] != $primary_cat){
                    $term = get_term( $link['term_id'] );
                    if ( is_wp_error( $term ) || ! $term ) {
                        continue;
                    }
                    if ( $term->parent == 0 ) {

                        if ( ! in_array( $link['term_id'], $primary_ancestors ) && $primary_term_parent_term ) {
                            $links[$key] = array(
                                'url' => get_term_link( $primary_term_parent, 'product_cat'),
                                'text' => $primary_term_parent_term->name,
                                'term_id' => $primary_term_parent
                            );
                        }
                    } else {

                        $links[$key] = array(
                            'url' => get_term_link( $primary_term, 'product_cat'),
                            'text' => $primary_term->name,
                            'term_id' => $primary_cat
                        );
                        
                    }
                }
            }
        }
    }
    //error_log(print_r($links, true));
    return $links;
}
add_filter('wpseo_breadcrumb_links', 'eeco_child_breadcrumb_links', 10, 1);

