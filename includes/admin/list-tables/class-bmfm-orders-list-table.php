<?php
/**
 * Blindmatrix Orders Table
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * BMFM_Blindmatrix_Orders class.
 */
class BMFM_Blindmatrix_Orders extends WP_List_Table {
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
				'singular' => 'order',
				'plural'   => 'orders',
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
		return array(
			'sno'     	       => 'S.No',
			'order_id'         => 'Order ID', 
			'total'            => 'Total',
			'parameters'       => 'Parameters',
			'order_date'       => 'Order Date', 
		);
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
		switch ( $column_name ) {
			case 'sno':
				$sno = self::$count++;
				return "$sno.";
				break;
			case 'order_id':
				return sprintf('<a href="%s" target="_blank">%s</a>', get_edit_post_link($item->get_id()), '#' . $item->get_id());
				break;
			case 'total':
				$order = wc_get_order($item->get_id());
				$order_total = 0;
				if (is_object($order)) {
					$order_items = $order->get_items();
					if (!empty($order_items)) {
						foreach ($order_items as $item_id => $order_item) {
							$blinds_product_data = wc_get_order_item_meta( $item_id, 'bmfm_blinds_product_data', true );
							if (empty($blinds_product_data)) {
								continue;
							}
							
							$order_total += $order_item->get_total();
						}
					}
				}
				return '' != $order_total ? wc_price($order_total):'-';
				break;
			case 'parameters':
				return sprintf('<a href="#" data-order_id="%s" class="bmfm-order-item-detail">View</a>', $item->get_id());
				break;
			case 'order_date':
				return ! is_null( $item->get_date_created() ) && function_exists('wc_format_datetime') ? wc_format_datetime($item->get_date_created()) : '-';
				break;
		}
	}

	/**
	 * Prepare table list items.
	 */
	public function prepare_items() {		
		$this->prepare_column_headers();
		$per_page     = $this->get_items_per_page( 'bmfm_per_page' );
		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}
		
		self::$count = $offset ? $offset+1:1;
		
		$order_ids     = self::get_order_ids();
		$request = bmfm_request_method();
		$searched_keyword = isset($request['s']) ? wc_clean(wp_unslash($request['s'])):false;
		$status_request   = !empty( $request['status'] ) ? wc_clean(wp_unslash($request['status'])) : false;
		$order_by         = !empty( $request['orderby'] ) ? wc_clean(wp_unslash($request['orderby'])) : false;
		$order            = !empty( $request['order'] ) ? wc_clean(wp_unslash( $request['order'])) : false;
		
		if (is_array($order_ids) && !empty($order_ids)) {
			foreach ($order_ids as $order_id) {
				$order = wc_get_order($order_id);
				if (!is_object($order)) {
					continue;
				}
								
				$this->items[] = $order;
			}
		}
		
		//Set the pagination.
		$this->set_pagination_args(
			array(
				'total_items' => count($order_ids),
				'per_page'    => $per_page,
				'total_pages' => ceil( count($order_ids) / $per_page ),
			)
		);

	}

	/**
	 * Get orders ids.
	 */
	public static function get_order_ids() {
		$args = array(
				'status' => array('wc-processing','wc-on-hold','wc-completed'),
				'limit'    => -1,
				'return' => 'ids',
				'meta_key' => '_bmfm_blind_product_in_order',
				'meta_value' => '1'
		);
		
		return wc_get_orders( $args );
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
		echo "<div class='wrap'><div class='bmfm-orders-list-wrapper'>";
		echo '<form method="post" class="bmfm-orders-list-form">';
		echo '<div class="bmfm-blinds-orders-table-heading"><h1 class="wp-heading-inline">Orders</h1></div>';
		$this->handle_bulk_actions();
		$this->prepare_items();
		$this->views();
		$request = bmfm_request_method();
		$searched_keyword = isset($request['s']) ? wc_clean(wp_unslash($request['s'])):false;
		if ($searched_keyword) {
			echo '<span class="subtitle">Search results for: <strong>' . wp_kses_post($searched_keyword) . '</strong></span>';
		}
		
		echo '<div class="bmfm-orders-contents-wrapper">';
		parent::display();
		echo '</div>';
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
}
