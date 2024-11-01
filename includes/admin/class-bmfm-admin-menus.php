<?php

/**
 * Admin Menu Page
 *
 * @class BMFM_Admin_Menu
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * BMFM_Admin_Menu class.
 */
class BMFM_Admin_Menu {
	/**
	 * Init.
	 */
	public static function init() {
		add_action('admin_menu', array(__CLASS__, 'admin_menu'), 9);
		add_filter('woocommerce_screen_ids', array(__CLASS__,'add_screen_ids'), 999);
		add_action('in_admin_header', array(__CLASS__,'remove_admin_notices'), 1000);
		add_filter('set_screen_option_bmfm_per_page', array( __CLASS__, 'set_items_per_page' ), 10, 3 );
		add_filter('screen_layout_columns', array(__CLASS__, 'on_screen_layout_columns'), 10, 2);
		add_filter('plugin_action_links', array(__CLASS__,'add_plugin_action_links'), 10, 2 );
	}

	/**
	 * Set admin menu.
	 */
	public static function admin_menu() {
		add_menu_page('Window Blinds Solution', 'BlindMatrix e-Commerce', 'manage_options', 'bmfm_dashboard', array(__CLASS__, 'render_dashboard_page'), untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/icon.png', 2);
		$hook = add_submenu_page('bmfm_dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'bmfm_dashboard', array(__CLASS__, 'render_dashboard_page'));
		add_action('load-' . $hook, array(__CLASS__,'on_load_menu'));
		
		$category_ids = bmfm_get_category_ids();
		$display_sub_menu = false;
		$get_data = bmfm_get_method();
		if (( isset($get_data['page']) && 'products_list_table' == $get_data['page'] )) {
			if (isset($get_data['bmfm_add_product']) && '1' == $get_data['bmfm_add_product'] ) {
				$display_sub_menu = true;
			}
			
			if (empty($category_ids)) {
				$display_sub_menu = true;
			}
		}
		
		if (!empty($category_ids) && is_array($category_ids)) {
			$display_sub_menu = true;
		}
		
		if ($display_sub_menu && bmfm_is_freemium()) {
			add_submenu_page('bmfm_dashboard', 'Add Product', 'Add Product', 'manage_options', 'products_list_table', array(__CLASS__, 'render_products_list_table'));
		}
		
		if (!empty($category_ids) && is_array($category_ids) && bmfm_is_freemium()) {
			if ('yes' == get_option('bmfm_is_blinds_order_placed')) {
				add_submenu_page('bmfm_dashboard', 'Orders', 'Orders', 'manage_options', 'orders_list_table', array(__CLASS__, 'render_orders_list_table'));
			}
		}
		add_submenu_page('bmfm_dashboard', '', '<div class="bmfm-go-premium-menu" style="color: #ffb818;"><span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> Buy now</div>', 'manage_options', 'premium_popup_info', array(__CLASS__, 'render_premium_info'));
	}

	/**
	 * Render dashboard page.
	 */
	public static function render_dashboard_page() {
	    if(!bmfm_is_freemium()){
	        include_once BMFM_ABSPATH . '/includes/admin/views/html-welcome-settings.php';
	        return;
	    }
	    
		$get_data = bmfm_get_method();
		if ( !isset($get_data['bmfm_cat_id'])) {
			if (count(bmfm_get_category_ids()) < 1) {
				include_once BMFM_ABSPATH . '/includes/admin/views/html-welcome-settings.php';
			} else if (count(bmfm_get_category_ids()) < 2 && isset($get_data['bmfm_import'])) {
				include_once BMFM_ABSPATH . '/includes/admin/views/html-welcome-settings.php';
			} else {
				$stored_category_ids = bmfm_get_category_ids();
				$cat_id = isset($stored_category_ids[0]) ? $stored_category_ids[0]:'';
				if ($cat_id) {
					wp_safe_redirect(admin_url('admin.php?page=bmfm_dashboard&bmfm_cat_id=' . $cat_id . ''));
					exit;
				}
					
			}
		} else {
			if (isset($get_data['bmfm_cat_id']) && !isset($get_data['bmfm_add_category'])) {
				if (!class_exists('BMFM_Blindmatrix_Products')) {
					require_once BMFM_ABSPATH . '/includes/admin/list-tables/class-bmfm-products-list-table.php';
				}
				$table_object = new BMFM_Blindmatrix_Products();
				$table_object->display();
			} else if (isset($get_data['bmfm_cat_id']) && isset($get_data['bmfm_add_category'])) {
				include_once BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-list.php';
			}
		}
	}
	/**
	 * Render products list table.
	 */
	public static function render_products_list_table() {
	    if(!bmfm_is_freemium()){
	        return;
	    }
	    
			$get_data = bmfm_get_method();
			$stored_cat_id = isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):'';
			$stored_term_object = bmfm_get_term($stored_cat_id);
			$stored_term_object = is_object($stored_term_object) ? $stored_term_object:false;
			
		if (isset($get_data['bmfm_add_product']) && !isset($get_data['bmfm_product_type_id'])) {
			include_once BMFM_ABSPATH . '/includes/admin/views/html-product-setup.php';
		} else if (isset($get_data['bmfm_product_type_id'])) {
			include_once BMFM_ABSPATH . '/includes/admin/views/html-blinds-price-tables.php';
		} else {
			wp_safe_redirect(admin_url('admin.php?page=products_list_table&bmfm_add_product=1&country=gb'));
			exit;
		}
	}
	
	/**
	 * Render orders list table.
	 */
	public static function render_orders_list_table() {
	    if(!bmfm_is_freemium()){
	        return;
	    }
	    
		if (!class_exists('BMFM_Blindmatrix_Orders')) {
			require_once BMFM_ABSPATH . '/includes/admin/list-tables/class-bmfm-orders-list-table.php';
		}
		$table_object = new BMFM_Blindmatrix_Orders();
		$table_object->display();
	}
	
	/**
	 * Add screen ids.
	 */
	public static function add_screen_ids( $screen_ids) {
	    $menu_slug    = 'blindmatrix-e-commerce';
		$screen_ids[] = $menu_slug.'_page_products_list_table';
		$screen_ids[] = 'toplevel_page_bmfm_dashboard';
		$screen_ids[] = $menu_slug.'_page_premium_popup_info';
		$screen_ids[] = $menu_slug.'_page_orders_list_table';
		return $screen_ids;
	}
	
	/**
	 * On Load menu page.
	 */
	public static function on_load_menu() {
		// Set Screen options.
		$screen = get_current_screen();
		add_screen_option(
			'per_page',
			array(
				'default' => 10,
				'option'  => 'bmfm_per_page',
			)
		);
	}
	
	/**
	 * Set items per Page.
	 */
	public static function set_items_per_page( $default, $option, $value ) {
		return 'bmfm_per_page' === $option ? absint( $value ) : $default;
	}
	
	/**
	 * On screen layout columns.
	 */
	public static function on_screen_layout_columns( $columns, $screen) {
		$screen_ids = array('toplevel_page_bmfm_dashboard');
		foreach ($screen_ids as $screen_id) {
			if ($screen == $screen_id) {
				$columns[$screen_id] = 2;
			}
		}
		
		return $columns;
	}
	
	/**
	 * Remove admin notices.
	 */
	public static function remove_admin_notices() {
		global $current_screen;
		$screen_id = isset($current_screen->id) ? $current_screen->id:'';
		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
		if (!$screen_id || !in_array($screen_id, array($wc_screen_id . '-blinds_page_products_list_table','toplevel_page_bmfm_dashboard','blindmatrix-freemium_page_products_list_table'))) {
			return;
		}
	
		remove_all_actions('admin_notices');
		remove_all_actions('all_admin_notices');
	}
	
	/**
	 * Add plugin action links.
	 */
	public static function add_plugin_action_links( $plugin_actions, $plugin_file) {
		if ( basename(plugin_dir_path(BMFM_PLUGIN_FILE)) . '/window-blinds-solution.php' === $plugin_file ) {
			$plugin_actions['bmfm_settings'] = sprintf( '<a href="%s">Settings</a>', esc_url( admin_url( 'admin.php?page=bmfm_dashboard' ) ) );
		}
		return $plugin_actions;
	}
	
	/**
	 * Render premium information.
	 */
	public static function render_premium_info() {
		include(BMFM_ABSPATH . '/includes/admin/views/html-upgrade-premium-info.php');
	}
}

BMFM_Admin_Menu::init();
