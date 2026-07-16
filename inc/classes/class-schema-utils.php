<?php
/**
 * Shared schema helpers.
 *
 * @package Worthio
 */

namespace WORTHIO_THEME\Inc;

class Schema_Utils {
	const CACHE_GROUP = 'worthio_theme';
	const QUERY_CACHE_TTL = 300;

	/**
	 * Determine whether a value should be treated as populated.
	 *
	 * @param mixed $value Candidate value.
	 * @return bool
	 */
	private static function has_value( $value ) {
		return '' !== $value && null !== $value && [] !== $value;
	}

	/**
	 * Read a meta value from a post or term before falling back to ACF.
	 *
	 * @param callable $meta_reader Meta reader callback.
	 * @param string   $key         Meta key.
	 * @param mixed    $acf_context Optional ACF context.
	 * @return mixed
	 */
	private static function get_value_with_acf_fallback( callable $meta_reader, $key, $acf_context = null ) {
		$value = $meta_reader( $key );
		if ( self::has_value( $value ) ) {
			return $value;
		}

		if ( null === $acf_context ) {
			return null;
		}

		$value = self::acf_get( $key, $acf_context );
		return self::has_value( $value ) ? $value : null;
	}

	public static function product_post_types() {
		return [ 'product', 'products' ];
	}

	/**
	 * Return consistent low-overhead query flags.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_optimized_query_flags() {
		return [
			'no_found_rows'             => true,
			'update_post_meta_cache'    => false,
			'update_post_term_cache'    => false,
			'cache_results'             => true,
			'ignore_sticky_posts'       => true,
			'lazy_load_term_meta'       => false,
			'suppress_filters'          => false,
		];
	}

	/**
	 * Build a stable cache key from a prefix and data payload.
	 *
	 * @param string $prefix Cache key prefix.
	 * @param mixed  $data   Key payload.
	 * @return string
	 */
	public static function build_cache_key( $prefix, $data ) {
		return sanitize_key( $prefix ) . '_' . md5( wp_json_encode( $data ) );
	}

	/**
	 * Fetch cached data from object cache or a transient-backed fallback.
	 *
	 * @param string   $key      Cache key.
	 * @param callable $callback Data generator.
	 * @param int      $ttl      Cache lifetime.
	 * @return mixed
	 */
	public static function remember( $key, callable $callback, $ttl = self::QUERY_CACHE_TTL ) {
		$cached = wp_cache_get( $key, self::CACHE_GROUP );
		if ( false !== $cached ) {
			return $cached;
		}

		$transient_key = '_wio_' . md5( $key );
		$cached        = get_transient( $transient_key );
		if ( false !== $cached ) {
			wp_cache_set( $key, $cached, self::CACHE_GROUP, $ttl );
			return $cached;
		}

		$value = $callback();

		wp_cache_set( $key, $value, self::CACHE_GROUP, $ttl );
		set_transient( $transient_key, $value, $ttl );

		return $value;
	}

	/**
	 * Return cached product IDs for a query.
	 *
	 * @param array<string, mixed> $query_args Query args.
	 * @param string               $cache_key_prefix Cache key prefix.
	 * @param int                  $ttl Cache lifetime.
	 * @return array<int, int>
	 */
	public static function get_cached_product_ids( array $query_args, $cache_key_prefix = 'product_ids', $ttl = self::QUERY_CACHE_TTL ) {
		$query_args = wp_parse_args(
			$query_args,
			self::get_optimized_query_flags()
		);

		$query_args['post_type'] = isset( $query_args['post_type'] ) ? $query_args['post_type'] : self::product_post_types();
		$query_args['fields']    = 'ids';

		$cache_key = self::build_cache_key( $cache_key_prefix, $query_args );

		return self::remember(
			$cache_key,
			static function () use ( $query_args ) {
				return array_map( 'absint', get_posts( $query_args ) );
			},
			$ttl
		);
	}

	/**
	 * Prime posts from a cached ID list while preserving order.
	 *
	 * @param array<int, int> $post_ids Post IDs.
	 * @param array<string, mixed> $extra_args Optional query overrides.
	 * @return array<int, \WP_Post>
	 */
	public static function get_posts_by_ids( array $post_ids, array $extra_args = [] ) {
		$post_ids = array_values( array_filter( array_map( 'absint', $post_ids ) ) );
		if ( empty( $post_ids ) ) {
			return [];
		}

		$query_args = wp_parse_args(
			$extra_args,
			[
				'post_type'              => self::product_post_types(),
				'post__in'               => $post_ids,
				'orderby'                => 'post__in',
				'posts_per_page'         => count( $post_ids ),
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			]
		);

		return get_posts( $query_args );
	}

	public static function canonical_product_post_type() {
		return 'product';
	}

	public static function acf_get( $field, $context = null ) {
		if ( ! function_exists( 'get_field' ) ) {
			return null;
		}

		return get_field( $field, $context );
	}

	/**
	 * Read a post field using core meta first and ACF as a fallback.
	 *
	 * @param string|array<int, string> $keys    Meta keys to check.
	 * @param int                       $post_id Post ID.
	 * @return mixed
	 */
	public static function get_post_value( $keys, $post_id ) {
		foreach ( (array) $keys as $key ) {
			$value = self::get_value_with_acf_fallback(
				static function ( $meta_key ) use ( $post_id ) {
					return get_post_meta( $post_id, $meta_key, true );
				},
				$key,
				$post_id
			);

			if ( self::has_value( $value ) ) {
				return $value;
			}
		}

		return null;
	}

	/**
	 * Read a term field using core term meta first and ACF as a fallback.
	 *
	 * @param string|array<int, string> $keys    Meta keys to check.
	 * @param int                       $term_id Term ID.
	 * @param string                    $taxonomy Taxonomy slug.
	 * @return mixed
	 */
	public static function get_term_value( $keys, $term_id, $taxonomy = '' ) {
		foreach ( (array) $keys as $key ) {
			$acf_context = $taxonomy ? $taxonomy . '_' . $term_id : null;
			$value       = self::get_value_with_acf_fallback(
				static function ( $meta_key ) use ( $term_id ) {
					return get_term_meta( $term_id, $meta_key, true );
				},
				$key,
				$acf_context
			);

			if ( self::has_value( $value ) ) {
				return $value;
			}
		}

		return null;
	}

	public static function normalize_score( $value, $min = 0, $max = 10 ) {
		if ( ! is_numeric( $value ) ) {
			return null;
		}

		$score = (float) $value;
		if ( $score < $min || $score > $max ) {
			return null;
		}

		return $score;
	}

	public static function normalize_price( $value ) {
		if ( ! is_numeric( $value ) ) {
			return null;
		}

		return (float) $value;
	}

	public static function resolve_offer_price( $post_id ) {
		$candidates = [
			self::get_post_value( 'price', $post_id ),
			self::get_post_value( '_price', $post_id ),
			self::get_post_value( 'regular_price', $post_id ),
			self::get_post_value( '_regular_price', $post_id ),
		];

		foreach ( $candidates as $candidate ) {
			$price = self::normalize_price( $candidate );
			if ( null !== $price ) {
				return $price;
			}
		}

		return null;
	}

	public static function resolve_cta_url( $post_id ) {
		$candidates = [
			self::get_post_value( 'cta_url', $post_id ),
			self::get_post_value( 'buy_url', $post_id ),
			self::get_post_value( 'affiliate_url', $post_id ),
		];

		foreach ( $candidates as $candidate ) {
			if ( is_string( $candidate ) ) {
				$url = trim( $candidate );
				if ( '' !== $url ) {
					return $url;
				}
			}
		}

		return '';
	}

	public static function resolve_currency( $post_id, $default = 'INR' ) {
		$currency = self::get_post_value( 'currency', $post_id );

		if ( is_string( $currency ) && '' !== trim( $currency ) ) {
			return strtoupper( trim( $currency ) );
		}

		if ( function_exists( 'get_woocommerce_currency' ) ) {
			//$wc_currency = get_woocommerce_currency();
			// if ( is_string( $wc_currency ) && '' !== trim( $wc_currency ) ) {
			// 	return strtoupper( trim( $wc_currency ) );
			// }
		}

		return $default;
	}

	public static function resolve_product_images( $post_id ) {
		$images = [];

		$featured = get_the_post_thumbnail_url( $post_id, 'full' );
		if ( $featured ) {
			$images[] = $featured;
		}

		$acf_candidates = [
			self::acf_get( 'product_image', $post_id ),
			self::acf_get( 'featured_image', $post_id ),
			self::acf_get( 'image', $post_id ),
			self::acf_get( 'gallery', $post_id ),
		];

		foreach ( $acf_candidates as $candidate ) {
			if ( is_string( $candidate ) && filter_var( $candidate, FILTER_VALIDATE_URL ) ) {
				$images[] = $candidate;
				continue;
			}

			if ( is_numeric( $candidate ) ) {
				$url = wp_get_attachment_image_url( (int) $candidate, 'full' );
				if ( $url ) {
					$images[] = $url;
				}
				continue;
			}

			if ( is_array( $candidate ) ) {
				foreach ( $candidate as $item ) {
					if ( is_array( $item ) && ! empty( $item['url'] ) ) {
						$images[] = $item['url'];
					} elseif ( is_numeric( $item ) ) {
						$url = wp_get_attachment_image_url( (int) $item, 'full' );
						if ( $url ) {
							$images[] = $url;
						}
					} elseif ( is_string( $item ) && filter_var( $item, FILTER_VALIDATE_URL ) ) {
						$images[] = $item;
					}
				}
			}
		}

		$images = array_values( array_unique( array_filter( $images ) ) );

		return empty( $images ) ? null : $images;
	}

	public static function resolve_key_specs( $post_id ) {
		$specs = self::get_post_value( 'key_specs', $post_id );
		$out   = [];

		if ( is_string( $specs ) ) {
			$lines = preg_split( '/\r\n|\r|\n/', $specs );
			foreach ( (array) $lines as $line ) {
				$text = self::to_plain_text( $line );
				if ( $text ) {
					$out[] = $text;
				}
			}
		} elseif ( is_array( $specs ) ) {
			foreach ( $specs as $row ) {
				if ( is_string( $row ) ) {
					$text = self::to_plain_text( $row );
					if ( $text ) {
						$out[] = $text;
					}
					continue;
				}

				if ( is_array( $row ) ) {
					foreach ( [ 'label', 'value', 'name', 'text', 'spec' ] as $key ) {
						if ( ! empty( $row[ $key ] ) ) {
							$text = self::to_plain_text( $row[ $key ] );
							if ( $text ) {
								$out[] = $text;
							}
							break;
						}
					}
				}
			}
		}

		if ( empty( $out ) ) {
			$terms = get_the_terms( $post_id, 'product_features' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$out[] = self::to_plain_text( $term->name );
				}
			}
		}

		$out = array_values( array_unique( array_filter( $out ) ) );
		return empty( $out ) ? [] : $out;
	}

	public static function to_plain_text( $value ) {
		if ( null === $value ) {
			return null;
		}

		$text = is_scalar( $value ) ? (string) $value : '';
		$text = wp_strip_all_tags( $text );
		$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$text = trim( preg_replace( '/\s+/', ' ', $text ) );

		return '' === $text ? null : $text;
	}

	public static function remove_empty( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}

		foreach ( $data as $key => $value ) {
			if ( is_array( $value ) ) {
				$data[ $key ] = self::remove_empty( $value );

				if ( empty( $data[ $key ] ) ) {
					unset( $data[ $key ] );
				}

				continue;
			}

			if ( '' === $value || null === $value ) {
				unset( $data[ $key ] );
			}
		}

		return $data;
	}

	public static function post_candidate_to_id( $candidate ) {
		if ( is_object( $candidate ) && isset( $candidate->ID ) ) {
			return (int) $candidate->ID;
		}

		return (int) $candidate;
	}

	public static function is_product_post_type( $post_type ) {
		return in_array( $post_type, self::product_post_types(), true );
	}

	public static function current_url() {
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
		$request_url = wp_parse_url( $request_uri );
		$home_parts  = wp_parse_url( home_url( '/' ) );

		$request_path = isset( $request_url['path'] ) ? $request_url['path'] : '/';
		$base_path    = isset( $home_parts['path'] ) ? '/' . trim( $home_parts['path'], '/' ) : '/';

		if ( '/' !== $base_path ) {
			$base_prefix = rtrim( $base_path, '/' );

			if ( 0 === strpos( $request_path, $base_prefix . '/' ) ) {
				$request_path = substr( $request_path, strlen( $base_prefix ) );
			} elseif ( $request_path === $base_prefix ) {
				$request_path = '/';
			}
		}

		$request_path = '/' . ltrim( $request_path, '/' );
		$url          = home_url( $request_path );

		if ( ! empty( $request_url['query'] ) ) {
			$url .= '?' . $request_url['query'];
		}

		return $url;
	}
}
