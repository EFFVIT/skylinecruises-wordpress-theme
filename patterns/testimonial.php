<?php
/**
 * Testimonial — the canonical "Testimonial Template" (Figma node 103:177), NOT the plain flat
 * version previously copied from one page's older instance (node 7:21). Confirmed via direct
 * Figma read: full-bleed rounded photo card (23px radius, ~23-30px inset — same family as the
 * hero and newsletter cards), dark overlay, centered white content:
 * - Heading "Skyline Testimonials" — Playfair Display Regular 60/61, "Testimonials" in italic gold
 * - Quote — Lato Regular 18/32.4, white, centered
 * - Attribution — Playfair Display Regular 20/32.4, white, format "- Name"
 * The heading and background photo are fixed section chrome (like the newsletter card); only the
 * quote + attribution vary per page, pulled verbatim from that page's real testimonial.
 */
return [
	'title'       => __( 'Testimonial', 'skyline-cruises' ),
	'description' => __( 'Rounded-card photo testimonial with "Skyline Testimonials" heading, matching the canonical Figma Testimonial Template (103:177) exactly.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"testimonial-outer"} -->
<div class="wp-block-group testimonial-outer">
<div class="testimonial" style="background-image:url(' . esc_url( get_template_directory_uri() . '/assets/images/testimonial-bg.jpg' ) . ')">
<!-- wp:heading {"level":2,"className":"testimonial__heading"} -->
<h2 class="testimonial__heading">Skyline <em>Testimonials</em></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"testimonial__quote"} -->
<p class="testimonial__quote">&#8220;An unforgettable night on the water &#8211; the crew went above and beyond and the views of the city were incredible.&#8221;</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__attribution"} -->
<p class="testimonial__attribution">- Olga R.</p>
<!-- /wp:paragraph -->
</div>
</div>
<!-- /wp:group -->',
];
