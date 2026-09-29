<?php 
$desktop = isset($args['search_desktop']) ? ' desktop-'.$args['search_desktop'] : '';
$mobile = isset($args['search_mobile']) ? ' mobile-'.$args['search_mobile'] : '';
$icon = get_option('eeco_search_button_icon') ? get_option('eeco_search_button_icon') : '<i class="fas fa-search"></i>'; ?>
<form role="search" method="get" id="searchform" class="searchform<?php echo esc_attr($desktop) . ' ' . esc_attr($mobile); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php if ($args['search_desktop'] == 'icon' || $args['search_mobile'] == 'icon-visible'): ?>
		<button class="search-button<?php echo esc_attr($desktop) . esc_attr($mobile); ?>" type="button"><span><?php _e('Search', 'bgh-theme'); ?></span></button>
	<?php endif; ?>
	<?php echo in_array($args['search_mobile'], array('icon-visible', 'search-open')) ? '<div class="exit-wrapper d-none"><i class="exit-icon fas fa-times"></i></div>' : ''; ?>
	<div class="searchform--input">
		<input type="text" inputmode="search" value="" name="s" id="s" placeholder="<?php _e('Search', 'bgh-theme'); ?>">
		<button type="submit" id="searchsubmit" value="<?php _e('Search', 'bgh-theme'); ?>"><?php echo $icon; ?></button>
	</div>
</form>