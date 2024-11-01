<?php
/**
 * Accessories and Parameters List Row HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$selected = !empty($selected) ? $selected: '' ; 
?>

<tr>
	<td>
		<?php 
		$placeholder = '';
		$is_xy_price_table_row = false;
		if (!empty($selected) && 'numeric_x' == $selected) {
			$placeholder = 'Width';
			$is_xy_price_table_row = true;
		} else if (!empty($selected) && 'numeric_y' == $selected) {
			$placeholder = 'Drop';
			$is_xy_price_table_row = true;
		} else if (!empty($selected) && 'text' == $selected) {
			$placeholder = 'Text Value';
		} else if (!empty($selected) && 'drop_down' == $selected) {
			$placeholder = 'Drop Down Name';
		} else if (!empty($selected) && 'component' == $selected) {
			$placeholder = 'Component Name';
		}
		?>
		<input type="text" class="bmfm-parameter-name" data-name="bmfm_product_setup_data[parameter_setup][parameter_name]" name="bmfm_product_setup_data[parameter_setup][parameter_name][<?php echo wp_kses_post($key); ?>]" value="<?php echo wp_kses_post(!empty($name) ? $name :''); ?>" <?php echo esc_attr($placeholder); ?>>
		<input type="hidden" class="bmfm-hidden-parameter-name" value="<?php echo wp_kses_post(!empty($name) ? $name :''); ?>">
	</td>
	<td>
	<?php if (!empty($selected) && 'numeric_x' == $selected) : ?>
		<select class="bmfm-parameter-type bmfm-show-blinds-content" data-name="bmfm_product_setup_data[parameter_setup][parameter_type]" data-selected_type="<?php echo wp_kses_post(!empty($selected) ? $selected:''); ?>" name="bmfm_product_setup_data[parameter_setup][parameter_type][<?php echo wp_kses_post($key); ?>]">
			<option value="numeric_x" <?php echo wp_kses_post(!empty($selected) ? selected($selected, 'numeric_x', true):''); ?>>X price table</option>
		</select>
	<?php elseif (!empty($selected) && 'numeric_y' == $selected) : ?>
		<select class="bmfm-parameter-type bmfm-show-blinds-content" data-name="bmfm_product_setup_data[parameter_setup][parameter_type]" data-selected_type="<?php echo wp_kses_post(!empty($selected) ? $selected:''); ?>" name="bmfm_product_setup_data[parameter_setup][parameter_type][<?php echo wp_kses_post($key); ?>]">
			<option value="numeric_y" <?php echo wp_kses_post(!empty($selected) ? selected($selected, 'numeric_y', true):''); ?>>Y price table</option>
		</select>
	<?php else : ?>	
		<select class="bmfm-parameter-type bmfm-show-blinds-content" data-name="bmfm_product_setup_data[parameter_setup][parameter_type]" data-selected_type="<?php echo wp_kses_post(!empty($selected) ? $selected:''); ?>" name="bmfm_product_setup_data[parameter_setup][parameter_type][<?php echo wp_kses_post($key); ?>]">
			<option value="drop_down" <?php echo wp_kses_post(!empty($selected) ? selected($selected, 'drop_down', true):''); ?>>Dropdown list</option>
			<option value="text" <?php echo wp_kses_post(!empty($selected) ? selected($selected, 'text', true):''); ?>>Text</option>
			<option value="component" <?php echo wp_kses_post(!empty($selected) ? selected($selected, 'component', true):''); ?>>Component list</option>
		</select>
		<?php endif; ?>	
	</td>
	<td>
		<?php 
		if ($is_xy_price_table_row) :
			echo '<span class="bmfm-empty-xy-price-table-row">-</span>';
		endif;
		?>
		<input type="checkbox" style="<?php echo esc_attr($is_xy_price_table_row ? 'display:none':''); ?>" data-name="bmfm_product_setup_data[parameter_setup][parameter_mandatory]" class="bmfm-parameter-mandatory-chekbox" name="bmfm_product_setup_data[parameter_setup][parameter_mandatory][<?php echo wp_kses_post($key); ?>]" class="bmfm-parameter-mandatory-chekbox" <?php echo wp_kses_post(!empty($checkbox) ? checked($checkbox, 'on', true) :''); ?>>
	</td>
	<td class="<?php echo esc_attr($is_xy_price_table_row ? 'bmfm-xy-price-table-row':''); ?>">
		<?php 
		if (!in_array($selected, array('numeric_x','numeric_y','text'))) :
			?>
			<a href="#" data-parameter_list_id="<?php echo wp_kses_post(!empty($parameter_list_id) ? $parameter_list_id:''); ?>" class="button-secondary bmfm-button bmfm-edit-parameter-list selected">Edit List<img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) ); ?>/assets/img/edit.png" width="16"></a>
			<?php
		endif;
		if ($is_xy_price_table_row) :
			echo '<span class="bmfm-empty-xy-price-table-row">-</span>';
		endif;
		?>
			<a class="button-secondary bmfm-button bmfm-delete-list-row bmfm-remove-parameter-row" style="<?php echo esc_attr($is_xy_price_table_row ? 'display:none':''); ?>" data-post_id="<?php echo wp_kses_post(!empty($parameter_list_id) ?$parameter_list_id:'') ; ?>">Delete<span class="dashicons dashicons-trash"></span></a>
		<input type="hidden" name="bmfm_product_setup_data[parameter_setup][post_id][]" value="<?php echo esc_attr(!empty($parameter_list_id) ? $parameter_list_id:''); ?>">
	</td>
</tr>
