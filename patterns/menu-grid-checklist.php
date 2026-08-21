<?php
/**
 * Menu grid checklist — Buffet Menu category signature block. Same photo+checklist shape as
 * Public Cruise Service's photo-checklist-row, but the list is a 2-col x 5-row menu-item grid
 * instead of a single column — confirmed distinct in the structural audit ("17 Dinner Buffet").
 * Do not reuse photo-checklist-row.php for this category.
 */
$check_icon = get_template_directory_uri() . '/assets/icons/check-circle.svg';
$items      = [
	'Herb-Roasted Chicken',
	'Grilled Salmon with Lemon Butter',
	'Penne alla Vodka',
	'Seasonal Roasted Vegetables',
	'Garden Salad with House Dressing',
	'Carved Roast Beef Station',
	'Rice Pilaf',
	'Assorted Dinner Rolls',
	'Chef&#8217;s Dessert Selection',
	'Coffee &amp; Tea Service',
];

$grid_markup = '';
foreach ( $items as $item ) {
	$grid_markup .= '<!-- wp:paragraph {"className":"checklist-item"} -->
<p class="checklist-item"><img class="check-icon" src="' . esc_url( $check_icon ) . '" alt="" />' . $item . '</p>
<!-- /wp:paragraph -->
';
}

return [
	'title'       => __( 'Menu Grid Checklist', 'skyline-cruises' ),
	'description' => __( 'Photo + 2-col x 5-row menu-item checklist grid — Buffet Menu category signature block.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"photo-checklist-row"} -->
<div class="wp-block-group photo-checklist-row">
<!-- wp:group {"className":"photo-checklist-row__intro"} -->
<div class="wp-block-group photo-checklist-row__intro">
<!-- wp:heading {"level":2} -->
<h2>Our Dinner Cruise Buffet Menu</h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"photo-checklist-row__body"} -->
<div class="wp-block-group photo-checklist-row__body">
<!-- wp:image {"className":"photo-checklist-row__photo"} -->
<figure class="wp-block-image photo-checklist-row__photo"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/buffet-placeholder.jpg' ) . '" alt="" /></figure>
<!-- /wp:image -->
<!-- wp:group {"className":"photo-checklist-row__list","style":{"layout":{"selfStretch":"fill"}}} -->
<div class="wp-block-group photo-checklist-row__list" style="display:grid;grid-template-columns:1fr 1fr;gap:16px 32px;">
' . $grid_markup . '<!-- wp:buttons -->
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
