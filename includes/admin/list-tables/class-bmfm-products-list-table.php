<?php
/**
 * Blindmatrix Products Table
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * BMFM_Blindmatrix_Products class.
 */
class BMFM_Blindmatrix_Products extends WP_List_Table {
	/**
	 * Count.
	 */
	protected static $count = 1;
	/**
	 * Extra columns data.
	 */
	protected static $extra_columns_data = array();
	/**
	 * Initialize the products table list.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'product',
				'plural'   => 'products',
				'ajax'     =>  false,
			)
		);
	}

	/**
	 * No items found text.
	 */
	public function no_items() {
		echo 'No data found';
	}
	
	/**
	 * Handle bulk actions.
	 */
	public function handle_bulk_actions() {
		$action = $this->current_action();
		if ( ! $action ) {
			return;
		}
		
		$request = bmfm_request_method();
		$bulk_action_ids = isset($request['ids']) && !empty($request['ids']) ? wc_clean(wp_unslash($request['ids'])):array();
		if (!empty($bulk_action_ids) && 'delete' == $action ) {
			foreach ($bulk_action_ids as $bulk_action_id) {
				wp_delete_post($bulk_action_id);
				wp_delete_term($bulk_action_id, 'product_cat');
			}
		}
	}
	
	/**
	 * Table list views.
	 *
	 * @return array
	 */
	protected function get_views() {

	}
	

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		$get_data = bmfm_get_method();
		$product_id        = isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):'';
		$category_list_ids = bmfm_get_category_list_ids($product_id);
		if (!empty($category_list_ids) && is_array($category_list_ids)) {
			foreach ($category_list_ids as $category_list_id) {
				$category_list = bmfm_get_category_list($category_list_id);
				if (!is_object($category_list)) :
					continue;
				 endif;
				$name                = $category_list->get_name();
				$key                 = preg_replace('/\s+/', '_', strtolower($name));
				$additional_columns[$key] = $name;
				self::$extra_columns_data[$key] = $category_list_id;
			}
		}
		
		$columns = array(
			'sno'     	       => 'S.No',
			'fabric_color'     => 'Fabric/Color', 
			'frame_color_image'=> 'Image',
			'description'      => 'Description',
			'price'            => 'Price', 
		);
		
		$category_id      = isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):'';
		$category_object  = bmfm_get_term($category_id);
		if ($category_id && is_object($category_object) && 'accessories' == $category_object->get_product_category_type()) {
			unset($columns['product_type']);
		} else if ($category_id && is_object($category_object) && 'blinds' == $category_object->get_product_category_type()) {
			unset($columns['price']);
		}
		
		$additional_columns['action'] = 'Action';
		
		return !empty($additional_columns) ? array_merge($columns, $additional_columns):$columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array();
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		
	}

	/**
	 * Column cb.
	 *
	 * @param  $item userslist instance.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="ids[]" value="%s" />', $item->get_id() );
	}
	/**
	 * Default Columns.
	 *
	 * @return array
	 */
	public function column_default( $item, $column_name ) {
		$get_data = bmfm_get_method();
		switch ( $column_name ) {
			case 'sno':
				$count = self::$count++;
				return "$count.";
				break;
			case 'fabric_color':
				return $item->get_product_name();
				break;
			case 'frame_color_image':
				if ('blinds' == $item->get_category_type() && '' != $item->get_image_url()) {
					return sprintf('<img src="%s" width="40">', $item->get_merged_frame_color_url());
				} else if ('accessories' == $item->get_category_type() && '' != $item->get_image_url()) {
					return sprintf('<img src="%s" width="40">', $item->get_image_url());
				}
				return '-';
				break;
			case 'price':
				return wc_price($item->get_price());
				break;
			case 'description':
				if (!is_object($item->get_product())) {
					return '';
				}
				$desc = strlen($item->get_product()->get_description()) > 40 ? substr($item->get_product()->get_description(), 0, 40) . '...':$item->get_product()->get_description();
				return is_object($item->get_product()) && !empty($item->get_product()->get_description()) ?$desc:'-' ;
				break;		
			case 'action':
				$edit_url = add_query_arg(array('bmfm_cat_id' => isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):'','bmfm_fabric_product'=> 'yes'), admin_url('admin.php?page=products_list_table&bmfm_add_product=1&bmfm_current_section=products_list'));
				$edit_icon = untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/edit.png';
				return sprintf('<span><a class="button bmfm-button bmfm-view-product-dashboard bmfm-view-fabric-color-product-dashboard" href="%s" data-post_id="%s">View on website<span class="dashicons dashicons-welcome-view-site"></span></a><a class="button bmfm-button selected bmfm-edit-product-dashboard" href="%s">Edit<img src="%s" width="16"></a><a class="button bmfm-button bmfm-delete-product-dashboard bmfm-remove-fabric-color-row-dashboard" href="#" data-post_id="%s">Delete<span class="dashicons dashicons-trash"></span></a></span>', $item->get_product()->get_permalink(), $item->get_id(), $edit_url, $edit_icon, $item->get_id());
				break;
		}
		
		if (!empty(self::$extra_columns_data) && is_array(self::$extra_columns_data)) {
			$linked_categories = !empty($item->get_linked_categories()) && is_array($item->get_linked_categories()) ? $item->get_linked_categories():array();
			foreach (self::$extra_columns_data as $key => $category_list_id) {
				$linked_sub_category_ids  = !empty($linked_categories[$item->get_id()][$category_list_id]) && is_array($linked_categories[$item->get_id()][$category_list_id]) ? $linked_categories[$item->get_id()][$category_list_id] :array();
				switch ( $column_name ) {
					case $key:
						$category_sub_list_ids = bmfm_get_category_sub_list_ids($category_list_id);
						if (!empty($category_sub_list_ids) && is_array($category_sub_list_ids)) {
							?>
							<select multiple="multiple" class="bmfm-select2 bmfm-category-selection" name="bmfm_category_selection_dashboard[<?php echo wp_kses_post($item->get_id()); ?>][<?php echo wp_kses_post($category_list_id); ?>][]">
								
							<?php
							foreach ($category_sub_list_ids as $category_sub_list_id) {
								$category_sub_list = bmfm_get_category_sublist($category_sub_list_id);
								if (!is_object($category_sub_list)) {
									continue;
								}
								
								?>
								<option <?php echo in_array($category_sub_list_id, $linked_sub_category_ids) ? 'selected=true':''; ?> data-img="<?php echo wp_kses_post($category_sub_list->get_image_url()); ?>" value="<?php echo wp_kses_post($category_sub_list_id); ?>">
								  <?php echo wp_kses_post($category_sub_list->get_name()); ?>
								</option>
								<?php
								
							}
							
							?>
							</select>
							<input type="hidden" class="bmfm-fabric-color-id" value="<?php echo wp_kses_post($item->get_id()); ?>"> 
							<?php
						} else {
							echo '-';
						}
						break;
				}
			}
		}

		return '';
	}

	/**
	 * Prepare table list items.
	 */
	public function prepare_items() {		
		$this->prepare_column_headers();
		$per_page     = 10;
		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}
		
		self::$count = $offset ? $offset+1:1;
		$get_data = bmfm_get_method();
		$category_id      = isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):'';
		$category_ids     = !empty($category_id) ? array($category_id):array();
		$category_object  = bmfm_get_term($category_id);
		
		$all_fabric_color_ids = bmfm_get_fabric_color_ids($category_ids);
		$fabric_color_ids     = bmfm_get_fabric_color_ids($category_ids, $offset, $per_page);
		if ('accessories' == $category_object->get_product_category_type()) {
			$all_fabric_color_ids = bmfm_get_accessories_list_ids($category_ids);
			$fabric_color_ids     = bmfm_get_accessories_list_ids($category_ids, $offset, $per_page);
		}
		$request = bmfm_request_method();
		$searched_keyword = isset($request['s']) ? wc_clean(wp_unslash($request['s'])):false;
		$status_request   = !empty( $request['status'] ) ? wc_clean(wp_unslash($request['status'])) : false;
		$order_by         = !empty( $request['orderby'] ) ? wc_clean(wp_unslash($request['orderby'])) : false;
		$order            = !empty( $request['order'] ) ? wc_clean(wp_unslash($request['order'])) : false;
		
		if (is_array($fabric_color_ids) && !empty($fabric_color_ids)) {
			foreach ($fabric_color_ids as $fabric_color_id) {
				$fabric_color_object = bmfm_get_fabric_color_product($fabric_color_id);
				if (!is_object($fabric_color_object)) {
					continue;
				}
								
				$this->items[] = $fabric_color_object;
			}
		}
		
		//Set the pagination.
		$this->set_pagination_args(
			array(
				'total_items' => count($all_fabric_color_ids),
				'per_page'    => $per_page,
				'total_pages' => ceil( count($all_fabric_color_ids) / $per_page ),
			)
		);

	}

	/**
	 * Extra table contents.
	 */
	protected function extra_tablenav( $which) {
		
		if ('top' == $which) {
			return $which;
		}
	
			$get_data = bmfm_get_method();
			$category_id = isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):'';
			$category_object  = bmfm_get_term($category_id);       
			
			$all_fabric_color_ids = bmfm_get_fabric_color_ids($category_id);
			$cat_count = '';
		if (count($all_fabric_color_ids) >= '50') {
			$cat_count ='bmfm-upgrade-premium-button';
		}

			$edit_url = add_query_arg(array('bmfm_cat_id' => isset($get_data['bmfm_cat_id']) ? absint($get_data['bmfm_cat_id']):'','bmfm_fabric_product'=> 'yes'), admin_url('admin.php?page=products_list_table&bmfm_add_product=1'));
		if ('accessories' == $category_object->get_product_category_type()) {
				$edit_text = 'Add new accessories';
		} else {
				$edit_text = 'Add new fabric';
		}
			$add_new_product = "<a href='$edit_url' class='button-primary bmfm-add-new-fabric'><span class='bmfm-product-link-span'><span class='dashicons dashicons-plus-alt'></span> $edit_text </span></a>";
			echo wp_kses_post("<div class='bmfm-add-new-product-in-dashboard $cat_count'> $add_new_product");
			echo '</div>';
	}
	/**
	 * Set _column_headers property for table list
	 */
	protected function prepare_column_headers() {
		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
		);
	}

	/**
	 * Display items.
	 */
	public function display() {
		$stored_product_ids = bmfm_get_category_ids();
		$class = !empty($stored_product_ids) && count($stored_product_ids) >= 3 ? 'bmfm-hide-add-product-button':'';
		echo "<div class='wrap'><div class='bmfm-products-list-wrapper'>";
		echo '<form method="post" class="bmfm-products-list-form">';
		include(BMFM_ABSPATH . '/includes/admin/views/html-blinds-per-product-table.php');
		$this->handle_bulk_actions();
		$this->prepare_items();
		$this->views();
		$request = bmfm_request_method();
		$searched_keyword = isset($request['s']) ? wc_clean(wp_unslash($request['s'])):false;
		if ($searched_keyword) {
			echo '<span class="subtitle">Search results for: <strong>' . wp_kses_post($searched_keyword) . '</strong></span>';
		}
		
		echo '<div class="bmfm-products-contents-wrapper">';
		parent::display();
		echo '</div>';
		$crown = untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/crown.png';
		$upgrade_premium = wp_kses_post("<a href='#' class='button-primary bmfm-button bmfm-upgrade-premium-button'><span class='bmfm-product-link-span'>Upgrade to Premium <img class='bmfm-upgrade-premium-img' src='$crown' width='20' height='20'></span></a>");
		echo wp_kses_post("<div class='bmfm-upgrade-premium-in-dashboard'><span>To add more products / fabrics / price tables / category filters</span>  $upgrade_premium");
		$get_data = bmfm_get_method();
		$cat_id = isset($get_data['bmfm_cat_id']) ? wc_clean(wp_unslash($get_data['bmfm_cat_id'])):'';
		echo wp_kses_post("<div class='bmfm-reset-data-wrapper'><a href='#' class='bmfm-reset-data-action button' data-post_id='$cat_id'>Delete product</a></div>");
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
}
