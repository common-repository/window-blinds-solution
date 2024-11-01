<?php
/**
 * Category Sublist Object 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Category Sublist class.
 */
class BMFM_Category_Sublist_Object extends BMFM_Post_Object {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = BMFM_Register_Post_Types::CATEGORY_SUBLIST_POST_TYPE;

	/**
	 * Post status.
	 *
	 * @var string
	 */
	protected $post_status = 'publish';
	
	/**
	 * Category name.
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 * Image id.
	 *
	 * @var string
	 */
	protected $image_id;
	
	/**
	 * Image URL.
	 *
	 * @var string
	 */
	protected $image_url;

	/**
	 * Stores category list data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'name'            => '',
		'image_id'        => '', 
		'image_url'       => '',
	);

	/**
	 * Get category name.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->name;
	}
	
	/**
	 * Get category image id.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_image_id( $context = 'view' ) {
		return $this->image_id;
	}
	
	/**
	 * Get category image URL.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_image_url( $context = 'view' ) {
		return $this->image_url;
	}
	
	/**
	 * Set category name.
	 *
	 * @param string $value Value to set.
	 */
	public function set_name( $value ) {
		$this->name = $value;
	}

	/**
	 * Set image id.
	 *
	 * @param string $value Value to set.
	 */
	public function set_image_id( $value ) {
		$this->image_id = $value;
	}
	
	/**
	 * Set image URL.
	 *
	 * @param string $value Value to set.
	 */
	public function set_image_url( $value ) {
		$this->image_url = $value;
	}
}
