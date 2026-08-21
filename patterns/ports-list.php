<?php
/**
 * Ports list — the list-form counterpart to marina-grid-3x3, used on non-hub School Events pages
 * ("40 Graduation Cruises & Parties"). Single-column list with a "Private Cruises Only" flag on
 * some items, per the structural audit.
 */
$check_icon = get_template_directory_uri() . '/assets/icons/check-circle.svg';
$ports      = [
	[ 'name' => "World's Fair Marina", 'private' => false ],
	[ 'name' => 'Chelsea Piers', 'private' => false ],
	[ 'name' => 'Pier 36', 'private' => false ],
	[ 'name' => 'Liberty Landing Marina', 'private' => true ],
	[ 'name' => 'Town Dock Park', 'private' => true ],
	[ 'name' => 'Ponus Yacht Club', 'private' => true ],
	[ 'name' => 'Veterans Memorial Park and Marina', 'private' => true ],
	[ 'name' => 'New Rochelle Municipal Marina', 'private' => true ],
	[ 'name' => 'Yonkers City Pier', 'private' => true ],
	[ 'name' => 'Glen Cove Ferry Terminal', 'private' => true ],
	[ 'name' => 'Lincoln Harbor', 'private' => true ],
	[ 'name' => 'Departure Ports Overview', 'private' => false ],
];

$items_markup = '';
foreach ( $ports as $port ) {
	$flag          = $port['private'] ? '<span class="private-flag">Private Cruises Only</span>' : '';
	$items_markup .= '<div class="ports-list__item"><img class="check-icon" src="' . esc_url( $check_icon ) . '" alt="" /><span>' . $port['name'] . $flag . '</span></div>
';
}

return [
	'title'       => __( 'Ports List', 'skyline-cruises' ),
	'description' => __( 'Single-column port/marina list with optional "Private Cruises Only" flags. School Events non-hub pages.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"ports-list"} -->
<div class="wp-block-group ports-list">
<!-- wp:heading {"level":2} -->
<h2>Departure Ports</h2>
<!-- /wp:heading -->
<!-- wp:html -->
<div class="ports-list__items">
' . $items_markup . '</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->',
];
