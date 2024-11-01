<?php
/**
 * Admin Ajax
 *
 * @class BMFM_Admin_Ajax
 */

defined('ABSPATH') || exit;

use Cloudinary\Api\Search\SearchApi;


/**
 * BMFM_Admin_Ajax class.
 */
class BMFM_Admin_Ajax {
	/**
	 * Init.
	 */
	public static function init() {
		$ajax_events = array(
			'save_functionality'                         => false,
			'save_fabric_color_rule'                     => false,
			'save_price_table_data'                      => false,
			'save_paramater_list_popup_functionality'    => false,
			'save_category_list_rule'                    => false,
			'edit_category_list_popup'                   => false,
			'save_category_sublist'                      => false,
			'edit_parameter_list_rule'                   => false,
			'save_category_selection_dashboard'          => false,
			'import_button_action'                       => false,
			'contact_us_action'                          => false,
			'remove_row_action'                          => false,
			'upload_image_custom_popup'                  => false,
			'view_order_item_detail_popup'               => false,
			'category_filter_action'                     => true, 
			'calculate_price'                            => true,
			'reset_all_data_action'                      => false,
			'save_fabric_color_and_accessories_data_row' => false,
			'delete_selected_price_table_row_column'     => false,
			'freemium_activation_key_submit_button'      => false,
			'freemium_contact_us_submit_button'          => false
		);

		foreach ($ajax_events as $ajax_event => $nopriv) {
			add_action('wp_ajax_bmfm_' . $ajax_event, array( __CLASS__, $ajax_event ));
			if ($nopriv) {				
				// For Guest Users.				
				add_action('wp_ajax_nopriv_bmfm_' . $ajax_event, array( __CLASS__, $ajax_event ));			
			}
		}
	}

	/**
	 * Save functionality.
	 */
	public static function save_functionality() {
		check_ajax_referer( 'bmfm-product-save-functionality-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['form_data'])) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			if (empty($form_data)) {
				throw new Exception('Invalid Data');
			}
			
			$active_section                  = isset($post['active_section']) ? wc_clean(wp_unslash($post['active_section'])):'';
			$product_setup_data              = isset($form_data['bmfm_product_setup_data']) ? $form_data['bmfm_product_setup_data']:array();
			$category_type_value             = isset($product_setup_data['category_type_value']) ? $product_setup_data['category_type_value']:'';
			$_section                        = 'products_and_parameter_setup';
			$product_type_list_id            = '';
			$term_id                         = '';
			$product_type_html_row_content   = '';
			$parameters_list_html_content    = '';
			$saved_parameter_list            = isset($post['saved_parameter_list']) ? wc_clean(wp_unslash($post['saved_parameter_list'])):'';
			$stored_term_id                  = isset($product_setup_data['term_id']) ? $product_setup_data['term_id']:''; 
			$type_id                         = isset($product_setup_data['type_id']) ? $product_setup_data['type_id']:'';
			$redirect_fabric_product_section = isset($post['redirect_fabric_product_section']) ? wc_clean(wp_unslash($post['redirect_fabric_product_section'])):'';
			$accessories_html_row_content='';
			switch ($active_section) {
				case 'products_and_parameter_setup':
					if (!$redirect_fabric_product_section) {	 
						//                if(!$saved_parameter_list){
						$product_name         = isset($product_setup_data['product_name']) ? $product_setup_data['product_name']:'';
						$product_slug         = isset($product_setup_data['product_slug']) ? $product_setup_data['product_slug']:$product_name;
						$product_desc         = isset($product_setup_data['product_desc']) ? $product_setup_data['product_desc']:'';
						$image_url            = isset($product_setup_data['image_url']) ? $product_setup_data['image_url']:'';
						$category_type        = isset($product_setup_data['category_type']) ? $product_setup_data['category_type']:'';
						if (!$product_name) {
							throw new Exception('Product Name is left empty');
						}

						$args  = array(
						'description'     => $product_desc,
						'parent'          => '0',
						'slug'            => strtolower(str_replace(' ', '-', $product_slug)),
						);
					
						if (!$stored_term_id) {
							bmfm_delete_invalid_terms();
							$term_id          = bmfm_create_term($product_name, $args, array('image_url' => $image_url,'category_type' => $category_type,'bmfm_blinds' => 'yes'));
							$parameter_list_ids = bmfm_get_parameter_list_ids($term_id);
							if (!empty($parameter_list_ids) && is_array($parameter_list_ids)) {
								foreach ($parameter_list_ids as $parameter_list_id) {
									 wp_delete_post($parameter_list_id);
								}
							}
						} else {
							$args['name'] = $product_name;
							$term_id      = bmfm_update_term($stored_term_id, $args, array('image_url' => $image_url,'category_type' => $category_type));
						}
					
						$parameter_setup_data = isset($product_setup_data['parameter_setup']) ? $product_setup_data['parameter_setup']:array();
						// Assign Product Type.
						if ($term_id && !$stored_term_id && 'blinds' == $category_type_value) {
							 $meta_args = array(
							 'parameter_name'      => 'Blinds type',
							 'parameter_type'      => 'product_type',
							 'mandatory_checked'   => 'no',
							 );
							 $parameter_list_id     = bmfm_create_parameter_list(array('post_title'=> 'Blinds type','post_parent' => $term_id), $meta_args);
							 $product_type_list_ids = bmfm_get_product_type_list_ids($parameter_list_id);
							 if ($parameter_list_id && count($product_type_list_ids) <= 1) {
									 $price_table_data = bmfm_get_price_table_data_in_mm();
									 $price_table_data = wp_json_encode($price_table_data);
									 $product_type_list_id = bmfm_create_product_type_list(array('post_title'  => 'Range A','post_parent' => $parameter_list_id), array('product_type_name' =>'Range A','price_table_data' => $price_table_data,'default_unit' => 'mm'));
							 }
						}
					
						if ('' != $stored_term_id) {
							$product_type_list_id = $type_id;
						}
					
						// Assign Parameters.
						$parameter_list_ids = array();
						if ($term_id && !empty($parameter_setup_data) && is_array($parameter_setup_data)) {
							$paraneter_type_key = 'parameter_name';
							if ('accessories' == $category_type_value) {
								$paraneter_type_key = 'accessories_name';
							}
							$parameter_data = !empty($parameter_setup_data[$paraneter_type_key]) && is_array($parameter_setup_data[$paraneter_type_key]) ? $parameter_setup_data[$paraneter_type_key]:array();
							foreach ($parameter_data as $key => $parameter_name) {
								$parameter_type    = isset($parameter_setup_data['parameter_type'][$key]) ? $parameter_setup_data['parameter_type'][$key]:'';
								$mandatory         = isset($parameter_setup_data['parameter_mandatory'][$key]) ? $parameter_setup_data['parameter_mandatory'][$key]:'';
								$parameter_post_id = isset($parameter_setup_data['post_id'][$key]) ? $parameter_setup_data['post_id'][$key]:'';
								if ('accessories' == $category_type_value) {
									$parameter_type    = isset($parameter_setup_data['accessories_type'][$key]) ? $parameter_setup_data['accessories_type'][$key]:'';
									$mandatory         = isset($parameter_setup_data['accessories_mandatory'][$key]) ? $parameter_setup_data['accessories_mandatory'][$key]:'';
									$parameter_post_id = isset($parameter_setup_data['accessories_post_id'][$key]) ? $parameter_setup_data['accessories_post_id'][$key]:'';
								}
							
								$meta_args = array(
									 'parameter_name'      => $parameter_name,
									 'parameter_type'      => $parameter_type,
									 'mandatory_checked'   => $mandatory,
									 'category_type'       => 'product_type' != $parameter_type ? $category_type_value:'',
								 );
							
								 $stored_parameter_list_id = !empty($parameter_post_id) ? $parameter_post_id:'';
								if (!$stored_parameter_list_id) {
									$parameter_list_id = bmfm_create_parameter_list(array('post_title' => $parameter_name,'post_parent' => $term_id), $meta_args);
								} else {
									$parameter_list_id = bmfm_update_parameter_list($stored_parameter_list_id, array(), $meta_args);
								}
							
								$parameter_list_ids[] = $parameter_list_id;
							}
						}
					
						ob_start();	
						if ('accessories' == $category_type_value) {
							 $parameter_list_ids = bmfm_get_parameter_list_ids($term_id, false, 'accessories');	
							if (is_array($parameter_list_ids) && !empty($parameter_list_ids)) {
								foreach ($parameter_list_ids as $key => $parameter_list_id) {
									$parameter_list = bmfm_get_parameter_list($parameter_list_id);
									if (!is_object($parameter_list)) {
										continue;
									}
						
									$name     = $parameter_list->get_parameter_name();
									$selected = $parameter_list->get_parameter_type();
									$checkbox = $parameter_list->get_mandatory_checked(); 
									include(BMFM_ABSPATH . '/includes/admin/views/html-accessories-parameters-list-row.php');
								}
							}
						}
						if ('blinds' == $category_type_value) {
							$parameter_list_ids = bmfm_get_parameter_list_ids($term_id, false, 'blinds');	
							if (is_array($parameter_list_ids) && !empty($parameter_list_ids)) {
								$checkbox = '';
								foreach ($parameter_list_ids as $key => $parameter_list_id) {
										 $parameter_list = bmfm_get_parameter_list($parameter_list_id);
									if (!is_object($parameter_list)) {
										continue;
									}
						
										 $name     = $parameter_list->get_parameter_name();
										 $selected = $parameter_list->get_parameter_type();
										 $checkbox = $parameter_list->get_mandatory_checked(); 
										 include(BMFM_ABSPATH . '/includes/admin/views/html-blinds-parameters-list-row.php');
								}
							}
						}
						$parameters_list_html_content = ob_get_contents();
						ob_end_clean();
				  
						$_section = 'product_list';
					} else {
						$term_id = isset($product_setup_data['term_id']) ? $product_setup_data['term_id']:'';
						$product_type_list_id = isset($product_setup_data['type_id']) ? $product_setup_data['type_id']:'';
						$_section = 'product_list';
					}	
					break;		
					
				case 'product_list':
					$product_list_data = isset($product_setup_data['product_list_setup']) ? $product_setup_data['product_list_setup']:array();
					if ('accessories' == $category_type_value) {
						$product_list_data = isset($product_setup_data['accessories_list_setup']) ? $product_setup_data['accessories_list_setup']:array();
					}

					if (!empty($product_list_data) && is_array($product_list_data)) {
						$product = 'roller-blinds';
						$visualizer_frame_urls  =  array(
							'visualizer-1' => BMFM_CLOUDURL . 'visualizer-frame/' . $product . '/visualizer-1.png',
							'visualizer-2' => BMFM_CLOUDURL . 'visualizer-frame/' . $product . '/visualizer-2.png',
							'visualizer-3' => BMFM_CLOUDURL . 'visualizer-frame/' . $product . '/visualizer-3.png'
						);
					
						$product_names = isset($product_list_data['name']) ? $product_list_data['name']:array();
						$stored_term_id = isset($product_setup_data['term_id']) ? $product_setup_data['term_id']:'';
						if (!empty($product_names) && '' != $type_id) {
							foreach ($product_names as $key => $product_name) {
								$product_desc        = isset($product_list_data['desc'][$key]) ? $product_list_data['desc'][$key]:'';	
								$product_img_url     = isset($product_list_data['image_url'][$key]) ? $product_list_data['image_url'][$key]:'';
								$frame_img_url       = isset($product_list_data['frame_image_url'][$key]) ? $product_list_data['frame_image_url'][$key]:'';
								$material_image_urls = isset($product_list_data['material_image_urls'][$key]) ? $product_list_data['material_image_urls'][$key]:'';
								$hide_frame          = isset($product_list_data['hide_frame'][$key]) ? $product_list_data['hide_frame'][$key]:'';
								$material_image_urls = !empty($material_image_urls) ? explode(',', $material_image_urls):array();
								$_uploaded_frame_pdt_name =  isset($product_list_data['uploaded_frame_pdt_name'][$key]) ? $product_list_data['uploaded_frame_pdt_name'][$key]:'';
								$price               = '';	
								if ('accessories' == $category_type_value) {
									$price = isset($product_list_data['price'][$key]) ? $product_list_data['price'][$key]:'';
								}
								
								if (!$_uploaded_frame_pdt_name) {
									$hide_frame = 'on';
								}	
								
								$post_args = array(
								  'post_title'    => $product_name,
								  'post_parent'   => 'blinds' == $category_type_value ? $type_id:0,
								  'post_content'  => $product_desc,
								);
																
								$meta_args = array(
								  'bmfm_category_type'             => $category_type_value,
								  'bmfm_category_ids'  	          => $stored_term_id,
								  'bmfm_image_url'      	          => $product_img_url,
								  'bmfm_frame_url'                 => $frame_img_url,
								  'bmfm_hide_frame'                => $hide_frame, 
								  'bmfm_material_images_url'       => $material_image_urls,
								  'category_ids'                  => $stored_term_id,
								  'regular_price'                 => !empty($price) ? $price:0,
								  'price'                         => !empty($price) ? $price:0,
								  'bmfm_uploaded_frame_pdt_name'   => $_uploaded_frame_pdt_name,
								);
							  
								$stored_fabric_color_id = isset($product_list_data['post_id'][$key]) ? $product_list_data['post_id'][$key]:'';
								
								if (!$stored_fabric_color_id) {
									bmfm_create_fabric_color_product($post_args, $meta_args);
								} else {
									bmfm_update_fabric_color_product($stored_fabric_color_id, $post_args, $meta_args);
								} 
							}
						}
					}

					$category_type_value = isset($product_setup_data['category_type_value']) ? $product_setup_data['category_type_value']:'';
					if ('accessories' == $category_type_value) {
						ob_start();
						$accessories_color_ids = !empty($stored_term_id) ? bmfm_get_accessories_list_ids($stored_term_id) : array();
						if (!empty($accessories_color_ids) && is_array($accessories_color_ids)) :
							$sno = 1;    
							foreach ($accessories_color_ids as $key => $accessories_color_id) :
								$accessories_color_product = bmfm_get_fabric_color_product($accessories_color_id);
								if (!is_object($accessories_color_product)) :
									continue;
								endif;
							
								$name      = $accessories_color_product->get_product_name();
								$desc      = is_object($accessories_color_product->get_product()) ? $accessories_color_product->get_product()->get_description():'';
								$price     = is_object($accessories_color_product->get_product()) ? $accessories_color_product->get_product()->get_price():'';
								$image_url = $accessories_color_product->get_image_url();
							
								include(BMFM_ABSPATH . '/includes/admin/views/html-accessories-color-list-row.php');
							endforeach;
					endif;
						$accessories_html_row_content = ob_get_contents();
						ob_end_clean();
					}
					
					if (!empty($type_id)) {
						$product_type_list = bmfm_get_product_type_list($type_id);
						if (is_object($product_type_list)) {
							$product_type_list_name = $product_type_list->get_product_type_name();
							$stored_unit            = $product_type_list->get_default_unit();
							$url                    = add_query_arg(array('bmfm_product_type_id' => $type_id,'bmfm_unit' => $stored_unit), admin_url('admin.php?page=products_list_table&bmfm_add_product=1&bmfm_stored_cat_id=' . $stored_term_id));
							
							ob_start();
							include_once(BMFM_ABSPATH . '/includes/admin/views/html-product-type-list-row.php');
							$product_type_html_row_content = ob_get_contents();
							ob_end_clean();
						}
					}
					
					if ('accessories' == $category_type_value) {
						$_section = 'finish_setup';
					} else {
						$_section = 'price_setup';
					}
					break;
					
				case 'price_setup':
					$category_type_value = isset($product_setup_data['category_type_value']) ? $product_setup_data['category_type_value']:'';
					$product_type_list_name  = isset($product_setup_data['product_type_list']['name']) ? $product_setup_data['product_type_list']['name']:'';
					$product_type_list_name  = !empty($product_type_list_name) ? $product_type_list_name: 'Range A';
					bmfm_update_product_type_list($type_id, array(), array('product_type_name' => $product_type_list_name));
					$_section                = 'finish_setup';
					break;
			}
			
			if ($stored_term_id) {
				bmfm_delete_menu_items();
				BMFM_Install::create_pages();
				delete_option('bmfm_settings_resetted');
				bmfm_create_menu_items();
				
				$view_product_url = bmfm_get_frontend_product_list_page_url($stored_term_id);
			}

			wp_send_json_success(
				array(
					'success' 						  => true,
					'section' 						  => $_section,
					'product_type_list_id' 		      => $product_type_list_id,
					'term_id'     					  => $term_id,
					'product_type_html_row_content'   => $product_type_html_row_content,
					'parameters_list_html_content'    => $parameters_list_html_content,
					'accessories_html_row_content'    => $accessories_html_row_content,
					'dashboard_url'                   => '' != $term_id ? admin_url('admin.php?page=bmfm_dashboard&bmfm_cat_id=' . $stored_term_id):'', 
					'view_product_url'                => !empty($view_product_url) ? $view_product_url:'', 
				) 
			);
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Save fabric color rule.
	 */
	public static function save_fabric_color_rule() {
		check_ajax_referer( 'bmfm-save-fabric-color-rule-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['form_data'])) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			if (empty($form_data)) {
				throw new Exception('Invalid Data');
			}
			
			$product_setup_data            = isset($form_data['bmfm_product_setup_data']) ? $form_data['bmfm_product_setup_data']:array();
			$term_id = isset($product_setup_data['term_id']) ? $product_setup_data['term_id']:'';
			if (!$term_id) {
				throw new Exception('Invalid Data');
			}
			$stored_term  = bmfm_get_term($term_id);
			if (!is_object($stored_term)) {
				throw new Exception('Invalid Data');
			}
			
			$type_id                       = isset($product_setup_data['type_id']) ? $product_setup_data['type_id']:'';
			$category_type_value           = isset($product_setup_data['category_type_value']) ? $product_setup_data['category_type_value']:'';
			$product_list_data = isset($product_setup_data['product_list_setup']) ? $product_setup_data['product_list_setup']:array();
			$offset                        = isset($post['offset']) ? $post['offset']:'';
			if ('accessories' == $category_type_value) {
				$product_list_data = isset($product_setup_data['accessories_list_setup']) ? $product_setup_data['accessories_list_setup']:array();
			}
				
			if (!empty($product_list_data) && is_array($product_list_data)) {	
						$content       = '';
						$product_names = isset($product_list_data['name']) ? $product_list_data['name']:array();
						$product_names = array_chunk($product_names, 10);
						$product_names = !empty($product_names[$offset])? $product_names[$offset]:array(); 
				if (!isset($product_names[$offset])) {
					$stored_cat_id = $term_id;
					$fabric_color_ids = bmfm_get_fabric_color_ids($stored_cat_id, false, false, false, 'DESC');
					if (!empty($fabric_color_ids) && is_array($fabric_color_ids)) {
						ob_start();
						$sno = 1;
						foreach ($fabric_color_ids as $key => $fabric_color_id) {
							$fabric_color_product = bmfm_get_fabric_color_product($fabric_color_id);
							if (!is_object($fabric_color_product)) {
								continue;
							}
					
							$image_url               = $fabric_color_product->get_image_url();
							$fabric_color_name       = $fabric_color_product->get_product_name();
							$fabric_color_desc       = $fabric_color_product->get_product()->get_description();
							$frame_url               = $fabric_color_product->get_frame_url();
							$material_images_url     = $fabric_color_product->get_material_images_url();
							$hide_frame              = $fabric_color_product->get_show_or_hide_frame();
							$uploaded_frame_pdt_name = $fabric_color_product->get_uploaded_frame_pdt_name();
							include(BMFM_ABSPATH . '/includes/admin/views/html-fabric-color-list-row.php');
						}
						$content = ob_get_contents();
						ob_end_clean();
					}
							
					wp_send_json_success( array( 
						'success'     => true ,
						'percentage'  => 100,
						'offset'      => $offset,
						'html'        => $content,
					) );
				}
				
						$stored_term_id = isset($product_setup_data['term_id']) ? $product_setup_data['term_id']:'';
						$index = '' != $offset ? $offset*10:0;
				if (!empty($product_names) && '' != $type_id ) {
					foreach ($product_names as $product_name) {
						$key = $index;
						$product_desc        = isset($product_list_data['desc'][$key]) ? $product_list_data['desc'][$key]:'';	
						$product_img_url     = isset($product_list_data['image_url'][$key]) ? $product_list_data['image_url'][$key]:'';
						$frame_img_url       = isset($product_list_data['frame_image_url'][$key]) ? $product_list_data['frame_image_url'][$key]:'';
						$material_image_urls = isset($product_list_data['material_image_urls'][$key]) ? $product_list_data['material_image_urls'][$key]:'';
						$material_image_urls = !empty($material_image_urls) ? explode(',', $material_image_urls):array();
						$hide_frame          = isset($product_list_data['hide_frame'][$key]) ? $product_list_data['hide_frame'][$key]:'';
						$price               = '';	
						$stored_fabric_color_id = isset($product_list_data['post_id'][$key]) ? $product_list_data['post_id'][$key]:'';
						$_uploaded_frame_pdt_name =  isset($product_list_data['uploaded_frame_pdt_name'][$key]) ? $product_list_data['uploaded_frame_pdt_name'][$key]:'';	
						$fabric_color_product = bmfm_get_fabric_color_product($stored_fabric_color_id);
						if ('accessories' == $category_type_value) {
								  $price = isset($product_list_data['price'][$key]) ? $product_list_data['price'][$key]:'';
						}
								
								$post_args = array(
						  'post_title'    => $product_name,
						  'post_parent'   => 'blinds' == $category_type_value ? $type_id:0,
						  'post_content'  => $product_desc,
								);
								
								$thumbnail_image_url = 'on' != $hide_frame ? bmfm_get_merged_frame_color_image_url($frame_img_url, $product_img_url) : $product_img_url;
								$product_post_thumbnail_id = '';	
								if ($stored_fabric_color_id) {
									$stored_thumbnail_id  = is_object($fabric_color_product) ? $fabric_color_product->get_image_id():'';
									if ('' != $stored_thumbnail_id) {
										$product_post_thumbnail_id = bmfm_set_uploaded_image_as_attachment($thumbnail_image_url, $stored_thumbnail_id);
									}
								} else {
									$product_post_thumbnail_id = '' != $thumbnail_image_url ? bmfm_set_uploaded_image_as_attachment($thumbnail_image_url):'';
								}
							
								$visualizer_frame_urls = array();
								$image_gallery_ids     = array();
								if ($_uploaded_frame_pdt_name && 'on' != $hide_frame) {
									$visualizer_frame_urls  =  array(
									0 => BMFM_CLOUDURL . 'visualizer-frame/' . $_uploaded_frame_pdt_name . '/visualizer-1.png',
									1 => BMFM_CLOUDURL . 'visualizer-frame/' . $_uploaded_frame_pdt_name . '/visualizer-2.png',
									2 => BMFM_CLOUDURL . 'visualizer-frame/' . $_uploaded_frame_pdt_name . '/visualizer-3.png'
									);
									$stored_product_image_gallery_ids = $fabric_color_product->get_product_image_gallery_ids();
								
									if (!empty($stored_product_image_gallery_ids)) {
										foreach ($stored_product_image_gallery_ids as $key => $stored_product_image_gallery_id) {
											$visualizer_frame_url = isset($visualizer_frame_urls[$key]) ? $visualizer_frame_urls[$key]:'';
											if (!$visualizer_frame_url) {
												continue;
											}
											$merged_url = bmfm_get_merged_frame_color_image_url($visualizer_frame_url, $product_img_url); 
											$image_gallery_ids[] = bmfm_set_uploaded_image_as_attachment($merged_url, $stored_product_image_gallery_id);
										}
									} else {
										foreach ($visualizer_frame_urls as $visualizer_frame_url) {
											$merged_url = bmfm_get_merged_frame_color_image_url($visualizer_frame_url, $product_img_url); 
											$image_gallery_ids[] = bmfm_set_uploaded_image_as_attachment($merged_url);
										}	
									}
								}
							
								$material_image_attachment_ids = array();
								if (!empty($material_image_urls)) {
									foreach ($material_image_urls as $material_image_url) {
										$material_image_attachment_ids[] = bmfm_set_uploaded_image_as_attachment($material_image_url);
									}
								}
							
								if (!empty($image_gallery_ids) && !empty($material_image_attachment_ids)) {
									$image_gallery_ids = array_merge($image_gallery_ids, $material_image_attachment_ids);
								}
							
								if (!empty($material_image_attachment_ids) && 'on' == $hide_frame) {
									$image_gallery_ids = $material_image_attachment_ids; 		
								}
							
								$meta_args = array(
								'bmfm_category_type'             => $category_type_value,
								'bmfm_category_ids'  	          => $stored_term_id,
								'category_ids'                  => $stored_term_id,
								'bmfm_image_url'      	          => $product_img_url,
								'bmfm_frame_url'                 => $frame_img_url,
								'bmfm_hide_frame'                => $hide_frame, 
								'bmfm_material_images_url'       => $material_image_urls,
								'regular_price'                 => !empty($price) ? $price:0,
								'price'                         => !empty($price) ? $price:0,
								'bmfm_product_post_thumbnail_id' => $product_post_thumbnail_id,
								'bmfm_uploaded_frame_pdt_name'   => $_uploaded_frame_pdt_name,
								'bmfm_product_image_gallery_ids' => $image_gallery_ids,
								);
							  
								if (!$stored_fabric_color_id) {
									bmfm_create_fabric_color_product($post_args, $meta_args);
								} else {
									unset($post_args['post_parent']);
									bmfm_update_fabric_color_product($stored_fabric_color_id, $post_args, $meta_args);
								} 
								$index++;							
					}
				}
			}
			wp_send_json_success( array( 
				'success'     => true ,
				'offset'      => $offset+1,
				'form_data'   => $form_data,
				'html'        => ''
			) );
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Save price table data.
	 */
	public static function save_price_table_data() {
		check_ajax_referer( 'bmfm-save-price-table-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['price_table_data']) || !isset($post['product_type_id'])) {
				throw new Exception('Invalid Data');
			}
			
			$product_type_id = isset($post['product_type_id']) ? absint($post['product_type_id']):'';
			if (!$product_type_id) {
				throw new Exception('Invalid Data');
			}
			
			$markup           = isset($post['markup']) ? floatval($post['markup']):0;
			$default_unit     = isset($post['default_unit']) ? wc_clean(wp_unslash($post['default_unit'])):'mm';
			$price_table_data = isset($post['price_table_data']) ? wc_clean(wp_unslash($post['price_table_data'])):'';
			$price_table_data = wp_json_encode($price_table_data);
			$extra_args       = array('markup' => $markup,'default_unit' => $default_unit);
			
			if ('cm' == $default_unit) {
				$extra_args['price_table_data_in_cm']   = $price_table_data; 
			} else if ('inch' == $default_unit) {
				$extra_args['price_table_data_in_inch'] = $price_table_data; 
			} else {
				$extra_args['price_table_data']         = $price_table_data;
			}
			
			bmfm_update_product_type_list($product_type_id, array(), $extra_args);
			
			wp_send_json_success( array( 'success' => true ,'msg' => 'Saved Successfully') );
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Save parameter list popup functionality.
	 */
	public static function save_paramater_list_popup_functionality() {
		check_ajax_referer( 'bmfm-save-paramater-list-popup-functionality-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['form_data']) ) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			if (empty($form_data)) {
				throw new Exception('Invalid Data');
			}
			
			$parameter_list_id   = isset($form_data['bmfm_parameter_list_id']) ? $form_data['bmfm_parameter_list_id']:'';
			if (empty($parameter_list_id)) {
				throw new Exception('Invalid Data');
			}
			
			$parameter_list_type = isset($form_data['bmfm_parameter_list_type']) ? $form_data['bmfm_parameter_list_type']:'';
			$parameter_data      = isset($form_data['bmfm_parameter_data']) && !empty($form_data['bmfm_parameter_data']) ? $form_data['bmfm_parameter_data']:array();
			$parameter_names     = isset($parameter_data['name']) && !empty($parameter_data['name']) ? $parameter_data['name']:array();
			
			if (!empty($parameter_names)) {
				foreach ($parameter_names as $index => $parameter_name) {
					switch ($parameter_list_type) {
						case 'drop_down':
							$meta_args = array(
								'name'      => $parameter_name,
								'image_url' => isset($parameter_data['image_url'][$index]) && !empty($parameter_data['image_url'][$index]) ? $parameter_data['image_url'][$index]:'',
							); 
							
							$stored_dropdown_list_id = isset($parameter_data['post_id'][$index]) ? $parameter_data['post_id'][$index]:0;
							if ($stored_dropdown_list_id) {
								bmfm_update_dropdown_list($stored_dropdown_list_id, array('post_parent' => $parameter_list_id), $meta_args);
							} else {
								bmfm_create_dropdown_list(array('post_parent' => $parameter_list_id), $meta_args);
							}
							break;
						case 'component':
							$meta_args = array(
								'name'        => $parameter_name,
								'type'        => isset($parameter_data['type'][$index]) && !empty($parameter_data['type'][$index]) ? $parameter_data['type'][$index]:'',
								'net_price'   => isset($parameter_data['cost_price'][$index]) && !empty($parameter_data['cost_price'][$index]) ? $parameter_data['cost_price'][$index]:'',
								'markup'      => isset($parameter_data['markup'][$index]) && !empty($parameter_data['markup'][$index]) ? $parameter_data['markup'][$index]:'',
								'image_url'   => isset($parameter_data['image_url'][$index]) && !empty($parameter_data['image_url'][$index]) ? $parameter_data['image_url'][$index]:'',
							); 
							
							$stored_component_list_id = isset($parameter_data['post_id'][$index]) ? $parameter_data['post_id'][$index]:0;
							if ($stored_component_list_id) {
								bmfm_update_component_list($stored_component_list_id, array('post_parent' => $parameter_list_id), $meta_args);
							} else {
								bmfm_create_component_list(array('post_parent' => $parameter_list_id), $meta_args);
							}
							break;
					}
				}
				
				$html_content = '';		
				$class = '';
				if ('drop_down' == $parameter_list_type) {
					$dropdown_list_ids = bmfm_get_dropdown_list_ids($parameter_list_id);
					$drop_down_s_no=1;
					if (!empty($dropdown_list_ids) && is_array($dropdown_list_ids)) {
						$class='.bmfm-blinds-dropdown-parameter-popup-content';
						ob_start();
						foreach ($dropdown_list_ids as $key => $dropdown_list_id) {
							$dropdown_list = bmfm_get_dropdown_list($dropdown_list_id);
							if (!is_object($dropdown_list)) :
								continue;
							endif;
							$drop_down_parameter_name      = $dropdown_list->get_name();
							$image_url                     = $dropdown_list->get_image_url();
							include(BMFM_ABSPATH . '/includes/admin/views/html-dropdown-parameter-row.php');
						}
						$html_content = ob_get_contents();
						ob_end_clean();
					}
				}
				
				if ('component' == $parameter_list_type) {
					$component_list_ids = bmfm_get_component_list_ids($parameter_list_id);
					$component_s_no=1;
					if (!empty($component_list_ids) && is_array($component_list_ids)) {
						$class='.bmfm-blinds-component-parameter-popup-content';
						ob_start();
						foreach ($component_list_ids as $key => $component_list_id) {
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
						}
						$html_content = ob_get_contents();
						ob_end_clean();
					}
				}
			}
			wp_send_json_success( array( 'success' => true ,'msg' => 'Saved Successfully','html_content' => $html_content,'class' => $class) );
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Save category list rule functionality.
	 */
	public static function save_category_list_rule() {
		check_ajax_referer( 'bmfm-save-category-list-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['form_data']) ) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			if (empty($form_data)) {
				throw new Exception('Invalid Data');
			}
			
			$category_list_data   = isset($form_data['bmfm_category_list']) ? $form_data['bmfm_category_list']:'';
			if (empty($category_list_data) || !is_array($category_list_data)) {
				throw new Exception('Invalid Data');
			}
			
			$product_id   = isset($form_data['bmfm_blinds_category_id']) ? absint($form_data['bmfm_blinds_category_id']):'';
			foreach ($category_list_data['name'] as $key => $name) {
				$meta_args   = array(
					'name'     => $name,
					'sequence' => isset($category_list_data['sequence'][$key]) ? $category_list_data['sequence'][$key]:''
				);
				
				$stored_category_list_id = isset($category_list_data['post_id'][$key]) ? $category_list_data['post_id'][$key]:'';
				if ('' != $stored_category_list_id) {
					$category_list_id = bmfm_update_category_list($stored_category_list_id, array('post_parent' => $product_id), $meta_args);
				} else {
					$category_list_id = bmfm_create_category_list(array('post_parent' => $product_id), $meta_args);
				}
			}

			$category_html_content = '';
			$category_list_ids     = bmfm_get_category_list_ids($product_id);
			if (!empty($category_list_ids)) {
				ob_start();
				$category_s_no=1;
				foreach ($category_list_ids as $key => $category_list_id) {
					$category_list = bmfm_get_category_list($category_list_id);
					if (!is_object($category_list)) :
						continue;
					endif;
					$name     = $category_list->get_name();
					
					include(BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-list-row.php');
				}
				$category_html_content = ob_get_contents();
				ob_end_clean();
			}
			
			wp_send_json_success( 
				array( 
					'success' 				 => true ,
					'msg'     				 => 'Saved Successfully',
					'category_html_content'  => $category_html_content, 
				)
			);
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Edit category list popup.
	 */
	public static function edit_category_list_popup() {
		check_ajax_referer( 'bmfm-edit-category-list-popup-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post)) {
				throw new Exception('Invalid Data');
			}
			
			$product_id         = isset($post['product_id']) ? absint($post['product_id']):0;
			$category_list_id   = isset($post['category_list_id']) ? absint($post['category_list_id']):0;
			
			ob_start();
			include(BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-sublist.php');
			$content = ob_get_contents();
			ob_end_clean();
			
			wp_send_json_success( 
				array( 
					'success'   => true ,
					'html'  	=> $content, 
				) 
			);
			
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Save category sublist.
	 */
	public static function save_category_sublist() {
		check_ajax_referer( 'bmfm-save-category-sublist-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['form_data']) ) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			if (empty($form_data)) {
				throw new Exception('Invalid Data');
			}
			
			$category_sublist_data = isset($form_data['bmfm_category_sublist']) && !empty($form_data['bmfm_category_sublist']) && is_array($form_data['bmfm_category_sublist']) ? $form_data['bmfm_category_sublist']:array();
			if (empty($category_sublist_data)) {
				throw new Exception('No data found');
			}
			
			$blinds_category_list_id = isset($form_data['bmfm_blinds_category_list_id']) ? $form_data['bmfm_blinds_category_list_id']:'';
			if (empty($blinds_category_list_id)) {
				throw new Exception('No data found');
			}
			
			foreach ($category_sublist_data['name'] as $key => $name) {
				$image_url                   = isset($category_sublist_data['image_url'][$key]) ? $category_sublist_data['image_url'][$key]:'';
				$sequence                    = isset($category_sublist_data['sequence'][$key]) ? $category_sublist_data['sequence'][$key]:'';
				$stored_category_sublist_id  = isset($category_sublist_data['post_id'][$key]) ? $category_sublist_data['post_id'][$key]:'';
				$meta_args = array(
						'name'            => $name,
						'image_url'       => $image_url, 
						'sequence'        => $sequence,
				);

				if ('' != $stored_category_sublist_id) {
					bmfm_update_category_sublist($stored_category_sublist_id, array('post_parent' => $blinds_category_list_id), $meta_args); 
				} else {
					bmfm_create_category_sublist(array('post_parent' => $blinds_category_list_id), $meta_args); 
				}
			}
			 
			$category_sub_html_content = '';
			$category_sub_list_ids     = bmfm_get_category_sub_list_ids($blinds_category_list_id);
			if (!empty($category_sub_list_ids)) {
				ob_start();
				$s_no=1;
				foreach ($category_sub_list_ids as $key => $category_sub_list_id) {
					$category_sub_list = bmfm_get_category_sublist($category_sub_list_id);
					if (!is_object($category_sub_list)) :
						continue;
					endif;
					
					$name     = $category_sub_list->get_name();
					$image_url = $category_sub_list->get_image_url();
					
					include(BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-sublist-row.php');
				}
				$category_sub_html_content = ob_get_contents();
				ob_end_clean();
			}  
			 
			wp_send_json_success( 
				array( 
					'success' 				     => true ,
					'msg'     				     =>'Saved Successfully',
					'category_sub_html_content'  => $category_sub_html_content,
				)
			);
			
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Edit parameter list rule.
	 */
	public static function edit_parameter_list_rule() {
		check_ajax_referer('bmfm-edit-parameter-list-rule-nonce', 'security');
		try {
			$post = bmfm_post_method();
			if (!isset($post)) {
				throw new Exception('Invalid Data');
			}
			
			$parameter_list_id = isset($post['parameter_list_id']) ? absint($post['parameter_list_id']):0;
			if (!$parameter_list_id) {
				throw new Exception('Invalid Data');
			}
			
			$parameter_type = isset($post['parameter_type']) ? wc_clean(wp_unslash($post['parameter_type'])):'';
			$content = '';
			if ('drop_down' == $parameter_type) {
				ob_start();
				include(BMFM_ABSPATH . '/includes/admin/views/html-blinds-dropdown-parameters.php');
				$content = ob_get_contents();
				ob_end_clean();
			} else if ('component' == $parameter_type) {
				ob_start();
				include(BMFM_ABSPATH . '/includes/admin/views/html-blinds-component-parameters.php');
				$content = ob_get_contents();
				ob_end_clean();
			}
			
			wp_send_json_success( 
				array( 
					'success'   => true ,
					'html'  	=> $content, 
				)
			);
			
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Save category selection dashboard action.
	 */
	public static function save_category_selection_dashboard() {
		check_ajax_referer('bmfm-save-category-selection-dashboard-nonce', 'security');
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['form_data']) ) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			if (empty($form_data)) {
				throw new Exception('Invalid Data');
			}
			
			$fabric_color_id = isset($post['post_id']) ? absint($post['post_id']):'';
			if (!$fabric_color_id) {
				throw new Exception('Invalid Data');
			}
			
			$fabric_color_object = bmfm_get_fabric_color_product($fabric_color_id);
			if (!is_object($fabric_color_object)) {
				throw new Exception('Invalid Data');
			}
			
			$linked_categories = isset($form_data['bmfm_category_selection_dashboard']) ? ( wp_unslash($form_data['bmfm_category_selection_dashboard']) ):'';
			
			bmfm_update_fabric_color_product($fabric_color_id, array(), array('bmfm_linked_categories' => $linked_categories));
			
			wp_send_json_success( 
				array( 
					'success' 				 => true ,
				)
			);
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Import button action.
	 */
	public static function import_button_action() {
		check_ajax_referer( 'bmfm-import-button-action-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['form_data']) ) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			if (empty($form_data)) {
				throw new Exception('Invalid Data');
			}
			
			$iterate             = isset($post['iterate']) ? $post['iterate']:false;
			$offset              = isset($post['offset']) ? $post['offset']:0;
			$status              = isset($post['status']) ? $post['status']:'';
			$stored_product_data = !empty($post['product_data']) ? json_decode(wp_unslash($post['product_data']), true):'';
			$stored_category_ids = bmfm_get_category_ids();
			
			$form_data = isset($form_data['bmfm_settings_data']) && is_array($form_data['bmfm_settings_data']) && !empty($form_data['bmfm_settings_data']) ? array_filter($form_data['bmfm_settings_data']):array();
			
			if (1 == $form_data['product_selection']) {
				$country = $form_data['chosen_country'];
				$uk_supplier = $form_data['uk_supplier'];
				$products = !empty($form_data['uk_fetch_supplier_products'][$uk_supplier]) ?$form_data['uk_fetch_supplier_products'][$uk_supplier]:array() ;
			} else {
				$country = $form_data['chosen_country'];
				$products = $form_data['own_products'];
				$uk_supplier = '';
			}
			if ( 'us' ==  $country || 'ca' == $country  ) {
				$default_unit = 'inch';
			} else {
				$default_unit = 'mm';
			}
	
			$product_arr = array();
			$pos = strpos($products, ',');
			if (false !== $pos) {
				$product_arr = explode(',', $products);
			} else {
				$product_arr[] = $products;
			}
						
			if ('true' == $iterate && $offset >= 5) {
				$currency = 'USD';
				if ('gb' == $country || 'ie'  == $country) {
					$currency ='GBP';
				} else if ('au' == $country || 'nz'  == $country) {
					$currency ='AUD';
				}
				
				update_option('woocommerce_currency', $currency);
				BMFM_Install::create_pages();
				delete_option('bmfm_settings_resetted');
				bmfm_create_menu_items();
				$term_id = !empty($stored_category_ids[0]) ? $stored_category_ids[0]:'';
				wp_send_json_success( 
					array( 
						'success' 				 => true ,
						'msg'     				 => 'Products Imported Successfully',
						'percentage'             => 100,
						'fabric_list_url'        => bmfm_get_frontend_product_list_page_url($term_id),
						'dashboard_url'          => admin_url('admin.php?page=bmfm_dashboard&bmfm_cat_id=' . $term_id . ''),
						'category_count'         => count($product_arr),
					)
				);
				exit;
			}
			
			if ('false' == $iterate && is_array($stored_category_ids) && count($stored_category_ids) >= 2 ) {
				throw new Exception('Already two products imported on your site.');
			}
			
			if ('false' == $iterate ) {
				$total_products_count = count($stored_category_ids)+count($product_arr);
				$total_products_count = 2 - $total_products_count;
				if ($total_products_count < 0) {
					throw new Exception(sprintf('Only %s product imported on your site.', 2 - count($stored_category_ids)));
				}
			}
			
			if ('false' == $iterate && !empty($stored_category_ids) && is_array($stored_category_ids)) {
				foreach ($stored_category_ids as $stored_category_id) {
					$stored_category_obj = new BMFM_Term_Object($stored_category_id);
					if (!is_object($stored_category_obj)) {
						continue;
					}
					
					if (false !== strpos(strtolower($stored_category_obj->get_name()), 'vertical') && in_array('vertical', $product_arr)) {
						throw new Exception('Vertical Blinds products already created on your site');
					}
					
					if (false !== strpos(strtolower($stored_category_obj->get_name()), 'roller') && in_array('roller', $product_arr)) {
						throw new Exception('Roller Blinds products already created on your site');
					}
					
					if (false !== strpos(strtolower($stored_category_obj->get_name()), 'venetian') && in_array('venetian', $product_arr)) {
						throw new Exception('Venetian Blinds products already created on your site');
					}
					
					if (false !== strpos(strtolower($stored_category_obj->get_name()), 'roman') && in_array('roman', $product_arr)) {
						throw new Exception('Roman Blinds products already created on your site');
					}
				}
			}
			
			// $dirall & dir - cloudinary path.
			$blinds_product_data = array();
			foreach ($product_arr as $product) {
				if (1 == $form_data['product_selection']) {
					if ('arena' == $uk_supplier) {
						$country = 'uk-ireland';
						$dirall = "fetch-product-from-suppliers/$country/$uk_supplier/$product/*";
						$dir    ="fetch-product-from-suppliers/$country/$uk_supplier/$product";
					}
					if ('decora' == $uk_supplier) {
						$dirall = "add-your-own-products/$product/*";
						$dir = "add-your-own-products/$product";
					}
				} else {
					$dirall = "add-your-own-products/$product/*";
					$dir = "add-your-own-products/$product";
				}
				switch ($product) {
					case 'roller-blinds':
						$product_name = 'Roller Blinds';
						break;
					case 'vertical-blinds':
						$product_name= 'Vertical Blinds';
						break;
					case 'venetian-blinds':
						$product_name= 'Venetian Blinds';
						break;
					case 'roman-blinds':
						$product_name= 'Roman Blinds';
						break;
					case 'wood-blinds':
						$product_name= 'Wood Blinds';
						break;	
					case 'cellular-blinds':
						$product_name= 'Cellular Blinds';
						break;	
					case 'day-night-blinds':
						$product_name= 'Day & Night Blinds';
						break;
					case 'fauxwood-blinds':
						$product_name= 'Fauxwood Blinds';
						break;	
					case 'pleated-blinds':
						$product_name= 'Pleated Blinds';
						break;			
					default:
						$product_name= 'Roller Blinds';
				}
				
				$image_url     = BMFM_CLOUDURL . $dir . '/product-image/product.png';
				$product_desc  ="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type.";
				$products      = array();
				if ($uk_supplier) {
					$category_slug = $uk_supplier . ' ' . $product_name;
				} else {
					$category_slug = $product_name;
				}

				if ('false' == $iterate) {

					// Create Category
					$args  = array(
						'description'     => $product_desc,
						'parent'          => '0',
						'slug'            => strtolower(str_replace(' ', '-', $category_slug)),
						);
						
					$term_id = bmfm_create_term($product_name, $args, array('country'=>$country,'image_url' => $image_url,'category_type' => 'blinds','bmfm_blinds' => 'yes')); 
					$parameter_list_ids = bmfm_get_parameter_list_ids($term_id);
					if (!empty($parameter_list_ids) && is_array($parameter_list_ids)) {
						foreach ($parameter_list_ids as $parameter_list_id) {
							wp_delete_post($parameter_list_id);
						}
					}
					
					// Create Parameter List
					$parameters_data = array(
					array('parameter_name' =>'Width','parameter_type' =>'numeric_x','mandatory_checked' =>'on','category_type'       => 'blinds'),
					array('parameter_name' =>'Drop','parameter_type' =>'numeric_y','mandatory_checked' =>'on','category_type'       => 'blinds'),
					array('parameter_name' =>'Room','parameter_type' =>'text','mandatory_checked' =>'off','category_type'       => 'blinds')
					);
					foreach ($parameters_data as $parameters) {
						if (! $parameters['parameter_name']) {
							continue;
						}
						$type_id = bmfm_create_parameter_list(array('post_parent' => $term_id,'post_title' => $parameters['parameter_name']), $parameters);
					}
				
					//Component List. 
					$meta_component_args = array(
					'parameter_name'    =>'Motorisation',
					'parameter_type'    =>'component',
					'mandatory_checked' =>'off',
					'category_type'     => 'blinds'
					);
					$component_parameter_list_id = bmfm_create_parameter_list(array('post_title'=> $meta_component_args['parameter_name'],'post_parent' => $term_id), $meta_component_args);
					$component_meta_args = array(
										array(
											'name'        => 'Motor and Remote',
											'type'        => 'fixed',
											'net_price'   => '78',
											'image_url'	  => BMFM_CLOUDURL . 'components/motorisation/Motor-and-Remote.png'
										), 
										array(
											'name'        => 'Motor only',
											'type'        => 'fixed',
											'net_price'   => '46',
											'image_url'	  => BMFM_CLOUDURL . 'components/motorisation/Motor-only.png'
										), 
										array(
											'name'        => 'Chain',
											'type'        => 'fixed',
											'net_price'   => '23',
											'image_url'	  => BMFM_CLOUDURL . 'components/motorisation/chain.png'
										)
									);
									
					foreach ($component_meta_args as $component_meta_arg) {
						if (! $component_meta_arg['name']) {
							continue;
						}
						bmfm_create_component_list(array('post_title'=> $component_meta_arg['name'],'post_parent' => $component_parameter_list_id), $component_meta_arg);
					}
				
					// Dropdown List.		
					$meta_dropdown_args         =  array('parameter_name' =>'Mounting','parameter_type' =>'drop_down','mandatory_checked' =>'off','category_type'     => 'blinds');
					$dropdown_parameter_list_id = bmfm_create_parameter_list(array('post_parent' => $term_id,'post_title' => $meta_dropdown_args['parameter_name']), $meta_dropdown_args);
					$dropdown_meta_args = array(
								array(
									'name'   	=> 'Blind',
									'image_url' =>  BMFM_CLOUDURL . 'components/mounting/blind.png'
									),
								 array(
									'name'      => 'Recess',
									'image_url' => BMFM_CLOUDURL . 'components/mounting/recess.png'
								  )  
					);

					foreach ($dropdown_meta_args as $dropdown_meta_arg) {
						if (! $dropdown_meta_arg['name']) {
							continue;
						}
						bmfm_create_dropdown_list(array('post_title'=> $dropdown_meta_arg['name'],'post_parent' => $dropdown_parameter_list_id), $dropdown_meta_arg);
					}
				
					// Create Product Type - Range A & Price Table.
					$meta_args = array(
							'parameter_name'      => 'Product Type',
							'parameter_type'      => 'product_type',
							'mandatory_checked'   => 'no',
							'category_type'       => ''
					);
					$parameter_list_id        = bmfm_create_parameter_list(array('post_title'=> 'Product Type','post_parent' => $term_id), $meta_args);
					$default_price_table_data = bmfm_get_price_table_data_in_mm();
					$price_table_data         = wp_json_encode($default_price_table_data);
					$product_type_id          = bmfm_create_product_type_list(array('post_title'=> 'Range A','post_parent' => $parameter_list_id), array('product_type_name' =>'Range A','price_table_data' => $price_table_data,'default_unit' => $default_unit));
				
					$blinds_product_data[$product]['term_id']    = $term_id;
					$blinds_product_data[$product]['type_id']    = $product_type_id;
				}
				
				if (!empty($stored_product_data[$product]['term_id']) && !empty($stored_product_data[$product]['type_id'])) {
					$term_id         = $stored_product_data[$product]['term_id'];
					$product_type_id = $stored_product_data[$product]['type_id'];
				}
				
				// Create Products - Fabric & Color.
				$api       = new SearchApi();
				$results   = $api->expression("folder:$dirall")->maxResults(51)->execute();
				$resources = $results['resources'];
				unset($resources[0]);
				$resources = array_values($resources);
				$resources = array_chunk($resources, 10);
				$resources = isset($resources[$offset]) ? $resources[$offset]:array();
				$visualizer_frame_urls   =  array(
						'visualizer-1' => BMFM_CLOUDURL . 'visualizer-frame/' . $product . '/visualizer-1.png',
						'visualizer-2' => BMFM_CLOUDURL . 'visualizer-frame/' . $product . '/visualizer-2.png',
						'visualizer-3' => BMFM_CLOUDURL . 'visualizer-frame/' . $product . '/visualizer-3.png'
				);
				
				if ('false' == $iterate) {
					$category_list_data = array(
						'color'           => 'Color',
					);
				
					$category_sublist_data = array(
						'color' => array(
							'black'  => 'Black',
							'blue'   => 'Blue',
							'brown'  => 'Brown',
							'cream'  => 'Cream',
							'green'  => 'Green',
							'grey'   => 'Grey',
							'orange' => 'Orange',
							'pink'   => 'Pink',
							'purple' => 'Purple',
							'red'    => 'Red',
							'teal'   => 'Teal',
							'white'  => 'White',
							'yellow' => 'Yellow',
						),
					);
					
					$category_list_ids = bmfm_get_category_list_ids($term_id);
					if (!empty($category_list_ids) && is_array($category_list_ids)) {
						foreach ($category_list_ids as $category_list_id) {
							$category_sublist_ids = bmfm_get_category_sub_list_ids($category_list_id);
							foreach ($category_sublist_ids as $category_sublist_id) {
								wp_delete_post($category_sublist_id);
							}
							wp_delete_post($category_list_id);
						}
					}
					
					foreach ($category_list_data as $category_list_key => $category_list_name) {
						$category_list_id = bmfm_create_category_list(array('post_parent'=> $term_id), array('name' => $category_list_name));
					
						foreach ($category_sublist_data[$category_list_key] as $category_sublist_key => $category_sublist_name) {
							$img_url = BMFM_CLOUDURL . 'filter-product/color/' . $category_sublist_name . '.JPG';
							bmfm_create_category_sublist(array('post_parent'=> $category_list_id), array('name' => $category_sublist_name,'image_url' => $img_url));
						}
					}
				}				
				
				$default_category_sub_lists_products = bmfm_get_default_category_sub_lists_data_to_import($product);
				$category_sub_lists_products         = !empty($default_category_sub_lists_products[$product]) ? $default_category_sub_lists_products[$product]:array();
				$category_list_ids                   = bmfm_get_category_list_ids($term_id);
				
				foreach ($resources as $single_image) {
						$url_frame_url = BMFM_CLOUDURL . 'visualizer-frame/' . $product . '/frame.png';
						$bmfm_image_url = $single_image['secure_url'];
						$pathinfo = pathinfo($single_image['secure_url']);
					if (strpos($pathinfo['filename'], '_') !== false) {
						$fabric_name = str_replace('_', ' ', $pathinfo['filename']);
						$fabric_namelowercase= strtolower($fabric_name);
						$fabricName = ucfirst($fabric_namelowercase);
					} else {
						$fabric_namelowercase= strtolower($pathinfo['filename']);
						$fabricName = ucfirst($fabric_namelowercase);
					}
						
					if (!$fabricName || 'Product' == $fabricName) {
						continue;
					}
						
						 $merged_frame_color_image_url = bmfm_get_merged_frame_color_image_url($url_frame_url, $bmfm_image_url);
						 $product_post_thumbnail_id = '';
					if ($merged_frame_color_image_url) {
						$product_post_thumbnail_id = bmfm_set_uploaded_image_as_attachment($merged_frame_color_image_url);
					}
					
						 $product_img_ids = array();
					foreach ($visualizer_frame_urls as $post_title => $visualizer_frame_url) {
						$merged_visualizer_color_image_url = bmfm_get_merged_frame_color_image_url($visualizer_frame_url, $bmfm_image_url);
						$product_img_id   = bmfm_set_uploaded_image_as_attachment($merged_visualizer_color_image_url);
						if (!$product_img_id) {
								continue;
						}
					
							 $product_img_ids[] = $product_img_id;
					}
					
						 $post_args = array(
							'post_title'    => $fabricName,
							'post_parent'   => $product_type_id,
							'post_content'  => $product_desc
						 );

						 $meta_args = array(
							'bmfm_category_type'             => 'blinds',
							'bmfm_image_url'                 => $bmfm_image_url,
							'bmfm_frame_url'                 => $url_frame_url,
							'category_ids'                  => $term_id,
							'bmfm_category_ids'              => $term_id,
							'regular_price'                 => 0,
							'price'                         => 0,
							'bmfm_product_post_thumbnail_id' => $product_post_thumbnail_id,  
							'bmfm_product_image_gallery_ids' => $product_img_ids,
							'bmfm_uploaded_frame_pdt_name'   => $product,
						 );
						
						 $fabric_color_product_id = bmfm_create_fabric_color_product($post_args, $meta_args);										
						 bmfm_update_linked_categories_for_fabric_products($fabric_color_product_id, $category_list_ids, $category_sub_lists_products, $pathinfo['filename']);
				}				
			}
			
			if (!empty($blinds_product_data)) {
				$blinds_product_data = wp_json_encode($blinds_product_data);
			} else {
				$blinds_product_data = wp_json_encode($stored_product_data);
			}
			
			wp_send_json_success( 
				array( 
					'success' 				 => true ,
					'percentage'             => 0,
					'offset'                 => $offset+1,
					'blinds_product_data'    => $blinds_product_data,
				)
			);
			
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Contact us submit action.
	 */
	public static function contact_us_action() {
		check_ajax_referer( 'bmfm-contact-us-action-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['form_data']) ) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			if (empty($form_data)) {
				throw new Exception('Invalid Data');
			}
									
			$form_data = isset($form_data['bmfm_settings_data']) && is_array($form_data['bmfm_settings_data']) && !empty($form_data['bmfm_settings_data']) ? array_filter($form_data['bmfm_settings_data']):array();
			if (isset($form_data['contact_us']['email']) && !filter_var($form_data['contact_us']['email'], FILTER_VALIDATE_EMAIL)) {
				throw new Exception('Invalid Email');
			}
			$files = bmfm_get_files();
			$upload     = isset($files['file']) ? wp_handle_upload( $files['file'], array('test_form' => false)):array();
			if (!isset($upload['error'])) {
				$attachment_id_url = '';
				$attachment_url = '';
				if (!empty($upload)) {
					$object = array(
						'post_title'     => basename( $upload['file'] ),
						'post_content'   => $upload['url'],
						'post_mime_type' => $upload['type'],
						'guid'           => $upload['url'],
						'context'        => 'Catalogue',
						'post_status'    => 'public',
					);

					$attachment_id     = wp_insert_attachment( $object, $upload['file'] );
					$attachment_id_url =  wp_get_attachment_url($attachment_id);

					$form_data['bmfm_settings_data']['contact_us']['attachment_id'] = $attachment_id;
					$attachment_url    = !empty($attachment_id) ? array(wp_get_attachment_url($attachment_id)) :array();
				}
				
				update_option('bmfm_stored_data', $form_data);
	
				$api_array =  array(
					'supplier_name' => $form_data['contact_us']['supplier_name'],
					'email' => $form_data['contact_us']['email'],
					'ph_no' => $form_data['contact_us']['ph_no'],
					'name' => $form_data['contact_us']['name'],
					'company_name' => $form_data['contact_us']['company_name'],
					'acc_manager_ph' => $form_data['contact_us']['acc_manager_ph'],
					'acc_manager_email' => $form_data['contact_us']['acc_manager_email'],
					'acc_manager' => $form_data['contact_us']['acc_manager'],
					'attatchment_url' => $attachment_id_url
				);
				
				ob_start();
				include(BMFM_ABSPATH . '/includes/admin/views/html-supplier-details-info.php');
				$message = ob_get_contents();
				ob_end_clean();
		
				wp_mail(get_option('admin_email'), 'Supplier Details', $message, 'Content-Type: text/html', $attachment_url);
								
				if (!class_exists('BMFM_suppllier_Request')) {
					include(BMFM_ABSPATH . '/vendor/Api/class-supplier-request.php'); 
				}			
				BMFM_suppllier_Request::send_request($api_array, 'POST');
			}
			
			update_option('bmfm_stored_data', $form_data);

			wp_send_json_success( 
				array( 
					'success' 				 => true ,
					'msg'     				 => 'Thanks for contacting us. We will get back to you as soon as possible.',
				)
			);
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Remove row action.
	 */
	public static function remove_row_action() {
		check_ajax_referer( 'bmfm-remove-row-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['post_id']) ) {
				throw new Exception('Invalid Data');
			}
			
			$post_id = isset($post['post_id']) ? absint($post['post_id']):'';
			if ('' != $post_id) {
				wp_delete_post($post_id);
			}
			
			wp_send_json_success( array( 'success' 	=> true ));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Upload image custom popup action.
	 */
	public static function upload_image_custom_popup() {
		check_ajax_referer( 'bmfm-upload-image-custom-popup-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post) ) {
				throw new Exception('Invalid Data');
			}
			
			// Create Products - Fabric & Color.
			$dirall = 'ownproduct/roller/*';
			$api           = new SearchApi();
			$results       = $api->expression("folder:$dirall")->maxResults(50)->execute();
			$resources     = $results['resources'];
			
			ob_start();
			include(BMFM_ABSPATH . '/includes/admin/views/html-frame-upload.php');
			$content = ob_get_contents();
			ob_end_clean();
			wp_send_json_success( array( 'success' 	=> true ,'html' => $content));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Category filter action.
	 */
	public static function category_filter_action() {		
		check_ajax_referer( 'bmfm-category-filter-nonce', 'security' );	
		try {
			$post = bmfm_post_method();
			if (!isset($post) ) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			$term_id= isset($post['category_id']) ? absint($post['category_id']):'';
			if (!$term_id) {
				throw new Exception('Invalid Data');
			}
			$orderby = isset($post['orderby']) ? wc_clean(wp_unslash($post['orderby'])):false;
			$selected_categories_data = isset($form_data['bmfm_check_category'])?$form_data['bmfm_check_category']:array();
			$products        = bmfm_get_products_based_on_category_filter($term_id, $selected_categories_data, true, $orderby);
			$products_count  = isset($products->found_posts) ? $products->found_posts:'0';
			ob_start();
			wc_get_template( 'shortcodes/product-list.php', array('products'=>$products,'term_id' => $term_id), '', BMFM_TEMPLATE_PATH);
			$content = ob_get_contents();
			ob_end_clean();
			
			wp_send_json_success( array( 'success' 	=> true ,'html' => $content,'products_count' => $products_count));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Calculate price action.
	 */
	public static function calculate_price() {
		check_ajax_referer( 'bmfm-calculate-price-nonce', 'security' );	
		try {
			$post = bmfm_post_method();
			if (!isset($post) ) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			$blinds_product_data = isset($form_data['bmfm_blinds_product_data']) ? $form_data['bmfm_blinds_product_data']:array() ;
			if (empty($blinds_product_data)) {
				throw new Exception('No Data Found');
			}
			
			$category_id = isset($blinds_product_data['category_id']) ? $blinds_product_data['category_id']:'';
			if (!$category_id) {
				throw new Exception('No Data Found');
			}
			
			$term_object = bmfm_get_term($category_id);
			if (!is_object($term_object)) {
				throw new Exception('No Data Found');
			}
			
			$fabric_color_id = isset($blinds_product_data['fabric_color_id']) ? absint($blinds_product_data['fabric_color_id']):0;
			if (!bmfm_check_is_fabric_color_product($fabric_color_id)) {
				throw new Exception('No Data Found');
			}
			
			$fabric_color_product = bmfm_get_fabric_color_product($fabric_color_id);
			$product_category_type = $term_object->get_product_category_type();
			$product_type_id = isset($blinds_product_data['product_type_id']) ? $blinds_product_data['product_type_id']:'';
			$width = isset($blinds_product_data['width']) ? array_values($blinds_product_data['width']):'';
			$width = isset($width[0]) ? $width[0]:'';
			$drop  = isset($blinds_product_data['drop']) ? array_values($blinds_product_data['drop']):'';
			$drop = isset($drop[0]) ? $drop[0]:'';
			if ('blinds' == $product_category_type && ( !$width || !$drop )) {
				throw new Exception('Width/Drop left empty');
			}
			
			$width_drop_price = 0;
			if ('blinds' == $product_category_type) {
				$width_drop_price = bmfm_get_price_based_on_width_and_height($product_type_id, $width, $drop);
			} 
			
			$accessories_price = 0;
			if ('accessories' == $product_category_type) {
				$accessories_price = is_object($fabric_color_product) ? floatval($fabric_color_product->get_price()):0;
			} 
			
			$selected_component_ids = isset($blinds_product_data['component']) ? array_values($blinds_product_data['component']):array();
			$selected_component_id = isset($selected_component_ids[0]) ? $selected_component_ids[0]:'';
			$component_price = 0;
			$component_type = 'fixed';
			if ($selected_component_id) {
				$component_list = bmfm_get_component_list($selected_component_id);
				if (is_object($component_list)) {
					$markup          = floatval($component_list->get_markup());
					$component_price = floatval($component_list->get_net_price());
					$component_type  = $component_list->get_type();
					if ($markup) {
						$component_price = $component_price * $markup;
					}
				}
			}
			
			$price = floatval($width_drop_price) + $accessories_price;
			if ('percentage' == $component_type) {
				$price = '' != $component_price ? $price + $price * ( absint($component_price)/100 ): $price; 
			} else {
				$price = (float) $price + (float) $component_price; 
			}
			
			wp_send_json_success( array( 'success' 	=> true ,'price' => $price,'price_display' => wc_price($price)));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * View order item detail popup action.
	 */
	public static function view_order_item_detail_popup() {
		check_ajax_referer( 'bmfm-view-order-item-detail-popup-nonce', 'security' );	
		try {
			$post = bmfm_post_method();
			if (!isset($post) || !isset($post['order_id'])) {
				throw new Exception('Invalid Data');
			}
			
			$order_id = isset($post['order_id']) ? absint($post['order_id']):'';
			if (!$order_id) {
				throw new Exception('Invalid Data');
			}
			
			$order       = wc_get_order($order_id); 
			$order_items = $order->get_items();
			if (!is_object($order) || empty($order_items)) {
				throw new Exception('Invalid Data');
			}
			
			$blinds_parameters = array();
			foreach ($order_items as $item_id => $order_item) {
				$blinds_product_data = wc_get_order_item_meta( $item_id, 'bmfm_blinds_parameters', true );
				if (!is_array($blinds_product_data) || empty($blinds_product_data)) {
					continue;
				}
				
				foreach ($order_item['bmfm_blinds_parameters'] as $key => $value) {
					if ('bmfm_blinds_product_data' == $key) {
						continue;
					}
					
					$blinds_parameters[$item_id][$key] = $value;
				}
			}
			
			if (empty($blinds_parameters)) {
				throw new Exception('No Data Found');
			}
						
			ob_start();
			include(BMFM_ABSPATH . '/includes/admin/views/html-orders-table-blinds-parameters.php');
			$content = ob_get_contents();
			ob_end_clean();
			
			wp_send_json_success(array('success'=> true,'html' => $content));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Save fabric color and accessories data row.
	 */
	public static function save_fabric_color_and_accessories_data_row() {
		check_ajax_referer( 'bmfm-save-fabric-color-and-accesories-data-row-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post)) {
				throw new Exception('Invalid Data');
			}
			
			$form_data = wp_parse_args(wp_unslash($post['form_data']));
			if (empty($form_data)) {
				throw new Exception('Invalid Data');
			}
			
			$product_setup_data            = isset($form_data['bmfm_product_setup_data']) ? $form_data['bmfm_product_setup_data']:array();
			$term_id                       = isset($product_setup_data['term_id']) ? $product_setup_data['term_id']:'';
			if (!$term_id) {
				throw new Exception('Invalid Data');
			}
			
			$stored_term  = bmfm_get_term($term_id);
			if (!is_object($stored_term)) {
				throw new Exception('Invalid Data');
			}
			
			$type_id                       = isset($product_setup_data['type_id']) ? $product_setup_data['type_id']:'';
			$category_type_value           = isset($product_setup_data['category_type_value']) ? $product_setup_data['category_type_value']:'';
			$product_list_data             = isset($product_setup_data['product_list_setup']) ? $product_setup_data['product_list_setup']:array();
			if ('accessories' == $category_type_value) {
				$product_list_data = isset($product_setup_data['accessories_list_setup']) ? $product_setup_data['accessories_list_setup']:array();
			}
			
			$type_id                       = isset($product_setup_data['type_id']) ? $product_setup_data['type_id']:'';
			$changed_row                   = isset($product_list_data['changed']) ? $product_list_data['changed']:array();
			$stored_term_id                = isset($product_setup_data['term_id']) ? $product_setup_data['term_id']:'';
			$content                       = '';

			if (!empty($changed_row) && is_array($changed_row)) {
				$changed_row = array_filter($changed_row);
				foreach ($changed_row as $key => $value) {
					$stored_fabric_color_id = isset($product_list_data['post_id'][$key]) ? $product_list_data['post_id'][$key]:'';
					if ('blinds' == $category_type_value) {
						$product_name        = isset($product_list_data['name'][$key]) ? $product_list_data['name'][$key]:'';
						$product_desc        = isset($product_list_data['desc'][$key]) ? $product_list_data['desc'][$key]:'';
						$product_img_url     = isset($product_list_data['image_url'][$key]) ? $product_list_data['image_url'][$key]:'';
						$frame_img_url       = isset($product_list_data['frame_image_url'][$key]) ? $product_list_data['frame_image_url'][$key]:'';
						$material_image_urls = isset($product_list_data['material_image_urls'][$key]) ? $product_list_data['material_image_urls'][$key]:'';
						$material_image_urls = !empty($material_image_urls) ? explode(',', $material_image_urls):array();
						$hide_frame          = isset($product_list_data['hide_frame'][$key]) ? $product_list_data['hide_frame'][$key]:'';
						$price               = '';	
						$_uploaded_frame_pdt_name =  isset($product_list_data['uploaded_frame_pdt_name'][$key]) ? $product_list_data['uploaded_frame_pdt_name'][$key]:'';	
						if (!$_uploaded_frame_pdt_name && 'on' == $hide_frame) {
							$hide_frame = 'on';
						}
						$fabric_color_product = bmfm_get_fabric_color_product($stored_fabric_color_id);
						$post_args = array(
						'post_title'    => $product_name,
						'post_parent'   => 'blinds' == $category_type_value ? $type_id:0,
						'post_content'  => $product_desc,
						);
					  
						$thumbnail_image_url = 'on' != $hide_frame && '' != $frame_img_url ? bmfm_get_merged_frame_color_image_url($frame_img_url, $product_img_url) : $product_img_url;
						$product_post_thumbnail_id = '';
						if ($stored_fabric_color_id) {
							$stored_thumbnail_id  = is_object($fabric_color_product) ? $fabric_color_product->get_image_id():'';
							if ('' != $stored_thumbnail_id) {
								$product_post_thumbnail_id = bmfm_set_uploaded_image_as_attachment($thumbnail_image_url, $stored_thumbnail_id);
							}
						} else {
							$product_post_thumbnail_id = '' != $thumbnail_image_url ? bmfm_set_uploaded_image_as_attachment($thumbnail_image_url):'';
						}
							
						$visualizer_frame_urls = array();
						$image_gallery_ids     = array();
						if ($_uploaded_frame_pdt_name && 'on' != $hide_frame) {
							$visualizer_frame_urls  =  array(
									0 => BMFM_CLOUDURL . 'visualizer-frame/' . $_uploaded_frame_pdt_name . '/visualizer-1.png',
									1 => BMFM_CLOUDURL . 'visualizer-frame/' . $_uploaded_frame_pdt_name . '/visualizer-2.png',
									2 => BMFM_CLOUDURL . 'visualizer-frame/' . $_uploaded_frame_pdt_name . '/visualizer-3.png'
							);
							$stored_product_image_gallery_ids = $fabric_color_product->get_product_image_gallery_ids();
							if (!empty($stored_product_image_gallery_ids)) {
								foreach ($stored_product_image_gallery_ids as $key => $stored_product_image_gallery_id) {
									$visualizer_frame_url = isset($visualizer_frame_urls[$key]) ? $visualizer_frame_urls[$key]:'';
									if (!$visualizer_frame_url) {
										continue;
									}
									$merged_url = bmfm_get_merged_frame_color_image_url($visualizer_frame_url, $product_img_url); 
									$image_gallery_ids[] = bmfm_set_uploaded_image_as_attachment($merged_url, $stored_product_image_gallery_id);
								}
							} else {
								foreach ($visualizer_frame_urls as $visualizer_frame_url) {
									$merged_url = bmfm_get_merged_frame_color_image_url($visualizer_frame_url, $product_img_url); 
									$image_gallery_ids[] = bmfm_set_uploaded_image_as_attachment($merged_url);
								}	
							}
						}
							
						$material_image_attachment_ids = array();
						if (!empty($material_image_urls)) {
							foreach ($material_image_urls as $material_image_url) {
								$material_image_attachment_ids[] = bmfm_set_uploaded_image_as_attachment($material_image_url);
							}
						}
							
						if (!empty($image_gallery_ids) && !empty($material_image_attachment_ids)) {
							$image_gallery_ids = array_merge($image_gallery_ids, $material_image_attachment_ids);
						}
							
						if (!empty($material_image_attachment_ids) && 'on' == $hide_frame) {
							$image_gallery_ids = $material_image_attachment_ids; 		
						}
				
						$fabric_color_image_id = '';
						if ('' != $product_img_url) {
							$fabric_color_image_id = bmfm_set_uploaded_image_as_attachment($product_img_url);	
						}	
							
						$meta_args = array(
						'bmfm_category_type'             => $category_type_value,
						'bmfm_category_ids'  	        => $stored_term_id,
						'category_ids'                  => $stored_term_id,
						'bmfm_image_url'      	        => $product_img_url,
						'bmfm_frame_url'                 => $frame_img_url,
						'bmfm_hide_frame'                => $hide_frame, 
						'bmfm_material_images_url'       => $material_image_urls,
						'regular_price'                 => !empty($price) ? $price:0,
						'price'                         => !empty($price) ? $price:0,
						'bmfm_fabric_color_image_id'     => $fabric_color_image_id, 
						'bmfm_product_post_thumbnail_id' => $product_post_thumbnail_id,
						'bmfm_uploaded_frame_pdt_name'   => $_uploaded_frame_pdt_name,
						'bmfm_product_image_gallery_ids' => $image_gallery_ids,
						);
					} else {
						$product_name        = isset($product_list_data['name'][$key]) ? $product_list_data['name'][$key]:'';
						$product_desc        = isset($product_list_data['desc'][$key]) ? $product_list_data['desc'][$key]:'';
						$product_img_url     = isset($product_list_data['image_url'][$key]) ? $product_list_data['image_url'][$key]:'';
						$price               = isset($product_list_data['price'][$key]) ? $product_list_data['price'][$key]:'';
					
						$post_args    = array(
						'post_title'    => $product_name,
						'post_parent'   => 0,
						'post_content'  => $product_desc,
						);  
						$meta_args = array(
						 'bmfm_category_type'             => $category_type_value,
						 'bmfm_category_ids'  	         => $stored_term_id,
						 'bmfm_image_url'      	         => $product_img_url,
						 'bmfm_frame_url'                 => '',
						 'bmfm_hide_frame'                => '', 
						 'bmfm_material_images_url'       => array(),
						 'category_ids'                  => $stored_term_id,
						 'regular_price'                 => !empty($price) ? $price:0,
						 'price'                         => !empty($price) ? $price:0,
						 'bmfm_uploaded_frame_pdt_name'   => '',
						);  
					  
						if (!empty($product_img_url)) {
							$product_post_thumbnail_id = bmfm_set_uploaded_image_as_attachment($product_img_url);
							$meta_args['bmfm_product_post_thumbnail_id'] = $product_post_thumbnail_id;
						}    
					}

					if (!$stored_fabric_color_id) {
						bmfm_create_fabric_color_product($post_args, $meta_args);
					} else {
						unset($post_args['post_parent']);
						bmfm_update_fabric_color_product($stored_fabric_color_id, $post_args, $meta_args);
					} 
				}

				if ('blinds' == $category_type_value) {	
					$fabric_color_ids = bmfm_get_fabric_color_ids($stored_term_id, false, false, false, 'DESC');
					if (!empty($fabric_color_ids) && is_array($fabric_color_ids)) {
						ob_start();
						$sno = 1;
						foreach ($fabric_color_ids as $key => $fabric_color_id) {
							$fabric_color_product = bmfm_get_fabric_color_product($fabric_color_id);
							if (!is_object($fabric_color_product)) {
								continue;
							}
					
							$image_url               = $fabric_color_product->get_image_url();
							$fabric_color_name       = $fabric_color_product->get_product_name();
							$fabric_color_desc       = $fabric_color_product->get_product()->get_description();
							$frame_url               = $fabric_color_product->get_frame_url();
							$material_images_url     = $fabric_color_product->get_material_images_url();
							$hide_frame              = $fabric_color_product->get_show_or_hide_frame();
							$uploaded_frame_pdt_name = $fabric_color_product->get_uploaded_frame_pdt_name();
							include(BMFM_ABSPATH . '/includes/admin/views/html-fabric-color-list-row.php');
						}
						$content = ob_get_contents();
						ob_end_clean();
					}
				} else {
					$accessories_ids = bmfm_get_accessories_list_ids($stored_term_id);
					if (!empty($accessories_ids) && is_array($accessories_ids)) {
						ob_start();
						$sno = 1;    
						foreach ($accessories_ids as $key => $accessories_color_id) {
							$accessories_color_product = bmfm_get_fabric_color_product($accessories_color_id);
							if (!is_object($accessories_color_product)) :
								 continue;
						  endif;
					
							$name      = $accessories_color_product->get_product_name();
							$desc      = is_object($accessories_color_product->get_product()) ? $accessories_color_product->get_product()->get_description():'';
							$price     = is_object($accessories_color_product->get_product()) ? $accessories_color_product->get_product()->get_price():'';
							$image_url = $accessories_color_product->get_image_url();
					
							include(BMFM_ABSPATH . '/includes/admin/views/html-accessories-color-list-row.php');
						}
						$content = ob_get_contents();
						ob_end_clean();
					}
				}
			}

			wp_send_json_success(array('success'=> true,'html' => $content));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	/**
	 * Delete selected price table column .
	 */
	public static function delete_selected_price_table_row_column() {
		check_ajax_referer( 'bmfm-delete-selected-price-table-row-column-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post)) {
				throw new Exception('Invalid Data');
			}
			$price_table_data = isset($post['price_table_data']) ? wc_clean(wp_unslash($post['price_table_data'])):'';
			$remove_col = isset($post['remove_col']) ? wc_clean($post['remove_col']):'';
			$remove_row = isset($post['remove_row']) ? wc_clean($post['remove_row']):'';
			if ($remove_row) {
				foreach ($remove_row as $r) {
					unset($price_table_data[$r]);
				}
				// Re-index the array
				$price_table_data = array_values($price_table_data);
			}
			if ($remove_col) {
				foreach ($price_table_data as $k => $v ) {
					foreach ($remove_col as $x) {
						unset($price_table_data[$k][$x]);
					}
					// Re-index the inner array
					$price_table_data[$k] = array_values($price_table_data[$k]);
				}   	
			}
			wp_send_json_success(array('success'=> true,'price_table_data' => $price_table_data));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}	
	
	/**
	 * Freemium activation key submit button.
	 */
	public static function freemium_activation_key_submit_button(){
	    check_ajax_referer( 'bmfm-freemium-activation-key-submit-button-nonce', 'security' );
	    try {
			$post = bmfm_post_method();
			if (!isset($post)) {
				throw new Exception('Invalid key');
			}
			
			$activation_key = isset($post['activation_key']) ? wc_clean(wp_unslash($post['activation_key'])):'';
			if(!$activation_key){
			    throw new Exception('Invalid activation key');
			}
			
			$activation_data = base64_decode($post['activation_key']);
			if(empty($activation_data)){
			    throw new Exception('Invalid activation key');
			}
			
			$curent_site_url     = isset($post['site_url']) ? $post['site_url']:'';
			$activation_data_arr = explode(',',$activation_data);
			$site_url            = isset($activation_data_arr[0]) ? $activation_data_arr[0]:'';
			$created_time        = isset($activation_data_arr[1]) ? $activation_data_arr[1]:'';
			if(!$site_url || !$created_time){
			    throw new Exception('Invalid activation key');
			}
			
			if(untrailingslashit($site_url) != $curent_site_url){
			    throw new Exception('Invalid activation key');
			}
			
			$one_day_timestamp = strtotime('+1 day', $created_time);
			if($one_day_timestamp < time()){
			    throw new Exception('Invalid activation key');
			}
			
			$post_id = BMFM_User_Request::get_requested_post_id();
			$timestamp = time();
			if($post_id){
			    $response = BMFM_User_Request::send_request(array('freemium_activated_date' => gmdate('Y-m-d H:i:s', $timestamp),'post_status' => 'freemium','id' => $post_id), 'POST');
			}else{
			    $response = BMFM_User_Request::send_request(array('plugin_activated_date' => gmdate('Y-m-d H:i:s', $timestamp),'freemium_activated_date' => gmdate('Y-m-d H:i:s', $timestamp),'post_status' => 'freemium'), 'POST');
			}
			
			if (!is_object($response) || !isset($response->post_id)) {
			    throw new Exception('Invalid activation key');
			}
			
			update_option('bmfm_plugin_status','freemium');
			
			update_option('bmfm_plugin_saved_date_timestamp',$timestamp);
			
			wp_send_json_success(array('success'=> true));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Contact us action.
	 */
	public static function freemium_contact_us_submit_button(){
	     check_ajax_referer( 'bmfm-freemium-contact-us-submit-button-nonce', 'security' );
	    try {
			$post = bmfm_post_method();
			if (!isset($post)) {
				throw new Exception('Invalid key');
			}
			
			$form_data        = !empty($post['form_data']) ? wp_parse_args($post['form_data']):array();
			$contact_us_data  = !empty($form_data['bmfm_contact_us_data']) ? $form_data['bmfm_contact_us_data']:array();
			$name             = !empty($contact_us_data['name']) ? $contact_us_data['name']:'';
			$company_name     = !empty($contact_us_data['company_name']) ? $contact_us_data['company_name']:'';
			$email            = !empty($contact_us_data['email']) ? $contact_us_data['email']:'';
			$tel              = !empty($contact_us_data['tel']) ? $contact_us_data['tel']:'';
			$site_url         = !empty($contact_us_data['site_url']) ? $contact_us_data['site_url']:'';
			$country          = !empty($contact_us_data['country']) ? $contact_us_data['country']:'';
			
			$email_subject    = 'Blindmatrix e-Commerce Activation Key Request';
			$header_message   = "Thanks for submitting the form. We will contact you shortly.";
			ob_start();
			include(BMFM_ABSPATH . '/includes/admin/views/html-contact-us-email.php');
			$content = ob_get_contents();
			ob_end_clean();

            // Customer email.
            $mailer       = WC()->mailer();
	        $email_object = new WC_Email();
	        $message      = apply_filters( 'woocommerce_mail_content', $email_object->style_inline( $mailer->wrap_message( $email_subject, $content ) ) );
	        bmfm_send_email($email, $email_subject, $message);

			$email_subject    = 'Blindmatrix e-Commerce Activation Key Request';
			$header_message   = false;
			ob_start();
			include(BMFM_ABSPATH . '/includes/admin/views/html-contact-us-email.php');
			$content = ob_get_contents();
			ob_end_clean();
			
			// Admin email.
			$mailer        = WC()->mailer();
	        $email_object = new WC_Email();
	        $message      = apply_filters( 'woocommerce_mail_content', $email_object->style_inline( $mailer->wrap_message( $email_subject, $content ) ) );
            $mail_sent = false;
            if(bmfm_send_email('praveen@blindmatrix.com', $email_subject, $message)) {
                $mail_sent = true;
            } 
            
            if (!$mail_sent) {
				throw new Exception('Unable to submit the form');
			}
			
			wp_send_json_success(array('success'=> true));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}

	/**
	 * Reset all data action.
	 */
	public static function reset_all_data_action() {
		check_ajax_referer( 'bmfm-reset-all-data-action-nonce', 'security' );
		try {
			$post = bmfm_post_method();
			if (!isset($post)) {
				throw new Exception('Invalid Data');
			}
			
			$category_ids  =  isset($post['cat_id']) ? array(wc_clean(wp_unslash($post['cat_id']))):array(); 
			if (empty($category_ids) || !is_array($category_ids)) {
				throw new Exception('Invalid data');
			} 

			if (!bmfm_reset_plugin_data($category_ids)) {
				throw new Exception('No data found');
			}
			
			$reset_data_redirect_url = admin_url('admin.php?page=bmfm_dashboard&bmfm_import=true');
			$stored_category_ids     = bmfm_get_category_ids();
			if (!empty($stored_category_ids)) {
				$reset_data_redirect_url = admin_url('admin.php?page=bmfm_dashboard');
			}
			 
			wp_send_json_success(array('success'=> true,'reset_data_redirect_url' => $reset_data_redirect_url));
		} catch (Exception $ex) {
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
}


BMFM_Admin_Ajax::init();
