<?php
/**
 * Blinds category list HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$get_data = bmfm_get_method();
$product_id      = isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):0;
$term_object = bmfm_get_term($product_id);
?> 
<div class="wrap woocommerce bmfm-product-setup-wrapper">
	<h1 class='wp-heading-inline'><?php echo wp_kses_post(is_object($term_object) ? $term_object->get_name():''); ?> Category <a href="<?php echo esc_url(admin_url('admin.php?page=bmfm_dashboard&bmfm_cat_id=' . $product_id)); ?>" class="button bmfm-button">Back to dashboard</a></h1>
 <div class="woocommerce-progress-form-wrapper bmfm-category-list-wrapper bmfm-progress-form-wrapper">
	   <form class="wc-progress-form-content woocommerce-importer bmfm-category-list-form" enctype="multipart/form-data" method="post">
		<header class="bmfm-progress-form-header">
			<h3>
				<span class="bmfm-product-setup-header bmfm-blinds-category-header-span">Category Filter Setup</span>
			</h3>
		</header>
		<div class="bmfm-blinds-category-list-content-section">
		 <section>
		   <table class="wp-list-table widefat striped fixed bmfm-blinds-category-list-content">
			<thead>
				<tr>
					<th><b>S.no</b></th>
					<th><b>Category Name</b></th>
					<th><b>Category List</b></th>
					<th><b>Action</b></th>
					<th><b>Sequence</b></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$category_list_ids = bmfm_get_category_list_ids($product_id);
				$category_s_no=1;
				if (!empty($category_list_ids)) :
					foreach ($category_list_ids as $key => $category_list_id) :
						$category_list = bmfm_get_category_list($category_list_id);
						if (!is_object($category_list)) :
							continue;
						endif;
						$name     = $category_list->get_name();
						include (BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-list-row.php');
					endforeach;
				else :
					$key = 0;
					include (BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-list-row.php');
				endif;
				?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<a href="#" class="button-primary bmfm-blinds-add-category-list">Add Category Filter</a>
						<input type="hidden" name="bmfm_blinds_category_id" value="<?php echo absint($product_id); ?>">
					</td>
				</tr>
			</tfoot>
		  </table>
		</section>
	  </div>
	  <div class="bmfm-setup-section bmfm-save-button-wrapper bmfm-show-section">
				<section class="bmfm-save-button-section">
					<a href="#" class="button-primary bmfm-save-category-list-button bmfm-save-button-primary">Save Changes</a>
				 </section>
		</div>
	</form>
  </div>
</div>
