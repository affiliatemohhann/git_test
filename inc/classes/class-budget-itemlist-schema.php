<?php
/**
 * Dynamic ItemList schema for budget listing pages.
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;

class Budget_ItemList_Schema {
	public static function generate_for_current_page() {
		$context = self::detect_budget_context();

		if ( empty( $context['budget'] ) ) {
			return [];
		}

		$cache_key = Schema_Utils::build_cache_key(
			'budget_itemlist_schema',
			[
				'budget' => $context['budget'],
				'title'  => $context['title'],
				'term'   => ! empty( $context['term'] ) && $context['term'] instanceof \WP_Term ? $context['term']->term_id : 0,
			]
		);

		return Schema_Utils::remember(
			$cache_key,
			static function () use ( $context ) {
				$candidates = self::get_candidate_products( $context );

				if ( empty( $candidates ) ) {
					return [];
				}

				usort(
					$candidates,
					static function ( $a, $b ) {
						return $a['price'] <=> $b['price'];
					}
				);

				$list_items = [];
				$position   = 1;

				foreach ( $candidates as $candidate ) {
					$list_items[] = [
						'@type'    => 'ListItem',
						'position' => $position++,
						'item'     => [
							'@type'  => 'Product',
							'@id'    => get_permalink( $candidate['id'] ) . '#product',
							'name'   => get_the_title( $candidate['id'] ),
							'url'    => get_permalink( $candidate['id'] ),
							'offers' => [
								'@type'         => 'Offer',
								'price'         => $candidate['price'],
								'priceCurrency' => Schema_Utils::resolve_currency( $candidate['id'], 'INR' ),
							],
						],
					];
				}

				$current_url = Schema_Utils::current_url();
				$name        = self::build_list_name( $context );

				return Schema_Utils::remove_empty(
					[
						'@type'           => 'ItemList',
						'@id'             => $current_url . '#budget-itemlist',
						'name'            => $name,
						'url'             => $current_url,
						'itemListOrder'   => 'https://schema.org/ItemListOrderAscending',
						'numberOfItems'   => count( $list_items ),
						'itemListElement' => $list_items,
					],
				);
			}
		);
	}

	private static function detect_budget_context() {
		$title = '';
		$term  = null;

		if ( is_tax() || is_category() || is_tag() ) {
			$term = get_queried_object();
			if ( $term instanceof \WP_Term ) {
				$title = $term->name;
			}
		} elseif ( is_page() ) {
			$post_id = get_queried_object_id();
			$title   = $post_id ? get_the_title( $post_id ) : '';
		} else {
			$title = wp_get_document_title();
		}

		$budget = self::extract_budget_value( $title );

		return [
			'budget' => $budget,
			'title'  => $title,
			'term'   => $term,
		];
	}

	private static function extract_budget_value( $text ) {
		if ( ! is_string( $text ) || '' === trim( $text ) ) {
			return null;
		}

		if ( preg_match( '/(?:under|below|upto|up to)\s*([0-9][0-9,]*)/i', $text, $matches ) ) {
			$value = (int) str_replace( ',', '', $matches[1] );
			return $value > 0 ? $value : null;
		}

		return null;
	}

	private static function get_candidate_products( $context ) {
		global $wp_query;

		$budget      = (float) $context['budget'];
		$product_ids = [];
		$items       = [];

		if ( isset( $wp_query->posts ) && is_array( $wp_query->posts ) ) {
			foreach ( $wp_query->posts as $post ) {
				$post_id = is_object( $post ) ? (int) $post->ID : (int) $post;
				if ( $post_id <= 0 || ! Schema_Utils::is_product_post_type( get_post_type( $post_id ) ) ) {
					continue;
				}
				$product_ids[] = $post_id;
			}
		}

		if ( empty( $product_ids ) ) {
			$args = [
				'post_type'      => Schema_Utils::product_post_types(),
				'posts_per_page' => 150,
				'meta_query'     => [
					'relation' => 'OR',
					[
						'key'     => 'price',
						'value'   => $budget,
						'compare' => '<=',
						'type'    => 'NUMERIC',
					],
					[
						'key'     => '_price',
						'value'   => $budget,
						'compare' => '<=',
						'type'    => 'NUMERIC',
					],
					[
						'key'     => 'regular_price',
						'value'   => $budget,
						'compare' => '<=',
						'type'    => 'NUMERIC',
					],
					[
						'key'     => '_regular_price',
						'value'   => $budget,
						'compare' => '<=',
						'type'    => 'NUMERIC',
					],
				],
			];

			if ( ! empty( $context['term'] ) && $context['term'] instanceof \WP_Term ) {
				$args['tax_query'] = [
					[
						'taxonomy' => $context['term']->taxonomy,
						'field'    => 'term_id',
						'terms'    => [ $context['term']->term_id ],
					],
				];
			}

			$product_ids = Schema_Utils::get_cached_product_ids( $args, 'budget_candidate_products' );
		}

		foreach ( $product_ids as $post_id ) {
			$post_id = (int) $post_id;
			if ( $post_id <= 0 ) {
				continue;
			}

			$price = Schema_Utils::resolve_offer_price( $post_id );
			if ( null === $price || $price > $budget ) {
				continue;
			}

			$items[] = [
				'id'    => $post_id,
				'price' => $price,
			];
		}

		return $items;
	}

	private static function build_list_name( $context ) {
		$title  = isset( $context['title'] ) ? trim( (string) $context['title'] ) : '';
		$budget = isset( $context['budget'] ) ? (int) $context['budget'] : 0;

		if ( '' !== $title ) {
			return $title;
		}

		return 'Products Under ' . $budget;
	}
}
