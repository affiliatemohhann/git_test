<?php
/**
 * Archive Settings
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;
use WORTHIO_THEME\Inc\Traits\Singleton;


class Archive_Settings {
	use Singleton;

	/**
	 * Product taxonomies that should include legacy product content.
	 *
	 * @return array<int, string>
	 */
	private function get_product_taxonomies() {
		return [
			'niche',
			'product_category',
			'product_brand',
			'price_range',
			'product_features',
		];
	}

	protected function __construct() {

		$this->setup_hooks();
	}

	protected function setup_hooks() {

		/**
		 * Filters.
		 */
		add_filter( 'pre_get_posts', [ $this, 'change_archive_posts_per_page' ] );
	}

	/**
	 * Change Posts Per Page for Archive.
	 *
	 * @param \WP_Query $query Query object.
	 * @return \WP_Query
	 *
	 */
	public function change_archive_posts_per_page( $query ) {
		if ( ! $query instanceof \WP_Query ) {
			return $query;
		}

		$current_post_type = $query->get( 'post_type' );

		if ( is_admin() ) {
			global $pagenow;

			$is_product_list_table = 'edit.php' === $pagenow
				&& $query->is_main_query()
				&& in_array( $current_post_type, Schema_Utils::product_post_types(), true );

			if ( $is_product_list_table ) {
				$query->set( 'post_status', [ 'publish', 'future', 'draft', 'pending', 'private', 'trash' ] );
			}

			return $query;
		}

		$is_product_query  = false;

		if ( empty( $current_post_type ) ) {
			$is_product_query = $query->is_post_type_archive( 'product' ) || $query->is_tax( $this->get_product_taxonomies() );
		} else {
			$is_product_query = (bool) array_intersect(
				(array) $current_post_type,
				Schema_Utils::product_post_types()
			);
		}

		if ( $is_product_query ) {
			$query->set( 'post_type', Schema_Utils::product_post_types() );
		}

		if ( $query->is_archive() && $query->is_main_query() ) {
			$query->set( 'posts_per_page', (string) WORTHIO_ARCHIVE_POST_PER_PAGE );
		} elseif ( ! empty( $query->query_vars['s'] ) ) {
			// For search result page only.
			// $query->set( 'posts_per_page', (string) WORTHIO_SEARCH_RESULTS_POST_PER_PAGE );
		}

		return $query;
	}

}
