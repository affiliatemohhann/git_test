<?php 
function worthio_product_permalink($link, $post) {

    if ($post->post_type === 'product') {
        $terms = wp_get_post_terms($post->ID, 'niche');
        if (!empty($terms)) {
            return str_replace('%niche%', $terms[0]->slug, $link);
        }
    }
    return $link;
}
add_filter('post_type_link', 'worthio_product_permalink', 10, 2);

?>