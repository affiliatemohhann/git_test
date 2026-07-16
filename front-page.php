<?php 
/* 
    Front Page Landing template 
    @package deals365
*/
get_header();
?>

	<div class="wrt-container">
		<div class="wrt-grd2-sdbr">
			<!-- Main Content -->
			<div role="main-content">
				<!-- Hero -->
				<?php get_template_part( 'parts/components/home/demo/home_hero1' )?>
			</div>
			
			<!-- sidebar -->
			<aside>
				Sidebar
			</aside>
		</div>
	</div>

	<div id="primary " class="wrt-container">		
		<div class="row">
			<div class="col-lg-9">
			<main id="main" class="site-main mt-5" role="main">
			<div class="">
				
				<!-- <?php 	get_template_part( 'parts/components/home/posts-carousel' )?> -->
			</div>
			<div class="home-page-wrap">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						get_template_part( 'parts/content', 'page' );
					endwhile;
					?>

				<?php

				else :
					get_template_part( 'parts/content-none' );
				endif;										
				?>
			</div>
			<?php ?>	

			<div class="pb-4">
				<div class="pb-3"><h2>Swiper Category carousel - Taxonomy</h2></div>
				<?php get_template_part('parts/carousel/category-slider')?>
			</div>
			
			<div class="pb-4">
				<div><h4>Swiper Taxonomy carousel</h4></div>
				<?php get_template_part('parts/carousel/taxonomy-slider')?>
			</div>
		</main>
			</div>
			<div class="col-lg-3 bg-light">
				<div class="p-2">
					<h4>Side bar</h4>
					<?php get_sidebar() ?>
					<?php 
						function worthio_phone_categories() {
						$terms = get_terms([
							'taxonomy' => 'product_category',
							// 'hide_empty' => true
						]);

						if (empty($terms)) return;

						echo '<ul class="sidebar-list">';
						foreach ($terms as $term) {
							echo '<li><a href="'.get_term_link($term).'">'.$term->name.'</a></li>';
						}
						echo '</ul>';
					}
					?>
					<h6>Categories List</h6>
					<?php worthio_phone_categories()?>
				<?php 
						function worthio_brand_categories() {
						$terms = get_terms([
							'taxonomy' => 'product_brand',
							'hide_empty' => true
						]);

						if (empty($terms)) return;

						echo '<ul class="sidebar-list">';
						foreach ($terms as $term) {
							echo '<li><a href="'.get_term_link($term).'">'.$term->name.'</a></li>';
						}
						echo '</ul>';
					}
					?>
						<h6>Phone Brands</h6>
					<?php worthio_brand_categories()?>	
				</div>
			</div>

			<div class="pb-4">
				<div class="pb-3"><h2>Swiper Posts carousel</h2></div>
				<?php get_template_part('parts/carousel/latest-posts')?>
			</div>

		</div>

	</div>

<?php

get_footer();