<?php
get_template_part(
	'parts/carousel/product-taxonomy-slider',
	null,
	[
		'taxonomy' => 'product_category',
		'terms'    => 'phones',
	]
);
