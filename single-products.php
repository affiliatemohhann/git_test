<!-- For Posts -->

<?php
/**
 * Single post template file. *
 * @package Worthio
 */

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
$post_id           = get_the_ID();
$editorial_score   = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'editorial_score', $post_id );
$pros              = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'pros', $post_id );
$cons              = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'cons', $post_id );
$short_summary     = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'short_summary', $post_id );
$final_verdict     = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'final_verdict', $post_id );
$performance_score = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'performance_score', $post_id );
$camera_score      = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'camera_score', $post_id );
$battery_score     = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'battery_score', $post_id );
$display_score     = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'display_score', $post_id );
$value_score       = \WORTHIO_THEME\Inc\Schema_Utils::get_post_value( 'value_score', $post_id );
?>

<div class="container">
	<div class="row">
		<div class="col-md-9">
		<h2><?php the_title(); ?> Review</h2>
		<?php the_content(); ?>			

		</div>
		<div class="col-md-3">
			<h3>Sidebar</h3>
		</div>
	</div>
</div>

<?php endwhile; endif; ?>


<?php
get_footer();
?>
