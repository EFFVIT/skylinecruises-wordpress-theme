<?php
/**
 * 2-up style cards — Private Event/Party category, "Section - Realizing Your Vision" on
 * "20 Birthday Party Cruises" (Figma node 96:94). Confirmed structure: heading + subheading
 * paragraph + 2-up card row + a CLOSING paragraph about catering below the cards — that closing
 * line was missing from an earlier version of this pattern, added back here.
 */
return [
	'title'       => __( 'Style Cards (2-up)', 'skyline-cruises' ),
	'description' => __( 'Two-column theme/style card row with a closing catering paragraph. Private Event/Party category.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"card-grid-section"} -->
<div class="wp-block-group card-grid-section">
<!-- wp:group {"className":"card-grid-section__intro"} -->
<div class="wp-block-group card-grid-section__intro">
<!-- wp:heading {"level":2} -->
<h2>Realizing Your Birthday Vision</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Birthday parties are very personal things, and our event planners will work with you to create exactly the atmosphere you want.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"card-grid card-grid--2up"} -->
<div class="wp-block-group card-grid card-grid--2up">
<!-- wp:group {"className":"offering-card"} -->
<div class="wp-block-group offering-card">
<!-- wp:heading {"level":3} -->
<h3>Fairgrounds Fun</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Considering a fairgrounds atmosphere? We can arrange for photo booths, Foosball tables, and the appropriate food and decor to match.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"offering-card"} -->
<div class="wp-block-group offering-card">
<!-- wp:heading {"level":3} -->
<h3>Black Tie Elegance</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Black tie more your style? Dance the evening away under the stars on the Skylight Deck as the NYC skyline unreels behind and around you.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:paragraph {"className":"card-grid-section__closing"} -->
<p class="card-grid-section__closing">And, of course, there is the food. Our expert chefs can create a custom menu for you, or you can bring in your own caterers if you have a preference.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
];
