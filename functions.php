<?php
// Remove stray whitespace before <!doctype.
ob_start( function ( $output ) {
	return ltrim( $output );
} );

// THEME UPDATES FROM GITHUB
include_once get_stylesheet_directory() . '/inc/theme-updater.php';

// COPY SETTINGS FROM EECO THEME CHILD ON FIRST ACTIVATION
include_once get_stylesheet_directory() . '/inc/migrate-theme-mods.php';

// SETTINGS KEPT OUT OF THE REPOSITORY (Settings → General)
include_once get_stylesheet_directory() . '/inc/theme-settings.php';

// LOAD PARENT THEME STYLES
add_action( 'wp_enqueue_scripts', 'parent_theme_styles' );
function parent_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

// SETUP TRANSLATIONS
function bgh_theme_child_setup() {
    $path = get_stylesheet_directory().'/languages';
    load_child_theme_textdomain( 'bgh-theme-child', $path );
}
add_action( 'after_setup_theme', 'bgh_theme_child_setup' );

function eeco_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'eeco_mime_types');

// STYLES
include get_stylesheet_directory() . '/inc/enqueue-styles.php';

// SINGLE PRODUCT CUSTOMIZATIONS
include_once get_stylesheet_directory() . '/inc/woocommerce/single-product.php';

// Avoid undefined $timestamp_created in the parent's "new" badge for products without a created date.
include_once get_stylesheet_directory() . '/inc/woocommerce/badges-date-fallback.php';

// Prevent parent datalayer issues when product shortcodes run outside storefront requests.
include_once get_stylesheet_directory() . '/inc/marketing/datalayer-fix.php';

// Default WP_Query cache vars for Relevanssi / parse_query-only loops.
include_once get_stylesheet_directory() . '/inc/compat/wp-query-cache-vars.php';

// PRODUCT ARCHIVE CUSTOMIZATIONS
include_once get_stylesheet_directory() . '/inc/woocommerce/archive-product.php';

// CART CUSTOMIZATIONS
include_once get_stylesheet_directory() . '/inc/woocommerce/cart/cart.php';

// CUSTOMIZE CHECKOUT FILEDS
include_once get_stylesheet_directory() . '/inc/woocommerce/checkout/checkout-fields.php';

// ACF Fields
include get_stylesheet_directory() . '/inc/acf.php';

// ACF Fields for gift redeem (only when ACF is loaded).
add_action(
	'acf/init',
	static function () {
		include get_stylesheet_directory() . '/inc/acf/gift-redeem-fields.php';
	}
);

// GIFT REDEEM FEAUTURES
include get_stylesheet_directory() . '/inc/gift-redeem.php';

//Show default category description
function eeco_child_display_cat_dec(){
	?>
    <style type="text/css">
       .term-thumbnail-wrap, .term-description-wrap {
           display: table-row !important;
       }
    </style>
<?php } 
add_action( 'admin_head-term.php', 'eeco_child_display_cat_dec');

// Allowed tags on GF
function eeco_child_allowed_gf_tags ( $allowable_tags ) {
	return '<br>';
}
add_filter( 'gform_allowable_tags', 'eeco_child_allowed_gf_tags', 10, 3 );

// Remove the original bank details

function prefix_remove_bank_details() {
	
	// Do nothing, if WC_Payment_Gateways does not exist
	if ( ! class_exists( 'WC_Payment_Gateways' ) ) {
		return;
	}

	// Get the gateways instance
	$gateways           = WC_Payment_Gateways::instance();
	
	// Get all available gateways, [id] => Object
	$available_gateways = $gateways->get_available_payment_gateways();

	if ( isset( $available_gateways['bacs'] ) ) {
		// If the gateway is available, remove the action hook
		remove_action( 'woocommerce_email_before_order_table', array( $available_gateways['bacs'], 'email_instructions' ), 10, 3 );
	}
}
add_action( 'init', 'prefix_remove_bank_details', 100 );


// RELEVANSSI LIVE SEARCH - PREVENT POST DRAFTS SHOWING ON LIVE SEACH RESULTS
add_filter( 'relevanssi_live_search_query_args', function ( $args ) {
    $args['post_status'] = 'publish,inherit'; // or just 'publish', if you don't need attachments in the results
    return $args;
  } );


//Add GELO scripts
function gelo_enqueue_script() {
    if (is_product()) {
        wp_enqueue_script(
            'gelo-scripts',
            get_stylesheet_directory_uri() . '/assets/js/gelo-scripts.js',
            array('jquery'),
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'gelo_enqueue_script');

/**
 * Fix minicart product names to include variation attributes
 * This ensures that when products are added to cart via AJAX, the names are properly displayed
 */
function eeco_fix_minicart_product_names( $product_name, $cart_item, $cart_item_key ) {
	$product = isset( $cart_item['data'] ) ? $cart_item['data'] : null;

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return $product_name;
	}

	// Only for variations: build name from parent + formatted variation values
	if ( $product->is_type( 'variation' ) ) {
		$parent_product = wc_get_product( $product->get_parent_id() );
		$base_name = $parent_product ? $parent_product->get_name() : $product->get_name();

		$readable_values = array();

		if ( ! empty( $cart_item['variation'] ) && is_array( $cart_item['variation'] ) ) {
			foreach ( $cart_item['variation'] as $attr_key => $attr_value ) {
				if ( ! $attr_value ) {
					continue;
				}

				// Normalize taxonomy key, e.g. attribute_pa_color -> pa_color
				$taxonomy = str_replace( 'attribute_', '', $attr_key );
				$value_label = $attr_value;

				// If this is a taxonomy attribute, resolve term name from slug
				if ( 0 === strpos( $taxonomy, 'pa_' ) ) {
					$taxonomy_name = wc_attribute_taxonomy_name( $taxonomy );
					$term = get_term_by( 'slug', $attr_value, $taxonomy_name );
					if ( $term && ! is_wp_error( $term ) ) {
						$value_label = $term->name;
					}
				} else {
					// Custom attribute: convert slug-like to words
					$value_label = str_replace( array( '-', '_' ), ' ', $value_label );
				}

				// Capitalization rules:
				// - Sizes like s,m,l,xl,xxl,3xl -> uppercase
				$size_map_pattern = '/^(xxs|xs|s|m|l|xl|xxl|xxxl|3xl|4xl|5xl)$/i';
				if ( preg_match( $size_map_pattern, trim( $value_label ) ) ) {
					$value_label = strtoupper( $value_label );
				} else {
					// - Otherwise capitalize first letter of each word (unicode-safe where possible)
					if ( function_exists( 'mb_convert_case' ) ) {
						$value_label = mb_convert_case( $value_label, MB_CASE_TITLE, 'UTF-8' );
					} else {
						$value_label = ucwords( $value_label );
					}
				}

				$readable_values[] = $value_label;
			}
		}

		if ( ! empty( $readable_values ) ) {
			$product_name = $base_name . ' - ' . implode( ', ', $readable_values );
		} else {
			$product_name = $base_name;
		}
	}

	return $product_name;
}
add_filter( 'woocommerce_cart_item_name', 'eeco_fix_minicart_product_names', 10, 3 );
