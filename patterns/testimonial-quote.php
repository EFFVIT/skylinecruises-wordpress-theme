<?php
/**
 * Testimonial quote — About/Info category, /about/clients-testimonials/. The live site uses the
 * Strong Testimonials plugin here (a slider of quotes + a name/company list). This pattern is a
 * static grid version for the rebuild; real quotes need to be pulled verbatim from the live page
 * (or the Strong Testimonials data export) when composed into the actual page.
 */
return [
	'title'       => __( 'Testimonial Quote Grid', 'skyline-cruises' ),
	'description' => __( 'Grid of client testimonial quotes. About/Info "Testimonials" page.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"testimonial-quote"} -->
<div class="wp-block-group testimonial-quote">
<!-- wp:heading {"level":2} -->
<h2>What Our Guests Say</h2>
<!-- /wp:heading -->
<!-- wp:group {"className":"testimonial-quote__grid"} -->
<div class="wp-block-group testimonial-quote__grid">
<!-- wp:group {"className":"offering-card"} -->
<div class="wp-block-group offering-card">
<!-- wp:paragraph -->
<p>"An unforgettable night on the water &#8211; the crew went above and beyond and the views of the city were incredible."</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__attribution"} -->
<p class="testimonial__attribution">Olga R.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"offering-card"} -->
<div class="wp-block-group offering-card">
<!-- wp:paragraph -->
<p>"Our corporate event was a huge success &#8211; the staff handled everything perfectly."</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__attribution"} -->
<p class="testimonial__attribution">Christopher L.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
];
