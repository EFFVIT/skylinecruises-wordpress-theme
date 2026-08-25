<?php
/**
 * One-off content patch: Testimonials page (352, about-clients-testimonials) was
 * missing the real "Some of Our Corporate Clients" list (59 companies, 3-column
 * grid) that appears above the testimonial grid on the live site. Block markup
 * generated from live-scraped company names, saved alongside this script as
 * client-list-block-352.html.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-testimonials-client-list.php";'
 */

$id = 352;
$post = get_post( $id );
if ( ! $post ) { echo "post not found\n"; exit; }

$content = $post->post_content;
$block = file_get_contents( __DIR__ . '/client-list-block-352.html' );
$anchor = '<!-- wp:group {"className":"testimonial-quote__grid"} -->';

if ( ! str_contains( $content, $anchor ) ) { echo "ANCHOR NOT FOUND\n"; exit; }

$new_content = str_replace( $anchor, $block . $anchor, $content );

kses_remove_filters();
$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $new_content ) ], true );
kses_init_filters();

if ( is_wp_error( $result ) ) {
	echo "update error: " . $result->get_error_message() . "\n";
	exit;
}

$saved = get_post( $id )->post_content;
echo "client list present: " . ( str_contains( $saved, 'Bank Of America' ) ? 'yes' : 'NO' ) . "\n";
echo "count li: " . substr_count( $saved, '<li>' ) . "\n";
