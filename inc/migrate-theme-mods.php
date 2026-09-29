<?php
/**
 * Carry over settings from Eeco Theme Child on first activation.
 *
 * Menu locations, Additional CSS and other Customizer settings are stored per
 * theme folder (theme_mods_{folder}), so without this the site would lose them
 * when switching from eeco-theme-child to this theme. Runs once; settings
 * already saved for this theme win over the copied ones.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function suomen_kolibri_copy_theme_mods() {
	if ( get_option( 'suomen_kolibri_theme_mods_copied' ) ) {
		return;
	}

	$old_mods = get_option( 'theme_mods_eeco-theme-child' );
	if ( is_array( $old_mods ) ) {
		// WordPress stores a fresh widget snapshot for the new theme during the switch.
		unset( $old_mods['sidebars_widgets'] );

		$option       = 'theme_mods_' . get_option( 'stylesheet' );
		$current_mods = get_option( $option );
		update_option( $option, array_merge( $old_mods, is_array( $current_mods ) ? $current_mods : array() ) );
	}

	update_option( 'suomen_kolibri_theme_mods_copied', 1, false );
}
add_action( 'after_switch_theme', 'suomen_kolibri_copy_theme_mods', 1 );
