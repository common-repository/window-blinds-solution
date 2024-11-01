<?php
/**
 * Plugin functions.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Set ini set.
 * 
 * @return void
 */
function bmfm_ini_set_configuration(){
    ini_set('max_execution_time', '3000'); 
    ini_set('memory_limit', '3200M'); 
}

/**
 * Validate upload image cURL request.
 * 
 * @return bool
 */
function bmfm_validate_upload_image_curl_request($url){
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	$is_404 = curl_getinfo($ch, CURLINFO_HTTP_CODE) == 404;
	curl_close($ch);
	
    return $is_404;
}

if ( ! function_exists( 'woocommerce_quantity_input' ) ) {
   /**
    * WooCommerce quantity input.
    * 
    * @return String
    */
	function woocommerce_quantity_input( $args = array(), $product = null, $echo = true ) {
			if ( is_null( $product ) ) {
				$product = $GLOBALS['product'];
			}

			$defaults = array(
				'input_id'     => uniqid( 'quantity_' ),
				'input_name'   => 'quantity',
				'input_value'  => '1',
				/**
			     * Controls the quantity input classes.
			     *
			     * @since 1.0
			     */
				'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ),
				/**
			     * Controls the quantity input maximum.
			     *
			     * @since 1.0
			     */
				'max_value'    => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
				/**
			     * Controls the quantity input minimum.
			     *
			     * @since 1.0
			     */
				'min_value'    => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
				/**
			     * Controls the quantity input step.
			     *
			     * @since 1.0
			     */
				'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
				/**
			     * Controls the quantity input pattern.
			     *
			     * @since 1.0
			     */
				'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
				/**
			     * Controls the quantity inputmode.
			     *
			     * @since 1.0
			     */
				'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
				'product_name' => $product ? $product->get_title() : '',
				/**
			     * Controls the quantity input placeholder.
			     *
			     * @since 1.0
			     */
				'placeholder'  => apply_filters( 'woocommerce_quantity_input_placeholder', '', $product ),
				/**
			     * Controls the quantity input autocomplete.
			     *
			     * @since 1.0
			     */
				'autocomplete' => apply_filters( 'woocommerce_quantity_input_autocomplete', 'off', $product ),
				'readonly'     => false,
			);
            /**
			 * Controls the quantity input autocomplete.
			 *
			 * @since 1.0
			 */
			$args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

			// Apply sanity to min/max args - min cannot be lower than 0.
			$args['min_value'] = max( $args['min_value'], 0 );
			$args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

			// Max cannot be lower than min if defined.
			if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
				$args['max_value'] = $args['min_value'];
			}

			/**
			 * The input type attribute will generally be 'number' unless the quantity cannot be changed, in which case
			 * it will be set to 'hidden'. An exception is made for non-hidden readonly inputs: in this case we set the
			 * type to 'text' (this prevents most browsers from rendering increment/decrement arrows, which are useless
			 * and/or confusing in this context).
			 */
			$type = $args['min_value'] > 0 && $args['min_value'] === $args['max_value'] ? 'hidden' : 'number';
			$type = $args['readonly'] && 'hidden' !== $type ? 'text' : $type;

			/**
			 * Controls the quantity input's type attribute.
			 *
			 * @since 7.4.0
			 *
			 * @param string $type A valid input type attribute value, usually 'number' or 'hidden'.
			 */
			$args['type'] = apply_filters( 'woocommerce_quantity_input_type', $type );

			ob_start();
			if ( ! is_product()|| !bmfm_check_is_fabric_color_product(get_the_ID())) {
				wc_get_template( 'global/quantity-input.php', $args );
			}else{
				wc_get_template( 'bm-quantity-input.php', $args ,'',BMFM_TEMPLATE_PATH);
			}

			if ( $echo ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo ob_get_clean();
			} else {
				return ob_get_clean();
			}
	}
}

/**
 * Request method.
 * 
 * @return array
 */
function bmfm_request_method(){
    return $_REQUEST;
}

/**
 * Get method.
 * 
 * @return array
 */
function bmfm_get_method(){
    return $_GET;
}

/**
 * Post method.
 * 
 * @return array
 */
function bmfm_post_method(){
    return $_POST;
}

/**
 * Files method.
 * 
 * @return array
 */
function bmfm_get_files(){
	return $_FILES;
}

/**
 * Set country selection CSS.
 * 
 * @return void
 */
function bmfm_set_country_selection_css(){
    wp_enqueue_style('select_country_css', 'https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.1.1/css/countrySelect.min.css', array(), BMFM_VERSION);
}
