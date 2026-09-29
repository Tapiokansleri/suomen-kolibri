<?php
/**
 * Theme settings kept out of the (public) repository.
 *
 * Settings → General → "Google Maps API key" is used for the pick-up point map
 * in woocommerce/order/order-details.php. The map is hidden while it is empty.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function suomen_kolibri_register_settings() {
	register_setting(
		'general',
		'suomen_kolibri_google_maps_key',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		)
	);

	add_settings_field(
		'suomen_kolibri_google_maps_key',
		__( 'Google Maps API key', 'bgh-theme-child' ),
		'suomen_kolibri_google_maps_key_field',
		'general',
		'default',
		array( 'label_for' => 'suomen_kolibri_google_maps_key' )
	);
}
add_action( 'admin_init', 'suomen_kolibri_register_settings' );

function suomen_kolibri_google_maps_key_field() {
	printf(
		'<input type="text" id="suomen_kolibri_google_maps_key" name="suomen_kolibri_google_maps_key" value="%s" class="regular-text code" autocomplete="off" /><p class="description">%s</p>',
		esc_attr( get_option( 'suomen_kolibri_google_maps_key', '' ) ),
		esc_html__( 'Used for the pick-up point map on the order page (Suomen Kolibri theme).', 'bgh-theme-child' )
	);
}
