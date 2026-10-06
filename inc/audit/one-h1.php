<?php
/**
 * Exactly one H1 on every page, without changing how the page looks.
 *
 * - No H1 (about 16 pages and the blog archives): the first H2 in the main content becomes the H1 and gets the class
 *   "h2", which gives it the H2 look.
 * - Several H1 (product description texts, articles, the front page): the first one stays, the others become H2 with
 *   the class "h1", which keeps the H1 look.
 *
 * The change is made in the finished HTML, so it also covers headings that editors have written in page builder
 * fields and article texts. The front page slider headings are handled in template-parts/sections/slider.php.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Adds a class to the attribute string of a tag. */
function suomen_kolibri_add_class( $attributes, $class ) {
	if ( preg_match( '/\bclass\s*=\s*(["\'])(.*?)\1/is', $attributes, $m ) ) {
		$classes = preg_split( '/\s+/', trim( $m[2] ) );
		if ( ! in_array( $class, $classes, true ) ) {
			$classes[] = $class;
		}
		return preg_replace( '/\bclass\s*=\s*(["\']).*?\1/is', 'class="' . implode( ' ', array_filter( $classes ) ) . '"', $attributes, 1 );
	}
	return rtrim( $attributes ) . ' class="' . $class . '"';
}

/** Renames a heading (opening tag at $open_pos, closing tag after it) and adds a class. Returns the new HTML. */
function suomen_kolibri_rename_heading( $html, $open_pos, $from, $to, $class ) {
	if ( ! preg_match( '/<' . $from . '\b([^>]*)>/i', $html, $m, PREG_OFFSET_CAPTURE, $open_pos ) || $m[0][1] !== $open_pos ) {
		return $html;
	}
	$close = stripos( $html, '</' . $from . '>', $open_pos );
	if ( false === $close ) {
		return $html;
	}
	$open_tag = '<' . $to . suomen_kolibri_add_class( $m[1][0], $class ) . '>';
	$html     = substr_replace( $html, '</' . $to . '>', $close, strlen( $from ) + 3 );
	return substr_replace( $html, $open_tag, $open_pos, strlen( $m[0][0] ) );
}

/** Output buffer callback: one H1 per page. */
function suomen_kolibri_single_h1( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, '<body' ) || strlen( $html ) > 5000000 ) {
		return $html;
	}

	// Hide comments and script, style, template and similar blocks so that text inside them is never changed.
	$masks  = array();
	$masked = preg_replace_callback(
		'#<!--.*?-->|<(script|style|template|noscript|textarea)\b[^>]*>.*?</\1>#is',
		static function ( $m ) use ( &$masks ) {
			$key           = '<!--sk-mask-' . count( $masks ) . '-->';
			$masks[ $key ] = $m[0];
			return $key;
		},
		$html
	);
	if ( null === $masked ) {
		return $html;
	}

	preg_match_all( '#<h1\b[^>]*>#i', $masked, $h1, PREG_OFFSET_CAPTURE );
	$count = count( $h1[0] );
	if ( 1 === $count ) {
		return $html;
	}

	if ( 0 === $count ) {
		$start = 0;
		$end   = strlen( $masked );
		if ( preg_match( '#<main\b#i', $masked, $main, PREG_OFFSET_CAPTURE ) ) {
			$start = $main[0][1];
		} elseif ( preg_match( '#<body\b#i', $masked, $body, PREG_OFFSET_CAPTURE ) ) {
			$start = $body[0][1];
		}
		if ( preg_match( '#<h2\b[^>]*>#i', $masked, $h2, PREG_OFFSET_CAPTURE, $start ) && $h2[0][1] < $end ) {
			$masked = suomen_kolibri_rename_heading( $masked, $h2[0][1], 'h2', 'h1', 'h2' );
		}
	} else {
		// Keep the first H1, turn the others (last first, so the positions stay valid) into H2 that look like H1.
		for ( $i = $count - 1; $i >= 1; $i-- ) {
			$masked = suomen_kolibri_rename_heading( $masked, $h1[0][ $i ][1], 'h1', 'h2', 'h1' );
		}
	}

	return strtr( $masked, $masks );
}

add_action(
	'template_redirect',
	static function () {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || is_feed() || is_robots() || is_embed() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}
		// The shop's own pages (cart, checkout, account, WooCommerce AJAX) are left exactly as they are: they are not in
		// Google, and nothing on them should depend on this filter.
		if ( isset( $_GET['wc-ajax'] ) || ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		ob_start( 'suomen_kolibri_single_h1' );
	},
	0
);
