<?php
/**
 * Product Object
 *
 * @class BMFM_Product_Object
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * BMFM_Product_Object class.
 */
class BMFM_Product_Object {
	/**
	 * Constructor.
	 */
	public function __construct( $id = 0) {
		$this->id = $id;
	}
	
	/**
	 * Create an object.
	 */
	public function create( $post_args, $meta_args) {
		$default_args = array(
			'post_title'    => '',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_parent'   => 0,
			'post_type'     =>'product'
		);

		$args = array_merge($default_args, $post_args);
		if (empty($args['post_title'])) {
			return;
		}
		
		$product_id = wp_insert_post($args);
		$this->update_metas($product_id, $meta_args);
	
		return $product_id;
	}
	
	/**
	 * Update an object.
	 */
	public function update( $post_id, $post_args = array(), $meta_args = array()) {
		if (!empty($post_args)) {
			$post_args['ID'] = $post_id;
			wp_update_post($post_args);
		}
		$this->update_metas( $post_id, $meta_args);
		return $post_id;
	}
	
	/**
	 * Update product metas.
	 */
	public function update_metas( $product_id, $meta_args) {
		$meta_args['bmfm_blinds_product'] = 'yes';
		if (isset($meta_args['category_ids'])) {
			wp_set_post_terms($product_id, array($meta_args['category_ids']), 'product_cat');
		}
		foreach ($meta_args as $meta_key => $meta_value) {
			update_post_meta($product_id, '_' . $meta_key, $meta_value);
		}
		
		if (!empty($meta_args['bmfm_product_post_thumbnail_id'])) {
			set_post_thumbnail( $product_id, $meta_args['bmfm_product_post_thumbnail_id']);
		}
		
		$product = wc_get_product($product_id);
		if (!is_object($product)) {
			return;
		}
		if (!empty($meta_args['bmfm_product_image_gallery_ids']) && is_array($meta_args['bmfm_product_image_gallery_ids'])) {
			$product->set_gallery_image_ids( $meta_args['bmfm_product_image_gallery_ids'] );
			$product->save();
		}
		
		if (empty($meta_args['bmfm_product_image_gallery_ids']) && !empty($meta_args['bmfm_hide_frame']) && 'on' == $meta_args['bmfm_hide_frame']) {
			$product->set_gallery_image_ids(array());
			$product->save();
		}
	}

	/**
	 * Get id.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get product.
	 */
	public function get_product() {
		$product = wc_get_product($this->get_id());
		return is_object($product) ? $product : false;
	}
	
	/**
	 * Get product name.
	 */
	public function get_product_name() {
		return is_object($this->get_product()) ? $this->get_product()->get_name() : '';
	}

	/**
	 * Get category type.
	 */
	public function get_category_type() {
		return $this->get_post_meta('bmfm_category_type');
	}
	
	/**
	 * Get image URL.
	 */
	public function get_image_url() {
		return $this->get_post_meta('bmfm_image_url');
	}
	
	/**
	 * Get frame URL.
	 */
	public function get_frame_url() {
		return $this->get_post_meta('bmfm_frame_url');
	}
	
	/**
	 * Get product image gallery ids.
	 */
	public function get_product_image_gallery_ids() {
		return !empty($this->get_post_meta('bmfm_product_image_gallery_ids')) ? $this->get_post_meta('bmfm_product_image_gallery_ids'):array() ;
	}
	
	/**
	 * Get merged frame color id.
	 */
	public function get_merged_frame_color_thumbnail_id() {
		return $this->get_post_meta('bmfm_product_post_thumbnail_id');
	}
	
	/**
	 * Get merged frame color URL.
	 */
	public function get_merged_frame_color_url() {
		$attachmenmt_id = $this->get_merged_frame_color_thumbnail_id();
		return '' != $attachmenmt_id ? wp_get_attachment_url($attachmenmt_id):'';
	}
	
	/**
	 * Get show or hide frame checkbox.
	 */
	public function get_show_or_hide_frame() {
		return $this->get_post_meta('bmfm_hide_frame');
	}
	
	/**
	 * Get material images URL.
	 */
	public function get_material_images_url() {
		return $this->get_post_meta('bmfm_material_images_url');
	}
	
	/**
	 * Get price.
	 */
	public function get_price() {
		return is_object($this->get_product()) ? $this->get_product()->get_price() : '';
	}
	
	/**
	 * Get image id.
	 */
	public function get_image_id() {
		return is_object($this->get_product()) ? get_post_thumbnail_id( $this->get_product()->get_id() ) : '';
	}
	
	/**
	 * Get fabric color image id.
	 */
	public function get_fabric_color_image_id() {
		return $this->get_post_meta('bmfm_fabric_color_image_id');
	}
	 
	/**
	 * Get description.
	 */
	public function get_desc() {
		return is_object($this->get_product()) ? $this->get_product()->get_short_description() : '';
	}
	
	/**
	 * Get linked categories.
	 */
	public function get_linked_categories() {
		return $this->get_post_meta('bmfm_linked_categories');
	}
	
	/**
	 * Uploaded frame product name.
	 */
	public function get_uploaded_frame_pdt_name() {
		return $this->get_post_meta('bmfm_uploaded_frame_pdt_name');
	}
	
	/**
	 * Get post parent.
	 */
	public function get_post_parent() {
		return is_object($this->get_product()) ? wp_get_post_parent_id($this->get_product()->get_id()) : '';
	}
	
	/**
	 * Get post meta.
	 */
	public function get_post_meta( $meta_key) {
		if (!is_object($this->get_product())) {
			return '';
		}
		
		return get_post_meta($this->get_product()->get_id(), '_' . $meta_key, true);
	}
}

