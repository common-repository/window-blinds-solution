/*bmfm_admin_params */
jQuery( function( $ ) {
	var file_frame;
	var changed = false,$_selected_image = '',$_product_name='',$material_img_object='';
	var Admin = {
		init: function() {		
// 			this.before_reload_confirm_alert();			
			$(".bmfm-product-config-wrapper,.bmfm-parameter-setup-wrapper,.bmfm-product-list-table-content,.bmfm-accessories-list-table-content").on("input", function() {
				Admin.set_save_functionality_disabled();
			});
			$( document ).on( 'click','.bmfm-remove-image,.bmfm-upload-image',function(){
				Admin.set_save_functionality_disabled();
			});
			Admin.set_changed_row_fabric_list();
			// Upload Image Functionality.
			$( document ).on( 'click','.bmfm-upload-image',this.upload_image_button);
			$( document ).on( 'click','.bmfm-remove-image',this.remove_image);
			$( document ).on( 'click','.bmfm-remove-material-image',this.remove_material_images);
			// Blinds Parameter List CRUD rule Functionality.
			$( document ).on( 'click','.bmfm-add-parameter-rule',this.add_parameter_rule);
			$( document ).on( 'click','.bmfm-remove-parameter-row',this.remove_parameter_rule);
			$( document ).on( 'click','.bmfm-edit-parameter-list',this.edit_parameter_list_rule);
			// Accessories Parameter List CRUD rule Functionality.
			$( document ).on( 'click','.bmfm-add-accessories-parameter-rule',this.add_accessories_parameter_rule);
			$( document ).on( 'click','.bmfm-remove-accessories-parameter-row',this.remove_accessories_parameter_rule);
			$( document ).on( 'click','.bmfm-edit-accessories-parameter-list',this.edit_accessories_parameter_list_rule);
			// Accessories List CRUD rule Functionality.
			$( document ).on('click','.bmfm-add-accessories-rule',this.add_accessories_rule);
			$( document ).on('click','.bmfm-remove-accessories-row',this.remove_accessories_rule);
			// Dropdown List Parameter/Accessories CRUD rule Functionality.
			$( document ).on('click','.bmfm-blinds-add-dropdown-popup-parameter',this.add_dropdown_parameter_rule_popup);
			$( document ).on('click','.bmfm-remove-dropdown-parameter-row,.bmfm-remove-component-parameter-row',this.remove_parameter_rule_popup);
			// Fabric/Color List CRUD rule Functionality.
			$( document ).on('click','.bmfm-add-fabric-color-rule',this.add_fabric_color_rule);
			$( document ).on('click','.bmfm-remove-fabric-color-row',this.remove_fabric_color_rule);
			// Category List CRUD rule functionality.
			$( document ).on('click','.bmfm-blinds-add-category-list',this.add_category_list_rule);
			$( document ).on('click','.bmfm-blinds-remove-category-list',this.remove_category_list_rule);
			$( document ).on('click','.bmfm-save-category-list-button',this.save_category_list_rule);
			// Category Sublist popup.
			$( document ).on('click','.bmfm-edit-category-list',this.edit_category_list_popup);
			$( document ).on('click','.bmfm-blinds-add-category-sublist',this.add_category_sublist_popup);
			$( document ).on('click','.bmfm-remove-category-sub-list',this.remove_category_sublist_popup);
			$( document ).on('click','.bmfm-save-category-sublist',this.save_category_sublist);
			// Save Products Data Functionality.
			$( document ).on( 'click','.bmfm-save-button',this.save_functionality);
			if($('.bmfm-redirect-fabric-product-section').length && 'yes' == $('.bmfm-redirect-fabric-product-section').val()){
				$('.bmfm-save-button').click();
			}
			// Save fabric color rule.
			$(document).on('click','.bmfm-save-fabric-color-rule',this.save_fabric_color_rule);
			// Save Parameter / Accessories List Data in Popup Functionality.
			$( document ).on( 'click','.bmfm-save-drop-down-parameter-list',this.save_parameter_list_popup_functionality);
			// Toggle Functionality.
			$( document ).on('change','.bmfm-product-category-type',this.toggle_product_category_type);
			$('.bmfm-product-category-type').change();
			// Delete Post Confirm Message.
			$( document ).on('click','.bmfm-delete-post',this.delete_post_confirm_msg);
			// Toggle parameter type.
			$( document ).on('change','.bmfm-parameter-type,.bmfm-accesories-parameter-type',this.toggle_parameter_type);
			$('.bmfm-parameter-type,.bmfm-accesories-parameter-type').change();
			// Country selection JS.
			$( document ).on('click','.bmfm-save-accessories-rule',this.save_accessories_rule);
			$(".bmfm-country").countrySelect({
					defaultCountry: "gb",
					preferredCountries: [ 'gb','ie', 'us','ca','au','nz'],
  					responsiveDropdown: true,
			});
			// Toggle JS.
			$(document).on('click','.bmfm-fetch-products-selection',this.fetch_products_selection);
			$(document).on('click','.bmfm-own-products-selection',this.own_products_selection);
			$(document).on('click','.bmfm-import-library-button',this.import_library_selection);
			if('1' == $('.bmfm-add-new-product-dashboard').val()){
				$('.bmfm-own-products-selection').click();
			}
			$(document).on('click','.bmfm-add-new-product-button',this.add_new_product_url);
			$(document).on('click','.bmfm-country,.bmfm-country-arrow',this.toggle_country_arrow); 			
			$(document).on('change','.bmfm-country',this.toggle_country_selection);
			$(document).on('click','.bmfm-uk-suppliers-wrapper > div,.bmfm-us-suppliers-wrapper > div,.bmfm-aus-suppliers-wrapper > div',this.toggle_supplier);
			$(document).on('click','.bmfm-products-wrapper > div',this.toggle_products_wrapper);
			$(document).on('click','.bmfm-add-your-own-products-wrapper > div',this.toggle_add_your_own_products_wrapper);
			// Import button functionality.
			$(document).on('click','.bmfm-import-button-action',this.import_button_action);
			// Upload File.
			$(document).on('click','.bmfm-contact-us-upload-file-action',this.contact_us_upload_action);
			$(document).on('change','.bmfm-contact-us-file',this.add_upload_file);
			// Contact us submit.
			$(document).on('click','.bmfm-contact-us-action',this.contact_us_action);
			$(document).on('keyup','.bmfm-category-name',this.add_product_slug);
			// Upgrade premium popup.
			$(document).on('click','.bmfm-upgrade-premium-button,.bmfm-go-premium-menu',this.upgrade_premium);
			// Select2.
			$('.bmfm-select2').select2({
				templateResult: this.add_custom_img_select2,
				minimumResultsForSearch: -1
			});
			// Save category selection on dashboard.
			$(document).on('change','.bmfm-category-selection',this.save_category_selection_dashboard);
			// Edit product link.
			$(document).on('click','.bmfm-blinds-product-link img',this.edit_product_link);
			// Upload image custom popup.
			$(document).on('click','.bmfm-upload-img-a',this.upload_image_custom_popup);
			// Custom popup image selection.
			$(document).on('click','.bmfm-custom-popup-img',this.custom_popup_img_selection);
			// Click back button.
			$(document).on('click','.bmfm-back-button',this.click_back_button);
			// Hide all frames checkbox.
			$(document).on('change','.bmfm-hide-frames-checkbox',this.hide_frames_checkbox);
			// uncheck hide all frame checkbox.
			$(document).on('change','.bmfm-hide-frame',this.uncheck_hide_all_frame_checkbox);
			// Unit Measurement.
			$(document).on('change','input[name="bmfm_default_unit"]',this.unit_measurement);
			// View order item detail.
			$(document).on('click','.bmfm-order-item-detail',this.view_order_item_detail);
			// Reset all data.
			$(document).on('click','.bmfm-reset-data-action',this.reset_all_data_action);
			// Remove fabric color row dashboard.
			$(document).on('click','.bmfm-remove-fabric-color-row-dashboard',this.remove_fabric_color_row_dashboard);
			// Save parameter list rule.
			$(document).on('click','.bmfm-save-parameter-list-rule',this.save_parameter_list_rule);
			// Save accessories list rule.
			$(document).on('click','.bmfm-save-accessories-parameter-list-rule',this.save_accessories_parameter_list_rule);
			// Set markup upto two decimals.
			$(document).on('keyup change','.bmfm-component-parameter-markup,.bmfm-markup-fee',this.set_markup_upto_two_decimals);
			// Freemium activation key submission.
			$(document).on('click','.bmfm-activation-key-submit-button',this.freemium_activation_key_submit_button);
			// Sort columns.
            this.sortable_columns();
			// Set background color for edit order item thumbnail image.
			this.set_bg_color_for_edit_order_item_thumbnail();
		        // Product fabric list selection
			this.target_specific_section();
			// Contact us submit button
			$(document).on('submit','.bmfm-freemium-contact-us-form',this.freemium_contact_us_submit_button);
		},
		before_reload_confirm_alert:function(){
			$( 'input, textarea, select, checkbox' ).on( 'change', function (event) {
				if ( ! changed ) {
					window.onbeforeunload = function () {
						return false;
					};
					changed = true;
				}
			} );
			$( '.bmfm-remove-image,.bmfm-upload-image,.bmfm-save-button').on( 'click', function (event) {
				if ( ! changed ) {
					window.onbeforeunload = function () {
						return false;
					};
					changed = true;
				}
			});
			$('.bmfm-dashboard-url,.bmfm-view-dashboard-url').on('click',function(){
				window.onbeforeunload = {};
			});
		},
		upload_image_button:function(event){
			event.preventDefault();

			var $this = $(event.currentTarget),
				$table_data = $this.closest('td'),
				$multiple_image = $this.data('multiple'),
				$dimensions= $this.data('pixel');
			// Create the media frame.
			file_frame = wp.media.frames.downloadable_file = wp.media({
				title: bmfm_admin_params.upload_img_title,
				button: {
					text: bmfm_admin_params.upload_btn_text
				},
			    multiple: $multiple_image,
			});

			$('.bmfm-upload-min-pixels-msg').show();
            if(true !== $multiple_image){
			    // When an image is selected, run a callback.
			    file_frame.on( 'select', function() {
				    var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
					if (true == Admin.validate_save_fabric_color_and_accessories_data_row($this) ){
						return false;	
					}
// 					else if (( $dimensions == 500 && attachment.width != $dimensions && attachment.height != $dimensions ) || ( $dimensions == 150  && attachment.width > $dimensions && attachment.height > $dimensions   )) {	
// 						if(true == Admin.error_message_image_upload(file_frame, $dimensions)){
// 							return false;	
// 						}																			
// 					}
					else {
						$table_data.find('.bmfm-upload-image').hide();
						$table_data.find('.bmfm-remove-image').show();
						$table_data.find( '.bmfm-upload-image-src' ).show();
						$table_data.find( '.bmfm-upload-image-url' ).val( attachment.url );
						$table_data.find( '.bmfm-upload-image-src' ).attr( 'src', attachment.url );
						Admin.save_fabric_color_and_accessories_data_row($this);
						$('.bmfm-upload-min-pixels-msg').hide();
					}
			    });
            }else{
                  file_frame.on( 'select', function() {
                    $table_data.find('.bmfm-upload-images').removeClass('selected');
                    $table_data.find('.bmfm-upload-images').addClass('selected');
                    var selection = file_frame.state().get( 'selection' ),
                        $image_urls = [];
                        $table_data.find('.bmfm-upload-images').val('');
                    selection.map( function ( attachment ) {
                        attachment = attachment.toJSON();
// 						if (true != Admin.validate_save_fabric_color_and_accessories_data_row($this,false) && attachment.width == $dimensions && attachment.height == $dimensions) {
						if (true != Admin.validate_save_fabric_color_and_accessories_data_row($this,false)){
							$image_urls.push(attachment.url);
							$table_data.find('.bmfm-upload-images').append('<span class="bmfm-per-upload-image"><img src="'+attachment.url+'" width="20"><span class="dashicons dashicons-dismiss bmfm-remove-material-image"></span></span>');
						}
                    });
					
					var error = false;
					if (true == Admin.validate_save_fabric_color_and_accessories_data_row($this)){
						error = true;	
					}else if ($image_urls.length > 0) {
                   	 	$table_data.find( '.bmfm-material-upload-image-urls' ).val( $image_urls.join(',') );
					}else{			
						if(true == Admin.error_message_image_upload(file_frame,$dimensions)){
							error = true;	
						}					
					}
					if(true === error){
						return false;
					}

					$('.bmfm-upload-min-pixels-msg').hide();
					if (false == Admin.validate_save_fabric_color_and_accessories_data_row($this) ){
						Admin.save_fabric_color_and_accessories_data_row($this);
					}
			    });
            }
			// Finally, open the modal.
			file_frame.open();
			return false;
		},error_message_image_upload:function(file_frame,$dimensions){
			var range;
			if($dimensions == 150){
				range = 'below ';
			}else{
				range = '';
			}
			$.confirm({							
				title: 'Error!',							
				columnClass: 'col-md-4 col-md-offset-4',							
				content: '<div class="bmfm-error-content-popup">Please select an images with dimensions '+ range + $dimensions +' x '+ $dimensions + ' pixels.</div>',
				type: 'red',							
				icon: 'fa fa-warning',							
				typeAnimated: true,							
				boxWidth: '30%',							
				useBootstrap: false,							
				closeIcon: true,							
				buttons: {								
					okay: function () {									
						file_frame.open();
						return true;								
					}							
				}						
			});				
		},remove_image:function(event){
			event.preventDefault();
			if(!confirm(bmfm_admin_params.confirm_msg)){
				return false;
			}
			
			var $this = $(event.currentTarget),
				$table_data = $this.closest('td');
			
			if($this.closest('.bmfm-upload-custom-image-span').length > 0){
			    $('.bmfm-product-list-table-content tbody tr').each(function(){
                   var span_obj = $(this).find('td:eq(3)').find('.bmfm-upload-custom-image-span');
                   $(this).find('td:eq(3)').each(function(){
                        $(this).find('.bmfm-upload-image-src').attr('src','');
                        $(this).find('.bmfm-frame-upload-image-url').val('');
                        $(this).find('.bmfm-upload-image-src').hide();
                        $(this).find('.bmfm-upload-img-a').show();
                        $(this).find('.bmfm-remove-image').hide();
					    $(this).find('.bmfm-frame-upload-product-name').val('');
                      });
                  });
			}else{
			    $this.hide();
		    	$table_data.find('.bmfm-upload-image').show();
			    $table_data.find('.bmfm-upload-image-src').hide();
			    $table_data.find('.bmfm-upload-image-src').attr( 'src', '' );
			    $table_data.find('.bmfm-upload-image-url').val('');
			}
			$('.bmfm-upload-min-pixels-msg').show();
		},
		remove_material_images:function(event){
		    event.preventDefault();
		    var $this = $(event.currentTarget),$table_data = $this.closest('td');
		    if(!confirm(bmfm_admin_params.confirm_msg)){
				return false;
			}
			
			$this.closest('.bmfm-per-upload-image').remove();
			var $image_urls = [];
			if($table_data.find('.bmfm-per-upload-image').length > 0){
			    $table_data.find('.bmfm-per-upload-image').each(function(){
			        $image_urls.push($(this).find('img').attr('src'));
			    });
			    $table_data.find( '.bmfm-material-upload-image-urls' ).val( $image_urls.join(',') );
			}
			
			if($table_data.find('.bmfm-per-upload-image').length <= 0){
			    $table_data.find('.bmfm-upload-images').removeClass('selected');
			    $table_data.find( '.bmfm-material-upload-image-urls' ).val('');
			}
			Admin.save_fabric_color_and_accessories_data_row($table_data);
		},
		add_parameter_rule:function(event){
			event.preventDefault();
			$(event.currentTarget).closest('.bmfm-parameter-setup-table-content').find('tbody').append(bmfm_admin_params.parameter_row_html);
			// Append Index to the row.
			var index = 0;
			$('.bmfm-parameter-setup-table-content').find('tbody tr').each(function(){
				$(this).find('.bmfm-parameter-name').attr('name', $(this).find('.bmfm-parameter-name').data('name')+'['+index+']');
				$(this).find('.bmfm-parameter-type').attr('name',$(this).find('.bmfm-parameter-type').data('name')+'['+index+']');
				$(this).find('.bmfm-parameter-mandatory-chekbox').attr('name',$(this).find('.bmfm-parameter-mandatory-chekbox').data('name')+'['+index+']');
				index++;
			});
			
			if($('.bmfm-parameter-setup-table-content tbody tr').length > 0){
				$('.bmfm-save-button').removeClass('bmfm-data-saved');
				$('.bmfm-save-parameter-list-rule').removeClass('bmfm-data-saved');
				$('.bmfm-save-button').addClass('bmfm-data-saved');
			}
			$('.bmfm-parameter-type,.bmfm-accesories-parameter-type').change();
		},
		remove_parameter_rule:function(event){
			event.preventDefault();
			var $action = Admin.remove_row($(event.currentTarget));
			if(true === $action){
			    $(event.currentTarget).closest('tr').remove();
			    var index = 0;
			    $('.bmfm-parameter-setup-table-content').find('tbody tr').each(function(){
				    $(this).find('.bmfm-parameter-name').attr('name', $(this).find('.bmfm-parameter-name').data('name')+'['+index+']');
				    $(this).find('.bmfm-parameter-type').attr('name',$(this).find('.bmfm-parameter-type').data('name')+'['+index+']');
				    $(this).find('.bmfm-parameter-mandatory-chekbox').attr('name',$(this).find('.bmfm-parameter-mandatory-chekbox').data('name')+'['+index+']');
				    index++;
			    });
			}
			$('.bmfm-parameter-type,.bmfm-accesories-parameter-type').change();
			if($('.bmfm-parameter-setup-table-content tbody tr').length <= 0){
				$('.bmfm-save-button').removeClass('bmfm-data-saved');
				$('.bmfm-save-parameter-list-rule').removeClass('bmfm-data-saved');
				$('.bmfm-save-button').addClass('bmfm-data-saved');
				$('.bmfm-save-parameter-list-rule').addClass('bmfm-data-saved');
			}
		},
		edit_parameter_list_rule:function(event,$obj = 'false'){
			if('false' != $obj){
				var $this = $obj;
			}else{
				event.preventDefault();
				var $this = $(event.currentTarget);
			}
			
			var $table_row = $this.closest('tr'),
				$type = $('.bmfm-product-category-type').val(),
				$content = '',
				$parameter_type = '.bmfm-parameter-type',
				$selected_parameter_type = $table_row.find('.bmfm-parameter-type option:selected').text(),
				$parameter_name = $table_row.find('.bmfm-parameter-name').val();
				
				if('accessories' == $type){
				    $parameter_type          = '.bmfm-accesories-parameter-type';
				    $selected_parameter_type = $table_row.find('.bmfm-accesories-parameter-type option:selected').text();
					$parameter_name  = $table_row.find('.bmfm-accessories-name').val();
				}
				
			if('numeric_x' == $table_row.find($parameter_type).val() || 'numeric_y' == $table_row.find($parameter_type).val() || 'text' == $table_row.find($parameter_type).val()){
				return false;
			}
			
			var data={
				action:'bmfm_edit_parameter_list_rule',
				parameter_list_id:$this.data('parameter_list_id'),
				parameter_type:$table_row.find($parameter_type).val(),
				security:bmfm_admin_params.edit_parameter_list_rule_nonce,
			};
			
			Admin.block_ui($this);
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
				 if(response.data.success){
				  $.confirm({
    				closeIcon: function(){
        				return true;
    				},
					title: $selected_parameter_type +' - '+ $parameter_name,
					boxWidth: '60%',
					useBootstrap: false,
					content: response.data.html,
					onContentReady: function () {
						$('.bmfm-parameter-list-id').val($this.data('parameter_list_id'));
						$('.bmfm-parameter-list-type').val( $table_row.find($parameter_type).val());
    				},
    				buttons: {
        				ok:{
            				isHidden: true,
        				},
						cancel:{
							isHidden: true,	
						}
    				  }
					});
					Admin.unblock_ui($this);
				}else if(response.data.error){
					alert(response.data.error);
					Admin.unblock_ui($this);
				}
			  }
			});
			return false;
		},
		add_accessories_parameter_rule:function(event){
			event.preventDefault();
			$(event.currentTarget).closest('.bmfm-accessories-parameter-table-content').find('tbody').append(bmfm_admin_params.accessories_parameter_row_html);
			// Append Index to the row.
			var index = 0;
			$('.bmfm-accessories-parameter-table-content').find('tbody tr').each(function(){
				$(this).find('.bmfm-accessories-name').attr('name', $(this).find('.bmfm-accessories-name').data('name')+'['+index+']');
				$(this).find('.bmfm-accesories-parameter-type').attr('name',$(this).find('.bmfm-accesories-parameter-type').data('name')+'['+index+']');
				$(this).find('.bmfm-accesories-parameter-mandatory-chekbox').attr('name',$(this).find('.bmfm-accesories-parameter-mandatory-chekbox').data('name')+'['+index+']');
				index++;
			});
			
			if($('.bmfm-accessories-parameter-table-content tbody tr').length > 0){
				$('.bmfm-save-button').removeClass('bmfm-data-saved');
				$('.bmfm-save-accessories-parameter-list-rule').removeClass('bmfm-data-saved');
				$('.bmfm-save-button').addClass('bmfm-data-saved');
			}
			$('.bmfm-parameter-type,.bmfm-accesories-parameter-type').change();
		},
		remove_accessories_parameter_rule:function(event){
			event.preventDefault();
			var $action = Admin.remove_row($(event.currentTarget));
			if(true === $action){
			    $(event.currentTarget).closest('tr').remove();
			    var index = 0;
			    $('.bmfm-accessories-parameter-table-content').find('tbody tr').each(function(){
				    $(this).find('.bmfm-accessories-name').attr('name', $(this).find('.bmfm-accessories-name').data('name')+'['+index+']');
				    $(this).find('.bmfm-accesories-parameter-type').attr('name',$(this).find('.bmfm-accesories-parameter-type').data('name')+'['+index+']');
				    $(this).find('.bmfm-accesories-parameter-mandatory-chekbox').attr('name',$(this).find('.bmfm-accesories-parameter-mandatory-chekbox').data('name')+'['+index+']');
				    index++;
			    });
			}
			if($('.bmfm-accessories-parameter-table-content tbody tr').length <= 0){
				$('.bmfm-save-button').removeClass('bmfm-data-saved');
				$('.bmfm-save-accessories-parameter-list-rule').removeClass('bmfm-data-saved');
				$('.bmfm-save-button').addClass('bmfm-data-saved');
				$('.bmfm-save-accessories-parameter-list-rule').addClass('bmfm-data-saved');
			}
		},
		edit_accessories_parameter_list_rule:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			Admin.edit_parameter_list_rule(false,$this);
		},
		add_fabric_color_rule:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget),
			$length = $this.closest('.bmfm-product-list-table-content').find('tbody tr').length;
			if($length >=50){
			   Admin.upgrade_premium();
			   return false;
			}
			
			$this.closest('.bmfm-product-list-table-content').find('tbody').append(bmfm_admin_params.fabric_color_row_html);
			var index = 0,$count = 1;
			$('.bmfm-product-list-table-content').find('tbody tr').each(function(){
				var $loop_this = $(this);
				$loop_this.find('td:eq(0)').text($count+'.');
				$loop_this.find('.bmfm-fabric-color-name').attr('name', $(this).find('.bmfm-fabric-color-name').data('name')+'['+index+']');
				$loop_this.find('.bmfm-fabric-color-upload-image-url').attr('name',$(this).find('.bmfm-fabric-color-upload-image-url').data('name')+'['+index+']');
				$loop_this.find('.bmfm-frame-upload-image-url').attr('name',$(this).find('.bmfm-frame-upload-image-url').data('name')+'['+index+']');
				$loop_this.find('.bmfm-material-upload-image-urls').attr('name',$(this).find('.bmfm-material-upload-image-urls').data('name')+'['+index+']');
				$loop_this.find('.bmfm-fabric-color-desc').attr('name',$(this).find('.bmfm-fabric-color-desc').data('name')+'['+index+']');
				index++;
				$count++;
			});
			
			if($('.bmfm-product-list-table-content').find('tbody tr').length > 0){
				var $url = $('.bmfm-product-list-table-content').find('tbody tr:first-child').find('.bmfm-frame-upload-image-url').val(),$product_name = $('.bmfm-product-list-table-content').find('tbody tr:first-child').find('.bmfm-frame-upload-product-name').val(),
					$table_data = $('.bmfm-product-list-table-content').find('tbody tr:last-child td:eq(3)');
				if('' != $url && '' != $product_name){
					$table_data.find('.bmfm-upload-img-a').hide();
					$table_data.find('.bmfm-upload-image').hide();
					$table_data.find('.bmfm-remove-image').show();
					$table_data.find( '.bmfm-upload-image-src' ).show();
					$table_data.find( '.bmfm-upload-image-url' ).val( $url );
					$table_data.find( '.bmfm-upload-image-src' ).attr( 'src', $url );
					$table_data.find('.bmfm-frame-upload-product-name').val($product_name);
				}
			}
			
			if($('.bmfm-product-list-table-content').find('tbody tr').length > 0){
				$('.bmfm-save-button').removeClass('bmfm-data-saved');
				$('.bmfm-save-button').addClass('bmfm-data-saved');
				$('.bmfm-save-fabric-color-rule').removeClass('bmfm-data-saved');
			}
		},
		remove_fabric_color_rule:function(event){
		    var $action = Admin.remove_row($(event.currentTarget));
		    if(true == $action){
			    var index = 0,$count = 1;
			    $(event.currentTarget).closest('tr').remove();    
			    $('.bmfm-product-list-table-content').find('tbody tr').each(function(){
				    var $loop_this = $(this);
				    $loop_this.find('td:eq(0)').text($count+'.');
				    $loop_this.find('.bmfm-fabric-color-name').attr('name', $(this).find('.bmfm-fabric-color-name').data('name')+'['+index+']');
				    $loop_this.find('.bmfm-fabric-color-upload-image-url').attr('name',$(this).find('.bmfm-fabric-color-upload-image-url').data('name')+'['+index+']');
				    $loop_this.find('.bmfm-fabric-color-desc').attr('name',$(this).find('.bmfm-fabric-color-desc').data('name')+'['+index+']');
				    index++;
				    $count++;
			    });
		    }
			if($('.bmfm-product-list-table-content').find('tbody tr').length <=0){
				$('.bmfm-save-button').removeClass('bmfm-data-saved');
				$('.bmfm-save-fabric-color-rule').removeClass('bmfm-data-saved');
				$('.bmfm-save-button').addClass('bmfm-data-saved');
				$('.bmfm-save-fabric-color-rule').addClass('bmfm-data-saved');
			}
		},
		add_category_list_rule:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			$this.closest('.bmfm-blinds-category-list-content').find('tbody').append(bmfm_admin_params.category_list_row_html);
			var index = 0,$count = 1;
			$('.bmfm-blinds-category-list-content').find('tbody tr').each(function(){
				var $loop_this = $(this);
				$loop_this.find('td:eq(0)').text($count+'.');
				$loop_this.find('.bmfm-category-list-name').attr('name', $(this).find('.bmfm-category-list-name').data('name')+'['+index+']');
				$loop_this.find('.bmfm-category-sequence').attr('name',$(this).find('.bmfm-category-sequence').data('name')+'['+index+']');
				index++;
				$count++;
			});
		},
		remove_category_list_rule:function(event){
		    var $action = Admin.remove_row($(event.currentTarget));
		    if(true === $action){
		    $(event.currentTarget).closest('tr').remove();    
			var index = 0,$count = 1;
			    $('.bmfm-blinds-category-list-content').find('tbody tr').each(function(){
				    var $loop_this = $(this);
				    $loop_this.find('td:eq(0)').text($count+'.');
				    $loop_this.find('.bmfm-category-list-name').attr('name', $(this).find('.bmfm-category-list-name').data('name')+'['+index+']');
				    $loop_this.find('.bmfm-category-sequence').attr('name',$(this).find('.bmfm-category-sequence').data('name')+'['+index+']');
				    index++;
				    $count++;
			    });
		    }
		},
		save_category_list_rule:function(event){
			event.preventDefault();
			if($('.bmfm-blinds-category-list-content').find('tbody').find('tr').length<=0){
				alert(bmfm_admin_params.category_error_msg);
				return false;
			}
		
			var error = false;
			$('.bmfm-category-list-name').each(function(){ 
				if('' == $(this).val() || undefined == $(this).val()){
					$(this).css('border','1px solid #ff0000');
					error = true;
				}
			});
			if(true == error){
				return false;
			}
		
			var data={
				action:'bmfm_save_category_list_rule',
				form_data:$('.bmfm-category-list-form').serialize(),
				security:bmfm_admin_params.save_category_list_nonce,
			};
			
			Admin.block_ui($('.bmfm-category-list-form'));
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
						alert(response.data.msg);
						$('.bmfm-blinds-category-list-content').find('tbody').html(response.data.category_html_content);
						Admin.unblock_ui($('.bmfm-category-list-form'));
					}else if(response.data.error){
						alert(response.data.error);
						Admin.unblock_ui($('.bmfm-category-list-form'));
					}
				}
			});
		},
		edit_category_list_popup:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget),
				$category_list_id = $this.data('category_list_id'),
				$product_id = $this.data('product_id');
			
			var data={
				action:'bmfm_edit_category_list_popup',
				category_list_id: $category_list_id,
				product_id:$product_id,
				security:bmfm_admin_params.edit_category_list_popup_nonce,
			};
			Admin.block_ui($('.bmfm-category-list-form'));
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
						$.confirm({
    						closeIcon: function(){
        						return true;
    						},
							title: 'Edit Category - '+$this.closest('tr').find('.bmfm-category-list-name').val(),
							boxWidth: '50%',
							useBootstrap: false,
							content: response.data.html,
							onContentReady: function () {
								Admin.sortable_columns();
							},
    						buttons: {
        						ok:{
            						isHidden: true,
        						}
    						}
						});
						Admin.unblock_ui($('.bmfm-category-list-form'));
					}else if(response.data.error){
						alert(response.data.error);
						Admin.unblock_ui($('.bmfm-category-list-form'));
					}
				}
			});
		},
		add_category_sublist_popup:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			
			$(event.currentTarget).closest('.bmfm-blinds-category-sublist-content').find('tbody').append(bmfm_admin_params.category_sublist_row_html);
			var index = 0,$count=1;
			$('.bmfm-blinds-category-sublist-content').find('tbody tr').each(function(){
				var $loop_this = $(this);
				$loop_this.find('td:eq(0)').text($count+'.');
				$loop_this.find('.bmfm-category-sub-list-name').attr('name', $(this).find('.bmfm-category-sub-list-name').data('name')+'['+index+']');
				$loop_this.find('.bmfm-category-sub-list-desc').attr('name',$(this).find('.bmfm-category-sub-list-desc').data('name')+'['+index+']');
				$loop_this.find('.bmfm-category-sub-list-upload-image-url').attr('name',$(this).find('.bmfm-category-sub-list-upload-image-url').data('name')+'['+index+']');
				$loop_this.find('.bmfm-category-sub-list-sequence').attr('name',$(this).find('.bmfm-category-sub-list-sequence').data('name')+'['+index+']');
				index++;
				$count++;
			});
		},
		remove_category_sublist_popup:function(event){
			event.preventDefault();
			var $action = Admin.remove_row($(event.currentTarget));
			if(true === $action){
			    $(event.currentTarget).closest('tr').remove();
			    var index = 0,$count=1;
			    $('.bmfm-blinds-category-sublist-content').find('tbody tr').each(function(){
				    var $loop_this = $(this);
					$loop_this.find('td:eq(0)').text($count+'.');
				    $loop_this.find('.bmfm-category-sub-list-name').attr('name', $(this).find('.bmfm-category-sub-list-name').data('name')+'['+index+']');
				    $loop_this.find('.bmfm-category-sub-list-desc').attr('name',$(this).find('.bmfm-category-sub-list-desc').data('name')+'['+index+']');
				    $loop_this.find('.bmfm-category-sub-list-upload-image-url').attr('name',$(this).find('.bmfm-category-sub-list-upload-image-url').data('name')+'['+index+']');
				    $loop_this.find('.bmfm-category-sub-list-sequence').attr('name',$(this).find('.bmfm-category-sub-list-sequence').data('name')+'['+index+']');
				    index++;
					$count++;
			    });
		   }
		},
		save_category_sublist:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);

			var error = false;
			$('.bmfm-category-sub-list-name').each(function(){ 
				if('' == $(this).val() || undefined == $(this).val()){
					$(this).css('border','1px solid #ff0000');
					error = true;
				}
			});
			if(true == error){
				return false;
			}

			var data={
				action:'bmfm_save_category_sublist',
				form_data:$('.bmfm-category-sublist-form').serialize(),
				security:bmfm_admin_params.save_category_sublist_nonce,
			};		

			Admin.block_ui($('.bmfm-category-sublist-form'));
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
						alert(response.data.msg);
						$('.bmfm-blinds-category-sublist-content').find('tbody').html(response.data.category_sub_html_content);
						$this.closest('.jconfirm').remove();
						Admin.unblock_ui($('.bmfm-category-sublist-form'));
					}else if(response.data.error){
						alert(response.data.error);
						Admin.unblock_ui($('.bmfm-category-sublist-form'));
					}
				}
			});
		},
		add_accessories_rule:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			$length = $(event.currentTarget).closest('.bmfm-accessories-list-table-content').find('tbody tr').length;
			if($length >=50){
			   Admin.upgrade_premium();
			   return false;
			}
			
			$(event.currentTarget).closest('.bmfm-accessories-list-table-content').find('tbody').append(bmfm_admin_params.accessories_row_html);
			var index = 0,$count = 1;
			$('.bmfm-accessories-list-table-content').find('tbody tr').each(function(){
				var $loop_this = $(this);
				$loop_this.find('td:eq(0)').text($count+'.');
				$loop_this.find('.bmfm-accessories-name').attr('name', $(this).find('.bmfm-accessories-name').data('name')+'['+index+']');
				$loop_this.find('.bmfm-accessories-upload-image-url').attr('name',$(this).find('.bmfm-accessories-upload-image-url').data('name')+'['+index+']');
				$loop_this.find('.bmfm-accessories-price').attr('name',$(this).find('.bmfm-accessories-price').data('name')+'['+index+']');
				$loop_this.find('.bmfm-accessories-desc').attr('name',$(this).find('.bmfm-accessories-desc').data('name')+'['+index+']');
				index++;
				$count++;
			});
			if($('.bmfm-accessories-list-table-content tbody tr').length > 0){
				$('.bmfm-save-accessories-rule').removeClass('bmfm-data-saved');
			}
		},
		remove_accessories_rule:function(event){
		    var $action = Admin.remove_row($(event.currentTarget));
			if(true === $action){
			    $(event.currentTarget).closest('tr').remove();
			    var index = 0;
			    $('.bmfm-accessories-list-table-content').find('tbody tr').each(function(){
				    var $loop_this = $(this);
				    $loop_this.find('.bmfm-accessories-name').attr('name', $(this).find('.bmfm-accessories-name').data('name')+'['+index+']');
				    $loop_this.find('.bmfm-accessories-upload-image-url').attr('name',$(this).find('.bmfm-accessories-upload-image-url').data('name')+'['+index+']');
				    $loop_this.find('.bmfm-accessories-price').attr('name',$(this).find('.bmfm-accessories-price').data('name')+'['+index+']');
				    $loop_this.find('.bmfm-accessories-desc').attr('name',$(this).find('.bmfm-accessories-desc').data('name')+'['+index+']');
				    index++;
			    });
			}
			if($('.bmfm-accessories-list-table-content tbody tr').length <= 0){
				$('.bmfm-save-button').removeClass('bmfm-data-saved');
				$('.bmfm-save-button').addClass('bmfm-data-saved');
			}
		},
		add_dropdown_parameter_rule_popup:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget),index = 0,$component_s_no=1,$drop_down_s_no=1;
			
			if('component' == $('.bmfm-parameter-list-type').val()){
				$this.closest('.bmfm-blinds-component-parameter-popup-content').find('tbody').append(bmfm_admin_params.component_list_dropdown_row_html);
				$('.bmfm-blinds-component-parameter-popup-content').find('tbody tr').each(function(){
					var $loop_this = $(this);
					$loop_this.find('td:eq(0)').text($component_s_no+'.');
					$loop_this.find('.bmfm-component-parameter-name').attr('name', $(this).find('.bmfm-component-parameter-name').data('name')+'['+index+']');
					$loop_this.find('.bmfm-component-parameter-type').attr('name',$(this).find('.bmfm-component-parameter-type').data('name')+'['+index+']');
					$loop_this.find('.bmfm-component-parameter-cost-price').attr('name',$(this).find('.bmfm-component-parameter-cost-price').data('name')+'['+index+']');
					$loop_this.find('.bmfm-component-parameter-markup').attr('name',$(this).find('.bmfm-component-parameter-markup').data('name')+'['+index+']');
					index++;
					$component_s_no++;
				});
			}else if('drop_down' == $('.bmfm-parameter-list-type').val()){
				$this.closest('.bmfm-blinds-dropdown-parameter-popup-content').find('tbody').append(bmfm_admin_params.dropdown_parameter_row_html);
				var index = 0;
				$('.bmfm-blinds-dropdown-parameter-popup-content').find('tbody tr').each(function(){
					var $loop_this = $(this);
					$loop_this.find('td:eq(0)').text($drop_down_s_no+'.');
					$loop_this.find('.bmfm-dropdown-parameter-name').attr('name', $(this).find('.bmfm-dropdown-parameter-name').data('name')+'['+index+']');
					$loop_this.find('.bmfm-dropdown-parameter-desc').attr('name',$(this).find('.bmfm-dropdown-parameter-desc').data('name')+'['+index+']');
					index++;
					$drop_down_s_no++;
				});
		  	}
		},
		remove_parameter_rule_popup:function(event){
		    var $action = Admin.remove_row($(event.currentTarget));
			if(true === $action){
			   $(event.currentTarget).closest('tr').remove(); 
			   var index = 0;
			  if('component' == $('.bmfm-parameter-list-type').val()){
				$('.bmfm-blinds-component-parameter-popup-content').find('tbody tr').each(function(){
					var $loop_this = $(this);
					$loop_this.find('.bmfm-component-parameter-name').attr('name', $(this).find('.bmfm-component-parameter-name').data('name')+'['+index+']');
					$loop_this.find('.bmfm-component-parameter-type').attr('name',$(this).find('.bmfm-component-parameter-type').data('name')+'['+index+']');
					$loop_this.find('.bmfm-component-parameter-cost-price').attr('name',$(this).find('.bmfm-component-parameter-cost-price').data('name')+'['+index+']');
					$loop_this.find('.bmfm-component-parameter-markup').attr('name',$(this).find('.bmfm-component-parameter-markup').data('name')+'['+index+']');
					index++;
				});
			  }else if('drop_down' == $('.bmfm-parameter-list-type').val()){
				    var index = 0;
				    $('.bmfm-blinds-dropdown-parameter-popup-content').find('tbody tr').each(function(){
					    var $loop_this = $(this);
					    $loop_this.find('.bmfm-dropdown-parameter-name').attr('name', $(this).find('.bmfm-dropdown-parameter-name').data('name')+'['+index+']');
					    $loop_this.find('.bmfm-dropdown-parameter-desc').attr('name',$(this).find('.bmfm-dropdown-parameter-desc').data('name')+'['+index+']');
					    index++;
				    });
		  	   }
			}
		},
		save_functionality:function(event,$save_parameter_action = 'no',$save_products_list = 'no'){
			var $this;
			if('yes' == $save_parameter_action){
				$this = $('.bmfm-save-button');
				if('accessories' == $('.bmfm-product-category-type').val()){
					$this = $('.bmfm-save-accessories-parameter-list-rule');
				}
			}else if('yes' == $save_products_list){
			   if('accessories' == $('.bmfm-category-type-value').val()){
			   	$this = $('.bmfm-save-accessories-rule');
			   }else{
				$this = $('.bmfm-product-list-table-content');	
			   }
			}else{
				event.preventDefault();
				$this = $(event.currentTarget);
				if($this.hasClass('bmfm-data-saved')){
					return false;
				}
			}

			if(false == Admin.validate_save_functionality($this)){
				return false;
			}
			
			var data={
				action:'bmfm_save_functionality',
				active_section: $('.bmfm-active-section').val(),
				redirect_fabric_product_section:$('.bmfm-redirect-fabric-product-section').val(),
				saved_parameter_list:$this.data('saved'),
				form_data:$this.closest('form').serialize(),
				security:bmfm_admin_params.product_save_functionality_nonce,
			};
			Admin.block_ui($this.closest('.bmfm-progress-form-wrapper'));
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
						$('.bmfm-setup-section').each(function(){
							$(this).removeClass('bmfm-show-section');
							$(this).removeClass('bmfm-hide-section');
							$(this).hide();
						});
						
						var $selected_list = 1,
							$classes = '.bmfm-product-config-wrapper,.bmfm-parameter-setup-wrapper,.bmfm-save-button-wrapper',
							$section = response.data.section;
						
						if('products_and_parameter_setup' == $('.bmfm-save-parameter-active-section').val()){
							$section = 'products_and_parameter_setup';
							$('.bmfm-save-parameter-active-section').val('');
							$('.bmfm-parameter-type,.bmfm-accesories-parameter-type').change();
						}
						
						if('product_list' == $('.bmfm-save-parameter-active-section').val() && 'blinds' == $('.bmfm-category-type-value').val()){
							$section = 'products_and_parameter_setup';
							$('.bmfm-save-parameter-active-section').val('');
						}
						if('product_list' ==  $('.bmfm-save-parameter-active-section').val() && 'accessories' == $('.bmfm-category-type-value').val()){
							$section = 'product_list';
							$('.bmfm-accessories-list-table-content tbody').html(response.data.accessories_html_row_content);
							$('.bmfm-save-parameter-active-section').val('');
						}
						
						if('yes' ==  $('.bmfm-redirect-fabric-product-section').val() || 'yes' == $save_products_list){
							$section = 'product_list';
							$('.bmfm-redirect-fabric-product-section').val('');
						}
						
						$('.bmfm-active-section').val( $section );
						$('.bmfm-back-button').hide();
						if('product_list' == $section){
							$('.bmfm_add_more_tag').text("To add more than 50 fabrics");
							$selected_list = 2;
							if('accessories' == $('.bmfm-category-type-value').val()){
								$classes = '.bmfm-accessories-list-setup-wrapper,.bmfm-save-button-wrapper';
							}else{
								$classes = '.bmfm-product-list-setup-wrapper,.bmfm-save-button-wrapper';
							}
							$('.bmfm-back-button').show();
							
							if($('.bmfm-hide-frame').length > 0){
								$('.bmfm-hide-frame').each(function(){
									Admin.uncheck_hide_all_frame_checkbox('false',$(this));
								});
							}
							$('.bmfm-save-button').show();
							if('accessories' == $('.bmfm-category-type-value').val() && $('.bmfm-progress-form-wrapper').hasClass('bmfm-saved-progress-form-wrapper')){
								$('.bmfm-save-button').hide();
							}
						}else if('price_setup' == $section ){
							$('.bmfm_add_more_tag').text("To add multiple price tables");
							$selected_list = 3;
							$classes = '.bmfm-price-setup-wrapper,.bmfm-save-button-wrapper';
							$('.bmfm-back-button').show();
							if($('.bmfm-progress-form-wrapper').hasClass('bmfm-saved-progress-form-wrapper')){
								$('.bmfm-save-button').hide();
							}
						}else if('finish_setup' == $section ){
							$selected_list = 4;
							$classes = '.bmfm-finish-setup-wrapper';
							if('' != response.data.view_product_url){
								$('.bmfm-view-dashboard-url').attr('href',response.data.view_product_url);
							}
							
							$('.bmfm-back-button').show();
						}
						
						$('.bmfm-product-setup-wrapper').find('.bmfm-progress-form-wrapper').find('.wc-progress-steps li').each(function(){
							$(this).removeClass('active');
						});
						$('.bmfm-product-setup-wrapper').find('.bmfm-progress-form-wrapper').find('.wc-progress-steps li:nth-child('+$selected_list+')').addClass('active');
						
						$('.bmfm-setup-section').each(function(){
								if($(this).is($classes)){
									$(this).addClass('bmfm-show-section');
									$(this).show();
								}else{
									$(this).addClass('bmfm-hide-section');
									$(this).hide();
								}
						});
						
						if('' != response.data.term_id){
							$('.bmfm-term-id').val(response.data.term_id);
						}
						
						if('' != response.data.product_type_list_id){
						    $('.bmfm-product-type-id').val(response.data.product_type_list_id);
						}
						
						$('.bmfm-price-setup-table-content').find('tbody').html(response.data.product_type_html_row_content);
						
						if('products_and_parameter_setup' == $section && '' != response.data.parameters_list_html_content){
							if('accessories' == $('.bmfm-category-type-value').val()){
								$('.bmfm-accessories-parameter-table-content tbody').html(response.data.parameters_list_html_content);
							}else{
								$('.bmfm-blinds-parameter-table-content tbody').html(response.data.parameters_list_html_content);
							}
							$('.bmfm-product-category-type').change();
						}
						
						 $('html,body').animate({
    scrollTop: $('.bmfm-progress-form-wrapper').offset().top - 200
  }, 400);
						 if(undefined == $('.bmfm-save-button').data('saved') || '' == $('.bmfm-save-button').data('saved')){
							 $('.bmfm-save-button').attr('data-saved','1');
						 	 $('.bmfm-save-button').text(bmfm_admin_params.save_label);
						 }
						
						if('' != response.data.dashboard_url){
						  	 $('.bmfm-dashboard-url').attr('href',response.data.dashboard_url);
						}
						
						if('yes' == $save_parameter_action){
							if('accessories' == $('.bmfm-category-type-value').val()){
								$('.bmfm-save-accessories-parameter-list-rule').removeClass('bmfm-data-saved');
								$('.bmfm-save-accessories-parameter-list-rule').addClass('bmfm-data-saved');
							}
							
							if('blinds' == $('.bmfm-category-type-value').val()){
								$('.bmfm-save-parameter-list-rule').removeClass('bmfm-data-saved');
								$('.bmfm-save-parameter-list-rule').addClass('bmfm-data-saved');
							}
							
							$('.bmfm-save-button').removeClass('bmfm-data-saved');
						}
						$('.bmfm-save-fabric-color-rule').addClass('bmfm-data-saved');
						
					Admin.unblock_ui($this.closest('.bmfm-progress-form-wrapper'));
					}else if(response.data.error){
						alert(response.data.error)
						Admin.unblock_ui($this.closest('.bmfm-progress-form-wrapper'));
					}
				}
			});
			return false;
		},
		save_fabric_color_rule:function(event){
			event.preventDefault();
			if($(this).hasClass('bmfm-data-saved') && 'blinds' != $('.bmfm-category-type-value').val()){
				return false;
			}
			
			$('.bmfm-save-parameter-active-section').val('product_list');
			Admin.save_functionality('false','no','yes');
			$('.bmfm-save-fabric-color-rule').addClass('bmfm-data-saved');
			$('.bmfm-save-button').removeClass('bmfm-data-saved');
		},
		validate_fabric_color_product_on_save(){
		    if($('.bmfm-product-list-table-content tbody tr').length > 0 && 'blinds' == $('.bmfm-category-type-value').val()){
					var error = false;
					$('.bmfm-product-list-table-content tbody tr').each(function(){
						var table_row = $(this);
						table_row.find('td').each(function(){
							var table_data = $(this);
							if('' === table_data.find('.bmfm-fabric-color-name').val()){
								error = true;
							}
							if('' === table_data.find('.bmfm-upload-fabric-color-image-span').find('.bmfm-upload-image-src').attr('src')){
								error = true;
							}	
						});
				  });
				  $('.bmfm-fabric-color-rule-error').hide();
				  if(true == error){
					  $('.bmfm-fabric-color-rule-error').show();
					  return false;
				  }
			}
			return true;
		},
		save_rules_based_on_frame_upload:function(event,offset = 0,$form_data = ''){
			var $form = $('.bmfm-progress-form-wrapper').find('.wc-progress-form-content');
			if('false' != event){
				$form_data = $form.serialize();
			}
			
			Admin.block_ui($form);
			var data={
				action:'bmfm_save_fabric_color_rule',
				offset:offset,
				form_data:$form_data,
				security:bmfm_admin_params.save_fabric_color_rule_nonce,
			};
			
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
						if(100 === response.data.percentage){
							if('' != response.data.html){
								$('.bmfm-product-list-table-content').find('tbody').html(response.data.html);
								if($('.bmfm-hide-frame').length > 0){
									$('.bmfm-hide-frame').each(function(){
										Admin.uncheck_hide_all_frame_checkbox('false',$(this));
									});
								}
							}
							$('.bmfm-save-fabric-color-rule').addClass('bmfm-data-saved');
							$('.bmfm-save-button').removeClass('bmfm-data-saved');
							Admin.unblock_ui($form);
						}else{
			                Admin.save_rules_based_on_frame_upload('false',response.data.offset,response.data.form_data);
						}
					}else if(response.data.error){
						alert(response.data.error)
						Admin.unblock_ui($form);
					}
				}
			});
			return false;
		},
		validate_save_functionality:function($this){
			var $active_section = $('.bmfm-active-section').val(),
				$class_name = '';
			
			if('' == $('.bmfm-category-name').val() || 'undefined' == $('.bmfm-category-name').val()){
				$class_name = '.bmfm-category-name';
			}else{
				$('.bmfm-category-name').css('border','1px solid #8c8f94');
			}
			
			if($('.bmfm-parameter-setup-table-content').find('tbody tr').length > 0 && 'blinds' == $('.bmfm-category-type-value').val()){
				$('.bmfm-parameter-setup-table-content').find('tbody tr').each(function(){
					if('' == $(this).find('.bmfm-parameter-name').val() && !$class_name){
						$class_name = $(this).find('.bmfm-parameter-name');
						$($class_name).css('border','1px solid #FF0000');
						$('html, body').animate({
    						scrollTop: $($class_name).offset().top - 100 
						},400);
						return false;
					}
				});
			}

			if('accessories' == $('.bmfm-category-type-value').val() && 'product_list'== $active_section ){
				if($('.bmfm-accessories-list-table-content').find('tbody tr').length > 0){
					var error = false;
					$('.bmfm-accessories-list-table-content').find('tbody tr').each(function(){
						var table_row = $(this);
						table_row.find('td').each(function(){
							var table_data = $(this);
							if('' === table_data.find('.bmfm-accessories-name').val()){
								error = true;
							}
							if('' === table_data.find('.bmfm-accessories-price').val()){
								error = true;
							}
						});
					});
					$('.bmfm-fabric-color-rule-error').hide();
					if(true == error){
						$('.bmfm-fabric-color-rule-error').show();
						return false;
					}
				}
			}
	
			if($('.bmfm-accessories-parameter-table-content').find('tbody tr').length > 0 && 'accessories' == $('.bmfm-category-type-value').val()){
				$('.bmfm-save-accessories-rule').addClass('bmfm-data-saved');
				$('.bmfm-accessories-parameter-table-content').find('tbody tr').each(function(){
					if('' == $(this).find('.bmfm-accessories-name').val() && !$class_name){
						$class_name = $(this).find('.bmfm-accessories-name');
						$($class_name).css('border','1px solid #FF0000');
						$('html, body').animate({
    						scrollTop: $($class_name).offset().top - 100 
						},400);
						return false;
					}
				});
			}
			
			if($this.hasClass('bmfm-product-list-table-content') && false == Admin.validate_fabric_color_product_on_save()){
			    return false;
			}
			
			if('' != $class_name){
				$($class_name).css('border','1px solid #FF0000');
				$('html, body').animate({
    				scrollTop: $($class_name).offset().top - 100 
				},400);
				return false;
			}
			
			return true;
		},
		save_parameter_list_popup_functionality:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			var data={
				action:'bmfm_save_paramater_list_popup_functionality',
				form_data:$('.bmfm-blinds-dropdown-parameter-popup-form').serialize(),
				security:bmfm_admin_params.save_paramater_list_popup_functionality_nonce,
			};
			Admin.block_ui($('.bmfm-blinds-dropdown-parameter-popup-form'));
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
						alert(response.data.msg);
						$(response.data.class).find('tbody').html(response.data.html_content);
						$this.closest('.jconfirm').remove();
						Admin.unblock_ui($('.bmfm-blinds-dropdown-parameter-popup-form'));
					}else if(response.data.error){
						alert(response.data.error);
						Admin.unblock_ui($('.bmfm-blinds-dropdown-parameter-popup-form'));
					}
				}
			});
			return false;
		},
		toggle_product_category_type:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			$('.bmfm-product-setup-wrapper').find('.bmfm-progress-form-wrapper').find('.wc-progress-steps li:nth-child(3)').show();
			$('.bmfm-blinds-category-type').show();
			$('.bmfm-accessories-category-type').hide();
			$('.bmfm-progress-form-wrapper ol li').css('width','25%');
			$('.bmfm-progress-form-wrapper').removeClass('bmfm-saved-product-accessories');
			$('.bmfm-progress-form-wrapper').removeClass('bmfm-saved-product-blinds');
			if($('.bmfm-progress-form-wrapper').hasClass('bmfm-saved-progress-form-wrapper')){
				$('.bmfm-progress-form-wrapper').addClass('bmfm-saved-product-blinds');
			}
			$('.bmfm-blinds-parameter-table-content').show();
			$('.bmfm-accessories-parameter-table-content').hide();
			
			if('accessories' == $this.val()){
				$('.bmfm-product-setup-wrapper').find('.bmfm-progress-form-wrapper').find('.wc-progress-steps li:nth-child(3)').hide();
				$('.bmfm-blinds-category-type').hide();
				$('.bmfm-accessories-category-type').show();
				$('.bmfm-progress-form-wrapper ol li').css('width','34%');
				if($('.bmfm-progress-form-wrapper').hasClass('bmfm-saved-progress-form-wrapper')){
					$('.bmfm-progress-form-wrapper').addClass('bmfm-saved-product-blinds');
				}
				$('.bmfm-blinds-parameter-table-content').hide();
				$('.bmfm-accessories-parameter-table-content').show();
			}
			$('.bmfm-category-type-value').val($this.val());
			$('.bmfm-parameter-type,.bmfm-accesories-parameter-type').change();
		},
		delete_post_confirm_msg:function(){
			if(!confirm(bmfm_admin_params.confirm_msg)){
				return false;
			}
		},
		fetch_products_selection:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget),
				$country_code = $('.country-list').find('.active').data('country-code');

			$('.bmfm-add-new-product-button').hide();
			$('.bmfm-products-wrapper > div').css('width','18%');
			$('.bmfm-no-supplier-error').hide();
			$('.bmfm-own-products-selection').find('.bmfm-tick-img').hide();
			$('.bmfm-add-your-own-products-wrapper').hide();
			$('.bmfm-import-products-choice').hide();
			$('.bmfm-uk-fetch-products-wrapper').show();
			if($this.hasClass('selected')){
				$this.removeClass('selected');
				$this.find('.bmfm-tick-img').hide();
				$('.bmfm-fetch-products-selection-wrapper').find('.bmfm-chosen-product-selection-type').val('');
			}else{
				$('.bmfm-own-products-selection').removeClass('selected');
				$this.addClass('selected');
				$this.find('.bmfm-tick-img').show();
				$('.bmfm-fetch-products-selection-wrapper').find('.bmfm-chosen-product-selection-type').val($this.data('type'));
			}
			$('.bmfm-country').change();
			Admin.set_import_steps();
		},
		own_products_selection:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget),
				$country_code = $('.country-list').find('.active').data('country-code');
			
			$('.bmfm-add-new-product-dashboard').val('');
			$('.bmfm-fetch-products-selection').find('.bmfm-tick-img').hide();
			$('.bmfm-products-wrapper').hide();
			$('.bmfm-no-products-msg').hide();
			$('.bmfm-uk-fetch-products-wrapper').hide();
			if($this.hasClass('selected')){
				$this.removeClass('selected');
				$this.find('.bmfm-tick-img').hide();
				$('.bmfm-import-products-choice').hide();
				$('.bmfm-add-your-own-products-wrapper').hide();
				$('.bmfm-import-button-wrapper').hide();
			}else{
				$('.bmfm-fetch-products-selection').removeClass('selected');
				$this.addClass('selected');
				$this.find('.bmfm-tick-img').show();
				$('.bmfm-import-products-choice').css('display','flex');			
				if($('.bmfm-import-library-button').hasClass('selected')){
					$('.bmfm-add-your-own-products-wrapper').show();
				}
			}			
			$('.bmfm-fetch-products-selection-wrapper').find('.bmfm-chosen-product-selection-type').val($this.data('type'));
			$('.bmfm-hide-suppliers').hide();
			$('.bmfm-contact-us-wrapper').hide();
			$('.bmfm-contact-us-submit-wrapper').hide();
			
			var found = false;
			$('.bmfm-add-your-own-products-wrapper > div').each(function(){
				if(undefined != $(this).data('name')){
					if($(this).hasClass('selected')){
						found = true;
					}
				}
		    });
			if(false == found){
				$('.bmfm-import-button-wrapper').hide();
			}else if($this.hasClass('selected')){
				$('.bmfm-import-button-wrapper').show();
			}
			
			$('.bmfm-add-new-product-button').show();
			$('.bmfm-no-supplier-error').hide();
			Admin.set_import_steps();
		},
		import_library_selection:function(event){
			var $this = $(event.currentTarget);
			if($this.hasClass('selected')){
					$this.removeClass('selected');
					$this.find('.bmfm-tick-img').hide();
					$('.bmfm-add-your-own-products-wrapper').hide();
					$('.bmfm-import-button-wrapper').hide();
				}else{
					$this.addClass('selected');
					$this.find('.bmfm-tick-img').show();
					$('.bmfm-add-your-own-products-wrapper').show();
					$('.bmfm-add-your-own-products-wrapper').css('display','block');
					var found = false;
					$('.bmfm-add-your-own-products-wrapper > div').each(function(){
						if(undefined != $(this).data('name')){
							if($(this).hasClass('selected')){
								found = true;
							}
						}
		    		});
					if(false == found){
						$('.bmfm-import-button-wrapper').hide();
					}else{
						$('.bmfm-import-button-wrapper').show();
					}
				}
			Admin.set_import_steps();
		},
		toggle_country_selection:function(event){
			event.preventDefault();
			var $country_code = $('.country-list').find('.active').data('country-code');
			$('input[name="bmfm_settings_data[chosen_country]"]').val($country_code);
			$('.bmfm-hide-suppliers').hide();
			$('.bmfm-products-wrapper').hide();
			$('.bmfm-contact-us-wrapper').hide();
			$('.bmfm-contact-us-submit-wrapper').hide();
			$('.bmfm-import-button-wrapper').hide();
			$('.bmfm-no-supplier-error').hide();
			if(!$('.bmfm-fetch-products-selection-wrapper').find('.selected').length || $('.bmfm-own-products-selection.selected').length > 0){
				return false;
			}
			
			var $class = '',supplier = '';
			$('.bmfm-no-supplier-error').hide();
			if('gb' == $country_code || 'ie' == $country_code){
				$('.bmfm-uk-suppliers-wrapper').show();
				$('.bmfm-uk-suppliers-wrapper').css('display','block');
				$class = '.bmfm-uk-suppliers-wrapper > div';
				$($class).each(function(){
					if($(this).hasClass('selected')){
						supplier = $(this).data('supplier');
					}
				});
			}
// 			else if('au' == $country_code || 'nz' == $country_code){
// 				$('.bmfm-aus-suppliers-wrapper').show();
// 				$('.bmfm-aus-suppliers-wrapper').css('display','flex');
// 				$class = '.bmfm-aus-suppliers-wrapper > div';
// 			}
			else if('us' == $country_code || 'ca' == $country_code){
				$('.bmfm-us-suppliers-wrapper').show();
				$('.bmfm-us-suppliers-wrapper').css('display','block');
				$class = '.bmfm-us-suppliers-wrapper > div';
			}
			else{
				$('.bmfm-contact-us-wrapper').css('display','flex');
				$('.bmfm-contact-us-submit-wrapper').show();
				$('.bmfm-no-supplier-error').show();
			}
			
			$('.bmfm-no-products-msg').hide();
			if($($class).hasClass('selected')){
				$('.bmfm-products-wrapper').hide();
				$('.bmfm-contact-us-wrapper').hide();
				$('.bmfm-contact-us-submit-wrapper').hide();
				var found = false;
				$('.bmfm-products-wrapper > div').each(function(){
				if(undefined != $(this).data('name')){
						if($(this).hasClass('selected')){
							found = true;
						}
					}
		    	});
				if('' != supplier){
					$('.bmfm-'+supplier+'-supplier').css('display','block');
			    }
					
				if(('gb' == $country_code || 'ie' == $country_code) && ('excel' == supplier || 'eclipse' == supplier || 'louvolite' == supplier)){
					$('.bmfm-no-products-msg').show();
				}
					
				if(('us' == $country_code || 'ca' == $country_code) && ('graber' == supplier || 'alta' == supplier || 'norman' == supplier)){
					$('.bmfm-no-products-msg').show();
				}
				
				if(false == found){
					$('.bmfm-import-button-wrapper').hide();
				}else{
					$('.bmfm-import-button-wrapper').show();
				}
				if('others' == $($class+'.selected').data('supplier')){
					$('.bmfm-products-wrapper').hide();
					$('.bmfm-contact-us-wrapper').show();
					$('.bmfm-contact-us-wrapper').css('display','flex');
					$('.bmfm-contact-us-submit-wrapper').show();
					$('.bmfm-import-button-wrapper').hide();
				}
			}
			Admin.set_import_steps();
		},
		add_new_product_url:function(event){
			event.preventDefault();
			var found = false,$this = $(this);
			$('.bmfm-add-your-own-products-wrapper > div').each(function(){
				if(undefined != $(this).data('name')){
					if($(this).hasClass('selected')){
						found = true;
					}
				}
			});
			
			if(true == found){
				$.confirm({
					onContentReady: function () {
						$('.jconfirm').addClass('bmfm-blinds-js-wrapper');
					},
    				title: '',
					type: 'green',
					typeAnimated: true,
    				content: 'Are you sure you want to create the new product?',
					boxWidth: '30%',
					useBootstrap: false,
					buttons: {
        			ok:{
            			action: function(){
							$('.bmfm-import-button-wrapper').hide();
                			var $country_code = $('.country-list').find('.active').data('country-code');
							Admin.block_ui($('.bmfm-welcome-contents-section'));
							window.location.href = bmfm_admin_params.add_product_url+'&country='+$country_code;
            			}
        			  },
					cancel:{
						action: function(){
							$('.bmfm-import-button-wrapper').show();
							Admin.show_or_hide_bg_color($this);
							$this.removeClass('selected');
            			}	
					 }	
    				}
				});
			}else{
				var $country_code = $('.country-list').find('.active').data('country-code');
				Admin.block_ui($('.bmfm-welcome-contents-section'));
				setTimeout(function () {
					Admin.unblock_ui($('.bmfm-welcome-contents-section'));
				}, 2500);
				window.location.href = bmfm_admin_params.add_product_url+'&country='+$country_code;
			}			
		},
		toggle_supplier:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			$('.bmfm-no-products-msg').hide();
			if($this.hasClass('selected')){
				Admin.show_or_hide_bg_color($this);
				$this.removeClass('selected');
				$this.closest('.bmfm-suppliers-section').find('.bmfm-chosen-supplier').val('');
				$('.bmfm-products-wrapper').hide();
				$('.bmfm-contact-us-wrapper').hide();
				$('.bmfm-contact-us-submit-wrapper').hide();
				$('.bmfm-import-button-wrapper').hide();
			}else{
				$this.closest('.bmfm-suppliers-section').find('.selected').each(function(){
					Admin.show_or_hide_bg_color($(this));
					$(this).removeClass('selected');
				});
				Admin.show_or_hide_bg_color($(this));
				$this.addClass('selected');
				$this.closest('.bmfm-suppliers-section').find('.bmfm-chosen-supplier').val($this.data('supplier'));
				$('.bmfm-products-wrapper').hide();
				var supplier = $this.data('supplier');
				$('.bmfm-'+supplier+'-supplier').css('display','block');
				$('.bmfm-contact-us-wrapper').hide();
				$('.bmfm-contact-us-submit-wrapper').hide();
				var found = false;
				$('.bmfm-products-wrapper > div').each(function(){
				if(undefined != $(this).data('name')){
						if($(this).hasClass('selected')){
							found = true;
						}
					}
		    	});
				if(false == found){
					$('.bmfm-import-button-wrapper').hide();
				}else{
					$('.bmfm-import-button-wrapper').show();
				}
				if('others' == $this.data('supplier')){
					$('.bmfm-products-wrapper').hide();
					$('.bmfm-contact-us-wrapper').show();
					$('.bmfm-contact-us-wrapper').css('display','flex');
					$('.bmfm-contact-us-submit-wrapper').show();
					$('.bmfm-import-button-wrapper').hide();
				}
				
				if('excel' == $this.data('supplier') || 'eclipse' == $this.data('supplier') || 'louvolite' == $this.data('supplier')){
					$('.bmfm-no-products-msg').show();
					$('.bmfm-import-button-wrapper').hide();
				}
				
				if('alta' == $this.data('supplier') || 'graber' == $this.data('supplier') || 'norman' == $this.data('supplier')){
					$('.bmfm-no-products-msg').show();
					$('.bmfm-import-button-wrapper').hide();
				}
			}
			Admin.set_import_steps();
		},
		toggle_products_wrapper:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			if($this.hasClass('selected')){
				Admin.show_or_hide_bg_color($this);
				$this.removeClass('selected');
			}else{
				var count = 1,validate = false;
				$this.closest('.bmfm-products-wrapper').find('.selected').each(function(){		
					if( 2 == count){
						validate = true;
					}	
					count++;
				});

				if(true == validate && !$this.hasClass('selected') && !$this.hasClass('bmfm-add-new-product-button')){
					Admin.upgrade_premium();
					return false;
				}
				Admin.show_or_hide_bg_color($this);
				$this.addClass('selected');
			}
			var $data = [];
			$this.closest('.bmfm-products-wrapper').find('.selected').each(function(){
				$data.push($(this).data('name'));
			});
			$this.closest('.bmfm-products-wrapper').find('.bmfm-chosen-products').val($data.join(','));
			if($this.hasClass('bmfm-add-new-product-button')){
				$('.bmfm-import-button-wrapper').hide();
			}
			
			var found = false;
			$('.bmfm-products-wrapper > div').each(function(){
				if(undefined != $(this).data('name')){
					if($(this).hasClass('selected') ){
						found = true;
					}
				}
		    });
			if(false == found){
				$('.bmfm-import-button-wrapper').hide();
			}else{
				$('.bmfm-import-button-wrapper').show();
			}
			Admin.set_import_steps();
		},
		toggle_add_your_own_products_wrapper:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			if($this.hasClass('selected')){
				Admin.show_or_hide_bg_color($this);
				$this.removeClass('selected');
			}else{
				var count = 1,validate = false;
				$this.closest('.bmfm-add-your-own-products-wrapper').find('.selected').each(function(){		
					if( 2 == count){
						validate = true;
					}	
					count++;
				});

				if(true == validate && !$this.hasClass('selected') && !$this.hasClass('bmfm-add-new-product-button')){
					Admin.upgrade_premium();
					return false;
				}
				Admin.show_or_hide_bg_color($this);
				$this.addClass('selected');
			}
			var $data = [];
			$this.closest('.bmfm-add-your-own-products-wrapper').find('.selected').each(function(){
				$data.push($(this).data('name'));
			});
			$this.closest('.bmfm-add-your-own-products-wrapper').find('.bmfm-add-your-own-products').val($data.join(','));
			if($this.hasClass('bmfm-add-new-product-button')){
				$('.bmfm-import-button-wrapper').hide();
			}
			
			var found = false;
			$('.bmfm-add-your-own-products-wrapper > div').each(function(){
				if(undefined != $(this).data('name')){
					if($(this).hasClass('selected')){
						found = true;
					}
				}
		    });
			if(false == found){
				$('.bmfm-import-button-wrapper').hide();
			}else{
				$('.bmfm-import-button-wrapper').show();
			}
			Admin.set_import_steps();
		},
		show_or_hide_bg_color:function($this){
			if($this.hasClass('selected')){
// 				$this.css({'background-color': 'unset','color': 'unset'});
// 				$this.hover(function(){
// 					$(this).css({'background-color': 'unset','color': 'unset'});
// 				});
				$this.find('.bmfm-tick-img').hide();
			}else{
// 				$this.css({'background-color': '#00c2ff','color': '#fff'});
// 				$this.hover(function(){
// 					$(this).css({'background-color': '#00c2ff','color': '#fff'});
// 				});
				$this.find('.bmfm-tick-img').show();
			}
		},
		toggle_country_arrow:function(){
			$('.arrow').click();
		},
		import_button_action:function(event,iterate = 'false',offset = 0,product_data = ""){
		    var $this;
		    if(false != event){
		        $this = $(event.currentTarget);
		        event.preventDefault();
		        $.confirm({
    				title: false,
    				type:'green',
    				//isHidden: true,
    				content: '<div class="bmfm-confirm-content">'+bmfm_admin_params.import_confirm_msg+'<div>',
					boxWidth: '35%',
					useBootstrap: false,
					closeIcon: true,
					onContentReady: function () {
					    $('.jconfirm').addClass('bmfm-confirm-content-wrapper bmfm-blinds-js-wrapper');
				    },
					buttons: {
				    Yes:{
						btnClass: 'bmfm-done',
						action: function(){
 							 Admin.import_functionality(event,iterate ,offset ,product_data );
 							 return true;
            		      }
				    },
					No:{
					   action: function(){
 							return true;
            		    }
				      } 
					},
				});
		    }else{
		        $this = $('.bmfm-import-button-action');
		    }
		},
		import_functionality:function(event,iterate,offset,product_data){
		    var data={
				action:'bmfm_import_button_action',
				iterate:iterate,
				offset:offset,
				product_data:product_data,
				form_data:$('.bmfm-progress-form-wrapper').find('.wc-progress-form-content').serialize(),
				security:bmfm_admin_params.import_button_action_nonce,
			};
			var percentage = offset * 20;
			if(percentage == 0){
				percentage = 10;
			}
			if('false' == iterate){
			    Admin.block_ui($('.bmfm-welcome-contents-section'), percentage);
			}else{
				if(percentage == 100){
					$("#bm-progressbar").css({"border-top-right-radius": "20px", "border-bottom-right-radius": "20px"});

				}
				$("#bm-progressbar").css("width",percentage+'%');
				$("#bm-progressbar").text(percentage+'%');
			}
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
					    if(100 === response.data.percentage){
							var content = '<div class="bmfm-import-success-info-wrapper">The product is imported successfully with 50 fabrics, <br/>1 price group and 1 default filters.</div>';
							if(response.data.category_count > 1){
								content = '<div class="bmfm-import-success-info-wrapper">2 products are imported successfully each with 50 fabrics, <br/>1 price group and 1 default filters.</div>';
							}
					        $.confirm({
    							title: false,
    							isHidden: true,
    							content: content,
								boxWidth: '30%',
								useBootstrap: false,
								closeIcon: true,
    							theme: 'bmfm-import-success-info-popup-container',
								buttons: {
				    				Done:{
									btnClass: 'bmfm-done',
										action: function(){
// 											if('' != response.data.fabric_list_url){
// 												window.location.href= response.data.fabric_list_url;
// 											}else{
												window.location.href= response.data.dashboard_url;
											//}
            							}
				    				}
								}
							});
					        Admin.unblock_ui($('.bmfm-welcome-contents-section'));
					    }else{
					        Admin.import_functionality(false,'true',response.data.offset,response.data.blinds_product_data);
					    }
					}else if(response.data.error){
						alert(response.data.error);
						Admin.unblock_ui($('.bmfm-welcome-contents-section'));
					}
				},
				error: function(xhr, status, error) {
                    Admin.unblock_ui($('.bmfm-welcome-contents-section'));
                    window.location.reload();
                }
			});
			return false;
		},
		contact_us_upload_action:function(event){
			event.preventDefault();
			$('.bmfm-contact-us-file').click();
		},
		add_upload_file:function(event){
			var file_name = event.target.files[0].name;
			$('.bmfm-contact-us-file-name').text(file_name);
		},
		contact_us_action:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget),
				file = $(".bmfm-contact-us-file")[0].files[0];
			
			if('' == $('.bmfm-contact-us-name input').val() && '' == $('.bmfm-contact-us-email input').val() && '' == $('.bmfm-contact-us-ph-no input').val()){
				$('.bmfm-contact-us-name input').css('border','1px solid red');
				$('.bmfm-contact-us-email input').css('border','1px solid red');
				$('.bmfm-contact-us-ph-no input').css('border','1px solid red');
				$('html,body').animate({ scrollTop: $('.bmfm-contact-us-name input').offset().top - 200 }, 400);
				return false;
			}
			
			if('' == $('.bmfm-contact-us-name input').val()){
				$('.bmfm-contact-us-name input').css('border','1px solid red');
				$('html,body').animate({ scrollTop: $('.bmfm-contact-us-name input').offset().top - 200 }, 400);
				return false;
			}
			
			if('' == $('.bmfm-contact-us-email input').val()){
				$('.bmfm-contact-us-email input').css('border','1px solid red');
				$('html,body').animate({ scrollTop: $('.bmfm-contact-us-email input').offset().top - 200 }, 400);
				return false;
			}
			
			if('' == $('.bmfm-contact-us-ph-no input').val()){
				$('.bmfm-contact-us-ph-no input').css('border','1px solid red');
				$('html,body').animate({ scrollTop: $('.bmfm-contact-us-ph-no input').offset().top - 200 }, 400);
				return false;
			}
			
			$('.bmfm-contact-us-name input').css('border','1px solid #8c8f94');
			$('.bmfm-contact-us-email input').css('border','1px solid #8c8f94');
			$('.bmfm-contact-us-ph-no input').css('border','1px solid #8c8f94');
			
			var formdata = new FormData();
			formdata.append('form_data',$this.closest('.bmfm-progress-form-wrapper').find('.wc-progress-form-content').serialize());
			formdata.append('file', file);
			formdata.append('action', 'bmfm_contact_us_action');  
			formdata.append('security', bmfm_admin_params.contact_us_action_nonce);

			Admin.block_ui($('.bmfm-welcome-contents-section'));
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: formdata,
				type: 'POST',
				contentType: false,
				processData: false,
				success: function( response ) {
					if(response.data.success){
						$.alert({
    						title: 'Success',
							type: 'green',
							typeAnimated: true,
    						content: response.data.msg,
							boxWidth: '30%',
							useBootstrap: false,
						});
						Admin.unblock_ui($('.bmfm-welcome-contents-section'));
					}else if(response.data.error){
						$.alert({
    						title: 'Error',
							type: 'red',
							typeAnimated: true,
    						content: response.data.error,
							boxWidth: '30%',
							useBootstrap: false,
						});
						Admin.unblock_ui($('.bmfm-welcome-contents-section'));
					}
				}
			});
			return false;
		},
		add_product_slug:function(){
			var category_name = $(this).val();
			category_name = category_name.toLowerCase();
			category_name = category_name.replace(/\s+/g,'-');
			$('.bmfm-category-slug').val(category_name);
		},
		upgrade_premium:function(){
			$.confirm({
    			title: false,
    			isHidden: true,
    			content: '<div class="bmfm-upgrade-premium-popup-wrapper">'+bmfm_admin_params.upgrade_premium_html+'</div>',
				boxWidth: '65%',
				useBootstrap: false,
				closeIcon: true,
    			theme: 'bmfm-upgrade-premium-popup-container',
				buttons: {
				    ok:{
				        isHidden: true,
				    }
				}
			});
			return false;
		},
		save_category_selection_dashboard:function(event){
		    event.preventDefault();
			var $this = $(event.currentTarget);
			Admin.block_ui($this.closest('td'));
			var data = {
			    action:'bmfm_save_category_selection_dashboard',
			    form_data:$('.bmfm-products-list-form').serialize(),
			    post_id:$this.closest('td').find('.bmfm-fabric-color-id').val(),
				security:bmfm_admin_params.save_category_selection_dashboard_nonce,
			};
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
				 if(response.data.success){
					Admin.unblock_ui($this.closest('td'));
				 }else if(response.data.error){
					alert(response.data.error);
					Admin.unblock_ui($this.closest('td'));
				 }
			  }
			});
			return false;
		},
		edit_product_link:function(event){
		    event.preventDefault();
		    window.location.href = bmfm_admin_params.edit_product_url;
		    return false;
		},
		toggle_parameter_type:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget),
				table_row = $this.closest('tr');
				table_row.find('.bmfm-edit-parameter-list').removeClass('bmfm-data-saved');	
				table_row.find('.bmfm-edit-accessories-parameter-list').removeClass('bmfm-data-saved');	
				if('blinds' == $('.bmfm-product-category-type').val()){
					if('numeric_x' == $this.val() && $this.hasClass('bmfm-parameter-type')){
						table_row.find('.bmfm-parameter-name').attr('placeholder','Width');
					}else if('numeric_y' == $this.val() && $this.hasClass('bmfm-parameter-type')){
						table_row.find('.bmfm-parameter-name').attr('placeholder','Drop');
					}else if('drop_down' == $this.val() && $this.hasClass('bmfm-parameter-type')){
						table_row.find('.bmfm-parameter-name').attr('placeholder','Drop Down Name');
						table_row.find('.bmfm-edit-parameter-list').removeClass('bmfm-hide');
						if('drop_down' != $this.data('selected_type')){
							table_row.find('.bmfm-edit-parameter-list').addClass('bmfm-data-saved');
						}
					}else if('text' == $this.val() && $this.hasClass('bmfm-parameter-type')){
						table_row.find('.bmfm-parameter-name').attr('placeholder','Text Value');
						table_row.find('.bmfm-edit-parameter-list').addClass('bmfm-hide');
					}else if('component' == $this.val() && $this.hasClass('bmfm-parameter-type')){
						table_row.find('.bmfm-parameter-name').attr('placeholder','Component Name');
						table_row.find('.bmfm-edit-parameter-list').removeClass('bmfm-hide');
						if('component' != $this.data('selected_type')){
							table_row.find('.bmfm-edit-parameter-list').addClass('bmfm-data-saved');
						}
					}
				}else{
					if('drop_down' == $this.val() && $this.hasClass('bmfm-accesories-parameter-type')){
						table_row.find('.bmfm-accessories-name').attr('placeholder','Drop Down Name');
						table_row.find('.bmfm-edit-accessories-parameter-list').removeClass('bmfm-hide');
						if('drop_down' != $this.data('selected_type')){
							table_row.find('.bmfm-edit-accessories-parameter-list').addClass('bmfm-data-saved');
						}
					}else if('text' == $this.val() && $this.hasClass('bmfm-accesories-parameter-type')){
						table_row.find('.bmfm-accessories-name').attr('placeholder','Text Value');
						table_row.find('.bmfm-edit-accessories-parameter-list').addClass('bmfm-hide');
					}else if('component' == $this.val() && $this.hasClass('bmfm-accesories-parameter-type')){
						table_row.find('.bmfm-accessories-name').attr('placeholder','Component Name');
						table_row.find('.bmfm-edit-accessories-parameter-list').removeClass('bmfm-hide');
						if('component' != $this.data('selected_type')){
							table_row.find('.bmfm-edit-accessories-parameter-list').addClass('bmfm-data-saved');
						}
					}
				}
		},
		remove_row:function($this){
		    if(!confirm(bmfm_admin_params.confirm_msg)){
		        return false;
		    }
		    
		    var table = $this.closest('table');
		    
		    if(!$this.data('post_id')){
		        $this.closest('tr').remove();
		        return true;
		    }

			var data = {
			    action:'bmfm_remove_row_action',
			    post_id:$this.data('post_id'),
				security:bmfm_admin_params.remove_row_nonce,
			};
			Admin.block_ui(table);
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
				  if(response.data.success){
				    Admin.unblock_ui(table);
				  }else if(response.data.error){
					alert(response.data.error);
					Admin.unblock_ui(table);
				  }
			    }
			});
			return true;
		},
		upload_image_custom_popup:function(event){
		    event.preventDefault();
		    var $this = $(event.currentTarget);
		    var data={
				action:'bmfm_upload_image_custom_popup',
				post_id:$this.closest('tr').find().val(),
				security:bmfm_admin_params.upload_image_custom_popup_nonce,
			};
			Admin.block_ui($this);
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
				  if(response.data.success){ 
				      $.alert({
    						title: 'Select frame',
							type: 'green',
							typeAnimated: true,
    						content: response.data.html,
							boxWidth: '50%',
							useBootstrap: false,
						  	closeIcon: true,
							buttons: {
							    preview_image:{
                                    text: 'Sample Preview',
                                    action: function () {
										var $img_url = $('.bmfm-frame-custom-upload.selected').find('.bmfm-custom-popup-img').data('img_url');
                                    	if('' == $img_url || undefined == $img_url){
                                        	$('.bmfm-frame-error-msg').show();
                                       	 	return false;
                                    	}
										$('.bmfm-frame-error-msg').hide();
										var $sample_preview_url = $('.bmfm-frame-custom-upload.selected').find('.bmfm-custom-popup-img').data('sample_preview_url');
                                         $.alert({
    						                title: 'Sample Preview',
							                type: 'blue',
											boxWidth: '20%',
							                typeAnimated: true,
    						                content: '<div style="text-align:center;"><img src="'+$sample_preview_url+'" width="250" style="border: 2px solid #eee;"></div>',
							                useBootstrap: false,
							                buttons: {
							                    ok: {
													btnClass: 'btn-blue',
												}
							                }
                                        });	
                                        return false;
                                    }
                                },
                                ok:{
									text: 'Save',
									btnClass: 'btn-blue',
									action: function () {
                                    var $img_url = $('.bmfm-frame-custom-upload.selected').find('.bmfm-custom-popup-img').data('img_url');
									var $selected_product_name = $('.bmfm-frame-custom-upload.selected').find('.bmfm-custom-popup-img').data('product_name');
                                    if('' == $img_url || undefined == $img_url){
                                        $('.bmfm-frame-error-msg').show();
                                        return false;
                                    }
										
									$_selected_image = $img_url;
									$_product_name = $selected_product_name;
									
                                    $('.bmfm-frame-error-msg').hide();
                                    $.confirm({							
				                            title: 'Frame Upload',							
				                            columnClass: 'col-md-4 col-md-offset-4',							
				                            content: 'Are you sure you want to change the frame? It will take time to update this on all the fabric/color options.',
				                            type: 'red',							
				                            icon: 'fa fa-warning',							
				                            typeAnimated: true,							
				                            boxWidth: '30%',							
				                            useBootstrap: false,							
				                            closeIcon: true,							
				                            buttons: {	
					                            Yes: {
					                                btnClass: 'btn-primary',
					                                action:function(){
					                                    if(false == Admin.validate_fabric_color_product_on_save()){
				                                            return true;
				                                        }
														
				                                        var $img_url = $_selected_image;
									                    var $selected_product_name = $_product_name;
					                                    $('.bmfm-product-list-table-content tbody tr').each(function(){
                                                            var span_obj = $(this).find('td:eq(3)').find('.bmfm-upload-custom-image-span');
                                                                $(this).find('td:eq(3)').each(function(){
                                                                $(this).find('.bmfm-upload-image-src').attr('src',$img_url);
                                                                $(this).find('.bmfm-frame-upload-image-url').val($img_url);
                                                                $(this).find('.bmfm-upload-image-src').show();
                                                                $(this).find('.bmfm-upload-img-a').hide();
                                                                $(this).find('.bmfm-remove-image').show();
										            	        $(this).find('.bmfm-frame-upload-product-name').val($selected_product_name);
                                                            });
                                                        });
                                                        Admin.save_rules_based_on_frame_upload('true');
						                                return true;	
					                                },
					                            },
					                            No: {
					                                action:function(){
					                                    var $img_url = $('.bmfm-saved-frame-data').data('frame_url');
									                    var $selected_product_name = $('.bmfm-saved-frame-data').data('product_name');
					                                    $('.bmfm-product-list-table-content tbody tr').each(function(){
                                                            var span_obj = $(this).find('td:eq(3)').find('.bmfm-upload-custom-image-span');
                                                                $(this).find('td:eq(3)').each(function(){
                                                                $(this).find('.bmfm-upload-image-src').attr('src',$img_url);
                                                                $(this).find('.bmfm-frame-upload-image-url').val($img_url);
                                                                $(this).find('.bmfm-upload-image-src').show();
                                                                $(this).find('.bmfm-upload-img-a').hide();
                                                                $(this).find('.bmfm-remove-image').show();
										            	        $(this).find('.bmfm-frame-upload-product-name').val($selected_product_name);
                                                            });
                                                        });
						                                return true;	
					                                },
					                            },	
				                            }						
			                        });
                                },
							  }
                            }
					  });
				     Admin.unblock_ui($this);
				  }
				}
			});	
			return false;
		},
		custom_popup_img_selection:function(event){
		    event.preventDefault();
		    var $this = $(event.currentTarget);
		    $('.bmfm-frame-custom-upload').each(function(){
		           $(this).find('.dashicons').hide();
		           $(this).removeClass('selected');
		    });
		    $this.closest('.bmfm-frame-custom-upload').find('.dashicons').show();
		    $this.closest('.bmfm-frame-custom-upload').addClass('selected');
		},
		click_back_button:function(event){
		    event.preventDefault();
		    
		    var $section = $('.bmfm-active-section').val(),
		    $changed_section ='products_and_parameter_setup',
		    $this = $(event.currentTarget);
            $this.show();
			if($('.bmfm-progress-form-wrapper').hasClass('bmfm-saved-progress-form-wrapper')){
				$('.bmfm-save-button').show();
			}
			
			if('product_list' == $section){
				$('.bmfm_add_more_tag').text("To add more than 2 products");
				$selected_list = 1;
				$classes = '.bmfm-product-config-wrapper,.bmfm-parameter-setup-wrapper,.bmfm-save-button-wrapper';
				$changed_section = 'products_and_parameter_setup';
				$this.hide();
			}else if('price_setup' == $section ){
				$('.bmfm_add_more_tag').text("To add more than 50 fabrics");
				$selected_list = 2;
				if('accessories' == $('.bmfm-category-type-value').val()){
					$classes = '.bmfm-accessories-list-setup-wrapper,.bmfm-save-button-wrapper';
				}else{
					$classes = '.bmfm-product-list-setup-wrapper,.bmfm-save-button-wrapper';
				}
				$changed_section = 'product_list';
			}else if('finish_setup' == $section ){
			    if('accessories' == $('.bmfm-category-type-value').val()){
			        $selected_list = 2;
					$classes = '.bmfm-accessories-list-setup-wrapper,.bmfm-save-button-wrapper';
					$changed_section = 'product_list';
				}else{
					$selected_list = 3;
				    $classes = '.bmfm-price-setup-wrapper,.bmfm-save-button-wrapper';
				    $changed_section = 'price_setup';
				}
			}
			
			$('.bmfm-product-setup-wrapper').find('.bmfm-progress-form-wrapper').find('.wc-progress-steps li').each(function(){
				$(this).removeClass('active');
			});
			
			$('.bmfm-active-section').val($changed_section);
			$('.bmfm-product-setup-wrapper').find('.bmfm-progress-form-wrapper').find('.wc-progress-steps li:nth-child('+$selected_list+')').addClass('active');
						
			$('.bmfm-setup-section').each(function(){
				if($(this).is($classes)){
					$(this).addClass('bmfm-show-section');
					$(this).show();
				}else{
					$(this).addClass('bmfm-hide-section');
					$(this).hide();
				}
			});
		},
		hide_frames_checkbox:function(event){
		    event.preventDefault();
		    var $this = $(event.currentTarget);
		    if($this.is(':checked')){
		        $('.bmfm-hide-frame').each(function(){
		            $(this).prop('checked',true);
					$(this).closest('tr').find(".bmfm-choice-frame").css({"opacity": ".4", "pointer-events": "none"});
				 });
		    }else{
		        $('.bmfm-hide-frame').each(function(){
		            $(this).prop('checked',false);
					$(this).closest('tr').find(".bmfm-choice-frame").css({"opacity": "unset", "pointer-events": "unset"});		
		        });
		    }
		},
		uncheck_hide_all_frame_checkbox:function(event,$this_obj = 'false'){
			if('false' == event){
				var $this = $this_obj;
			}else{
				event.preventDefault();
				var $this = $(event.currentTarget);
			}
						
			var $count;
		    if(!($this.is(':checked'))){
				if($('.bmfm-hide-frames-checkbox').is(':checked')){
					$('.bmfm-hide-frames-checkbox').prop('checked',false);										       
				}
				$this.closest('tr').find(".bmfm-choice-frame").css({"opacity": "unset", "pointer-events": "unset"});
			}
			else{
				$count=0;
				$('.bmfm-hide-frame').each(function(){ 
					if($(this).is(':checked')){
						$count++;					
					}
				});
				if($count>=$('.bmfm-hide-frame').length){
					$('.bmfm-hide-frames-checkbox').prop('checked',true);	
				}
				$this.closest('tr').find(".bmfm-choice-frame").css({"opacity": ".4", "pointer-events": "none"});
			}
			
			if ('false' != event && true == Admin.validate_save_fabric_color_and_accessories_data_row($this)){
				$this.prop('checked',false);	
				return false;
			}
			if ('false' != event){
				Admin.save_fabric_color_and_accessories_data_row($this);		
			}
		},
		unit_measurement:function(event){
		    event.preventDefault();
		    var $this = $(event.currentTarget);
		    window.location.href=document.location.href+'&bmfm_unit='+$this.val();
		},
		sortable_columns:function(){
		    $( '.bmfm-blinds-category-list-content tbody,.bmfm-blinds-category-sublist-content tbody' ).sortable({
			    items: 'tr',
			    cursor: 'move',
			    axis: 'y',
			    scrollSensitivity: 40,
			    forcePlaceholderSize: true,
			    helper: 'clone',
			    opacity: 0.65,
			    placeholder: 'wc-metabox-sortable-placeholder',
			    start: function ( event, ui ) {
				    ui.item.css( 'background-color', '#f6f6f6' );
			    },
			    stop: function ( event, ui ) {
				    ui.item.removeAttr( 'style' );
			    },
		    });
		},
		view_order_item_detail:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			 var data={
				action:'bmfm_view_order_item_detail_popup',
				order_id:$this.data('order_id'), 
				security:bmfm_admin_params.view_order_item_detail_popup_nonce,
			};
			Admin.block_ui($this);
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
				  if(response.data.success){ 
				    $.alert({
    					title: 'Blinds Parameter Details',
						type: 'blue',
						typeAnimated: true,
    					content: response.data.html,
						useBootstrap: false,
						closeIcon: true,
						buttons: {
					 		ok: function(){}
						}
            		});	
				    Admin.unblock_ui($this);
				  }
				}
			});	
			return false;
		},
		set_bg_color_for_edit_order_item_thumbnail:function(){
			if($('.bmfm-cart-item-thumbnail').length > 0){
				var img_url = $('.bmfm-cart-item-thumbnail').data('img_url');
				$('.bmfm-cart-item-thumbnail img').css('background','url("'+img_url+'")');
			}
		},
		reset_all_data_action:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget);
			var data={
				action:'bmfm_reset_all_data_action',
				cat_id:$this.data('post_id'),
				security:bmfm_admin_params.reset_all_data_action_nonce,
			};
			
			$.confirm({
				title: false,
				type:'green',
				isHidden: true,
				boxWidth: '35%',
				content: bmfm_admin_params.bmfm_get_delete_content_popup_html,
				useBootstrap: false,
				closeIcon: true,
				onContentReady: function () {
					$('.jconfirm').addClass('bmfm-delete-button-js-wrapper bmfm-blinds-js-wrapper');
				},
				buttons: {
				  YES:{					
					btnClass: 'btn-primary', // Use the custom style for the Yes button
					action: function() {							
						this.close();
						Admin.block_ui($this.closest('.bmfm-products-list-wrapper'));
						$.ajax({			
							url:  bmfm_admin_params.ajax_url,
							data: data,			
							type: 'POST',			
							success: function( response ) {			
							  if(response.data.success){ 			
								$.alert({
    								title: false,
									isHidden: true,
									closeIcon: true,
									boxWidth: '40%',
									type: 'green',
									content:'<div class="additional-content-delete-one bmfm-delete-text">Product Deleted Successfully</div>',
									useBootstrap: false,
									onContentReady: function () {
					                    $('.jconfirm').addClass('bmfm-delete-button-js-wrapper bmfm-blinds-js-wrapper');
				                    },
									buttons: {
					 					ok: function(){
											window.location.href = response.data.reset_data_redirect_url;	
										}
									}
            					});	  
								Admin.unblock_ui($this.closest('.bmfm-products-list-wrapper'));			
							  }else if(response.data.error){			
								alert(response.data.error);			
								Admin.unblock_ui($this.closest('.bmfm-products-list-wrapper'));			
							 }			
						   }			
						});				
						return false;
					}
				  },
				  NO:{
					action:function(){
						this.close();
				  	}					
				  }
				}
			  });
			return false;
		},

		remove_fabric_color_row_dashboard:function(event){
			if(true == Admin.remove_row($(event.currentTarget))){
			   location.reload();
			}
		},
		save_parameter_list_rule:function(event){
			event.preventDefault();
			if($(this).hasClass('bmfm-data-saved')){
				return false;
			}
			$('.bmfm-save-parameter-active-section').val('products_and_parameter_setup');
			Admin.save_functionality('false','yes');
		},
		save_accessories_parameter_list_rule:function(event){
			event.preventDefault();
			if($(this).hasClass('bmfm-data-saved')){
				return false;
			}
			$('.bmfm-save-parameter-active-section').val('products_and_parameter_setup');
			Admin.save_functionality('false','yes');
			
		},
		save_accessories_rule:function(event){
			event.preventDefault();	
			$('.bmfm-save-button').addClass('bmfm-data-saved');
			if($(this).hasClass('bmfm-data-saved')){
				return false;
			}
			$('.bmfm-save-parameter-active-section').val('product_list');
			Admin.save_functionality('false','yes');

		},
		block_ui:function($id,$percentage =''){
			if($percentage != ''){
				var opacity= 0.9;
				var message ='<div id="bm-progress"><div  style="width: '+$percentage+'%;" id="bm-progressbar">'+$percentage+'%</div></div><div class="import_text">Please bear with us as we import fabric colors along with their corresponding images, category filters & components.</div>';
				
			}else{
				var message = null;
				var opacity= 0.6;
			}
			$( $id ).block({
					message: message,
					overlayCSS: {
						background: '#fff',
						opacity: opacity
					}
			});
		},set_import_steps:function(){
			var $country = $('input[name="bmfm_settings_data[chosen_country]"]').val(),
			$fetch_products_type_selection = $('.bmfm-fetch-products-selection-wrapper > div.selected').data('type'),
				$first_section = $('.bmfm-step-1').closest('.bmfm-import-steps-wrapper'),
				$second_section = $('.bmfm-step-2').closest('.bmfm-import-steps-wrapper'),
				$third_section = $('.bmfm-step-3').closest('.bmfm-import-steps-wrapper'),
				$fourth_section = $('.bmfm-step-4').closest('.bmfm-import-steps-wrapper');
			$('.bmfm-time-line').each(function(){
				if(!$(this).hasClass('bmfm-step-1')){
					$(this).removeClass('selected');
				}
				if($(this).hasClass('bmfm-step-3') || $(this).hasClass('bmfm-step-4')){
					$(this).addClass('bmfm-hide');
				}
				$(this).removeClass('bmfm-time-line-basecolor');
			});
			// Step2
			if(undefined != $fetch_products_type_selection){
				$second_section.find('.bmfm-step-2').addClass('selected');
				$third_section.find('.bmfm-step-3').removeClass('bmfm-hide');
				// Fetch products from the supplier.
				if('1' == $fetch_products_type_selection){
					if('gb' == $country || 'ie' == $country){
						if($third_section.find('.bmfm-uk-suppliers-wrapper > div').hasClass('selected')){
							$third_section.find('.bmfm-step-3').addClass('selected');
							$third_section.find('.bmfm-step-3').addClass('bmfm-steps-end');
							if('arena' == $third_section.find('.bmfm-uk-suppliers-wrapper > div.selected').data('supplier') ){
								$fourth_section.find('.bmfm-step-4').removeClass('bmfm-hide');
								$third_section.find('.bmfm-step-3').removeClass('bmfm-steps-end');
								if($fourth_section.find('.bmfm-arena-supplier > div').hasClass('selected')){
									$fourth_section.find('.bmfm-step-4').addClass('selected');
								}
							}
							if('decora' == $third_section.find('.bmfm-uk-suppliers-wrapper > div.selected').data('supplier')){
								$fourth_section.find('.bmfm-step-4').removeClass('bmfm-hide');
								$third_section.find('.bmfm-step-3').removeClass('bmfm-steps-end');
								if($fourth_section.find('.bmfm-decora-supplier > div').hasClass('selected')){
									$fourth_section.find('.bmfm-step-4').addClass('selected');
								}
							}
							if( 'others' == $third_section.find('.bmfm-uk-suppliers-wrapper > div.selected').data('supplier')){
								$fourth_section.find('.bmfm-step-4').removeClass('bmfm-hide');
								$third_section.find('.bmfm-step-3').removeClass('bmfm-steps-end');
								$fourth_section.find('.bmfm-step-4').addClass('selected');
							}
						}
					}else if('us' == $country || 'ca' == $country){
						if($third_section.find('.bmfm-us-suppliers-wrapper > div').hasClass('selected')){
							$third_section.find('.bmfm-step-3').addClass('selected');
							$third_section.find('.bmfm-step-3').addClass('bmfm-steps-end');
							if('others' == $third_section.find('.bmfm-us-suppliers-wrapper > div.selected').data('supplier')){
								$fourth_section.find('.bmfm-step-4').removeClass('bmfm-hide');
								$third_section.find('.bmfm-step-3').removeClass('bmfm-steps-end');
								$fourth_section.find('.bmfm-step-4').addClass('selected');
							}
						}
					}else{
						$third_section.find('.bmfm-step-3').addClass('selected');
						$third_section.find('.bmfm-step-3').addClass('bmfm-steps-end');
					}
				}
				// Add your own products.
				if('2' == $fetch_products_type_selection){
					if($third_section.find('.bmfm-import-library-button').hasClass('selected')){
						$third_section.find('.bmfm-step-3').addClass('selected');
						$fourth_section.find('.bmfm-step-4').removeClass('bmfm-hide');
						$third_section.find('.bmfm-step-3').removeClass('bmfm-steps-end');
						if($fourth_section.find('.bmfm-add-your-own-products-wrapper > div').hasClass('selected')){
							$fourth_section.find('.bmfm-step-4').addClass('selected');
						}
					}
				}
			}

			var $count = $('.bmfm-time-line.selected').length - 1 ,$i;
			for($i=1;$i<=$count;$i++){
				$('.bmfm-step-'+$i).addClass('bmfm-time-line-basecolor');
			}
		},
		add_custom_img_select2:function(opt){
			if (!opt.id) {
				return opt.text;
			} 
			var optimage = $(opt.element).attr('data-img'); 
			if(!optimage || optimage == " " ){
			   return opt.text;
			} else {                    
				var $opt = $(
				   '<span class="bmfm-select2-img-single-product"><img src="' + optimage + '" width="20" /> ' + opt.text + '</span>'
				);
				return $opt;
			}
		},
		set_save_functionality_disabled:function(){
			$('.bmfm-save-accessories-rule').removeClass('bmfm-data-saved');
			$('.bmfm-save-parameter-list-rule').removeClass('bmfm-data-saved');
			$('.bmfm-save-accessories-parameter-list-rule').removeClass('bmfm-data-saved');
			$('.bmfm-save-button').removeClass('bmfm-data-saved');
			$('.bmfm-save-button').addClass('bmfm-data-saved');
			$('.bmfm-save-fabric-color-rule').removeClass('bmfm-data-saved');
		},
		set_changed_row_fabric_list:function(){
			$( '.bmfm-product-list-table-content input, .bmfm-product-list-table-content textarea, .bmfm-product-list-table-content select, .bmfm-product-list-table-content checkbox' ).on( 'input', function (event) {
				$(this).closest('tr').find('.bmfm-changed-data').val('yes');
			});
			$( '.bmfm-product-list-table-content .bmfm-remove-image,.bmfm-product-list-table-content .bmfm-upload-image' ).on( 'click',function(){
				$(this).closest('tr').find('.bmfm-changed-data').val('yes');
			});
		},
		set_markup_upto_two_decimals:function(){
			var $markup = $(this).val();
			if($markup.indexOf('.')!=-1){         
       			if($markup.split(".")[1].length > 2){     
					$(this).val(parseFloat($markup).toFixed(2));
	   			}
			}
		},
		target_specific_section:function(){
			var $current_section = $('.bmfm-current-section-name').val();
			if('products_list' == $current_section){
				$('.bmfm-save-button').click();
			}
		},
		validate_save_fabric_color_and_accessories_data_row:function($this,display_error = true){
		  if('product_list' != $('.bmfm-active-section').val()){
				return false;
		  }
		  if('blinds' == $('.bmfm-category-type-value').val()){
			if($this.closest('.bmfm-product-list-table-content').length <= 0){
				return false;
			}
			
			var $table_row = $this.closest('tr'),error = false;
			$table_row.find('.bmfm-fabric-color-name').css('border','1px solid #8c8f94');
			if('' === $table_row.find('.bmfm-fabric-color-name').val()){
				error = 'Please enter the fabric name and then upload the image';
				$table_row.find('.bmfm-fabric-color-name').css('border','1px solid red');
			}
			
			if(!$this.closest('td').find('.bmfm-upload-image-url').hasClass('bmfm-fabric-color-upload-image-url') && '' === $table_row.find('.bmfm-upload-fabric-color-image-span').find('.bmfm-upload-image-src').attr('src') && false == error){
				error = 'Please upload the fabric image';
			}
			
			if(false !== error){
			  if(false === display_error){
				return true;
			  }
				$.confirm({							
				title: 'Error!',							
				columnClass: 'col-md-4 col-md-offset-4',							
				content: error,
				type: 'red',							
				icon: 'fa fa-warning',							
				typeAnimated: true,							
				boxWidth: '30%',							
				useBootstrap: false,							
				closeIcon: true,							
				buttons: {								
					okay: function () {									
						return true;								
					}							
				}						
			  });
			  return true;
			}		
		  }else{
			  if($this.closest('.bmfm-accessories-list-table-content').length <= 0){
				return false;
			  }
			  
			  var $table_row = $this.closest('tr'),error = false;
			  $table_row.find('.bmfm-accessories-name').css('border','1px solid #8c8f94');
			  if('' === $table_row.find('.bmfm-accessories-name').val()){
				 error = 'Please enter the accessories name';
				 $table_row.find('.bmfm-accessories-name').css('border','1px solid red');
			  }
			
			  if('' === $table_row.find('.bmfm-accessories-price').val()){
				 error = 'Please enter the accessories price';
				 $table_row.find('.bmfm-accessories-price').css('border','1px solid red');
			  }
			
			  if(false !== error){
			    if(false === display_error){
				  return true;
			    }
				  
				$.confirm({							
				title: 'Error!',							
				columnClass: 'col-md-4 col-md-offset-4',							
				content: error,
				type: 'red',							
				icon: 'fa fa-warning',							
				typeAnimated: true,							
				boxWidth: '30%',							
				useBootstrap: false,							
				closeIcon: true,							
				buttons: {								
					okay: function () {									
						return true;								
					}							
				}						
			  });
			  return true;
			}
		  }
		  return false;
		},
		save_fabric_color_and_accessories_data_row:function($this){
			if('product_list' != $('.bmfm-active-section').val()){
				return false;
			}
			var $table = $('.bmfm-product-list-table-content');
			if('accessories' == $('.bmfm-category-type-value').val()){
				$table = $('.bmfm-accessories-list-table-content');
			}
			
			if($table.length <= 0){
				return true;
			}

			var $table_row = $this.closest('tr');	
			$table_row.find('.bmfm-changed-data').val('yes');
			Admin.block_ui($table);
			var data={
				action  :'bmfm_save_fabric_color_and_accessories_data_row',
				form_data:$table_row.closest('form').serialize(),
				security :bmfm_admin_params.save_fabric_color_and_accesories_data_row_nonce,
			};
			
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
						if('' != response.data.html){
							$table.find('tbody').html(response.data.html);
							if($('.bmfm-hide-frame').length > 0){
								$('.bmfm-hide-frame').each(function(){
									Admin.uncheck_hide_all_frame_checkbox('false',$(this));
								});
							}
						}
						//$table_row.find('.bmfm-changed-data').val('');
						Admin.unblock_ui($table);
					}else if(response.data.error){
						alert(response.data.error)
						Admin.unblock_ui($table);
					}
				}
			});
			return true;
		},
		freemium_activation_key_submit_button:function(event){
		    event.preventDefault();
		    var $activation_key = $('.bmfm-activation-key-val').val(),
		    $this = $(this);
		    if(!$activation_key){
		        $.confirm({							
				    title: 'Error!',							
				    columnClass: 'col-md-4 col-md-offset-4',							
				    content: 'Activation Key is required.',
				    type: 'red',							
				    icon: 'fa fa-warning',							
				    typeAnimated: true,							
				    boxWidth: '30%',							
				    useBootstrap: false,							
				    closeIcon: true,							
				    buttons: {								
					    Ok: function () {									
						    								
					    }							
				    }						
			    });	
			    return false;
		    }
		    
		    Admin.block_ui($this.closest('.bmfm-freemium-activation-key-section'));
			var data={
				action  :'bmfm_freemium_activation_key_submit_button',
				activation_key:$activation_key,
				site_url: bmfm_admin_params.site_url,
				security :bmfm_admin_params.freemium_activation_key_submit_button_nonce,
			};
			
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
					    $.confirm({							
				            title: 'Success!',							
				            columnClass: 'col-md-4 col-md-offset-4',							
				            content: 'Activated Successfully.',
				            type: 'green',							
				            typeAnimated: true,							
				            boxWidth: '30%',							
				            useBootstrap: false,							
				            closeIcon: false,							
				            buttons: {								
					            Ok: function () {									
						    	    window.location.reload();			
					            }							
				            }						
			            });	
						Admin.unblock_ui($this.closest('.bmfm-freemium-activation-key-section'));
					}else if(response.data.error){
						$.confirm({							
				            title: 'Error!',							
				            columnClass: 'col-md-4 col-md-offset-4',							
				            content: response.data.error,
				            type: 'red',							
				            icon: 'fa fa-warning',							
				            typeAnimated: true,							
				            boxWidth: '30%',							
				            useBootstrap: false,							
				            closeIcon: true,							
				            buttons: {								
					            Ok: function () {									
						    								
					            }							
				            }						
			            });	
						Admin.unblock_ui($this.closest('.bmfm-freemium-activation-key-section'));
					}
				}
			});
			return false;
		},
		freemium_contact_us_submit_button:function(event){
		    event.preventDefault();
		    var $this = $(this);
		    Admin.block_ui($this.closest('.bmfm-freemium-contact-us-form-wrapper'));
			var data={
				action  :'bmfm_freemium_contact_us_submit_button',
				form_data: $this.closest('.bmfm-freemium-contact-us-form-wrapper').find('form').serialize(),
				security :bmfm_admin_params.freemium_contact_us_submit_button_nonce,
			};
			
			$.ajax({
				url:  bmfm_admin_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
					    $.confirm({							
				            title: 'Success!',							
				            columnClass: 'col-md-4 col-md-offset-4',							
				            content: 'Thanks for submitting the details. We will reach you as soon as possible.',
				            type: 'green',							
				            typeAnimated: true,							
				            boxWidth: '30%',							
				            useBootstrap: false,							
				            closeIcon: false,							
				            buttons: {								
					            Ok: function () {									
						    	    window.location.reload();			
					            }							
				            }						
			            });	
						Admin.unblock_ui($this.closest('.bmfm-freemium-contact-us-form-wrapper'));
					}else if(response.data.error){
						$.confirm({							
				            title: 'Error!',							
				            columnClass: 'col-md-4 col-md-offset-4',							
				            content: response.data.error,
				            type: 'red',							
				            icon: 'fa fa-warning',							
				            typeAnimated: true,							
				            boxWidth: '30%',							
				            useBootstrap: false,							
				            closeIcon: true,							
				            buttons: {								
					            Ok: function () {									
						    								
					            }							
				            }						
			            });	
						Admin.unblock_ui($this.closest('.bmfm-freemium-contact-us-form-wrapper'));
					}
				}
			});
			return false;
		},
		unblock_ui:function($id){
			$( $id ).unblock();
		},
	};
	Admin.init();
} );

