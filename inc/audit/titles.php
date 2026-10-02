<?php
/**
 * Search result titles of products: "Brand Product name | Omalla logolla | Suomen Kolibri".
 *
 * The Yoast title template of products is `%%sk_product%% %%sep%% %%sk_reason%% %%sep%% %%sitename%%`. The two
 * variables are made here, so every product gets a title in the same form without anyone writing 1 800 titles:
 *
 * - %%sk_product%%: the product name, with the product's brand in front when the name does not already start with it.
 *   The brand is the product's category directly under "Brandit" (for example Iittala or South West).
 * - %%sk_reason%%: the reason to click, "Omalla logolla" (the shop prints the customer's logo on its products). When
 *   the name is long, the shorter "Oma logo" is used when the title would be over 68 characters.
 *
 * A title written by hand in the product's Yoast field still wins over the template.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wpseo_register_extra_replacements',
	static function () {
		if ( function_exists( 'wpseo_register_var_replacement' ) ) {
			wpseo_register_var_replacement( '%%sk_product%%', 'suomen_kolibri_title_product', 'advanced', 'Tuotteen nimi ja merkki (Suomen Kolibri)' );
			wpseo_register_var_replacement( '%%sk_reason%%', 'suomen_kolibri_title_reason', 'advanced', 'Syy klikata: Omalla logolla (Suomen Kolibri)' );
		}
	}
);

/** The post a Yoast variable is made for. */
function suomen_kolibri_title_post_id( $args ) {
	$post_id = ( is_object( $args ) && ! empty( $args->ID ) ) ? (int) $args->ID : (int) get_queried_object_id();
	return ( $post_id && 'product' === get_post_type( $post_id ) ) ? $post_id : 0;
}

/**
 * The word that shows a brand in a product name, and how the brand is written when it is added in front. Brands that
 * are known by another name than the category name are listed here; for the rest the longest word of the name is used.
 */
function suomen_kolibri_brand_words() {
	static $brands = null;
	if ( null !== $brands ) {
		return $brands;
	}
	$brands = array();
	$parent = get_term_by( 'slug', 'brandit', 'product_cat' );
	if ( ! $parent ) {
		return $brands;
	}
	$special = array(
		'ID Identity'       => array( 'ID', 'ID' ),
		'J.Harvest & Frost' => array( 'Harvest', 'James Harvest' ),
	);
	foreach ( get_terms( array( 'taxonomy' => 'product_cat', 'parent' => (int) $parent->term_id, 'hide_empty' => false ) ) as $term ) {
		$name = html_entity_decode( $term->name, ENT_QUOTES, 'UTF-8' );
		if ( isset( $special[ $name ] ) ) {
			$brands[ $name ] = array( 'word' => $special[ $name ][0], 'display' => $special[ $name ][1] );
			continue;
		}
		$word  = '';
		$parts = preg_split( '/[^\p{L}\p{N}]+/u', $name, -1, PREG_SPLIT_NO_EMPTY );
		foreach ( $parts as $part ) {
			if ( mb_strlen( $part ) > mb_strlen( $word ) ) {
				$word = $part;
			}
		}
		if ( '' !== $word ) {
			// "Label-Free" is also written "LabelFree" in product names, so the joined form counts too.
			$brands[ $name ] = array(
				'word'   => $word,
				'joined' => implode( '', $parts ),
				'display' => $name,
			);
		}
	}
	return $brands;
}

/** True when a word of the product name is (nearly) the brand word of any brand: the name already tells the brand. */
function suomen_kolibri_name_has_brand( $name ) {
	$words = preg_split( '/[^\p{L}\p{N}]+/u', mb_strtolower( $name ), -1, PREG_SPLIT_NO_EMPTY );
	foreach ( suomen_kolibri_brand_words() as $brand ) {
		foreach ( array_filter( array( $brand['word'], $brand['joined'] ?? '' ) ) as $candidate ) {
			$word = mb_strtolower( $candidate );
			foreach ( $words as $w ) {
				// One typo is allowed in longer words ("Iiittala", "Mattehorn").
				if ( $w === $word || ( mb_strlen( $word ) >= 6 && abs( strlen( $w ) - strlen( $word ) ) <= 1 && levenshtein( $w, $word ) <= 1 ) ) {
					return true;
				}
			}
		}
	}
	return false;
}

/** Product name with the product's brand in front when the name does not tell the brand. */
function suomen_kolibri_product_title_name( $post_id ) {
	$name = trim( html_entity_decode( wp_strip_all_tags( get_the_title( $post_id ) ), ENT_QUOTES, 'UTF-8' ) );
	if ( ! function_exists( 'suomen_kolibri_product_brand' ) || ! function_exists( 'wc_get_product' ) ) {
		return $name;
	}
	$product = wc_get_product( $post_id );
	$brand   = $product ? trim( suomen_kolibri_product_brand( $product ) ) : '';
	if ( '' === $brand || suomen_kolibri_name_has_brand( $name ) ) {
		return $name;
	}
	$brands = suomen_kolibri_brand_words();
	return ( isset( $brands[ $brand ] ) ? $brands[ $brand ]['display'] : $brand ) . ' ' . $name;
}

/** Yoast variable %%sk_product%%. */
function suomen_kolibri_title_product( $var = '', $args = null ) {
	$post_id = suomen_kolibri_title_post_id( $args );
	return $post_id ? suomen_kolibri_product_title_name( $post_id ) : '';
}

/** Yoast variable %%sk_reason%%. */
function suomen_kolibri_title_reason( $var = '', $args = null ) {
	$post_id = suomen_kolibri_title_post_id( $args );
	if ( ! $post_id ) {
		return '';
	}
	$brand = html_entity_decode( get_bloginfo( 'name' ), ENT_QUOTES, 'UTF-8' );
	$name  = suomen_kolibri_product_title_name( $post_id );
	// name + " | " + reason + " | " + site name
	return ( mb_strlen( $name ) + mb_strlen( 'Omalla logolla' ) + mb_strlen( $brand ) + 6 <= 68 ) ? 'Omalla logolla' : 'Oma logo';
}
