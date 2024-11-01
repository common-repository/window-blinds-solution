<?php
/**
 * Category List Object 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Category List class.
 */
class BMFM_Category_List_Object extends BMFM_Post_Object {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = BMFM_Register_Post_Types::CATEGORY_LIST_POST_TYPE;

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
	 * Stores category list data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'name'            => '',
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
	 * Set category name.
	 *
	 * @param string $value Value to set.
	 */
	public function set_name( $value ) {
		$this->name = $value;
	}
}
