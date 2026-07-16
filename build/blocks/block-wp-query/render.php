<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 */
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<h2><?php echo esc_html( $attributes['qrmessage'] ); ?></h2>
</div>
