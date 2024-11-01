<?php
/**
 * Product Type List Row HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<tr>
	<td>1</td>
	<td>
		<input type="text" class="bmfm-product-type-list-name" name="bmfm_product_setup_data[product_type_list][name]" value="<?php echo wp_kses_post($product_type_list_name); ?>" disabled>
	</td>
	<td>
	<a href="<?php echo esc_url($url); ?>" class="bmfm-button selected bmfm-edit-price_setup ">Edit Price Table <img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/edit.png" width="16"></a>
	</td>
</tr>
