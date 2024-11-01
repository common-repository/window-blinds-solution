<?php
/**
 * Blinds dropdown parameters HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="bmfm-blinds-dropdown-parameter-popup-wrapper">
	<form class="bmfm-blinds-dropdown-parameter-popup-form" method="post">
		<table class="wp-list-table widefat striped fixed bmfm-blinds-dropdown-parameter-popup-content">
			<thead>
				<tr>
					<th><b>S.no</b></th>
					<th><b>Item View</b></th>
					<th><b>Image <span class="bmfm-upload-pixels-header-msg">(Recommended 150x150 px)</span></b></th>
					<th><b>Action</b></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$dropdown_list_ids = bmfm_get_dropdown_list_ids($parameter_list_id);
				$drop_down_s_no=1;
				if (!empty($dropdown_list_ids) && is_array($dropdown_list_ids)) :
					foreach ($dropdown_list_ids as $key => $dropdown_list_id) :
						$dropdown_list = bmfm_get_dropdown_list($dropdown_list_id);
						if (!is_object($dropdown_list)) :
							continue;
						endif;
						$drop_down_parameter_name = $dropdown_list->get_name();
						$image_url                = $dropdown_list->get_image_url();
						include(BMFM_ABSPATH . '/includes/admin/views/html-dropdown-parameter-row.php');
					endforeach;
				else :
					$key=0;
					include(BMFM_ABSPATH . '/includes/admin/views/html-dropdown-parameter-row.php');
				endif;
				?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td colspan="3">
						<a href="#" class="button button-primary bmfm-blinds-add-dropdown-popup-parameter">Add dropdown list</a>
						<a href="#" class="button button-primary bmfm-save-drop-down-parameter-list bmfm-save-button-primary">Save Changes</a>
						<input type="hidden" class="bmfm-parameter-list-id" name="bmfm_parameter_list_id">
						<input type="hidden" class="bmfm-parameter-list-type" name="bmfm_parameter_list_type">
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
