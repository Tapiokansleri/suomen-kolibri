<?php 

// STYLE VARIABLES
$sectionBg = get_sub_field('hero_section_bg_color');
$sectionWidth = get_sub_field('hero_section_width');
$contentWidth = get_sub_field('hero_content_width');
$textBox = get_sub_field('hero_text_box');
$textBoxColor = get_sub_field('hero_text_box_color');
$contentAlign = get_sub_field('hero_content_align');
$fontColor = get_sub_field('hero_font_color');
$ctaStyle = get_sub_field('hero_cta_style');
$layer = get_sub_field('hero_layer');
$layerColor = get_sub_field('hero_layer_color');
$class = get_sub_field('hero_class');
$id = get_sub_field('hero_id');
$desktopSpacing = get_sub_field('hero_spacing_desktop');
$mobileSpacing = get_sub_field('hero_spacing_mobile');

// CAMPAIGN VARIABLES
$campaigns = get_sub_field('hero_campaigns');
$date = date('Y-m-d');
$start = get_sub_field('hero_campaign_start');
$end = get_sub_field('hero_campaign_end');

// CONTENT
$imageDesktop = get_sub_field('hero_desktop');
if (!empty($imageDesktop)) {
	$filename = $imageDesktop['filename'];
}
$imageMobile = get_sub_field('hero_mobile');
$text_1 = get_sub_field('hero_text_1');
$text_2 = get_sub_field('hero_text_2');
$text_3 = get_sub_field('hero_text_3');
$link = get_sub_field('hero_cta');
$hideCta = get_sub_field('hide_hero_cta');
$analytics = get_sub_field('ga_hero_campaign_name'); 

// CAMPAIGN CONTENT
$campaignDesktop = get_sub_field('hero_campaign_desktop');
if (!empty($campaignDesktop)) {
	$campaignFilename = $campaignDesktop['filename'];
}
$campaignMobile = get_sub_field('hero_campaign_mobile');
$campaignText_1 = get_sub_field('hero_campaign_text');
$campaignText_2 = get_sub_field('hero_campaign_text_2');
$campaignText_3 = get_sub_field('hero_campaign_text_3');
$campaignLink = get_sub_field('hero_campaign_cta');
$hideCampaignCta = get_sub_field('hide_hero_campaign_cta');
$campaignAnalytics = get_sub_field('campaign_ga_hero_campaign_name');
$promoname = get_sub_field('ga_hero_campaign_name'); ?>

<section <?php if ($id) { echo 'id="'.$id.'"'; } ?> class="hero<?php echo eeco_section_spacing($desktopSpacing, $mobileSpacing); if ($class) { echo ' '.$class; } if ($fontColor) { echo ' '.$fontColor; } if ($sectionBg) { echo ' '.$sectionBg; } ?>">
	<?php if($promoname): ?>
		<div class="promotion-data"><?php do_action( 'eeco_promotions_data', $promoname ); ?></div>
	<?php endif; ?>
	<?php if ( ( $campaigns == true && !empty($start) && !empty($end) && strtotime($date) >= strtotime($start) && strtotime($date) <= strtotime($end) ) && ( $campaignLink && $hideCampaignCta == true )):
		$link_url = $campaignLink['url'];
		$link_target = $campaignLink['target'] ? $campaignLink['target'] : '_self'; ?>
		<a class="<?php echo esc_attr($fontColor); ?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
		<?php 
	endif;
	if( $campaigns == false && $link && $hideCta == true ): 
		$link_url = $link['url'];
		$link_target = $link['target'] ? $link['target'] : '_self';	?>
		<a class="<?php echo esc_attr($fontColor); ?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
	<?php endif; ?>
	<div class="<?php if ($sectionWidth == 'container') { echo 'container'; } elseif ($sectionWidth == 'container-fluid') { echo 'container-fluid'; } else { echo 'full-width px-0'; } ?>">
		<div class="row<?php if ($sectionWidth == 'full-width') { echo ' no-gutters'; } ?>">
			<div class="col">
				<?php 
				// CAMPAIGN IMAGES
				if ( $campaigns == true && !empty($start) && !empty($end) && strtotime($date) >= strtotime($start) && strtotime($date) <= strtotime($end) ): ?>
					<img src="<?php echo $campaignDesktop['url']; ?>" alt="<?php echo $campaignDesktop['alt']; ?>" class="<?php if ($campaignMobile) { echo ' d-none d-md-block'; } ?>" />
					<?php if ($campaignMobile): ?>
						<img src="<?php echo $campaignMobile['url']; ?>" alt="<?php echo $campaignMobile['alt']; ?>" class="d-block d-md-none" />
					<?php endif; ?>
				<?php 
				// REGULAR IMAGE
				else: ?>
					<img src="<?php echo $imageDesktop['url']; ?>" alt="<?php echo $imageDesktop['alt']; ?>" class="<?php if ($imageMobile) { echo ' d-none d-md-block'; } ?>" />
					<?php if ($imageMobile): ?>
						<img src="<?php echo $imageMobile['url']; ?>" alt="<?php echo $imageMobile['alt']; ?>" class="d-block d-md-none" />
					<?php endif; ?>
				<?php endif; ?>
				<?php if ($layer) {
					echo '<div class="layer '.$layerColor.'"></div>';
				} ?>
			</div>
			<div class="wrapper<?php if ($contentWidth) {echo ' '.$contentWidth; } ?>">
				<div class="content">
					<div class="col-md-4 col-8<?php if ($contentAlign == 'center') { echo ' offset-md-4 offset-2'; } elseif ($contentAlign == 'right') { echo ' offset-md-4 offset-2'; } echo ' text-'.$contentAlign; ?>">
						<?php 
						if($textBox): ?>
							<div class="p-4 <?php echo $textBoxColor; ?>">
						<?php endif;
							// CAMPAIGN CONTENT
							if ( $campaigns == true && !empty($start) && !empty($end) && strtotime($date) >= strtotime($start) && strtotime($date) <= strtotime($end) ): ?>
								<?php
								if ($campaignText_1) { echo '<h1>'.$campaignText_1.'</h1>'; }
								if ($campaignText_2) { echo '<h3>'.$campaignText_2.'</h3>'; }
								if ($campaignText_3) { echo '<h3>'.$campaignText_3.'</h3>'; } ?>
								
								<?php if( $campaignLink && $hideCampaignCta == false ): 
									$link_url = $campaignLink['url'];
									$link_title = $campaignLink['title'];
									$link_target = $campaignLink['target'] ? $campaignLink['target'] : '_self';
									?>
									<a class="<?php if($ctaStyle) { echo 'button '.$ctaStyle; } echo ($promoname ? ' hero-cta' : ''); ?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
								<?php endif; ?>
							<?php 
							// REGULAR CONTENT
							else: ?>
								<?php
								if ($text_1) { echo '<h1>'.$text_1.'</h1>'; }
								if ($text_2) { echo '<h3>'.$text_2.'</h3>'; }
								if ($text_3) { echo '<h3>'.$text_3.'</h3>'; } ?>
								
								<?php if( $link && $hideCta == false ): 
									$link_url = $link['url'];
									$link_title = $link['title'];
									$link_target = $link['target'] ? $link['target'] : '_self';
									?>
									<a class="<?php if($ctaStyle) { echo 'button '.$ctaStyle; } echo ($promoname ? ' hero-cta' : ''); ?>" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
								<?php endif; ?>
							<?php endif; ?>
						<?php if($textBox): ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div> <!-- row -->
	</div>
	<?php if( ( $campaigns == false && $link && $hideCta == true) || (( $campaigns == true && !empty($start) && !empty($end) && strtotime($date) >= strtotime($start) && strtotime($date) <= strtotime($end) ) && ( $campaignLink && $hideCampaignCta == true )) ): ?>
		</a>
	<?php endif; ?>
</section>