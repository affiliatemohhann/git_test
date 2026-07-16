<?php
/**
 * Custom template tags for the theme.
 *
 * @package Worthio
 */

/**
 * Gets the thumbnail with Lazy Load.
 * Should be called in the WordPress Loop.
 *
 * @param int|null $post_id               Post ID.
 * @param string   $size                  The registered image size.
 * @param array    $additional_attributes Additional attributes.
 *
 * @return string
 */
function get_the_post_custom_thumbnail( $post_id, $size = 'featured-thumbnail', $additional_attributes = [] ) {
	$custom_thumbnail = '';

	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}

	if ( has_post_thumbnail( $post_id ) ) {
		$default_attributes = [
			'loading' => 'lazy'
		];

		$attributes = array_merge( $additional_attributes, $default_attributes );

		$custom_thumbnail = wp_get_attachment_image(
			get_post_thumbnail_id( $post_id ),
			$size,
			false,
			$attributes
		);
	}

	return $custom_thumbnail;
}

/**
 * Renders Custom Thumbnail with Lazy Load.
 *
 * @param int    $post_id               Post ID.
 * @param string $size                  The registered image size.
 * @param array  $additional_attributes Additional attributes.
 */
function the_post_custom_thumbnail( $post_id, $size = 'featured-thumbnail', $additional_attributes = [] ) {
	echo get_the_post_custom_thumbnail( $post_id, $size, $additional_attributes );
}

/**
 * Prints HTML with meta information for the current post-date/time.
 *
 * @return void
 */
function worthio_posted_on() {

	$year                        = get_the_date( 'Y' );
	$month                       = get_the_date( 'n' );
	$day                         = get_the_date( 'j' );
	$post_date_archive_permalink = get_day_link( $year, $month, $day );

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	// Post is modified ( when post published time is not equal to post modified time )
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_attr( get_the_date() ),
		esc_attr( get_the_modified_date( DATE_W3C ) ),
		esc_attr( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'worthio' ),
		'<a href="' . esc_url( $post_date_archive_permalink ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	echo '<span class="posted-on text-secondary">' . $posted_on . '</span>';
}

/**
 * Prints HTML with meta information for the current author.
 *
 * @return void
 */
function worthio_posted_by() {
	$byline = sprintf(
		esc_html_x( ' by %s', 'post author', 'worthio' ),
		'<span class="author vcard"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline text-secondary">' . $byline . '</span>';
}

/**
 * Get the trimmed version of post excerpt.
 *
 * This is for modifing manually entered excerpts,
 * NOT automatic ones WordPress will grab from the content.
 *
 * It will display the first given characters ( e.g. 100 ) characters of a manually entered excerpt,
 * but instead of ending on the nth( e.g. 100th ) character,
 * it will truncate after the closest word.
 *
 * @param int $trim_character_count Charter count to be trimmed
 *
 * @return bool|string
 */
function worthio_the_excerpt( $trim_character_count = 0 ) {
	$post_ID = get_the_ID();

	if ( empty( $post_ID ) ) {
		return null;
	}

	if ( has_excerpt() || 0 === $trim_character_count ) {
		the_excerpt();

		return;
	}

	$excerpt = wp_html_excerpt( get_the_excerpt( $post_ID ), $trim_character_count, '[...]' );


	echo $excerpt;
}

/**
 * Filter the "read more" excerpt string link to the post.
 *
 * @param string $more "Read more" excerpt string.
 *
 * @return string (Maybe) modified "read more" excerpt string.
 */
function worthio_excerpt_more( $more = '' ) {

	if ( ! is_single() ) {
		$more = sprintf( '<a class="worthio-read-more text-white mt-3 btn btn-info" href="%1$s">%2$s</a>',
			get_permalink( get_the_ID() ),
			__( 'Read more', 'worthio' )
		);
	}

	return $more;
}

/**
 * Worthio Pagination.
 *
 * @return void
 */
function worthio_pagination() {

	$allowed_tags = [
		'span' => [
			'class' => []
		],
		'a' => [
			'class' => [],
			'href' => [],
		]
	];

	$args = [
		'before_page_number' => '<span class="btn border border-secondary mr-2 mb-2">',
		'after_page_number' => '</span>',
	];

	printf( '<nav class="worthio-pagination clearfix">%s</nav>', wp_kses( paginate_links( $args ), $allowed_tags ) );
}

/**
 * Display Post pagination with prev next, first last, to, from
 *
 * @param $current_page_no
 * @param $posts_per_page
 * @param $article_query
 * @param $first_page_url
 * @param $last_page_url
 * @param bool $is_query_param_structure
 */
function worthio_the_post_pagination( $current_page_no, $posts_per_page, $article_query, $first_page_url, $last_page_url, bool $is_query_param_structure = true ) {
	$prev_posts = ( $current_page_no - 1 ) * $posts_per_page;
	$from       = 1 + $prev_posts;
	$to         = count( $article_query->posts ) + $prev_posts;
	$of         = $article_query->found_posts;
	$total_pages = $article_query->max_num_pages;

	$base = ! empty( $is_query_param_structure ) ? add_query_arg( 'page', '%#%' ) :  get_pagenum_link( 1 ) . '%_%';
	$format = ! empty( $is_query_param_structure ) ? '?page=%#%' : 'page/%#%';

	?>
	<div class="mt-0 md:mt-10 mb-10 lg:my-5 flex items-center justify-end posts-navigation">
		<?php
		if ( 1 < $total_pages && !empty( $first_page_url ) ) {
			printf(
				'<span class="mr-2">Showing %1$s - %2$s Of %3$s</span>',
				$from,
				$to,
				$of
			);
		}


		// First Page
		if ( 1 !== $current_page_no && ! empty( $first_page_url ) ) {
			printf( '<a class="first-pagination-link btn border border-secondary mr-2" href="%1$s" title="first-pagination-link">%2$s</a>', esc_url( $first_page_url ), __( 'First', 'worthio' ) );
		}

		echo paginate_links( [
			'base'      => $base,
			'format'    => $format,
			'current'   => $current_page_no,
			'total'     => $total_pages,
			'prev_text' => __( 'Prev', 'worthio' ),
			'next_text' => __( 'Next', 'worthio' ),
		] );

		// Last Page
		if ( $current_page_no < $total_pages && !empty( $last_page_url ) ) {

			printf( '<a class="last-pagination-link btn border border-secondary ml-2" href="%1$s" title="last-pagination-link">%2$s</a>', esc_url( $last_page_url ), __( 'Last', 'worthio' ) );
		}

		?>
	</div>
	<?php
}

/**
 * Checks to see if the specified user id has a uploaded the image via wp_admin.
 *
 * @return bool  Whether or not the user has a gravatar
 */
function worthio_is_uploaded_via_wp_admin( $gravatar_url ) {

	$parsed_url = wp_parse_url( $gravatar_url );

	$query_args = ! empty( $parsed_url['query'] ) ? $parsed_url['query'] : '';

	// If query args is empty means, user has uploaded gravatar.
	return empty( $query_args );

}

/**
 * If the gravatar is uploaded returns true.
 *
 * There are two things we need to check, If user has uploaded the gravatar:
 * 1. from WP Dashboard, or
 * 2. or gravatar site.
 *
 * If any of the above condition is true, user has valid gravatar,
 * and the function will return true.
 *
 * 1. For Scenario 1: Upload from WP Dashboard:
 * We check if the query args is present or not.
 *
 * 2. For Scenario 2: Upload on Gravatar site:
 * When constructing the URL, use the parameter d=404.
 * This will cause Gravatar to return a 404 error rather than an image if the user hasn't set a picture.
 *
 * @param $user_email
 *
 * @return bool
 */
function worthio_has_gravatar( $user_email ) {

	$gravatar_url = get_avatar_url( $user_email );

	if ( worthio_is_uploaded_via_wp_admin( $gravatar_url ) ) {
		return true;
	}

	$gravatar_url = sprintf( '%s&d=404', $gravatar_url );

	// Make a request to $gravatar_url and get the header
	$headers = @get_headers( $gravatar_url );

	// If request status is 200, which means user has uploaded the avatar on gravatar site
	return preg_match( "|200|", $headers[0] );
}

/**
 * Get hierarchical term items.
 *
 * @param string $taxonomy  Taxonomy.
 * @param int    $parent_id Parent term ID.
 *
 * @return array
 */
function get_hierarchical_term_items( string $taxonomy = '', int $parent_id = 0 ) : array {
	return \WORTHIO_THEME\Inc\Schema_Utils::remember(
		\WORTHIO_THEME\Inc\Schema_Utils::build_cache_key(
			'hierarchical_term_items',
			[
				'taxonomy'  => $taxonomy,
				'parent_id' => $parent_id,
			]
		),
		static function () use ( $taxonomy, $parent_id ) {
			$the_terms = get_terms(
				[
					'taxonomy'   => $taxonomy,
					'hide_empty' => true,
					'pad_counts' => true,
				]
			);

			if ( is_wp_error( $the_terms ) || empty( $the_terms ) ) {
				return [];
			}

			$terms_by_parent = [];

			foreach ( $the_terms as $the_term ) {
				if ( ! $the_term instanceof WP_Term || empty( $the_term->count ) ) {
					continue;
				}

				$current_parent = (int) $the_term->parent;

				if ( ! isset( $terms_by_parent[ $current_parent ] ) ) {
					$terms_by_parent[ $current_parent ] = [];
				}

				$terms_by_parent[ $current_parent ][] = $the_term;
			}

			$build_term_branch = static function ( $current_parent ) use ( &$build_term_branch, $terms_by_parent ) {
				if ( empty( $terms_by_parent[ $current_parent ] ) ) {
					return [];
				}

				$items = [];

				foreach ( $terms_by_parent[ $current_parent ] as $term ) {
					$term_data = [
						'label' => $term->name,
						'value' => $term->term_id,
						'slug'  => $term->slug,
					];

					$children = $build_term_branch( (int) $term->term_id );
					if ( ! empty( $children ) ) {
						$term_data['children'] = $children;
					}

					$items[] = $term_data;
				}

				return $items;
			};

			return $build_term_branch( $parent_id );
		}
	);
}

/**
 * Get Filter Ids with their title.
 *
 * Pairs of filter ids and title in their respective key's
 * e.g. ['destinations'=> [123 => 'Canada', 345 => 'Egypt']].
 *
 * @todo Write tests for this and add recursion for the loops
 *       inside this function.
 *
 * @param array $filters_data Filter's data.
 *
 * @return array filter ids.
 */
function get_filter_ids( array $filters_data = [] ): array {
	if ( empty( $filters_data ) || ! is_array( $filters_data ) ) {
		return [];
	}

	$filter_ids = [];

	foreach ( $filters_data as $filter_data ) {
		if (
			empty( $filter_data['slug'] )
			|| empty( $filter_data['children'] )
			|| ! is_array( $filter_data['children'] )
		) {
			continue;
		}

		// Build Data.
		$key                = $filter_data['slug'];
		$filter_ids[ $key ] = [];

		// Parent.
		foreach ( $filter_data['children'] as $parent_item ) {
			$filter_ids[ $key ][ $parent_item['value'] ] = [
				'slug' => $parent_item['slug'] ?? '',
				'text' => $parent_item['label'] ?? '',
			];

			if ( empty( $parent_item['children'] ) || ! is_array( $parent_item['children'] ) ) {
				continue;
			}

			// Children.
			foreach ( $parent_item['children'] as $child_item ) {
				$filter_ids[ $key ][ $child_item['value'] ] = [
					'slug' => $child_item['slug'] ?? '',
					'text' => $child_item['label'] ?? '',
				];

				if ( empty( $child_item['children'] ) || ! is_array( $child_item['children'] ) ) {
					continue;
				}

				// Grand Children.
				foreach ( $child_item['children'] as $grand_child_item ) {
					$filter_ids[ $key ][ $grand_child_item['value'] ] = [
						'slug' => $grand_child_item['slug'] ?? '',
						'text' => $grand_child_item['label'] ?? '',
					];

					if ( empty( $grand_child_item['children'] ) || ! is_array( $grand_child_item['children'] ) ) {
						continue;
					}

					// Great Grand Children.
					foreach ( $grand_child_item['children'] as $great_grand_child_item ) {
						$filter_ids[ $key ][ $great_grand_child_item['value'] ] = [
							'slug' => $great_grand_child_item['slug'] ?? '',
							'text' => $great_grand_child_item['label'] ?? '',
						];
					}
				}
			}
		}
	}

	return $filter_ids;
}

/**
 * Get Filters data.
 *
 * @return array[]
 */
function get_filters_data(): array {
	$category_terms = get_hierarchical_term_items( 'category' );
	$tag_terms = get_hierarchical_term_items( 'post_tag' );

	return [
		[
			'label'    => 'Categories',
			'slug'     => 'category',
			'children' => $category_terms,
		],
		[
			'label'    => 'Tags',
			'slug'     => 'post_tag',
			'children' => $tag_terms,
		],
	];
}

// Svg Load Funtions

