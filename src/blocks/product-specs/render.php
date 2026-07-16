<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.

 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<div><h2>Product Specs Block Started</h2></div>

 <div <?php echo get_block_wrapper_attributes(); ?>>
    <?php echo esc_html( $attributes['message'] ); ?> 
</div> 


