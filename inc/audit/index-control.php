<?php
/**
 * Which tag and attribute pages Google may index.
 *
 * Product tags, product attributes (size, flavour, model, colour) and blog tags are almost empty pages: 10 436 of
 * the 12 907 sitemap addresses. They stay on the site and work for visitors. Google is told not to index them
 * (noindex, follow) and they leave the XML sitemap, except the pages that brought at least 10 clicks in Search Console
 * between 27.5.2025 and 26.9.2026 and have products (the list below).
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Terms that stay in Google, by taxonomy (term slugs). */
function suomen_kolibri_indexed_terms() {
	return array(
		'pa_malli'    => array( 'suomi-israel', 'suomi-nato' ),
		'product_tag' => array(
			'alvar-aalto-tuikku', 'asentajan-hattu', 'hankkija-lippis', 'heijastinreppu', 'hissijojo', 'hotellitossut', 'juustoveitsi',
			'kangasmerkki-omalla-logolla', 'keltainen-sateenvarjo', 'kinkkusetti', 'kultaharkko', 'laadukas-sateenvarjo', 'lahjaboksi',
			'lakki-omalla-painatuksella', 'lippalakki-omalla-logolla', 'mielyttava-lahja-tyontekijalle', 'mini-petanque', 'muovinen-juomapullo',
			'painatus', 'paperiranneke', 'passin-kannet', 'passinkannet', 'pehmoporo', 'poikakalenteri', 'printer-red', 'pyoralaukku',
			'selviytymispakkaus', 'suikka-omalla-logolla', 'suomi-finland-100', 'taulukassi', 'tehokas-kasipuhallin', 'teraspullo',
			'tolkinjaahdytin-omalla-logolla', 'tyokassi', 'tyttokalenteri', 'uusi-vauva-tulossa', 'vaatteet', 'vuolukivi',
		),
	);
}

/** Taxonomies handled by this file. */
function suomen_kolibri_limited_taxonomies() {
	return array( 'product_tag', 'pa_koko', 'pa_maku', 'pa_malli', 'pa_vari', 'post_tag' );
}

/** Whether a term page may be indexed. */
function suomen_kolibri_term_is_indexable( $term ) {
	if ( ! $term instanceof WP_Term || ! in_array( $term->taxonomy, suomen_kolibri_limited_taxonomies(), true ) ) {
		return true;
	}
	$keep = suomen_kolibri_indexed_terms();
	return isset( $keep[ $term->taxonomy ] ) && in_array( urldecode( $term->slug ), $keep[ $term->taxonomy ], true );
}

// 1. Meta robots on the page: noindex, follow (Yoast writes the tag, this changes its first value).
add_filter(
	'wpseo_robots',
	static function ( $robots ) {
		if ( ! is_string( $robots ) || ! ( is_tax() || is_tag() ) ) {
			return $robots;
		}
		$term = get_queried_object();
		if ( $term instanceof WP_Term && ! suomen_kolibri_term_is_indexable( $term ) && 0 !== strpos( $robots, 'noindex' ) ) {
			return preg_replace( '/^index\b/', 'noindex', $robots );
		}
		return $robots;
	}
);

// 2. XML sitemap: taxonomies without any kept page disappear completely.
add_filter(
	'wpseo_sitemap_exclude_taxonomy',
	static function ( $exclude, $taxonomy ) {
		$keep = suomen_kolibri_indexed_terms();
		return ( in_array( $taxonomy, suomen_kolibri_limited_taxonomies(), true ) && empty( $keep[ $taxonomy ] ) ) ? true : $exclude;
	},
	10,
	2
);

// 3. XML sitemap: product tags and the model attribute list only the kept terms, in the index and in the sitemap pages.
add_filter(
	'get_terms_args',
	static function ( $args, $taxonomies ) {
		if ( ! function_exists( 'get_query_var' ) || ! get_query_var( 'sitemap' ) ) {
			return $args;
		}
		$taxonomies = (array) $taxonomies;
		$keep       = suomen_kolibri_indexed_terms();
		if ( 1 !== count( $taxonomies ) || ! isset( $keep[ $taxonomies[0] ] ) ) {
			return $args;
		}
		global $wpdb;
		$slugs = array();
		foreach ( $keep[ $taxonomies[0] ] as $slug ) {
			$slugs[] = $slug;
			$slugs[] = strtolower( rawurlencode( $slug ) ); // non-ASCII slugs are stored percent-encoded
		}
		$slugs = array_values( array_unique( $slugs ) );
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- placeholders are generated below.
		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT t.term_id FROM {$wpdb->terms} t INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id WHERE tt.taxonomy = %s AND t.slug IN (" . implode( ',', array_fill( 0, count( $slugs ), '%s' ) ) . ')', // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				array_merge( array( $taxonomies[0] ), $slugs )
			)
		);
		$args['include'] = $ids ? array_map( 'intval', $ids ) : array( 0 );
		return $args;
	},
	10,
	2
);
