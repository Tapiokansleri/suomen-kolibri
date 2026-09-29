<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1, maximum-scale=1">

	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="dns-prefetch" href="//www.facebook.com">
	<link rel="dns-prefetch" href="//connect.facebook.net">
	<link rel="dns-prefetch" href="//static.ak.facebook.com">
	<link rel="dns-prefetch" href="//static.ak.fbcdn.net">
	<link rel="dns-prefetch" href="//s-static.ak.facebook.com">

	<?php $favicon = get_option('bgh_theme_logo_favicon'); ?>
	<?php $touch = get_option('bgh_theme_logo_favicon'); ?>

	<?php if($favicon): ?>
		<link rel="shortcut icon" href="<?php echo wp_get_attachment_url($favicon); ?>" />
	<?php endif; ?>

	<?php if($touch): ?>
		<link rel="icon" href="<?php echo wp_get_attachment_url($touch); ?>" sizes="180x180">
		<link rel="apple-touch-icon" href="<?php echo wp_get_attachment_url($touch); ?>" sizes="180x180">
	<?php endif; ?>

	<!-- WP HEAD -->
	<?php wp_head(); ?>


</head>

<body <?php body_class(); ?> >
	<?php if (get_option('bgh_gtm_ID')): ?>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo get_option('bgh_gtm_ID'); ?>"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->	
	<?php endif;

	$menu = get_option('eeco_menu_template');

	// FLASH SALE TOP BAR
	$enabled = get_option('bgh_marketing_flash_top_enabled');
	if ( $enabled ) {
		get_template_part('template-parts/header/flash-sale-top-bar');
	} 
	// BENEFIT BAR
	$show_benefits_desktop = get_option('bgh_marketing_header_show_on_desktop');
	$show_benefits_mobile = get_option('bgh_marketing_header_show_on_mobile');
	
	if ( class_exists( 'WooCommerce' ) && ($show_benefits_mobile || $show_benefits_desktop && !is_checkout()) ) {
		get_template_part('template-parts/header/benefit-bar');
	} elseif ($show_benefits_mobile || $show_benefits_desktop) {
		get_template_part('template-parts/header/benefit-bar');
	}
	if (class_exists( 'WooCommerce' ) && !is_checkout() && $menu === 'menu-3' ): ?>
		<div class="sidebar-menu">
			<nav class="navbar navbar-expand-lg">
				<?php eeco_sidebar_nav(); ?>
			</nav>
		</div>
	<?php endif;
	
	