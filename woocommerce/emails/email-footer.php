<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$base = get_option( 'woocommerce_email_base_color' );

$base_lighter_40 = wc_hex_lighter( $base, 40 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	border-top:0;
	-webkit-border-radius:6px;
";

$credit = "
	border:0;
	color: $base_lighter_40;
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:left;
";
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
                        	<tr>
                            	<td align="left" valign="top">
                                    <!-- Footer -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_footer" style="<?php echo $template_footer; ?>">
                                    	<tr>
                                        	<td valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td colspan="2" align="left" valign="top" id="credit" style="font-family: Arial;
font-size: 12px; line-height:125%; color: #000; text-align:left; padding: 0 8px 48px 20px !important;">
                                                 Suomen Kolibri Oy<br>
												 Kalevantie 7<br>
												 33100 TAMPERE</td>
												 <td valign="top" style="font-family: Arial;
font-size: 12px; line-height:125%;">+358 (0)3 3454 5000<br>kolibri@suomenkolibri.fi<br>
												 www.suomenkolibri.fi</td>
												 <td valign="top" style="font-family: Arial;
font-size: 12px; line-height:125%;">Kotipaikka Tampere<br>
Y-tunnus 0833183-2<br>
Alv.rek</td>

												 





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
    </body>
</html>
