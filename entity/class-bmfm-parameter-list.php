<?php
/**
 * Parameter List Object 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Parameter List class.
 */
class BMFM_Parameter_List_Object extends BMFM_Post_Object {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = BMFM_Register_Post_Types::PARAMETER_LIST_POST_TYPE;

	/**
	 * Post status.
	 *
	 * @var string
	 */
	protected $post_status = 'publish';
	
	/**
	 * Parameter name.
	 *
	 * @var string
	 */
	protected $parameter_name;
	/**
	 * Parameter type.
	 *
	 * @var string
	 */
	protected $parameter_type;
	/**
	 * Mandatory checked.
	 *
	 * @var string
	 */
	protected $mandatory_checked;

	/**
	 * Stores parameterlist data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'parameter_name'      => '',
		'parameter_type'      => '',
		'mandatory_checked'   => '',
		'category_type'       => '',
	);

	/**
	 * Get parameter name.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_parameter_name( $context = 'view' ) {
		return $this->parameter_name;
	}

	/**
	 * Get parameter type.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_parameter_type( $context = 'view' ) {
		return $this->parameter_type;
	}

	/**
	 * Get mandatory checked.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_mandatory_checked( $context = 'view' ) {
		return $this->mandatory_checked;
	}
	
	/**
	 * Get category type.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_category_type( $context = 'view' ) {
		return $this->category_type;
	}

	/**
	 * Set parameter name.
	 *
	 * @param string $value Value to set.
	 */
	public function set_parameter_name( $value ) {
		$this->parameter_name =$value;
	}

	/**
	 * Set parameter type.
	 *
	 * @param string $value Value to set.
	 */
	public function set_parameter_type( $value ) {
		$this->parameter_type = $value;
	}

	/**
	 * Set mandatory checked.
	 *
	 * @param string $value Value to set.
	 */
	public function set_mandatory_checked( $value ) {
		$this->mandatory_checked = $value;
	}
	
	/**
	 * Set category type.
	 *
	 * @param string $value Value to set.
	 */
	public function set_category_type( $value ) {
		$this->category_type = $value;
	}
}
