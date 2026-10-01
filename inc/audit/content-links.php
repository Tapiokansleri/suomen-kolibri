<?php
/**
 * Clean links in page content, without changing how the page looks.
 *
 * - Preview links: product descriptions contain links such as /tuote/name/?preview_id=1&preview_nonce=abc&preview=true
 *   (copied from the editor). The preview part is removed, so that the link goes to the product's real address.
 * - Removed products: a link to a product that no longer exists (or has only an old address) would lead to an error
 *   page. The link is removed and its text stays in place. A link to an old address of a product that still exists
 *   is kept, because WordPress redirects it to the product.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** True when the address belongs to this site (relative or with one of the site's host names). */
function suomen_kolibri_is_own_address( $host ) {
	if ( '' === (string) $host ) {
		return true;
	}
	$own = wp_parse_url( home_url(), PHP_URL_HOST );
	$host = preg_replace( '/^www\./i', '', strtolower( $host ) );
	return $host === preg_replace( '/^www\./i', '', strtolower( (string) $own ) );
}

/** True when a published product has this slug, or had it as an old slug (WordPress redirects those). */
function suomen_kolibri_product_slug_exists( $slug ) {
	static $seen = array();
	if ( isset( $seen[ $slug ] ) ) {
		return $seen[ $slug ];
	}
	$cached = wp_cache_get( $slug, 'suomen_kolibri_product_slug' );
	if ( false !== $cached ) {
		return $seen[ $slug ] = ( 'yes' === $cached );
	}
	global $wpdb;
	$found = (bool) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish' AND post_name = %s LIMIT 1",
			$slug
		)
	);
	if ( ! $found ) {
		$found = (bool) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT p.ID FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = '_wp_old_slug' AND m.meta_value = %s
				 WHERE p.post_type = 'product' AND p.post_status = 'publish' LIMIT 1",
				$slug
			)
		);
	}
	wp_cache_set( $slug, $found ? 'yes' : 'no', 'suomen_kolibri_product_slug', 300 );
	return $seen[ $slug ] = $found;
}

/** Filter for content with links. */
function suomen_kolibri_clean_content_links( $html ) {
	if ( ! is_string( $html ) || false === strpos( $html, '<a' ) || is_admin() ) {
		return $html;
	}
	return preg_replace_callback(
		'#<a\b([^>]*)>(.*?)</a>#is',
		static function ( $m ) {
			if ( ! preg_match( '/\bhref\s*=\s*(["\'])(.*?)\1/is', $m[1], $h ) ) {
				return $m[0];
			}
			$href  = html_entity_decode( $h[2], ENT_QUOTES, 'UTF-8' );
			$parts = wp_parse_url( $href );
			if ( ! is_array( $parts ) || ! suomen_kolibri_is_own_address( $parts['host'] ?? '' ) ) {
				return $m[0];
			}
			$path = $parts['path'] ?? '';

			// A link to a removed product: keep the text, drop the link.
			if ( preg_match( '#^/tuote/([^/]+)/#', trailingslashit( $path ), $p ) && ! suomen_kolibri_product_slug_exists( urldecode( $p[1] ) ) ) {
				return $m[2];
			}

			// Preview parameters out of the address.
			if ( isset( $parts['query'] ) && preg_match( '/(^|&)(preview|preview_id|preview_nonce)=/', $parts['query'] ) ) {
				parse_str( $parts['query'], $query );
				foreach ( array( 'preview', 'preview_id', 'preview_nonce', '_thumbnail_id' ) as $key ) {
					unset( $query[ $key ] );
				}
				$clean = ( isset( $parts['scheme'], $parts['host'] ) ? $parts['scheme'] . '://' . $parts['host'] . ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) : '' )
					. $path
					. ( $query ? '?' . http_build_query( $query ) : '' )
					. ( isset( $parts['fragment'] ) ? '#' . $parts['fragment'] : '' );
				$attrs = preg_replace( '/\bhref\s*=\s*(["\'])(.*?)\1/is', 'href="' . esc_attr( $clean ) . '"', $m[1], 1 );
				return '<a' . $attrs . '>' . $m[2] . '</a>';
			}
			return $m[0];
		},
		$html
	);
}

add_filter( 'the_content', 'suomen_kolibri_clean_content_links', 20 );
add_filter( 'acf_the_content', 'suomen_kolibri_clean_content_links', 20 );
add_filter( 'woocommerce_short_description', 'suomen_kolibri_clean_content_links', 20 );
add_filter( 'term_description', 'suomen_kolibri_clean_content_links', 20 );
