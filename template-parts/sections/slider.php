<?php 

// STYLE VARIABLES
$slide_time = get_sub_field('slide_time');
if (!empty($slide_time)) {
    $milliseconds = $slide_time * 1000;
}
$layer = get_sub_field('slide_layer');
$layer_color = get_sub_field('slide_layer_color');
$section_width = get_sub_field('slide_section_width');
$content_align = get_sub_field('slide_content_align');
$font_color = get_sub_field('slide_font_color');
$ctaStyle = get_sub_field('slide_cta_style');
$ctaStyle2 = get_sub_field('slide_cta_style_2');
$text_box = get_sub_field('slide_text_box');
$text_box_color = get_sub_field('slide_text_box_color');

// DEVELOPER VARIABLES
$id = get_sub_field('slide_id');
$class = get_sub_field('slide_class'); ?>

<section <?php if ($id) { echo 'id="'.$id.'"'; } ?> class="slider <?php echo $section_width; if ($class) { echo ' '.$class; } if ($font_color) { echo ' '.$font_color; } ?>">
    <div id="carouselIndicators" class="carousel slide" data-ride="carousel" <?php if (!empty($slide_time)) { echo 'data-interval="'.$milliseconds.'"'; } ?>>
        <?php if (have_rows('slider_repeater')): ?>
            <ol class="carousel-indicators">
                <?php 
                $counter = 0;
                while (have_rows('slider_repeater')): the_row(); ?>
                    <li data-target="#carouselIndicators" data-slide-to="<?php echo $counter; ?>" <?php if ($counter == 0) { echo 'class="active"'; } ?>></li>
                <?php $counter++; endwhile; ?>
            </ol>
        <?php endif; ?> 
        <div class="carousel-inner">
            <?php if (have_rows('slider_repeater')): 
                $count = 0;
                while (have_rows('slider_repeater')): the_row(); ?>
                    <div class="carousel-item<?php if ($count == 0) { echo ' active'; }?>">
                        <?php
                        // VARIABLES
                        $img = get_sub_field('slide_desktop'); 
                        $imageMobile = get_sub_field('slide_mobile');
                        $text_1 = get_sub_field('slide_text_1');
                        $text_2 = get_sub_field('slide_text_2');
                        $text_3 = get_sub_field('slide_text_3');
                        $link = get_sub_field('slide_cta');
                        $link2 = get_sub_field('slide_cta_2');
                        ?>
                        <img class="w-100<?php if ($imageMobile) { echo ' d-none d-md-block'; } ?>" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                        <?php if ($imageMobile): ?>
                            <img src="<?php echo $imageMobile['url']; ?>" alt="<?php echo $imageMobile['alt']; ?>" class="d-block d-md-none" />
                        <?php endif; ?>
                        <?php if ($layer) {
                            echo '<div class="layer '.$layer_color.'"></div>';
                        } ?>
                        <div class="container"><div class="row"><div class="col-md-4 col-12 eeco-child-slider <?php if ($content_align == 'center') { echo ' offset-md-3'; } elseif ($content_align == 'right') { echo ' offset-md-6'; } echo ' text-'.$content_align; ?>">
                            <div class="content-box">
                            <?php if($text_box): ?>
                                <div class="content <?php echo $text_box_color; ?>">
                            <?php endif;
                            if ($text_1) { echo '<h1>'.$text_1.'</h1>'; }
                            if ($text_2) { echo '<h3>'.$text_2.'</h3>'; }
                            if ($text_3) { echo '<h3>'.$text_3.'</h3>'; }?>
                            <div class="slider-button-wrapper">
                            <?php 
                            if( $link ): 
                                $link_url = $link['url'];
                                $link_title = $link['title'];
                                $link_target = $link['target'] ? $link['target'] : '_self';
                                ?>
                                <a class="<?php if($ctaStyle == 'primary-cta' || $ctaStyle == 'secondary-cta') { echo 'button '.$ctaStyle; } ?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                            <?php endif; ?>
                            </div>
                            <?php if($text_box): ?> 
                                </div>
                            <?php endif; ?>
                            </div>
                        </div></div></div>
                    </div>
                <?php $count++; endwhile;
            endif; ?>
        </div>
        <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>