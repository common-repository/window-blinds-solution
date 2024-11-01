<?php
/**
 * Product Type List Row HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<tr>
	<td>
		<?php echo wp_kses_post(!empty($drop_down_s_no) ? $drop_down_s_no++:'1'); ?>
	</td>
	<td>
		<input type="text" class="bmfm-dropdown-parameter-name" data-name="bmfm_parameter_data[name]" name="bmfm_parameter_data[name][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" placeholder="Enter dropdown list" value="<?php echo wp_kses_post(!empty($drop_down_parameter_name) ? $drop_down_parameter_name:''); ?>">
	</td>
	<td>
		<a href="#" class="bmfm-upload-image button-secondary" style="<?php echo esc_attr(!empty($image_url) ? 'display:none':''); ?>" data-pixel="150">Upload</a>
			<span class="bmfm-upload-image-span">
				<img src="<?php echo esc_url(!empty($image_url) ? $image_url:''); ?>" class="bmfm-upload-image-src <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>">
				<span class="dashicons dashicons-dismiss bmfm-remove-image <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>"></span>
			</span>
			<input type="hidden" class="bmfm-upload-image-url bmfm-dropdown-upload-image-url" name="bmfm_parameter_data[image_url][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]"  data-name="bmfm_parameter_data[image_url]" value="<?php echo esc_url(!empty($image_url) ? $image_url:''); ?>">
	</td>
	<td>
		<a class="button bmfm-button bmfm-delete-list-row bmfm-remove-dropdown-parameter-row" data-post_id="<?php echo wp_kses_post(!empty($dropdown_list_id) ?$dropdown_list_id:''); ?>">Delete<span class="dashicons dashicons-trash"></span></a>
		<input type="hidden" name="bmfm_parameter_data[post_id][]" value="<?php echo wp_kses_post(!empty($dropdown_list_id) ?$dropdown_list_id:'') ; ?>">
	</td>
</tr>
