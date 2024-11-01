<?php
/**
 * Blinds Component parameters HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="bmfm-blinds-component-parameter-popup-wrapper">
	<form class="bmfm-blinds-dropdown-parameter-popup-form" method="post">
		<table class="wp-list-table widefat striped fixed bmfm-blinds-component-parameter-popup-content">
			<thead>
				<tr>
					<th><b>S.no</b></th>
					<th><b>Component Name</b></th>
					<th><b>Component Type</b></th>
					<th><b>Cost Price</b></th>
					<th><b>Markup Fee</b></th>
					<th><b>Image <span class="bmfm-upload-pixels-header-msg">(Recommended 150x150 px)</span></b></th>
					<th><b>Action</b></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$component_list_ids = bmfm_get_component_list_ids($parameter_list_id);
				$component_s_no=1;
				if (!empty($component_list_ids) && is_array($component_list_ids)) :
					foreach ($component_list_ids as $key => $component_list_id) :
						$component_list = bmfm_get_component_list($component_list_id);
						if (!is_object($component_list)) :
							continue;
						endif;
						$component_parameter_name 		= $component_list->get_name();
						$component_type 				= $component_list->get_type();
						$component_parameter_cost_price = $component_list->get_net_price();
						$component_parameter_markup     = $component_list->get_markup();
						$image_url                      = $component_list->get_image_url();
						include(BMFM_ABSPATH . '/includes/admin/views/html-component-parameter-row.php');
					endforeach;
				else :
					$key=0;
					include(BMFM_ABSPATH . '/includes/admin/views/html-component-parameter-row.php');
				endif;
					
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7">
						<a href="#" class="button button-primary bmfm-blinds-add-dropdown-popup-parameter">Add Component</a>
						<a href="#" class="button button-primary bmfm-save-drop-down-parameter-list bmfm-save-button-primary">Save Changes</a>
						<input type="hidden" class="bmfm-parameter-list-id" name="bmfm_parameter_list_id">
						<input type="hidden" class="bmfm-parameter-list-type" name="bmfm_parameter_list_type">
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
