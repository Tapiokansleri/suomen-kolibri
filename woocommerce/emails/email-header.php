<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.7.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );
$store_name                 = $store_name ?? get_bloginfo( 'name', 'display' );

/**
 * Filter the URL used for the email header image/logo link.
 *
 * Return an empty string to disable the link.
 *
 * @since 10.7.0
 * @param string $url The URL to link to. Defaults to the site home URL.
 */
$header_image_url = apply_filters( 'woocommerce_email_header_image_url', home_url() );

// Theme: show the order date under the email heading.
$header_order = ( isset( $email ) && is_a( $email, 'WC_Email' ) && is_a( $email->object, 'WC_Order' ) ) ? $email->object : null;
$header_date  = $header_order ? $header_order->get_date_created() : null;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<title><?php echo esc_html( $store_name ); ?></title>
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<table width="100%" id="outer_wrapper" role="presentation">
			<tr>
				<td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
				<td width="600">
					<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
						<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="inner_wrapper" role="presentation">
							<tr>
								<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container" role="presentation">
										<tr>
											<td align="center" valign="top">
												<!-- Header -->
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" role="presentation" style="background:#fff;" bgcolor="#ffffff">
													<tr>
														<td>
															<?php
															$img = get_option( 'woocommerce_email_header_image' );
															/**
															 * This filter is documented in templates/emails/email-styles.php
															 *
															 * @since 9.6.0
															 */
															if ( apply_filters( 'woocommerce_is_email_preview', false ) ) {
																$img_transient = get_transient( 'woocommerce_email_header_image' );
																$img           = false !== $img_transient ? $img_transient : $img;
															}

															// Theme: use the theme's brand logo in emails.
															$theme_logo_id  = get_option( 'bgh_theme_logo' );
															$theme_logo_url = $theme_logo_id ? wp_get_attachment_url( $theme_logo_id ) : false;
															if ( $theme_logo_url ) {
																$img = $theme_logo_url;
															}

															if ( $email_improvements_enabled ) :
																?>
																<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																	<tr>
																		<td id="template_header_image">
																			<?php
																			if ( $img ) {
																				$image_html = '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $store_name ) . '" />';
																				if ( $header_image_url ) {
																					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $image_html is built from esc_url() and esc_attr().
																					echo '<p style="margin-top:0;"><a href="' . esc_url( $header_image_url ) . '" style="display: inline-block; text-decoration: none;" target="_blank">' . $image_html . '</a></p>';
																				} else {
																					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																					echo '<p style="margin-top:0;">' . $image_html . '</p>';
																				}
																			} elseif ( $header_image_url ) {
																				echo '<p class="email-logo-text"><a href="' . esc_url( $header_image_url ) . '" style="color: inherit; text-decoration: none;" target="_blank">' . esc_html( $store_name ) . '</a></p>';
																			} else {
																				echo '<p class="email-logo-text">' . esc_html( $store_name ) . '</p>';
																			}
																			?>
																		</td>
																	</tr>
																</table>
															<?php else : ?>
																<div id="template_header_image">
																	<?php
																	if ( $img ) {
																		$image_html = '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $store_name ) . '" />';
																		if ( $header_image_url ) {
																			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $image_html is built from esc_url() and esc_attr().
																			echo '<p style="margin-top:0;"><a href="' . esc_url( $header_image_url ) . '" style="display: inline-block; text-decoration: none;" target="_blank">' . $image_html . '</a></p>';
																		} else {
																			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																			echo '<p style="margin-top:0;">' . $image_html . '</p>';
																		}
																	}
																	?>
																</div>
															<?php endif; ?>
														</td>
													</tr>
													<tr>
														<td style="padding-left: 16px;">
															<h1 style="color: #000; margin: 0; padding: 5px 5px; text-shadow: 0 1px 0 #7797b4; display: block; font-family: Arial; font-size: 16px; font-weight: bold; text-align: left; line-height: 150%;"><?php echo esc_html( $email_heading ); ?></h1>
															<?php
															if ( $header_date ) :
																$header_timestamp = $header_date->getTimestamp();
																?>
																<h2 style="color: #000; margin: 0; padding: 5px 5px; text-shadow: 0 1px 0 #7797b4; display: block; font-family: Arial; font-size: 16px; font-weight: bold; text-align: left; line-height: 150%;"><?php printf( '<time datetime="%s">%s</time>', esc_attr( date_i18n( 'c', $header_timestamp ) ), esc_html( date_i18n( wc_date_format(), $header_timestamp ) ) ); ?></h2>
															<?php endif; ?>
														</td>
													</tr>
												</table>
												<!-- End Header -->
											</td>
										</tr>
										<tr>
											<td align="center" valign="top">
												<!-- Body -->
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body" role="presentation">
													<tr>
														<td valign="top" id="body_content">
															<!-- Content -->
															<table border="0" cellpadding="20" cellspacing="0" width="100%" role="presentation">
																<tr>
																	<td valign="top" id="body_content_inner_cell">
																		<div id="body_content_inner">
