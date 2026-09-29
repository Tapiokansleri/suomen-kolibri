<?php 
// VARIABLES FROM THEME SETTINGS
$post_width = get_option('bgh_article_width'); 
$show_author = get_option('bgh_show_article_author'); ?>
<section class="post-content">   
    <div class="container">  
        <div class="row">
            <div class="<?php echo $post_width; ?> p-md-5">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="entry-data text-center mb-5">
                            <img src="<?php the_post_thumbnail_url(); ?>" class="img-fluid mb-4">
                            <h1 class="article-title"><?php the_title(); ?></h1>
                            <p><span class="date"><?php echo get_the_date(); ?></span><?php if( $show_author ): ?>, <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); endif; ?></a></p>
                            <div class="social"></div>
                        </div>
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>