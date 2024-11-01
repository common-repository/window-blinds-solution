<?php 
/**
 *
 * User Request REST API class
 *
 */

defined('ABSPATH')||exit;

class BMFM_User_Request {
    
    /**
     * Plugin Status
     */
    protected static $plugin_status;
    
    /**
     * Freemium activated date
     */
    protected static $freemium_activated_date;
    
    /**
     * Response
     */
    protected static $_response;

    public static function send_request( $extra_args = array(), $method = 'GET', $response = false) {
		$url = 'https://plugin.blindssoftware.com/';

        if(site_url()== $url){
            return ;
        }

        $url = untrailingslashit($url) . '/wp-json/bmf/v1/freemium_userslist';
		$user_name_and_pwd = 'admin:Welcome@2021';

        $args =  array(
            'url_info' => site_url(), 
            'ip_address' => self::get_ip_address(),
			'user_info' => self::get_user_info(),
			'plugin_activated_date' => '',
			'plugin_status' => 'activated',
			'reports' => self::get_reports(),
			'id'  =>'',
			'product_count' =>self::get_product_details()
        );

        if (is_object($response) && $response->url_info) {
			unset($args['url_info']);
		}
		
		if (is_object($response) && $response->ip_address) {
			unset($args['ip_address']);
		}
		
		if (is_object($response) && $response->user_info) {
			unset($args['user_info']);
		}
		
		if (is_object($response) && 'premium' == $response->post_status) {
			$args['premium_site_url'] = site_url();
			$args['premium_user_info'] = self::get_ip_address();
			$args['premium_ip_address'] = self::get_user_info();
		}
		
		if (!empty($extra_args) && is_array($extra_args)) {
			$args = array_merge($args, $extra_args);
		}

		if('GET' == $method){
			unset($args['reports']);
		}

        $curl = curl_init();

        switch ($method) {
			case 'POST':
			case 'PUT':
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($args) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
				}
				break;
			default:
				if ($args) {
					$url = sprintf('%s?%s', $url, http_build_query($args));
				}
		}

        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $user_name_and_pwd);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);

        if (curl_errno($curl)) {
			return;
		}

        curl_close($curl);
        return json_decode($result);
    }

    public static function get_ip_address() {
		$server = $_SERVER;
		if ( isset( $server['HTTP_X_REAL_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $server['HTTP_X_REAL_IP'] ) );
		} elseif ( isset( $server['HTTP_X_FORWARDED_FOR'] ) ) {
			// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
			// Make sure we always only send through the first IP in the list which should always be the client IP.
			return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $server['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
		} elseif ( isset( $server['REMOTE_ADDR'] ) ) {
			return sanitize_text_field( wp_unslash( $server['REMOTE_ADDR'] ) );
		}
		return '';
	}

    public static function get_user_info() {
		return serialize(array(
			'userid'       => get_current_user_id(),
			'from_name'    => !empty(get_option('woocommerce_email_from_name')) ? get_option('woocommerce_email_from_name'):get_bloginfo('name'),
			'from_address' => !empty(get_option('woocommerce_email_from_address')) ? get_option('woocommerce_email_from_address'): get_bloginfo('admin_email'),
		));
	}

    public static function get_reports(){
    	if(!function_exists('WC')){
			return '';
		}
        
        return '';
    // 	$reports = WC()->api->get_endpoint_data( '/wc/v3/system_status' );
    //     if(!empty($reports)){
    //     	return json_encode($reports);
    //     }
    }

	public static function get_product_details(){
        $products_obj = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1 ) );
		return !empty($products_obj->posts) && is_array($products_obj->posts) ? count($products_obj->posts) : 0;
		
	}

    public static function get_requested_post_id() {
        $response = self::get_response();
        $post_id = get_option('bm_requested_post_id'); // have to do the add_option() 
        if (is_object($response) && isset($response->post_id)){
			return $response->post_id;
		}
        return '';    
    }
    
    public static function get_status(){
        if(isset(self::$plugin_status)){
            return self::$plugin_status;
        }
        
        $response = self::get_response();
        if (!is_object($response) || !isset($response->post_data->post_status)) {
            return '';
        }
            
        self::$plugin_status = !empty($response->post_data->post_status) ? $response->post_data->post_status:'';
        return self::$plugin_status;
    }
    
    public static function get_freemium_activated_date(){
        if(isset(self::$freemium_activated_date)){
            return self::$freemium_activated_date;
        }
        
        $response = self::get_response();
        if (!is_object($response) || !isset($response->post_data->post_status)) {
            return '';
        }
            
        self::$freemium_activated_date = !empty($response->post_data->freemium_activated_date) ? strtotime($response->post_data->freemium_activated_date):'';    
        return self::$freemium_activated_date;
    }
    
    public static function get_response(){
        if(isset(self::$_response)){
            return self::$_response;
        }
        
        self::$_response = self::send_request(array('url_info' => wp_unslash(site_url())), 'GET');
        return self::$_response;
    }
}
