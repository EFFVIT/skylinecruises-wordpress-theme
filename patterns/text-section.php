<?php
/**
 * Text section — plain heading + 1-3 paragraphs, no photo/checklist. Confirmed via direct Figma
 * read as a real recurring shape across several categories (Private Event/Party "Section - Intro",
 * School Events "Section - Intro" and "Section - Spectacular Views", Port/Location "Section -
 * Intro"). Not category-specific — genuinely shared prose section, unlike features-pair.
 */
return [
	'title'       => __( 'Text Section', 'skyline-cruises' ),
	'description' => __( 'Plain heading + paragraphs, no photo. Shared prose section used across Private Event, School Events, and Port/Location intros.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":2} -->
<h2>NYC&#8217;s Floating Birthday Venue</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Your birthday is a special day &#8211; a holiday that is all yours &#8211; and what better way to celebrate than to take your birthday party out on the water? Mark your personal milestone with an event your friends and family will always remember.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
];
