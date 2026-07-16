<?php 

// Product Schema Class

namespace WORTHIO_THEME\Inc;

class Product_Schema {
	public static function generate( $post_id ) {
		$post_id = (int) $post_id;

		if ( $post_id <= 0 ) {
			return [];
		}

		$permalink = get_permalink( $post_id );
		$price     = Schema_Utils::resolve_offer_price( $post_id );
		$currency  = Schema_Utils::resolve_currency( $post_id, 'INR' );
		$score     = Schema_Utils::normalize_score( Schema_Utils::get_post_value( 'editorial_score', $post_id ) );
		$image     = Schema_Utils::resolve_product_images( $post_id );
		$brand     = Schema_Utils::get_post_value( 'brand_name', $post_id );
		$sku       = Schema_Utils::get_post_value( 'sku', $post_id );
		$mpn       = Schema_Utils::get_post_value( 'mpn', $post_id );
		$offer_url = Schema_Utils::resolve_cta_url( $post_id );
		$key_specs = Schema_Utils::resolve_key_specs( $post_id );

		$additional_properties = [];
		foreach ( $key_specs as $spec ) {
			$additional_properties[] = [
				'@type' => 'PropertyValue',
				'name'  => $spec,
			];
		}

		$schema = [
			'@type'       => 'Product',
			'@id'         => $permalink . '#product',
			'url'         => $permalink,
			'name'        => get_the_title( $post_id ),
			'description' => get_the_excerpt( $post_id ),
			'image'       => $image,
			'brand'       => $brand ? [
				'@type' => 'Brand',
				'name'  => $brand,
			] : null,
			'sku' => $sku,
			'mpn' => $mpn,
			'offers' => [
				'@type'         => 'Offer',
				'url'           => $offer_url ? $offer_url : $permalink,
				'priceCurrency' => $currency,
				'price'         => $price,
				'availability'  => 'https://schema.org/InStock',
				'itemCondition' => 'https://schema.org/NewCondition',
			],
			'additionalProperty' => $additional_properties,
			'aggregateRating' => $score ? [
				'@type'       => 'AggregateRating',
				'ratingValue' => $score,
				'bestRating'  => 10,
				'worstRating' => 0,
				'reviewCount' => 1,
			] : null,
			'mainEntityOfPage' => [
				'@id' => $permalink . '#webpage',
			],
		];

		return Schema_Utils::remove_empty( $schema );
	}

}

?>
