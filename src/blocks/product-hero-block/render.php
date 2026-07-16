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

if ( empty( $product_name ) ) {
	return;
}

$images   = \WORTHIO_THEME\Inc\Schema_Utils::resolve_product_images( $post_id );
$score    = \WORTHIO_THEME\Inc\Schema_Utils::normalize_score(	\WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'editorial_score', $post_id ));
$price    = \WORTHIO_THEME\Inc\Schema_Utils::resolve_offer_price( $post_id );
$currency = \WORTHIO_THEME\Inc\Schema_Utils::resolve_currency( $post_id, 'INR' );
$cta_text = ! empty( $attributes['ctaText'] ) ? $attributes['ctaText'] : 'Check Price';
$cta_url  = ! empty( $attributes['ctaUrl'] ) ? $attributes['ctaUrl'] : \WORTHIO_THEME\Inc\Schema_Utils::resolve_cta_url( $post_id );

if ( $cta_url && ! preg_match( '#^https?://#i', $cta_url ) ) {
	$cta_url = 'https://' . ltrim( $cta_url, '/' );
}

$price_label = null !== $price ? $currency . ' ' . number_format_i18n( (float) $price, 0 ) : '';

$read_field = static function( $keys ) use ( $post_id ) {
	foreach ( (array) $keys as $key ) {
		$value = \WORTHIO_THEME\Inc\Schema_Utils::to_plain_text(
			\WORTHIO_THEME\Inc\Schema_Utils::get_post_value( $key, $post_id )
		);
		if ( $value ) {
			return $value;
		}
	}

	return '';
};

$spec_map = [
	'Processor'   => [ 'wio_processor', 'processor', 'chipset', 'soc' ],
	'RAM/Storage' => [ 'wio_ram_storage', 'ram_storage', 'ram_and_storage', 'memory_storage', 'memory' ],
	'Display'     => [ 'wio_display', 'display', 'screen', 'screen_size' ],
	'Battery'     => [ 'wio_battery', 'battery', 'battery_capacity' ],
	'Front Camera'=> [ 'wio_front_camera', 'front_camera', 'selfie_camera' ],
	'Rear Camera' => [ 'wio_rear_camera', 'rear_camera', 'main_camera', 'camera' ],
	'Network'     => [ 'wio_network', 'network', 'connectivity' ],
	'OS'          => [ 'wio_os', 'os', 'operating_system' ],
];

$specs = [];
foreach ( $spec_map as $label => $keys ) {
	$value = $read_field( $keys );
	if ( $value ) {
		$specs[] = [
			'label' => $label,
			'value' => $value,
		];
	}
}

if ( empty( $specs ) ) {
	foreach ( \WORTHIO_THEME\Inc\Schema_Utils::resolve_key_specs( $post_id ) as $index => $fallback_spec ) {
		if ( $index >= 8 ) {
			break;
		}

		$specs[] = [
			'label' => 'Spec ' . ( $index + 1 ),
			'value' => $fallback_spec,
		];
	}
}

$gallery_id = 'product-hero-gallery-' . $post_id . '-' . wp_unique_id();
?>

<section <?php echo get_block_wrapper_attributes( [ 'class' => 'worthio-product-hero-block' ] ); ?>>
	<div class="hero-gallery" data-gallery id="<?php echo esc_attr( $gallery_id ); ?>">
		<div class="hero-gallery-main">
			<?php if ( ! empty( $images ) ) : ?>
				<?php foreach ( $images as $index => $image_url ) : ?>
					<figure class="hero-slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-slide <?php echo 0 === $index ? '' : 'hidden'; ?>>
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" loading="lazy" />
					</figure>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="hero-slide is-active hero-slide--empty" data-slide>
					<span><?php esc_html_e( 'Product image will appear here.', 'worthio' ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $images ) && count( $images ) > 1 ) : ?>
				<div class="hero-gallery-nav">
					<button type="button" class="hero-gallery-arrow" data-gallery-prev aria-controls="<?php echo esc_attr( $gallery_id ); ?>" aria-label="<?php esc_attr_e( 'Previous image', 'worthio' ); ?>">
						&#8249;
					</button>
					<button type="button" class="hero-gallery-arrow" data-gallery-next aria-controls="<?php echo esc_attr( $gallery_id ); ?>" aria-label="<?php esc_attr_e( 'Next image', 'worthio' ); ?>">
						&#8250;
					</button>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $images ) && count( $images ) > 1 ) : ?>
			<div class="hero-gallery-thumbs" role="tablist" aria-label="<?php esc_attr_e( 'Product images', 'worthio' ); ?>">
				<?php foreach ( $images as $index => $image_url ) : ?>
					<button
						type="button"
						class="hero-thumb<?php echo 0 === $index ? ' is-active' : ''; ?>"
						data-gallery-thumb
						data-slide-index="<?php echo esc_attr( $index ); ?>"
						aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
					>
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( sprintf( __( '%1$s image %2$d', 'worthio' ), $product_name, $index + 1 ) ); ?>" loading="lazy" />
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="hero-content">
		<p class="hero-kicker"><?php esc_html_e( 'Key Specificationsss', 'worthio' ); ?></p>
		<h2 class="hero-title"><?php echo esc_html( $product_name ); ?></h2>

		<div class="hero-price-panel">
			<?php if ( null !== $score ) : ?>
				<div class="hero-score-card">
					<span class="hero-card-label"><?php esc_html_e( 'Editorial Score', 'worthio' ); ?></span>
					<strong><?php echo esc_html( $score ); ?>/10</strong>
				</div>
			<?php endif; ?>

			<div class="hero-price-card">
				<span class="hero-card-label"><?php esc_html_e( 'Product Price', 'worthio' ); ?></span>
				<strong><?php echo $price_label ? esc_html( $price_label ) : esc_html__( 'Price on request', 'worthio' ); ?></strong>
			</div>
		</div>

		<?php if ( ! empty( $specs ) ) : ?>
			<div class="hero-specs-card">
				<?php foreach ( $specs as $spec ) : ?>
					<div class="hero-spec-row">
						<span class="hero-spec-label"><?php echo esc_html( $spec['label'] ); ?></span>
						<span class="hero-spec-value"><?php echo esc_html( $spec['value'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $cta_url ) ) : ?>
			<div class="hero-actions">
				<a class="hero-cta" href="<?php echo esc_url( $cta_url ); ?>" rel="nofollow sponsored noopener" target="_blank">
					<?php echo esc_html( $cta_text ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
