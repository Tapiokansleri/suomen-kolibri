<?php
/**
 * Images: alt texts and lazy loading.
 *
 * Done in the finished HTML, so it covers images from page builder fields, widgets, product cards and article texts:
 *
 * - An image without alt text gets one. Order: the alt text saved in the media library, the product name for product
 *   images, a fixed name for the logo and the certificate badges, then a readable name made from the media library
 *   title or the file name. Images marked decorative (aria-hidden, role="presentation") and 1 pixel images stay as they are.
 * - Images after the first six in the page get loading="lazy" and decoding="async", so the browser loads them when
 *   the visitor scrolls near them. Carousel images are left alone, so a slide never appears empty when it turns.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Fixed alt texts by file name (lower case, part of the name). */
function suomen_kolibri_fixed_alts() {
	return array(
		'suomen-kolibri-logo'  => 'Suomen Kolibri',
		'suomen-kolibri_logo'  => 'Suomen Kolibri',
		'avainlippu'           => 'Avainlippu',
		'lk_valkoinen'         => 'Luotettava Kumppani',
		'bmc_iso-14001'        => 'ISO 14001',
		'bmc_iso-9001'         => 'ISO 9001',
		'rinki_merkki'         => 'Rinki',
	);
}

/** A readable alt text from a title or a file name: "arabia_logo-300x200.png" becomes "Arabia logo". */
function suomen_kolibri_alt_from_name( $name ) {
	$name = preg_replace( '/\.[a-z0-9]{3,4}$/i', '', (string) $name );
	$name = preg_replace( '/-e\d{9,}$/', '', $name );
	$name = preg_replace( '/-\d+x\d+$/', '', $name );
	$name = preg_replace( '/[-_]+/', ' ', $name );
	$name = preg_replace( '/\b(png|jpg|jpeg|webp|gif|scaled|\d+px)\b/i', '', $name );
	$name = trim( preg_replace( '/\s+/', ' ', $name ) );
	return '' === $name ? '' : mb_strtoupper( mb_substr( $name, 0, 1 ) ) . mb_substr( $name, 1 );
}

/** The file path inside the uploads folder for an image address, without the size suffix. Empty for other images. */
function suomen_kolibri_upload_file( $src ) {
	if ( ! preg_match( '#/wp-content/uploads/([^?\#]+)#', $src, $m ) ) {
		return '';
	}
	$file = rawurldecode( $m[1] );
	$file = preg_replace( '/-scaled(?=\.[a-z0-9]+$)/i', '', $file );
	return preg_replace( '/-\d+x\d+(?=\.[a-z0-9]+$)/i', '', $file );
}

/** Looks up media library data for many files with a few queries. Returns file => array( title, alt, parent_title ). */
function suomen_kolibri_media_info( array $files ) {
	global $wpdb;
	$files = array_values( array_unique( array_filter( $files ) ) );
	if ( ! $files ) {
		return array();
	}
	$marks = implode( ',', array_fill( 0, count( $files ), '%s' ) );
	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT pm.meta_value AS file, p.ID AS id, p.post_title AS title, p.post_parent AS parent FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE pm.meta_key = '_wp_attached_file' AND pm.meta_value IN ($marks)", $files ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	if ( ! $rows ) {
		return array();
	}
	$ids     = array_map( 'intval', wp_list_pluck( $rows, 'id' ) );
	$parents = array_values( array_unique( array_filter( array_map( 'intval', wp_list_pluck( $rows, 'parent' ) ) ) ) );
	$alts    = array();
	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	foreach ( (array) $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attachment_image_alt' AND post_id IN (" . implode( ',', $ids ) . ')', ARRAY_A ) as $r ) {
		$alts[ (int) $r['post_id'] ] = trim( (string) $r['meta_value'] );
	}
	$parent_titles = array();
	if ( $parents ) {
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		foreach ( (array) $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'product' AND ID IN (" . implode( ',', $parents ) . ')', ARRAY_A ) as $r ) {
			$parent_titles[ (int) $r['ID'] ] = html_entity_decode( (string) $r['post_title'], ENT_QUOTES, 'UTF-8' );
		}
	}
	$info = array();
	foreach ( $rows as $r ) {
		$info[ $r['file'] ] = array(
			'title'        => html_entity_decode( (string) $r['title'], ENT_QUOTES, 'UTF-8' ),
			'alt'          => $alts[ (int) $r['id'] ] ?? '',
			'parent_title' => $parent_titles[ (int) $r['parent'] ] ?? '',
		);
	}
	return $info;
}

/** Output buffer callback. */
function suomen_kolibri_images( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, '<body' ) || strlen( $html ) > 5000000 ) {
		return $html;
	}

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
	if ( null === $masked || ! preg_match_all( '#<img\b[^>]*>#i', $masked, $found, PREG_OFFSET_CAPTURE ) ) {
		return $html;
	}

	// Which images need an alt text, and which media library files to look up.
	$attr = static function ( $tag, $name ) {
		return preg_match( '/\s' . $name . '\s*=\s*(["\'])(.*?)\1/is', $tag, $m ) ? $m[2] : null;
	};
	$files = array();
	foreach ( $found[0] as $hit ) {
		$alt = $attr( $hit[0], 'alt' );
		if ( null === $alt || '' === trim( $alt ) ) {
			$files[] = suomen_kolibri_upload_file( (string) $attr( $hit[0], 'src' ) );
		}
	}
	$info    = suomen_kolibri_media_info( $files );
	$fixed   = suomen_kolibri_fixed_alts();
	$changed = false;
	$eager   = 6;

	// Last image first, so that the positions of the earlier ones stay valid.
	for ( $i = count( $found[0] ) - 1; $i >= 0; $i-- ) {
		$tag = $found[0][ $i ][0];
		$pos = $found[0][ $i ][1];
		$new = $tag;

		$alt        = $attr( $tag, 'alt' );
		$decorative = ( 'true' === $attr( $tag, 'aria-hidden' ) ) || in_array( $attr( $tag, 'role' ), array( 'presentation', 'none' ), true )
			|| ( '1' === $attr( $tag, 'width' ) && '1' === $attr( $tag, 'height' ) );
		if ( ( null === $alt || '' === trim( $alt ) ) && ! $decorative ) {
			$src  = (string) $attr( $tag, 'src' );
			$file = suomen_kolibri_upload_file( $src );
			$base = strtolower( basename( $file ? $file : wp_parse_url( $src, PHP_URL_PATH ) ) );
			$text = '';
			foreach ( $fixed as $needle => $label ) {
				if ( false !== strpos( $base, $needle ) ) {
					$text = $label;
					break;
				}
			}
			// Article cards: the picture is described by the title of the article next to it.
			if ( '' === $text && false !== strpos( substr( $masked, max( 0, $pos - 400 ), min( 400, $pos ) ), 'post-holder' )
				&& preg_match( '#<h[2-4][^>]*>\s*(?:<a\b[^>]*>)?\s*([^<]{3,140})#u', substr( $masked, $pos + strlen( $tag ), 900 ), $heading ) ) {
				$text = trim( html_entity_decode( $heading[1], ENT_QUOTES, 'UTF-8' ) );
			}
			if ( '' === $text && isset( $info[ $file ] ) ) {
				$text = $info[ $file ]['alt'] ? $info[ $file ]['alt'] : ( $info[ $file ]['parent_title'] ? $info[ $file ]['parent_title'] : suomen_kolibri_alt_from_name( $info[ $file ]['title'] ) );
			}
			if ( '' === $text ) {
				$text = suomen_kolibri_alt_from_name( basename( (string) wp_parse_url( $src, PHP_URL_PATH ) ) );
			}
			if ( '' !== $text ) {
				$esc = esc_attr( $text );
				if ( null === $alt ) {
					$new = preg_replace( '/<img\b/i', '<img alt="' . $esc . '"', $new, 1 );
				} else {
					$new = preg_replace( '/\salt\s*=\s*(["\']).*?\1/is', ' alt="' . $esc . '"', $new, 1 );
				}
			}
		}

		// Lazy loading after the first images, not in carousels.
		if ( $i >= $eager && null === $attr( $tag, 'loading' ) && null === $attr( $tag, 'fetchpriority' ) ) {
			$before = substr( $masked, max( 0, $pos - 700 ), min( 700, $pos ) );
			if ( false === strpos( $before, 'carousel-item' ) ) {
				$new = preg_replace( '/<img\b/i', '<img loading="lazy"' . ( null === $attr( $tag, 'decoding' ) ? ' decoding="async"' : '' ), $new, 1 );
			}
		}

		if ( $new !== $tag ) {
			$masked  = substr_replace( $masked, $new, $pos, strlen( $tag ) );
			$changed = true;
		}
	}

	return $changed ? strtr( $masked, $masks ) : $html;
}

add_action(
	'template_redirect',
	static function () {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || is_feed() || is_robots() || is_embed() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}
		ob_start( 'suomen_kolibri_images' );
	},
	0
);
