<?php

/**
 * Plugin Name: BlindMatrix e-Commerce
 * Description: Sell window blinds, curtains and shutters online with the BlindMatrix e-commerce plugin, which converts your entire website into an e-commerce store, allowing you to sell products online 24/7. It features a product visualizer that lets your customers see and virtually feel the products on the window frame.
 * Version: 2.9
 * Author: Blindmatrix
 * Requires Plugins: woocommerce
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Author URI: https://blindmatrix.com/ecommerce-for-retailers/
 */

defined('ABSPATH') || exit;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

include(dirname(__FILE__) . '/vendor/class-plugin-functions.php');
add_action('init', function() {
	bmfm_ini_set_configuration();
});

add_action('plugins_loaded',function(){
    include(dirname(__FILE__) . '/includes/bmfm-activation-handler.php');
    if (class_exists('BMFM_Activation_Handler') && !BMFM_Activation_Handler::is_woocommerce_active()) {
    	return;
    }
    
    if(class_exists('Blindmatrix_Premium')){
        Blindmatrix_Premium::instance();
        return;
    }
    
    Window_Blinds_Solution::instance();
});

use Cloudinary\Configuration\Configuration;

/**
 * Main Class.
 *
 * @class Window_Blinds_Solution
 */
final class Window_Blinds_Solution {
	/**
	 * Plugin version.
	 */
	public $version = '2.9';

	/**
	 * The single instance of the class.
	 */
	protected static $_instance = null;
	/**
	 * Main Instance.
	 *
	 * Ensures only one instance of object is loaded or can be loaded.
	 *
	 * @return object - Main instance.
	 */
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, 'Cloning is forbidden.', '1.0' );
	}
	
	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, 'Unserializing instances of this class is forbidden.', '1.0' );
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Define Plugin Constants.
	 */
	public function define_constants() {
		define('BMFM_PLUGIN_FILE', __FILE__);
		define('BMFM_ABSPATH', dirname(BMFM_PLUGIN_FILE));
		define('BMFM_VERSION', $this->version);
		define('BMFM_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( BMFM_PLUGIN_FILE) ) . '/templates/');
		define('BMFM_CLOUDNAME', 'dflpfaeif');
		define('BMFM_CLOUDURL', 'https://res.cloudinary.com/' . BMFM_CLOUDNAME . '/image/upload/v1/');
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		//Autoload.
		require dirname( __FILE__) . '/vendor/autoload.php';
		// Cloudinary Config Instance.
		Configuration::instance('cloudinary://645372311759815:Z1ZNL0Hm4lxVGNDLULtelIw6nP0@dflpfaeif?secure=true');
		// Core functions.
		include_once(BMFM_ABSPATH . '/includes/bmfm-core-functions.php');
		// Page Template Handler.
		include_once(BMFM_ABSPATH . '/includes/class-bmfm-page-template-handler.php');
		// User Request.
		include_once(BMFM_ABSPATH . '/vendor/Api/class-user-request.php'); 
		// Supplier Request.
		include_once(BMFM_ABSPATH . '/vendor/Api/class-supplier-request.php'); 
		// Admin Ajax.
		include_once(BMFM_ABSPATH . '/includes/class-bmfm-ajax.php');
		// Register post types.
		include_once(BMFM_ABSPATH . '/includes/class-bmfm-register-post-types.php');
		// Custom post object.
		include_once(BMFM_ABSPATH . '/includes/class-bmfm-custom-post-object.php');
		// Term object.
		include_once(BMFM_ABSPATH . '/includes/class-bmfm-term-object.php');
		// Product object.
		include_once(BMFM_ABSPATH . '/includes/class-bmfm-product-object.php');
		// Install.
		include_once(BMFM_ABSPATH . '/includes/class-bmfm-install.php');
		// Entities.
		// Parameter List.
		include_once(BMFM_ABSPATH . '/entity/class-bmfm-parameter-list.php');
		// Product Type List.
		include_once(BMFM_ABSPATH . '/entity/class-bmfm-product-type-list.php');
		// Component List.
		include_once(BMFM_ABSPATH . '/entity/class-bmfm-component-list.php');
		// Dropdown List.
		include_once(BMFM_ABSPATH . '/entity/class-bmfm-dropdown-list.php');
		// Category List.
		include_once(BMFM_ABSPATH . '/entity/class-bmfm-category-list.php');
		// Category Sublist.
		include_once(BMFM_ABSPATH . '/entity/class-bmfm-category-sublist.php');
		
		if (is_admin()) {
			// Admin menus.
			include_once(BMFM_ABSPATH . '/includes/admin/class-bmfm-admin-menus.php');
			// Admin assets.
			include_once(BMFM_ABSPATH . '/includes/admin/class-bmfm-admin-assets.php');
		}
		
		if(bmfm_is_freemium()){
		    // Frontend handler.
		    include_once(BMFM_ABSPATH . '/includes/frontend/class-bmfm-frontend.php');
		    if (!is_admin()) {
			    // Frontend scripts.
			    include_once(BMFM_ABSPATH . '/includes/frontend/class-bmfm-frontend-scripts.php');
		    }
		}
	}
	
	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {
		register_activation_hook( BMFM_PLUGIN_FILE, array( 'BMFM_Install', 'install' ) );
		register_deactivation_hook( BMFM_PLUGIN_FILE, array( 'BMFM_Install', 'uninstall' ) );
		add_action('plugins_loaded', array('BMFM_Page_Template_Handler','init'));
		add_action('init', array('BMFM_Install','reset_plugin_data_on_init'));
	}
}
