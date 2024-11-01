<?php
/**
 * Frontend Scripts
 *
 * @class BMFM_Frontend_Scripts
 */
if (!defined('ABSPATH')) {

	exit; // Exit if accessed directly.

}



/**

 * BMFM_Frontend_Scripts class.

 */
class BMFM_Frontend_Scripts {

	/**

	 * Init.

	 */

	public static function init() {
		add_action('wp_enqueue_scripts', array(__CLASS__,'enqueue_scripts'));
		add_action('wp_enqueue_scripts', array(__CLASS__,'enqueue_styles'));
	}
	
	/**

	 * Enqueue scripts.

	 */
	public static function enqueue_scripts() {
		wp_enqueue_script( 'bmfm-select2', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/vendor/assets/js/frontend/select2.js', array( 'jquery'), BMFM_VERSION );
		wp_enqueue_script( 'bmfm-frontend', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/js/frontend/frontend.js', array( 'jquery','bmfm-select2'), BMFM_VERSION );
		wp_localize_script(
			'bmfm-frontend',
			'bmfm_frontend_params',
			array(
				'ajax_url'                    => admin_url( 'admin-ajax.php' ),
				'category_filter_nonce'       => wp_create_nonce('bmfm-category-filter-nonce'),
				'calculate_price_nonce'       => wp_create_nonce('bmfm-calculate-price-nonce'),
				'category_id'                 => bmfm_get_category_id_based_on_slug(),
				'validate_unsupported_themes' => bmfm_unsupported_themes(),
				'category_type'               => bmfm_get_category_type(),
			)
		);
	}
	
	/**

	 * Enqueue styles.

	 */
	public static function enqueue_styles() {
		// Load Dashicons.
		wp_enqueue_style('dashicons');
		wp_enqueue_style( 'select2_css', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/vendor/assets/css/select2.css', array(), BMFM_VERSION );
		wp_enqueue_style( 'frontend_css', untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/css/frontend.css', array(), BMFM_VERSION );		
		$css = '';
		if (bmfm_is_empty_category_list()) {
			$css .= '.bmfm-woocommerce-product-list{width:100%;} .bmfm-woocommerce-product-filter{display:none;}';
		}
		
		global $post;
		if (!empty($post->ID) && !empty($post->post_type) && 'product' == $post->post_type ) {
			$product = wc_get_product($post->ID);
			$fabric_color_product = is_object($product) ? bmfm_get_fabric_color_product($product->get_id()):false;
			if (bmfm_check_is_fabric_color_product($fabric_color_product->get_id()) && is_object($fabric_color_product) && '' != $fabric_color_product->get_image_url()) {
				$image_url = $fabric_color_product->get_image_url();
				$flatsome = bmfm_unsupported_theme_flatsome();
				$css .= ".woocommerce-product-gallery .woocommerce-product-gallery__image{
				  background:url('$image_url') center center/cover no-repeat; 
				}
				$flatsome";
			}
		}
		
		wp_register_style( 'bmfm-inline-style', false, array(), BMFM_VERSION ); 
		wp_enqueue_style( 'bmfm-inline-style' );
		wp_add_inline_style('bmfm-inline-style', $css);
		
	}
}



BMFM_Frontend_Scripts::init();
