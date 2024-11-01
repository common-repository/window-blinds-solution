<?php
/**
 * Blinds and Accessories List
 *
 * This template can be overridden by copying it to yourtheme/blindmatrix-freemium/shortcodes/blinds_and_accessories_list.php.
 *
 */

if (!defined('ABSPATH')) {
	exit;
}

$get_data = bmfm_get_method();
// Get the target category slug
$term_id = bmfm_get_category_id_based_on_slug();
if (!$term_id) {
	return;
}

$_term = bmfm_get_term($term_id);
if (!is_object($_term)) {
	return;
}

$selected_categories_data = array();
if (!empty($get_data)) {
	foreach ($get_data as $parent_category_list_id => $child_category_data) {
		if (!absint($parent_category_list_id)) {
			continue;
		}
		
		$child_category_ids = explode(',', $child_category_data);
		$selected_categories_data[$parent_category_list_id] = $child_category_ids;
	}
}

$_orderby = isset($get_data['bmfm_orderby']) ? wc_clean(wp_unslash($get_data['bmfm_orderby'])):'menu_order';
$products = bmfm_get_products_based_on_category_filter($term_id, $selected_categories_data, false, $_orderby);
?>
<div class="bmfm-category-page-container">
<header class="woocommerce-products-header">
	<?php 
	if ('' != $_term->get_name()) :
		?>
		<h3 class="bmfm-product-list-title"><?php echo wp_kses_post($_term->get_name()); ?></h3>
		<?php
	endif;
	/**
	 * Hook: woocommerce_archive_description.
	 * 
	 * @since 1.0
	 */
	do_action( 'woocommerce_archive_description' );
	/**
	 * Hook: bmfm_archive_description.
	 * 
	 * @since 1.0
	 */
	do_action( 'bmfm_archive_description', $term_id );
	?>
</header>

<div class="bmfm-container">
	<?php 
	$product_id           = isset($products->posts) ? $products->posts: 0 ; 
	$fabric_color_product = bmfm_get_fabric_color_product($product_id);
	if (is_object($_term) && 'blinds' == $_term->get_product_category_type()) : 
		?>
	
	<div class="bmfm-category-filtering" bis_skin_checked="1">	
		<div id="bmfm-sidebar-btn" class="bmfm-filter-button">		
			<span class="dashicons dashicons-filter"></span>	
			<strong>Filter By</strong>		
		</div>	
	</div>
	
	  <div class="bmfm-category-filters">
		<span class="bmfm-swatch_thumbnails_container">
			<label class="switch_label">Product view</label>
			<label class="bmfm_switch">
				<input type="checkbox" class="bmfm_swatch_thumbnails" id="bmfm_swatch_thumbnails" hidden>
				<span class="bm_slider round"></span>
			</label>
			<label class="switch_label">Fabric view</label>
		</span>
	  </div>
	<?php endif; ?>
	
	<div class="bmfm-radio-layouts">		
		<label>		
			<span class="radio-layout grid-3">		
				<input class="bmfm_layout" type="radio" name="radio"/>	
				<img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/grid-3.jpg'); ?>" alt="Grid 3" title="Grid 3" loading="lazy"> 	
			</span>	
		</label>
		<label>		
			<span class="radio-layout grid-4 bmfm_grid_checked">	
				<input class="bmfm_layout " type="radio" name="radio" checked/>		
				<img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/grid-4.jpg'); ?>" alt="Grid 4" title="Grid 4" loading="lazy"> 		
			</span>		
		</label>		
		<label>		
			<span class="radio-layout grid-5">			
				<input class="bmfm_layout" type="radio" name="radio" />		
				<img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/grid-5.jpg'); ?>" alt="Grid 5" title="Grid 5" loading="lazy"> 	
			</span>		
		</label>	
	</div>
	
	<div class="bmfm-orderby-filters">
		<select name="orderby" class="orderby" aria-label="Shop order">
					<option value="menu_order" <?php echo wp_kses_post('menu_order' == $_orderby ? 'selected="selected"' :''); ?>>Default sorting</option>
					<option value="rating" <?php echo wp_kses_post('rating' == $_orderby ? 'selected="selected"' :''); ?>>Sort by average rating</option>
					<option value="date" <?php echo wp_kses_post('date' == $_orderby ? 'selected="selected"' :''); ?>>Sort by latest</option>
					<option value="asc" <?php echo wp_kses_post('asc' == $_orderby ? 'selected="selected"' :''); ?>>Sort by ascending</option>
					<option value="desc" <?php echo wp_kses_post('desc' == $_orderby ? 'selected="selected"' :''); ?>>Sort by descending</option>
				<?php 
				if ('accessories' == $_term->get_product_category_type()) {  
					?>
					<option value="price" <?php echo wp_kses_post('price' == $_orderby ? 'selected="selected"' :''); ?>>Sort by Price</option>
					<?php } ?>
		</select>
	</div>
	
	<div class="bmfm-total-rows">
		<span class="woocommerce-result-count"><span class="bmfm-products-count"><?php echo esc_html(isset($products->found_posts) ? $products->found_posts:'0'); ?></span> Items</span>
	</div>
</div>
<input class="bmfm-term_id" value="<?php echo wp_kses_post($term_id); ?>" hidden>
<?php 
$have_posts = is_object($products) ? $products->have_posts() : false;
?>
<div class="bmfm-woocommerce-product-container" >
	<form class="bmfm-products-list-form" method="POST">
		<?php 
		$category_list_ids        = bmfm_get_category_list_ids($term_id);
		$linked_categories_exists = bmfm_validate_linked_categories_exists($category_list_ids);
		if (!empty($category_list_ids) && $linked_categories_exists) :
			?>
		<div id="bmfm-sidebar" class="bmfm-woocommerce-product-filter">
			<?php
				wc_get_template( 'shortcodes/category_filters.php', array('category_list_ids'=>$category_list_ids,'selected_categories_data' => $selected_categories_data), '', BMFM_TEMPLATE_PATH);
			?>
		</div>
		<button title="Close (Esc)" type="button" class="bmfm-close">
			<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>	
		</button>
			<?php 
		endif;
		if ($have_posts) :
			?>
		<div class="woocommerce bmfm-woocommerce-product-list <?php echo !$linked_categories_exists ? 'bmfm-product-list-full-width':''; ?>" >
			<?php 
			wc_get_template( 'shortcodes/product-list.php', array('products'=>$products,'term_id' => $term_id), '', BMFM_TEMPLATE_PATH);
		else :
			?>
				   <div class="bmfm-woocommerce-info"><?php echo 'No products found in this category.'; ?></div>
			<?php
		endif;
		?>
		</div>
	</form>
	</div>
	</div>
