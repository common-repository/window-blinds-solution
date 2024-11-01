<?php
/**
 * Blinds per product table HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$stored_category_ids = bmfm_get_category_ids();
$get_data = bmfm_get_method();
$_cat_id      = isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):'';
$url         = admin_url('admin.php?page=bmfm_dashboard&bmfm_cat_id=' . $_cat_id . '&bmfm_add_category=1');
$term_object = bmfm_get_term($_cat_id);

$price_table_url = '';
$view_product_url='';

if ($_cat_id) {

	if (!term_exists($_cat_id, 'product_cat')) {

		if (empty($stored_category_ids)) {
			wp_safe_redirect(admin_url('admin.php?page=bmfm_dashboard&bmfm_import=true'));
			exit;
		} 
	}
	$product_type_list_id = bmfm_get_product_type_list_id_based_on_cat_id($_cat_id);

	$product_type_list    = bmfm_get_product_type_list($product_type_list_id);

	if (is_object($product_type_list)) {

		$default_unit         = $product_type_list->get_default_unit();
		$price_table_url      = admin_url("admin.php?page=products_list_table&bmfm_add_product=1&bmfm_product_type_id=$product_type_list_id&bmfm_stored_cat_id=$_cat_id&bmfm_unit=$default_unit");
		$view_product_url     = bmfm_get_frontend_product_list_page_url($_cat_id);
	}
}

?>
	<div class="bmfm-blinds-price-table-heading">
	  <h1 class="wp-heading-inline">
		  Products Dashboard
	  </h1>
	</div>
	<?php 
	if(bmfm_is_freemium()){
	   include(BMFM_ABSPATH . '/includes/admin/views/html-freemium-days-remaining-info.php');
	}
	?>
	<div class="bmfm-blinds-products-wrapper">
		 <?php 
			foreach ($stored_category_ids as $stored_category_id) :
				$class = '';
				$_link = '#';
				if ($_cat_id == $stored_category_id) {
					$class = 'selected';
				} else {
					$_link = admin_url('admin.php?page=bmfm_dashboard&bmfm_cat_id=' . $stored_category_id);
				}
		
				$_term = get_term($stored_category_id, 'product_cat');
				if (!is_object($_term)) :
					continue;
			   endif;

				$stored_category_object=bmfm_get_term($stored_category_id);
				if (!is_object($stored_category_object)) :
					continue;
			   endif;
				$edit_product ='Product';
				if ('accessories' == $stored_category_object->get_product_category_type()) { 
					$edit_product ='Accessories';
				};
				?>
			<div class="bmfm-product-button-list">
				<a class="button bmfm-blinds-product-link bmfm-button <?php echo esc_attr($class); ?>" href="<?php echo esc_url($_link); ?>">
					<span class="bmfm-product-link-span"><?php echo wp_kses_post($_term->name); ?>
						<span class="dashicons dashicons-arrow-right"></span>
					</span>				
				</a>
				<ul class="bmfm-toggle-dropdown 
				<?php 
				if ('accessories' == $stored_category_object->get_product_category_type()) {
					echo ( 'bmfm-accessories-type' );} 
				?>
				">	
					<li><a href="<?php echo esc_url(admin_url('admin.php?page=products_list_table&bmfm_add_product=1&bmfm_cat_id=' . $_cat_id . '&bmfm_current_section=product_and_parameters_list')); ?>"> <img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )) . '/assets/img/edit-black.png'; ?>" width="15" /> Edit <?php echo wp_kses_post($edit_product); ?></a></li>
					<li><a href="<?php echo esc_url(admin_url('admin.php?page=products_list_table&bmfm_add_product=1&bmfm_cat_id=' . $_cat_id . '&bmfm_current_section=products_list')); ?>"> <img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )) . '/assets/img/edit-black.png'; ?>" width="15" /> Edit  <?php echo wp_kses_post($edit_product); ?> list</a></li>
					<li><a href="<?php echo esc_url($price_table_url); ?>"><img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )) . '/assets/img/edit-table.png'; ?>" width="15" /> Edit Price Table</a></li>
					<li><a href="<?php echo esc_url($view_product_url); ?>"><img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )) . '/assets/img/view-product.png'; ?>" width="15" /> View <?php echo wp_kses_post($edit_product); ?></a></li>
				</ul>
	</div>
				<?php
		 endforeach;
			if (count($stored_category_ids) >= 2) :
				?>
		<a href="#" class="button bmfm-upgrade-premium-button bmfm-button">
			<span class="bmfm-product-link-span">To add more products upgrade to premium <img class="bmfm-upgrade-premium-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )) . '/assets/img/crown.png'; ?>" width="20" height="20"/></span>
		</a>	 
				<?php 
		endif;

			if (count($stored_category_ids) < 2) :
				?>
			<a class="button bmfm-blinds-import-products bmfm-button" href="<?php echo esc_url(admin_url('admin.php?page=bmfm_dashboard&bmfm_import=true&bmfm_add_new_product_dashboard=1')); ?>" ><span class="bmfm-product-link-span">Add new product</span></a>
				<?php 
		  endif;
			?>
		<div class="bmfm-add-edit-category-list-wrapper">
				<a class="button bmfm-blinds-add-category bmfm-button" href="<?php echo esc_url($url); ?>"><span class="bmfm-product-link-span">Add/Edit category filter</span></a>
		</div>
	</div>
