<?php
/**
 * Register Custom Taxonomies
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;
use WORTHIO_THEME\Inc\Traits\Singleton;

class Register_Taxonomies {
	use Singleton;

	/**
	 * Product post types supported by product taxonomies.
	 *
	 * @return array<int, string>
	 */
	private function get_supported_product_object_types() {
		return [ 'product', 'products' ];
	}

	/**
	 * Register a taxonomy against the product object types.
	 *
	 * @param string $taxonomy Taxonomy key.
	 * @param array  $args     Taxonomy arguments.
	 * @return void
	 */
	private function register_product_taxonomy( $taxonomy, array $args ) {
		register_taxonomy( $taxonomy, $this->get_supported_product_object_types(), $args );
	}

	protected function __construct() {

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/**		 * Actions.		 */
		add_action( 'init', [ $this, 'worthio_register_niche_taxonomy' ] );
		add_action( 'init', [ $this, 'worthio_register_product_type_taxonomy' ] );
		add_action( 'init', [ $this, 'worthio_register_brand_taxonomy' ] );
		add_action( 'init', [ $this, 'worthio_register_price_range' ] );
		add_action( 'init', [ $this, 'worthio_register_product_features' ] );
	}

	// Register Niche Taxonimy
	public function worthio_register_niche_taxonomy() {

		$labels = [
			'name'              => _x( 'Niches', 'taxonomy general name', 'worthio' ),
			'singular_name'     => _x( 'Niche', 'taxonomy singular name', 'worthio' ),
			'search_items'      => __( 'Search Niche', 'worthio' ),
			'all_items'         => __( 'All Niche', 'worthio' ),
			'parent_item'       => __( 'Parent Niche', 'worthio' ),
			'parent_item_colon' => __( 'Parent Niche:', 'worthio' ),
			'edit_item'         => __( 'Edit Niche', 'worthio' ),
			'update_item'       => __( 'Update Niche', 'worthio' ),
			'add_new_item'      => __( 'Add New Niche', 'worthio' ),
			'new_item_name'     => __( 'New Niche Name', 'worthio' ),
			'menu_name'         => __( 'Niche', 'worthio' ),
		];
		$args   = [
			'labels'             => $labels,
			'description'        => __( 'Niche', 'worthio' ),
			'hierarchical'       => true,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		];

		$this->register_product_taxonomy( 'niche', $args );
	}

	 // Register Product Category
	public function worthio_register_product_type_taxonomy() {
		$labels = [
			'name'              => _x( 'Product Category', 'taxonomy general name', 'worthio' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'worthio' ),
			'search_items'      => __( 'Category', 'worthio' ),
			'all_items'         => __( 'Category', 'worthio' ),
			'parent_item'       => __( 'Parent Category', 'worthio' ),
			'parent_item_colon' => __( 'Parent Category:', 'worthio' ),
			'edit_item'         => __( 'Edit Category', 'worthio' ),
			'update_item'       => __( 'Update Category', 'worthio' ),
			'add_new_item'      => __( 'Add New Category', 'worthio' ),
			'new_item_name'     => __( 'New Year Category', 'worthio' ),
			'menu_name'         => __( 'Product Category', 'worthio' ),
		];
		$args   = [
			'labels'             => $labels,
			'description'        => true,
			'hierarchical'       => true,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'rewrite'            => [
				'slug'       => 'product-category',
				'with_front' => false,
			],
			'show_in_rest'       => true,
		];
		$this->register_product_taxonomy( 'product_category', $args );
	}

	// Register Product Brand
	public function worthio_register_brand_taxonomy() {
		$labels = [
			'name'              => _x( 'Product Brand', 'Brand name', 'worthio' ),
			'singular_name'     => _x( 'Brand', 'Brand singular name', 'worthio' ),
			'search_items'      => __( 'Brand', 'worthio' ),
			'all_items'         => __( 'Brand', 'worthio' ),
			'parent_item'       => __( 'Parent Brand', 'worthio' ),
			'parent_item_colon' => __( 'Parent Brand:', 'worthio' ),
			'edit_item'         => __( 'Edit Brand', 'worthio' ),
			'update_item'       => __( 'Update Brand', 'worthio' ),
			'add_new_item'      => __( 'Add New Brand', 'worthio' ),
			'new_item_name'     => __( 'New Year Brand', 'worthio' ),
			'menu_name'         => __( 'Product Brand', 'worthio' ),
		];
		$args   = [
			'labels'             => $labels,
			'description'        => true,
			'hierarchical'       => false,
			'public'             => true,
			'rewrite'            => [
				'slug'       => 'brand',
				'with_front' => false,
			],
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
		];
		$this->register_product_taxonomy( 'product_brand', $args );
	}	
	
	// Register Product Range
	public function worthio_register_price_range() {
		 $labels = [
        'name'              => 'Price range',
        'singular_name'     => 'Price range',
        'search_items'      => 'Search',
        'all_items'         => 'All rangees',
        'edit_item'         => 'Edit range',
        'update_item'       => 'Update range',
        'add_new_item'      => 'Add New range',
        'new_item_name'     => 'New range Name',
        'menu_name'         => 'Price Range',
    	];
		
		$args = [
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => true, // behaves like tags
        'show_admin_column' => true,
        'show_in_rest'      => true,  // Important for Gutenberg & React
        'rewrite'           => [
            'slug' => 'range',
            'with_front' => false,
        	],
    	];
		$this->register_product_taxonomy( 'price_range', $args );
	}

	// Register Product Features
	public function worthio_register_product_features() {
		 $labels = [
        'name'              => 'Product Features',
        'singular_name'     => 'Product Feature',
        'search_items'      => 'Search Features',
        'all_items'         => 'All Features',
        'edit_item'         => 'Edit Feature',
        'update_item'       => 'Update Feature',
        'add_new_item'      => 'Add New Feature',
        'new_item_name'     => 'New Feature Name',
        'menu_name'         => 'Product Features',
    	];
		
		$args = [
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => false, // behaves like tags
        'show_admin_column' => true,
        'show_in_rest'      => true,  // Important for Gutenberg & React
        'rewrite'           => [
            'slug' => 'features',
            'with_front' => false,
        	],
    	];
		$this->register_product_taxonomy( 'product_features', $args );
	}
}

