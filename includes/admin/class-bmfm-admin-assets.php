<?php
/**
 * Admin Assets
 *
 * @class BMFM_Admin_Assets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * BMFM_Admin_Assets class.
 */
class BMFM_Admin_Assets {
	/**
	 * Init.
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ), 9 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_style' ), 9 );
	}

	/**
	 * Admin scripts.
	 */
	public static function admin_scripts() {
		wp_enqueue_media();
		global $current_screen;
		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
		$menu_slug = 'blindmatrix-e-commerce';
		$screen_ids = array($wc_screen_id . '-blinds_page_products_list_table','toplevel_page_bmfm_dashboard',$menu_slug.'_page_products_list_table',$menu_slug.'_page_orders_list_table');
		$screen_id = isset($current_screen->id) ? $current_screen->id:'';
		if (!$screen_id || !in_array($screen_id, $screen_ids)) {
			return;
		}
				
		wp_register_script( 'jquery-confirmjs', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/vendor/assets/js/admin/jquery-confirm.js', array(), BMFM_VERSION );
		wp_enqueue_script( 'bmfm-handsontable', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/vendor/assets/js/admin/handsontable.full.min.js', array(), BMFM_VERSION );
		wp_enqueue_script( 'bmfm-country-select', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/vendor/assets/js/admin/countrySelect.min.js', array(), BMFM_VERSION );
		wp_enqueue_script( 'bmfm-admin', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/js/admin/admin.js', array( 'jquery','jquery-blockui','jquery-confirmjs','bmfm-country-select', 'jquery-ui-sortable','select2'), BMFM_VERSION );
		$get_data = bmfm_get_method();
		$product_type_id = isset($get_data['bmfm_product_type_id']) ? wc_clean(wp_unslash($get_data['bmfm_product_type_id'])):'';
		wp_localize_script(
			'bmfm-admin',
			'bmfm_admin_params',
			array(
				'ajax_url'                  		  			=> admin_url( 'admin-ajax.php' ),
				'upload_img_title'          	      			=> 'Choose an image',
				'upload_btn_text'         		      			=> 'Use image',
				'confirm_msg'                		 		    => 'Are you sure you want to Proceed?',
				'import_confirm_msg'                		 	=> 'Are you sure you want to Import Products From Library?',
				'product_save_functionality_nonce'    			=> wp_create_nonce('bmfm-product-save-functionality-nonce'),
				'save_paramater_list_popup_functionality_nonce' => wp_create_nonce('bmfm-save-paramater-list-popup-functionality-nonce'),
				'save_category_list_nonce'                      => wp_create_nonce('bmfm-save-category-list-nonce'),
				'edit_category_list_popup_nonce'                => wp_create_nonce('bmfm-edit-category-list-popup-nonce'),
				'save_category_sublist_nonce'                   => wp_create_nonce('bmfm-save-category-sublist-nonce'),
				'import_button_action_nonce'                    => wp_create_nonce('bmfm-import-button-action-nonce'),
				'contact_us_action_nonce'                       => wp_create_nonce('bmfm-contact-us-action-nonce'),
				'remove_row_nonce'                              => wp_create_nonce('bmfm-remove-row-nonce'),
				'upload_image_custom_popup_nonce'               => wp_create_nonce('bmfm-upload-image-custom-popup-nonce'),
				'edit_parameter_list_rule_nonce'                => wp_create_nonce('bmfm-edit-parameter-list-rule-nonce'),
				'view_order_item_detail_popup_nonce'            => wp_create_nonce('bmfm-view-order-item-detail-popup-nonce'),
				'save_fabric_color_rule_nonce'                  => wp_create_nonce('bmfm-save-fabric-color-rule-nonce'),
				'save_category_selection_dashboard_nonce'       => wp_create_nonce('bmfm-save-category-selection-dashboard-nonce'),
				'reset_all_data_action_nonce'                   => wp_create_nonce('bmfm-reset-all-data-action-nonce'),
				'save_fabric_color_and_accesories_data_row_nonce' => wp_create_nonce('bmfm-save-fabric-color-and-accesories-data-row-nonce'),  
				'delete_selected_price_table_row_column_nonce'  => wp_create_nonce('bmfm-delete-selected-price-table-row-column-nonce'),
				'freemium_activation_key_submit_button_nonce'   => wp_create_nonce('bmfm-freemium-activation-key-submit-button-nonce'),
				'freemium_contact_us_submit_button_nonce'       => wp_create_nonce('bmfm-freemium-contact-us-submit-button-nonce'),
				'parameter_row_html'                            => bmfm_get_blinds_parameter_row_html(),
				'accessories_parameter_row_html'                => bmfm_get_accessories_parameter_row_html(),
				'fabric_color_row_html'               			=> bmfm_get_fabric_color_row_html(),
				'accessories_row_html'                			=> bmfm_get_accessories_row_html(),
				'save_label'                          			=> 'Next',
				'dropdown_parameter_row_html'         			=> bmfm_get_parmeter_list_dropdown_row_html(),
				'component_list_dropdown_row_html'              => bmfm_get_component_list_dropdown_row_html(),
				'category_list_row_html'                        => bmfm_get_category_list_row_html(),
				'category_sublist_row_html'                     => bmfm_get_category_sublist_row_html(),
				'upgrade_premium_html'                          => bmfm_get_upgrade_premium_html(),
				'category_error_msg'                            => 'Please add atleast one category',
				'save_changes_button_name'                      => 'Save changes',
				'add_product_url'                               => admin_url('admin.php?page=products_list_table&bmfm_add_product=1'),
				'edit_product_url'                              => add_query_arg(array('bmfm_cat_id' => isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):''), admin_url('admin.php?page=products_list_table&bmfm_add_product=1')),
				'reset_data_redirect_url'                       => admin_url('admin.php?page=bmfm_dashboard&bmfm_import=true'),
				'content_html'                                  => bmfm_get_delete_content_popup_html(),
				'site_url'                                      => site_url(), 
			)
		);
		
		if ($product_type_id) {
			wp_enqueue_script( 'bmfm-price-table', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/js/admin/price-table.js', array( 'jquery','jquery-blockui','jquery-confirmjs','bmfm-handsontable'), BMFM_VERSION );
			wp_localize_script(
				'bmfm-price-table',
				'bmfm_price_table_params',
				array(
					'ajax_url'                  		  		=> admin_url( 'admin-ajax.php' ),
					'save_price_table_nonce'                    => wp_create_nonce('bmfm-save-price-table-nonce'),
					'product_type_id'                           => $product_type_id,
					'stored_price_table_data'                   => bmfm_get_stored_price_table_data($product_type_id),
					'stored_price_table_data_in_cm'             => bmfm_get_stored_price_table_data_in_cm($product_type_id),
					'stored_price_table_data_in_inch'           => bmfm_get_stored_price_table_data_in_inch($product_type_id),
					'confirm_msg'                               => 'Are you sure you want to proceed?',
					'placeholder_msg'                           => 'Select options'
				)
			);
		}
	}
	/**
	 * Admin style.
	 */
	public static function admin_style() {
		global $current_screen;
		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
		$menu_slug = 'blindmatrix-e-commerce';
		$screen_ids = array($wc_screen_id . '-blinds_page_products_list_table','toplevel_page_bmfm_dashboard',$menu_slug.'_page_products_list_table',$menu_slug.'_page_premium_popup_info',$menu_slug.'_page_orders_list_table');
		$screen_id = isset($current_screen->id) ? $current_screen->id:'';
		if (!$screen_id || !in_array($screen_id, $screen_ids)) {
			return;
		}
				
		wp_enqueue_style( 'jquery-confirmcss', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/vendor/assets/css/jquery-confirm.css', array(), BMFM_VERSION );
		wp_enqueue_style('handsontable_css', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/vendor/assets/css/handsontable.min.css', array(), BMFM_VERSION);
		bmfm_set_country_selection_css();
		wp_enqueue_style( 'admin_css', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/css/admin.css', array(), BMFM_VERSION );
		
		wp_register_style( 'bmfm-admin-inline-style', false , array(), BMFM_VERSION); 
		wp_enqueue_style( 'bmfm-admin-inline-style' );
		$category_ids = bmfm_get_category_ids();
		$css = '';
		if (empty($category_ids) || !is_array($category_ids)) {
			$css = '.toplevel_page_bmfm_dashboard ul .wp-first-item{
					display:none;
				}';
		}
		wp_add_inline_style('bmfm-admin-inline-style', $css);
	}
}

BMFM_Admin_Assets::init();
