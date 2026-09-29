# Suomen Kolibri

WordPress child theme for Suomen Kolibri, based on Eeco Theme Child. The parent theme `eeco-theme` must be installed.

## How updates reach the site

The theme checks this repository for new releases (`inc/theme-updater.php`, using [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker)). New releases appear in **Dashboard → Updates**. With auto-updates enabled for the theme under **Appearance → Themes**, WordPress installs them on its own (it checks about twice a day).

## Releasing a change

1. Make the change and raise `Version:` in `style.css` (for example 1.0.0 → 1.0.1).
2. Commit and push to `main`.
3. The **Release** workflow creates the release `v1.0.1`. The site picks it up on its next update check.

Pushes that don't change the version aren't released.

## WooCommerce templates

All WooCommerce template overrides live in `woocommerce/` of this theme, including the ones inherited from the parent theme (the parent's copies were outdated, so current versions are kept here). They match WooCommerce 10.7. When WooCommerce reports outdated templates under **WooCommerce → Status → Templates**, update the listed files here: start from the new WooCommerce template and re-apply the theme's changes.

## CSS

Styles are compiled from `assets/sass` into `assets/css`, and the compiled files are committed because the site uses them directly:

```
npm install
npm run build-css
```

## First install on the site

1. Download `suomen-kolibri.zip` from the latest release and upload it under **Appearance → Themes → Add New Theme → Upload Theme**.
2. Activate it. Menu locations and Customizer settings are copied from Eeco Theme Child once (`inc/migrate-theme-mods.php`).
3. Turn on **Enable auto-updates** for the theme.
4. Paste the Google Maps API key under **Settings → General → Google Maps API key**. It is kept out of this public repository; until it is set, the pick-up point map on the order page is hidden.
