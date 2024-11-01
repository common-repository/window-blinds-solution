<?php
/**
 * Blindmatrix Freemium Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 */

defined('ABSPATH') || exit;

/**
 * Get plugin screen ids.
 * 
 * @return array
 */
function bmfm_get_plugin_screen_ids() {
	$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
	return '';
}

/**
 * Check is fabric color product.
 * 
 * @return bool
 */
function bmfm_check_is_fabric_color_product( $product_id) {
	$product = wc_get_product($product_id);
	if (!is_object($product) || empty($product->get_category_ids())) {
		return false;
	}
	
	$fabric_color_product = bmfm_get_fabric_color_product($product_id);
	if (!is_object($fabric_color_product)) {
		return false;
	}
	
	if (!$fabric_color_product->get_category_type()) {
		return false;
	}
	
	if ('accessories' == $fabric_color_product->get_category_type()) {
		return true;
	}
	
	if ('blinds' == $fabric_color_product->get_category_type()) {
		$parameter_list_ids = bmfm_get_parameter_list_ids($product->get_category_ids());
		if (empty($parameter_list_ids) || !is_array($parameter_list_ids)) {
			return false;
		}
	}
	
	return true;
}

/**
 * Get category type.
 * 
 * @return string
 */
function bmfm_get_category_type() {
	global $post;
	if (empty($post->ID) || empty($post->post_type) || 'product' != $post->post_type ) {
		return '';
	}
	
	$product = wc_get_product($post->ID);
	if (!bmfm_check_is_fabric_color_product($product->get_id())) {
		return '';
	}
	
	$fabric_color_product = bmfm_get_fabric_color_product($product->get_id());
	return is_object($fabric_color_product) ? $fabric_color_product->get_category_type() :''; 
}

/**
 * Create a term object.
 * 
 * @return int
 */
function bmfm_create_term( $term_name, $args = array(), $meta_args = array()) {
	$term_object = new BMFM_Term_Object();
	return $term_object->create($term_name, $args, $meta_args);
}

/**
 * Get a term object.
 * 
 * @return int
 */
function bmfm_get_term( $term_id) {
	$term_object = new BMFM_Term_Object($term_id);
	return $term_object;
}

/**
 * Update a term object.
 * 
 * @return int
 */
function bmfm_update_term( $term_id, $args = array(), $meta_args = array()) {
	$term_object = new BMFM_Term_Object($term_id);
	return $term_object->update($term_id, $args, $meta_args);
}

/**
 * Create a parameter list object.
 * 
 * @return int
 */
function bmfm_create_parameter_list( $post_args, $meta_args) {
	$parameter_list = new BMFM_Parameter_List_Object();
	return $parameter_list->create($post_args, $meta_args);
}

/**
 * Get a parameter list object.
 * 
 * @return object
 */
function bmfm_get_parameter_list( $post_id) {
	$parameter_list = new BMFM_Parameter_List_Object($post_id);
	return $parameter_list;
}

/**
 * Update a parameter list object.
 * 
 * @return object
 */
function bmfm_update_parameter_list( $post_id, $post_args, $meta_args) {
	$parameter_list = new BMFM_Parameter_List_Object($post_id);
	return $parameter_list->update($post_id, $post_args, $meta_args);
}

/**
 * Delete a parameter list object.
 * 
 * @return object
 */
function bmfm_delete_parameter_list( $post_id) {
	wp_delete_post($post_id);
}

/**
 * Get category ids.
 * 
 * @return object
 */
function bmfm_get_category_ids() {
	return array_values(get_terms(array(
		'taxonomy' => 'product_cat',
		'fields' => 'ids',
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key' => 'bmfm_blinds',
				'compare' => 'EXISTS'
				)
			)
		)
	));
}

/**
 * Get parameter list ids.
 * 
 * @return object
 */
function bmfm_get_parameter_list_ids( $post_parent = true, $exclude_product_type = false, $category_type = false) {
	$args =  array(
			'post_type'      => BMFM_Register_Post_Types::PARAMETER_LIST_POST_TYPE,
			'post_status'    => array('publish'),
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'ID'
	);
	
	if ($post_parent) {
		if (is_array($post_parent)) {
			$args['post_parent__in'] = $post_parent;
		} else {
			$args['post_parent'] = $post_parent;
		}
	}
	
	if (false !== $exclude_product_type) {
		$args['meta_query'] = array(
			array(
				'key'     => 'parameter_type',
				'value'   => 'product_type',
				'compare' => '!='
				)
			);
	}
	
	if (false !== $category_type) {
		$args['meta_key']   = 'category_type';
		$args['meta_value'] = $category_type;
	}
		
	return get_posts($args);
}

/**
 * Get a product type list id based on category id.
 * 
 * @return int
 */
function bmfm_get_product_type_list_id_based_on_cat_id( $post_parent, $all_fabric_colors = false) {
	$args =  array(
			'post_type'      => BMFM_Register_Post_Types::PARAMETER_LIST_POST_TYPE,
			'post_status'    => array('publish'),
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'meta_key'       => 'parameter_type',
			'meta_value'     => 'product_type',
			'order'          => 'ASC',
			'orderby'        => 'ID',
	);
	
	if (is_array($post_parent)) {
		$args['post_parent__in'] = $post_parent;
	} else {
		$args['post_parent'] = $post_parent;
	}
	
	$product_type_ids      = get_posts($args);
	if ($all_fabric_colors) {
		$product_type_list_ids = bmfm_get_product_type_list_ids($product_type_ids);
		return $product_type_list_ids;
	}
	$product_type_id       = end($product_type_ids);
	$product_type_list_ids = '' != $product_type_id ? bmfm_get_product_type_list_ids($product_type_id):0;
	
	return !empty($product_type_list_ids) && isset($product_type_list_ids[0]) ? $product_type_list_ids[0]:0;
}

/**
 * Create a product type list object.
 * 
 * @return int
 */
function bmfm_create_product_type_list( $post_args, $meta_args) {
	$product_type_list = new BMFM_Product_Type_List_Object();
	return $product_type_list->create($post_args, $meta_args);
}


/**
 * Get a product type list object.
 * 
 * @return object
 */
function bmfm_get_product_type_list( $post_id) {
	$product_type_list= new BMFM_Product_Type_List_Object($post_id);
	return $product_type_list;
}

/**
 * Update a product type list object.
 * 
 * @return object
 */
function bmfm_update_product_type_list( $post_id, $post_args, $meta_args) {
	$product_type_list = new BMFM_Product_Type_List_Object($post_id);
	return $product_type_list->update($post_id, $post_args, $meta_args);
}

/**
 * Delete a product type list object.
 * 
 * @return object
 */
function bmfm_delete_product_type_list( $post_id) {
	wp_delete_post($post_id);
}

/**
 * Create a product type list object.
 * 
 * @return int
 */
function bmfm_create_dropdown_list( $post_args, $meta_args) {
	$dropdown_list = new BMFM_Dropdown_List_Object();
	return $dropdown_list->create($post_args, $meta_args);
}

/**
 * Get a dropdown list object.
 * 
 * @return object
 */
function bmfm_get_dropdown_list( $post_id) {
	$dropdown_list= new BMFM_Dropdown_List_Object($post_id);
	return $dropdown_list;
}

/**
 * Update a dropdown list object.
 * 
 * @return object
 */
function bmfm_update_dropdown_list( $post_id, $post_args, $meta_args) {
	$dropdown_list= new BMFM_Dropdown_List_Object($post_id);
	return $dropdown_list->update($post_id, $post_args, $meta_args);
}

/**
 * Delete a dropdown list object.
 * 
 * @return object
 */
function bmfm_delete_dropdown_list( $post_id) {
	wp_delete_post($post_id);
}

/**
 * Create a component list object.
 * 
 * @return int
 */
function bmfm_create_component_list( $post_args, $meta_args) {
	$component_list = new BMFM_Component_List_Object();
	return $component_list->create($post_args, $meta_args);
}

/**
 * Get a component list object.
 * 
 * @return object
 */
function bmfm_get_component_list( $post_id) {
	$component_list= new BMFM_Component_List_Object($post_id);
	return $component_list;
}

/**
 * Update a component list object.
 * 
 * @return object
 */
function bmfm_update_component_list( $post_id, $post_args, $meta_args) {
	$component_list= new BMFM_Component_List_Object($post_id);
	return $component_list->update($post_id, $post_args, $meta_args);
}

/**
 * Delete a component list object.
 * 
 * @return object
 */
function bmfm_delete_component_list( $post_id) {
	wp_delete_post($post_id);
}

/**
 * Get dropdown list ids.
 * 
 * @return array
 */
function bmfm_get_dropdown_list_ids( $post_parent = true) {
	$args = array(
			'post_type'      => BMFM_Register_Post_Types::DROPDOWN_LIST_POST_TYPE,
			'post_status'    => array('publish'),
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'ID'
	);
	
	if ($post_parent) {
		$args['post_parent'] = $post_parent;
	}
	
	return get_posts($args);
}

/**
 * Get component list ids.
 * 
 * @return array
 */
function bmfm_get_component_list_ids( $post_parent = true) {
	$args = array(
			'post_type'      => BMFM_Register_Post_Types::COMPONENT_LIST_POST_TYPE,
			'post_status'    => array('publish'),
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'ID'
	);
	
	if ($post_parent) {
		$args['post_parent']  = $post_parent;
	}
	
	return get_posts($args);
}

/**
 * Get product type list ids.
 * 
 * @return array
 */
function bmfm_get_product_type_list_ids( $post_parent = true) {
	$args = array(
			'post_type'      => BMFM_Register_Post_Types::PRODUCT_TYPE_LIST_POST_TYPE,
			'post_status'    => array('publish'),
			'fields'         => 'ids',
			'posts_per_page' => -1,
	);
	
	if (is_array($post_parent) && !empty($post_parent)) {
		$args['post_parent__in']  = $post_parent;
	} else if ($post_parent) {
		$args['post_parent']  = $post_parent;
	}
	
	return get_posts($args);
}

/**
 * Get blinds parameter row HTML.
 * 
 * @return HTML
 */
function bmfm_get_blinds_parameter_row_html() {
	$key = '';
	ob_start();
	include_once(BMFM_ABSPATH . '/includes/admin/views/html-blinds-parameters-list-row.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get accessories parameter row HTML.
 * 
 * @return HTML
 */
function bmfm_get_accessories_parameter_row_html() {
	$key = '';
	ob_start();
	include_once(BMFM_ABSPATH . '/includes/admin/views/html-accessories-parameters-list-row.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get fabric color row HTML.
 * 
 * @return HTML
 */
function bmfm_get_fabric_color_row_html() {
	ob_start();
	include_once(BMFM_ABSPATH . '/includes/admin/views/html-fabric-color-list-row.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get accessories row HTML.
 * 
 * @return HTML
 */
function bmfm_get_accessories_row_html() {
	ob_start();
	include_once(BMFM_ABSPATH . '/includes/admin/views/html-accessories-color-list-row.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get parameter list dropdown row HTML.
 * 
 * @return HTML
 */
function bmfm_get_parmeter_list_dropdown_row_html() {
	ob_start();
	$drop_down_s_no=1;
	include_once(BMFM_ABSPATH . '/includes/admin/views/html-dropdown-parameter-row.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get component list dropdown row HTML.
 * 
 * @return HTML
 */
function bmfm_get_component_list_dropdown_row_html() {
	ob_start();
	$component_s_no=1;
	include_once(BMFM_ABSPATH . '/includes/admin/views/html-component-parameter-row.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get stored price table data.
 * 
 * @return array
 */
function bmfm_get_stored_price_table_data( $parameter_type_id) {
	$product_type_list = BMFM_get_product_type_list($parameter_type_id);	
	return is_object($product_type_list) && !empty($product_type_list->get_price_table_data()) ? json_decode(wp_unslash($product_type_list->get_price_table_data())) : bmfm_get_price_table_data_in_mm();	
}

/**
 * Get stored price table data in cm.
 * 
 * @return array
 */
function bmfm_get_stored_price_table_data_in_cm( $parameter_type_id) {
	$product_type_list = BMFM_get_product_type_list($parameter_type_id);	
	return is_object($product_type_list) && !empty($product_type_list->get_price_table_data_in_cm()) ? json_decode(wp_unslash($product_type_list->get_price_table_data_in_cm())) : bmfm_get_price_table_data_in_cm();	
}

/**
 * Get stored price table data in inch.
 * 
 * @return array
 */
function bmfm_get_stored_price_table_data_in_inch( $parameter_type_id) {
	$product_type_list = BMFM_get_product_type_list($parameter_type_id);	
	return is_object($product_type_list) && !empty($product_type_list->get_price_table_data_in_inch()) ? json_decode(wp_unslash($product_type_list->get_price_table_data_in_inch())) : bmfm_get_price_table_data_in_inch();	
}

/**
 * Get price based on width and height.
 * 
 * @return int/float
 */
function bmfm_get_price_based_on_width_and_height( $parameter_type_id, $width, $height) {
	$product_type_list = bmfm_get_product_type_list($parameter_type_id);
	if (!is_object($product_type_list)) {
		return 0;
	}
	$default_unit = $product_type_list->get_default_unit();
	$table_data    = bmfm_get_stored_price_table_data($parameter_type_id);
	if ('inch' == $default_unit) {
		$table_data = bmfm_get_price_table_data_in_inch();
	} else if ('cm' == $default_unit) {
		$table_data = bmfm_get_price_table_data_in_cm();
	}
	
	// X-axis key.
	$x_axis_values = !empty($table_data[0]) && is_array($table_data[0]) ? $table_data[0]:array();
	$matched_value = '';
	foreach ($x_axis_values as $key => $value) {
		if (0 == $key) {
			continue;
		}
		
		if ('' !== $matched_value) {
			continue;
		}
		
		if ($value == $width) {
			$matched_value = $value;
		}
		
		if ($value > $width) {
			$matched_value = $value;
		}
	}
	
	$x_axis_key = array_search($matched_value, $x_axis_values);
	
	// Y-axis key.
	$y_axis_values = array();
	foreach ($table_data as $key => $value) {
		$y_axis_values[] = isset($value[0]) ? $value[0]:'';
	}
	
	$matched_value = '';
	foreach ($y_axis_values as $key => $value) {
		if (0 == $key ) {
			continue;
		}
		
		if ('' !== $matched_value) {
			continue;
		}
		
		if ($value == $height) {
			$matched_value = $value;
		}
		
		if ($value > $height) {
			$matched_value = $value;
		}
	}

	$y_axis_key  = array_search($matched_value, $y_axis_values);
	
	$fabric_color_price = isset($table_data[$y_axis_key][$x_axis_key]) ? $table_data[$y_axis_key][$x_axis_key]:'';
	if ($fabric_color_price) {
		$markup             = !empty($product_type_list->get_markup()) ? floatval($product_type_list->get_markup()):1;
		$fabric_color_price = 0 !== $markup ? $fabric_color_price* $markup : $fabric_color_price;
	}
	
	return $fabric_color_price;
}

/**
 * Get category list row HTML.
 * 
 * @return HTML
 */
function bmfm_get_category_list_row_html() {
	ob_start();
	$category_s_no=1;
	include_once(BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-list-row.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Create a category list object.
 * 
 * @return int
 */
function bmfm_create_category_list( $post_args, $meta_args) {
	$category_list = new BMFM_Category_List_Object();
	return $category_list->create($post_args, $meta_args);
}

/**
 * Get a category list object.
 * 
 * @return object
 */
function bmfm_get_category_list( $post_id) {
	$category_list= new BMFM_Category_List_Object($post_id);
	return $category_list;
}

/**
 * Update a category list object.
 * 
 * @return object
 */
function bmfm_update_category_list( $post_id, $post_args, $meta_args) {
	$category_list= new BMFM_Category_List_Object($post_id);
	return $category_list->update($post_id, $post_args, $meta_args);
}

/**
 * Get category list ids.
 * 
 * @return array
 */
function bmfm_get_category_list_ids( $post_parent = true) {
	$args = array(     
			'post_type'      => BMFM_Register_Post_Types::CATEGORY_LIST_POST_TYPE,
			'post_status'    => array('publish'),
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'ID'
	);
	
	if ($post_parent) {
		$args['post_parent'] = $post_parent;
	}
	
	return get_posts($args);
}


/**
 * Get category sublist row HTML.
 * 
 * @return HTML
 */
function bmfm_get_category_sublist_row_html() {
	ob_start();
	$s_no=1;
	include(BMFM_ABSPATH . '/includes/admin/views/html-blinds-category-sublist-row.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Create a category sublist object.
 * 
 * @return int
 */
function bmfm_create_category_sublist( $post_args, $meta_args) {
	$category_sublist = new BMFM_Category_Sublist_Object();
	return $category_sublist->create($post_args, $meta_args);
}

/**
 * Get a category sublist object.
 * 
 * @return object
 */
function bmfm_get_category_sublist( $post_id) {
	$category_sublist= new BMFM_Category_Sublist_Object($post_id);
	return $category_sublist;
}

/**
 * Update a category sublist object.
 * 
 * @return object
 */
function bmfm_update_category_sublist( $post_id, $post_args, $meta_args) {
	$category_sublist= new BMFM_Category_Sublist_Object($post_id);
	return $category_sublist->update($post_id, $post_args, $meta_args);
}

/**
 * Get category sublist ids.
 * 
 * @return array
 */
function bmfm_get_category_sub_list_ids( $post_parent = true) {
	$args = array(     
			'post_type'      => BMFM_Register_Post_Types::CATEGORY_SUBLIST_POST_TYPE,
			'post_status'    => array('publish'),
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'ID'
	);
	if ($post_parent) {
		$args['post_parent'] = $post_parent;
	}
	
	return get_posts($args);
}

/**
 * Create a fabric color product object.
 * 
 * @return int
 */
function bmfm_create_fabric_color_product( $post_args, $meta_args) {
	$product_obj = new BMFM_Product_Object();
	return $product_obj->create($post_args, $meta_args);
}

/**
 * Get a fabric color product object.
 * 
 * @return object
 */
function bmfm_get_fabric_color_product( $post_id) {
	$product_obj= new BMFM_Product_Object($post_id);
	return $product_obj;
}

/**
 * Update a fabric color product object.
 * 
 * @return int
 */
function bmfm_update_fabric_color_product( $post_id, $post_args, $meta_args) {
	$product_obj= new BMFM_Product_Object($post_id);
	return $product_obj->update($post_id, $post_args, $meta_args);
}

/**
 * Get upgrade premium HTML.
 * 
 * @return HTML
 */
function bmfm_get_upgrade_premium_html() {
	ob_start();
	include(BMFM_ABSPATH . '/includes/admin/views/html-upgrade-premium-info.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get fabric color ids.
 * 
 * @return array
 */
function bmfm_get_fabric_color_ids( $cat_ids, $offset = false, $per_page = false, $all_fabric_colors = false, $order = false) {
	$offset = false != $offset ? $offset:0;
	$per_page = false != $per_page ? $per_page:-1;
	$product_type_list_id = bmfm_get_product_type_list_id_based_on_cat_id($cat_ids, $all_fabric_colors);
	$args = array(
			'post_type' => 'product',
			'posts_per_page' =>$per_page,
			'fields' => 'ids',
			'offset' => $offset,
			'post_parent' => $product_type_list_id,
	);
	
	if ($all_fabric_colors && is_array($product_type_list_id) && !empty($product_type_list_id)) {
		$args['post_parent__in'] = $product_type_list_id;
	}
	
	if (false !== $order) {
		$args['order'] = $order;
	}
	
	return get_posts($args);
}

/**
 * Get accessories list ids.
 * 
 * @return array
 */
function bmfm_get_accessories_list_ids( $cat_id, $offset = false, $per_page = false) {
	$offset = false != $offset ? $offset:0;
	$per_page = false != $per_page ? $per_page:-1;

	return get_posts(
		array(
			'post_type' => 'product',
			'posts_per_page' =>$per_page,
			'fields' => 'ids',
			'offset' => $offset,
			'meta_key' => '_bmfm_category_type',
			'meta_value' => 'accessories',
			'tax_query' => array(
			  array(
				'taxonomy' => 'product_cat',
				'terms' => $cat_id,
			  ),
			),
		)
	);
}


/**
 * Get price table data in mm.
 * 
 * @return array
 */
function bmfm_get_price_table_data_in_mm() {
	return array(
		array('Drop/Width', '610', '740', '914', '1067', '1219', '1372', '1524', '1676', '1829','1981'),
		array('610', '30.33', '31.86', '33.37', '34.87', '36.41', '37.91', '39.44', '42.47', '45.5','53.09'),
		array('762', '31.86', '33.37', '34.87', '36.41', '37.91', '39.44', '40.93', '43.99', '47.01','54.59'),
		array('914', '33.37', '34.87', '36.41', '37.91', '39.44', '40.93', '42.47', '45.5', '48.52','56.1'),
		array('1067', '34.87', '36.41', '37.91', '39.44', '40.93', '42.47', '43.99', '47.01', '50.04','57.63'),
		array('1219', '37.91', '39.44', '40.93', '42.47', '43.99', '45.5', '47.01', '50.04', '53.09','60.65'),
		array('1524', '40.93', '42.47', '43.99', '45.5', '47.01', '48.52', '50.04', '53.09', '56.1','63.68'),
		array('1829', '45.5', '47.01', '48.52', '50.04', '51.55', '53.09', '54.59', '57.63', '60.65','78.85'),
		array('2134', '50.04', '51.55', '53.09', '54.59', '56.1', '57.63', '59.15', '62.19', '65.22','83.41'),
		array('2438', '54.59', '56.1', '57.63', '59.15', '60.65', '62.19', '63.68', '66.74', '69.77','87.96'),
		array('2913', '59.15', '60.65', '62.19', '63.68', '65.22', '66.74', '68.23', '71.28', '74.31','92.51')
	);
}

/**
 * Price table data in cm.
 * 
 * @return array
 */
function bmfm_get_price_table_data_in_cm() {
	return array(
		array('Drop/Width', '61', '74', '91.4', '106.7', '121.9', '137.2', '152.4', '167.6', '182.9','198.1'),
		array('61', '30.33', '31.86', '33.37', '34.87', '36.41', '37.91', '39.44', '42.47', '45.5','53.09'),
		array('76.2', '31.86', '33.37', '34.87', '36.41', '37.91', '39.44', '40.93', '43.99', '47.01','54.59'),
		array('91.4', '33.37', '34.87', '36.41', '37.91', '39.44', '40.93', '42.47', '45.5', '48.52','56.1'),
		array('106.7', '34.87', '36.41', '37.91', '39.44', '40.93', '42.47', '43.99', '47.01', '50.04','57.63'),
		array('121.9', '37.91', '39.44', '40.93', '42.47', '43.99', '45.5', '47.01', '50.04', '53.09','60.65'),
		array('152.4', '40.93', '42.47', '43.99', '45.5', '47.01', '48.52', '50.04', '53.09', '56.1','63.68'),
		array('182.9', '45.5', '47.01', '48.52', '50.04', '51.55', '53.09', '54.59', '57.63', '60.65','78.85'),
		array('213.4', '50.04', '51.55', '53.09', '54.59', '56.1', '57.63', '59.15', '62.19', '65.22','83.41'),
		array('243.8', '54.59', '56.1', '57.63', '59.15', '60.65', '62.19', '63.68', '66.74', '69.77','87.96'),
		array('291.3', '59.15', '60.65', '62.19', '63.68', '65.22', '66.74', '68.23', '71.28', '74.31','92.51')
	);
}

/**
 * Price tabke data in inch.
 * 
 * @return array
 */
function bmfm_get_price_table_data_in_inch() {
	return array(
		array('Drop/Width', '24.02', '29.13', '35.98', '42.01', '47.99', '54.02', '60.00', '65.98', '72.01','78.00'),
		array('24.02', '30.33', '31.86', '33.37', '34.87', '36.41', '37.91', '39.44', '42.47', '45.5','53.09'),
		array('30.00', '31.86', '33.37', '34.87', '36.41', '37.91', '39.44', '40.93', '43.99', '47.01','54.59'),
		array('35.98', '33.37', '34.87', '36.41', '37.91', '39.44', '40.93', '42.47', '45.5', '48.52','56.1'),
		array('42.01', '34.87', '36.41', '37.91', '39.44', '40.93', '42.47', '43.99', '47.01', '50.04','57.63'),
		array('47.99', '37.91', '39.44', '40.93', '42.47', '43.99', '45.5', '47.01', '50.04', '53.09','60.65'),
		array('60.00', '40.93', '42.47', '43.99', '45.5', '47.01', '48.52', '50.04', '53.09', '56.1','63.68'),
		array('72.01', '45.5', '47.01', '48.52', '50.04', '51.55', '53.09', '54.59', '57.63', '60.65','78.85'),
		array('84.02', '50.04', '51.55', '53.09', '54.59', '56.1', '57.63', '59.15', '62.19', '65.22','83.41'),
		array('95.98', '54.59', '56.1', '57.63', '59.15', '60.65', '62.19', '63.68', '66.74', '69.77','87.96'),
		array('114.69','59.15', '60.65', '62.19', '63.68', '65.22', '66.74', '68.23', '71.28', '74.31','92.51')
	);
}

/**
 * Get categories lists and sub lists.
 * 
 * @return array
 */
function bmfm_get_categories_and_sub_list_ids() {
	$product_ids = bmfm_get_category_ids();
	if (empty($product_ids) || !is_array($product_ids)) {
		return array();
	}
	
	$categories_data = array();
	foreach ($product_ids as $product_id) {
		$category_list_ids = bmfm_get_category_list_ids($product_id);
		if (empty($category_list_ids) || !is_array($category_list_ids)) {
			continue;
		}
		
		foreach ($category_list_ids as $category_list_id) {
			$category_sub_list_ids = bmfm_get_category_sub_list_ids($category_list_id);
			if (empty($category_sub_list_ids) || !is_array($category_sub_list_ids)) {
				continue; 
			}
			
			$categories_data[$product_id][$category_list_id] = $category_sub_list_ids;
		}
	}
	
	return $categories_data;
}

/**
 * Validate linked categories list exists or not.
 * 
 * @return array
 */
function bmfm_validate_linked_categories_exists( $category_list_ids) {
	if (empty($category_list_ids) || !is_array($category_list_ids)) {
		return false;
	}
	
	$linked_categories_exists = false;
	foreach ($category_list_ids as $category_list_id) {
		$category_list = bmfm_get_category_list($category_list_id);
		if (!is_object($category_list)) {
			continue;
		}
	
		$fabric_color_ids = bmfm_get_fabric_color_ids($category_list->get_post_parent());
		$fabric_color_ids_based_on_linked_cat = get_posts(array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'post__in'       => $fabric_color_ids,
				'meta_query'     => array(
					array(
						'key'  	  => '_bmfm_linked_categories',
						'value'   => '',
						'compare' => '!='
					)
				),
		));
			
		if (!empty($fabric_color_ids_based_on_linked_cat)) {
			$linked_categories_exists = true;
		}
	}
	
	return $linked_categories_exists;
}

/**
 * Is blinds fabric or color product.
 * 
 * @return bool
 */
function bmfm_is_blinds_product( $fabric_color_id) {
	$fabric_color_product = bmfm_get_fabric_color_product($fabric_color_id);
	return is_object($fabric_color_product) ? 'yes' == get_post_meta($fabric_color_id, '_bmfm_blinds_product', true):false;
}


/**
 * Get freemium page id.
 * 
 * @return int
 */
function bmfm_get_freemium_page_id() {
	return get_option('bmfm_freemium_page_id')? get_option('bmfm_freemium_page_id'): 0 ;
}

/**
 * Get shop blinds page id.
 * 
 * @return int
 */
function bmfm_get_shop_blinds_page_id() {
	return get_option('bmfm_shop_blinds_page_id')? get_option('bmfm_shop_blinds_page_id'): 0 ;
}

/**
 * Get shop accessories page id.
 * 
 * @return int
 */
function bmfm_get_shop_accessories_page_id() {
	return get_option('bmfm_shop_accessories_page_id')? get_option('bmfm_shop_accessories_page_id'): 0 ;
}

/**
 * Get listing page id.
 * 
 * @return int
 */
function bmfm_get_listing_page_id( $term_id) {
	if (!$term_id) {
		return 0;
	}
	$term = bmfm_get_term($term_id);
	return  'accessories'==$term->get_product_category_type()? bmfm_get_shop_accessories_page_id(): bmfm_get_shop_blinds_page_id() ;
}


/**
 * Create a page and store the ID in an option.
 *
 * @param mixed  $slug Slug for the new page.
 * @param string $option Option name to store the page's ID.
 * @param string $page_title (default: '') Title for the new page.
 * @param string $page_content (default: '') Content for the new page.
 * @param int    $post_parent (default: 0) Parent for the new page.
 * @param string $post_status (default: publish) The post status of the new page.
 * @return int page ID.
 */
function bmfm_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0, $post_status = 'publish', $validate_page_content = true) {
	global $wpdb;

	$option_value = get_option( $option );

	if ( $option_value > 0 ) {
		$page_object = get_post( $option_value );

		if ( $page_object && 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ), true ) ) {
			// Valid page is already in place.
			return $page_object->ID;
		}
	}

	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode).
		$shortcode        = str_replace( array( '<!-- wp:shortcode -->', '<!-- /wp:shortcode -->' ), '', $page_content );
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$shortcode}%" ) );
	} else {
		// Search for an existing page with the specified page slug.
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $valid_page_found ) {
		if ( $option ) {
			update_option( $option, $valid_page_found );
		}
		return $valid_page_found;
	}

	// Search for a matching valid trashed page.
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode).
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug.
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'          => $page_id,
			'post_status' => $post_status,
		);
		wp_update_post( $page_data );
	} else {
		$page_data = array(
			'post_status'    => $post_status,
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed',
		);
		$page_id   = wp_insert_post( $page_data );
	}
	
	update_post_meta($page_id, '_wp_page_template', 'full-width-page-template.php');

	if ( $option ) {
		update_option( $option, $page_id );
	}

	return $page_id;
}

/**
 * Get products list object.
 * 
 * @return object
 */
function bmfm_get_products_list_object( $term_id, $product_ids = array(), $per_page = true, $orderby = false) {
	// Get current page
	$current_page = max(1, get_query_var('paged'));

	$products_per_page = -1;	
	if ($per_page) {
		// Get number of products per page
		$products_per_page = 25;
	}	

	$category_type = 'blinds';
	if (!is_array($term_id) && !empty($term_id)) {
		$term = bmfm_get_term($term_id);
		$category_type = is_object($term) && !empty($term->get_product_category_type()) ? $term->get_product_category_type():'blinds';
	}	

	$product_type_list_id = 'blinds' == $category_type ? bmfm_get_product_type_list_id_based_on_cat_id($term_id):0;
	// Set up query arguments
	$args = array(
	'post_type' => 'product',
	'post_parent' => $product_type_list_id,
	'tax_query' => array(
		array(
			'taxonomy' => 'product_cat',
			'field' => 'id',
			'terms' => array($term_id),
			'operator' => 'IN',
		)
	),
	'posts_per_page' => $products_per_page,
	'paged'    => $current_page,
	'orderby'  => 'menu_order title',
	'order'    => 'ASC',
	'meta_key' => '_bmfm_category_type',
	'meta_value' => $category_type
	);

	if (!empty($product_ids) && is_array($product_ids)) {
		$args['post__in'] = $product_ids;
	}

	switch ($orderby) {
		case 'asc':
			$args['orderby'] = 'title';
			$args['order'] = 'ASC';
			break;
		case 'desc':
			$args['orderby'] = 'title';
			$args['order'] = 'DESC';
			break;
		case 'date':
			$args['orderby'] = 'date ID';
			$args['order'] = 'DESC';
			break;
		case 'rating':
			$args['meta_key']       = '_wc_average_rating';
			$args['orderby' ]       = 'meta_value_num';
			$args['order']          = 'DESC';
			break;
		case 'price':
			$args['meta_key']       = '_price';
			$args['orderby' ]       = 'meta_value_num';
			$args['order']          = 'DESC';
			break;
	}

	// Get products based on query arguments
	$products = new WP_Query($args);

	/**
	 * Filter products.
	 *
	 * @since 1.0
	 */
	return apply_filters('woocommerce_product_filter_products', $products);
}

/**
 * Is empty category filter.
 * 
 * @return bool
 */
function bmfm_is_empty_category_list() {
	$category_id = bmfm_get_category_id_based_on_slug();
	$is_empty_category = false;
	if ($category_id) {
		$category_list_ids = bmfm_get_category_list_ids($category_id);
		if (empty($category_list_ids)) {
			$is_empty_category = true; 
		}
	}
	
	return $is_empty_category;
}

/**
 * Get products object based on category filter.
 * 
 * @return object
 */
function bmfm_get_products_based_on_category_filter( $term_id, $selected_categories_data, $filter_keys = false, $orderby = false ) {
	if (empty($selected_categories_data)) {
		$products = bmfm_get_products_list_object($term_id, array(), true, $orderby);
		return $products;
	}
		
		$products = bmfm_get_products_list_object($term_id, array(), false, $orderby);
		$posts = isset($products->posts) ? $products->posts :array();
		$filtered_product_ids = array();
	if (!empty($posts) && is_array($posts)) {
		foreach ($posts as $post_data) {
			$product_id = isset($post_data->ID) ? $post_data->ID:'';
			$fabric_color_product = bmfm_get_fabric_color_product($product_id);
			if (!is_object($fabric_color_product)) {
				continue;
			}
			$stored_linked_categories_data = $fabric_color_product->get_linked_categories();
					
			$linked_categories = isset($stored_linked_categories_data[$product_id]) ? $stored_linked_categories_data[$product_id]:array();
			if (!empty($linked_categories) && is_array($linked_categories)) {
				foreach ($linked_categories as $parent_category_id => $stored_child_category_ids) {
					$selected_child_categories_data = isset($selected_categories_data[$parent_category_id]) ? $selected_categories_data[$parent_category_id]:array();
					if ($filter_keys) {
						$selected_child_category_ids = !empty($selected_child_categories_data) && is_array($selected_child_categories_data) ? array_keys($selected_child_categories_data):array(); 
					} else {
						$selected_child_category_ids = !empty($selected_child_categories_data) && is_array($selected_child_categories_data) ? $selected_child_categories_data:array();
					}
					if (!empty($selected_child_category_ids) && is_array($selected_child_category_ids) && !empty($stored_child_category_ids) && is_array($stored_child_category_ids)) {
						foreach ($stored_child_category_ids as $stored_child_category_id) {
							if (in_array($stored_child_category_id, $selected_child_category_ids)) {
								$filtered_product_ids[] = $product_id;
							}
						}
					}
				}
			}
		}
	}
		
		$products = false;
	if (!empty($filtered_product_ids)) {
		$products = bmfm_get_products_list_object($term_id, $filtered_product_ids, true, $orderby);
	}
		
		return $products;
}	

/**
 * Get pagenum link.
 * 
 * @return string
 */
function bmfm_get_pagenum_link( $pagenum = 1, $escape = true, $category_id = '') {
	global $wp_rewrite;

	$pagenum = (int) $pagenum;
	
	$product_list_page_id = bmfm_get_listing_page_id($category_id);
	if (!$product_list_page_id) {
		return '';
	}
	
	$post_object = get_post($product_list_page_id);
	$post_name = $post_object->post_name;
	$request = "/$post_name/";
	if ($category_id) {
		$term_object = bmfm_get_term($category_id);
		$term_slug   = $term_object->get_slug(); 
		$request = "/$post_name/?freemium_product=$term_slug";
	}

	$home_root = wp_parse_url( home_url() );
	$home_root = ( isset( $home_root['path'] ) ) ? $home_root['path'] : '';
	$home_root = preg_quote( $home_root, '|' );

	$request = preg_replace( '|^' . $home_root . '|i', '', $request );
	$request = preg_replace( '|^/+|', '', $request );

	$qs_regex = '|\?.*?$|';
	preg_match( $qs_regex, $request, $qs_match );

	$parts   = array();
	$parts[] = untrailingslashit( get_bloginfo( 'url' ) );

	if ( ! empty( $qs_match[0] ) ) {
		$query_string = $qs_match[0];
		$request      = preg_replace( $qs_regex, '', $request );
	} else {
		$query_string = '';
	}

	$request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request );
	$request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request );
	$request = ltrim( $request, '/' );

	if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' !== $request ) ) {
		$parts[] = $wp_rewrite->index;
	}

	$parts[] = untrailingslashit( $request );

	if ( $pagenum > 1 ) {
		$parts[] = $wp_rewrite->pagination_base;
		$parts[] = $pagenum;
	}

	$result = user_trailingslashit( implode( '/', array_filter( $parts ) ), 'paged' );
	if ( ! empty( $query_string ) ) {
		$result .= $query_string;
	}
	/**
	 * Filters the page number link for the current request.
	 *
	 * @since 2.5.0
	 * @since 5.2.0 Added the `$pagenum` argument.
	 *
	 * @param string $result  The page number link.
	 * @param int    $pagenum The page number.
	 */
	$result = apply_filters( 'get_pagenum_link', $result, $pagenum );

	if ( $escape ) {
		return esc_url( $result );
	} else {
		return esc_url_raw( $result );
	}
}

/**
 * Create menu items.
 * 
 * @return string
 */
function bmfm_create_menu_items( $extra_menus = array()) {
	$term_ids = bmfm_get_category_ids();
	if (empty($term_ids)) {
		return;
	}
	$menus = array('blinds' => 'Shop Blinds','accessories' => 'Shop Accessories');
	$menus = array_merge($menus, $extra_menus);
	$menu_locations_data    = get_nav_menu_locations();
	if (!is_array($menu_locations_data) || empty($menu_locations_data)) {
		return;
	}
	
	$menu_location_ids = array_values($menu_locations_data);
	$menu_location_id = isset($menu_location_ids[0]) ? $menu_location_ids[0]:'';
	if (!$menu_location_id) {
		return;
	}

	$freemium_page_id  = bmfm_get_freemium_page_id();
	if (!$freemium_page_id) {
		return;
	}

	bmfm_delete_menu_items();
	$filtered_menus = array();
	foreach ($term_ids as $term_id) {
		$term_object = bmfm_get_term($term_id);
		if (!is_object($term_object) || !$term_object->get_name()) {
			continue;
		}
		
		$category_type = $term_object->get_product_category_type();
		if (isset($menus[$category_type])) {
			$filtered_menus[$category_type] = $menus[$category_type];
		}
	}
	
	if (empty($filtered_menus)) {
		return;
	}
	
	$menus           = $filtered_menus;
	$menu_item_ids   = array();
	$blinds_menu_ids = array();
	foreach ($menus as $key => $menu_name) {
		$menu_created_once = true;
		foreach ($term_ids as $term_id) {
			$term_object = bmfm_get_term($term_id);
			if (!is_object($term_object) || !$term_object->get_name()) {
				continue;
			}
			$category_type = $term_object->get_product_category_type();
			if ($key != $category_type) {
				continue;
			}
			
			if ($menu_created_once) {
				$menu_created_once = false;
				$menu_id = wp_update_nav_menu_item($menu_location_id, 0, array(
					'menu-item-title' =>  $menu_name,
					'menu-item-classes' => 'bmfm-parent-activity',
					'menu-item-url' => get_page_link($freemium_page_id),  
					'menu-item-status' => 'publish',
					'menu-item-parent-id' => 0
				   )
				);
			}

			$product_list_page_id = bmfm_get_listing_page_id($term_id);
			$blinds_menu_ids = wp_update_nav_menu_item($menu_location_id, 0, array(
				'menu-item-title'    =>  $term_object->get_name(),
				'menu-item-classes'  => 'bmfm-child-activity',
				'menu-item-url'      => bmfm_get_frontend_product_list_page_url($term_id), 
				'menu-item-status'   => 'publish',
				'menu-item-parent-id'=> $menu_id,
				)
			);
		}
	}
}


/**
 * Delete menu items.
 * 
 * @return void
 */
function bmfm_delete_menu_items() {
	$freemium_page_id  = bmfm_get_freemium_page_id();
	if (!$freemium_page_id) {
		return;
	}
	
	$term_ids = bmfm_get_category_ids();
	if (empty($term_ids)) {
		return;
	}
	
	$menu_child_posts = array();
	foreach ($term_ids as $term_id) {
		$menu_parent_posts = get_posts(array(
			'post_type'      =>'nav_menu_item',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'meta_key'       =>'_menu_item_url',
			'meta_value'     => bmfm_get_frontend_product_list_page_url($term_id),
		));
		
		if (!empty($menu_parent_posts)) {
			foreach ($menu_parent_posts as $menu_parent_post) {
				wp_delete_post($menu_parent_post);
			}
		}
		
		$menu_child_posts = get_posts(array(
			'post_type'      =>'nav_menu_item',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'meta_key'       =>'_menu_item_url',
			'meta_value'     => get_page_link($freemium_page_id),  
		));
		
		if (!empty($menu_child_posts)) {
			foreach ($menu_child_posts as $menu_child_post) {
				wp_delete_post($menu_child_post);
			}
		}
	}
}

/**
 * Validate unsupported themes.
 * 
 * @return bool
 */
function bmfm_unsupported_themes() {
	$theme_object = wp_get_theme();
	if (!is_object($theme_object)) {
		return false;
	}
	
	$unsupported_themes = array('astra');
	/**
	 * Filter unsupported themes.
	 *
	 * @since 1.0
	 */
	return apply_filters('bmfm_unsupported_themes', in_array($theme_object->get( 'TextDomain' ), $unsupported_themes));
}


/**
 * Unsupported themes for flatsome CSS.
 * 
 * @return string
 */
function bmfm_unsupported_theme_flatsome() {
	$theme_object = wp_get_theme();
	if (!is_object($theme_object)) {	
		return false;
	}
	$style_css='';
	if ($theme_object->get( 'TextDomain' ) == 'flatsome') {	
		$style_css ='.bmfm-fabric-color-product-wrapper .woocommerce-product-gallery{
			width: 100% !important;	
			margin:unset !important;	
		}	
		.bmfm-fabric-color-product-wrapper .product-main div#product-sidebar,	
		.bmfm-fabric-color-product-wrapper .product-main .is-divider.small {	
			display: none;	
		}		
		.bmfm-fabric-color-product-wrapper .bmfm-value input {	
			margin-bottom: 0px;		
		}	
		.bmfm-fabric-color-product-wrapper ul.next-prev-thumbs{	
			display:none;	
		}		
		.bmfm-fabric-color-product-wrapper .product-gallery .product-thumbnails {
			margin-top: 10px;	
		}	
		@media (max-width:520px){	
			.bmfm-fabric-color-product-wrapper .product-footer .woocommerce-tabs ul.tabs li{		
				width: 50%;		
				padding-left: 10px;		
			}			
			.bmfm-fabric-color-product-wrapper .product-gallery {		
				margin-bottom: 10%;	
			}		
		}';	} 
		return $style_css;
}

/**
 * Get default category sublists to import.
 * 
 * @return array
 */
function bmfm_get_default_category_sub_lists_data_to_import( $product_name) {
	$blinds_color_categories  = array('Black','Blue','Brown','Cream','Green','Grey','Orange','Pink','Purple','Red','Teal','White','Yellow');
	$category_sub_lists = array();
	foreach ($blinds_color_categories as $blinds_color_category) {
		switch ($blinds_color_category) {
			case 'Black':
				if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Pewter',
						'Graphite',
						'Monsoon',
						'Dusk'
					); 
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Carbon',
						'Tanza',
						'Khol',
						'Kalm',
						'Hazel',
						'Claro'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Unicolour_Black',
						'Unilux_black',
						'Unicolour_Charcoal',
						'Hanson_denim',
						'Plaza_graphite'
					);
				} else if ('wood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Khol',
						'Hazel',
						'Carbon',
						'Callo',
						'Lima_Grain',
						'Orion',
						'Orion_Fine_Grain',
						'Dusk',
						'Tanza',
						'Stratus'
					);
				} else if ('fauxwood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Khol',
						'Hazel',
						'Carbon',
						'Callo',
						'Lima_Grain',
						'Orion',
						'Dusk',
						'Tanza',
						'Stratus'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Wiltonblackout_Black',
						'Patina_Black',
						'Micro_Infusion_Black',
						'Fairhaven_Pearl'
					);
				}
				break;	
			case 'Blue':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Unicolour_Cadet',
						'Bella_Empire',
						'Bella_Indigo',
						'Bella_Midnight',
						'Napa_fiji',
						'Mira_Bright_Sapphire',
						'Banlight_Duo_FR',
						'Banlight_Duo_FR_Indigo',
						'Atlantex_asc_Dark_Blue',
						'Banlight_Duo_FR_Kingfisher'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Cadet',
						'Imperial',
						'Navy',
						'Powder_Blue',
						'Grey_Filtra',
						'Black_Filtra',
						'Cloud',
						'Sensa_Aqua',
						'BanlightFR_Navy',
						'BanlightDuoFR_Ocean',
						'BanlightDuoFR_Indigo',
						'BanlightDuoFR_GlacierBlue'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Cinnabar',
						'Splash_Brittany'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Amico_Breva',
						'Unilux_marine',
						'Unilux_surf',
						'Unicolour_Navy',
						'Devon_denim'
					);
				} else if ('wood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Mirage_blue',
						'Mirage_Grain'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Metro_Blue',
						'Gatsby_Blue',
						'DuopleatBlackout_Blue',
						'Duopleat_Blue',
						'Fairhaven_Ice'
					);
				}
				break;
			case 'Brown':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Superfine_Prima',
						'Yen_walnut',
						'Mira_Bright_Copper',
						'Banlight_Duo_FR_Henna'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Chocolate',
						'Stone',
						'Zora_Coco',
						'Zora_Barley',
						'Aluminium',
						'Linenweave_Graphite',
						'Linenweave_Espresso',
						'Kassala_Spice',
						'Kassala_Bark',
						'BanlightDuoFR_Espresso'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Aluminium',
						'Auburn',
						'Nordic',
						'Montana',
						'Urban_oak',
						'Tuscan_oak',
						'Oregon',
						'Tawny',
						'Honey',
						'Fired_walnut'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Clay',
						'Unicolour_Chocolate',
						'Bella_Placid',
						'Metz_Porcelain',
						'Plaza_stone',
						'Plaza_taupe',
						'Unicolour_Taupe',
						'Bella_Havana',
						'Uniview_Prima',
						'Splash_Taupe'
					);
				} else if ('wood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Claro',
						'Honey',
						'Fired_walnut',
						'Auburn',
						'Mission_Grain',
						'Lima',
						'Desert_Oak',
						'Amber',
						'Tuscan_oak',
						'Trban_oak',
						'Tawny',
						'Oregon',
						'Nordic',
						'Mantis',
						'Mantis_Fine_Grain',
						'Montana'
					);
				} else if ('fauxwood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'tenne',
						'rustic_oak',
						'medium_oak',
						'berry_brown',
						'cinder',
						'Claro',
						'Honey',
						'Fired_walnut',
						'Auburn',
						'Mission_Grain',
						'Lima',
						'Desert_Oak',
						'Amber',
						'Tuscan_oak',
						'Trban_oak',
						'Tawny',
						'Oregon',
						'Nordic',
						'Mantis',
						'Montana',
						'Morena'
					);
				}if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Patina_Darksilver',
						'Patina_Dark_bronze',
						'Patina_Champagnegold'
					); 
				}
				break;
			case 'Cream':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Marlow_sand',
						'Bexley_sandstone',
						'Bexley_creme',
						'Samba_Cream',
						'Metz_ivory',
						'Bella_Placid',
						'Atlantex_asc_Dark_Beige',
						'Atlantex_asc_Cream'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Pebble',
						'Sandstone',
						'Cream',
						'Ivory',
						'Vanity',
						'Natura',
						'Sensa_Grey',
						'PVCHouston_Pearl',
						'PVCHouston_Jasmine',
						'Kassala_Cornsilk',
						'Sensa_Cream',
						'PVCHarlem_Cotton',
						'BanlightDuoFR_Henna'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Alessi_ivory',
						'Gloss_creme',
						'Morena',
						'Mirren',
						'Magnolia',
						'Litra_Coca',
						'Ebon'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Beige',
						'Unicolour_Beige',
						'Napa_agero',
						'Unilux_butter',
						'Unicolour_Cream',
						'Metz_ivory',
						'Napa_oslo'
					);
				} else if ('wood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Gloss_creme',
						'Linara_Fine_Grain',
						'Gravity_Fine_Grain',
						'Mirage',
						'Mirage_Fine_Grain',
						'Linara',
						'Gravity',
						'Mirren'
					);
				} else if ('fauxwood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Mirren',
						'Linara'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Duopleat_Cream',
						'DuopleatBlackout_Cream'
					);
				}
				break;
			case 'Green':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Sorbet',
						'Splash_Vine',
						'Bella_kiwi',
						'Sensa_Lime',
						'Mira_Gold',
						'Kinross_Denim'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bondi',
						'Sensa_Lime',
						'BanlightDuoFRForest_Green',
						'BanlightDuoFR_Green',
						'BanlightDuoFR_FreshApple'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Sorbet',
						'Lyra'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Superfine_Urbane'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'DuopleatBlackout_Green',
						'Gatsby_Green',
						'Metro_Green',
						'Duopleat_Natural'
					);
				}
				break;
			case 'Grey':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Marlow_steel',
						'Bella_Sonar',
						'Bella_Flint',
						'Bexley_truffle',
						'Sensa_Taupe',
						'Sensa_Grey',
						'Mira_Bright_Silver',
						'Atlantex_asc_Grey'
							
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Steel',
						'Charcoal',
						'Flint',
						'Taupe',
						'Dove',
						'Ash',
						'Ice',
						'Brushed_Aluminium',
						'Twilight',
						'Sensa_Taupe',
						'Sensa_Grey',
						'Ribbons_Silver',
						'PVCHouston_Iron',
						'PVCHarlem_Shadow',
						'PVCHarlem_Flint',
						'PVCHarlem_Charcoal',
						'PVCBrooklyn_Moonstone',
						'Linenweave_Charcoal',
						'Kassala_Quartz',
						'Kassala_Emerald',
						'Jasmine_Iris',
						'Brooklyn_Frost',
						'BanlightDuoFR_Grey'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Steel',
						'Ash',
						'Alava',
						'Acacia',
						'Zora_Haze',
						'Revera',
						'Litra_Putty',
						'Estrella_Axis',
						'Cream_Filtra',
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Hanson_graphite',
						'Mood_cosmic',
						'Unicolour_Ash',
						'Splash_Tropez',
						'Unicolour_Dove',
						'Bella_Gable',
						'Napa_cayo',
						'Splash_Sonar'
					);
				} else if ('wood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Kalm',
						'Acacia',
						'Mission',
						'Mission_Fine_Grain',
						'Athena',
						'Athena_Fine_Grain',
						'Ash',
						'Revera'
					);
				} else if ('fauxwood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'highshine_ivory',
						'chiffon',
						'astral',
						'cirrus',
						'cirrus_50mm',
						'haze',
						'greige',
						'flint',
						'kalm'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'DuopleatBlackout_LightGrey',
						'DuopleatBlackout_Grey',
						'Duopleat_Grey',
						'Duopleat_Lightgrey',
						'Tobias_Grey',
						'Substance_Grey',
						'Sarene_Darkgrey',
						'MicroHive_Grey',
						'Harmony_Grey'
					);
				}
				break;
			case 'Orange':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Splash_Jazz',
						'Superfine_Spark'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Cerise',
						'Verona',
						'BanlightDuoFR_Mandarin',
						'BanlightDuoFR_Cora'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Tango',
						'Splash_Tango'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Superfine_Spark'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Sienna_Natural',
						'ShineGoldswatch',
						'Shine_Silver'
					);
				}
				break;
			case 'Pink':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Splash_Orchid',
						'Bella_Flamingo',
						'Bexley_peony',
						'Superfine_Astral'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Lilac',
						'BanlightDuoFR_Fuschia'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Orchild'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Wiltonblackout_Pink',
						'Shimmer_Dots_PinkGold'
					);
				}
				break;
			case 'Purple':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Henlow_Purple',
						'Unicolour_Purple',
						'Bella_Sloe',
						'Bexley_health',
						'Bella_Amparo',
						'Banlight_Duo_FR_Iris',
						'Banlight_Duo_FR_Heather'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Purple',
						'Brushed_Linen',
						'Jasmine_Mulberry',
						'BanlightDuoFR_Mulberry',
						'BanlightDuoFR_Iris',
						'BanlightDuoFR_Heather'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Splash_Amparo',
						'Litra_Fawn'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Uniview_Astral'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'DuopleatBlackout_Slategrey',
						'DuopleatBlackout_Turquoise'
					);
				}
				break;
			case 'Red':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Scarlett',
						'Bella_Bossa',
						'Staten_Lava',
						'Bexley_sorbet',
						'Unicolour_Red'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Lava',
						'Red',
						'Aluminium_Filtra',
						'BanlightDuoFR_Grape'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Sorbet',
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Hanson_chilli',
						'Unilux_lava',
						'Uniview_Spectre',
					);
				} else if ('wood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Morena'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'DuopleatBlackout_Red',
						'Duopleat_Red'
					);
				}
				break;
			case 'Teal':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Baxley_teal',
						'Splash_Twist',
						'Unicolour_Emerald',
						'Splash_Mambo'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Aloe',
						'Galleria',
						'Emerald',
						'Drama',
						'Cyan',
						'Ribbons_Teal'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Mambo',
						'Splash_Como',
						'Majestic',
						'Cyan'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Unicolour_Cyan',
						'Bella_Legion'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Unicolour_Cyan',
						'Bella_Legion',
						'Duopleat_Turquoise',
						'Duopleat_Teal_texture',
						'Duopleat_Taupe',
						'Duopleat_Slategrey',
						'MicroInfusion_Beige',
						'MicroInfusion_Concrete'
					);
				}
				break;
			case 'White':
				if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'White',
						'White_Filtra',
						'Ribbons_White',
						'PVCHarlem_Ice'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'White',
						'Pure',
						'Gloss_pure'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Unicolour_White',
						'Mood_cotton',
						'Estella_duck_egg'
					);
				} else if ('wood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Serene',
						'Serene_Fine_Grain',
						'Truee',
						'True_Fine_Grain',
						'Pure'
					);
				} else if ('fauxwood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'vellum_white',
						'highshine_white',
						'cool_white',
						'snow',
						'metro_White',
						'Serene',
						'Truee',
						'Pure'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'MicroHive_White',
						'ShimmerDots_WhiteGold',
						'Wiltonblackout_White',
						'Sarene_White',
						'Gatsby_Silver',
						'Duopleat_Blackout_White',
					);
				}
				break;
			case 'Yellow':
				if ('roller-blinds' == $product_name || 'roman-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Bella_Solar',
						'Splash_Solar',
						'Unicolour_Lime',
						'Napa_maro',
						'Unicolour_Sienna',
						'Serene_Sand',
						'Boston_FR_Silk_1'
					); 
				} else if ('vertical-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Buttercup',
						'Lime',
						'Sienna',
						'BanlightDuoFR_Mustard',
						'Linenweave_Flax'
					);
				} else if ('venetian-blinds' == $product_name || 'cellular-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Sienna',
						'Echo'
					);
				} else if ('day-night-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Unilux_Lime',
						'Unicolour_Yellow',
						'Devon_pesto'
					);
				} else if ('fauxwood-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'autumn_gold'
					);
				} else if ('pleated-blinds' == $product_name) {
					$category_sub_lists[$product_name][$blinds_color_category] = array(
						'Duopleat_Yellow',
						'Duopleat_blackout_Yellow'
					);
				}
				break;
		}
	}
	
	return $category_sub_lists;
}

/**
 * Update linked categories for fabric products.
 * 
 * @return void
 */
function bmfm_update_linked_categories_for_fabric_products( $fabric_color_product_id, $category_list_ids, $category_sub_lists_products, $file_name) {
	$linked_categories_data = array();
	foreach ($category_list_ids as $category_list_id) {
		$category_sublist_ids = bmfm_get_category_sub_list_ids($category_list_id);
		foreach ($category_sublist_ids as $category_sublist_id) {
			$category_sub_list = bmfm_get_category_sublist($category_sublist_id);
			if (!is_object($category_sub_list)) {
				continue;
			}
			
			$name              = $category_sub_list->get_name();
			$matched_product_names_for_category = isset($category_sub_lists_products[$name]) ? $category_sub_lists_products[$name]:array();

			if (!empty($matched_product_names_for_category) && is_array($matched_product_names_for_category) && in_array( $file_name, $matched_product_names_for_category)) {
				$linked_categories_data[$fabric_color_product_id][$category_list_id][] = $category_sublist_id;
			}
		}
	}
					
	if (!empty($linked_categories_data)) {
		bmfm_update_fabric_color_product($fabric_color_product_id, array(), array('bmfm_linked_categories' => $linked_categories_data));
	}
}

/**
 * Set uploaded image as attachment.
 * 
 * @return int
 */
function bmfm_set_uploaded_image_as_attachment( $url, $attachment_id = 0) {
	if (bmfm_validate_upload_image_curl_request($url)) {
		return 0;
	}
	
	$product_img_upload  = wc_rest_upload_image_from_url(esc_url_raw($url));
	if ( is_wp_error( $product_img_upload ) ) {
		return 0;
	}
	
	if ($attachment_id) {
		if (isset($product_img_upload['file'])) {
			$filename = $product_img_upload['file'];
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata($attachment_id, $filename);
			wp_update_attachment_metadata($attachment_id, $attach_data);
			update_attached_file($attachment_id, $product_img_upload['file']);
		}
	} else {
		$attachment_id = wc_rest_set_uploaded_image_as_attachment($product_img_upload);
	}
	
	return $attachment_id;
}

/**
 * Get merged frame color image URL.
 * 
 * @return void
 */
function bmfm_get_merged_frame_color_image_url( $frame_url, $color_url) {
	if (!$frame_url || !$color_url) {
		return '';
	}
	
	$frame_file_type_data      = wp_check_filetype($frame_url);
	$frame_file_types          = isset($frame_file_type_data['type']) ? explode('/', $frame_file_type_data['type']):array();
	$frame_file_type           = end($frame_file_types);
	if (!$frame_file_type) {
		return '';
	}
	
	$color_file_type_data      = wp_check_filetype($color_url);
	$color_file_types          = isset($color_file_type_data['type']) ? explode('/', $color_file_type_data['type']):array();
	$color_file_type           = end($color_file_types);
	if (!$color_file_type) {
		return '';
	}
		
	// Create image resources from the URLs
	$frame_img_fn = 'imagecreatefrom' . $frame_file_type;   
	$frame_img = $frame_img_fn($frame_url);
	$color_img_fn = 'imagecreatefrom' . $color_file_type;   
	$color_img = $color_img_fn($color_url);
	
	if (false == $frame_img || false == $color_img) {
		return '';
	}

	// Get the width and height of the Frame image
	$frame_width  = imagesx($frame_img);
	$frame_height = imagesy($frame_img);
	$color_width  = imagesx($color_img);
	$color_height = imagesx($color_img);

	// Create a new true color image with the same size as the PNG image
	$new_frame_image = imagecreatetruecolor($frame_width, $frame_height);

	// Copy the color image onto the new frame image
	imagecopyresampled($new_frame_image, $color_img, 0, 0, 0, 0, $frame_width, $frame_height, $color_width, $color_height);

	// Enable blending mode and save full alpha channel information
	imagealphablending($new_frame_image, true);
	imagesavealpha($new_frame_image, true);

	// Copy the PNG image onto the new image
	imagecopy($new_frame_image, $frame_img, 0, 0, 0, 0, $frame_width, $frame_height);
	
	$upload_dir = wp_upload_dir();
	$target_dir = $upload_dir['basedir'] . '/blinds/';
	if (!file_exists($target_dir)) {
		wp_mkdir_p($target_dir);
	}
	
	// Save the merged image to a file
	imagepng($new_frame_image, $upload_dir['basedir'] . '/blinds/frame-color.png');

	// Clean up
	imagedestroy($frame_img);
	imagedestroy($color_img);
	imagedestroy($new_frame_image);
	
	$upload_dir = wp_get_upload_dir();
	if (empty($upload_dir['baseurl'])) {
		return '';
	}
	
	return $upload_dir['baseurl'] . '/blinds/frame-color.png';
}

/**
 * Get price table data for minimum and maximum values.
 * 
 * @return array
 */
function bmfm_get_price_table_min_max_data( $parameter_type_id) {
	$product_type_list = $parameter_type_id ? bmfm_get_product_type_list($parameter_type_id):false;
	if (!is_object($product_type_list)) {
		return array();
	}

	$default_unit = $product_type_list->get_default_unit();
	$price_table_arr    = bmfm_get_stored_price_table_data($parameter_type_id);
	if ('inch' == $default_unit) {
		$price_table_arr = bmfm_get_price_table_data_in_inch();
	} else if ('cm' == $default_unit) {
		$price_table_arr = bmfm_get_price_table_data_in_cm();
	}

	if (empty($price_table_arr) || !is_array($price_table_arr)) {
		return array();
	}

	$max_width  = !empty($price_table_arr[0]) && is_array($price_table_arr[0]) ? max( array_filter( $price_table_arr[0], 'is_numeric')):'';
	$min_width =  !empty($price_table_arr[0]) && is_array($price_table_arr[0]) ? min( array_filter( $price_table_arr[0], 'is_numeric')):'';
	if (!$max_width || !$min_width) {
		return array();
	} 
	
	$min_max_price_table_data = array();
	$min_max_price_table_data['width'] = array(
		'min_width'=> $min_width,
		'max_width'=>$max_width
	);

	$price_table_arr = array_filter(array_map(function( $arr) {
		return isset($arr[0]) && is_numeric($arr[0]) ? $arr[0]:'';
	}, $price_table_arr));

	$max_drop  = max( $price_table_arr);
	$min_drop =  min( $price_table_arr);
	if (!$max_drop || !$min_drop) {
		return array();
	} 

	$min_max_price_table_data['drop'] = array('min_drop'=> $min_drop,'max_drop'=>$max_drop);
	return $min_max_price_table_data;
}

/**
 * Removes invalid terms.
 * 
 * @return void
 */

function bmfm_delete_invalid_terms() {
	$matched_ids = array_values(get_terms(array(
	   'taxonomy' => 'product_cat',
	   'fields' => 'ids',
	   'order' => 'DESC',
	   'hide_empty' => false,
	   'meta_query' => array(
		   array(
			   'key' => 'bmfm_blinds',
			   'compare' => 'EXISTS'
		   ),
		   array(
			   'key' => 'product_count_product_cat',
			   'compare' => 'NOT EXISTS'
		   ),
		   'relation'=>'AND'
		  )
	   )
	));

	$matched_ids = array_merge($matched_ids, array_values(get_terms(array(
	   'taxonomy' => 'product_cat',
	   'fields' => 'ids',
	   'order' => 'DESC',
	   'hide_empty' => false,
	   'meta_query' => array(
		   array(
			   'key' => 'bmfm_blinds',
			   'compare' => 'EXISTS'
		   ),
		   array(
			   'key' => 'product_count_product_cat',
			   'value' => 0
		   ),
		   'relation'=>'AND'
		  )
	   )
	)));

	if (empty($matched_ids)) {
		   return;
	}

	foreach ($matched_ids as $matched_id) {
		   wp_delete_term($matched_id, 'product_cat');
	}
}

/**

 * Reset single or entire product data.
 * 
 * @return bool
 */
function bmfm_reset_plugin_data( $selected_category_ids = array()) {
	bmfm_delete_menu_items();
	if (empty($selected_category_ids) && is_array($selected_category_ids)) {
		$category_ids = bmfm_get_category_ids();
		if (empty($category_ids) || !is_array($category_ids)) {
			return false;
		}	
	} else {
		$category_ids = $selected_category_ids;
	}
	
	$fabric_color_ids = array();
	if (!empty($category_ids)) {
		foreach ($category_ids as $category_id) {
			$term = bmfm_get_term($category_id);
			if ('blinds' == $term->get_product_category_type()) {
				$fabric_color_ids = bmfm_get_fabric_color_ids($category_id, false, false, true);
			}
			if ('accessories' == $term->get_product_category_type()) {
				$fabric_color_ids = bmfm_get_accessories_list_ids($category_id, false, false);
			}
		}
	}
	
	if (!empty($fabric_color_ids) && is_array($fabric_color_ids)) {
		foreach ($fabric_color_ids as $fabric_color_id) {
			$fabric_color_product = bmfm_get_fabric_color_product($fabric_color_id);
			if (is_object($fabric_color_product)) {
				if ('' != absint($fabric_color_product->get_merged_frame_color_thumbnail_id())) {
					wp_delete_attachment(absint($fabric_color_product->get_merged_frame_color_thumbnail_id()), true);
				}

				if (!empty($fabric_color_product->get_product_image_gallery_ids() && is_array($fabric_color_product->get_product_image_gallery_ids()))) {
					foreach ($fabric_color_product->get_product_image_gallery_ids() as $gallery_id) {
						wp_delete_attachment(absint($gallery_id), true);
					}
				}
			}
				wp_delete_post($fabric_color_id);
		}
	}

			//Get parameter list ids
			$parameter_list_ids = bmfm_get_parameter_list_ids($category_ids);
	if (!empty($parameter_list_ids) && is_array($parameter_list_ids)) {
		foreach ($parameter_list_ids as $parameter_list_id) {
			$parameter_list = bmfm_get_parameter_list($parameter_list_id);
			if (!is_object($parameter_list)) {
				continue;
			}
			$parameter_type = $parameter_list->get_parameter_type();
			// Dropdown
			if ('drop_down' == $parameter_type) {
				$dropdown_list_ids = bmfm_get_dropdown_list_ids($parameter_list_id); 						
				if (!empty($dropdown_list_ids) && is_array($dropdown_list_ids)) {
					foreach ($dropdown_list_ids as $dropdown_list_id) {
						wp_delete_post($dropdown_list_id);
					}
				}		
			}
					
			//Component					
			if ('component' == $parameter_type) {
				$component_list_ids = bmfm_get_component_list_ids($parameter_list_id);
				if (!empty($component_list_ids) && is_array($component_list_ids)) {
					foreach ($component_list_ids as $component_list_id) {
						wp_delete_post($component_list_id);
					}
				}
			}
					
			// Range A Product Type.
			if ('product_type' == $parameter_type) {
				$product_type_list_ids = bmfm_get_product_type_list_ids($parameter_list_id);						
				if (!empty($product_type_list_ids) && is_array($product_type_list_ids)) {						
					foreach ($product_type_list_ids as $product_type_list_id) {									
						wp_delete_post($product_type_list_id);
					}            	    	
				}
			}
			wp_delete_post($parameter_list_id);
		}
	} 	
	
		// Category list ids
		$category_list_ids = bmfm_get_category_list_ids($category_ids);
	if (!empty($category_list_ids) && is_array($category_list_ids)) {
		foreach ($category_list_ids as $category_list_id) {
			wp_delete_post($category_list_id);
		}
	}
	
		// Category sublist ids
		$category_sub_list_ids = bmfm_get_category_sub_list_ids($category_list_ids);
	if (!empty($category_sub_list_ids) && is_array($category_sub_list_ids)) {
		foreach ($category_sub_list_ids as $category_sub_list_id) {
			wp_delete_post($category_sub_list_id);
		}
	}

	foreach ($category_ids as $category_id) {
		wp_delete_term($category_id, 'product_cat');
	}

		bmfm_create_menu_items();
			 
	if (empty($selected_category_ids)) {
			update_option('bmfm_settings_resetted', 'yes');
			$delete_page_keys = array('freemium','shop_blinds','shop_accessories');
		foreach ($delete_page_keys as $key) { 
			$page_id = get_option('bmfm_' . $key . '_page_id'); 
			wp_delete_post($page_id, true);
			delete_option('bmfm_' . $key . '_page_id');
		}
	}

		bmfm_delete_invalid_terms();
	return true;
}

/**
 * Get category id based on slug.
 * 
 * @return int
 */
function bmfm_get_category_id_based_on_slug( $slug = false) {
	$get_data = bmfm_get_method();
	if (!$slug) {
		$slug = isset($get_data['freemium_product']) ? wc_clean(wp_unslash($get_data['freemium_product'])):'';
	}
	
	if (!$slug) {
		return 0;
	}
	
	$stored_term = get_term_by('slug', $slug, 'product_cat');
	if (!is_object($stored_term)) {
		return 0;
	}
	
	return !empty($stored_term->term_id) ? absint($stored_term->term_id):0;
}

/**
 * Get frontend product list page URL.
 * 
 * @return string
 */
function bmfm_get_frontend_product_list_page_url( $term_id) {
	if (!$term_id) {
		return '';
	}
	
	$product_list_page_id = bmfm_get_listing_page_id($term_id);
	$stored_term_object   = bmfm_get_term($term_id);
	return 0 != $product_list_page_id && is_object($stored_term_object) ? add_query_arg(array('freemium_product' => $stored_term_object->get_slug() ), get_page_link($product_list_page_id)):'';
}

/**
 * Get delete content popup URL.
 * 
 * @return HTML
 */
function bmfm_get_delete_content_popup_html(){
	ob_start();
	?>
	<div>
		<div class="additional-content-delete-one">
			You are trying to delete the product completely.
		</div>
		<div class="additional-content-delete-two">
			Would you like to proceed further?
		</div>
	</div>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get plugin status.
 * 
 * @return string
 */
function bmfm_is_freemium(){
    return 'freemium' == BMFM_User_Request::get_status();
}

/**
 * Send email.
 */
function bmfm_send_email($to,$email_subject,$message){
    add_filter('wp_mail_from', 'bmfm_mail_from_address'); 
    add_filter('wp_mail_from_name', 'bmfm_mail_from_name'); 
    add_filter('wp_mail_content_type', 'bmfm_mail_content_type'); 
    $mail_sent = wp_mail($to, $email_subject, $message);
    remove_filter('wp_mail_from', 'bmfm_mail_from_address'); 
    remove_filter('wp_mail_from_name', 'bmfm_mail_from_name'); 
    remove_filter('wp_mail_content_type', 'bmfm_mail_content_type');

    return $mail_sent;
}

/**
 * Get mail from address.
 * 
 * @return string
 */
function bmfm_mail_from_address(){
    return !empty(get_option('woocommerce_email_from_address')) ? get_option('woocommerce_email_from_address'):get_option( 'admin_email' );
}

/**
 * Get mail from name.
 * 
 * @return string
 */
function bmfm_mail_from_name(){
    return !empty(get_option('woocommerce_email_from_name')) ? get_option('woocommerce_email_from_name'):get_option( 'blogname' );
}

/**
 * Get mail content type.
 * 
 * @return string
 */
function bmfm_mail_content_type(){
    return "text/html";
}
