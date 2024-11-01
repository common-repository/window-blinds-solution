<?php
/**
 * Component Parameter List Row HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<tr>
	<td>
		<?php echo wp_kses_post(!empty($component_s_no) ? $component_s_no++:'1'); ?>
	</td>
	<td>
		<input type="text" class="bmfm-component-parameter-name" data-name="bmfm_parameter_data[name]" name="bmfm_parameter_data[name][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" placeholder="Enter component name" value="<?php echo wp_kses_post(!empty($component_parameter_name) ? $component_parameter_name:''); ?>">
	</td>
	<td>
		<select data-name="bmfm_parameter_data[type]" name="bmfm_parameter_data[type][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" class="bmfm-component-parameter-type">
			<option value="fixed" <?php echo wp_kses_post(!empty($component_type) && 'fixed' == $component_type ? 'selected="selected"':''); ?>>Fixed</option>
			<option value="percentage" <?php echo wp_kses_post(!empty($component_type) && 'percentage' == $component_type ? 'selected="selected"':''); ?>>Percentage</option>
		</select>
	</td>
	<td>
		<input type="number" step="any" class="bmfm-component-parameter-cost-price" data-name="bmfm_parameter_data[cost_price]" name="bmfm_parameter_data[cost_price][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" value="<?php echo wp_kses_post(!empty($component_parameter_cost_price) ? $component_parameter_cost_price:''); ?>">
	</td>
	<td>
		<input type="number" step="any" class="bmfm-component-parameter-markup" data-name="bmfm_parameter_data[markup]" name="bmfm_parameter_data[markup][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" value="<?php echo wp_kses_post(!empty($component_parameter_markup) ? $component_parameter_markup:''); ?>">
	</td>
	<td>
		<a href="#" class="bmfm-upload-image button-secondary" style="<?php echo esc_attr(!empty($image_url) ? 'display:none':''); ?>" data-pixel="150">Upload</a>
		<span class="bmfm-upload-image-span">
			<img src="<?php echo esc_url(!empty($image_url) ? $image_url:'') ; ?>" class="bmfm-upload-image-src <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>">
			<span class="dashicons dashicons-dismiss bmfm-remove-image <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>"></span>
		</span>
		<input type="hidden" class="bmfm-upload-image-url bmfm-component-upload-image-url" data-name="bmfm_parameter_data[image_url]" name="bmfm_parameter_data[image_url][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]"  value="<?php echo esc_url(!empty($image_url) ? $image_url:'') ; ?>">
	</td>
	<td>
		<a class="button bmfm-button bmfm-delete-list-row bmfm-remove-component-parameter-row" data-post_id="<?php echo wp_kses_post(!empty($component_list_id) ?$component_list_id:'') ; ?>">Delete<span class="dashicons dashicons-trash"></span></a>
		<input type="hidden" name="bmfm_parameter_data[post_id][]" value="<?php echo wp_kses_post(!empty($component_list_id) ?$component_list_id:'') ; ?>">
	</td>
</tr>
