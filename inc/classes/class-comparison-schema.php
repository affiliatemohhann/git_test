<?php 

// Comparison Schema Class

namespace WORTHIO_THEME\Inc;

class Comparison_Schema {
	public static function generate( $product_ids = [], $title = '', $page_id = 0 ) {
		if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
			return [];
		}

		$list_items = [];
		$position   = 1;
		$page_url   = $page_id ? get_permalink( $page_id ) : get_permalink();

		foreach ( $product_ids as $candidate ) {
			$product_id = Schema_Utils::post_candidate_to_id( $candidate );

			if ( $product_id <= 0 ) {
				continue;
			}

			$list_items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'item'     => [
					'@id'   => get_permalink( $product_id ) . '#product',
					'name'  => get_the_title( $product_id ),
					'url'   => get_permalink( $product_id ),
					'image' => get_the_post_thumbnail_url( $product_id, 'full' ),
				],
			];
		}

		if ( empty( $list_items ) ) {
			return [];
		}

		return [
			'@type' => 'CollectionPage',
			'@id'   => $page_url . '#comparison',
			'url'   => $page_url,
			'name'  => $title ? $title : get_the_title( $page_id ),
			'isPartOf' => [
				'@id' => home_url( '/' ) . '#website',
			],
			'mainEntity' => [
				'@type' => 'ItemList',
				'@id'   => $page_url . '#comparison-itemlist',
				'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
				'numberOfItems' => count( $list_items ),
				'itemListElement' => $list_items,
			],
		];
	}
}

?>
