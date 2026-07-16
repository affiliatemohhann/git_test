<?php
$args = [
    'post_type'      => 'post',
    'posts_per_page' => 10,
    'post_status'    => 'publish'
];

$query = new WP_Query($args);
?>

<?php if ($query->have_posts()) : ?>
<div class="swiper latest-posts-swiper">
    <div class="swiper-wrapper">

        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div class="swiper-slide">
                <article class="post-card">
                    <?php the_post_thumbnail('medium'); ?>
                    <h3><?php the_title(); ?></h3>
                    <a href="<?php the_permalink(); ?>">Read Review →</a>
                </article>
            </div>
        <?php endwhile; ?>

    </div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>
<?php endif; wp_reset_postdata(); ?>
