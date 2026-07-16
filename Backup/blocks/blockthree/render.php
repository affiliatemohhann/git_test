<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 */
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php esc_html_e( 'Blockone Three  hello from a dynamic block!', 'blockone' ); ?>
</p>
