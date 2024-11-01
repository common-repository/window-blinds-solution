<?php
/**
 * Register Custom Post Types
 *
 * @class BMFM_Register_Post_Types
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * BMFM_Register_Post_Types class.
 */
class BMFM_Register_Post_Types {
	/**
	 * Parameter list post type constant.
	 */
	const PARAMETER_LIST_POST_TYPE = 'bmfm_parameter_list';
	
	/**
	 * Product Type list post type constant.
	 */
	const PRODUCT_TYPE_LIST_POST_TYPE = 'bmfm_pdt_type_list';
	
	/**
	 * Component list post type constant.
	 */
	const COMPONENT_LIST_POST_TYPE = 'bmfm_comp_list';
	
	/**
	 * Dropdown list post type constant.
	 */
	const DROPDOWN_LIST_POST_TYPE = 'bmfm_dropdown_list';
	
	/**
	 * Category list post type constant.
	 */
	const CATEGORY_LIST_POST_TYPE = 'bmfm_cat_list';
	
	/**
	 * Category sublist post type constant.
	 */
	const CATEGORY_SUBLIST_POST_TYPE = 'bmfm_cat_sublist';

	/**
	 * Init.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
	}
	/**
	 * Register Post Types.
	 */
	public static function register_post_types() {
		register_post_type(
			self::PARAMETER_LIST_POST_TYPE,
				array(
					'label'           => 'Parameter List',
					'public'          => false,
					'hierarchical'    => false,
					'supports'        => false,
					'capability_type' => 'post',
					'rewrite'         => false,
				)
		);
		register_post_type(
			self::PRODUCT_TYPE_LIST_POST_TYPE,
				array(
					'label'           => 'Product Type List',
					'public'          => false,
					'hierarchical'    => false,
					'supports'        => false,
					'capability_type' => 'post',
					'rewrite'         => false,
				)
		);
		register_post_type(
			self::COMPONENT_LIST_POST_TYPE,
				array(
					'label'           => 'Component List',
					'public'          => false,
					'hierarchical'    => false,
					'supports'        => false,
					'capability_type' => 'post',
					'rewrite'         => false,
				)
		);
		register_post_type(
			self::DROPDOWN_LIST_POST_TYPE,
				array(
					'label'           => 'Dropdown List',
					'public'          => false,
					'hierarchical'    => false,
					'supports'        => false,
					'capability_type' => 'post',
					'rewrite'         => false,
				)
		);
		
		register_post_type(
			self::CATEGORY_LIST_POST_TYPE,
				array(
					'label'           => 'Category List',
					'public'          => false,
					'hierarchical'    => false,
					'supports'        => false,
					'capability_type' => 'post',
					'rewrite'         => false,
				)
		);
		
		register_post_type(
			self::CATEGORY_SUBLIST_POST_TYPE,
				array(
					'label'           => 'Category Sublist',
					'public'          => false,
					'hierarchical'    => false,
					'supports'        => false,
					'capability_type' => 'post',
					'rewrite'         => false,
				)
		);
	}
}

BMFM_Register_Post_Types::init();
