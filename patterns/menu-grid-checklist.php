<?php
/**
 * Menu grid checklist — Buffet Menu category signature block ("Section - Buffet Options",
 * Figma node 32:5 on page 31:3, "17 - Dinner Cruise Buffet Menu"). Same photo+checklist shape as
 * Public Cruise Service's photo-checklist-row, but the list is a 2-col grid with a LARGER 40px
 * check icon (confirmed via node 32:11 — use ".checklist-item--lg", not the default 24px).
 * Do not reuse photo-checklist-row.php for this category.
 *
 * Note: in the real page this section is preceded by a separate plain heading+paragraph "Section
 * - Intro" block (node 32:2, "A Truly Memorable Dinner" + real verbatim intro copy) — compose
 * that as a plain core/heading + core/paragraph before this pattern, it doesn't need its own
 * registered pattern file.
 */
$check_icon = get_template_directory_uri() . '/assets/icons/check-circle.svg';
$items      = [
	'Caesar Salad',
	'Freshly baked rolls',
	'Braised Boneless Short Rib of Beef',
	'Grilled Bruschetta Chicken',
	'Lemon Sole Stuffed with Crabmeat',
	'Vegetarian Pasta or Vegetarian Entr&eacute;e',
	'Roasted New Bliss Potatoes',
	'Freshly Steamed Vegetables',
	'Selection of Gourmet Cakes',
	'Coffee, Tea, and Water included',
];

$grid_markup = '';
foreach ( $items as $item ) {
	$grid_markup .= '<!-- wp:paragraph {"className":"checklist-item checklist-item--lg"} -->
<p class="checklist-item checklist-item--lg"><img class="check-icon" src="' . esc_url( $check_icon ) . '" alt="" />' . $item . '</p>
<!-- /wp:paragraph -->
';
}

return [
	'title'       => __( 'Menu Grid Checklist', 'skyline-cruises' ),
	'description' => __( 'Photo + 2-col menu-item checklist grid with the larger 40px check icon — Buffet Menu category signature block.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"photo-checklist-row"} -->
<div class="wp-block-group photo-checklist-row">
<!-- wp:group {"className":"photo-checklist-row__intro"} -->
<div class="wp-block-group photo-checklist-row__intro">
<!-- wp:heading {"level":2} -->
<h2>Some Dinner Buffet Options</h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"photo-checklist-row__body"} -->
<div class="wp-block-group photo-checklist-row__body">
<!-- wp:image {"className":"photo-checklist-row__photo"} -->
<figure class="wp-block-image photo-checklist-row__photo"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/buffet-placeholder.jpg' ) . '" alt="" /></figure>
<!-- /wp:image -->
<!-- wp:group {"className":"photo-checklist-row__list","style":{"layout":{"selfStretch":"fill"}}} -->
<div class="wp-block-group photo-checklist-row__list" style="display:grid;grid-template-columns:1fr 1fr;gap:16px 24px;">
' . $grid_markup . '<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">Book Your Dinner Cruise Now!</a></div>
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
