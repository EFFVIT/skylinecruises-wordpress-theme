<?php
/**
 * Directions block — Port/Location category, using "48 Liberty Landing Marina" as the clean
 * reference. Nested Driving (named sub-routes) / Mass Transit structure. Port pages are the
 * leanest template (hero + intro + this + newsletter + footer, no Features/Testimonial/CTA).
 * Heading is per-page real copy ("Directions to Liberty Landing Marina"), NOT a generic
 * "Getting There" — build-pages.js\'s directionsBlock() takes a `heading` param for this.
 */
return [
	'title'       => __( 'Directions Block', 'skyline-cruises' ),
	'description' => __( 'Nested Driving/Mass Transit directions section. Port/Location category.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"directions-block"} -->
<div class="wp-block-group directions-block">
<!-- wp:heading {"level":2} -->
<h2>Directions to Liberty Landing Marina</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Liberty Landing Marina is easily accessible by car or public transit.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"directions-block__group"} -->
<div class="wp-block-group directions-block__group">
<!-- wp:heading {"level":3} -->
<h3>Driving</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"directions-block__route"} -->
<p class="directions-block__route"><strong>From Manhattan:</strong> Take the Holland Tunnel to NJ-78, then follow signs to Liberty State Park.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"directions-block__route"} -->
<p class="directions-block__route"><strong>From New Jersey and Upstate New York:</strong> Take the NJ Turnpike South to Exit 14B, then follow signs to Liberty State Park.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"directions-block__group"} -->
<div class="wp-block-group directions-block__group">
<!-- wp:heading {"level":3} -->
<h3>Mass Transit</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Take the Light Rail to Liberty State Park Station, a short walk from the marina.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
];
