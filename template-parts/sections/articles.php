<?php 
// CONTENT

$section_title = get_sub_field('articles_section_title');
$section_desc = get_sub_field('articles_section_description');
$campaigns = get_sub_field('product_listing_campaigns');
$articlesStyle = get_sub_field('articles_style');
$imgPosition = get_sub_field('articles_image');
$articlesPerRow = get_sub_field('articles_per_row_desktop');
$articlesPerRowMobile = get_sub_field('articles_per_row_mobile');
$display = get_sub_field('display_articles');
$articleHighlights = get_sub_field('articles_highlights');
$ctaText = get_sub_field('articles_cta_text');

// STYLE
$sectionBg = get_sub_field ('articles_section_bg_color_bg_image');
$sectionBgColor = get_sub_field('articles_section_bg_color');
$sectionBgImage = get_sub_field('articles_section_bg_image');
$layer = get_sub_field('articles_layer');
$layerColor = get_sub_field('articles_layer_color');
$containerWidth = get_sub_field('articles_container_width');
$desktopWidth = get_sub_field('articles_desktop_width');
$mobileWidth = get_sub_field('articles_mobile_width');
$title_desc_align_desktop = get_sub_field('articles_title_desc_align');
$title_desc_align_mobile = get_sub_field('articles_title_desc_align_mobile');
$contentAlignment = get_sub_field('articles_article_text_align');
$sectionTitleColor = get_sub_field('articles_section_title_color');
$sectionDescColor = get_sub_field('articles_section_description_color');
$articleCats = get_sub_field('articles_categories');
$name = get_sub_field('articles_article_name');
$articleDate = get_sub_field('articles_article_date');
$excerpt = get_sub_field('articles_article_excerpt');
$ctaStyle = get_sub_field('articles_cta_style');
$section_heading = get_sub_field('articles_section_title_heading');
$desktopSpacing = get_sub_field('articles_spacing_desktop');
$mobileSpacing = get_sub_field('articles_spacing_mobile');
$article_font = get_sub_field('articles_section_font_color');

// DEVELOPER
$class = get_sub_field('articles_class');
$id = get_sub_field('articles_id');

?>

<section <?php if (!empty($id)) { echo 'id="'.esc_attr($id).'"'; } ?>class="articles<?php echo eeco_section_spacing($desktopSpacing, $mobileSpacing); if ($sectionBg == 'bg_color') { echo ' ' . esc_attr($sectionBgColor); } if (!empty($class)) { echo ' '.esc_attr($class); } ?>" <?php if ($sectionBg == 'bg_image' && $sectionBgImage) { echo 'style="background-image: url('.$sectionBgImage['url'].'); background-size: cover; background-position: center;"'; } ?>>
    <div class="<?php echo esc_attr($containerWidth); ?>">
        <div class="row justify-content-center<?php if ($containerWidth == 'full-width') { echo ' no-gutters'; } ?>">
            <div class="<?php echo esc_attr($desktopWidth) . ' ' . esc_attr($mobileWidth); ?>">
                <?php if (!empty($section_title) || !empty($section_desc)) {
                    $args = array(
                        'title' => $section_title, 
                        'description' => $section_desc, 
                        'heading' => $section_heading, 
                        'text-align' => $title_desc_align_desktop, 
                        'text-align-mobile' => $title_desc_align_mobile, 
                        'title-color' => $sectionTitleColor, 
                        'description-color' => $sectionDescColor
                    );
                    get_template_part('template-parts/sections/parts/title-desc', null, apply_filters('eeco_title_desc_arguments', $args));
                } 
                if ($articlesStyle == 'carousel') {
                    get_template_part('template-parts/sections/parts/articles-carousel', null, ['container' => $containerWidth, 'color' => $article_font]);
                } elseif ( $articlesStyle == 'grid' ) {
                    get_template_part('template-parts/sections/parts/articles-grid', null, ['color' => $article_font]);
                } else {
                    get_template_part('template-parts/sections/parts/articles-list', null, ['color' => $article_font]);
                } ?>
                
            </div>
        </div>
    </div>
    <?php if ($layer) {
        echo '<div class="layer '.$layerColor.'"></div>';
    } ?>
</section>