<?php
/**
 * Site-level schema nodes.
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;

class Site_Schema {
	public static function website() {
		$url = home_url( '/' );

		return Schema_Utils::remove_empty(
			[
				'@type' => 'WebSite',
				'@id'   => $url . '#website',
				'url'   => $url,
				'name'  => get_bloginfo( 'name' ),
				'inLanguage' => get_bloginfo( 'language' ),
				'potentialAction' => [
					'@type' => 'SearchAction',
					'target' => [
						'@type' => 'EntryPoint',
						'urlTemplate' => home_url( '/?s={search_term_string}' ),
					],
					'query-input' => 'required name=search_term_string',
				],
			]
		);
	}

	public static function organization() {
		$url      = home_url( '/' );
		$logo_id  = get_theme_mod( 'custom_logo' );
		$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : null;

		return Schema_Utils::remove_empty(
			[
				'@type' => 'Organization',
				'@id'   => $url . '#organization',
				'name'  => get_bloginfo( 'name' ),
				'url'   => $url,
				'logo'  => $logo_url ? [
					'@type' => 'ImageObject',
					'url'   => $logo_url,
				] : null,
			]
		);
	}
}

