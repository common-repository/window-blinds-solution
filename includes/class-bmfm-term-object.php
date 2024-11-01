<?php
/**
 * Term Object
 *
 * @class BMFM_Term_Object
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * BMFM_Term_Object class.
 */
class BMFM_Term_Object {
	/**
	 * Name.
	 */
	protected $name;
	/**
	 * Description.
	 */
	protected $description;
	/**
	 * Constructor.
	 */
	protected $parent = '0';
	/**
	 * Slug.
	 */
	protected $slug;
	/**
	 * Product count product category.
	 */
	protected $product_count_product_cat;
	/**
	 * Category type.
	 */
	protected $category_type;
	/**
	 * Country.
	 */
	protected $country;
	/**
	 * Thumbnail ID.
	 */
	protected $thumbnail_id;
	/**
	 * Image URL.
	 */
	protected $image_url;
	
	/**
	 * Constructor.
	 */
	public function __construct( $id = 0) {
		$this->id = $id;
		$this->read($id);
	}
	
	/**
	 * Read the object.
	 */
	public function read( $id) {
		if (!$id || !absint($id)) {
			return;
		}
		
		$term_object = get_term($id);
		
		$term_args  = array(
			'name'     			=> $term_object->name,
			'slug' 				=> $term_object->slug,
			'term_group'     	=> $term_object->term_group,
			'term_taxonomy_id'  => $term_object->term_taxonomy_id,
			'taxonomy'   		=> $term_object->taxonomy,
			'description'  	 	=> $term_object->description,
			'parent'   			=> $term_object->parent,
			'count'   			=> $term_object->count,
		);
		
		foreach ($term_args as $key => $value) {
			$this->$key = $value;
		}
		
		$term_meta = get_term_meta($id);
		foreach ($term_meta as $key => $value) {
			$value = isset($value[0]) ? $value[0]:'';
			$this->$key = $value;
		}
	}
	
	/**
	 * Create an object.
	 */
	public function create( $term_name, $args = array(), $meta_args = array()) {
		$default_args  = array(
			'description'     => '',
			'parent'          => '0',
			'slug '           => '',
		);
		
		$args    = array_merge($default_args, $args);
		$term    = wp_insert_term($term_name, 'product_cat', $args);
		if ( is_wp_error( $term ) ) {
			if ( 'term_exists' === $term->get_error_code() ) {
				// When term exists, error data should contain existing term id.
				$term_id = $term->get_error_data();
			}
		} else {
			// New term.
			$term_id = $term['term_id'];
		}
		
		if ($term_id && !empty($meta_args)) {
			$this->update_term_meta($term_id, $meta_args);
		}
		
		return $term_id;
	}
	
	/**
	 * Update an object.
	 */
	public function update( $term_id, $args = array(), $meta_args = array()) {
		$default_args  = array(
			'description'     => $this->get_description(),
			'parent'          => $this->get_parent(),
			'slug '           => $this->get_slug(),
		);

		$args    = array_merge($default_args, $args);
		
		wp_update_term( $term_id, 'product_cat', $args );
		if ($term_id && !empty($meta_args)) {
			$this->update_term_meta($term_id, $meta_args);
		}

		return $term_id;
	}
	
	/**
	 * Update term meta.
	 */
	public function update_term_meta( $term_id, $meta_args) {
		foreach ($meta_args as $meta_key => $meta_value) {
			update_term_meta( $term_id, sanitize_key($meta_key), $meta_value );
		}
	}
	
	/**
	 * Get name.
	 */
	public function get_name() {
		return $this->name;
	}
	
	/**
	 * Get description.
	 */
	public function get_description() {
		return $this->description;
	}
	
	/**
	 * Get parent.
	 */
	public function get_parent() {
		return $this->parent;
	}
	 
	/**
	 * Get slug.
	 */
	public function get_slug() {
		return $this->slug;
	}
	
	/**
	 * Get id.
	 */
	public function get_id() {
		return $this->id;
	}
	
	/**
	 * Get linked product count.
	 */
	public function get_count() {
		return $this->product_count_product_cat;
	}
	
	/**
	 * Get thumbnail ID.
	 */
	public function get_thumbnail_id() {
		return $this->thumbnail_id;
	}
	
	/**
	 * Get product category type.
	 */
	public function get_product_category_type() {
		return $this->category_type;
	}
	
	/**
	 * Get image URL.
	 */
	public function get_image_url() {
		return $this->image_url;
	}
	/**
	 * Get country.
	 */
	public function get_country() {
		return $this->country;
	}
	
}

