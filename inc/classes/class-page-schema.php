<?php
/**
 * Generic page-level schema.
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;

class Page_Schema {
	public static function generate( $main_entity_id = null ) {
		$post_id = get_queried_object_id();
		$url     = Schema_Utils::current_url();
		$title   = is_singular() && $post_id ? get_the_title( $post_id ) : wp_get_document_title();

		$schema = [
			'@type' => 'WebPage',
			'@id'   => $url . '#webpage',
			'url'   => $url,
			'name'  => $title,
			'isPartOf' => [
				'@id' => home_url( '/' ) . '#website',
			],
			'inLanguage' => get_bloginfo( 'language' ),
			'breadcrumb' => [
				'@id' => $url . '#breadcrumb',
			],
			'mainEntity' => $main_entity_id ? [
				'@id' => $main_entity_id,
			] : null,
		];

		if ( is_singular() && $post_id ) {
			$schema['datePublished'] = get_the_date( 'c', $post_id );
			$schema['dateModified']  = get_the_modified_date( 'c', $post_id );
		}

		return Schema_Utils::remove_empty( $schema );
	}
}
