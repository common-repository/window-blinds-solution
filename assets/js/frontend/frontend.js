/*bmfm_frontend_params */
jQuery( function( $ ) {
	var Frontend = {
		init: function() {
			// List page category filter toggle .
			$( document ).on( 'click','.bmfm_widget_product_categories .bmfm-toggle',this.toggle_category_filter);
			// List page category filter selection.
			$( document ).on( 'click','.bmfm-cat-child .bmfm-cat-filtername' ,this.category_filter_selection);
			// Product category fabric view selection.
			$( document ).on( 'click','.bmfm_swatch_thumbnails' ,this.category_swatch_filter_selection);
			// Width and drop measurement.
			$(document).on('keyup click','.bmfm-width input,.bmfm-drop input',this.width_and_drop_measurement);
			// Add to cart action.
			$(document).on('click','.bmfm-fabric-color-product-wrapper .single_add_to_cart_button',this.add_to_cart_action);
			// Component selection.
			$(document).on('change','.bmfm-component-selection,.bmfm-dropdown-selection',this.component_and_dropdown_selection);
			// Orderby filter.
			$(document).on('change','.bmfm-orderby-filters .orderby',this.orderby_filter_selection);
			//Single product page Qty control.
			$(document).on( 'click', 'button.bmfm_plus, button.bmfm_minus', this.alter_order_qty);
        	// Trigger Select2 JS. 
			$('.bmfm-select2').select2({
				templateResult: this.add_custom_img_select2,
				minimumResultsForSearch: -1
			});
			if ( $('body.bmfm-fabric-color-product-wrapper.logged-in.admin-bar').length > 0 ) {
				$('html').attr( 'style', 'margin-top: 0 !important;' );     
				$('body.bmfm-fabric-color-product-wrapper').css({'margin-top': 32});  
			}
			// Product gallery action.
			$(document).on('click','.bmfm-fabric-color-product-wrapper .woocommerce-product-gallery .flex-control-nav li',this.product_gallery_action);
			// Trigger price on page load.
			if($('.bmfm-fabric-color-price').length > 0 && 'accessories' == $('.bmfm-category-type').val()){
				this.calculate_price();
			}
			
			// Grid layout style changes
			$(document).on('change','.bmfm_layout',this.grid_layout_change_action);
			// Filter sidebar toggle
			$(document).on('click','#bmfm-sidebar-btn',this.filter_sidebar_toggle);				
			// Filter sidebar toggle close
			$(document).on('click','.bmfm-close',this.filter_sidebar_toggle);
		},
		toggle_category_filter:function(){
			if($(this).parent('li').hasClass('active')){
				$(this).parent('li').removeClass('active');
			}
			else{
				$(this).parent('li').addClass('active');
			}
		},
		category_filter_selection:function(){
			if($(this).parent('li').hasClass('active')){
				$(this).parent('li').removeClass('active');
			}
			else{
				$(this).parent('li').addClass('active');
			}
			var categoryId = $(this).attr("data-id");
			if (jQuery('#bmfm_check_'+categoryId).is(":checked")) {
				document.getElementById("bmfm_check_"+categoryId).checked = false;
			}else{
				document.getElementById("bmfm_check_"+categoryId).checked = true;
			}
			Frontend.append_url_functionality();
			Frontend.product_category_product_list();
		},
		append_url_functionality:function(){
			var prevmainCategory='';
			var category_id = bmfm_frontend_params.category_id;
			var getpara = '';
			$(".bmfm_check_category").each(function () {
				if ($(this).is(":checked")) {
					var maincategoryname = $(this).attr("data-cat-id");
					var categoryname = $(this).attr("data-id");

					if(prevmainCategory != maincategoryname){
						prevmainCategory = maincategoryname;
						getpara += '~~'+prevmainCategory+'=';
					}
					getpara += categoryname+',';
					
				}else{
					$(this).parents("li.bmfm-cat-child").removeClass('active');
				}
			});
			var getpara = getpara.substring(2);
			var getpara_exp = getpara.split('~~');
			var getpara_arr=[];
			getpara_exp.forEach((value, index) => {
				var strVal = value.replace(/,(\s+)?$/, '');
				getpara_arr.push(strVal);
			});
			var getpara_join= getpara_arr.join('&')
			
			var currentURL = window.location.protocol + "//" + window.location.host + window.location.pathname,
			 urlParams = new URLSearchParams(window.location.search);
			if('' != urlParams.get('freemium_product')){
				currentURL += '?freemium_product='+urlParams.get('freemium_product');
			}
			
			if('' != $('.bmfm-orderby-filters .orderby').val()){
				currentURL += '&bmfm_orderby='+$('.bmfm-orderby-filters .orderby').val();
			}
			
			if(getpara_join != ''){
				window.history.pushState({ path: currentURL }, '', currentURL + '&'+getpara_join);
			}else{
				window.history.pushState({ path: currentURL }, '', currentURL);
			}	
		},
		product_category_product_list:function(){
			if(true == bmfm_frontend_params.validate_unsupported_themes){
				window.location.reload();
				return false;
			}
			var data={
				action:'bmfm_category_filter_action',
				category_id:bmfm_frontend_params.category_id,
				orderby:$('.bmfm-orderby-filters .orderby').val(),
				form_data: $('.bmfm-products-list-form').serialize(),
				security:bmfm_frontend_params.category_filter_nonce
			};
			$.ajax({
				url: bmfm_frontend_params.ajax_url,
				data:data,
				type: 'POST',
				success:  function( response ) {
					if(response.data.success){
					 	$('.bmfm-woocommerce-product-list').html(response.data.html); 
						$('.bmfm-total-rows').find('.bmfm-products-count').text(response.data.products_count); 
					}
				}
			});
		},
		category_swatch_filter_selection:function(){
			 if (this.checked) {
				jQuery('.custom-image-wrapper').addClass("bmfm_remove_frame"); 
				jQuery('.bmfm-custom-fabric-img').hide(); 
				} else {
					jQuery('.custom-image-wrapper').removeClass("bmfm_remove_frame"); 
					jQuery('.bmfm-custom-fabric-img').show(); 
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
				   '<span class="bmfm-select2-img-single-product"><img src="' + optimage + '" width="60" /> ' + opt.text + '</span>'
				);
				return $opt;
			}
		},
		width_and_drop_measurement:function(){
				var minWidthValue = parseFloat($('.bmfm-width input[type="number"]').attr('min')),
				 maxWidthValue = parseFloat($('.bmfm-width input[type="number"]').attr('max')),
				 maxdropValue = parseFloat($('.bmfm-drop input[type="number"]').attr('max')),
				 mindropValue = parseFloat($('.bmfm-drop input[type="number"]').attr('min')),
				 input_drop = parseFloat($('.bmfm-drop input[type="number"]').val()),
				 input_width = parseFloat($('.bmfm-width input[type="number"]').val());

				$('.bmfm-width-measurement').find('.bmfm-error').hide();
				$('.bmfm-width-measurement').find('.bmfm-error').text('This field is required.');
				$('.bmfm-drope-measurement').find('.bmfm-error').hide();
				$('.bmfm-drope-measurement').find('.bmfm-error').text('This field is required.');
						if(input_width && (input_width < minWidthValue || input_width > maxWidthValue)){
							$('.bmfm-width-measurement').find('.bmfm-error').text('Please enter the value beteen '+minWidthValue+' ~ '+maxWidthValue).show();
							return false;
						}

					if(input_drop && (input_drop < mindropValue || input_drop > maxdropValue)){
						$('.bmfm-drope-measurement').find('.bmfm-error').text('Please enter the value beteen '+mindropValue+' ~ '+maxdropValue).show();
						return false;
					}

			Frontend.calculate_price();
		},
		component_and_dropdown_selection:function(){
			Frontend.calculate_price();
		},
		add_to_cart_action:function(){
			if('accessories' == bmfm_frontend_params.category_type){
				return true;
			}
			
			var $proceed_to_cart = true;
			if($('.bmfm-required').length > 0){
				$('.bmfm-required').each(function(){
					var $wrapper = $(this).closest('.bmfm-blinds-parameter');
					$wrapper.find('.bmfm-error').hide();
					if('' == $wrapper.find('input').val()){
						$wrapper.find('.bmfm-error').show();
						$proceed_to_cart = false;
					}

					if('undefined' == $wrapper.find('select').val() || 'Choose an option' == $wrapper.find('select').val()){
						$wrapper.find('.bmfm-error').show();
						$proceed_to_cart = false;
					}
				});
			}
			if(undefined == $('.bmfm-fabric-color-price').val() || '' == $('.bmfm-fabric-color-price').val() || !$('.bmfm-fabric-color-product-wrapper .single_add_to_cart_button').hasClass('bmfm-remove-disabled')){
				$proceed_to_cart = false;
			}
			
			if(false == $proceed_to_cart){
				return false;
			}
		},
		calculate_price:function(){
			$('.bmfm-fabric-color-product-wrapper .single_add_to_cart_button.button').removeClass('bmfm-remove-disabled');
			if('blinds' == bmfm_frontend_params.category_type && ('' == $('.bmfm-width input').val() || '' == $('.bmfm-drop input').val())){
				return false;
			}
						
			var data={
				action:'bmfm_calculate_price',
				form_data: $('.bmfm-blinds-info-wrapper').closest('.cart').serialize(),
				security: bmfm_frontend_params.calculate_price_nonce
			};
			$.ajax({
				url:  bmfm_frontend_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
				 if(response.data.success){
					 if('' != response.data.price){
						 $('.bmfm-fabric-color-price').val(response.data.price);
						 $('.bmfm-display-price-info').show();
						 $('.bmfm-display-price').html(response.data.price_display);
						 $('.bmfm-fabric-color-product-wrapper .single_add_to_cart_button').show();
						 $('.bmfm-fabric-color-product-wrapper .single_add_to_cart_button.button').addClass('bmfm-remove-disabled');
						 Frontend.add_to_cart_action();
					 }
				 }else if(response.data.error){
					alert(response.data.error);
				 }
			   }
			});		
		},
		orderby_filter_selection:function(){
			Frontend.append_url_functionality();
			Frontend.product_category_product_list();
		},

		alter_order_qty:function(){
			var qty = $( this ).parent( '.quantity' ).find( '.qty' );
			var val = parseFloat(qty.val());
			var max = parseFloat(qty.attr( 'max' ));
			var min = parseFloat(qty.attr( 'min' ));
			var step = parseFloat(qty.attr( 'step' ));
			if ( $( this ).is( 'button.bmfm_plus' ) ) {
				if ( max && ( max <= val ) ) {
				qty.val( max ).change();
				} else {
				qty.val( val + step ).change();
				}
			} else {
				if ( min && ( min >= val ) ) {
				qty.val( min ).change();
				} else if ( val > 1 ) {
				qty.val( val - step ).change();
				}
			}
		},
		product_gallery_action:function(event){
			event.preventDefault();
			var $this = $(event.currentTarget),
			$src = $this.find('img').attr('src');
			$('.bmfm-fabric-color-frame-img').val($src);
		},
		set_bg_color_for_cart_thumbnail:function(){
			if($('.bmfm-cart-item-thumbnail').length > 0){
				$('.bmfm-cart-item-thumbnail').each(function(){
					var img_url = $(this).data('img_url');
					$(this).find('img').css('background','url("'+img_url+'")')
				});
			}
		},
		set_bg_color_on_update_cart_thumbnail:function(){
			Frontend.set_bg_color_for_cart_thumbnail();
		},
		grid_layout_change_action:function(){	
			$(".radio-layout").removeClass("bmfm_grid_checked");
			$(this).parent('span').addClass("bmfm_grid_checked")
				
			if($(this).parent('span').hasClass('grid-3')){
				  $(".bmfm_product_list").addClass("bmfm-col-3");
				  $(".bmfm_product_list").removeClass("bmfm-col-4");
				  $(".bmfm_product_list").removeClass("bmfm-col-5");
			}	
			else if($(this).parent('span').hasClass('grid-4')){
				  $(".bmfm_product_list").addClass("bmfm-col-4");
				  $(".bmfm_product_list").removeClass("bmfm-col-3");
				  $(".bmfm_product_list").removeClass("bmfm-col-5");
			}
			else if($(this).parent('span').hasClass('grid-5')){
				  $(".bmfm_product_list").addClass("bmfm-col-5");
				  $(".bmfm_product_list").removeClass("bmfm-col-4");
				  $(".bmfm_product_list").removeClass("bmfm-col-3");
			}
		},		
		filter_sidebar_toggle:function(){	
			$('#bmfm-sidebar').toggleClass('visible');
		},

	};
	Frontend.init();
} );
