<?php
/**
 * Category Sublist Row HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<tr>	
		<td>
			<?php echo wp_kses_post(!empty($s_no) ? $s_no++:'1'); ?>
		</td>
		<td>
			<input type="text" 
				   class="bmfm-category-sub-list-name"
				   data-name="bmfm_category_sublist[name]" 
				   name="bmfm_category_sublist[name][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]"
				   value="<?php echo wp_kses_post(!empty($name) ? $name:''); ?>" 
				   placeholder="Enter Sub Category Name">
		</td> 
		<td>
			<a href="#" class="bmfm-upload-image button-secondary" style="<?php echo esc_attr(!empty($image_url) ? 'display:none':''); ?>" data-pixel="150">Upload</a>
			<span class="bmfm-upload-image-span">
				<img src="<?php echo esc_url(!empty($image_url) ? $image_url:''); ?>" class="bmfm-upload-image-src <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>">
				<span class="dashicons dashicons-dismiss bmfm-remove-image <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>"></span>
			</span>
			<input type="hidden" class="bmfm-upload-image-url bmfm-category-sub-list-upload-image-url" name="bmfm_category_sublist[image_url][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]"  data-name="bmfm_category_sublist[image_url]" value="<?php echo esc_url(!empty($image_url) ? $image_url:''); ?>">
		</td>
		<td>
			<a class="button-primary bmfm-button bmfm-delete-list-row bmfm-remove-category-sub-list" data-post_id="<?php echo wp_kses_post(!empty($category_sub_list_id) ?$category_sub_list_id:'') ; ?>">Delete<span class="dashicons dashicons-trash"></span></a>
			<input type="hidden" name="bmfm_category_list_id" value="<?php echo wp_kses_post(!empty($category_list_id) ? $category_list_id:''); ?>">
			<input type="hidden" name="bmfm_product_id" value="<?php echo wp_kses_post(!empty($product_id) ? $product_id:''); ?>">
			<input type="hidden" name="bmfm_category_sublist[post_id][]" value="<?php echo wp_kses_post(!empty($category_sub_list_id) ? $category_sub_list_id:''); ?>">
		</td>
		<td>
			<span class="dashicons dashicons-menu"></span>
		</td>
	</tr>
