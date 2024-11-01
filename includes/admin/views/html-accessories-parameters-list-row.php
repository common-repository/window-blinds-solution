<?php
/**
 * Accessories Parameters List Row HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<tr>
	<td>
		<?php 
		$placeholder = '';
		if (!empty($selected) && 'text' == $selected) {
			$placeholder = 'Text Value';
		} else if (!empty($selected) && 'drop_down' == $selected) {
			$placeholder = 'Drop Down Name';
		} else if (!empty($selected) && 'component' == $selected) {
			$placeholder = 'Component Name';
		}
		?>
		<input type="text" class="bmfm-accessories-name" data-name="bmfm_product_setup_data[parameter_setup][accessories_name]" name="bmfm_product_setup_data[parameter_setup][accessories_name][<?php echo wp_kses_post($key); ?>]" value="<?php echo wp_kses_post(!empty($name) ? ( $name ) :''); ?>" <?php echo esc_attr($placeholder); ?>>
		<input type="hidden" class="bmfm-hidden-parameter-name" value="<?php echo !empty($name) ? wp_kses_post($name) :''; ?>">
	</td>
	<td>
		<select class="bmfm-accesories-parameter-type" data-name="bmfm_product_setup_data[parameter_setup][accessories_type]" data-selected_type="<?php echo wp_kses_post(!empty($selected) ? $selected:''); ?>"  name="bmfm_product_setup_data[parameter_setup][accessories_type][<?php echo wp_kses_post($key); ?>]">
			<option value="drop_down" <?php echo wp_kses_post(!empty($selected) ? selected($selected, 'drop_down', true):''); ?>>Dropdown list</option>
			<option value="text" <?php echo wp_kses_post(!empty($selected) ? selected($selected, 'text', true):''); ?>>Text</option>
			<option value="component" <?php echo wp_kses_post(!empty($selected) ? selected($selected, 'component', true):''); ?>>Component list</option>
		</select>
	</td>
	<td>
		<input type="checkbox" data-name="bmfm_product_setup_data[parameter_setup][accessories_mandatory]" class="bmfm-accesories-parameter-mandatory-chekbox" name="bmfm_product_setup_data[parameter_setup][accessories_mandatory][<?php echo wp_kses_post($key); ?>]" class="bmfm-parameter-mandatory-chekbox" <?php echo wp_kses_post(!empty($checkbox) ? checked($checkbox, 'on', true) :''); ?>>
	</td>
	<td>
		<a href="#" data-parameter_list_id="<?php echo wp_kses_post(!empty($parameter_list_id) ? $parameter_list_id:'' ); ?>" class="button-secondary bmfm-button bmfm-edit-accessories-parameter-list selected">Edit List<img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) ); ?>/assets/img/edit.png" width="16"></a>
		<a class="button-secondary bmfm-button bmfm-delete-list-row bmfm-remove-accessories-parameter-row" data-post_id="<?php echo wp_kses_post(!empty($parameter_list_id) ?$parameter_list_id:'') ; ?>">Delete<span class="dashicons dashicons-trash"></span></a>
		<input type="hidden" name="bmfm_product_setup_data[parameter_setup][accessories_post_id][]" value="<?php echo esc_attr(!empty($parameter_list_id) ? $parameter_list_id:''); ?>">
	</td>
</tr>
