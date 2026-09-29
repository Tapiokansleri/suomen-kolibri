<?php
/**
 * Template Name: Lunasta lahjakortti
 */
get_header('gift'); ?>
<main class="redeem-gift">
	<div class="full-width px-0">
		<div class="row no-gutters">
			<div class="col">
				<?php echo get_the_post_thumbnail( $post, 'full'); ?>
			</div>
			<div class="wrapper container">
				<div class="content">
					<div class="col-lg-4">
						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>
						<form class="eeco-redeem-gift" method="post">
							<div class="my-3 coupon">
								<input type="text" name="eeco_gift_code" class="input-text" id="eeco_gift_code" value="" placeholder="<?php esc_attr_e( 'Syötä koodisi', 'bgh-theme-theme' ); ?>" /> 
								<button type="submit" class="button primary-cta" value="<?php esc_attr_e( 'Apply coupon', 'bgh-theme' ); ?>"><?php esc_attr_e( 'Lunasta', 'bgh-theme-child' ); ?></button>
								<input type="hidden" name="eeco_apply_gift_code" id="eeco_apply_gift_code"/>
								<?php wp_nonce_field('eeco_apply_gift_code'); ?>
							</div>
							<?php do_action('eeco_redeem_gift'); ?>
						</form>
				</div>
			</div>
		</div>
	</div>
</main>
<?php 
get_footer(); ?>