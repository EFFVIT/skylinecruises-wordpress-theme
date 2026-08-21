<?php
/**
 * Photo + checklist row — Public Cruise Service category signature block.
 * Photo left, single-column checkmark list right (7 items on the "02 - NYC Dinner Cruises"
 * reference page). The Buffet Menu category swaps this list for a 2-col x 5-row menu grid
 * instead (see menu-grid-checklist.php) — don't reuse this pattern for that category.
 */
$check_icon = get_template_directory_uri() . '/assets/icons/check-circle.svg';
$items      = [
	'Complimentary hors d&#8217;oeuvres upon boarding',
	'Live DJ and dance floor',
	'Indoor and outdoor deck seating',
	'Full-service cash bar',
	'Panoramic Manhattan skyline views',
	'Climate-controlled interior cabin',
	'Professional, attentive crew',
];

$list_markup = '';
foreach ( $items as $item ) {
	$list_markup .= '<!-- wp:paragraph {"className":"checklist-item"} -->
<p class="checklist-item"><img class="check-icon" src="' . esc_url( $check_icon ) . '" alt="" />' . $item . '</p>
<!-- /wp:paragraph -->
';
}

return [
	'title'       => __( 'Photo + Checklist Row', 'skyline-cruises' ),
	'description' => __( 'Photo left, checkmark list right — Public Cruise Service category signature block.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"photo-checklist-row"} -->
<div class="wp-block-group photo-checklist-row">
<!-- wp:group {"className":"photo-checklist-row__intro"} -->
<div class="wp-block-group photo-checklist-row__intro">
<!-- wp:heading {"level":2} -->
<h2>A Truly Memorable Dinner Cruise</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"intro-copy"} -->
<p class="intro-copy">Step aboard for an unforgettable evening on the water, complete with skyline views, great food, and live entertainment.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"photo-checklist-row__body"} -->
<div class="wp-block-group photo-checklist-row__body">
<!-- wp:image {"className":"photo-checklist-row__photo"} -->
<figure class="wp-block-image photo-checklist-row__photo"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/checklist-placeholder.jpg' ) . '" alt="" /></figure>
<!-- /wp:image -->
<!-- wp:group {"className":"photo-checklist-row__list"} -->
<div class="wp-block-group photo-checklist-row__list">
' . $list_markup . '<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">Book Now</a></div>
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
