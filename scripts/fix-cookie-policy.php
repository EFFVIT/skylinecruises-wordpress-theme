<?php
/**
 * One-off content patch: Cookie Policy (page 332) — restores 2 missing parenthetical clauses in
 * the Flash Cookies section, per the 2026-08-25 site-wide copy audit.
 * Run inside the container: wp --allow-root --path=/var/www/html eval-file fix-cookie-policy.php
 */
$id = 332;
$post = get_post( $id );
$content = $post->post_content;

$old = 'how to delete existing Flash Cookies, to prevent Flash LSOs from being placed on your computer without you being asked, and how to block Flash Cookies that are not being delivered by the operator of the page you are on at the time).';
$new = 'how to delete existing Flash Cookies (referred to "information" on the Macromedia site), to prevent Flash LSOs from being placed on your computer without you being asked, and (For Flash Player 8 and later) how to block Flash Cookies that are not being delivered by the operator of the page you are on at the time).';

if ( ! str_contains( $content, $old ) ) {
	echo "OLD TEXT NOT FOUND — aborting.\n";
	exit( 1 );
}
$content = str_replace( $old, $new, $content );

kses_remove_filters();
$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $content ) ], true );
kses_init_filters();
if ( is_wp_error( $result ) ) {
	echo 'ERROR: ' . $result->get_error_message() . "\n";
	exit( 1 );
}
$saved = get_post( $id )->post_content;
echo 'Verify: ' . ( str_contains( $saved, 'referred to "information" on the Macromedia site' ) ? 'fixed' : 'NOT FIXED' ) . "\n";
