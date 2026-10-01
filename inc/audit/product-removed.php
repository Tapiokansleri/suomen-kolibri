<?php
/**
 * A removed product shows "Tuote poistunut valikoimasta" instead of "Sivua ei löytynyt".
 *
 * When a product is moved to the trash (or deleted), its address no longer finds a product. The visitor sees the
 * theme's normal 404 page with a clear message about the product, a link to the product's category when it is
 * known (trashed products), and the same product suggestions as on the 404 page. Google gets the status
 * "410 Gone" (the page was removed on purpose) and noindex. A redirect set in the Redirection plugin for the
 * address still wins, because it runs first.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** True when the request is for a product address that does not find a published product. */
function suomen_kolibri_is_removed_product_request() {
	return is_404() && '' !== (string) get_query_var( 'product' );
}

/** The category of a trashed product: the first category that is not a brand category. Null when unknown. */
function suomen_kolibri_removed_product_category() {
	$slug = (string) get_query_var( 'product' );
	if ( '' === $slug ) {
		return null;
	}
	$ids = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'trash',
			'name'           => $slug . '__trashed',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);
	if ( ! $ids ) {
		return null;
	}
	$terms = get_the_terms( $ids[0], 'product_cat' );
	if ( ! is_array( $terms ) || ! $terms ) {
		return null;
	}
	$brandit = get_term_by( 'slug', 'brandit', 'product_cat' );
	foreach ( $terms as $term ) {
		if ( ! $brandit || (int) $term->parent !== (int) $brandit->term_id ) {
			return $term;
		}
	}
	return $terms[0];
}

// Status 410 instead of 404 (WordPress has already set 404 when the template starts).
add_action(
	'template_redirect',
	static function () {
		if ( suomen_kolibri_is_removed_product_request() ) {
			status_header( 410 );
			nocache_headers();
		}
	},
	1
);

// The text of the 404 page (the theme prints this option on its 404 page).
add_filter(
	'pre_option_bgh_not_found_page_content',
	static function ( $pre ) {
		if ( ! suomen_kolibri_is_removed_product_request() ) {
			return $pre;
		}
		$term = suomen_kolibri_removed_product_category();
		if ( $term && ! is_wp_error( $term ) && ! is_wp_error( get_term_link( $term ) ) ) {
			$link  = get_term_link( $term );
			$label = sprintf( 'Katso tuoteryhmää %s', html_entity_decode( $term->name, ENT_QUOTES, 'UTF-8' ) );
		} else {
			$link  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
			$label = 'Katso kaikkia tuotteita';
		}
		return '<h1 class="entry-title">Tuote poistunut valikoimasta</h1>'
			. '<p>Etsimäsi tuote ei ole enää valikoimassamme. Tutustu samankaltaisiin tuotteisiin alla tai ota yhteyttä, niin etsimme sinulle sopivan vaihtoehdon.</p>'
			. '<p class="mt-4"><a class="button primary-cta" href="' . esc_url( $link ) . '">' . esc_html( $label ) . '</a> '
			. '<a class="button secondary-cta" href="' . esc_url( home_url( '/yhteystiedot/' ) ) . '">Ota yhteyttä</a></p>';
	}
);

// Page title in the browser tab and in Google.
$suomen_kolibri_removed_title = static function ( $title ) {
	return suomen_kolibri_is_removed_product_request() ? 'Tuote poistunut valikoimasta | Suomen Kolibri' : $title;
};
add_filter( 'pre_get_document_title', $suomen_kolibri_removed_title, 20 );
add_filter( 'wpseo_title', $suomen_kolibri_removed_title, 20 );
unset( $suomen_kolibri_removed_title );
