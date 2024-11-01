<?php
/**
 * Handles the Page Template .
 *
 * @class BMFM_Page_Template_Handler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * BMFM_Page_Template_Handler class.
 */
class BMFM_Page_Template_Handler {
	
	/**
	 * Array of templates that this plugin tracks.
	 */
	protected static $templates = array();
	
	/**
	 * Init.
	 */
	public static function init() {
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
			add_filter('page_attributes_dropdown_pages_args', array( __CLASS__, 'register_project_templates' ));
		} else {
			add_filter('theme_page_templates', array( __CLASS__, 'add_new_template' ));
		}
		add_filter('wp_insert_post_data', array( __CLASS__, 'register_project_templates' ));
		add_filter('template_include', array( __CLASS__, 'view_project_template'));
		
		self::$templates = array('full-width-page-template.php' => 'Full Width Page Template');
	}
	
	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public static function register_project_templates( $atts ) {
		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, self::$templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;
	}
	
	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public static function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, self::$templates );
		return $posts_templates;
	}
	
	/**
	 * Checks if the template is assigned to the page
	 */
	public static function view_project_template( $template ) {
		// Return the search template if we're searching (instead of the template for the first result)
		if ( is_search() ) {
			return $template;
		}

		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}
		
		// Return default template if we don't have a custom one defined
		if ( ! isset( self::$templates[get_post_meta($post->ID, '_wp_page_template', true)] ) ) {
			return $template;
		}

		/**
		 * Allows filtering of file path.
		 *
		 * @since 1.0
		 */
		$filepath = apply_filters( 'page_templater_plugin_dir_path', plugin_dir_path( __FILE__ ) );

		$file =  $filepath . get_post_meta( $post->ID, '_wp_page_template', true);
		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo wp_kses_post($file);
		}

		// Return template
		return $template;

	}
}
