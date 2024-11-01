<?php
/**
 * Product Setup HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$get_data = bmfm_get_method();
if ( !isset($get_data['bmfm_cat_id']) && count(bmfm_get_category_ids()) >= 2) :
	include(BMFM_ABSPATH . '/includes/admin/views/html-upgrade-premium-info.php');
	return;
endif;
$product_category_type = '' != $stored_cat_id && is_object($stored_term_object) ? $stored_term_object->get_product_category_type():'blinds';

$view_product_url     = bmfm_get_frontend_product_list_page_url($stored_cat_id);
$saved_product_category_class = '';
if ('' != $stored_cat_id) {
	$saved_product_category_class = 'bmfm-saved-product-blinds';
	if ('accessories' == $product_category_type) {
		$saved_product_category_class = 'bmfm-saved-product-accessories';
	}
}
$current_section_hide='';
if (isset($get_data['bmfm_current_section'])) {
	$current_section_hide='bmfm-hide';
}
?>

<?php if ($stored_cat_id && isset($get_data['bmfm_current_section'])) : ?>
	<div class="woocommerce-progress-form-wrapper bmfm-progress-form-wrapper bmfm_backto_dashboard">
		<a href="<?php echo esc_url(admin_url('admin.php?page=bmfm_dashboard&bmfm_cat_id=' . $stored_cat_id)); ?>"><span class="dashicons dashicons-arrow-left-alt2"></span> <?php echo wp_kses_post($stored_term_object->get_name()); ?></a>
	</div>
<?php endif; ?>
<div class="wrap woocommerce bmfm-product-setup-wrapper">
	  <div class="woocommerce-progress-form-wrapper bmfm-progress-form-wrapper <?php echo esc_attr('' != $stored_cat_id ? 'bmfm-saved-progress-form-wrapper':''); ?>">
				   <ol class="wc-progress-steps <?php echo esc_attr('' != $stored_cat_id ? $saved_product_category_class :''); ?> <?php echo wp_kses_post($current_section_hide); ?>">
					   <li class="active">
							  <span class="bmfm-product-and-parameter-list-title bmfm-blinds-category-type bmfm-show-blinds-content">
							  Product & Parameter Setup
						   </span>
						   <span class="bmfm-accessories-and-parameter-list-title bmfm-accessories-category-type bmfm-hide-blinds-content">
								  Accessories and Parameter Setup
						   </span>
					   </li>
					   <li class="">
						   <span class="bmfm-products-list-setup-title bmfm-blinds-category-type bmfm-show-blinds-content">Fabric Setup</span>
						   <span class="bmfm-accessories-list-setup-title bmfm-accessories-category-type bmfm-hide-blinds-content">Accessories List Setup</span>
					   </li>
					   <li class="">Price Setup</li>
					   <li class="">Finish Setup</li>
				   </ol>
				   <form class="wc-progress-form-content woocommerce-importer" enctype="multipart/form-data" method="post">
					   <div class="bmfm-setup-section bmfm-product-config-wrapper bmfm-show-section">
							<header class="bmfm-progress-form-header">
							<h3>
								<span class="bmfm-product-setup-header bmfm-blinds-category-type bmfm-show-blinds-content">Product Setup</span>
								<span class="bmfm-product-accessories-header bmfm-accessories-category-type bmfm-hide-blinds-content">Accessories Setup</span>
							</h3>
						 </header>
						 <section>
							   <table class="form-table woocommerce-importer-options">
								<tbody>
									<tr>
										<th>
											<span class="bmfm-accessories-name-header-cell bmfm-accessories-category-type bmfm-hide-blinds-content">Accessories Name</span>
											<span class="bmfm-product-name-header-cell bmfm-blinds-category-type bmfm-show-blinds-content">Product Name</span>
											<span class="required">*</span>
										</th>
										 <td><input type="text" class="bmfm-input-field bmfm-category-name" name="bmfm_product_setup_data[product_name]" placeholder="Enter your product name" value="<?php echo wp_kses_post('' != $stored_cat_id && is_object($stored_term_object) ? $stored_term_object->get_name():''); ?>">
										
										<span class="bmfm-upload-image-button-inline">
											<?php 
											$image_url = is_object($stored_term_object) ? $stored_term_object->get_image_url():'';
											?>
											<a href="#" class="bmfm-upload-image button-secondary" style="<?php echo esc_attr(!empty($image_url) ? 'display:none':''); ?>" data-pixel="500">Upload Image</a>
											<span class="bmfm-upload-min-pixels-msg" style="<?php echo esc_attr(!empty($image_url) ? 'display:none':''); ?>">Recommended 500 x 500 pixels</span>
											<span class="bmfm-upload-image-span">
												<img src="<?php echo esc_url($image_url); ?>" class="bmfm-upload-image-src <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>">
												<span class="dashicons dashicons-dismiss bmfm-remove-image <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>"></span>
											</span>
											<input type="hidden" class="bmfm-upload-image-url" name="bmfm_product_setup_data[image_url]" value="<?php echo esc_url($image_url); ?>">
										</span>
									  </td>
									</tr>
									<tr>
										<th>Product Permalink</th>
										<td>
											<input class="bmfm-input-field bmfm-category-slug" type="text" name="bmfm_product_setup_data[product_slug]" placeholder="Enter your product slug"
										value="<?php echo wp_kses_post('' != $stored_cat_id && is_object($stored_term_object) ? $stored_term_object->get_slug():''); ?>">
										</td>
									</tr>
									<tr>
										<th>
											<span class="bmfm-accessories-cat-header-cell bmfm-accessories-category-type bmfm-hide-blinds-content">Accessories Category</span>
											<span class="bmfm-product-cat-header-cell bmfm-blinds-category-type bmfm-show-blinds-content">Product Category</span>
										</th>
										<td>
											<select class="bmfm-product-category-type" name="bmfm_product_setup_data[category_type]">
												<option value="blinds" <?php echo esc_attr('blinds' == $product_category_type? 'selected=true':''); ?>>Blinds</option>
												<option value="accessories" <?php echo esc_attr('accessories' == $product_category_type? 'selected=true':''); ?>>Accessories</option>
											</select>
										</td>
									</tr>
									<tr>
										<th>
											<span class="bmfm-accessories-desc-header-cell bmfm-accessories-category-type bmfm-hide-blinds-content">Accessories Description</span>
											<span class="bmfm-product-desc-header-cell bmfm-blinds-category-type bmfm-show-blinds-content">Product Description</span>
										</th>
										<td>
											<textarea class="bmfm-input-field bmfm-category-desc" name="bmfm_product_setup_data[product_desc]" placeholder="Enter your description here..." rows="2"><?php echo wp_kses_post(is_object($stored_term_object) && !empty($stored_term_object->get_description()) ? $stored_term_object->get_description():''); ?></textarea>
										</td>
									</tr>
								</tbody>
						   </table>
					   </section>
					  </div>
					   
					  <div class="bmfm-setup-section bmfm-parameter-setup-wrapper bmfm-show-section">
						 <header class="bmfm-progress-form-header">
							<h3>
								<span class="bmfm-accessories-parameter-header-cell bmfm-accessories-category-type bmfm-hide-blinds-content">Accessories Parameter Setup</span>
								<span class="bmfm-product-parameter-header-cell bmfm-blinds-category-type bmfm-show-blinds-content">Parameter Setup</span>
							 </h3>
						  </header>
						 <section class="bmfm-parameter-setup-section-wrapper">
							<?php 
							//                             $parameter_list_ids = '' != $stored_cat_id && is_object($stored_term_object) ? bmfm_get_parameter_list_ids($stored_term_object->get_id(),true):array();
							  $parameter_list_ids = '' != $stored_cat_id ? bmfm_get_parameter_list_ids($stored_term_object->get_id(), false, 'blinds'):array();	
							  include(BMFM_ABSPATH . '/includes/admin/views/html-blinds-parameters-list.php');
							 
							  $parameter_list_ids = '' != $stored_cat_id ? bmfm_get_parameter_list_ids($stored_term_object->get_id(), false, 'accessories'):array();	
							  include(BMFM_ABSPATH . '/includes/admin/views/html-accessories-parameters-list.php');
							?>
						 </section>
						</div>
					   
						<div class="bmfm-setup-section bmfm-product-list-setup-wrapper bmfm-hide-section">
						  <header class="bmfm-progress-form-header">
						  <h3>Fabric Setup</h3>
						  </header>
						  <section>
							  <?php 
								include(BMFM_ABSPATH . '/includes/admin/views/html-fabric-color-list.php');
								?>
						  </section>
					   </div>
					   
					   <div class="bmfm-setup-section bmfm-accessories-list-setup-wrapper bmfm-hide-section">
						  <header class="bmfm-progress-form-header">
							<h3>Accessories List Setup</h3>
						  </header>
						  <section>
							 <?php 
								include(BMFM_ABSPATH . '/includes/admin/views/html-accessories-color-list.php');
								?>
						  </section>
						</div>
					   
					   <div class="bmfm-setup-section bmfm-price-setup-wrapper bmfm-hide-section">
						  <header class="bmfm-progress-form-header">
							<h3>Price Setup</h3>
						  </header>
						  <section>
							   <table class="bmfm-price-setup-table-content wp-list-table widefat striped">
								<thead>
									<tr>
										<th><b>S.No</b></th>
										<th><b>Product Type</b></th>
										<th><b>Actions</b></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
						   </table>
						  </section>
					   </div>
					   
					   <div class="bmfm-setup-section bmfm-save-button-wrapper bmfm-show-section">
							 <section class="bmfm-save-button-section">
							 <span class="bmfm_add_more_tag">To add more than 2 products</span><a href="#" class="button-primary bmfm-upgrade-premium-button" style="margin-bottom: 20px;">Upgrade to Premium <img class="bmfm-upgrade-premium-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )) . '/assets/img/crown.png'; ?>" width="20" height="20"/></a>
								   <a href="#" style="display:none;" class="button-secondary bmfm-button bmfm-back-button <?php echo wp_kses_post($current_section_hide); ?>">Previous</a>
								   <a href="#" class="button-secondary bmfm-button bmfm-save-button 
								   <?php 
									echo esc_attr(empty($stored_cat_id) ? 'bmfm-data-saved':'');
									echo wp_kses_post($current_section_hide); 
									?>
									">Next</a>
							 </section>
					   </div>
					   
						<div class="bmfm-setup-section bmfm-finish-setup-wrapper bmfm-hide-section">   
						  <section>
							   <img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/tick.png" >
							 <h2><b>Hooray...!</b></h2>
							 <p class="bmfm-thanks-message">Thanks for installing our <b>Window Blinds Solution</b> plugin</p>
							 <p>To add more than 2 products upgrade to premium</p>
							 <a href="#" class="button bmfm-upgrade-premium-button bmfm-button" style="margin-bottom: 20px;">Upgrade to premium <img class="bmfm-upgrade-premium-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )) . '/assets/img/crown.png'; ?>" width="20" height="20"/></a>
							 <a href="<?php echo esc_url(admin_url('admin.php?page=bmfm_dashboard')); ?>" class="button bmfm-dashboard-url bmfm-button selected">Go to Dashboard</a>
							 <a href="<?php echo esc_url($view_product_url); ?>" class="button bmfm-view-dashboard-url bmfm-button"><span> View on website</span></a>
						  </section>
					   </div>
					   <input type="hidden" class="bmfm-active-section" value="products_and_parameter_setup">
					   <input type="hidden" class="bmfm-redirect-fabric-product-section" value="<?php echo wp_kses_post(isset($get_data['bmfm_fabric_product']) ? $get_data['bmfm_fabric_product']:''); ?>">
					   <input type="hidden" class="bmfm-term-id" name="bmfm_product_setup_data[term_id]" value="<?php echo wp_kses_post($stored_cat_id); ?>">
					   <input type="hidden" class="bmfm-product-type-id" name="bmfm_product_setup_data[type_id]" value="<?php echo wp_kses_post(bmfm_get_product_type_list_id_based_on_cat_id($stored_cat_id)); ?>">
					   <input type="hidden" class="bmfm-category-type-value" name="bmfm_product_setup_data[category_type_value]">
					   <input type="hidden" class="bmfm-current-section-name" name="bmfm_current_section_name" value="<?php echo wp_kses_post(isset($get_data['bmfm_current_section']) ? wc_clean(wp_unslash($get_data['bmfm_current_section'])):''); ?>">
				   </form>
				</div>
		<?php if ($stored_cat_id) : ?>
			<div class="woocommerce-progress-form-wrapper bmfm-progress-form-wrapper bmfm-click-back-category-url">
				<a href="<?php echo esc_url(admin_url('admin.php?page=bmfm_dashboard&bmfm_cat_id=' . $stored_cat_id)); ?>"><span class="dashicons dashicons-arrow-left-alt2"></span> Back to Dashboard</a>
			</div>
		<?php endif; ?>
</div>
