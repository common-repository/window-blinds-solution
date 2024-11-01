<?php
/**
 * Blinds Price Table HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$get_data = bmfm_get_method();
$_product_type_id  = isset($get_data['bmfm_product_type_id']) ? absint($get_data['bmfm_product_type_id']):0;
$product_type_list = bmfm_get_product_type_list($_product_type_id);
$default_unit      = isset($get_data['bmfm_unit']) ? wc_clean(wp_unslash($get_data['bmfm_unit'])) : 'mm';
?>
<div class="wrap woocommerce">
 <div class="bmfm-blinds-price-table-wrapper">
	<div class="bmfm-blinds-price-table-heading">
		<h1 class="wp-heading-inline">Price Table Configuration</h1>
		<?php 
		$_cat_id = isset($get_data['bmfm_stored_cat_id']) ? absint($get_data['bmfm_stored_cat_id']):'';
		if ($_cat_id) :
			?>
		<a href="<?php echo esc_url('admin.php?page=bmfm_dashboard&bmfm_cat_id=' . $_cat_id); ?>" class="button-secondary bmfm-button selected bmfm-back-to-dashboard-button"><span class="dashicons dashicons-arrow-left-alt2"></span> Back to Dashboard</a>
		<?php endif; ?>
	</div>
	<div class="bmfm-blinds-markup-fee-wrapper">
		 <table class="form-table">
			 <tbody>
				 <tr>
					 <th class="bmfm-markup-fee-header">Markup %</th>
					<td><input type="number" min="0" name="bmfm_markup_fee" class="bmfm-markup-fee" value="<?php echo wp_kses_post(is_object($product_type_list) && !empty($product_type_list->get_markup()) ? $product_type_list->get_markup() : ''); ?>"></td>
				</tr>
				 <tr>
					 <th class="bmfm-default-unit">Default Unit for Order</th>
					<td class="bmfm-default-unit-table-data">
						<label>
							<input type="radio" name="bmfm_default_unit" value="mm" <?php echo wp_kses_post('mm' == $default_unit ? 'checked="checked"' : ''); ?>>mm
						</label> 	
						<label>
							<input type="radio" name="bmfm_default_unit" value="cm" <?php echo wp_kses_post('cm' == $default_unit ? 'checked="checked"' : ''); ?>>cm
						</label>
						<label>
							<input type="radio" name="bmfm_default_unit" value="inch" <?php echo wp_kses_post('inch' == $default_unit ? 'checked="checked"' : ''); ?>>inches
						</label>
					</td>
				</tr>
			 </tbody>
		</table>
	</div>
	<div class="bmfm-blinds-price-table-buttons">
		<a href=""> </a>
		<a class="bmfm-insert-col bmfm-icon-price" href="#"><span class="dashicons dashicons-table-col-after bmfm-price-icon-green"><p>Insert Column</p></span></a>
		<input type="hidden" class="bmfm-parameter-type-id" value="<?php echo wp_kses_post($_product_type_id); ?>">
	</div>
	<br>
	<div id="bmfm_blinds_price_handsontable"></div>
	<br>
	<div class="bmfm-blinds-price-table-buttons">
		  <a class="bmfm-insert-row bmfm-icon-price" href="#"><span class="dashicons dashicons-table-row-after bmfm-price-icon-green"><p>Insert Row</p></span></a>
		  <div class="bmfm-flex-end" bis_skin_checked="1">
			<a class="bmfm-delete-handsontable bmfm-icon-price" href="#"><span class="dashicons dashicons-remove bmfm-price-icon-red"><p>Delete</p></span></a>
			<a class="bmfm-delete-all-handsontable bmfm-icon-price" href="#"><span class="dashicons dashicons-trash bmfm-price-icon-red"> <p>Delete price table</p></span></a>
				<a class="bmfm-save-handsontable bmfm-button button selected" href="#">Save & Update</a>
		</div>
  </div>
  <div class="bmfm-blinds-price-table-save-changes-wrapper">
	 <span>To add more price tables</span><a href="#" class="button-primary bmfm-upgrade-premium-button">Upgrade to Premium <img class="bmfm-upgrade-premium-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) ) . '/assets/img/crown.png'; ?>" width="20" height="20"/></a>
 </div>
 </div>
</div>
