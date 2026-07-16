<?php
/**
 *
 * @package Worthio
 */

// THEME - WORTHIO FOLDER PATH AND URI
if( ! defined('WORTHIO_DIR_PATH') ) {
    define('WORTHIO_DIR_PATH', untrailingslashit(get_template_directory()) );   
}

if( ! defined('WORTHIO_DIR_URI') ) {
    define('WORTHIO_DIR_URI', untrailingslashit(get_template_directory_uri()) );   
}

// BUILD FOLDER URI and PATH
if( ! defined('WORTHIO_BUILD_URI') ) {
    define('WORTHIO_BUILD_URI', untrailingslashit(get_template_directory_uri()) .'/build' );   
}

if( ! defined('WORTHIO_BUILD_PATH') ) {
    define('WORTHIO_BUILD_PATH', untrailingslashit(get_template_directory()) .'/build' );   
}


// JS FOLDER URI and PATH
if( ! defined('WORTHIO_BUILD_JS_URI') ) {
    define('WORTHIO_BUILD_JS_URI', untrailingslashit(get_template_directory_uri()) .'/build/js' );   
}
if( ! defined('WORTHIO_BUILD_JS_DIR_PATH') ) {
    define('WORTHIO_BUILD_JS_DIR_PATH', untrailingslashit(get_template_directory()) . '/build/js');   
}

// CSS FOLDER URI and PATH

if( ! defined('WORTHIO_BUILD_CSS_URI') ) {
    define('WORTHIO_BUILD_CSS_URI', untrailingslashit(get_template_directory_uri()) .'/build/css' );   
}
if( ! defined('WORTHIO_BUILD_CSS_DIR_PATH') ) {
    define('WORTHIO_BUILD_CSS_DIR_PATH', untrailingslashit(get_template_directory()) . '/build/css');   
}

// IMAGES FOLDER URI and PATH
if( ! defined('WORTHIO_BUILD_IMAGES_URI') ) {
    define('WORTHIO_BUILD_IMAGES_URI', untrailingslashit(get_template_directory_uri()) .'/build/images' );   
}

if( ! defined('WORTHIO_BUILD_IMAGES_DIR_PATH') ) {
    define('WORTHIO_BUILD_IMAGES_DIR_PATH', untrailingslashit(get_template_directory_uri()) .'/build/images' );   
}
// LIBRARY URI and PATH
if( ! defined('WORTHIO_BUILD_LIB_URI') ) {
    define('WORTHIO_BUILD_LIB_URI', untrailingslashit(get_template_directory_uri()) .'/build/library' );   
}
if( ! defined('WORTHIO_BUILD_LIB_DIR_PATH') ) {
    define('WORTHIO_BUILD_CSS_LIB_PATH', untrailingslashit(get_template_directory()) . '/build/library');   
}

// Auto Load Files
if( ! defined('WORTHIO_ARCHIVE_POST_PER_PAGE') ) {
    define('WORTHIO_ARCHIVE_POST_PER_PAGE', 6 );   
}
// AUTO LOADER AND NAME SPACE PATHS
require_once WORTHIO_DIR_PATH . '/inc/helpers/autoloader.php';
require_once WORTHIO_DIR_PATH . '/inc/helpers/template-tags.php';
require_once WORTHIO_DIR_PATH . '/inc/helpers/svg-load.php';

//  Initialize THE THEME
function worthio_get_theme_instance() {
 	\WORTHIO_THEME\Inc\WORTHIO_THEME::get_instance();
}
// Get instance of the theme.
worthio_get_theme_instance();

// Enqueue theme scripts
function worthio_scripts() {   
}
add_action( 'wp_enqueue_scripts', 'worthio_scripts' );

// BLOCKS REGISTRATION Latest WP Methods

function worthio_block_main_block_init() {

	  $build_dir = WORTHIO_BUILD_PATH;
    $manifest  = $build_dir . '/blocks-manifest.php';

    if ( ! file_exists( $manifest ) ) {
       return;
    }

	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( $build_dir . '/blocks', $manifest );
	}
}
add_action( 'init', 'worthio_block_main_block_init' );

// Schema Registration

//Shortcodes
require_once WORTHIO_DIR_PATH . '/inc/shortcodes/shortcodelinks.php';






