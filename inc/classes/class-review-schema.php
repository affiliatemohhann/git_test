<?php 

// Review_Schema Schema Class

namespace WORTHIO_THEME\Inc;

class Review_Schema {   
	public static function generate( $post_id ) {
		$post_id = (int) $post_id;

		if ( $post_id <= 0 ) {
			return [];
		}

		$permalink   = get_permalink( $post_id );
		$score       = Schema_Utils::normalize_score( Schema_Utils::acf_get( 'editorial_score', $post_id ) );
		$author_id   = (int) get_post_field( 'post_author', $post_id );
		$author_name = $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';
		$author_url  = $author_id ? get_author_posts_url( $author_id ) : '';
		$org_id      = home_url( '/' ) . '#organization';

		$schema = [
			'@type' => 'Review',
			'@id'   => $permalink . '#review',
			'url'   => $permalink,
			'itemReviewed' => [
				'@id' => $permalink . '#product',
			],
			'author' => $author_name ? [
				'@type' => 'Person',
				'@id'   => $author_url ? $author_url . '#author' : null,
				'name'  => $author_name,
				'url'   => $author_url ? $author_url : null,
				'worksFor' => [
					'@id' => $org_id,
				],
			] : null,
			'publisher' => [
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'@id'   => $org_id,
			],
			'datePublished' => get_the_date( 'c', $post_id ),
			'dateModified'  => get_the_modified_date( 'c', $post_id ),
			'inLanguage'    => get_bloginfo( 'language' ),
			'mainEntityOfPage' => [
				'@id' => $permalink . '#webpage',
			],
			'reviewRating' => $score ? [
				'@type'       => 'Rating',
				'ratingValue' => $score,
				'bestRating'  => 10,
				'worstRating' => 0,
			] : null,
			'positiveNotes' => self::notes_to_item_list( Schema_Utils::acf_get( 'pros', $post_id ), $permalink . '#positive-notes' ),
			'negativeNotes' => self::notes_to_item_list( Schema_Utils::acf_get( 'cons', $post_id ), $permalink . '#negative-notes' ),
			'reviewBody'    => Schema_Utils::to_plain_text( Schema_Utils::acf_get( 'final_verdict', $post_id ) ),
		];

		return Schema_Utils::remove_empty( $schema );
	}

	private static function notes_to_item_list( $raw_notes, $id ) {
		$notes = $raw_notes;
		$items = [];

		if ( is_string( $notes ) ) {
			$notes = preg_split( '/\r\n|\r|\n/', $notes );
		}

		if ( ! is_array( $notes ) ) {
			return null;
		}

		$position = 1;

		foreach ( $notes as $note ) {
			if ( is_array( $note ) && isset( $note['text'] ) ) {
				$note = $note['text'];
			}

			$label = is_string( $note ) ? trim( wp_strip_all_tags( $note ) ) : '';
			if ( '' === $label ) {
				continue;
			}

			$items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => $label,
			];
		}

		if ( empty( $items ) ) {
			return null;
		}

		return [
			'@type' => 'ItemList',
			'@id'   => $id,
			'itemListElement' => $items,
		];
	}

}

?>
