<?php
/**
 * Product List
 *
 * This template can be overridden by copying it to yourtheme/blindmatrix-freemium/shortcodes/product-list.php.
 *
 */

if (!defined('ABSPATH')) {
	exit;
}
// Get current page
$current_page = max(1, get_query_var('paged'));
$big = 999999999; // need an unlikely integer

// Prepare pagination arguments
$pagination_args = array(
	'base' =>  str_replace( $big, '%#%', esc_url( bmfm_get_pagenum_link( $big, true, $term_id ) ) ),
	'format' => '?paged=%#%',
	'type ' => 'list',
	'total' => is_object($products) ? $products->max_num_pages:0,
	'current' => $current_page,
	'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
	'next_text' => is_rtl() ? '&larr;' : '&rarr;',
	'type'      => 'list',
	'end_size'  => 3,
	'mid_size'  => 3,
);
if (is_object($products) && $products->have_posts()) :
	?>
<div class="woocommerce-container">
	<ul class="products columns-3 bmfm_product_list bmfm-col-4 row row-small large-columns-4 medium-columns-3 small-columns-2">
		<?php
		while (is_object($products) && $products->have_posts()) {
			$products->the_post();
			wc_get_template( 'product-list-tamplate.php', '', '', BMFM_TEMPLATE_PATH);
			//wc_get_template_part('content', 'product');
		}
			wp_reset_postdata();
		?>
	</ul>
</div>
	<?php
else :
	?>
	   <div class="bmfm-woocommerce-info"><?php echo 'No products found in this category.'; ?></div>
	<?php
endif;
$pagination_links = paginate_links($pagination_args);
// Display pagination
?>
<nav class="woocommerce-pagination">
	<?php 
		print_r($pagination_links);
	?>
</nav>
