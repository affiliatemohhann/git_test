<?php
/**
 * Register Post Types
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;
use WORTHIO_THEME\Inc\Traits\Singleton;

class Register_Post_Types {
	use Singleton;

	/**
	 * Default block template for product editors.
	 *
	 * @return array<int, array<int|string|array<string, mixed>>>
	 */
	private function get_product_block_template() {
		return [
			[ 'worthio/product-hero-block' ],
			[ 'worthio/product-review' ],
			[
				'core/heading',
				[
					'level'   => 2,
					'content' => __( 'Full Review', 'worthio' ),
				],
			],
			[
				'core/paragraph',
				[
					'placeholder' => __( 'Write the full product review here...', 'worthio' ),
				],
			],
		];
	}

	protected function __construct() {
		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {
		/** Actions.*/
		add_action( 'init', [ $this, 'worthio_register_product_cpt' ], 0 );
		//add_action( 'init', [ $this, 'worthio_register_legacy_product_cpt' ], 1 );
	}

	// Register Custom Post Type product
	public function worthio_register_product_cpt() {
		$labels = [
			'name'                  => _x( 'All Products', 'Post Type General Name', 'worthio' ),
			'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'worthio' ),
			'menu_name'             => _x( 'Products', 'Admin Menu text', 'worthio' ),
			'name_admin_bar'        => _x( 'Product', 'Add New on Toolbar', 'worthio' ),
			'archives'              => __( 'Product Archives', 'worthio' ),
			'attributes'            => __( 'Product Attributes', 'worthio' ),
			'parent_item_colon'     => __( 'Parent Product:', 'worthio' ),
			'all_items'             => __( 'All Products', 'worthio' ),
			'add_new_item'          => __( 'Add New Product', 'worthio' ),
			'add_new'               => __( 'Add New', 'worthio' ),
			'new_item'              => __( 'New Product', 'worthio' ),
			'edit_item'             => __( 'Edit Product', 'worthio' ),
			'update_item'           => __( 'Update Product', 'worthio' ),
			'view_item'             => __( 'View Product', 'worthio' ),
			'view_items'            => __( 'View Products', 'worthio' ),
			'search_items'          => __( 'Search Product', 'worthio' ),
			'not_found'             => __( 'Not found', 'worthio' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'worthio' ),
			'featured_image'        => __( 'Featured Image', 'worthio' ),
			'set_featured_image'    => __( 'Set featured image', 'worthio' ),
			'remove_featured_image' => __( 'Remove featured image', 'worthio' ),
			'use_featured_image'    => __( 'Use as featured image', 'worthio' ),
			'insert_into_item'      => __( 'Insert into products', 'worthio' ),
			'uploaded_to_this_item' => __( 'Uploaded to this product', 'worthio' ),
			'items_list'            => __( 'Product list', 'worthio' ),
			'items_list_navigation' => __( 'Products list navigation', 'worthio' ),
			'filter_items_list'     => __( 'Filter Products list', 'worthio' ),
		];
		$args   = [
			'label'               => __( 'Product', 'worthio' ),
			'description'         => __( 'The products', 'worthio' ),
			'labels'              => $labels,
			'menu_icon'           => 'dashicons-grid-view',
			'supports'            => [
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'revisions',
				'author',
				'comments',
				'trackbacks',
				'page-attributes',
				'custom-fields',
			],
			'taxonomies'          => [],
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => 'products',
			'rewrite'             => [
				'slug'       => 'products',
				'with_front' => false,
			],
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'show_in_rest'        => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'template'            => $this->get_product_block_template(),
			'template_lock'       => false,
		];
		register_post_type( 'products', $args );
	}
	
}
