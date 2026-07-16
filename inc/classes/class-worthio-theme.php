<?php
/**
 * Bootstraps the Theme.
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;
use WORTHIO_THEME\Inc\Traits\Singleton;

class WORTHIO_THEME {
	use Singleton;

	protected function __construct() {		
		// Load class.	
		 Assets::get_instance();		
		 Menus::get_instance();
		 Sidebars::get_instance();
		 Meta_Boxes::get_instance();
		 Register_Post_Types::get_instance();
		 Register_Taxonomies::get_instance();	
		 Schema_Engine::get_instance();	
		 Archive_Settings::get_instance();
		
		
		// Blocks::get_instance();		
		// Block_Patterns::get_instance();
		// loadmore_Single::get_instance();
		// Loadmore_Posts::get_instance();		
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/* Actions */		
		add_action('after_setup_theme', [$this, 'setup_theme']);
	}

	public function setup_theme() {
		add_theme_support( 'title-tag' );

		add_theme_support( 'custom-logo', [
		'header-text'          => array( 'site-title', 'site-description' ),
		'height'               => 48,
		'width'                => 184,
		'flex-height'          => true,
		'flex-width'           => true,		
		'unlink-homepage-logo' => true, 
		] );	
		
		add_theme_support( 'post-thumbnails');
		add_image_size( 'featured-thumbnail', 350, 233, true);
		add_theme_support( 'customize-selective-refresh-widgets');
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', [
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style'
		] );

		
		
		add_theme_support( 'align-wide');
		add_theme_support( 'wp-block-styles');
		add_theme_support( 'editor-styles');
	
		// Remove the core block patterns.
		remove_theme_support( 'core-block-patterns');

		global $content_width;

		if ( ! isset ($content_width)) {
			$content_width = 1240;
		}
	}
}
