<?php 
    function query_testone () {

        $qry_args = array(
        'post_type'      => 'products', // your CPT
        'posts_per_page' => 8,
        'post_status'    => 'publish',
    );
      
        $the_query = new WP_Query($qry_args );

        if($the_query->have_posts()) {
            echo '<ul>';
            while ($the_query->have_posts()) {
                $the_query->the_post();
                echo '<li>' . get_the_title() . " -> Post Title"  . '</li>';
             }
            echo '</ul>';
            wp_reset_postdata();

        } else{
            echo 'No posts found';
        }
    };

    add_shortcode('shrtcodequeryone', 'query_testone');
?>
