<?php
/**
 * Hero + prose + CTA — About/Info category. Planned from the live-site audit (not Figma):
 * covers the legacy pages like /about/, /about/directions/, /about/catering/ — heading + photo +
 * prose paragraphs + a repeatable CTA button (the live site reuses one CTA div, e.g. "Book Your
 * Seats Today", verbatim across several of these pages).
 */
return [
	'title'       => __( 'Hero + Prose + CTA', 'skyline-cruises' ),
	'description' => __( 'Heading, photo, prose paragraphs, CTA button. About/Info category.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"hero-prose-cta"} -->
<div class="wp-block-group hero-prose-cta">
<!-- wp:heading {"level":2} -->
<h2>About Skyline Cruises</h2>
<!-- /wp:heading -->
<!-- wp:group {"className":"hero-prose-cta__body"} -->
<div class="wp-block-group hero-prose-cta__body">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/about-placeholder.jpg' ) . '" alt="" /></figure>
<!-- /wp:image -->
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:paragraph -->
<p>Since 1993, Skyline Cruises has offered New Yorkers and visitors alike an unforgettable way to experience the city &#8211; from the water.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"className":"hero-prose-cta__cta"} -->
<div class="wp-block-buttons hero-prose-cta__cta">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">Book Your Seats Today</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
];
