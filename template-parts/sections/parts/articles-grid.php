<?php
$display = get_sub_field('display_articles');
$articlesPerRow = get_sub_field('articles_per_row_desktop');
$articlesPerRowMobile = get_sub_field('articles_per_row_mobile'); 
$border = get_sub_field('articles_border');
$contentAlignment = get_sub_field('articles_article_text_align');
$articleCats = get_sub_field('articles_categories');
$name = get_sub_field('articles_article_name');
$articleDate = get_sub_field('articles_article_date');
$excerpt = get_sub_field('articles_article_excerpt');
$ctaStyle = get_sub_field('articles_cta_style');
$ctaText = get_sub_field('articles_cta_text');
$articleHighlights = get_sub_field('articles_highlights');
$color = $args['color']; ?>

<?php if ($display == 'new'): ?>
    <?php
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 12,
        'order'          => 'DESC',
        'orderby'        => 'date',
    );
    $articles = new WP_Query( $args );
    if ( $articles->have_posts() ) : ?>
    <div class="row">
        <?php while ( $articles->have_posts() ) : $articles->the_post(); 
			$categories = array();
			foreach((get_the_category()) as $category) {
				$categories[] = $category->cat_name; 
			}?>
            <div class="<?php echo 'mb-5 ' . esc_attr($articlesPerRow) . ' ' . esc_attr($articlesPerRowMobile); ?>">
                <div id="post-<?php the_ID(); ?>" class="post-holder <?php if ($border == 'border') { echo esc_attr($border);  } echo ' text-'. esc_attr($contentAlignment); ?>">
                    <a <?php if($category->cat_name == 'kuvasto') echo 'target="_blank"'; ?> href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
                        <?php if ( has_post_thumbnail() ) {
                            the_post_thumbnail('articles', array('class' => 'mb-3'));
                        } ?>
                    </a>
                    <div class="entry-content <?php echo $color; ?>">
                        <?php 
						if ($articleCats == true) {
                            echo '<p>';
                            echo implode(', ', $categories).'</p>';
                        }
						if ($name == true && $category->cat_name == 'kuvasto') {
                            echo '<h3><a target="_blank" href="'.get_the_permalink().'" title="'.esc_attr__( 'Permalink to ' ), the_title_attribute().'" rel="bookmark">'.get_the_title().'</a></h3>';
                        } elseif( $name == true ) {
							echo '<h3><a href="'.get_the_permalink().'" title="'.esc_attr__( 'Permalink to ' ), the_title_attribute().'" rel="bookmark">'.get_the_title().'</a></h3>';
						}
                        if ($articleDate == true) {
                            echo '<p class="date">'.get_the_date().'</p>';
                        }
                        if ($excerpt == true) {
                            the_excerpt();
                        } ?>
                    </div>
                    <a <?php if($category->cat_name == 'kuvasto') echo 'target="_blank"'; ?> href="<?php the_permalink(); ?>" class="<?php if($ctaStyle == 'primary-cta' || $ctaStyle == 'secondary-cta') { echo 'button '. esc_attr($ctaStyle); } else { echo 'text-link'; } ?>" title="<?php printf( esc_attr__( 'Permalink to ' ), the_title_attribute() ); ?>" rel="bookmark"><?php if (empty($ctaText)) { _e('Read more', 'bgh-theme'); } else { echo $ctaText; } ?></a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php endif; wp_reset_postdata(); ?>
<?php elseif ($display == 'highlight'): ?>
    <div class="row">
        <?php foreach( $articleHighlights as $post): // variable must be called $post (IMPORTANT)
            setup_postdata($post); 
			$categories = array();
			foreach((get_the_category()) as $category) {
				$categories[] = $category->cat_name; 
			}?>
            <div class="<?php echo 'mb-5 ' . esc_attr($articlesPerRow) . ' ' . esc_attr($articlesPerRowMobile); ?>">
                <div id="post-<?php the_ID(); ?>" class="post-holder <?php if ($border == 'border') { echo esc_attr($border);  } echo ' text-'. esc_attr($contentAlignment); ?>">
                    <a <?php if($category->cat_name == 'kuvasto') echo 'target="_blank"'; ?> href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
                        <?php if ( has_post_thumbnail() ) {
                            the_post_thumbnail('articles', array('class' => 'mb-3'));
                        } ?>
                    </a>
                    <div class="entry-content">
                        <?php 
						if ($articleCats == true) {
                            echo '<p>';
                            echo implode(', ', $categories).'</p>';
                        }
                        if ($name == true && $category->cat_name == 'kuvasto') {
                            echo '<h3><a target="_blank" href="'.get_the_permalink().'" title="'.esc_attr__( 'Permalink to ' ), the_title_attribute().'" rel="bookmark">'.get_the_title().'</a></h3>';
                        } elseif( $name == true ) {
							echo '<h3><a href="'.get_the_permalink().'" title="'.esc_attr__( 'Permalink to ' ), the_title_attribute().'" rel="bookmark">'.get_the_title().'</a></h3>';
						}
                        if ($articleDate == true) {
                            echo '<p class="date">'.get_the_date().'</p>';
                        }
                        if ($excerpt == true) {
                            the_excerpt();
                        } ?>
                    </div>
                    <a <?php if($category->cat_name == 'kuvasto') echo 'target="_blank"'; ?> href="<?php the_permalink(); ?>" class="<?php if($ctaStyle == 'primary-cta' || $ctaStyle == 'secondary-cta') { echo 'button '. esc_attr($ctaStyle); } else { echo 'text-link'; } ?>" title="<?php printf( esc_attr__( 'Permalink to ' ), the_title_attribute() ); ?>" rel="bookmark"><?php if (empty($ctaText)) { _e('Read more', 'bgh-theme'); } else { echo $ctaText; } ?></a>
                </div>
            </div>
        <?php endforeach; wp_reset_postdata(); ?>
    </div>
<?php endif; ?>