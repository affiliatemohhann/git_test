<?php 
      function worthio_load_svg() {
          $svg_path = plugin_dir_path(__FILE__) . '../../src/icons/svg/sprite.svg'; 
          if (file_exists($svg_path)) {
              echo file_get_contents($svg_path);
          }
      }
	  	
      add_action('wp_footer', 'worthio_load_svg');

        function worthio_icon($name, $class = '') {
            return '<svg class="worhtio-icon ' . esc_attr($class) . '">
                        <use href="#icon-' . esc_attr($name) . '"></use>
                    </svg>';
        }

?>