<?php
/**
 * Follow-up to fix-booze-cruises.php: the features-pair opening-sentence replace failed
 * because the stored content uses a literal unicode curly apostrophe (U+2019) in
 * "Skyline's attentive staff", not the &#8217; entity. Retry with the literal character.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-booze-cruises-2.php";'
 */

$id = 76;
$post = get_post( $id );
$old = "<!-- wp:paragraph --><p>Skyline\xe2\x80\x99s attentive staff serves premium beer, wine, and liquor at the cash bar. Want something special? <a href=\"https://skylinecruises.com/contact-us/\">Contact the event experts at Skyline</a> to customize your experience.</p><!-- /wp:paragraph -->";
$new = "<!-- wp:paragraph --><p>The Skyline Princess is not your average NYC party boat. Skyline\xe2\x80\x99s attentive staff serves premium beer, wine, and liquor at the cash bar. Want something special? <a href=\"https://skylinecruises.com/contact-us/\">Contact the event experts at Skyline</a> to customize your experience.</p><!-- /wp:paragraph -->";

if ( ! str_contains( $post->post_content, $old ) ) {
	echo "OLD TEXT NOT FOUND\n";
	exit;
}
$new_content = str_replace( $old, $new, $post->post_content );
kses_remove_filters();
$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $new_content ) ], true );
kses_init_filters();
if ( is_wp_error( $result ) ) {
	echo "update error: " . $result->get_error_message() . "\n";
	exit;
}
$saved = get_post( $id )->post_content;
echo "fixed, present: " . ( str_contains( $saved, 'not your average NYC party boat' ) ? 'yes' : 'NO' ) . "\n";
