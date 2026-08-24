<?php
/**
 * One-off #2: add the East River route-path polyline to page 6's (NYC Dinner Cruises) route-map
 * block, and fix a real corruption bug introduced by one-off #1's direct wp_update_post() call —
 * it passed unslashed content, so WordPress's internal wp_unslash() ate the backslash out of the
 * JSON "—" em-dash escape, leaving literal "u2014" text in the department label. This script
 * fixes both by replacing the whole canvas div at once, using JSON_UNESCAPED_UNICODE (no backslash
 * to strip) and wp_slash() on the final content before wp_update_post() (the officially correct way
 * to call it programmatically) so the bug can't recur.
 */

$post_id = 6;
$post    = get_post( $post_id );

if ( ! $post ) {
	WP_CLI::error( 'Page 6 not found.' );
}

// Matches the corrupted div currently live (data-departure has "u2014" instead of an em dash,
// and has no data-route-path attribute at all) OR the pre-corruption form, either way replaced
// wholesale rather than patched in place.
$pattern = '/<div class="route-map__canvas"[^>]*><\/div>/';

if ( ! preg_match( $pattern, $post->post_content ) ) {
	WP_CLI::error( 'route-map__canvas div not found in page 6 content — aborting, nothing changed.' );
}

$departure = [
	'lat'   => 40.7591,
	'lng'   => -73.8459,
	'label' => "World's Fair Marina — Departure",
];

$landmarks = [
	[ 'lat' => 40.7061, 'lng' => -73.9969, 'label' => 'Brooklyn Bridge' ],
	[ 'lat' => 40.7127, 'lng' => -74.0134, 'label' => 'One World Trade Center' ],
	[ 'lat' => 40.6995, 'lng' => -74.0396, 'label' => 'Ellis Island' ],
	[ 'lat' => 40.6892, 'lng' => -74.0445, 'label' => 'Statue of Liberty' ],
];

$route_path = [
	[ 'lat' => 40.7591, 'lng' => -73.8459 ],
	[ 'lat' => 40.7825, 'lng' => -73.8802 ],
	[ 'lat' => 40.7823, 'lng' => -73.9165 ],
	[ 'lat' => 40.7648, 'lng' => -73.9385 ],
	[ 'lat' => 40.7527, 'lng' => -73.9610 ],
	[ 'lat' => 40.7143, 'lng' => -73.9725 ],
	[ 'lat' => 40.7061, 'lng' => -73.9969 ],
	[ 'lat' => 40.7009, 'lng' => -74.0135 ],
	[ 'lat' => 40.6892, 'lng' => -74.0445 ],
];

$new_div = '<div class="route-map__canvas" data-departure=\'' . esc_attr( wp_json_encode( $departure, JSON_UNESCAPED_UNICODE ) ) . '\' data-landmarks=\'' . esc_attr( wp_json_encode( $landmarks, JSON_UNESCAPED_UNICODE ) ) . '\' data-route-path=\'' . esc_attr( wp_json_encode( $route_path, JSON_UNESCAPED_UNICODE ) ) . '\' role="img" aria-label="Map of the Skyline Cruises route from World\'s Fair Marina along the East River past Brooklyn Bridge, One World Trade Center, Ellis Island, and the Statue of Liberty"></div>';

$new_content = preg_replace( $pattern, $new_div, $post->post_content, 1 );

$result = wp_update_post( [
	'ID'           => $post_id,
	'post_content' => wp_slash( $new_content ),
], true );

if ( is_wp_error( $result ) ) {
	WP_CLI::error( $result->get_error_message() );
}

// Verify the em dash survived (not "u2014").
$saved = get_post_field( 'post_content', $post_id );
if ( str_contains( $saved, 'u2014' ) || ! str_contains( $saved, "\xe2\x80\x94" ) ) {
	WP_CLI::error( 'Post-save verification failed: em dash still corrupted or missing.' );
}

WP_CLI::success( 'Page 6 route-map block updated with route-path line; em-dash corruption fixed.' );
