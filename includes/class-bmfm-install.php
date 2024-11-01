<?php
/**
 * Installation related functions and actions.
 *
 * @class BMFM_Install
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * BMFM_Install class.
 */
class BMFM_Install {
	
	/**
	 * Init.
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'redirect_url_on_activation' ), 5 );
		add_action('init',array(__CLASS__,'upgrade_plugin_status'));
	}

	/**
	 * Install.
	 */
	public static function install() {
		self::create_pages();
		bmfm_create_menu_items();

		update_option('bmfm_blinds_plugin_activated', '1');
		$post_id = BMFM_User_Request::get_requested_post_id();
		if ($post_id) {
			$response = BMFM_User_Request::send_request(array('id' => $post_id,'plugin_status' => 'activated'), 'POST');
		} 

	}
	
	/**
	 * Reset plugin data on page load.
	 */
	public static function reset_plugin_data_on_init() {
		$get_data = bmfm_get_method();
		$reset_option =  isset($get_data['blindmatrix_reset_options'])? wc_clean(wp_unslash($get_data['blindmatrix_reset_options'])):'';
		if ('yes' == $reset_option) {
			delete_option('bmfm_requested_post_id');
			//Reset entire plugin data 
			bmfm_reset_plugin_data();
		}
	}

	/**
	 * Uninstall.
	 */
	public static function uninstall() {
		bmfm_delete_menu_items();
		$delete_page_keys = array('freemium','shop_blinds','shop_accessories');
		foreach ($delete_page_keys as $key) {
			$page_id = get_option('bmfm_' . $key . '_page_id');
			wp_delete_post($page_id, true);
			delete_option('bmfm_' . $key . '_page_id');
		}
		$post_id = BMFM_User_Request::get_requested_post_id();
		$timestamp = time();
		if ($post_id) {
			$response = BMFM_User_Request::send_request(array('id' =>$post_id ,'plugin_status' => 'deactivated'), 'POST');
		}
	}

	/**
	 * Create pages.
	 */
	public static function create_pages() {
		$pages = array(
				'freemium'    => array(
					'name'    => 'freemium',
					'title'   => 'Freemium',
					'content' => '<!-- wp:shortcode -->[BlindMatrix source="freemium"]<!-- /wp:shortcode -->',
					'validate_page_content' => true,
				),
				'shop_blinds'    => array(
					'name'    => 'shop_blinds',
					'title'   => 'Shop Blinds',
					'content' => '<!-- wp:shortcode -->[BlindMatrix source="blinds_and_accessories_List"]<!-- /wp:shortcode -->',
					'validate_page_content' => true,
				),
				'shop_accessories'    => array(
					'name'    => 'shop_accessories',
					'title'   => 'Shop Accessories',
					'content' => '<!-- wp:shortcode -->[BlindMatrix source="blinds_and_accessories_List"]<!-- /wp:shortcode -->',
					'validate_page_content' => true,
				),
		);
		foreach ( $pages as $key => $page ) {
			bmfm_create_page(
				esc_sql( $page['name'] ),
				'bmfm_' . $key . '_page_id',
				$page['title'],
				$page['content'],
				0,
				'publish',
				$page['validate_page_content']
			);
		}
	}
	
	/**
	 * Redirect URL on activation.
	 */
	public static function redirect_url_on_activation() {
		if (get_option('bmfm_blinds_plugin_activated')) {
			delete_option('bmfm_blinds_plugin_activated');
			wp_safe_redirect(admin_url('admin.php?page=bmfm_dashboard'));
			exit;
		}
	}
	
	/**
	 * Upgrade plugin status.
	 */
	public static function upgrade_plugin_status(){
	    $saved_date_timestamp = BMFM_User_Request::get_freemium_activated_date();
	    if($saved_date_timestamp && bmfm_is_freemium()){
	        $remaining_timestamp  = strtotime('+30 days',$saved_date_timestamp);
		    if (time() > $remaining_timestamp ) {
		        update_option('bmfm_plugin_status','expired');
		        $post_id  = BMFM_User_Request::get_requested_post_id();
		        $response = BMFM_User_Request::send_request(array('expired_date' => gmdate('Y-m-d H:i:s', time()),'post_status' => 'expired','id' => $post_id), 'POST');
		        if(isset($response->post_id)) {
		            return;
		        }
		    }
	    }

		$post_id  = BMFM_User_Request::get_requested_post_id();
		if(!bmfm_is_freemium() && !$post_id){
	        $timestamp = time();
			$response = BMFM_User_Request::send_request(array('plugin_activated_date' => gmdate('Y-m-d H:i:s', $timestamp),'freemium_activated_date' => gmdate('Y-m-d H:i:s', $timestamp),'post_status' => 'freemium'), 'POST');
	    }
	}
}

BMFM_Install::init();
