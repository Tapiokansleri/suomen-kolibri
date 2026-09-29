<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.5.0
 */

use Automattic\WooCommerce\Enums\ProductType;

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$layout            = get_option('eeco_product_page_image_layout');
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$gallery           = $product->get_gallery_image_ids();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'layout--'.$layout . ($gallery ? '---with-gallery' : ''),
		'images',
	)
);
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<?php if ($layout != 'parallel' && $layout != 'same-size') {
		echo '<div class="woocommerce-product-gallery__wrapper">';
	} ?>
		<?php
		if ( $post_thumbnail_id ) {
			$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
		} else {
			// Check for visible children with prices to determine if variation image swapping is possible.
			// Using get_visible_children() + get_price() is more efficient than get_available_variations()
			// as it uses cached IDs and synced price data rather than loading all variation objects.
			$wrapper_classname = $product->is_type( ProductType::VARIABLE ) && ! empty( $product->get_visible_children() ) && '' !== $product->get_price() ?
				'woocommerce-product-gallery__image woocommerce-product-gallery__image--placeholder' :
				'woocommerce-product-gallery__image--placeholder';
			$html              = sprintf( '<div class="%s">', esc_attr( $wrapper_classname ) );
			$html             .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html_x( 'Awaiting product image', 'Product image placeholder hover text', 'bgh-theme' ) );
			$html             .= '</div>';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

		do_action( 'woocommerce_product_thumbnails' );
		?>
	<?php if ($layout != 'parallel' && $layout != 'same-size') {
		echo '</div>';
	} ?>
</div>
<?php if ($gallery && ($layout == 'parallel' || $layout == 'same-size')) {
	$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
	$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
	$thumbnail_src     = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
	echo '<ol class="image-indicator">';
	echo '<li data-gallery_image="'.esc_url( $thumbnail_src[0] ).'" class="active"><span class="circle"></span></li>';
	foreach ($gallery as $image) {
		$thumbnail_src = wp_get_attachment_image_src( $image, $thumbnail_size );
		echo '<li data-gallery_image="'.esc_url( $thumbnail_src[0] ).'"><span class="circle"></span></li>';
	}
	echo '</ol>';
} ?>
