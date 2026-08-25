<?php
/**
 * One-off content patch: Celebration Cruises (84) uses the generic composer heading
 * "Book Your Event" in its intro section, but the live page pairs that exact intro
 * paragraph ("No matter what the celebration...") with the real subheading
 * "A Truly Memorable Experience" instead. Manifest also corrected in
 * pages-manifest-public-cruise-service-batch2.json for future rebuilds.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-celebration-heading.php";'
 */

function apply_fix( $id, $old, $new, $label ) {
	$post = get_post( $id );
	if ( ! $post ) { echo "$label ($id): post not found\n"; return false; }
	if ( ! str_contains( $post->post_content, $old ) ) { echo "$label ($id): OLD TEXT NOT FOUND\n"; return false; }
	$new_content = str_replace( $old, $new, $post->post_content );
	kses_remove_filters();
	$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $new_content ) ], true );
	kses_init_filters();
	if ( is_wp_error( $result ) ) { echo "$label ($id): update error " . $result->get_error_message() . "\n"; return false; }
	echo "$label ($id): fixed\n";
	return true;
}

apply_fix( 84,
	'<!-- wp:heading {"level":2} --><h2>Book Your Event</h2><!-- /wp:heading -->',
	'<!-- wp:heading {"level":2} --><h2>A Truly Memorable Experience</h2><!-- /wp:heading -->',
	'Celebration Cruises (subheading)'
);
