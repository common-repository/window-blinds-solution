<?php
/**
 * Blindmatrix Activation Handler
 *
 * Trigger after plugin activation.
 *
 */

defined('ABSPATH') || exit;

/**
 * BMFM_Activation_Handler class.
 */
class BMFM_Activation_Handler {
	
	/**
	 * Init.
	 */
	public static function init() {
		add_action( 'plugins_loaded', array(__CLASS__,'plugins_loaded') );
	}

	/**
	 * On plugins loaded trigger notices.
	 */
	public static function plugins_loaded() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', array(__CLASS__,'woocommerce_missing_wc_notice') );
			return;
		}		
	}
	
	/**
	 * WooCommerce missing notice.
	 */
	public static function woocommerce_missing_wc_notice() {
		$link = sprintf('<a href="%s" target="_blank">WooCommerce</a>', site_url() . '/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term');
		echo wp_kses_post('<div class="error"><p><strong>' . sprintf( 'Blindmatrix Freemium requires WooCommerce to be installed and active. You can install %s here.', $link ) . '</strong></p></div>');
	}

	/**
	 * Is WooCommerce active or not?.
	 */
	public static function is_woocommerce_active() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return true;
		}
	
		return false;
	}
}

BMFM_Activation_Handler::init();
