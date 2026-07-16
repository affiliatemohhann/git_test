<?php get_header(); ?>

<!-- For Pages -->


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Page - WP Query</h2>
            <hr>
            <?php 
                $qryargs =array (
                    'post_type' => 'products',
                    'posts_per_page' => 1,
                );
                $post_query = new WP_Query( $qryargs );
            ?>

            <?php while($post_query->have_posts()) : $post_query->the_post();?>
            <h4><?php the_title() ?></h4>
            <?php endwhile; wp_reset_query(); ?>

            <p><hr></p>

            <?php 
                $qryargs =array (
                    'post_type' => 'products',
                    'posts_per_page' => 6,
                    'post__not_in' => array (566)
                );
                $post_query = new WP_Query( $qryargs );
            ?>

            <?php while($post_query->have_posts()) : $post_query->the_post();?>
            <h4><?php the_title() ?></h4>
            <?php endwhile; wp_reset_query(); ?>
            
        </div>

        <div class="col-lg-9">
             <?php the_content(); ?>
        </div>
        <div class="col-lg-3">
				<div class="p-2">
					<h4>Side bar</h4>
					<?php get_sidebar() ?>
				</div> 
        </div>
    </div>
</div>

<?php get_footer();?>

