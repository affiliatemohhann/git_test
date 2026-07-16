<?php
/**
 * Shared product taxonomy slider.
 *
 * @package Worthio
 */

$taxonomy = ! empty( $args['taxonomy'] ) ? $args['taxonomy'] : '';
$terms    = ! empty( $args['terms'] ) ? $args['terms'] : '';

if ( empty( $taxonomy ) || empty( $terms ) ) {
	return;
}

$query_args = [
	'post_type'              => \WORTHIO_THEME\Inc\Schema_Utils::product_post_types(),
	'tax_query'              => [
		[
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $terms,
		],
	],
	'posts_per_page'         => 8,
];

$post_ids = \WORTHIO_THEME\Inc\Schema_Utils::get_cached_product_ids(
	$query_args,
	'product_taxonomy_slider_' . sanitize_key( $taxonomy )
);
$posts = \WORTHIO_THEME\Inc\Schema_Utils::get_posts_by_ids( $post_ids );
?>

<?php if ( ! empty( $posts ) ) : ?>
<div class="swiper latest-posts-swiper">
	<div class="swiper-wrapper">
		<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
			<div class="swiper-slide">
				<article class="post-card">
					<?php the_post_thumbnail( 'medium' ); ?>
					<h3><?php the_title(); ?></h3>
					<a href="<?php the_permalink(); ?>">Read Review â†’</a>
				</article>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="swiper-button-next"></div>
	<div class="swiper-button-prev"></div>
</div>
<?php endif; wp_reset_postdata(); ?>
