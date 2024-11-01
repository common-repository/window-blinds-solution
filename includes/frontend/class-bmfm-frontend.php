<?php
/**
 * Frontend functionality
 *
 * @class BMFM_Frontend
 */
if (!defined('ABSPATH')) {

	exit; // Exit if accessed directly.

}



/**

 * BMFM_Frontend class.

 */
class BMFM_Frontend {

	/**

	 * Init.

	 */

	public static function init() {
		add_shortcode('BlindMatrix', array(__CLASS__,'blindmatrix_shortcode'));
		
		add_filter('wp_get_attachment_image_src', array(__CLASS__, 'alter_product_list_image'), 999, 1);
		add_filter('wp_get_attachment_image', array(__CLASS__, 'bmfm_attachment_image_filter'), 9999, 4);
		add_action('bmfm_archive_description', array(__CLASS__,'render_description'));
		add_action( 'bmfm_woocommerce_color_img_shop_loop_item_title', array(__CLASS__, 'add_fabric_color_img_list_page'), 10 );
		
		add_filter('woocommerce_single_product_image_thumbnail_html', array(__CLASS__,'filter_image_thumbnail_html'), 999, 2);
		add_action('woocommerce_before_add_to_cart_button', array(__CLASS__,'render_blinds_info'), 10);
		add_action('woocommerce_before_single_product', array(__CLASS__,'render_blinds_product_title'), 10);
		add_filter('woocommerce_get_price_html', array(__CLASS__,'alter_price_html'), 999, 2);		
		add_filter('woocommerce_loop_add_to_cart_link', array(__CLASS__,'alter_loop_add_to_cart_link'), 999, 2);
		add_action('woocommerce_before_add_to_cart_quantity', array(__CLASS__,'render_price_html_after_add_to_cart_qty'));
		add_filter('woocommerce_add_cart_item_data', array(__CLASS__,'add_cart_item_data'), 999, 2);
		add_filter('woocommerce_get_item_data', array( __CLASS__, 'render_cart_item_data' ), 999, 2 );	
		add_action('woocommerce_before_calculate_totals', array( __CLASS__, 'before_calculate_totals' ), 9999, 1 );
		add_filter('body_class', array(__CLASS__,'add_custom_body_classes'), 999);
		
		add_action('woocommerce_checkout_create_order_line_item', array( __CLASS__,'create_order_line_item' ), 999, 4);
		add_action('woocommerce_checkout_update_order_meta', array(__CLASS__,'checkout_update_order_meta'), 999);
		
		add_filter('woocommerce_add_to_cart_validation', array(__CLASS__,'add_to_cart_validation'), 999, 2);
		
		add_action( 'bmfm_woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 999 );
		add_action( 'bmfm_woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 999 );
		add_action( 'bmfm_woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 999 );
		add_action( 'bmfm_woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 999 );	
		add_action( 'bmfm_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 999 );
		add_action( 'bmfm_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 995 );	
		add_action( 'bmfm_woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 995 );	
		add_action( 'bmfm_woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 999 );
		add_filter('woocommerce_email_order_items_args', array( __CLASS__, 'custom_email_order_items_args'), 999, 1);
	}
		
	/**
	
	 * Adding frame to image source

	 */
	public static function alter_product_list_image( $image ) {
		if (is_admin() && !wp_doing_ajax()) {
			return $image;
		}
		
		if (is_product()) {
			global $woocommerce_loop;
			if (empty($woocommerce_loop['name'])) {
				return $image;
			}
		}
		
		if (!bmfm_check_is_fabric_color_product(get_the_ID())) {
			return $image;
		}
		
		$product = bmfm_get_fabric_color_product(get_the_ID());
		if (is_object($product) && '' != $product->get_image_url()) {
			$image[0] = $product->get_image_url();
		}
		
		return $image;
	}
	
	/**
	
	 * Adding fabric image to background

	 */
	public static function bmfm_attachment_image_filter( $content, $attachment_id, $size, $attr) {
		if (is_admin() && !wp_doing_ajax()) {
			return $content;
		}
		
		if (is_product()) {
			global $woocommerce_loop;
			if (empty($woocommerce_loop['name'])) {
				return $content;
			}
		}
		
		if (!bmfm_check_is_fabric_color_product(get_the_ID())) {
			return $content;
		}
		
		$product = bmfm_get_fabric_color_product(get_the_ID());
		$fabric_color_product = $product->get_show_or_hide_frame();
		$fabric_hide_frame = 'bmfm_hide_frame_' . $fabric_color_product;
		
		if (is_object($product) && '' != $product->get_frame_url() ) {
			
			?>
			<style>
			.custom-image-wrapper-<?php echo wp_kses_post(get_the_ID()); ?>::before {
				 content: ""; 
				  position: absolute;
				  top: 0;
				  left: 0;
				  width: 100%;
				  height: 100%;
				  background:url('<?php echo esc_url($product->get_frame_url()); ?>'); 
				  z-index: 1; 
				  background-repeat: no-repeat;
				  background-size: cover;
			}
			</style>
			<?php 
				ob_start();
			?>
			<div style="position: relative; width: 100%; height: 100%;" class="custom-image-wrapper custom-image-wrapper-<?php echo wp_kses_post(get_the_ID()); ?> <?php echo wp_kses_post($fabric_hide_frame); ?> "><?php echo wp_kses_post($content); ?></div>
			<?php
			$content = ob_get_contents();
			ob_end_clean();
		}
		return $content;
	}
	
	/**
	
	 * Render blindmatrix shortcode

	 */
	public static function blindmatrix_shortcode( $attrs, $content = null) {
		$attrs = shortcode_atts(
			array(
				'title' => 'true',
				'desc' => 'true',
				'price' => 'true',
				'products' => '',
				'style'=> 'layout1',
				'source'=>'',
			), $attrs, 'BlindMatrix');
		if (isset($attrs['source'])) {
			$file = wp_strip_all_tags($attrs['source']);
			if ('/' != $file[0]) {
				$theme_file_path = get_stylesheet_directory() . '/' . basename( plugin_dir_url(__FILE__) ) . '/' . $file . '.php';
				$file = BMFM_TEMPLATE_PATH . '/shortcodes/' . $file . '.php';
				if (file_exists($theme_file_path )) {
					$file = $theme_file_path;
				}
				/**
				 * Filters the blindmatrix shortcode path.
				 *
				 * @since 1.0
				 */
				$file = apply_filters('blindmatrix_shortcode_path', $file, $attrs);
			}

			if (!file_exists($file)) {
				return;
			}
		
			ob_start();
			include($file);
			$buffer = ob_get_clean();
			$buffer = do_shortcode($buffer);
		}
		return $buffer;
	}
	
	/**
	
	 * Render product description

	 */
	public static function render_description( $term_id) {
		$term_object = bmfm_get_term($term_id);
		if (!is_object($term_object)) {
			return;
		}
			
		echo sprintf('<span class="bmfm-archive-description">%s</span>', wp_kses_post($term_object->get_description()));
	}

	/**
	
	 * Render fabric color image in list page 

	 */
	public static function add_fabric_color_img_list_page() {
		global $product;
		$fabric_color_product = bmfm_get_fabric_color_product($product->get_id());
		$fabric_color_image_url = $fabric_color_product->get_image_url();
		$category_ids = $product->get_category_ids();
		$category_id  = isset($category_ids[0])? $category_ids[0]:'';
		$term_object = bmfm_get_term($category_id);
		
		if (!empty($fabric_color_image_url) && !( 'accessories' == $term_object->get_product_category_type() )) :
			?>
		<img class="bmfm-custom-fabric-img" src="<?php echo esc_url($fabric_color_image_url); ?>"  width="100" height="100" />
			<?php
		endif;
	}


	/**
	
	 * Render blinds product title in product page

	 */
	public static function render_blinds_product_title() {
		global $post;
		if (!isset($post->ID) || !$post->ID) {
			return $classes;
		}
			
		$product = wc_get_product($post->ID);
		if (!bmfm_check_is_fabric_color_product($post->ID)) {
			return;
		}
		
		$category_ids = $product->get_category_ids();
		$category_id  = isset($category_ids[0])? $category_ids[0]:'';
		if (!$category_id) {
			return;
		}
		
		$term_object = bmfm_get_term($category_id);
		if (!is_object($term_object)) {
			return;
		}
		
		$product_list_page_id = bmfm_get_listing_page_id($category_id);
		if ($product_list_page_id) :
			?>
			<p class="bmfm-click-back-category-url">
				<a href="<?php echo esc_url(bmfm_get_frontend_product_list_page_url($category_id)); ?>"><span class="dashicons dashicons-arrow-left-alt2"></span> Back to <?php echo wp_kses_post(strtolower($term_object->get_name())); ?></a>
			</p>
			<?php
			if (!( $term_object->get_product_category_type() == 'accessories' )) {
				?>
				<h1 class="bmfm-title entry-title"><?php echo wp_kses_post($term_object->get_name() . ' - ' . $product->get_name()); ?></h1>
				<?php
			} else {
				?>
				<h1 class="bmfm-title entry-title"><?php echo wp_kses_post($product->get_name()); ?></h1>
				<?php
			}
		endif;
	}
	
	/**
	
	 * Filter image thumbnail HTML in product page

	 */
	public static function filter_image_thumbnail_html( $html, $post_thumbnail_id) {
		global $product;
		if (!is_object($product)) {
			return $html;
		}
		
		$fabric_color_product = bmfm_get_fabric_color_product($product->get_id());
		if (!is_object($fabric_color_product)) {
			return $html;
		}
		
		if (!bmfm_check_is_fabric_color_product($product->get_id())) {
			return $html;
		}
		
		if (!$fabric_color_product->get_category_type() || 'blinds' == $fabric_color_product->get_category_type()) {
			return $html;
		}
				
		if ( '' != $fabric_color_product->get_image_url()) {
			ob_start();
			?>
			<div style="position: relative; width: 100%; height: 100%;" class="woocommerce-product-gallery__image--placeholder custom-image-wrapper custom-image-wrapper-<?php echo wp_kses_post($product->get_id()); ?>"><?php echo sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url($fabric_color_product->get_image_url()), esc_html__( 'Awaiting product image', 'woocommerce' ) ); ?></div>
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		
		return $html;
	}

	/**
	
	 * Render blinds information in product page

	 */
	public static function render_blinds_info() {
		global $product;
		if (!is_object($product) || empty($product->get_category_ids())) {
			return;
		}
		
		if (!bmfm_check_is_fabric_color_product($product->get_id())) {
			return;
		}
	
		$fabric_color_product = bmfm_get_fabric_color_product($product->get_id());
		if (!$fabric_color_product->get_category_type()) {
			return;
		}
		
		$parameter_list_ids = bmfm_get_parameter_list_ids($product->get_category_ids(), false, $fabric_color_product->get_category_type());
		if (empty($parameter_list_ids) || !is_array($parameter_list_ids)) {
			return;
		}
		
		wc_get_template( 'single-product/blinds-parameters.php', array('product'=>$product,'parameter_list_ids' => $parameter_list_ids), '', BMFM_TEMPLATE_PATH);
	}

	/**
	
	 * Add custom body classes

	 */
	public static function add_custom_body_classes( $classes) {
		$shop_blinds_page_id = bmfm_get_shop_blinds_page_id();
		$shop_accessories_page_id = bmfm_get_shop_accessories_page_id();
		$bmfm_blinds_list_page_id = bmfm_get_freemium_page_id();
		global $post;
		if (is_product()) {
			if (!isset($post->ID)) {
				return $classes;
			}
			
			$product = wc_get_product($post->ID);
			if (!bmfm_check_is_fabric_color_product($product->get_id())) {
				return $classes;
			}
			
			$classes[] = 'bmfm-fabric-color-product-wrapper';
		} else if (isset($post->ID) && $shop_blinds_page_id && $shop_accessories_page_id && in_array($post->ID, array($shop_blinds_page_id,$shop_accessories_page_id))) {
			$classes[] = 'bmfm-fabric-list-wrapper';
		} else if (isset($post->ID) && $post->ID  == $bmfm_blinds_list_page_id) {
			$classes[] = 'bmfm-blinds-list-wrapper';
			
		}
		return $classes;
	}
	
	/**
	
	 * Alter price HTML

	 */
	public static function alter_price_html( $price, $product) {
		if (!bmfm_check_is_fabric_color_product($product->get_id())) {
			return $price;
		}
		
		if ('accessories' == bmfm_get_category_type($product->get_id())) {
			return $price;
		}

		
		if (!is_product()) {
			return '';
		}
		
		return $price;
	}
	
	/**
	
	 * Alter loop add to cart link

	 */
	public static function alter_loop_add_to_cart_link( $add_to_cart_link, $product) {
		if (!bmfm_check_is_fabric_color_product($product->get_id())) {
			return $add_to_cart_link;
		}
		$args['class'] = 'button bmfm-read-more-button';
		return sprintf(
			'<a href="%s" class="%s"><span class="dashicons dashicons-cart bmfm-read-more-cart-icon"></span>%s</a>',
					esc_url( $product->get_permalink() ),
					esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
					'Buy Now'
		);
	}
	
	/**
	
	 * Alter loop add to cart link

	 */
	public static function render_price_html_after_add_to_cart_qty() {
		global $post;
		if (!isset($post->ID)) {
			return;
		}
			
		$product = wc_get_product($post->ID);
		if (!bmfm_check_is_fabric_color_product($product->get_id())) {
			return;
		}
		
		echo '<div class="bmfm-display-price-info"><div class="bmfm-your-price-text">Your Price</div><div class="bmfm-display-price"></div></div>';
	}
	
	/**
	
	 * Add cart item data.

	 */
	public static function add_cart_item_data( $cart_item_data, $product_id) {
		$request = bmfm_request_method();
		$blinds_product_data = isset($request['bmfm_blinds_product_data']) ? wc_clean(wp_unslash($request['bmfm_blinds_product_data'])):array();
		if (!empty($blinds_product_data) && is_array($blinds_product_data)) {
			$cart_item_data['bmfm_blinds_product_data'] = $blinds_product_data;
		}
		
		return $cart_item_data;
	}
	
	/**
	
	 * Set price on before calculate totals.

	 */
	public static function before_calculate_totals( $cart_obj) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		
		$cart = $cart_obj->get_cart();
		if (empty($cart) || !is_array($cart)) {
			return;
		}
		
		foreach ( $cart as $cart_value ) {
			$price = isset($cart_value['bmfm_blinds_product_data']['bmfm_fabric_color_price']) ? $cart_value['bmfm_blinds_product_data']['bmfm_fabric_color_price']:'';
			$product_name = isset($cart_value['bmfm_blinds_product_data']['product_name']) ? $cart_value['bmfm_blinds_product_data']['product_name']:'';
			if ('' != $price && '' != $product_name) {
				$cart_value['data']->set_price($price);
				$cart_value['data']->set_name($cart_value['bmfm_blinds_product_data']['product_name']);
			}
		}			
	}
	
	/**
	
	 * Render cart item data.

	 */
	public static function render_cart_item_data( $item_data, $cart_item) {
		$blind_args = self::get_item_metas($cart_item);			
		if (empty($blind_args)) {
			return $item_data;
		}
			
		foreach ($blind_args as $key => $value) {
			$item_data[] = array(
				'key'   => $key,
				'value' => $value,
			);
		}
		
		return $item_data;
	}
	
	/**
	 * Create order line item.
	 */
	public static function create_order_line_item( $item, $cart_item_key, $values, $order) {
		$blinds_product_data = isset($values['bmfm_blinds_product_data']) ? $values['bmfm_blinds_product_data']:array();
		$blind_args = self::get_item_metas($values);	
		if (empty($blind_args)) {
			return;
		}
		
		if (!empty($blinds_product_data) && is_array($blinds_product_data)) {
			$blind_args['bmfm_blinds_product_data'] = $blinds_product_data;
			$blind_args['bmfm_blinds_parameters']   = $blind_args;
		}

		foreach ($blind_args as $key => $value) {
			$item->update_meta_data( $key, $value );
		}
		$item->save();
	}
	
	/**
	
	 * Get item metas.

	 */
	public static function get_item_metas( $values) {
		$blinds_product_data = isset($values['bmfm_blinds_product_data']) ? $values['bmfm_blinds_product_data']:array();
		if (empty($blinds_product_data) || !is_array($blinds_product_data)) {
			return array();
		}
		
		$unit                     = isset($blinds_product_data['unit']) ? $blinds_product_data['unit'] : 'mm';
		$width_parameter_data     = isset($blinds_product_data['width']) ? $blinds_product_data['width'] : array();
		$drop_parameter_data      = isset($blinds_product_data['drop']) ? $blinds_product_data['drop'] : array();
		$text_parameter_data      = isset($blinds_product_data['text']) ? $blinds_product_data['text'] : array();
		$dropdown_parameter_data  = isset($blinds_product_data['dropdown']) ? $blinds_product_data['dropdown'] : array();
		$component_parameter_data = isset($blinds_product_data['component']) ? $blinds_product_data['component'] : array();
			
		$blind_args = array();
		if (!empty($width_parameter_data)) {
			// Width.
			foreach ($width_parameter_data as $width_parameter_id => $width_value) {
				$parameter_list = bmfm_get_parameter_list($width_parameter_id);
				if (!is_object($parameter_list) || !$width_value) {
					continue;
				}
				
				$blind_args[$parameter_list->get_parameter_name()] = $width_value . ' ' . $unit;
			}
		}
		
		if (!empty($drop_parameter_data)) {
			// Drop.	
			foreach ($drop_parameter_data as $drop_parameter_id => $drop_value) {
				$parameter_list = bmfm_get_parameter_list($drop_parameter_id);
				if (!is_object($parameter_list) || !$drop_value) {
					continue;
				}
				
				$blind_args[$parameter_list->get_parameter_name()] = $drop_value . ' ' . $unit;
			}
		}
		
		if (!empty($text_parameter_data)) {
			// Text.
			foreach ($text_parameter_data as $text_parameter_id => $text_value) {
				$parameter_list = bmfm_get_parameter_list($text_parameter_id);
				if (!is_object($parameter_list) || !$text_value) {
					continue;
				}
				
				$blind_args[$parameter_list->get_parameter_name()] = $text_value;
			}
		}
		
		if (!empty($dropdown_parameter_data)) {
			// dropdown.
			foreach ($dropdown_parameter_data as $dropdown_parameter_id => $dropdown_id) {
				$parameter_list = bmfm_get_parameter_list($dropdown_parameter_id);
				if (!is_object($parameter_list)) {
					continue;
				}
			
				$dropdown_list_object = bmfm_get_dropdown_list($dropdown_id);
				if (!is_object($dropdown_list_object) || '' == $dropdown_list_object->get_name()) {
					continue;
				}
				
				$blind_args[$parameter_list->get_parameter_name()] = $dropdown_list_object->get_name();
			}
		}
		
		if (!empty($component_parameter_data)) {
			// component.
			foreach ($component_parameter_data as $component_parameter_id => $component_id) {
				$parameter_list = bmfm_get_parameter_list($component_parameter_id);
				if (!is_object($parameter_list)) {
					continue;
				}
			
				$component_list_object = bmfm_get_component_list($component_id);
				if (!is_object($parameter_list) || '' == $component_list_object->get_name()) {
					continue;
				}
								
				$blind_args[$parameter_list->get_parameter_name()] = $component_list_object->get_name();
			}
		}
		
		return $blind_args;
	}
	
	/**
	
	 * Checkout update order meta.

	 */
	public static function checkout_update_order_meta( $order_id) {
		$order = wc_get_order($order_id);
		if (!is_object($order)) {
			return;
		}
		
		if ($order->get_meta( '_bmfm_blind_product_in_order' , true)) {
			return;
		}
		
		$order_items = $order->get_items();
		$is_blind_product_in_order = false;
		foreach ($order_items as $item_id => $order_value) {
			$blinds_product_data = wc_get_order_item_meta( $item_id, 'bmfm_blinds_product_data', true );
			if (empty($blinds_product_data)) {
				continue;
			}
			
			$is_blind_product_in_order = true;
		}
		
		if ($is_blind_product_in_order) {
			$order->update_meta_data( '_bmfm_blind_product_in_order', '1' );
			$order->save();
			update_option('bmfm_is_blinds_order_placed', 'yes');
		}
	}
	
	/**
	
	 * Add to cart validation.

	 */
	public static function add_to_cart_validation( $bool, $product_id) {
		if (!bmfm_check_is_fabric_color_product($product_id)) {
			return $bool;
		}
		
		$fabric_color_product = bmfm_get_fabric_color_product($product_id);
		if (is_object($fabric_color_product) && 'accessories' == $fabric_color_product->get_category_type()) {
			return $bool;
		}
		
		$request = bmfm_request_method();
		if (!isset($request['bmfm_blinds_product_data']['width'], $request['bmfm_blinds_product_data']['drop'], $request['bmfm_blinds_product_data']['bmfm_fabric_color_price']) || empty($request['bmfm_blinds_product_data']['width']) || empty($request['bmfm_blinds_product_data']['drop']) || empty($request['bmfm_blinds_product_data']['bmfm_fabric_color_price'])) {
			wc_add_notice('Width , Drop & Price cannot be empty', 'error');
			$bool = false;
		}
		
		return $bool;
	}	
	
	/**
	 * Custom email - order item arguments.
	 */
	public static function custom_email_order_items_args( $args) {
		$args['show_image'] = true;
		return $args;
	}
	
}

BMFM_Frontend::init();
