<?php
/**
 * One-off content patch, two parts:
 * 1. Fix 3 missing "In Short"/transitional sentences on privacy-policy-2 (id 333, the real page),
 *    per the 2026-08-25 site-wide copy audit — all 3 confirmed present on the live source.
 * 2. Copy that corrected real content onto privacy-policy (id 3), which is CURRENTLY serving raw
 *    WordPress installer placeholder text ("Suggested text: our website address is...") instead
 *    of Skyline's actual privacy policy — a critical bug, not a minor gap. This is a known
 *    pre-existing duplicate pair; both need the real content, the duplication itself is a
 *    separate cleanup item already tracked, not something this script resolves.
 *
 * Run inside the container: wp --allow-root --path=/var/www/html eval-file fix-privacy-policy.php
 */

$real_id = 333;
$post = get_post( $real_id );
if ( ! $post ) {
	echo "Post $real_id not found\n";
	exit( 1 );
}
$content = $post->post_content;

$replacements = [
	[
		'old' => '<!-- wp:paragraph --><p>Information collected from other sources: We may obtain information about you from other sources,',
		'new' => '<!-- wp:paragraph --><p>In Short: We may collect limited data from public databases, marketing partners, and other outside sources.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Information collected from other sources: We may obtain information about you from other sources,',
	],
	[
		'old' => '<!-- wp:paragraph --><p>Fulfill and manage your orders for Contractual reasons.',
		'new' => '<!-- wp:paragraph --><p>We use the information we collect or receive:</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Fulfill and manage your orders for Contractual reasons.',
	],
	[
		'old' => 'which will enable them to collect data about how you interact with the Websites over time. Unless described in this Policy,',
		'new' => 'which will enable them to collect data about how you interact with the Websites over time. This information may be used to, among other things, analyze and track data, determine the popularity of certain content and better understand online activity. Unless described in this Policy,',
	],
];

$applied = 0;
foreach ( $replacements as $i => $r ) {
	if ( ! str_contains( $content, $r['old'] ) ) {
		echo 'WARNING: replacement #' . ( $i + 1 ) . " old text not found verbatim\n";
		continue;
	}
	$content = str_replace( $r['old'], $r['new'], $content );
	$applied++;
}
echo "Applied $applied / " . count( $replacements ) . " replacements to page $real_id\n";
if ( $applied !== count( $replacements ) ) {
	echo "Aborting — not all replacements matched.\n";
	exit( 1 );
}

kses_remove_filters();
$result = wp_update_post( [ 'ID' => $real_id, 'post_content' => wp_slash( $content ) ], true );
kses_init_filters();
if ( is_wp_error( $result ) ) {
	echo 'ERROR updating ' . $real_id . ': ' . $result->get_error_message() . "\n";
	exit( 1 );
}
echo "Page $real_id updated.\n";

// Part 2: push the same corrected content onto the placeholder page.
$placeholder_id = 3;
$placeholder = get_post( $placeholder_id );
if ( ! $placeholder ) {
	echo "Post $placeholder_id not found\n";
	exit( 1 );
}
echo "Placeholder page $placeholder_id current content starts with: " . substr( wp_strip_all_tags( $placeholder->post_content ), 0, 80 ) . "\n";

kses_remove_filters();
$result2 = wp_update_post( [ 'ID' => $placeholder_id, 'post_content' => wp_slash( $content ) ], true );
kses_init_filters();
if ( is_wp_error( $result2 ) ) {
	echo 'ERROR updating ' . $placeholder_id . ': ' . $result2->get_error_message() . "\n";
	exit( 1 );
}
echo "Page $placeholder_id replaced with real policy content.\n";

// Verify both.
foreach ( [ $real_id, $placeholder_id ] as $id ) {
	$saved = get_post( $id )->post_content;
	$has_placeholder = str_contains( $saved, 'Suggested text' ) || str_contains( $saved, 'Gravatar' );
	$has_real         = str_contains( $saved, 'Skyline Cruise Lines Inc' );
	$has_fix1         = str_contains( $saved, 'We may collect limited data from public databases' );
	echo "Page $id verify: still_placeholder=" . ( $has_placeholder ? 'YES(bad!)' : 'no' )
		. ' real_policy_present=' . ( $has_real ? 'yes' : 'NO' )
		. ' fix1_present=' . ( $has_fix1 ? 'yes' : 'NO' ) . "\n";
}
