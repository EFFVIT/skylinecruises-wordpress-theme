<?php
/**
 * Entertainment chips — "Project Graduation" section, "40 Graduation Cruises & Parties" only
 * per the structural audit (not present on the School Events hub or any other page). Kept as its
 * own opt-in pattern rather than forced onto every School Events page, per the consistency
 * requirement: real per-page content varies, but we don't invent a chip row where the source
 * page didn't have one.
 */
$check_icon = get_template_directory_uri() . '/assets/icons/check-circle.svg';
$chips      = [ 'DJs', 'Photo Booth', 'Caricaturist', 'Magician', 'And More&#8230;' ];

$chips_markup = '';
foreach ( $chips as $chip ) {
	$chips_markup .= '<span class="entertainment-chip"><img class="check-icon" src="' . esc_url( $check_icon ) . '" alt="" />' . $chip . '</span>
';
}

return [
	'title'       => __( 'Entertainment Chips', 'skyline-cruises' ),
	'description' => __( 'Row of entertainment-option pill chips. School Events "Graduation" page only, not category-wide.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"entertainment-chips"} -->
<div class="wp-block-group entertainment-chips">
<!-- wp:heading {"level":2} -->
<h2>Project Graduation</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Add on entertainment to make the celebration even more memorable.</p>
<!-- /wp:paragraph -->
<!-- wp:html -->
<div class="entertainment-chips__row">
' . $chips_markup . '</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->',
];
