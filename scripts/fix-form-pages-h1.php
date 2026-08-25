<?php
/**
 * One-off content patch: adds each page's real, missing on-page title (the live pages all have
 * a visible entry-title heading; these 3 built pages never rendered one at all — form-page-shell
 * only ever produces its own H2 intro line, and nothing else in this theme outputs a page title
 * when there's no hero pattern on the page) — per the 2026-08-25 site-wide copy audit.
 *
 * Also fixes a real stray-markup bug on page 358: an empty `<h2></h2>` left over from an unused
 * heading param.
 *
 * Run inside the container: wp --allow-root --path=/var/www/html eval-file fix-form-pages-h1.php
 */

$fixes = [
	356 => [
		'old' => '<div class="wp-block-group form-page-shell__intro"><!-- wp:heading {"level":2} --><h2>Plan your Fall cruising',
		'new' => '<div class="wp-block-group form-page-shell__intro"><!-- wp:heading {"level":1} --><h1>Request A School Trip Quote and Itinerary</h1><!-- /wp:heading --><!-- wp:heading {"level":2} --><h2>Plan your Fall cruising',
	],
	357 => [
		'old' => '<div class="wp-block-group form-page-shell__intro"><!-- wp:heading {"level":2} --><h2>Tell us about your event</h2>',
		'new' => '<div class="wp-block-group form-page-shell__intro"><!-- wp:heading {"level":1} --><h1>Request Your Free Quote</h1><!-- /wp:heading --><!-- wp:heading {"level":2} --><h2>Tell us about your event</h2>',
	],
	358 => [
		'old' => '<div class="wp-block-group form-page-shell__intro"><!-- wp:heading {"level":2} --><h2></h2><!-- /wp:heading -->',
		'new' => '<div class="wp-block-group form-page-shell__intro"><!-- wp:heading {"level":1} --><h1>Sign up form</h1><!-- /wp:heading -->',
	],
];

$all_ok = true;
foreach ( $fixes as $id => $r ) {
	$post = get_post( $id );
	if ( ! $post ) {
		echo "Post $id not found\n";
		$all_ok = false;
		continue;
	}
	if ( ! str_contains( $post->post_content, $r['old'] ) ) {
		echo "Page $id: old text not found verbatim\n";
		$all_ok = false;
		continue;
	}
	$new_content = str_replace( $r['old'], $r['new'], $post->post_content );
	kses_remove_filters();
	$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $new_content ) ], true );
	kses_init_filters();
	if ( is_wp_error( $result ) ) {
		echo "Page $id update error: " . $result->get_error_message() . "\n";
		$all_ok = false;
		continue;
	}
	echo "Page $id fixed.\n";
}

foreach ( array_keys( $fixes ) as $id ) {
	$saved = get_post( $id )->post_content;
	echo "Verify $id: has_h1=" . ( str_contains( $saved, '<h1>' ) ? 'yes' : 'NO' )
		. ' empty_h2_gone=' . ( str_contains( $saved, '<h2></h2>' ) ? 'NO(still there)' : 'yes' ) . "\n";
}
