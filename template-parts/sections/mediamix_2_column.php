<?php 

// CONTENT VARIABLES
$title = get_sub_field('mediamix_2_title');
$description = get_sub_field('mediamix_2_description');
$column_1_selection = get_sub_field('mediamix_2_first_media_selection');
$column_2_selection = get_sub_field('mediamix_2_second_media_selection');

// STYLE VARIABLES
$section_bg = get_sub_field('mediamix_2_section_bg_color_bg_image');
$section_bg_color = get_sub_field('mediamix_2_section_bg_color');
$section_bg_img = get_sub_field('mediamix_2_section_bg_image');
$layer = get_sub_field('mediamix_2_layer');
$layer_color = get_sub_field('mediamix_2_layer_color');
$container_width = get_sub_field('mediamix_2_container_width');
$class = get_sub_field('mediamix_2_class');
$id = get_sub_field('mediamix_2_id');
$content_width_desktop = get_sub_field('mediamix_2_desktop_width');
$content_width_mobile = get_sub_field('mediamix_2_mobile_width');
$title_desc_align = get_sub_field('mediamix_2_title_description_alignment');
$title_desc_align_mobile = get_sub_field('mediamix_2_title_desc_align_mobile');
$content_align = get_sub_field('mediamix_2_content_alignment');
$title_color = get_sub_field('mediamix_2_title_color');
$desc_color = get_sub_field('mediamix_2_description_color');
$column_ratio = get_sub_field('mediamix_2_column_relation');
$column_order_desktop = get_sub_field('mediamix_2_order_desktop');
$column_order_mobile = get_sub_field('mediamix_2_order_mobile');
$column_1_cta_style = get_sub_field('mediamix_2_cta_first_style');
$column_2_cta_style = get_sub_field('mediamix_2_cta_second_style');
$vertical_align_items = get_sub_field('mediamix_2_vertical_align');
$section_heading = get_sub_field('mediamix_2_section_title_heading');
$desktopSpacing = get_sub_field('mediamix_2_spacing_desktop');
$mobileSpacing = get_sub_field('mediamix_2_spacing_mobile');
$section_font = get_sub_field('mediamix_2_section_font_color');

switch ($vertical_align_items) {
    case 'flex-start':
        $alignitems = 'align-items-start';
        break;
    case 'center':
        $alignitems = 'align-items-center';
        break;
    case 'flex-end':
        $alignitems = 'align-items-end';
        break;
}
switch ($column_ratio) {
    case '1_1':
        $column1 = 'col-md-6';
        $column2 = 'col-md-6';
        break;
    case '1_2':
        $column1 = 'col-md-4';
        $column2 = 'col-md-8';
        break;
    case '1_3':
        $column1 = 'col-md-3';
        $column2 = 'col-md-9';
        break;
    case '2_1':
        $column1 = 'col-md-8';
        $column2 = 'col-md-4';
        break;
    case '3_1':
        $column1 = 'col-md-9';
        $column2 = 'col-md-3';
        break;
} ?>

<section <?php echo $id ? 'id="'.$id.'"' : '';?> class="mediamix_2_column<?php echo eeco_section_spacing($desktopSpacing, $mobileSpacing);  echo $class ? ' '.$class : ''; echo $section_bg_color ? ' '.$section_bg_color : ''; ?>"<?php if ($section_bg == 'bg_image' && $section_bg_img) { echo 'style="background-image: url('.$section_bg_img['url'].'); background-size: cover; background-position: center;"'; } ?>>
    <div class="<?php echo $container_width == 'container' ? 'container' : ($container_width == 'container-fluid' ? 'container-fluid' : 'full-width' ); ?>">
        <div class="row justify-content-center<?php if ($container_width == 'full-width') { echo ' no-gutters'; } ?>">
            <div class="<?php echo $content_width_desktop. ' '. $content_width_mobile?>">
                <?php if (!empty($title) || !empty($description)) {
                    $args = array(
                        'title' => $title, 
                        'description' => $description, 
                        'heading' => $section_heading, 
                        'text-align' => $title_desc_align, 
                        'text-align-mobile' => $title_desc_align_mobile, 
                        'title-color' => $title_color, 
                        'description-color' => $desc_color
                    );
                    get_template_part('template-parts/sections/parts/title-desc', null, apply_filters('eeco_title_desc_arguments', $args));
                }
                if (have_rows('mediamix_2_repeater')):
                    $counter = 1;
                    while (have_rows('mediamix_2_repeater')): the_row(); 
                        // COLUMN 1
                        $column_1_text = get_sub_field('mediamix_2_repeater_first_text');
                        $column_1_cta = get_sub_field('mediamix_2_repeater_first_cta');
                        $column_1_img = get_sub_field('mediamix_2_repeater_first_image');
                        $column_1_video = get_sub_field('field_mediamix_2_repeater_first_video');
                        $column_1_ig = get_sub_field('mediamix_2_repeater_first_instagram');
                        // COLUMN 2
                        $column_2_text = get_sub_field('mediamix_2_repeater_second_text');
                        $column_2_cta = get_sub_field('mediamix_2_repeater_second_cta');
                        $column_2_img = get_sub_field('mediamix_2_repeater_second_image');
                        $column_2_video = get_sub_field('field_mediamix_2_repeater_second_video');
                        $column_2_ig = get_sub_field('mediamix_2_repeater_second_instagram'); 
                        // ANALYTICS
                        $promoname_1 = get_sub_field('ga_mediamix_2_first_campaign_name');
                        $promoname_2 = get_sub_field('ga_mediamix_2_second_campaign_name');

                        if ($column_order_desktop == 'desk-shuffle') {
                            $order = $counter % 2 ? 'odd' : 'even';
                        } ?>

                        <div class="row<?php echo $container_width == 'full-width' ? ' no-gutters' : ''; echo ' ' .$column_order_desktop.'-'.$order . ' ' .$column_order_mobile . ' text-'.$content_align . ' ' .$alignitems . ' ' . $section_font; ?>">
                            <div class="<?php echo esc_attr($column1); ?> mediamix_2_first">
                                <?php if($promoname_1): ?>
                                    <div class="promotion-data"><?php do_action( 'eeco_promotions_data', $promoname_1 ); ?></div>
                                <?php endif; ?> 
                                <?php if ($column_1_selection == 'text') {
                                    echo $column_1_text;
                                    if( $column_1_cta ): 
                                        $link_url = $column_1_cta['url'];
                                        $link_title = $column_1_cta['title'];
                                        $link_target = $column_1_cta['target'] ? $column_1_cta['target'] : '_self';
                                        ?>
                                        <a class="<?php if($column_1_cta_style) { echo 'button '.$column_1_cta_style; } echo ($promoname_1 ? ' mediamix_2_cta' : ''); ?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                                    <?php endif;
                                } elseif ($column_1_selection == 'image') {
                                    $align = get_sub_field('mediamix_2_image_position');
                                    if ( ! empty( $column_1_img['url'] ) ) {
                                        echo '<img src="' . esc_url( $column_1_img['url'] ) . '" alt="' . esc_attr( $column_1_img['alt'] ?? '' ) . '" class="align-' . esc_attr( $align ) . '">';
                                    }
                                } elseif ($column_1_selection == 'video') {
                                    echo '<div class="embed-responsive embed-responsive-16by9">'.$column_1_video.'</div>';
                                } elseif ($column_1_selection == 'instagram') {
                                    echo do_shortcode($column_1_ig);
                                } ?>
                            </div>
                            <div class="<?php echo esc_attr($column2); ?> mediamix_2_second">
                            <?php if($promoname_2): ?>
                                <div class="promotion-data"><?php do_action( 'eeco_promotions_data', $promoname_2 ); ?></div>
                            <?php endif; ?> 
                            <?php if ($column_2_selection == 'text') {
                                echo $column_2_text;
                                if( $column_2_cta ): 
                                    $link_url = $column_2_cta['url'];
                                    $link_title = $column_2_cta['title'];
                                    $link_target = $column_2_cta['target'] ? $column_2_cta['target'] : '_self';
                                    ?>
                                    <a class="<?php if($column_2_cta_style) { echo 'button '.$column_2_cta_style; } echo ($promoname_2 ? ' mediamix_2_cta' : '');?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                                <?php endif;
                            } elseif ($column_2_selection == 'image') {
                                $align2 = get_sub_field('mediamix_2_second_image_position');
                                if ( ! empty( $column_2_img['url'] ) ) {
                                    echo '<img src="' . esc_url( $column_2_img['url'] ) . '" alt="' . esc_attr( $column_2_img['alt'] ?? '' ) . '" class="align-' . esc_attr( $align2 ) . '">';
                                }
                            } elseif ($column_2_selection == 'video') {
                                echo '<div class="embed-responsive embed-responsive-16by9">'.$column_2_video.'</div>';
                            } elseif ($column_2_selection == 'instagram') {
                                echo do_shortcode($column_2_ig);
                            } ?>
                            </div>
                        </div>
                    <?php $counter++; endwhile;
                endif; ?>
            </div>
        </div>
    </div>
    <?php if ($layer) {
        echo '<div class="layer '.$layer_color.'"></div>';
    } ?>
</section>