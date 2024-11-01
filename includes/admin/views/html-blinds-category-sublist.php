<?php
/**
 * Blinds category sublist HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?> 
<div class="bmfm-category-sublist-wrapper">
	   <form class="bmfm-category-sublist-form" enctype="multipart/form-data" method="post">
		   <table class="wp-list-table widefat striped fixed bmfm-blinds-category-sublist-content">
			<thead>
				<tr>
					<th><b>S.no</b></th>
					<th><b>Category Name</b></th>
					<th><b>Image <span class="bmfm-upload-pixels-header-msg">(Recommended 25x25 px)</span></b></th>
					<th><b>Action</b></th>
					<th><b>Sequence</b></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$category_sub_list_ids     = bmfm_get_category_sub_list_ids($category_list_id);
				 $s_no=1;
				if (!empty($category_sub_list_ids)) :
					foreach ($category_sub_list_ids as $key => $category_sub_list_id) :
						$category_sub_list = bmfm_get_category_sublist($category_sub_list_id);
						if (!is_object($category_sub_list)) :
							continue;
						endif;
						$name      = $category_sub_list->get_name();
						$image_url = $category_sub_list->get_image_url();
						include (BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-sublist-row.php');
					endforeach;
				else :
					$key=0;
					include (BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-sublist-row.php');
				endif;
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5">
						<a href="#" class="button-primary bmfm-blinds-add-category-sublist">Add Category List</a>
						<a href="#" class="button-primary bmfm-save-category-sublist bmfm-save-button-primary">Save changes</a>
						<input type="hidden" name="bmfm_blinds_category_id" value="<?php echo absint($product_id); ?>">
						<input type="hidden" name="bmfm_blinds_category_list_id" value="<?php echo absint($category_list_id); ?>">
					</td>
				</tr>
			</tfoot>
		</table>
  </form>
</div>
