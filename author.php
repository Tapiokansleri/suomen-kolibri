<?php
/**
 * The template for displaying Author archive pages.
 *
 * Displays author info (name, avatar, bio, website) even when
 * the author has no posts, followed by their post list if any.
 */

get_header();

$author = get_queried_object();
$author_id = $author->ID;
?>

<main class="container pt-5 pb-5">
	<div class="row">
		<div class="col-md-8 order-2 order-md-1">

			<div class="mb-4" id="breadcrumbs"><?php echo do_shortcode('[wpseo_breadcrumb]'); ?></div>

			<div class="author-header mb-5">
				<div class="d-flex align-items-start">
					<?php $avatar = get_avatar( $author_id, 120 );
					if ( $avatar ) : ?>
						<div class="author-avatar mr-4 flex-shrink-0">
							<?php echo $avatar; ?>
						</div>
					<?php endif; ?>
					<div class="author-info">
						<h1 class="author-title mb-2"><?php echo esc_html( $author->display_name ); ?></h1>
						<?php $bio = get_the_author_meta( 'description', $author_id );
						if ( $bio ) : ?>
							<div class="author-bio mb-3">
								<?php echo wpautop( esc_html( $bio ) ); ?>
							</div>
						<?php endif; ?>
						<?php $website = get_the_author_meta( 'user_url', $author_id );
						if ( $website ) : ?>
							<p class="author-website">
								<a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo esc_html( $website ); ?>
								</a>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php if ( have_posts() ) : ?>
				<h2 class="mb-4"><?php printf( esc_html__( 'Articles by %s', 'bgh-theme-child' ), esc_html( $author->display_name ) ); ?></h2>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/post/col-8' ); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<p><?php printf( esc_html__( '%s has not published any articles yet.', 'bgh-theme-child' ), esc_html( $author->display_name ) ); ?></p>
			<?php endif; ?>

		</div>
		<div class="col-md-3 offset-md-1 order-1 order-md-2">
			<?php $show_sidebar = get_option( 'bgh_show_article_sidebar' );
			if ( $show_sidebar ) {
				get_sidebar();
			} ?>
		</div>
		<div class="col-12 order-3">
			<div class="pagination justify-content-center">
				<?php
				$mid_size = 2;
				$current_page = get_query_var( 'paged' );
				if ( ! $current_page || $current_page == $wp_query->max_num_pages ) {
					$mid_size = 4;
				}
				echo paginate_links( array(
					'mid_size'  => $mid_size,
					'prev_text' => '<i class="fas fa-caret-left"></i>',
					'next_text' => '<i class="fas fa-caret-right"></i>',
					'end_size'  => 0,
				) );
				?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
