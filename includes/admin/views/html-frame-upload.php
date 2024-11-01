<?php
/**
 * Frame upload HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bmfm-frame-error-msg">Please select atleast any one frame</div>
<div class="bmfm-frame-upload-section">
	<?php 
	$product_names = array('roller-blinds','vertical-blinds','day-night-blinds','venetian-blinds','wood-blinds');
	foreach ($product_names as $product_name) :
		$product_frame_url = BMFM_CLOUDURL . 'visualizer-frame/' . $product_name . '/frame.png';
		$sample_preview_url = BMFM_CLOUDURL . 'visualizer-frame/preview-sample/' . $product_name . '.jpg';
		?>
		<div class="bmfm-frame-custom-upload">
				<img src="<?php echo esc_url($product_frame_url); ?>" 
			class="bmfm-custom-popup-img"
			data-img_url="<?php echo esc_url($product_frame_url); ?>" 
			data-product_name="<?php echo wp_kses_post($product_name); ?>"
			data-sample_preview_url ="<?php echo wp_kses_post($sample_preview_url); ?>"
			alt="no image" width="100">
				<span class="dashicons dashicons-yes-alt"></span>
				<span class="bmfm-frame-product-name">
				<?php 
				$_product_name = str_replace('-', ' ', $product_name);
				echo wp_kses_post($_product_name); 
				?>
				</span>
		</div>
		<?php
	endforeach;
	?>
</div>
