# Suomen Kolibri

WordPress child theme for Suomen Kolibri, made by [Tapio Kauranen](https://tapiokauranen.com). The parent theme `eeco-theme` must be installed.

## How updates reach the site

The theme checks this repository for new releases (`inc/theme-updater.php`, using [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker)). New releases appear in **Dashboard → Updates**. With auto-updates enabled for the theme under **Appearance → Themes**, WordPress installs them on its own (it checks about twice a day).

## Releasing a change

1. Make the change and raise `Version:` in `style.css` (for example 1.0.0 → 1.0.1).
2. Commit and push to `main`.
3. The **Release** workflow creates the release `v1.0.1`. The site picks it up on its next update check.

Pushes that don't change the version aren't released.

## WooCommerce templates

All WooCommerce template overrides live in `woocommerce/` of this theme, including the ones inherited from the parent theme (the parent's copies were outdated, so current versions are kept here). They match WooCommerce 11.1. When WooCommerce reports outdated templates under **WooCommerce → Status → Templates**, update the listed files here: start from the new WooCommerce template and re-apply the theme's changes.

## Search engine features (`inc/audit/`)

Added after the SEO audit of September 2026. None of them changes how the site looks.

| File | What it does |
|---|---|
| `index-control.php` | Product tag pages and the attribute pages (size, taste, model, colour) and blog tags are `noindex, follow` and leave the sitemap. About 40 pages that bring visitors stay indexed (list in the file). |
| `one-h1.php` | Exactly one H1 per page: the first H2 becomes the H1 on pages without one, extra H1 become H2 with the class `h1`, so the look is the same. Front page slider captions are plain text (`template-parts/sections/slider.php`). |
| `schema.php` | More complete structured data: product brand, full image addresses, item condition, clean description; company address, phone, e-mail, business ID and the shop with opening hours on the Yhteystiedot page. |
| `product-removed.php` | A product in the trash (or deleted) shows "Tuote poistunut valikoimasta" with a link to its category and answers `410 Gone` instead of "Sivua ei löytynyt". A redirect made in the Redirection plugin wins. |
| `images.php` | ALT texts for the logo, footer badges and sliders, lazy loading for images far down the page. |
| `content-links.php` | Removes `preview` parameters from links in content and removes links to products that no longer exist (the text stays). |

The titles, descriptions and redirects of the audit are settings in the database, not theme code, and were applied with separate scripts.

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
