<?php
/**
 * Bio + photo gallery — About/Info category, the newest markup era found on the live site
 * (e.g. /captain-arnold-wonsever/ — pure Gutenberg: H1, hero image, prose, closing gallery).
 */
return [
	'title'       => __( 'Bio + Photo Gallery', 'skyline-cruises' ),
	'description' => __( 'Staff/captain bio with photo and closing image gallery. About/Info category.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"bio-photo-gallery"} -->
<div class="wp-block-group bio-photo-gallery">
<!-- wp:heading {"level":2} -->
<h2>Captain Profile</h2>
<!-- /wp:heading -->
<!-- wp:group {"className":"bio-photo-gallery__body"} -->
<div class="wp-block-group bio-photo-gallery__body">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/bio-placeholder.jpg' ) . '" alt="" /></figure>
<!-- /wp:image -->
<!-- wp:paragraph -->
<p>With decades of experience navigating New York Harbor, our captains bring skill, care, and warmth to every cruise.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:gallery {"columns":3,"className":"bio-photo-gallery__gallery"} -->
<figure class="wp-block-gallery bio-photo-gallery__gallery">
<figure class="wp-block-image"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/gallery-placeholder-1.jpg' ) . '" alt="" /></figure>
<figure class="wp-block-image"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/gallery-placeholder-2.jpg' ) . '" alt="" /></figure>
<figure class="wp-block-image"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/gallery-placeholder-3.jpg' ) . '" alt="" /></figure>
</figure>
<!-- /wp:gallery -->
</div>
<!-- /wp:group -->',
];
