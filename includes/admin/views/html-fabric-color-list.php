<?php
/**
 * Fabric color list HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fabric_color_ids = !empty($stored_cat_id) ? bmfm_get_fabric_color_ids($stored_cat_id):array();
?>
<table class="bmfm-product-list-table-content wp-list-table widefat striped">
	<thead>
		<tr>
			<th><b>S.No</b></th>
			<th><b>Fabric color <span class="required">*</span></b></th>
			<th><b>Fabric Image <span class="required">*</span><span class="bmfm-upload-pixels-header-msg">(Recommended 500x500 px)</span></b></th>
			<th><b>Frame</b></th>
			<th><b><input type="checkbox" class="bmfm-hide-frames-checkbox bmfm-hide-farames-checkbox-size">Hide Frame</b></th>
			<th><b>More Images <span class="bmfm-upload-pixels-header-msg">(Recommended 500x500 px)</span></b></th>
			<th><b>Description</b></th>
			<th><b>Action</b></th>
			<th><b></b></th>
		</tr>
	</thead>
	
	<tbody>
		<?php 
		$sno = 1;
		if (!empty($stored_cat_id) && is_object($stored_term_object)) :
			$fabric_color_ids = bmfm_get_fabric_color_ids($stored_cat_id);
			if (!empty($fabric_color_ids) && is_array($fabric_color_ids)) :
				foreach ($fabric_color_ids as $key => $fabric_color_id) :
					$fabric_color_product = bmfm_get_fabric_color_product($fabric_color_id);
					if (!is_object($fabric_color_product)) :
						continue;
					endif;
					
					$image_url               = $fabric_color_product->get_image_url();
					$fabric_color_name       = $fabric_color_product->get_product_name();
					$fabric_color_desc       = $fabric_color_product->get_product()->get_description();
					$frame_url               = $fabric_color_product->get_frame_url();
					$material_images_url     = $fabric_color_product->get_material_images_url();
					$hide_frame              = $fabric_color_product->get_show_or_hide_frame();
					$uploaded_frame_pdt_name = $fabric_color_product->get_uploaded_frame_pdt_name();
					include(BMFM_ABSPATH . '/includes/admin/views/html-fabric-color-list-row.php');
				endforeach;
			endif;
		else :    
			$image_url            = '';
			$fabric_color_name    = '';
			$fabric_color_desc    = '';
			$frame_url            = '';
			$material_images_url  = '';
			$key                  = 0;
			include(BMFM_ABSPATH . '/includes/admin/views/html-fabric-color-list-row.php');
		endif; 
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="7">
				<span class="bmfm-fabric-color-rule-error">Fabric color, Image columns are mandatory fields.</span>
				<a href="#" class="button-primary bmfm-add-fabric-color-rule">Add Fabric</a>
				<a href="#" class="button-secondary bmfm-button bmfm-save-fabric-color-rule">Save Fabric</a>
			</td>
		</tr>
	</tfoot>
</table>
