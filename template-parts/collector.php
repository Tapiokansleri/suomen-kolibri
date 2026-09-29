<main>
    <?php if (class_exists('ACF') && have_rows('sections')) :
		if( !post_password_required( $post )):
			while (have_rows('sections')) : the_row();

				// HERO
				if( get_row_layout() == 'hero_image' ) {
					get_template_part('template-parts/sections/hero');
				}

				//MEDIAMIX 1 COLUMN
				elseif ( get_row_layout() == 'mediamix_1' ) {
					get_template_part('template-parts/sections/mediamix-1-column');
				}

				// MEDIAMIX 2 COLUMNS
				elseif ( get_row_layout() == 'mediamix_2' ) {
					get_template_part('template-parts/sections/mediamix_2_column');
				}
					// BANNERS
				elseif( get_row_layout() == 'section_banners' ) {
					get_template_part('template-parts/sections/banners');    
				}

				// CALL-TO-ACTION
				elseif ( get_row_layout() == 'call_to_action' ) {
					get_template_part('template-parts/sections/cta');
				}
				
				//CARDS
				elseif ( get_row_layout() == 'card' ) {
					get_template_part('template-parts/sections/card');
				}

				// MEDIA CAROUSEL
				elseif ( get_row_layout() == 'media_carousel' ) {
					get_template_part('template-parts/sections/media-carousel');
				}

				// PRODUCTS
				elseif( class_exists( 'WooCommerce' ) && get_row_layout() == 'products' ) {
					get_template_part('template-parts/sections/products');
				}

				// PRODUCT LISTING
				elseif( class_exists( 'WooCommerce' ) && get_row_layout() == 'product_listing' ) {
					get_template_part('template-parts/sections/product-listing');
				}

				// ARTICLES
				elseif (get_row_layout() == 'articles') {
					get_template_part('template-parts/sections/articles');
				}

				// REFERENCES
				elseif (get_row_layout() == 'references') {
					get_template_part('template-parts/sections/references');
				}

				// SLIDER
				elseif (get_row_layout() == 'hero_slider') {
						get_template_part('template-parts/sections/slider');
				} 
            
        	endwhile;
		endif;
    endif; ?>
</main>