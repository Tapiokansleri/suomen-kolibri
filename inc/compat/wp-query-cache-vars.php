<?php
/**
 * Default WP_Query cache flags when queries are built via parse_query() only.
 *
 * @package Eeco_Theme_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'parse_query',
	static function ( $query ) {
		foreach ( array( 'update_post_term_cache', 'update_post_meta_cache', 'lazy_load_term_meta' ) as $var ) {
			if ( ! isset( $query->query_vars[ $var ] ) ) {
				$query->query_vars[ $var ] = true;
			}
		}
	}
);
