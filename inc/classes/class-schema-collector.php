<?php
/**
 * Blocks
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;
    
use WORTHIO_THEME\Inc\Traits\Singleton;

class Worthio_Schema_Collector {
	use Singleton;

	protected function __construct() {

		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/** Actions. */
		add_action( 'init', [ $this, 'worthio_schema_add' ] );
	}

	/**
	 * Add a block category	
	 * @param array $categories Block categories.	
	 * @return array
	 */

        // echo '<pre>';
		// print_r( $categories );
		// wp_die();	

    public function worthio_schema_add($key, $data) {
    global $worthio_schema;

        if (!isset($worthio_schema)) {
            $worthio_schema = [];
        }

        $worthio_schema[$key] = $data;
    }
}
