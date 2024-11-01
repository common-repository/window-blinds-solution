<?php
/**
 * Welcome Settings HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$get_data = bmfm_get_method();

$curl_status = BMFM_User_Request::get_status();
?>
<div class="woocommerce-progress-form-wrapper bmfm-welcome-form-wrapper bmfm-progress-form-wrapper">
	<form class="wc-progress-form-content woocommerce-importer" enctype="multipart/form-data" method="post">
		<header class="bmfm-progress-form-header">
			<h3><b>Welcome to BlindMatrix e-Commerce</b></h3>
		</header>
		<?php if(in_array($curl_status, array('freemium','premium'))): ?>
		 <section class="bmfm-welcome-contents-section">
			  <div class="bmfm-welcome-contents-wrapper">   
				<br>
			<div class="bmfm-import-steps-wrapper bmfm-region-selection-section-wrapper" data-step-="1">
				<div class="bmfm-step-process-group">
					<span class="bmfm-time-line bmfm-step-1 selected"></span>
				</div>
				<div class="bmfm-region-selection-wrapper bmfm-full-width">	
					<div class="bmfm-region-selection-section">
						<label><b>Choose your country</b></label>
					</div>
					<div>
						<input type="text" class="bmfm-country" readonly>
						<input type="hidden" name="bmfm_settings_data[chosen_country]" value="gb">
						<span class="dashicons dashicons-arrow-down-alt2 bmfm-country-arrow"></span>
					</div>
				</div>
			</div>
		<div class="bmfm-import-steps-wrapper" data-step-="2">
			<div class="bmfm-step-process-group">
				<span class="bmfm-time-line bmfm-step-2"></span>
			</div>
				<div class="bmfm-fetch-products-selection-wrapper bmfm-full-width">	
					<div class="bmfm-fetch-products-selection" data-type="1">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						<img class="bmfm-product-selection-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/supplier-info.png" height="50">
						<label>Fetch products from the suppliers</label>
						<span class="bmfm-tooltiptext">Add sample products from library</span>
					</div>
					<div class="bmfm-own-products-selection" data-type="2">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						<img class="bmfm-product-selection-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/product-info.png" height="50">
						<label>Add your own products</label>
					</div>
					<input type="hidden" class="bmfm-chosen-product-selection-type" name="bmfm_settings_data[product_selection]">
					<input type="hidden" class="bmfm-add-new-product-dashboard" value="<?php echo esc_attr(isset($get_data['bmfm_add_new_product_dashboard']) ? wc_clean(wp_unslash($get_data['bmfm_add_new_product_dashboard'])):''); ?>">
				</div>
		</div>
		<div class="bmfm-import-steps-wrapper" data-step-="3">
			<div class="bmfm-step-process-group">
				<span class="bmfm-time-line bmfm-step-3 bmfm-hide"></span>
			</div>
				 <div class="bmfm-uk-suppliers-wrapper bmfm-hide-suppliers bmfm-suppliers-section bmfm-full-width">	
					<div data-supplier="arena">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Arena
					</div>
					<div data-supplier="decora">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Decora
					</div>
					<div data-supplier="excel" style="display:none">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Excel
					</div>
					<div data-supplier="eclipse" style="display:none">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Eclipse
					</div>
					<div data-supplier="louvolite" style="display:none">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Louvolite
					</div>
					<div data-supplier="others" class="bmfm-other-supplier">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Other supplier
					</div>
					<input type="hidden" class="bmfm-chosen-supplier" name="bmfm_settings_data[uk_supplier]">
				</div>
				<div class="bmfm-us-suppliers-wrapper bmfm-hide-suppliers bmfm-suppliers-section bmfm-full-width">	
					<div data-supplier="alta">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Alta
					</div>
					<div data-supplier="graber">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Graber
					</div>
					<div data-supplier="norman">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Norman
					</div>
					<div data-supplier="others" class="bmfm-other-supplier">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Other supplier
					</div>
					<div class="bmfm-hidden-visibility"></div>
					<div class="bmfm-hidden-visibility"></div>
					<input type="hidden" class="bmfm-chosen-supplier" name="bmfm_settings_data[us_supplier]">
				</div>
				<div class="bmfm-aus-suppliers-wrapper bmfm-hide-suppliers bmfm-suppliers-section bmfm-full-width">	
					<div data-supplier="twc">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						TWC
					</div>
					<div data-supplier="sunlight">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Sunlight
					</div>
					<div data-supplier="others" class="bmfm-other-supplier">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						Other supplier
					</div>
					<input type="hidden" class="bmfm-chosen-supplier" name="bmfm_settings_data[aus_supplier]">
				</div>
				<div class="bmfm-import-products-choice bmfm-full-width">
					<div class="bmfm-import-library-button" >
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						<span class="dashicons dashicons-category"></span>
						Add product from library
						<span class="bmfm-tooltiptext">Add sample products from library</span>
					</div>
					<div class="bmfm-add-new-product-button">
						<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						<span class="dashicons dashicons-plus-alt"></span>
						Add new product
					</div>
				</div> 
		</div> 
		<div class="bmfm-import-steps-wrapper" data-step-="4">
			<div class="bmfm-step-process-group">
				<span class="bmfm-time-line bmfm-step-4 bmfm-hide"></span>
			</div> 
			<div class="bmfm-uk-fetch-products-wrapper bmfm-full-width">				    
				<div class="bmfm-products-wrapper bmfm-arena-supplier bmfm-full-width">
					<?php 
					$product_data = array('roller-blinds' => 'Roller Blinds','vertical-blinds' => 'Vertical Blinds','pleated-blinds' => 'Pleated Blinds','fauxwood-blinds' => 'Fauxwood Blinds');
					foreach ($product_data as $key => $product_name) :
						?>
					<div class="<?php echo esc_attr('yes' == $product_name ? 'bmfm-hidden-visibility':''); ?>" data-name="<?php echo esc_attr($key); ?>">
							<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
						<?php 
						$product_icon_file = $key;	
						if ('fauxwood-blinds' == $key) {
							$product_icon_file = 'wood-blinds';
						} else if ('pleated-blinds' == $key) {
							$product_icon_file = 'cellular-blinds';
						}	
						?>
							
						<img class="bmfm-product-icon" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/product-icons/<?php echo wp_kses_post($product_icon_file); ?>.png" width="25"><?php echo wp_kses_post($product_name); ?>
					</div>
						<?php
					endforeach;
					?>
					<input type="hidden" class="bmfm-chosen-products" name="bmfm_settings_data[uk_fetch_supplier_products][arena]">
				</div> 
				<div class="bmfm-products-wrapper bmfm-decora-supplier bmfm-full-width">
					<?php 
					$product_data = array('roller-blinds' => 'Roller Blinds','vertical-blinds' => 'Vertical Blinds','venetian-blinds' => 'Venetian Blinds','wood-blinds' => 'Wood Blinds','day-night-blinds' => 'Day & Night Blinds','roman-blinds' => 'Roman Blinds','cellular-blinds' => 'Cellular Blinds');
					foreach ($product_data as $key => $product_name) :
						$product_icon = untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/product-icons/' . $key . '.png';
						if ('empty_field' == $key) :
							$product_icon='';
						endif;
						?>
						<div class="<?php echo esc_attr('yes' == $product_name ? 'bmfm-hidden-visibility':''); ?>" data-name="<?php echo esc_attr($key); ?>">
							<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
							<img class="bmfm-product-icon" src="<?php echo esc_url($product_icon); ?>" width="25">
						<?php echo wp_kses_post($product_name); ?>
						</div>
						<?php
					endforeach;
					?>
					<input type="hidden" class="bmfm-chosen-products" name="bmfm_settings_data[uk_fetch_supplier_products][decora]">
				</div> 	
			</div>
				<div class="bmfm-add-your-own-products-wrapper bmfm-full-width">	
					<?php 
					$product_data = array('roller-blinds' => 'Roller Blinds','vertical-blinds' => 'Vertical Blinds','venetian-blinds' => 'Venetian Blinds','wood-blinds' => 'Wood Blinds','day-night-blinds' => 'Day & Night Blinds','roman-blinds' => 'Roman Blinds','cellular-blinds' => 'Cellular Blinds');
					foreach ($product_data as $key => $product_name) :
						$product_icon = untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/product-icons/' . $key . '.png';
						if ('empty_field' == $key) :
							$product_icon='';
						endif;
						?>
						<div  class="<?php echo esc_attr('yes' == $product_name ? 'bmfm-hidden-visibility':''); ?>" data-name="<?php echo esc_attr($key); ?>">
							<img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) )); ?>/assets/img/transparent-tick.png">
							<img class="bmfm-product-icon" src="<?php echo esc_url($product_icon); ?>" width="25">
						<?php echo wp_kses_post($product_name); ?>
						</div>
						<?php
					endforeach;
					?>
					<input type="hidden" class="bmfm-add-your-own-products" name="bmfm_settings_data[own_products]">
				</div> 
				<div class="bmfm-no-supplier-error bmfm-full-width">
					No suppliers allocated to this country. Please fill in here to get the data.
				</div> 
				<div class="bmfm-no-products-msg bmfm-full-width">
					No products found for this supplier.
				</div> 
			</div>   
				<div class="bmfm-contact-us-wrapper bmfm-full-width">
					<div class="bmfm-supplier-details-heading bmfm-other-supplier-details">
						<h3>Suppliers details</h3>
					</div>
					<div class="bmfm-your-details-heading bmfm-other-supplier-details">
						<h3>Your details</h3>
					</div>
					
					<div class="bmfm-contact-us-supplier-name bmfm-other-supplier-details">
						<input type="text" name="bmfm_settings_data[contact_us][supplier_name]" placeholder="Supplier name">
					</div>
					
					<div class="bmfm-contact-us-company-name bmfm-other-supplier-details">
						<input type="text" placeholder="Your company name" name="bmfm_settings_data[contact_us][company_name]">
					</div>
					
					<div class="bmfm-contact-us-acc-manager-name bmfm-other-supplier-details">
						<input type="text" placeholder="Account manager" name="bmfm_settings_data[contact_us][acc_manager]">
					</div>
					
					<div class="bmfm-contact-us-name bmfm-other-supplier-details">
						<input type="text" placeholder="Your name*" name="bmfm_settings_data[contact_us][name]">
					</div>
					
					<div class="bmfm-contact-us-acc-manager-email bmfm-other-supplier-details">
						<input type="email" placeholder="Account manager email" name="bmfm_settings_data[contact_us][acc_manager_email]">
					</div>
					
					<div class="bmfm-contact-us-email bmfm-other-supplier-details">
						<input type="email" placeholder="Your email*" name="bmfm_settings_data[contact_us][email]">
					</div>
					
					<div class="bmfm-contact-us-acc-manager-ph-no bmfm-other-supplier-details">
						<input type="text" placeholder="Account manager phone" name="bmfm_settings_data[contact_us][acc_manager_ph]">
					</div>
					
					<div class="bmfm-contact-us-ph-no bmfm-other-supplier-details">
						<input type="text" placeholder="Your phone number*" name="bmfm_settings_data[contact_us][ph_no]">
					</div>					
					<div class="bmfm-contact-us-upload-file-wrapper bmfm-other-supplier-details">
						Click here to <a href="#" class="button-secondary bmfm-contact-us-upload-file-action bmfm-button">Upload Catalogue</a>
						<span class="bmfm-contact-us-file-name"></span>
						<input type="file" name="bmfm_contact_us_upload_file" class="bmfm-contact-us-file">
					</div>
				</div>
				<div class="bmfm-contact-us-submit-wrapper bmfm-full-width">
					  <a href="#" class="button-secondary bmfm-contact-us-action">Submit</a>
				</div>
				<div class="bmfm-import-button-wrapper bmfm-full-width">
					  <a href="#" class="button-secondary bmfm-import-button-action">Import Products From Library</a>
				</div>
			  </div>
		</section>
		<?php endif; ?>
		
		<?php if(!in_array($curl_status, array('freemium','premium'))): ?>
		  <section class="bmfm-freemium-activation-key-section">
		     <div class="bmfm-activation-key">
		         <label>Activation Key</label>
		         <input type="text" placeholder="Enter your activation key here..." class="bmfm-activation-key-val">
		         <a href="#" class="button-secondary bmfm-activation-key-submit-button" >Submit</a>
		     </div>   
		     <div class="bmfm-activation-key-info">
		         <a href="https://blindmatrix.com/ecommerce-for-retailers/" target="_blank">Know more >></a>
		     </div> 
		  </section>
		<?php endif; ?>
		<section></section>
	</form>
	<?php 
	if('freemium' == $curl_status): 
	    include(BMFM_ABSPATH . '/includes/admin/views/html-freemium-days-remaining-info.php');
	endif;?>
</div>

<?php 

if(!in_array($curl_status, array('freemium','premium'))): 
    include(BMFM_ABSPATH . '/includes/admin/views/html-contact-us-form.php');
endif;

include(BMFM_ABSPATH . '/includes/admin/views/html-upgrade-premium-info.php');


