<?php
/**
 * Route map — unique to the Public Cruise Service category (confirmed absent from every other
 * category in the structural audit). H2 + an interactive Leaflet/OpenStreetMap embed pinning the
 * real departure marina and the real public landmarks the cruise passes — NOT a fabricated route
 * line, since we don't have the vessel's actual GPS track. Pins only, per the 2026-08-21 decision.
 *
 * Departure + landmark coordinates below are real, publicly-known locations (not invented):
 * World's Fair Marina (Flushing Bay, Queens — the site's confirmed main/public home port, see
 * Port/Location row 45), Brooklyn Bridge, One World Trade Center, Ellis Island, Statue of Liberty.
 * This is the standard NYC Harbor route shared by most Public Cruise Service pages (Dinner, Brunch,
 * Lunch, Holiday, NYE, Valentine's, Mother's/Father's Day, July 4th, Booze, Anniversary,
 * Celebration, US Open). Long Island Lighthouse Cruises and Connecticut Cruises follow a different
 * real route and should pass their OWN departure/landmarks via the pattern's block attributes
 * when those two pages are built — do not reuse this default set for them.
 */

$default_departure = [
	'lat'   => 40.7591,
	'lng'   => -73.8459,
	'label' => "World's Fair Marina — Departure",
];

$default_landmarks = [
	[ 'lat' => 40.7061, 'lng' => -73.9969, 'label' => 'Brooklyn Bridge' ],
	[ 'lat' => 40.7127, 'lng' => -74.0134, 'label' => 'One World Trade Center' ],
	[ 'lat' => 40.6995, 'lng' => -74.0396, 'label' => 'Ellis Island' ],
	[ 'lat' => 40.6892, 'lng' => -74.0445, 'label' => 'Statue of Liberty' ],
];

return [
	'title'       => __( 'Cruise Route Map', 'skyline-cruises' ),
	'description' => __( 'Heading + interactive map pinning the departure marina and real NYC landmarks. Public Cruise Service category only.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"route-map"} -->
<div class="wp-block-group route-map">
<!-- wp:heading {"level":2} -->
<h2>Our Cruise Route</h2>
<!-- /wp:heading -->
<!-- wp:html -->
<div class="route-map__canvas" data-departure=\'' . esc_attr( wp_json_encode( $default_departure ) ) . '\' data-landmarks=\'' . esc_attr( wp_json_encode( $default_landmarks ) ) . '\' role="img" aria-label="Map of the Skyline Cruises route from World\'s Fair Marina past Brooklyn Bridge, One World Trade Center, Ellis Island, and the Statue of Liberty"></div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->',
];
