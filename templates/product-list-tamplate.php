<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/blindmatrix-freemium/product-list-template.php.
 *
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	/**
	 * Hook: bmfm_woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 * 
	 * @since 1.0
	 */
	do_action( 'bmfm_woocommerce_before_shop_loop_item' );

	/**
	 * Hook: bmfm_woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 * 
	 * @since 1.0
	 */
	do_action( 'bmfm_woocommerce_before_shop_loop_item_title' );

	?>
	<div class="bmfm-blinds-shop-loop-item-title-wrapper">
		<div class="bmfm-blinds-shop-loop-item-title">
			<?php 
				/**
				 * Hook: bmfm_woocommerce_shop_loop_item_title.
				 *
				 * @hooked woocommerce_template_loop_product_title - 10
				 * 
				 * @since 1.0
				 */
				do_action( 'bmfm_woocommerce_shop_loop_item_title' );
			?>
		</div>
		<?php 	
			/**
			 * Hook: bmfm_woocommerce_color_img_shop_loop_item_title.
			 *  
			 * @since 1.0
			 */
			do_action( 'bmfm_woocommerce_color_img_shop_loop_item_title' );
		?>
	</div>
		<?php

		/**
		 * Hook: bmfm_woocommerce_after_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 * 
		 * @since 1.0
		 */
		do_action( 'bmfm_woocommerce_after_shop_loop_item_title' );

		/**
		 * Hook: bmfm_woocommerce_after_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 * 
		 * @since 1.0
		 */
		do_action( 'bmfm_woocommerce_after_shop_loop_item' );
		?>
</li>

