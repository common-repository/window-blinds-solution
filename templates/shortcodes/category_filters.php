<?php 
/**
 * Category Filters
 *
 * This template can be overridden by copying it to yourtheme/blindmatrix-freemium/shortcodes/category_filters.php.
 *
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<aside class="widget woocommerce bmfm_widget_product_categories">
	<ul class="bmfm-product-categories">
	<?php
	foreach ($category_list_ids  as $category_list_id) :
		$category_list = bmfm_get_category_list($category_list_id);
		if (!is_object($category_list)) {
			continue;
		}
		
		$name = $category_list->get_name();
		?>
			
		<li class="bmfm-cat-item_cat bmfm-cat-parent active" >
			<a class="bmfm-cat-name"><?php echo wp_kses_post($name); ?></a>
			<a class="bmfm-toggle"><span class="dashicons dashicons-arrow-down-alt2"></span></a>
		<?php 
		// Category Sublist
		$category_sub_list_ids = bmfm_get_category_sub_list_ids($category_list_id);
		if ( !empty($category_sub_list_ids) && is_array($category_sub_list_ids) ) : 
			?>
				<ul class="bmfm-cat-child">
				<?php
				foreach ($category_sub_list_ids as $category_sub_list_id) :
					$category_sub_list = bmfm_get_category_sublist($category_sub_list_id);
					if (!is_object($category_sub_list)) {
						continue;
					}
					
					$list_name = $category_sub_list->get_name();
					$img_url   = $category_sub_list->get_image_url();
					$selected_child_category_ids = !empty($selected_categories_data[$category_list_id]) ? $selected_categories_data[$category_list_id]:array();
					$checked         = '';
					$extra_class     = '';
					if (in_array($category_sub_list_id, $selected_child_category_ids)) {
						$checked     ='checked';
						$extra_class ='active';
					}
					?>
						<li class="bmfm-cat-item bmfm-menu-item-type-post_type <?php echo wp_kses_post($extra_class); ?>">
							<input type="checkbox" style="display:none;" id="bmfm_check_<?php echo wp_kses_post($category_sub_list_id); ?>" data-cat-id="<?php echo wp_kses_post($category_list_id); ?>" data-id="<?php echo wp_kses_post($category_sub_list_id); ?>" class="bmfm_check_category" name="bmfm_check_category[<?php echo wp_kses_post($category_list_id); ?>][<?php echo wp_kses_post($category_sub_list_id); ?>]" <?php echo wp_kses_post($checked); ?> hidden />
								<a data-cat-name="<?php echo wp_kses_post($name); ?>" data-cat-id="<?php echo wp_kses_post($category_list_id); ?>" data-id="<?php echo wp_kses_post($category_sub_list_id); ?>" data-name="<?php echo wp_kses_post($list_name); ?>" class="bmfm-cat-filtername category_all" >
								<?php if ($img_url) { ?>
										<img width="25" src="<?php echo esc_url($img_url); ?>" alt="<?php echo wp_kses_post($list_name); ?>" title="<?php echo wp_kses_post($list_name); ?>">
									<?php } ?>
							<?php echo wp_kses_post($list_name); ?>
								</a>
						</li> 
					
				<?php endforeach; ?>
				</ul>	
			<?php endif; ?>
		</li>
	 <?php endforeach; ?>
	</ul>
</aside>
