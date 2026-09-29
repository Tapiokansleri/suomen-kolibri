<?php $post_width = get_option( 'bgh_article_width' ); ?>
<section class="mb-5 next-posts">
    <div class="container">
        <div class="<?php echo esc_attr( $post_width ); ?>">
            <div class="row">
                <div class="col-6 next-post">
                    <div class="button article-btn">
                    <?php previous_post_link(); ?>
                    </div>              
                </div>
                <div class="col-6 next-post text-right">
                <div class="button article-btn">
                    <?php next_post_link(); ?>
                </div>
                </div>
            </div>
        </div>
    </div>
</section> 