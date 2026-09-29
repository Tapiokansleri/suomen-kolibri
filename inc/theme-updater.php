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

$suomen_kolibri_updater = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
	'https://github.com/Tapiokansleri/suomen-kolibri/',
	get_stylesheet_directory() . '/functions.php',
	'suomen-kolibri'
);

// Install the suomen-kolibri.zip attached to each release (see .github/workflows/release.yml).
$suomen_kolibri_updater->getVcsApi()->enableReleaseAssets( '/suomen-kolibri\.zip($|[?&#])/i' );
