<?php
/**
 * Custom Search Form.
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<!-- <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'worthio' ); ?></span> -->
	<input class="search-field" type="search" placeholder="<?php echo esc_attr_x( 'Search for Products', 'placeholder', 'worthio' ); ?>" value="<?php the_search_query(); ?>" aria-label="Search" name="search">
	<button class="search-submit" type="submit"><?php echo esc_attr_x( 'Search', 'submit button', 'worthio' ); ?></button>
</form>