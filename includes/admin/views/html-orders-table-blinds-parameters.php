<?php
/**
 * Orders Table Blinds Parameters HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="bmfm-orders-table-blinds-parameters-wrapper">
	<?php 
	foreach ($blinds_parameters as $item_id => $blinds_parameter_data) :
		$item = new WC_Order_Item_Product($item_id);
		$product_name = is_object($item) ? $item->get_name():'';
		$blinds_product_data = wc_get_order_item_meta( $item->get_id(), 'bmfm_blinds_product_data', true );
		if ($product_name) :
			$url = is_object($item->get_product()) ? $item->get_product():'';
			if ($url) :

				?>
			<h2><a href="<?php echo esc_url(get_edit_post_link($item->get_product()->get_id())); ?>" target="_blank"><?php echo wp_kses_post($product_name); ?></a></h2>
				<?php
			else :
				?>
				<h2><?php echo wp_kses_post($product_name); ?></h2>
				<?php
			endif;

		endif;
		?>
		<table class="bmfm-orders-table-blinds-parameters-content widefat striped fixed">
			<tbody>
				<?php
				foreach ($blinds_parameter_data as $name => $value) :
					?>
					<tr>
						<th><b><?php echo wp_kses_post($name); ?></b></th>
						<td><?php echo wp_kses_post($value); ?></td>
					</tr>
				 <?php endforeach; ?>
			</tbody>
		</table>
	<?php endforeach; ?>
</div>
