<?php
/**
 * Theme updates from GitHub releases.
 *
 * A new release on https://github.com/Tapiokansleri/suomen-kolibri shows up in
 * Dashboard → Updates like any other theme update. With auto-updates enabled
 * for the theme (Appearance → Themes), WordPress installs it on its own.
 *
 * @package Suomen_Kolibri
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/lib/plugin-update-checker/plugin-update-checker.php';

YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
	'https://github.com/Tapiokansleri/suomen-kolibri/',
	get_stylesheet_directory() . '/functions.php',
	'suomen-kolibri'
);
