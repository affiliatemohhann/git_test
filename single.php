<!-- For Posts -->

<?php
/**
 * Single post template file. *
 * @package Worthio
 */

get_header();
?>

	<div id="primary">
		<main id="main" class="site-main mt-5" role="main">
			<div class="container">
				<div class="row">

					
					<div class="col-lg-8 col-md-8 col-sm-12">
						<?php
						if ( have_posts() ) :
							?>
							<div class="post-wrap">
							<?php
							if ( is_home() && ! is_front_page() ) {
								?>
								<header class="mb-5">
									<h1 class="page-title screen-reader-text">
										<?php single_post_title(); ?>
										<h2>Singl Page Title</h2>
									</h1>
								</header>
								<?php
							}

							while ( have_posts() ) : the_post();
								get_template_part( '/parts/content' );

							endwhile;
							?>

						<?php

						else :

							get_template_part( '/parts/content-none' );

							?>

							</div>
						<?php
						endif;

						// For Single Post loadmore button, uncomment this code and comment next and prev link code below.
						 echo do_shortcode( '[single_post_listings]' )
						?>
					</div>
					<?php
					// Next and previous link for page navigation.
					?>
					<div class="d-flex justify-content-between pb-3">
						<div class="prev-link"><?php previous_post_link(); ?></div>
						<div class="next-link"><?php next_post_link(); ?></div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<?php get_sidebar(); ?>
				</div>
			</div>
			<?php comments_template(); ?>
		</main>
	</div>

<?php

$args = [

	'post_status'      => 'publish',
	'posts_per_page' => 1,
	'page'           => 1,
	'startup_post_id' => 181,
];

// $my_query = new WP_Query( $args );
// echo '<pre>';
// print_r( $my_query -> request);	
// wp_die();

get_footer();
