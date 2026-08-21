<?php
/**
 * Marina grid (3x3) — School Events hub page ("39 School Events"). A 3x3 checklist of marina/port
 * names with check icons, tying directly into the Port/Location category.
 */
$check_icon = get_template_directory_uri() . '/assets/icons/check-circle.svg';
$marinas    = [
	"World's Fair Marina",
	'Chelsea Piers',
	'Pier 36',
	'Liberty Landing Marina',
	'Town Dock Park',
	'Ponus Yacht Club',
	'Veterans Memorial Park and Marina',
	'New Rochelle Municipal Marina',
	'Yonkers City Pier',
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
	'description' => __( '3x3 checklist grid of marina/port names. School Events hub page.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"marina-grid"} -->
<div class="wp-block-group marina-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px 40px;padding:80px 157px;">
' . $grid_markup . '</div>
<!-- /wp:group -->',
];
