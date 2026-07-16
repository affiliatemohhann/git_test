<?php
/**
 * Breadcrumb schema generator.
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;

class Breadcrumb_Schema {
	public static function generate() {
		$items    = [];
		$position = 1;

		$items[] = self::make_item( $position++, home_url( '/' ), 'Home' );

		if ( is_singular() ) {
			$post_id = get_queried_object_id();
			$post    = get_post( $post_id );

			if ( ! $post ) {
				return [];
			}

			if ( self::is_product_post_type( $post->post_type ) ) {
				$primary_term = self::get_primary_product_term( $post_id );
				if ( $primary_term ) {
					$items = array_merge( $items, self::term_chain_items( $primary_term, $position ) );
					$position = count( $items ) + 1;
				}
			} elseif ( 'page' !== $post->post_type ) {
				$pt = get_post_type_object( $post->post_type );
				if ( $pt && ! empty( $pt->has_archive ) ) {
					$items[] = self::make_item( $position++, get_post_type_archive_link( $post->post_type ), $pt->labels->name );
				}
			}

			$items[] = self::make_item( $position++, get_permalink( $post_id ), get_the_title( $post_id ) );
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$term = get_queried_object();
			if ( $term instanceof \WP_Term ) {
				$items = array_merge( $items, self::term_chain_items( $term, $position ) );
			}
		} elseif ( is_post_type_archive() ) {
			$obj = get_queried_object();
			if ( $obj && ! empty( $obj->name ) ) {
				$items[] = self::make_item( $position++, get_post_type_archive_link( $obj->name ), post_type_archive_title( '', false ) );
			}
		}

		if ( count( $items ) < 2 ) {
			return [];
		}

		return [
			'@type' => 'BreadcrumbList',
			'@id'   => Schema_Utils::current_url() . '#breadcrumb',
			'itemListElement' => array_values( $items ),
		];
	}

	private static function term_chain_items( $term, $start_position ) {
		$items     = [];
		$position  = (int) $start_position;
		$ancestors = array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) );

		foreach ( $ancestors as $ancestor_id ) {
			$ancestor = get_term( $ancestor_id, $term->taxonomy );
			if ( $ancestor instanceof \WP_Term ) {
				$ancestor_link = get_term_link( $ancestor );
				if ( is_wp_error( $ancestor_link ) ) {
					continue;
				}
				$items[] = self::make_item( $position++, $ancestor_link, $ancestor->name );
			}
		}

		$term_link = get_term_link( $term );
		if ( ! is_wp_error( $term_link ) ) {
			$items[] = self::make_item( $position++, $term_link, $term->name );
		}

		return $items;
	}

	private static function make_item( $position, $url, $name ) {
		return [
			'@type'    => 'ListItem',
			'position' => (int) $position,
			'item'     => [
				'@id'  => $url,
				'name' => wp_strip_all_tags( (string) $name ),
			],
		];
	}

	private static function get_primary_product_term( $post_id ) {
		$terms = get_the_terms( $post_id, 'product_category' );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return null;
		}

		return reset( $terms );
	}

	private static function is_product_post_type( $post_type ) {
		return Schema_Utils::is_product_post_type( $post_type );
	}
}
