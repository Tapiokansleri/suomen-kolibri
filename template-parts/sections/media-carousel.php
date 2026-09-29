<?php 

//STYLE VARIABLES
$sectionBg = get_sub_field ('media_carousel_section_bg_color_bg_image');
$sectionBgColor = get_sub_field('media_carousel_section_bg_color');
$sectionBgImage = get_sub_field('media_carousel_section_bg_image');
if (!empty($sectionBgImage)) {
    $bgImageFilename = $sectionBgImage['filename'];
}
$layer = get_sub_field('media_carousel_layer');
$layerColor = get_sub_field('media_carousel_layer_color');
$containerWidth = get_sub_field('media_carousel_container_width');
$class = get_sub_field('media_carousel_class');
$id = get_sub_field('media_carousel_id');
$title_desc_align = get_sub_field('media_carousel_text_align');
$title_desc_align_mobile = get_sub_field('media_carousel_title_desc_align_mobile');
$contentAlignment = get_sub_field('media_carousel_card_text_align');
$titleColor = get_sub_field('media_carousel_section_title_color');
$descriptionColor = get_sub_field('media_carousel_section_description_color');
$border = get_sub_field('media_carousel_border');
$ctaStyle = get_sub_field('media_carousel_cta_style');
$carousel_mobile_width = get_option('eeco_carousel_mobile_width'); 
$section_heading = get_sub_field('media_carousel_section_title_heading');
$desktopSpacing = get_sub_field('media_carousel_spacing_desktop');
$mobileSpacing = get_sub_field('media_carousel_spacing_mobile');

//CONTENT
$title = get_sub_field('media_carousel_section_title');
$description = get_sub_field('media_carousel_section_description'); ?>

<section <?php echo $id ? 'id="'.$id.'"' : ''; ?> class="media-carousel<?php echo eeco_section_spacing($desktopSpacing, $mobileSpacing); echo $class ? ' '.$class : ''; echo $sectionBgColor ? ' '.$sectionBgColor : ''; ?>"<?php if ($sectionBg == 'bg_image' && $sectionBgImage) { echo 'style="background-image: url('.$sectionBgImage['url'].'); background-size: cover; background-position: center;"'; } ?>>
    <div class="<?php if ($containerWidth == 'container') {echo 'container';} elseif ($containerWidth == 'container-fluid') {echo 'container-fluid';} else {echo 'full-width px-0'; } ?>">
        <div class="row justify-content-center<?php echo $containerWidth == 'full-width' ? ' no-gutters' : ''; ?>">
            <div class="col">
                <?php if (!empty($title) || !empty($description)) {
                    $args = array(
                        'title' => $title, 
                        'description' => $description, 
                        'heading' => $section_heading, 
                        'text-align' => $title_desc_align, 
                        'text-align-mobile' => $title_desc_align_mobile, 
                        'title-color' => $titleColor, 
                        'description-color' => $descriptionColor
                    );
                    get_template_part('template-parts/sections/parts/title-desc', null, apply_filters('eeco_title_desc_arguments', $args));
                }
                $repeater = get_sub_field('media_carousel_repeater');
                $count = is_array( $repeater ) ? count( $repeater ) : 0;
                if (have_rows('media_carousel_repeater')): ?>
                <div class="<?php echo $count > 4 ? 'carousel-row' : 'row'; echo ' text-'.esc_attr($contentAlignment); echo $containerWidth == 'full-width' ? ' mx-0' : ''; ?>">
                    <?php if ($count > 4): ?>
                        <div class="scroll <?php echo $carousel_mobile_width?>">
                    <?php endif; ?>
                        <?php while (have_rows('media_carousel_repeater')): the_row(); 
                            $image = get_sub_field('media_carousel_image');
                            $title = get_sub_field('media_carousel_title');
                            $text = get_sub_field('media_carousel_text');
                            $ctaButton = get_sub_field('media_carousel_cta');
                            $hideCta = get_sub_field('hide_media_carousel_cta');
                            $titleColor =  get_sub_field('media_carousel_title_color');
                            $textColor  = get_sub_field('media_carousel_text_color');
                            $cardBg = get_sub_field('media_carousel_card_bg');
                        ?>
                        <div class="item<?php echo $count < 5 ? ' no-carousel' : '' ; ?>">
                            <div class="media-card-holder<?php echo $border == true ? ' border ' : ' ' ; echo esc_attr($cardBg); ?>">
                                <?php if ($ctaButton && $hideCta == true):
                                    $link_url = $ctaButton['url'];
                                    $link_target = $ctaButton['target'] ? $ctaButton['target'] : '_self'; ?>
                                    <a href="<?php echo esc_url($link_url); ?>" class="media-carousel-link" target="<?php echo esc_attr($link_target); ?>">
                                <?php endif; ?>
                                <?php if ( ! empty( $image['url'] ) ) : ?>
                                <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>">
                                <?php endif; ?>
                                <h4 class=<?php echo $titleColor; ?>><?php echo $title ?></h4>
                                <p class="<?php echo $textColor; ?>"><?php echo $text ?></p>
                                <?php 
                                if( $ctaButton && $hideCta == false ): 
                                    $link_url = $ctaButton['url'];
                                    $link_title = $ctaButton['title'];
                                    $link_target = $ctaButton['target'] ? $ctaButton['target'] : '_self';
                                    ?>
                                    <a class="<?php if($ctaStyle) { echo 'button '.$ctaStyle; } ?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                                <?php endif; ?>
                                <?php if ($ctaButton && $hideCta == true): ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endwhile;  ?>
                    </div>
                    <?php if ($count > 4) { eeco_carousel_arrows($containerWidth); } ?>
                </div>
               <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($layer) {
        echo '<div class="layer '.$layerColor.'"></div>';
    } ?>
</section>