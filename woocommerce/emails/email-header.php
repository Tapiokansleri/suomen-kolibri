<?php
/**
 * Email Header
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$header_order = ( isset( $email ) && is_a( $email, 'WC_Email' ) && is_a( $email->object, 'WC_Order' ) ) ? $email->object : null;
$header_date  = $header_order ? $header_order->get_date_created() : null;

// Load colours
$bg 		= get_option( 'woocommerce_email_background_color' );
$body		= get_option( 'woocommerce_email_body_background_color' );
$base 		= get_option( 'woocommerce_email_base_color' );
$base_text 	= wc_light_or_dark( $base, '#202020', '#ffffff' );
$text 		= get_option( 'woocommerce_email_text_color' );

$bg_darker_10 = wc_hex_darker( $bg, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline. !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
$wrapper = "
	background-color: " . esc_attr( $bg ) . ";
	width:100%;
	-webkit-text-size-adjust:none !important;
	margin:0;
	padding: 70px 0 70px 0;
";
$template_container = "
	-webkit-box-shadow:0 0 0 3px rgba(0,0,0,0.025) !important;
	box-shadow:0 0 0 3px rgba(0,0,0,0.025) !important;
	-webkit-border-radius:6px !important;
	border-radius:6px !important;
	background-color: " . esc_attr( $body ) . ";
	border: 1px solid $bg_darker_10;
	-webkit-border-radius:6px !important;
	border-radius:6px !important;
";
$template_header = "
	background-color: " . esc_attr( $base ) .";
	color: $base_text;
	-webkit-border-top-left-radius:6px !important;
	-webkit-border-top-right-radius:6px !important;
	border-top-left-radius:6px !important;
	border-top-right-radius:6px !important;
	border-bottom: 0;
	font-family:Arial;
	font-weight:bold;
	line-height:100%;
	vertical-align:middle;
";
$body_content = "
	background-color: " . esc_attr( $body ) . ";
	-webkit-border-radius:6px !important;
	border-radius:6px !important;
";
$body_content_inner = "
	color: $text_lighter_20;
	font-family:Arial;
	font-size:14px;
	line-height:150%;
	text-align:left;
";
$header_content_h1 = "
	color: " . esc_attr( $base_text ) . ";
	margin:0;
	padding: 28px 24px;
	text-shadow: 0 1px 0 $base_lighter_20;
	display:block;
	font-family:Arial;
	font-size:20px;
	font-weight:bold;
	text-align:left;
	line-height: 150%;
";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo get_bloginfo( 'name' ); ?></title>
	</head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<div style="<?php echo $wrapper; ?>">
        	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
            	<tr>
                	<td align="center" valign="top">

                    	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="<?php echo $template_container; ?>">
                        	<tr>                		
                            	<td align="center" valign="top">
                                    <!-- Header -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style="<?php echo $template_header; ?> background:#fff;" bgcolor="#ffffff">
                                        <tr>
                                        <td>
												<div id="template_header_image">
													<?php if ( $img = get_option( 'bgh_theme_logo' ) ) {
															echo '<p style="margin-top:0;"><img src="' . wp_get_attachment_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" /></p>';
														} ?>
												</div>
											</td>
                                            <td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 16px;">
                                            	<h1 style="color: #000;
margin: 0;
padding: 5px 5px;
text-shadow: 0 1px 0 #7797b4;
display: block;
font-family: Arial;
font-size: 16px;
font-weight: bold;
text-align: left;
line-height: 150%;"><?php echo $email_heading; ?></h1><?php if ( $header_date ) :
	$header_timestamp = $header_date->getTimestamp();
	?><h2 style="color: #000;
margin: 0;
padding: 5px 5px;
text-shadow: 0 1px 0 #7797b4;
display: block;
font-family: Arial;
font-size: 16px;
font-weight: bold;
text-align: left;
line-height: 150%;"><?php printf( '<time datetime="%s">%s</time>', esc_attr( date_i18n( 'c', $header_timestamp ) ), esc_html( date_i18n( wc_date_format(), $header_timestamp ) ) ); ?></h2><?php endif; ?>

                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Header -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Body -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                    	<tr>
                                            <td valign="top" style="<?php echo $body_content; ?>">
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div style="<?php echo $body_content_inner; ?>">