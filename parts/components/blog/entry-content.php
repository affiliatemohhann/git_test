<?php
/**
 * Template for entry content.
 *
 * To be used inside WordPress The Loop.
 *
 * @package Worthio */

?>

<div class="entry-content">
	<?php
	if ( is_single() ) {
		the_content(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. */
					__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'worthio' ),
					[
						'span' => [
							'class' => [],
						],
					]
				),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			)
		);

		wp_link_pages(
			[
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'worthio' ),
				'after'  => '</div>',
			]
		);

	} else {
		?>
		<div class="truncate-4">
			<?php worthio_the_excerpt( 160 ); ?>
		</div>
		<?php
		echo worthio_excerpt_more();
	}

	?>
</div>