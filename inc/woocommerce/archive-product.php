<?php

// Remove parent theme's category description handler before the hook fires.
add_action( 'woocommerce_archive_description', function () {
	remove_action( 'woocommerce_archive_description', 'bgh_category_short_desc', 10 );
}, 0 );

function eeco_child_category_short_desc() {

	if ( is_product_category() || is_tax('tuotemerkki') ||  is_tax('product_tag')  || is_product_attribute()){
        global $wp_query;
        $term = $wp_query->get_queried_object();
        $image = get_field('category_desktop_image', $term);
        // GET SHORT DESCRIPTION AND IMAGES FROM ACF
        $shortDesc = $term->description;
        $campaignShort = get_field('campaign_category_description', $term);
        $campaignDesktopImage = get_field('campaign_category_desktop_image', $term);
        $campaignMobileImage = get_field('campaign_category_mobile_image', $term);

        // GET CURRENT DATE
        $today = date('Y-m-d');

        // GET SCHEDULE DATES
        $campaign = get_field('category_schedule_campaign', $term);
        $campaign_dates = get_field('category_campaign_dates', $term);
        if (!empty($campaign_dates)) {
            $start = $campaign_dates['category_campaign_from'];
            $end = $campaign_dates['category_campaign_to'];
        }

        // Check whether h1 was already rendered by eeco_wrap_cat_image_and_description_open
        $show_title_over_image = get_option('bgh_product_archive_title_layout');
        $h1_already_rendered = ( $show_title_over_image == 'top' && !empty($image) );

        // Check if any description content already contains an h1
        $desc_has_h1 = ( stripos( $shortDesc, '<h1' ) !== false )
                    || ( stripos( $campaignShort, '<h1' ) !== false );
        $show_auto_h1 = ! $h1_already_rendered && ! $desc_has_h1 && apply_filters( 'woocommerce_show_page_title', true );

        if ( $campaign == true && !empty($start) && !empty($end) && strtotime($today) >= strtotime($start) && strtotime($today) <= strtotime($end) ) {
            $desktopImage = isset($campaignDesktopImage['url']) ? $campaignDesktopImage['url'] : '';
            $desktopImageAlt = isset($campaignDesktopImage['alt']) ? $campaignDesktopImage['alt'] : '';
            $mobileImage = isset($campaignMobileImage['url']) ? $campaignMobileImage['url'] : '';
            $mobileImageAlt = isset($campaignMobileImage['alt']) ? $campaignMobileImage['alt'] : '';
            if ( $show_auto_h1 ) {
                echo '<h1 class="woocommerce-products-header__title page-title">'; woocommerce_page_title(); echo '</h1>';
            }
            echo '<div class="campaign-content-wrapper">';
                if ($desktopImage) {
                    echo '<img class="campaign-content-wrapper__desktop-image category-image-campaign-desktop d-none d-lg-block mb-3" src="'.$desktopImage.'" alt="'.$desktopImageAlt.'">';
                    echo '<img class="campaign-content-wrapper__mobile-image category-image-campaign-desktop d-lg-none mb-3" src="'.$mobileImage.'" alt="'.$mobileImageAlt.'">';
                }
            echo '<div class="content-closed">'.$campaignShort.'</div>';
            echo '</div>';
        } elseif ($shortDesc && !is_paged()) {
            ?>
            <div class="content-closed">
                <?php if ( $show_auto_h1 ) : ?>
                    <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
                <?php endif; ?>
                <p><?php echo $shortDesc; ?></p>
            </div>
            <a class="desc-readmore d-none more-less" data-less="<?php _e('Close', 'bgh-theme'); ?>" data-more="<?php _e('Read more', 'bgh-theme');?>"><?php _e('Read more', 'bgh-theme');?></a>
            <?php
        } elseif (empty($shortDesc) && !is_paged()) {
            if ( $show_auto_h1 ) {
                echo '<h1 class="woocommerce-products-header__title page-title">'; woocommerce_page_title(); echo '</h1>';
            }
        }
    }
    // PRODUCT CATALOG CONTENT
    if (!is_product_category() && !is_tax('tuotemerkki') && !is_tax('product_tag') && !is_product_attribute()) {
        $catalog_content = get_option('bgh_product_catalog_content');
        $catalog_has_h1 = ( stripos( $catalog_content, '<h1' ) !== false );
        if (!empty($catalog_content)) {
            ?>
            <div class="content-closed">
                <?php
                    $show_title_under_image = get_option('bgh_product_archive_title_layout');
                    if ( !$catalog_has_h1 && ($show_title_under_image == 'under' || get_option('bgh_product_catalog_image') != true) ) {
                        if ( apply_filters( 'woocommerce_show_page_title', true ) ) {
                            echo '<h1 class="woocommerce-products-header__title page-title">'; woocommerce_page_title(); echo '</h1>';
                        }
                    }
                ?>
                <p><?php echo $catalog_content; ?></p>
            </div>
            <a class="desc-readmore d-none more-less" data-less="<?php _e('Close', 'bgh-theme'); ?>" data-more="<?php _e('Read more', 'bgh-theme');?>"><?php _e('Read more', 'bgh-theme');?></a>
            <?php
        } else {
            if ( apply_filters( 'woocommerce_show_page_title', true ) && !is_shop() ) {
                echo '<h1 class="woocommerce-products-header__title page-title">'; woocommerce_page_title(); echo '</h1>';
            }
        }
    }
}
add_action('woocommerce_archive_description', 'eeco_child_category_short_desc', 10 );
