<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

$email = $email ?? null;

// Theme: inline footer styles (for Gmail compatibility).
$template_footer = '
	border-top:0;
	-webkit-border-radius:6px;
';

?>
																		</div>
																	</td>
																</tr>
															</table>
															<!-- End Content -->
														</td>
													</tr>
												</table>
												<!-- End Body -->
											</td>
										</tr>
									<!-- Theme: footer sits inside #template_container, as in the original theme design. -->
									<tr>
										<td align="left" valign="top">
											<!-- Footer -->
											<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_footer" role="presentation" style="<?php echo esc_attr( $template_footer ); ?>">
												<tr>
													<td valign="top">
														<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
															<tr>
																<td colspan="2" align="left" valign="top" id="credit" style="font-family: Arial; font-size: 12px; line-height:125%; color: #000; text-align:left; padding: 0 8px 48px 20px !important;">
																	Suomen Kolibri Oy<br>
																	Kalevantie 7<br>
																	33100 TAMPERE
																</td>
																<td valign="top" style="font-family: Arial; font-size: 12px; line-height:125%;">
																	+358 (0)3 3454 5000<br>
																	kolibri@suomenkolibri.fi<br>
																	www.suomenkolibri.fi
																</td>
																<td valign="top" style="font-family: Arial; font-size: 12px; line-height:125%;">
																	Kotipaikka Tampere<br>
																	Y-tunnus 0833183-2<br>
																	Alv.rek
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											<!-- End Footer -->
										</td>
									</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</td>
				<td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
			</tr>
		</table>
	</body>
</html>
