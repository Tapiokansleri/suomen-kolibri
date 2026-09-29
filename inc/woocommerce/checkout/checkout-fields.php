<?php

// CUSTOMIZE CHECKOUT FILEDS
function eeco_child_edit_checkout_fields( $fields) {
	
	unset($fields['billing']['checkbox_company']);

	return $fields;
}
add_filter('woocommerce_checkout_fields', 'eeco_child_edit_checkout_fields', 9999, 1);