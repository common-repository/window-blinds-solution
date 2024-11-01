<?php
/**
 * Full Width Page Template
 *
 * This template can be overridden by copying it to yourtheme/blindmatrix-freemium/full-width-page-template.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/**
 * Hook:bmfm_before_page_template.
 *
 * @since 1.0
 */
do_action( 'bmfm_before_page_template' ); 
?>
<div id="content" role="main" class="bmfm-page-template-full-width-wrapper content-area">
		<?php 
		while ( have_posts() ) :
			the_post(); 
			the_content(); 
		endwhile; 
		?>
</div>
<?php 
/**
 * Hook:bmfm_after_page_template.
 *
 * @since 1.0
 */
do_action( 'bmfm_after_page_template' ); 
 
get_footer(); ?>
