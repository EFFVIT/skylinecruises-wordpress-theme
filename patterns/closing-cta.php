<?php
/**
 * Closing CTA — dark navy panel, heading/paragraph + button. Used at the end of most categories
 * except Port/Location (which has no CTA-button section at all — leanest template).
 */
return [
	'title'       => __( 'Closing CTA', 'skyline-cruises' ),
	'description' => __( 'Dark navy closing call-to-action panel.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} -->
<h2>Ready To Set Sail?</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Reserve your spot today and experience New York City like never before &#8211; from the deck of a Skyline Cruises yacht.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">Book Now</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
];
