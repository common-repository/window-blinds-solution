<?php
/**
 * Single Product Blinds parameters
 *
 * This template can be overridden by copying it to yourtheme/blindmatrix-freemium/single-product/blinds-parameters.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$category_ids = $product->get_category_ids();
$category_id  = isset($category_ids[0]) ? $category_ids[0]:'';
$term_object  = bmfm_get_term($category_id);
$product_type_list_id = bmfm_get_product_type_list_id_based_on_cat_id($product->get_category_ids());
$product_type_list_object = bmfm_get_product_type_list($product_type_list_id);
$stored_unit = is_object($product_type_list_object) ? $product_type_list_object->get_default_unit() : 'mm';
$fabric_color_product = bmfm_get_fabric_color_product($product->get_id());
$max_min =   bmfm_get_price_table_min_max_data($product_type_list_id);
?>
<div class="bmfm-blinds-info-wrapper">
			<?php if (is_object($fabric_color_product) && 'blinds' == $fabric_color_product->get_category_type()) : ?>
					<div class="bmfm-blinds-measurements-info">
						<h3>Please enter your measurements in <span><?php echo wp_kses_post($stored_unit); ?></span></h3>
					</div>
					<?php 
				endif;
			foreach ($parameter_list_ids as $parameter_list_id) :
				$parameter_list = bmfm_get_parameter_list($parameter_list_id);
				if (!is_object($parameter_list)) :
					continue;
				endif;
				$mandatory_checked = $parameter_list->get_mandatory_checked();
		
				if ('numeric_x' == $parameter_list->get_parameter_type()) :
					?>
					<div class="bmfm-width-measurement bmfm-blinds-parameter">
						<div class="bmfm-label">
								<label><img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/tick.png'); ?>" width="13"><?php echo wp_kses_post($parameter_list->get_parameter_name()); ?> <span class="bmfm-required">*</span></label>
						</div>
						<div class="bmfm-value">
							<div class="bmfm-width-measure-icon">
								<img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/arrow.png'); ?>" alt="Width Measurement" title="Width Measurement" loading="lazy"> 
							</div>
							<div class="bmfm-width">
								<input type="number" step="any" min =<?php echo wp_kses_post($max_min['width']['min_width'] . ' ' . $stored_unit); ?> max=<?php echo wp_kses_post($max_min['width']['max_width'] . ' ' . $stored_unit); ?> name="bmfm_blinds_product_data[width][<?php echo esc_attr($parameter_list->get_id()); ?>]" placeholder="Min <?php echo wp_kses_post($max_min['width']['min_width'] . ' ' . $stored_unit); ?> ~ Max <?php echo wp_kses_post($max_min['width']['max_width'] . ' ' . $stored_unit); ?> ">
							</div>
						</div>
						<div class="bmfm-error">This field is required.</div>
					</div>
					<?php 
				endif;
		
				if ('numeric_y' == $parameter_list->get_parameter_type()) :
					?>
					<div class="bmfm-drope-measurement bmfm-blinds-parameter">
						<div class="bmfm-label">
								<label><img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/tick.png'); ?>" width="13"><?php echo wp_kses_post($parameter_list->get_parameter_name()); ?> <span class="bmfm-required">*</span></label>
						</div>
						<div class="bmfm-value">
							<div class="bmfm-drop-measure-icon">
								<img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/arrow.png'); ?>" alt="Drop Measurement" title="Drop Measurement" loading="lazy"> 
							</div>
							<div class="bmfm-drop">
								<input type="number" step="any" min=<?php echo wp_kses_post($max_min['drop']['min_drop']); ?> max=<?php echo wp_kses_post($max_min['drop']['max_drop']); ?> name="bmfm_blinds_product_data[drop][<?php echo esc_attr($parameter_list->get_id()); ?>]" placeholder="Min <?php echo wp_kses_post($max_min['drop']['min_drop'] . ' ' . $stored_unit); ?>  ~ Max <?php echo wp_kses_post($max_min['drop']['max_drop'] . ' ' . $stored_unit); ?> ">
							</div>
						</div>
						<div class="bmfm-error">This field is required.</div>
					</div>
					<?php 
				endif;
		
				if ('text' == $parameter_list->get_parameter_type()) :
					?>
					<div class="bmfm-text bmfm-blinds-parameter">
						<div class="bmfm-label">
								<label><img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/tick.png'); ?>" width="13"><?php echo wp_kses_post($parameter_list->get_parameter_name()); ?> <?php 
								if ('on' == $mandatory_checked) {
									?>
									<span class="bmfm-required">*</span><?php } ?></label>
						</div>
						<div class="bmfm-value">
							<input type="text" name="bmfm_blinds_product_data[text][<?php echo esc_attr($parameter_list->get_id()); ?>]">
						</div>
						<div class="bmfm-error">This field is required.</div>
					</div>
					<?php 
				endif;
		
				if ('component' == $parameter_list->get_parameter_type()) :
					$component_list_ids = bmfm_get_component_list_ids($parameter_list->get_id());
					if (!empty($component_list_ids) && is_array($component_list_ids)) :
						?>
					<div class="bmfm-component bmfm-blinds-parameter">
						<div class="bmfm-label">
								<label><img class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/tick.png'); ?>" width="13"><?php echo wp_kses_post($parameter_list->get_parameter_name()); ?> <?php 
								if ('on' == $mandatory_checked) {
									?>
									<span class="bmfm-required">*</span><?php } ?></label>
						</div>
						<div class="bmfm-value">
							<select class="bmfm-component-selection bmfm-select2" name="bmfm_blinds_product_data[component][<?php echo esc_attr($parameter_list->get_id()); ?>]">
								<option>Choose an option</option>
							<?php 
							foreach ($component_list_ids as $component_list_id) :
								$component_list_object = bmfm_get_component_list($component_list_id);
								if (!is_object($component_list_object)) :
									continue;
								endif;
								?>
								<option data-img="<?php echo esc_url($component_list_object->get_image_url()); ?>" value="<?php echo wp_kses_post($component_list_object->get_id()); ?>"><?php echo wp_kses_post($component_list_object->get_name()); ?>
								</option>
								<?php
							endforeach;
							?>
							</select>
						</div>
						<div class="bmfm-error">This field is required.</div>
					</div>
						<?php 
				  endif;
				endif;
				
				if ('drop_down' == $parameter_list->get_parameter_type()) :
					$dropdown_list_ids = bmfm_get_dropdown_list_ids($parameter_list->get_id());
					if (!empty($dropdown_list_ids) && is_array($dropdown_list_ids)) :
						?>
					<div class="bmfm-drop-down bmfm-blinds-parameter">
						<div class="bmfm-label">
								<label><img class="bmfm-tick-img" class="bmfm-tick-img" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BMFM_PLUGIN_FILE ) ) . '/assets/img/tick.png'); ?>" width="13"><?php echo wp_kses_post($parameter_list->get_parameter_name()); ?> <?php 
								if ('on' == $mandatory_checked) {
									?>
									<span class="bmfm-required">*</span><?php } ?></label>
						</div>
						<div class="bmfm-value">
							<select class="bmfm-dropdown-selection bmfm-select2" name="bmfm_blinds_product_data[dropdown][<?php echo esc_attr($parameter_list->get_id()); ?>]">
								<option>Choose an option</option>
							<?php 
							foreach ($dropdown_list_ids as $dropdown_list_id) :
								$dropdown_list_object = bmfm_get_dropdown_list($dropdown_list_id);
								if (!is_object($dropdown_list_object)) :
									continue;
								endif;
								?>
								<option data-img="<?php echo esc_url($dropdown_list_object->get_image_url()); ?>" value="<?php echo wp_kses_post($dropdown_list_object->get_id()); ?>"><?php echo wp_kses_post($dropdown_list_object->get_name()); ?></option>
								<?php
							endforeach;
							?>
							</select>
						</div>
						<div class="bmfm-error">This field is required.</div>
					</div>
						<?php 
				  endif;
				endif;
			endforeach;
	
			$image_info = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'woocommerce_thumbnail' );
			?>
			<input type="hidden" class="bmfm-fabric-color-price" name="bmfm_blinds_product_data[bmfm_fabric_color_price]">
			<input type="hidden" class="bmfm-fabric-color-frame-img" name="bmfm_blinds_product_data[bmfm_fabric_color_frame_url]" value="<?php echo esc_url(isset($image_info[0]) ? $image_info[0]:''); ?>">
			<input type="hidden" class="bmfm-fabric-color-img" name="bmfm_blinds_product_data[bmfm_fabric_color_img_url]" value="<?php echo esc_url(is_object($fabric_color_product) ? $fabric_color_product->get_image_url():''); ?>">
			<input type="hidden" class="bmfm-category-id" name="bmfm_blinds_product_data[category_id]" value="<?php echo esc_attr($category_id); ?>">
			<input type="hidden" class="bmfm-category-type" name="bmfm_blinds_product_data[category_type]" value="<?php echo wp_kses_post(is_object($term_object) ? $term_object->get_product_category_type() : ''); ?>">
			<input type="hidden" class="bmfm-product-type-id" name="bmfm_blinds_product_data[product_type_id]" value="<?php echo esc_attr($product_type_list_id); ?>">
			<input type="hidden" name="bmfm_blinds_product_data[unit]" value="<?php echo esc_attr($stored_unit); ?>">
			<input type="hidden" name="bmfm_blinds_product_data[fabric_color_id]" value="<?php echo wp_kses_post(is_object($product) ? $product->get_id():''); ?>">
			<input type="hidden" name="bmfm_blinds_product_data[product_name]" value="
			<?php 
			echo wp_kses_post(is_object($term_object) ? $term_object->get_name() : '');
			echo wp_kses_post(' - ' . get_the_title()); 
			?>
			">
			
		</div>
		<?php
