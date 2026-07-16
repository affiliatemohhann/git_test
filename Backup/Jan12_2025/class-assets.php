<?php 

// Enquee Assets @package WORTHIO

namespace WORTHIO_THEME\Inc;
use WORTHIO_THEME\Inc\Traits\Singleton;

class Assets {
    use Singleton;
	protected function __construct() {	
		// Load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/** * Actions.*/
		add_action( 'wp_enqueue_scripts', [$this, 'register_styles'] );
		add_action( 'wp_enqueue_scripts', [$this, 'register_scripts'] );	
			
	}
	 //  Enquee Styles
	public function register_styles() {		
		
		
		//wp_register_style( 'Stylesheet',  get_stylesheet_uri(), [], filemtime( WORTHIO_DIR_PATH . '/style.css'), 'all');
		//wp_register_style( 'index-css', WORTHIO_BUILD_URI . '/index.css', [],   filemtime( WORTHIO_BUILD_PATH . '/index.css'), 'all');
		//wp_register_style( 'index-css', WORTHIO_BUILD_CSS_URI . '/index.css', [],   filemtime( WORTHIO_BUILD_CSS_DIR_PATH . '/index.css'), 'all');
		//wp_register_style( 'main-css', WORTHIO_BUILD_CSS_URI . '/main.css', [],   filemtime( WORTHIO_BUILD_CSS_DIR_PATH . '/main.css'), 'all');
		//wp_register_style( 'bootstrap-css',  WORTHIO_BUILD_URI . "/bootstrap.css", filemtime( WORTHIO_BUILD_PATH . '/bootstrap.css'), 'all' );		
		//wp_register_style( 'main-css', WORTHIO_BUILD_CSS_URI . '/main.css', [],   filemtime( WORTHIO_BUILD_CSS_DIR_PATH . '/main.css'), 'all');
		//wp_register_style( 'slick-css', WORTHIO_DIR_URI . '/src/library/css/slick.css', [], false, 'all');
		//wp_register_style( 'slick-theme-css', WORTHIO_DIR_URI . '/src/library/css/slick-theme.css', [], false, 'all');
		//wp_register_style( 'search-css', WORTHIO_BUILD_CSS_URI . '/search.css', [], filemtime( WORTHIO_BUILD_CSS_DIR_PATH . '/search.css'), 'all');
				
		//wp_enqueue_style ('index-css');	
		//wp_enqueue_style ('bootstrap-css');
		//wp_enqueue_style ('Stylesheet');
		//wp_enqueue_style ('main-css');
		//wp_enqueue_style ('slick-css');
		//wp_enqueue_style ('slick-theme-css');

		 $asset_file = include( WORTHIO_BUILD_PATH . '/bootstrap.asset.php' );

		wp_enqueue_style(
			'bootstrap-css',
			WORTHIO_BUILD_PATH . '/bootstrap.css',
			$asset_file['dependencies'], // Automatically handles dependencies
			$asset_file['version']        // Automatically handles versioning/cache busting
		);
		wp_enqueue_style ('bootstrap-css');
		if( is_page('search') ) {
			wp_enqueue_style ('search-css');
		}
	}

	// Enquee Scripts
	public function register_scripts() {
		// Scripts
		wp_register_script( 'index-js', WORTHIO_BUILD_URI . '/index.js', ['jquery'], filemtime( WORTHIO_BUILD_PATH . '/index.js'), true);	
		//wp_register_script( 'slick-js', WORTHIO_DIR_URI . '/src/library/js/slick.min.js', ['jquery'], true);
		
		//wp_register_script( 'main-js', WORTHIO_BUILD_URI . '/main.js', ['jquery','slick-js'],  WORTHIO_BUILD_URI . '/main.js', true);	
		//wp_register_script( 'single-js', WORTHIO_BUILD_JS_URI . '/single.js', ['jquery','slick-js'], filemtime( WORTHIO_BUILD_JS_DIR_PATH . '/single.js'), true);		
		//wp_register_script( 'bootstrap-js', WORTHIO_DIR_URI . '/src/js/bootstrap.bundle.min.js', ['jquery'], false , true);
		//wp_register_script( 'search-js', WORTHIO_BUILD_JS_URI . '/search.js', ['main-js'], filemtime( WORTHIO_BUILD_JS_DIR_PATH . '/search.js'), true);	
		

		//wp_enqueue_script ('main-js');
		wp_enqueue_script ('bootstrap-js');
		wp_enqueue_script ('index-js');		
		wp_enqueue_script ('slick-js');
		
		
		// if single post page then load single-js
		 if ( is_single() ) {
		 	wp_enqueue_script ('single-js');
		 }

		 // If search page.
		if( is_page('search') ) {
			$filters_data = get_filters_data();
			wp_enqueue_script( 'search-js' );
			wp_localize_script( 'search-js', 'search_settings',				[
					'rest_api_url' => home_url( '/wp-json/wp/v2/posts' ),
					'root_url'     => home_url('search'),
					'filter_ids'   => get_filter_ids( $filters_data ),
				]
			);
		}		

			wp_localize_script( 'main-js', 'siteConfig', [
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'loadmore_post_nonce' ),
		] );	
	}



	
}

?>