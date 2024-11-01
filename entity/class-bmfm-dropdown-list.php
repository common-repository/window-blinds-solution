<?php
/**
 * Dropdown List Object 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Dropdown List class.
 */
class BMFM_Dropdown_List_Object extends BMFM_Post_Object {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = BMFM_Register_Post_Types::DROPDOWN_LIST_POST_TYPE;

	/**
	 * Post status.
	 *
	 * @var string
	 */
	protected $post_status = 'publish';
	
	/**
	 * Dropdown name.
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 * Image URL.
	 *
	 * @var string
	 */
	protected $image_url;

	/**
	 * Stores dropdownlist data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'name'        => '',
		'image_url'   => '',
	);

	/**
	 * Get dropdown name.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->name;
	}
	
	/**
	 * Get dropdown image url.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_image_url( $context = 'view') {
		return $this->image_url;
	}

	/**
	 * Set dropdown name.
	 *
	 * @param string $value Value to set.
	 */
	public function set_name( $value ) {
		$this->name = $value;
	}
	
	/**
	 * Set dropdown image URL.
	 *
	 * @param string $value Value to set.
	 */
	public function set_image_url( $value) {
		$this->image_url = $value;
	}
}
