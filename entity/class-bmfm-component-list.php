<?php
/**
 * Component List Object 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Component List class.
 */
class BMFM_Component_List_Object extends BMFM_Post_Object {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = BMFM_Register_Post_Types::COMPONENT_LIST_POST_TYPE;

	/**
	 * Post status.
	 *
	 * @var string
	 */
	protected $post_status = 'publish';
	
	/**
	 * Component name.
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 * Component type.
	 *
	 * @var string
	 */
	protected $type;
	
	/**
	 * Component net price.
	 *
	 * @var string
	 */
	protected $net_price;
	
	/**
	 * Component markup.
	 *
	 * @var string
	 */
	protected $markup;
	
	/**
	 * Component image ID.
	 *
	 * @var string
	 */
	protected $image_id;
	/**
	 * Component image URL.
	 *
	 * @var string
	 */
	protected $image_url;

	/**
	 * Stores componentlist data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'name'        => '',
		'type'        => '',
		'net_price'   => '',
		'markup'      => '',
		'image_id'    => '',
		'image_url'   => '',
	);

	/**
	 * Get component name.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->name;
	}
	
	/**
	 * Get component type.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_type( $context = 'view') {
		return $this->type;
	}
	
	/**
	 * Get net price.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_net_price( $context = 'view') {
		return $this->net_price;
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
	 * Get image id.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_image_id( $context = 'view') {
		return $this->image_id;
	}
	
	/**
	 * Get image URL.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_image_url( $context = 'view') {
		return $this->image_url;
	}

	/**
	 * Set component name.
	 *
	 * @param string $value Value to set.
	 */
	public function set_name( $value ) {
		$this->name = $value;
	}
	
	/**
	 * Set component type.
	 *
	 * @param string $value Value to set.
	 */
	public function set_type( $value) {
		$this->type = $value;
	}
	
	/**
	 * Set net price.
	 *
	 * @param string $value Value to set.
	 */
	public function set_net_price( $value) {
		$this->net_price = $value;
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
	 * Set image id.
	 *
	 * @param string $value Value to set.
	 */
	public function set_image_id( $value) {
		$this->image_id = $value;
	}
	
	/**
	 * Set image URL.
	 *
	 * @param string $value Value to set.
	 */
	public function set_image_url( $value) {
		$this->image_url = $value;
	}
}
