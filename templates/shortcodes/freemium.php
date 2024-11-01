<?php
/**
 * Freemium
 *
 * This template can be overridden by copying it to yourtheme/blindmatrix-freemium/shortcodes/freemium.php.
 *
 */

if (!defined('ABSPATH')) {
	exit;
}

$category_ids = bmfm_get_category_ids();	


if (!empty($category_ids)) :
	foreach ( $category_ids as $category_id ) {
		$term_object = bmfm_get_term($category_id);
		if ($term_object) {
			$name = $term_object->get_name();
			$desc = $term_object->get_description();
			$image_url = $term_object->get_image_url();
			$slug = $term_object->get_slug();
			$category_list_page_url = bmfm_get_frontend_product_list_page_url($category_id);
			?>	
			<div class="bmfm-categories row">
				<div class="wc-block-components-product-description bmfm-categories-info col-8" style="width:75% !important">
					<h2 class="has-medium-font-size bmfm-product-category-heading"><a href="<?php echo esc_url($category_list_page_url); ?>"><?php echo wp_kses_post($name); ?></a></h2>
					<p><?php echo wp_kses_post($desc); ?></p>
					<a href="<?php echo esc_url($category_list_page_url); ?>" class="<?php echo wp_kses_post($slug); ?> button bmfm-product-button">Shop</a>
				</div>
				<div class="wc-block-components-product-image bmfm-categories-img wc-block-grid__product-image col-4" style="width:25% !important">
					<a href="<?php echo esc_url($category_list_page_url); ?>" class="<?php echo wp_kses_post($slug); ?>">
						<div class="custom-image-wrapper" >
						  <?php if (!empty($image_url)) : ?>
								<img width="200" height="200" src="<?php echo esc_url($image_url); ?>" class="woocommerce-placeholder wp-post-image" alt="">
							<?php endif; ?>							
						</div>
					</a>
				</div>
			</div>	
			<?php			
		}
	}
   endif;
?>
