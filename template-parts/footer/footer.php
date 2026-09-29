<?php 
$logo = get_option('bgh_theme_logo_footer');
$menu_logo = ( get_option('bgh_theme_logo_mobile') ? wp_get_attachment_url(get_option('bgh_theme_logo_mobile')) : ( get_option('bgh_theme_logo') ? wp_get_attachment_url(get_option('bgh_theme_logo')) : get_template_directory_uri() . '/assets/images/logo.png' ) );
$menu_logo_alt = ( get_option('bgh_theme_logo_mobile') ? get_post_meta(get_option('bgh_theme_logo_mobile'), '_wp_attachment_image_alt', true) : ( get_option('bgh_theme_logo') ? get_post_meta(get_option('bgh_theme_logo'), '_wp_attachment_image_alt', true) : 'Logo' ) ); ?>

<footer id="footer-top" class="py-5">
	<div class="<?php echo $logo ? 'container-fluid' : 'container' ?>">
		<div class="row">
			<?php if($logo):
				$alt = get_post_meta($logo, '_wp_attachment_image_alt', true); ?>
				<div class="col-md">
					<img id="logo" src="<?php echo wp_get_attachment_url($logo); ?>" alt="<?php echo $alt; ?>">
				</div>
			<?php endif; ?>
			<div class="col-md">
				<?php
				if(is_active_sidebar('footer-1')){
					dynamic_sidebar('footer-1');
				}
				?>
			</div>
			<div class="col-md">
				<?php
				if(is_active_sidebar('footer-2')){
					dynamic_sidebar('footer-2');
				}
				?>
			</div>
			<div class="col-md">
				<?php
				if(is_active_sidebar('footer-3')){
					dynamic_sidebar('footer-3');
				}
				?>
			</div>
			<div class="col-md">
				<?php
				if(is_active_sidebar('footer-4')){
					dynamic_sidebar('footer-4');
				}
				?>
			</div>
		</div>
	</div>
</footer>
<footer id="footer-bottom" class="">
	<div class="container py-4">
		<div class="row">
			<div class="col-lg-12 col-md-12 footer-content">
				<?php
				if(is_active_sidebar('absolute-footer')){
					dynamic_sidebar('absolute-footer');
				}
				?>
			</div>
		</div>
	</div>
</footer>


<?php 
$minicart = get_option('eeco_minicart_design');
if ( class_exists( 'WooCommerce' ) && $minicart == 'drawer'):
	// Mini cart is not needed on cart page
	if (!is_cart()) {
		$products_in_cart = !WC()->cart->is_empty() ? ' <span class="items-in-cart">'.WC()->cart->get_cart_contents_count().'</span>' : ''; ?>
		<div class="modal fade woocommerce" id="menuCheckout" tabindex="-1" role="dialog" aria-labelledby="menuCheckoutTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content px-3">
					<div class="modal-header position-relative">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"><i class="far fa-times"></i></span>
						</button>
						<?php if ( ! WC()->cart->is_empty() ) {
							bgh_woocommerce_widget_shopping_cart_button_view_cart();
						} ?>
						<h5 class="modal-title" id="menuCheckoutTitle"><strong><?php _e('Cart', 'bgh-theme'); ?><?php echo $products_in_cart; ?></strong></h5>
						<?php if ( ! WC()->cart->is_empty() ) : ?>
							<p class="mb-0 woocommerce-mini-cart__total total"><?php echo WC()->cart->get_cart_subtotal(); ?></p>
						<?php endif; ?>
					</div>
					<?php if ($notification = get_option('bgh_mini_cart_notification')): ?>
						<div class="modal-notification px-3 mt-3">
							<p><strong><?php echo $notification; ?></strong></p>
						</div>
					<?php endif; ?>
					<div class="menu-checkout-content px-3">
						<?php woocommerce_mini_cart(); ?>
					</div>
				</div>
			</div>
		</div>
	<?php } 
endif;
if ( class_exists('WooCommerce') && (get_option('eeco_minicart_notifications')['desktop'] == 'popup') || get_option('eeco_minicart_notifications')['mobile'] == 'popup') {
	echo '<div class="add-to-cart-notification-wrapper"></div>';
}
// MOBILE MENU WIDTH
$menu = get_option('eeco_menu_template');
$menu_width = get_option('eeco_menu_mobile_menu_width'); ?>
<div class="modal fade mobile-menu" id="modalMenu" tabindex="-1" role="dialog" aria-labelledby="modalMenuTitle" aria-hidden="true">
	<div class="modal-dialog <?php echo 'w-'.esc_attr($menu_width); ?>" role="document">
		<div id="modalMenuTitle" class="modal-content">
			<div class="modal-header">
				<?php 
				$my_account = get_option('eeco_menu_my_account');
				if (in_array($my_account, array('mobile-menu', 'both'))): ?>
					<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="button primary-cta">
						<i class="fas fa-user"></i>
						<span><?php _e('My Account', 'bgh-theme'); ?></span>
					</a>
				<?php endif; ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i class="far fa-times"></i>
				</button>
				<a class="mobile-menu--logo" href="<?php if ( function_exists('pll_home_url') ) { echo pll_home_url(); } else { echo get_option('home'); } ?>">
					<img class="logo" src="<?php echo $menu_logo; ?>" alt="<?php echo $menu_logo_alt; ?>">
				</a>
				<?php if (get_option('eeco_menu_search_icon_mobile') === 'search-burger') {
					get_search_form();
				} ?>
		    </div>
			<div class="modal-menu-content position-relative">
				<span class="hidden invisible d-none back"><?php _e('Back', 'bgh-theme'); ?></span>
				<?php if (class_exists( 'WooCommerce' ) && is_account_page() && is_user_logged_in()) {
					echo '<div class="menu-my-account">';
					echo '<ul class="nav navbar-nav yamm">';
					foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
						<li class="my-account-item">
							<a class="<?php echo $endpoint; ?>" href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
						</li>
					<?php endforeach;
					echo '</ul></div>';
				} 
				
				if ($menu === 'menu-3') {
					eeco_sidebar_nav_mobile();
				} else {
					if(function_exists('bootstrap_mobile_nav')){
						bootstrap_mobile_nav();
					}
				} 
                if ( has_nav_menu( 'main-menu' ) ) {
                    if (function_exists('main_mobile_nav')) {
                        main_mobile_nav();
                    }
				}
                ?>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>