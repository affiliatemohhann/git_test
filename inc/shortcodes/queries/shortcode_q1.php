<?php 

    // -------------------------- Query with shortcode ------------------------------

    function shortcode_wp_q_tst1($atts) {

    // Default attributes
    $atts = shortcode_atts(
        array(
        'posts_per_page' => 5,
        ), $atts, 'latest_products'
    );       
    
    // WP Query arguments
    $args = array(
        'post_type'      => 'products', // your CPT
        'posts_per_page' => $atts['posts_per_page'],
        'post_status'    => 'publish',
    );

    $wp_query_tst1 = new WP_Query($args);

    // Start output buffering
    ob_start();

    if ($wp_query_tst1->have_posts()) {
        echo '<div class="latest-products border p-3 border-primary">';

        while ($wp_query_tst1->have_posts()) {
               $wp_query_tst1->the_post();
            ?>
            <!-- Printing... -->
            <div class="product-item">
                <a href="<?php the_permalink(); ?>">
                    <!-- <?php if (has_post_thumbnail()) { ?>
                        <div class="product-thumb">
                            <?php the_post_thumbnail('small'); ?>
                        </div>
                    <?php } ?> -->
                    
                    <h6>Post Tilte: <?php the_title(); ?></h3>
                    <p>Author: <?php the_author(); ?> </p>
                      <p>PermaLink: <?php get_permalink()  ?> </p>
                </a>

                <p><?php echo wp_trim_words(get_the_excerpt(), 1500); ?></p>
            </div>

            <?php
        }

        echo '</div>';
    } else {
        echo '<p>No products found.</p>';
    }


    // Reset post data
    wp_reset_postdata();

    // Return output
    return ob_get_clean();
}

// Register shortcode
add_shortcode('shortcodetest1', 'shortcode_wp_q_tst1');

?>