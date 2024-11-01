<?php
/**
 * Accessories Parameters List HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$get_data = bmfm_get_method();
?>

<table class="bmfm-parameter-setup-table-content bmfm-accessories-parameter-table-content wp-list-table widefat fixed striped">
	<thead>
		<tr>
			<th><b>Parameter Name</b></th>
			<th><b>Parameter Type</b></th>
			<th><b>Mandatory</b></th>
			<th><b>Action</b></th>
		</tr>
		</thead>
			<tbody>
			  <?php 
				if (empty($parameter_list_ids)) : 
					for ($key=0;$key<1;$key++) {
						$parameter_list_id = '';
						$name     = '';
						$selected = '';
						$checkbox = ''; 
						include(BMFM_ABSPATH . '/includes/admin/views/html-accessories-parameters-list-row.php');
					}
				else :
					if (is_array($parameter_list_ids) && !empty($parameter_list_ids)) :
						foreach ($parameter_list_ids as $key => $parameter_list_id) :
							$parameter_list = bmfm_get_parameter_list($parameter_list_id);
							if (!is_object($parameter_list)) :
								continue;
							endif;
						
							$name     = $parameter_list->get_parameter_name();
							$selected = $parameter_list->get_parameter_type();
							$checkbox = $parameter_list->get_mandatory_checked(); 
							include(BMFM_ABSPATH . '/includes/admin/views/html-accessories-parameters-list-row.php');
						endforeach;
				endif;
			endif; 
				?>
		</tbody>
	<tfoot>
		<tr>
			<td colspan="4">
				<a href="#" class="button-primary bmfm-add-accessories-parameter-rule">Add Parameter</a>
				<?php 
				$stored_cat_id = isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']) :''; 
				?>
				<a href="#" class="button-secondary bmfm-button bmfm-save-accessories-parameter-list-rule <?php echo esc_attr(!empty($stored_cat_id) ? 'bmfm-data-saved':''); ?>">Save Parameter</a>
				<input type="hidden" class="bmfm-save-parameter-active-section">
			</td>
		</tr>
	</tfoot>
</table>