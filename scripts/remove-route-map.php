<?php
/**
 * One-off: remove the "Our Cruise Route" section entirely from Long Island Lighthouse Cruise
 * (id 77) and Connecticut Cruises (id 81), per explicit user request 2026-08-24 — these two pages
 * had their own custom route-map data (real named lighthouses / real CT departure port), but the
 * user decided the section itself shouldn't appear on these two pages at all.
 */

$page_ids = [ 77, 81 ];
$pattern  = '#<!-- wp:group \{"className":"route-map"\} -->.*?<!-- /wp:group -->\n?#s';

foreach ( $page_ids as $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		WP_CLI::error( "Page $post_id not found." );
	}

	if ( ! preg_match( $pattern, $post->post_content ) ) {
		WP_CLI::warning( "Page $post_id ({$post->post_title}): no route-map block found — skipping." );
		continue;
	}

	$new_content = preg_replace( $pattern, '', $post->post_content, 1 );

	$result = wp_update_post( [
		'ID'           => $post_id,
		'post_content' => wp_slash( $new_content ),
	], true );

	if ( is_wp_error( $result ) ) {
		WP_CLI::error( "Page $post_id: " . $result->get_error_message() );
	}

	WP_CLI::success( "Page $post_id ({$post->post_title}): route-map section removed." );
}
