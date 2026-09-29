<?php 
/* The loop that displays posts. */
$show_sidebar = get_option('bgh_show_article_sidebar');
?>



<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found' ); ?></h1>
		<div class="entry-content ">
			<p><?php _e( 'Apologies, but the page you requested could not be found. You can return to main page.' ); ?></p>
			<!-- <?php get_search_form(); ?> -->
		</div><!-- .entry-content  -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php
/* Start the Loop.
 *
 * It is broken into three main parts: when we're displaying
 * posts that are in the gallery category, when we're displaying
 * posts in the asides category, and finally all other posts.
 *
 * Additionally, we sometimes check for whether we are on an
 * archive page, a search page, etc., allowing for small differences
 * in the loop on each template without actually duplicating
 * the rest of the loop that is shared.
 */ 


?>
<?php if($show_sidebar): ?>
	<div class="col-md-8 order-2 order-md-1">
<?php else: ?>
	<div class="col-md-12">
<?php endif; ?>
	<?php echo '<div class="mb-4" id="breadcrumbs">'. do_shortcode('[wpseo_breadcrumb]'). '</div>'; ?>
	<div class="d-md-none my-4">
		<h2><?php _e('All posts', 'bgh-theme'); ?><?php if (is_category()) { echo ' / <span>' . single_cat_title( '', false ) . '</span>';}  ?></h2>
	</div>
	<?php while ( have_posts() ) : the_post();	?>
		<?php get_template_part('template-parts/post/col-8'); ?>
		<?php comments_template( '', true ); ?>
	<?php endwhile; // End the loop. Whew. ?>
</div>
<div class="col-md-3 offset-md-1 order-1 order-md-2">
	<?php if ($show_sidebar) {
		get_sidebar(); 
	} ?>
</div>
<div class="col-12 order-3">
	<div class="pagination justify-content-center">
		<!-- pagination -->
		<?php 

		$mid_size = 2; // default to 1 number each side

		// Check if we are on the first or last page
		$current_page = get_query_var('paged');
		if ( !$current_page || $current_page == $wp_query->max_num_pages ) {
		    $mid_size = 4; // 2 numbers before/after the current page
		}
		$args = array(
		    'mid_size' => $mid_size,
			'prev_text'          => _x('<i class="fas fa-caret-left"></i>', 'Post loop: Navigate left icon', 'bgh-theme'),
			'next_text'          => _x('<i class="fas fa-caret-right"></i>', 'Post loop: Navigate right icon', 'bgh-theme'),
			'end_size'           => 0,
		); ?>
		<?php echo paginate_links( $args ); ?>
	</div>
</div>
