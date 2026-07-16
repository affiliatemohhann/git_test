<?php 

// Register Menus

namespace WORTHIO_THEME\Inc;
use WORTHIO_THEME\Inc\Traits\Singleton;

class Svg_Loader {
    use Singleton;

	protected function __construct() {	
		// Load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/** * Actions.*/		
		add_action( 'wp_footer', [ $this, 'worthio_load_svg'] );
		add_action( 'wp_footer', [ $this, 'worthio_icon'] );
	}

	public function worthio_load_svg() {
          $svg_path = plugin_dir_path(__FILE__) . '/src/icons/svg/sprite.svg'; 
          if (file_exists($svg_path)) {
              echo file_get_contents($svg_path);
          }
      }

      public function worthio_icon($name, $class = '') {
            return '<svg class="worhtio-icon ' . esc_attr($class) . '">
                        <use href="#icon-' . esc_attr($name) . '"></use>
                    </svg>';
        }


}
?>