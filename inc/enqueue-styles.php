<?php 

function bgh_child_styles() {
    // CHILD STYLE
    $style_file = get_stylesheet_directory().'/assets/css/child-style.css';
    $version = filemtime($style_file); 
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() .'/assets/css/child-style.css', array(), $version );

	// PRODUCT STYLES
	if( is_product() ) {
		$style_file = get_stylesheet_directory().'/assets/css/child-product-page.css';
		$version = filemtime($style_file); 
		wp_enqueue_style( 'child-product-page', get_stylesheet_directory_uri() .'/assets/css/child-product-page.css', array(), $version );
	}

	if( is_cart() ) {
		$style_file = get_stylesheet_directory().'/assets/css/child-cart.css';
		$version = filemtime($style_file);
		wp_enqueue_style( 'child-cart', get_stylesheet_directory_uri() .'/assets/css/child-cart.css', array(), $version );
	}

	if ( is_checkout() ) {
		$style_file = get_stylesheet_directory().'/assets/css/child-checkout.css';
		$version = filemtime($style_file);
		wp_enqueue_style( 'child-checkout', get_stylesheet_directory_uri() .'/assets/css/child-checkout.css', array(), $version );
	}

	// CHILD SLIDER
	$style_file = get_stylesheet_directory().'/assets/css/child-slider.css';
	$version = filemtime($style_file); 
	wp_enqueue_style( 'child-slider', get_stylesheet_directory_uri() .'/assets/css/child-slider.css', array(), $version );

	// CHILD PRODUCT ARCHIVE
	if ( is_shop() || is_product_category() || is_tax('product_tag') || is_tax('tuotemerkki') || is_page_template('template-parts/page-sale-featured.php') || is_product_attribute()  || is_page_template('template-parts/pick-redeemable-products.php') || is_search() ){
		$style_file = get_stylesheet_directory().'/assets/css/child-product-archive.css';
		$version = filemtime($style_file); 
		wp_enqueue_style( 'child-product-archive', get_stylesheet_directory_uri() .'/assets/css/child-product-archive.css', array(), $version );
	}

	// REDEEM GIFT PAGE
	if ( is_page_template('template-parts/page-redeem-gift.php') ) {
		$style_file = get_stylesheet_directory().'/assets/css/child-redeem-gift.css';
		$version = filemtime($style_file); 
		wp_enqueue_style( 'child-redeem-gift', get_stylesheet_directory_uri() .'/assets/css/child-redeem-gift.css', array(), $version );
	}

	// POST ARCHIVE
	if ( is_home() || is_category() || is_tag()) {
		$style_file = get_stylesheet_directory().'/assets/css/child-post-archive.css';
		$version = filemtime($style_file); 
		wp_enqueue_style( 'child-post-archive', get_stylesheet_directory_uri() .'/assets/css/child-post-archive.css', array(), $version );
	}

	if( is_page_template('template-parts/pick-redeemable-products.php') ) {
		wp_enqueue_style( 'product-archive', get_template_directory_uri() .'/assets/css/product-archive.css', array(), true );
	}
}

add_action( 'wp_enqueue_scripts', 'bgh_child_styles', 99);