<?php

/**
 * Accessories color list HTML
 */

if (!defined('ABSPATH')) {
	exit;
}

$accessories_color_ids = !empty($stored_cat_id) ? bmfm_get_accessories_list_ids($stored_cat_id) : array();
/* if (!empty($accessories_color_ids) && is_array($accessories_color_ids)) :
?>
	<span class="bmfm-product-list-table-count"><?php echo wp_kses_post(count($accessories_color_ids)); ?> items</span>
<?php
endif; */
?>
<table class="bmfm-accessories-list-table-content wp-list-table widefat fixed striped">
	<thead>
		<tr>
			<th><b>S.No</b></th>
			<th><b>Accessories <span class="required">*</span></b></th>
			<th><b>Image <span class="bmfm-upload-pixels-header-msg">(Recommended 500x500 px)</span></b></th>
			<th><b>Price <span class="required">*</span></b></th>
			<th><b>Description</b></th>
			<th><b>Action</b></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$sno = 1;    
		if (!empty($stored_cat_id) && is_object($stored_term_object)) :
			if (!empty($accessories_color_ids) && is_array($accessories_color_ids)) :
				foreach ($accessories_color_ids as $key => $accessories_color_id) :
					$accessories_color_product = bmfm_get_fabric_color_product($accessories_color_id);
					if (!is_object($accessories_color_product)) :
						continue;
					endif;
					
					$name      = $accessories_color_product->get_product_name();
					$desc      = is_object($accessories_color_product->get_product()) ? $accessories_color_product->get_product()->get_description():'';
					$price     = is_object($accessories_color_product->get_product()) ? $accessories_color_product->get_product()->get_price():'';
					$image_url = $accessories_color_product->get_image_url();
					
					include(BMFM_ABSPATH . '/includes/admin/views/html-accessories-color-list-row.php');
				endforeach;
			endif;
		else :    
			$key  = 0;
			include(BMFM_ABSPATH . '/includes/admin/views/html-accessories-color-list-row.php');
		endif; 
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
				<span class="bmfm-fabric-color-rule-error"> Accessories name,  Price are mandatory fields.</span>	
				<a href="#" class="button-primary bmfm-add-accessories-rule">Add Accessories</a> 
				<a href="#" class="button-primary bmfm-save-accessories-rule bmfm-data-saved">Save Accessories</a>
			</td>
		</tr>
	</tfoot>
</table>
