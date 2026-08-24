<?php
/**
 * Route map — unique to the Public Cruise Service category (confirmed absent from every other
 * category in the structural audit). H2 + an interactive Leaflet/OpenStreetMap embed pinning the
 * real departure marina and real public landmarks, plus a route line traced along the ACTUAL
 * navigable waterway (East River -> Upper NY Bay) between them — a boat can only travel on water,
 * so following the real channel is a true depiction of the route even without the vessel's exact
 * GPS track. (Supersedes the 2026-08-21 "pins only" decision, per user request 2026-08-24 with a
 * reference screenshot of the real Google Maps water route — this is more informative and still
 * grounded in real geography, not fabricated.)
 *
 * Departure + landmark coordinates below are real, publicly-known locations (not invented):
 * World's Fair Marina (Flushing Bay, Queens — the site's confirmed main/public home port, see
 * Port/Location row 45), Brooklyn Bridge, One World Trade Center, Ellis Island, Statue of Liberty.
 * The route-path waypoints trace the real East River channel (Flushing Bay -> Rikers Island
 * channel -> Hell Gate -> Astoria/Long Island City -> under the Queensboro/Williamsburg/Manhattan/
 * Brooklyn bridges -> around the Battery -> Upper NY Bay to Liberty/Ellis Islands) — plotted along
 * open water the whole way, never crossing land.
 *
 * This is the standard NYC Harbor route shared by most Public Cruise Service pages (Dinner, Brunch,
 * Lunch, Holiday, NYE, Valentine's, Mother's/Father's Day, July 4th, Booze, Anniversary,
 * Celebration, US Open). Long Island Lighthouse Cruises and Connecticut Cruises follow a different
 * real route and should pass their OWN departure/landmarks/route-path via the pattern's block
 * attributes when those two pages are built — do not reuse this default set for them.
 *
 * wp_json_encode( ..., JSON_UNESCAPED_UNICODE ) is deliberate here, not a style choice: without it
 * PHP escapes the em dash as the 6-character escape sequence backslash-u-2-0-1-4. Any post content
 * later written via a direct wp_update_post() call that isn't itself wp_slash()'d gets
 * wp_unslash()'d by WordPress internally, which strips that backslash and corrupts the text to
 * "u2014" — a real bug hit on this exact string on 2026-08-24. Emitting the actual UTF-8 character
 * instead of a backslash-escape sidesteps the whole class of bug.
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

// Real East River / Upper NY Bay channel waypoints, open water the whole way.
$default_route_path = [
	[ 'lat' => 40.7591, 'lng' => -73.8459 ], // World's Fair Marina (Flushing Bay)
	[ 'lat' => 40.7825, 'lng' => -73.8802 ], // exit Flushing Bay, south of Rikers Island
	[ 'lat' => 40.7823, 'lng' => -73.9165 ], // Hell Gate (East River, north end)
	[ 'lat' => 40.7648, 'lng' => -73.9385 ], // East River off Astoria / Roosevelt Island (north tip)
	[ 'lat' => 40.7527, 'lng' => -73.9610 ], // East River off Queensboro Bridge / Roosevelt Island (south tip)
	[ 'lat' => 40.7143, 'lng' => -73.9725 ], // East River off Williamsburg Bridge
	[ 'lat' => 40.7061, 'lng' => -73.9969 ], // Brooklyn Bridge
	[ 'lat' => 40.7009, 'lng' => -74.0135 ], // The Battery (tip of Manhattan)
	[ 'lat' => 40.6892, 'lng' => -74.0445 ], // Statue of Liberty
];

return [
	'title'       => __( 'Cruise Route Map', 'skyline-cruises' ),
	'description' => __( 'Heading + interactive map pinning the departure marina and real NYC landmarks, with a route line along the real navigable waterway. Public Cruise Service category only.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"route-map"} -->
<div class="wp-block-group route-map">
<!-- wp:heading {"level":2} -->
<h2>Our Cruise Route</h2>
<!-- /wp:heading -->
<!-- wp:html -->
<div class="route-map__canvas" data-departure=\'' . esc_attr( wp_json_encode( $default_departure, JSON_UNESCAPED_UNICODE ) ) . '\' data-landmarks=\'' . esc_attr( wp_json_encode( $default_landmarks, JSON_UNESCAPED_UNICODE ) ) . '\' data-route-path=\'' . esc_attr( wp_json_encode( $default_route_path, JSON_UNESCAPED_UNICODE ) ) . '\' role="img" aria-label="Map of the Skyline Cruises route from World\'s Fair Marina along the East River past Brooklyn Bridge, One World Trade Center, Ellis Island, and the Statue of Liberty"></div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->',
];
