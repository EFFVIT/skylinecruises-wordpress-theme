<?php
/**
 * One-off: swap page 6's (NYC Dinner Cruises) static route-map image block for the new
 * interactive Leaflet map block. Run via `wp eval-file` inside the WP container.
 */

$post_id = 6;
$post    = get_post( $post_id );

if ( ! $post ) {
	WP_CLI::error( 'Page 6 not found.' );
}

$old_block = '<!-- wp:group {"className":"route-map"} -->
<div class="wp-block-group route-map">
<!-- wp:heading {"level":2} --><h2>Our Cruise Route</h2><!-- /wp:heading -->
<!-- wp:image --><figure class="wp-block-image"><img src="https://178-156-192-164.sslip.io/wp-content/uploads/2026/08/routemap-sm.jpg" alt="Skyline Cruises route map" /></figure><!-- /wp:image -->
</div>
<!-- /wp:group -->';

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

$new_block = '<!-- wp:group {"className":"route-map"} -->
<div class="wp-block-group route-map">
<!-- wp:heading {"level":2} -->
<h2>Our Cruise Route</h2>
<!-- /wp:heading -->
<!-- wp:html -->
<div class="route-map__canvas" data-departure=\'' . esc_attr( wp_json_encode( $departure ) ) . '\' data-landmarks=\'' . esc_attr( wp_json_encode( $landmarks ) ) . '\' role="img" aria-label="Map of the Skyline Cruises route from World\'s Fair Marina past Brooklyn Bridge, One World Trade Center, Ellis Island, and the Statue of Liberty"></div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->';

if ( strpos( $post->post_content, $old_block ) === false ) {
	WP_CLI::error( 'Old route-map block not found verbatim in page 6 content — aborting, nothing changed.' );
}

$new_content = str_replace( $old_block, $new_block, $post->post_content );

$result = wp_update_post( [
	'ID'           => $post_id,
	'post_content' => $new_content,
], true );

if ( is_wp_error( $result ) ) {
	WP_CLI::error( $result->get_error_message() );
}

WP_CLI::success( 'Page 6 route-map block replaced.' );
