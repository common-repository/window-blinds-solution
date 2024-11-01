<?php
/**
 * Fabric Color List Row HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<tr>
		<td>
			<?php 
			$first_col =!empty($sno) ? $sno++:'';
			echo esc_attr(!empty($first_col) ? "$first_col.":''); 
			?>
		</td>
		<td>
			<input type="text" class="bmfm-fabric-color-name" 
			data-name="bmfm_product_setup_data[product_list_setup][name]" 
			name="bmfm_product_setup_data[product_list_setup][name][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]"
			value="<?php echo wp_kses_post(!empty($fabric_color_name) ? $fabric_color_name:''); ?>">
		</td>
		<td>
			<a href="#" class="bmfm-upload-image button-secondary" style="<?php echo esc_attr(!empty($image_url) ? 'display:none':''); ?>" data-pixel="500">Upload</a>
			<span class="bmfm-upload-image-span bmfm-upload-fabric-color-image-span">
				<img src="<?php echo esc_url(!empty($image_url) ? $image_url:''); ?>" class="bmfm-upload-image-src <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>" >
				<span class="dashicons dashicons-dismiss bmfm-remove-image <?php echo esc_attr(!empty($image_url) ? 'bmfm-image-added':''); ?>"></span>
			</span>
			<input type="hidden" class="bmfm-upload-image-url bmfm-fabric-color-upload-image-url" name="bmfm_product_setup_data[product_list_setup][image_url][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" data-name="bmfm_product_setup_data[product_list_setup][image_url]" value="<?php echo esc_url(!empty($image_url) ? $image_url:''); ?>">
		</td>
		<td class="bmfm-choice-frame">
			<span class="bmfm-upload-custom-image-span">
				 <a href="#" class="bmfm-upload-img-a button-secondary" style="<?php echo esc_attr(!empty($frame_url) ? 'display:none':''); ?>">Upload</a>
				 <img src="<?php echo esc_url(!empty($frame_url) ? $frame_url:''); ?>" class="bmfm-upload-image-src <?php echo esc_attr(!empty($frame_url) ? 'bmfm-image-added':''); ?>" >
				 <span class="dashicons dashicons-dismiss bmfm-remove-image <?php echo esc_attr(!empty($frame_url) ? 'bmfm-image-added':''); ?>"></span>
				 <input type="hidden" class="bmfm-upload-image-url bmfm-frame-upload-image-url" name="bmfm_product_setup_data[product_list_setup][frame_image_url][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" data-name="bmfm_product_setup_data[product_list_setup][frame_image_url]" value="<?php echo esc_url(!empty($frame_url) ? $frame_url:''); ?>">
				 <input type="hidden" class="bmfm-frame-upload-product-name" name="bmfm_product_setup_data[product_list_setup][uploaded_frame_pdt_name][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" value="<?php echo wp_kses_post(!empty($uploaded_frame_pdt_name) ? $uploaded_frame_pdt_name:''); ?>">
				 <input type="hidden" class ="bmfm-saved-frame-data" data-frame_url="<?php echo esc_url(!empty($frame_url) ? $frame_url:''); ?>" data-product_name="<?php echo wp_kses_post(!empty($uploaded_frame_pdt_name) ? $uploaded_frame_pdt_name:''); ?>">
			</span>
		</td>
		<td>
			<input type="checkbox"
			class="bmfm-hide-frame bmfm-hide-farames-checkbox-size bmfm-hide-frame-action"
			name="bmfm_product_setup_data[product_list_setup][hide_frame][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" 
			data-name="bmfm_product_setup_data[product_list_setup][hide_frame]" 
			<?php echo esc_attr(!empty($hide_frame) ? 'checked=true':''); ?>>
		</td>
		<td>
			<span class="bmfm-upload-images">
				<?php 
				if (!empty($material_images_url) && is_array($material_images_url)) :
					foreach ($material_images_url as $material_image_url) :
						?>
						<span class="bmfm-per-upload-image selected">
							<img src="<?php echo esc_url($material_image_url); ?>" width="20">
							<span class="dashicons dashicons-dismiss bmfm-remove-material-image"></span>
						</span>
						<?php
					endforeach;
				endif;
				?>
			</span>
			<a href="#" class="bmfm-upload-image button-secondary" data-multiple="true" data-pixel="500">Upload</a>
			<input type="hidden" class="bmfm-upload-image-url bmfm-material-upload-image-urls" name="bmfm_product_setup_data[product_list_setup][material_image_urls][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]" data-name="bmfm_product_setup_data[product_list_setup][material_image_urls]" value="<?php echo esc_attr(!empty($material_images_url) && is_array($material_images_url) ? implode(',', $material_images_url):''); ?>">
		</td>
		<td>
			<textarea class="bmfm-fabric-color-desc" 
			data-name="bmfm_product_setup_data[product_list_setup][desc]" name="bmfm_product_setup_data[product_list_setup][desc][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]"><?php echo wp_kses_post(!empty($fabric_color_desc) ? $fabric_color_desc:''); ?></textarea>
		</td>
		<td>
			<a class="button-primary bmfm-button bmfm-delete-list-row bmfm-remove-fabric-color-row" data-post_id="<?php echo wp_kses_post(!empty($fabric_color_id) ?$fabric_color_id:'' ); ?>">Delete<span class="dashicons dashicons-trash"></span></a>
			<input type="hidden" name="bmfm_product_setup_data[product_list_setup][post_id][]" value="<?php echo wp_kses_post(!empty($fabric_color_id) ? $fabric_color_id:''); ?>">
			<input type="hidden" class="bmfm-changed-data" name="bmfm_product_setup_data[product_list_setup][changed][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]">
		</td>
	</tr>
