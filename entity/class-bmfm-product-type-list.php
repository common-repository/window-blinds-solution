<?php
/**
 * Product Type List Object 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Product Type List class.
 */
class BMFM_Product_Type_List_Object extends BMFM_Post_Object {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = BMFM_Register_Post_Types::PRODUCT_TYPE_LIST_POST_TYPE;

	/**
	 * Post status.
	 *
	 * @var string
	 */
	protected $post_status = 'publish';
	
	/**
	 * Product type name.
	 *
	 * @var string
	 */
	protected $product_type_name;
	
	/**
	 * Price table data.
	 *
	 * @var string
	 */
	protected $price_table_data;
	
	/**
	 * Price table data in cm.
	 *
	 * @var string
	 */
	protected $price_table_data_in_cm;
	
	/**
	 * Price table data in inch.
	 *
	 * @var string
	 */
	protected $price_table_data_in_inch;
	
	/**
	 * Markup.
	 *
	 * @var string
	 */
	protected $markup;
	
	/**
	 * Default unit.
	 *
	 * @var string
	 */
	protected $default_unit;

	/**
	 * Stores producttypelist data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'product_type_name'        => '',
		'price_table_data'         => '',
		'price_table_data_in_cm'   => '',
		'price_table_data_in_inch' => '',
		'markup'                   => '', 
		'default_unit'             => '',  
	);

	/**
	 * Get product type name.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_product_type_name( $context = 'view' ) {
		return $this->product_type_name;
	}
	
	/**
	 * Get price table data.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_price_table_data( $context = 'view') {
		return $this->price_table_data;
	}
	
	/**
	 * Get price table data in cm.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_price_table_data_in_cm( $context = 'view') {
		return $this->price_table_data_in_cm;
	}
	
	/**
	 * Get price table data in inch.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_price_table_data_in_inch( $context = 'view') {
		return $this->price_table_data_in_inch;
	}
	
	/**
	 * Get markup.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_markup( $context = 'view') {
		return $this->markup;
	}
	
	/**
	 * Get default unit.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_default_unit( $context = 'view') {
		return $this->default_unit;
	}

	/**
	 * Set product type name.
	 *
	 * @param string $value Value to set.
	 */
	public function set_product_type_name( $value ) {
		$this->product_type_name = $value;
	}
	
	/**
	 * Set price table data.
	 *
	 * @param string $value Value to set.
	 */
	public function set_price_table_data( $value) {
		$this->price_table_data = $value;
	}
	
	/**
	 * Set price table data in cm.
	 *
	 * @param string $value Value to set.
	 */
	public function set_price_table_data_in_cm( $value) {
		$this->price_table_data_in_cm = $value;
	}
	
	/**
	 * Set price table data in inch.
	 *
	 * @param string $value Value to set.
	 */
	public function set_price_table_data_in_inch( $value) {
		$this->price_table_data_in_inch = $value;
	}
	
	/**
	 * Set markup.
	 *
	 * @param string $value Value to set.
	 */
	public function set_markup( $value) {
		$this->markup = $value;
	}
	
	/**
	 * Set default unit.
	 *
	 * @param string $value Value to set.
	 */
	public function set_default_unit( $value) {
		$this->default_unit = $value;
	}
}
