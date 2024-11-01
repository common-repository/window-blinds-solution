<?php
/**
 * Accessories List Row HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<tr>
		<td>
			<?php 
			$first_col = !empty($sno) ? $sno++:'';
			echo esc_attr(!empty($first_col) ? "$first_col.":''); 
			?>
		</td>
		<td>
			<input type="text" class="bmfm-accessories-name" 
			name="bmfm_product_setup_data[accessories_list_setup][name][<?php echo esc_attr(!empty($key) ? $key:''); ?>]"
			data-name="bmfm_product_setup_data[accessories_list_setup][name]"
			value="<?php echo esc_attr(!empty($name) ? $name:''); ?>">
		</td>
		<td>
			<a href="#" class="bmfm-upload-image button-secondary" style="<?php echo esc_attr(!empty($image_url) ? 'display:none':''); ?>" data-pixel="500">Upload</a>
			<span class="bmfm-upload-image-span">
				<img src="<?php echo esc_attr(!empty($image_url) ? $image_url:''); ?>" class="bmfm-upload-image-src <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>">
				<span class="dashicons dashicons-dismiss bmfm-remove-image <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>"></span>
			</span>
			<input type="hidden"
			class="bmfm-upload-image-url bmfm-accessories-upload-image-id" 
			data-name="bmfm_product_setup_data[accessories_list_setup][image_url]" 
			name="bmfm_product_setup_data[accessories_list_setup][image_url][<?php echo esc_attr(!empty($key) ? $key:''); ?>]"
			value="<?php echo esc_attr(!empty($image_url) ? $image_url:''); ?>">
		</td>
		<td><input type="number" class="bmfm-accessories-price"
		data-name="bmfm_product_setup_data[accessories_list_setup][price]" 
		name="bmfm_product_setup_data[accessories_list_setup][price][<?php echo esc_attr(!empty($key) ? $key:''); ?>]"
		value="<?php echo esc_attr(!empty($price) ? $price:''); ?>"></td>
		<td>
			<textarea class="bmfm-accessories-desc" data-name="bmfm_product_setup_data[accessories_list_setup][desc]" 
			name="bmfm_product_setup_data[accessories_list_setup][desc][<?php echo esc_attr(!empty($key) ? $key:''); ?>]"><?php echo esc_attr(!empty($desc) ? $desc:''); ?></textarea>
		</td>
		<td>
		<a class="button-primary  bmfm-button  bmfm-remove-accessories-row" href="#" data-post_id="<?php echo wp_kses_post(!empty($accessories_color_id) ? $accessories_color_id:''); ?>">Delete<span class="dashicons dashicons-trash"></span></a>
			<input type="hidden" name="bmfm_product_setup_data[accessories_list_setup][post_id][]" value="<?php echo wp_kses_post(!empty($accessories_color_id) ? $accessories_color_id:''); ?>">
			<input type="hidden" class="bmfm-changed-data" name="bmfm_product_setup_data[accessories_list_setup][changed][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]">
		</td>
	</tr>
