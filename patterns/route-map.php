<?php
/**
 * Route map — unique to the Public Cruise Service category (confirmed absent from every other
 * category in the structural audit). H2 + a large cruise-route map image.
 */
return [
	'title'       => __( 'Cruise Route Map', 'skyline-cruises' ),
	'description' => __( 'Heading + large route map image. Public Cruise Service category only.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"route-map"} -->
<div class="wp-block-group route-map">
<!-- wp:heading {"level":2} -->
<h2>Our Cruise Route</h2>
<!-- /wp:heading -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/route-map-placeholder.jpg' ) . '" alt="Skyline Cruises route map" /></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->',
];
