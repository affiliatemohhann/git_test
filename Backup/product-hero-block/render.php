<?php
/**
 * Product Hero dynamic block render.
 *
 * @package Worthio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = 0;
if ( isset( $block->context['postId'] ) ) {
	$post_id = (int) $block->context['postId'];
}
if ( ! $post_id ) {
	$post_id = get_the_ID();
}
if ( ! $post_id ) {
	return;
}

$product_name = get_the_title( $post_id );
$images       = \WORTHIO_THEME\Inc\Schema_Utils::resolve_product_images( $post_id );
$image_url    = ! empty( $images[0] ) ? $images[0] : '';
$score        = \WORTHIO_THEME\Inc\Schema_Utils::normalize_score(
	\WORTHIO_THEME\Inc\Schema_Utils::acf_get( 'editorial_score', $post_id )
);
$price        = \WORTHIO_THEME\Inc\Schema_Utils::resolve_offer_price( $post_id );
$currency     = \WORTHIO_THEME\Inc\Schema_Utils::resolve_currency( $post_id, 'INR' );
$cta_text     = ! empty( $attributes['ctaText'] ) ? $attributes['ctaText'] : 'Check Price';
$cta_url      = ! empty( $attributes['ctaUrl'] ) ? $attributes['ctaUrl'] : \WORTHIO_THEME\Inc\Schema_Utils::resolve_cta_url( $post_id );
$key_specs    = \WORTHIO_THEME\Inc\Schema_Utils::resolve_key_specs( $post_id );

if ( empty( $product_name ) ) {
	return;
}
?>

<section <?php echo get_block_wrapper_attributes( [ 'class' => 'worthio-product-hero-block' ] ); ?>>
	<div class="hero-media">
		<?php if ( $image_url ) : ?>
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" loading="lazy" />
		<?php endif; ?>
	</div>

	<div class="hero-content">
		<h2 class="hero-title"><?php echo esc_html( $product_name ); ?></h2>

		<div class="hero-meta">
			<div><h2>Static Text in Hero Block</h2></div>
			<?php if ( null !== $score ) : ?>
				<span class="hero-score"><?php echo esc_html( $score ); ?>/10</span>
			<?php endif; ?>
			<?php if ( null !== $price ) : ?>
				<span class="hero-price"><?php echo esc_html( $currency . ' ' . number_format_i18n( (float) $price, 0 ) ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $key_specs ) ) : ?>
			<ul class="hero-specs">
				<?php foreach ( $key_specs as $spec ) : ?>
					<li><?php echo esc_html( $spec ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( ! empty( $cta_url ) ) : ?>
			<p class="hero-cta-wrap">
				<a class="hero-cta" href="<?php echo esc_url( $cta_url ); ?>" rel="nofollow sponsored noopener" target="_blank">
					<?php echo esc_html( $cta_text ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>

