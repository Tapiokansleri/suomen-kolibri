<?php
/**
 * Structured data (schema.org) for Google: more complete product and company information.
 *
 * Nothing here changes how the site looks or works, only what Google reads from the page code.
 *
 * - Product: brand (from the product's category under "Brandit"), a clean description, full image addresses
 *   (the live site prints /wp-content/... without the host because WP_CONTENT_URL is relative), the product images
 *   and the condition of the goods.
 * - Company (Yoast Organization): address, phone, email, business ID. On the Yhteystiedot page the shop is added as
 *   a Store with opening hours.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Company facts, as published on the Yhteystiedot page and in the emails. */
function suomen_kolibri_company_facts() {
	return array(
		'street'   => 'Kalevantie 7',
		'postcode' => '33100',
		'city'     => 'Tampere',
		'country'  => 'FI',
		'phone'    => '+358 3 3454 5000',
		'email'    => 'kolibri@suomenkolibri.fi',
		'tax_id'   => '0833183-2',
		'opens'    => '08:00',
		'closes'   => '16:00',
	);
}

/** A site relative address (/wp-content/...) becomes a full address. Other values stay as they are. */
function suomen_kolibri_full_url( $value ) {
	if ( is_string( $value ) && '' !== $value && '/' === $value[0] && ( ! isset( $value[1] ) || '/' !== $value[1] ) ) {
		return home_url( $value );
	}
	return $value;
}

/** Makes the address fields of a schema piece full addresses, also in nested pieces. */
function suomen_kolibri_full_urls_in_piece( $piece ) {
	if ( ! is_array( $piece ) ) {
		return $piece;
	}
	foreach ( $piece as $key => $value ) {
		if ( is_array( $value ) ) {
			$piece[ $key ] = suomen_kolibri_full_urls_in_piece( $value );
		} elseif ( in_array( $key, array( 'url', 'contentUrl', 'image', 'thumbnailUrl', 'logo' ), true ) ) {
			$piece[ $key ] = suomen_kolibri_full_url( $value );
		}
	}
	return $piece;
}

/** Brand of a product: the product's category directly under the category "Brandit". */
function suomen_kolibri_product_brand( $product ) {
	static $brandit_id = null;
	if ( null === $brandit_id ) {
		$brandit    = get_term_by( 'slug', 'brandit', 'product_cat' );
		$brandit_id = $brandit ? (int) $brandit->term_id : 0;
	}
	if ( ! $brandit_id ) {
		return '';
	}
	$terms = get_the_terms( $product->get_parent_id() ? $product->get_parent_id() : $product->get_id(), 'product_cat' );
	if ( ! is_array( $terms ) ) {
		return '';
	}
	foreach ( $terms as $term ) {
		if ( (int) $term->parent === $brandit_id ) {
			return html_entity_decode( $term->name, ENT_QUOTES, 'UTF-8' );
		}
	}
	return '';
}

// Product (WooCommerce prints this data).
add_filter(
	'woocommerce_structured_data_product',
	static function ( $markup, $product ) {
		if ( ! is_array( $markup ) || ! $product instanceof WC_Product ) {
			return $markup;
		}

		// Description without line breaks and entity leftovers such as &amp;nbsp;.
		if ( ! empty( $markup['description'] ) && is_string( $markup['description'] ) ) {
			$text = html_entity_decode( html_entity_decode( $markup['description'], ENT_QUOTES, 'UTF-8' ), ENT_QUOTES, 'UTF-8' );
			$text = preg_replace( '/[\s\x{00A0}]+/u', ' ', $text );
			$markup['description'] = trim( (string) $text );
		}

		// Full image addresses: the main image first, then up to four gallery images.
		$images = array();
		if ( ! empty( $markup['image'] ) && is_string( $markup['image'] ) ) {
			$images[] = suomen_kolibri_full_url( $markup['image'] );
		}
		foreach ( array_slice( $product->get_gallery_image_ids(), 0, 4 ) as $image_id ) {
			$url = wp_get_attachment_url( $image_id );
			if ( $url ) {
				$images[] = suomen_kolibri_full_url( $url );
			}
		}
		$images = array_values( array_unique( array_filter( $images ) ) );
		if ( 1 === count( $images ) ) {
			$markup['image'] = $images[0];
		} elseif ( $images ) {
			$markup['image'] = $images;
		}

		$brand = suomen_kolibri_product_brand( $product );
		if ( '' !== $brand ) {
			$markup['brand'] = array(
				'@type' => 'Brand',
				'name'  => $brand,
			);
		}

		// The shop sells new goods.
		if ( ! empty( $markup['offers'] ) && is_array( $markup['offers'] ) ) {
			foreach ( $markup['offers'] as $i => $offer ) {
				if ( is_array( $offer ) && empty( $offer['itemCondition'] ) ) {
					$markup['offers'][ $i ]['itemCondition'] = 'https://schema.org/NewCondition';
				}
			}
		}

		return $markup;
	},
	20,
	2
);

// Company and shop (Yoast prints this data).
add_filter(
	'wpseo_schema_graph',
	static function ( $graph ) {
		if ( ! is_array( $graph ) ) {
			return $graph;
		}
		$facts   = suomen_kolibri_company_facts();
		$address = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $facts['street'],
			'postalCode'      => $facts['postcode'],
			'addressLocality' => $facts['city'],
			'addressCountry'  => $facts['country'],
		);
		$org_id  = home_url( '/#organization' );

		foreach ( $graph as $i => $piece ) {
			$graph[ $i ] = suomen_kolibri_full_urls_in_piece( $piece );
			if ( is_array( $piece ) && isset( $piece['@id'] ) && $org_id === $piece['@id'] ) {
				$graph[ $i ]['address']   = $address;
				$graph[ $i ]['telephone'] = $facts['phone'];
				$graph[ $i ]['email']     = $facts['email'];
				$graph[ $i ]['taxID']     = $facts['tax_id'];
			}
		}

		if ( is_page( 'yhteystiedot' ) ) {
			$graph[] = array(
				'@type'                     => 'Store',
				'@id'                       => home_url( '/#store' ),
				'name'                      => 'Suomen Kolibri Oy',
				'url'                       => home_url( '/yhteystiedot/' ),
				'address'                   => $address,
				'telephone'                 => $facts['phone'],
				'email'                     => $facts['email'],
				'openingHoursSpecification' => array(
					array(
						'@type'     => 'OpeningHoursSpecification',
						'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
						'opens'     => $facts['opens'],
						'closes'    => $facts['closes'],
					),
				),
				'parentOrganization'        => array( '@id' => $org_id ),
			);
		}
		return $graph;
	}
);
