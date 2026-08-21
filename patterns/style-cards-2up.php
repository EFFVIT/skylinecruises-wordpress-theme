<?php
/**
 * 2-up style cards — Private Event/Party category, second card row ("Realizing Your Vision"
 * section on "20 Birthday Party Cruises" — Fairgrounds Fun / Black Tie Elegance).
 */
return [
	'title'       => __( 'Style Cards (2-up)', 'skyline-cruises' ),
	'description' => __( 'Two-column theme/style card row. Private Event/Party category.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"card-grid-section"} -->
<div class="wp-block-group card-grid-section">
<!-- wp:group {"className":"card-grid-section__intro"} -->
<div class="wp-block-group card-grid-section__intro">
<!-- wp:heading {"level":2} -->
<h2>Realizing Your Vision</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Whatever style you have in mind, our team will help bring it to life.</p>
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
<p>Casual, playful decor and activities for a lighthearted celebration on the water.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"offering-card"} -->
<div class="wp-block-group offering-card">
<!-- wp:heading {"level":3} -->
<h3>Black Tie Elegance</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>A refined, upscale atmosphere for a more formal affair.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
];
