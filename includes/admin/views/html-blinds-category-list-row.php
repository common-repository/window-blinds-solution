<?php
/**
 * Category List Row HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<tr>	
		<td>
			<?php echo wp_kses_post(!empty($category_s_no) ? $category_s_no++:'1'); ?>
		</td>
		<td>
			<input type="text" 
				   class="bmfm-category-list-name"
				   data-name="bmfm_category_list[name]" 
				   name="bmfm_category_list[name][<?php echo wp_kses_post(isset($key) ? $key : ''); ?>]"
				   value="<?php echo wp_kses_post(!empty($name) ? $name:''); ?>" placeholder="Enter Category Name">
		</td> 
		<td>
			<?php 
			if (!empty($category_list_id)) :
				$add_text = 'Add';
				?>
				<a href="#" class="button-secondary bmfm-edit-category-list" 
				   data-category_list_id="<?php echo wp_kses_post($category_list_id); ?>"
				   data-product_id ="<?php echo wp_kses_post($product_id); ?>">
					<?php echo esc_html($add_text); ?>
				</a>
				<?php
			else :
				echo '-';
			endif;
			?>
		</td>
		<td>
			<input type="hidden" name="bmfm_category_list[post_id][]" value="<?php echo wp_kses_post(!empty($category_list_id) ? $category_list_id:''); ?>">
			<a class="button-primary bmfm-button bmfm-delete-list-row bmfm-blinds-remove-category-list" data-post_id="<?php echo wp_kses_post(!empty($category_list_id) ?$category_list_id:''); ?>">Delete<span class="dashicons dashicons-trash"></span></a>
		</td>
		<td>
			<span class="dashicons dashicons-menu"></span>
		</td>	
	</tr>
