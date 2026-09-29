<?php

// Add modal for cart quote request
function eeco_child_cart_quote_modal () {
	?>
		<div class="modal fade" id="quoteCart" tabindex="-1" aria-labelledby="quoteCartLabel" aria-hidden="true">
  			<div class="modal-dialog">
    			<div class="modal-content">
      				<div class="modal-header">
        				<h6 class="modal-title" id="quoteCartLabel"><?php echo _x('Pyydä tarjous ostoskorin sisällöstä', 'Ostoskori', 'bgh-theme-child'); ?></h6>
        				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          					<span aria-hidden="true">&times;</span>
        				</button>
      				</div>
      				<div class="modal-body">
						<?php echo eeco_child_quote_cart_content(); ?>
						<?php echo do_shortcode('[gravityform id="3" title="false" description="false" ajax="true"]'); ?>
      				</div>
    			</div>
  			</div>
		</div>
	<?php
}
add_action('wp_footer', 'eeco_child_cart_quote_modal');

// Get cart content for quote
function eeco_child_quote_cart_content( ) {
	
	$html = ''; 

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item) {
		
		$product = $cart_item['data'];
		
		$formated_item_data = wc_get_formatted_cart_item_data($cart_item);
		$search = array('<p>', '</p>', '<dl', '</dl>');
		$replace= array('', '', '<div', '</div>');
		$formated_item_data = str_replace($search, $replace, $formated_item_data);
		

		$html .= 'Tuote: ' . $product->get_title() . '<br>';
		$html .=  $formated_item_data;
		$html .= 'Määrä: ' .  $cart_item['quantity'] . ' kpl<br>';
		$html .= 'Hinta: ' . number_format($cart_item['line_subtotal'], 2, ',', ' ') . ' €';
		$html .= '<br><br>';
	}

	return $html;

}

// Add cart content for quote for GF
function eeco_child_gf_cart_content( $value ) {
 	$value = eeco_child_quote_cart_content();
	return $value;
}
add_filter( 'gform_field_value_gf_cart_content', 'eeco_child_gf_cart_content', 10, 1 );

//Remove collon from shipping methods
function eeco_child_edit_shipping_label( $label, $method ) {
	
	$label = str_replace(':', '', $label);
	
	return $label;
}
add_filter('woocommerce_cart_shipping_method_full_label', 'eeco_child_edit_shipping_label', 15 , 2);