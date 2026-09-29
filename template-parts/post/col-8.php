<div class="post-holder">
	<?php if ( has_post_thumbnail() ): ?>
		<div class="image-wrapper">
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
				<?php the_post_thumbnail('archive-thumb'); ?>
			</a>
		</div>
	<?php endif; ?>
	<div class="content-wrapper">
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
		<p><span class="date"><?php echo _x('Published:', 'Blog post', 'bgh-theme') .' '. get_the_date(); ?></span><br>
		
		</p>
		<?php the_excerpt(); ?>
	</div>
</div>