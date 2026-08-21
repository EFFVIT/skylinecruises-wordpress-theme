<?php
/**
 * Testimonial — quote + attribution. Standard variant (no border, used on Public Cruise/Buffet
 * pages). School Events uses a bordered "card" variant — add ".testimonial--card" to the wrapper
 * className when composing a School Events page (see patterns.css for both variants).
 */
return [
	'title'       => __( 'Testimonial', 'skyline-cruises' ),
	'description' => __( 'Pull-quote with attribution.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"testimonial"} -->
<div class="wp-block-group testimonial">
<!-- wp:paragraph {"className":"testimonial__quote"} -->
<p class="testimonial__quote">"An unforgettable night on the water &#8211; the crew went above and beyond and the views of the city were incredible."</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__attribution"} -->
<p class="testimonial__attribution">Olga R.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
];
