<?php
/**
 * Features pair — "Memorable Service" / "Smooth Sailing" icon pair. Used on Public Cruise Service,
 * Buffet Menu, and Private Event/Party pages only — confirmed absent on School Events, Graduation,
 * and Port/Location pages. Not a truly global pattern, so it's opt-in per page, not in page.php.
 */
$service_icon = get_template_directory_uri() . '/assets/icons/feature-service.png';
$sailing_icon = get_template_directory_uri() . '/assets/icons/feature-sailing.png';

return [
	'title'       => __( 'Features Pair', 'skyline-cruises' ),
	'description' => __( 'Memorable Service / Smooth Sailing two-up icon feature row.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"features-pair"} -->
<div class="wp-block-group features-pair">
<!-- wp:group {"className":"features-pair__item"} -->
<div class="wp-block-group features-pair__item">
<!-- wp:image {"sizeSlug":"full"} -->
<figure class="wp-block-image"><img src="' . esc_url( $service_icon ) . '" alt="Memorable Service" /></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3} -->
<h3>Memorable Service</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Our attentive crew takes care of every detail, so you can focus on making memories with your guests.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"features-pair__item"} -->
<div class="wp-block-group features-pair__item">
<!-- wp:image {"sizeSlug":"full"} -->
<figure class="wp-block-image"><img src="' . esc_url( $sailing_icon ) . '" alt="Smooth Sailing" /></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3} -->
<h3>Smooth Sailing</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Enjoy calm, scenic waters aboard a well-maintained fleet built for a comfortable ride, rain or shine.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
];
