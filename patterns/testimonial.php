<?php
/**
 * Testimonial — quote + attribution. Standard variant (no border, used on Public Cruise/Buffet/
 * Private Event pages). School Events uses a bordered "card" variant — add ".testimonial--card"
 * to the wrapper className when composing a School Events page (see patterns.css for both variants).
 *
 * Exact spec confirmed via direct Figma read (node 7:21, "Section - Testimonial"):
 * - Big quote-mark glyph on its own line: Playfair Display Regular 64px, color gold #decb56
 * - Quote: Playfair Display ITALIC 28px/42px, color navy #0b2c4d, centered, max-width 1000px
 * - Attribution: Poppins Medium 16px, color body-gray #4b5563, centered — format is "&#8211; Name"
 *   (en dash + space prefix, not bare "Name") per the live site's own convention, keep this on
 *   every real testimonial pulled in, not just this example.
 * - 24px gap between all three elements, bg matches page bg (#fafbfc), not light-blue.
 */
return [
	'title'       => __( 'Testimonial', 'skyline-cruises' ),
	'description' => __( 'Pull-quote with gold quote-mark glyph and dash-prefixed attribution, matching the confirmed Figma spec exactly.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"testimonial"} -->
<div class="wp-block-group testimonial">
<!-- wp:paragraph {"className":"testimonial__mark"} -->
<p class="testimonial__mark">&#8220;</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__quote"} -->
<p class="testimonial__quote">An unforgettable night on the water &#8211; the crew went above and beyond and the views of the city were incredible.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__attribution"} -->
<p class="testimonial__attribution">&#8211; Olga R.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
];
