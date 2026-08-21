<?php
/**
 * Marina grid (3x3) — School Events hub page ("39 School Events", Figma node 120:234). A 3x3
 * checklist of marina/port names WITH their real location suffix (e.g. "Pier 36 – Downtown
 * Manhattan, NY") — an earlier version of this dropped the location text, corrected here with
 * the real verbatim list. Check-circle icon confirmed 28px (node 120:236), sized via the
 * ".marina-grid .check-icon" rule in patterns.css, not the 24px default.
 */
$check_icon = get_template_directory_uri() . '/assets/icons/check-circle.svg';
$marinas    = [
	"The World's Fair Marina &#8211; Flushing, Queens, NY",
	'Pier 36 &#8211; Downtown Manhattan, NY',
	'Chelsea Piers &#8211; Manhattan, NY',
	'Liberty Landing Marina &#8211; Jersey City, NJ',
	'Yonkers City Pier &#8211; Yonkers, NY',
	'Municipal Marina &#8211; New Rochelle, NY',
	'Town Dock Park &#8211; Port Washington, NY',
	'Ponus Yacht Club &#8211; Stamford, CT',
	"Veteran's Memorial Park Marina &#8211; Norwalk, CT",
];

$grid_markup = '';
foreach ( $marinas as $marina ) {
	$grid_markup .= '<!-- wp:paragraph {"className":"checklist-item"} -->
<p class="checklist-item"><img class="check-icon" src="' . esc_url( $check_icon ) . '" alt="" />' . $marina . '</p>
<!-- /wp:paragraph -->
';
}

return [
	'title'       => __( 'Marina Grid (3x3)', 'skyline-cruises' ),
	'description' => __( '3x3 checklist grid of marina/port names with real location suffixes. School Events hub page.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"marina-grid"} -->
<div class="wp-block-group marina-grid">
' . $grid_markup . '</div>
<!-- /wp:group -->',
];
