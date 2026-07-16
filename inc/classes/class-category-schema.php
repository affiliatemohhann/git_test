<?php
/**
 * Product category archive schema.
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;

class Category_Schema {
	public static function generate( $term ) {
		if ( ! ( $term instanceof \WP_Term ) ) {
			return [];
		}

		$cache_key = Schema_Utils::build_cache_key(
			'category_schema',
			[
				'taxonomy' => $term->taxonomy,
				'term_id'  => $term->term_id,
			]
		);

		return Schema_Utils::remember(
			$cache_key,
			static function () use ( $term ) {
				$url = get_term_link( $term );
				if ( is_wp_error( $url ) ) {
					return [];
				}

				$post_ids = Schema_Utils::get_cached_product_ids(
					[
						'post_type'      => Schema_Utils::product_post_types(),
						'posts_per_page' => 25,
						'tax_query'      => [
							[
								'taxonomy' => $term->taxonomy,
								'field'    => 'term_id',
								'terms'    => [ $term->term_id ],
							],
						],
					],
					'category_schema_products'
				);

				$items    = [];
				$position = 1;

				foreach ( $post_ids as $post_id ) {
					$items[] = [
						'@type'    => 'ListItem',
						'position' => $position++,
						'item'     => [
							'@id'  => get_permalink( $post_id ) . '#product',
							'name' => get_the_title( $post_id ),
							'url'  => get_permalink( $post_id ),
						],
					];
				}

				$schema = [
					'@type'       => 'CollectionPage',
					'@id'         => $url . '#collection',
					'url'         => $url,
					'name'        => $term->name,
					'description' => term_description( $term->term_id, $term->taxonomy ),
					'isPartOf'    => [
						'@id' => home_url( '/' ) . '#website',
					],
					'mainEntity'  => [
						'@type'           => 'ItemList',
						'itemListOrder'   => 'https://schema.org/ItemListOrderAscending',
						'numberOfItems'   => count( $items ),
						'itemListElement' => $items,
					],
				];

				return Schema_Utils::remove_empty( $schema );
			}
		);
	}
}
