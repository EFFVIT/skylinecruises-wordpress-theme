<?php
/**
 * One-off content patch: "Skyline Cruises Picture Gallery" (346, /picture-gallery/) has
 * its 34 real photos in one flat gallery block; the live page
 * (https://skylinecruises.com/the-ship/picture-gallery/) organizes the same count (6+6+12+10=34)
 * into 4 subheaded sections: Exterior Images, Interior Images, Spectacular Views, Experiences.
 * Splits the single wp:gallery block into 4, with the real subheadings between them.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-picgallery2-subheadings.php";'
 */

$id = 346;
$post = get_post( $id );
if ( ! $post ) { echo "post not found\n"; exit; }
$content = $post->post_content;

// Build a numbered list of the 34 image URLs from the existing gallery.
preg_match_all( '#<figure class="wp-block-image"><img src="([^"]+)" alt="" /></figure>#', $content, $m );
$urls = $m[1];
if ( count( $urls ) !== 34 ) {
	echo "expected 34 images, found " . count( $urls ) . " - aborting\n";
	exit;
}

$sections = [
	'Exterior Images of the Skyline Princess'       => array_slice( $urls, 0, 6 ),
	'Interior Images of the Skyline Princess'       => array_slice( $urls, 6, 6 ),
	'Spectacular Views From the Skyline Princess'   => array_slice( $urls, 12, 12 ),
	'Experiences on the Skyline Princess'           => array_slice( $urls, 24, 10 ),
];

$new_blocks = '<!-- wp:heading {"level":2} --><h2>Skyline Cruises Picture Gallery</h2><!-- /wp:heading -->' . "\n\n";
foreach ( $sections as $heading => $section_urls ) {
	$new_blocks .= '<!-- wp:heading {"level":3} --><h3>' . $heading . '</h3><!-- /wp:heading -->' . "\n";
	$new_blocks .= '<!-- wp:gallery {"columns":3,"className":"bio-photo-gallery__gallery"} -->' . "\n";
	$new_blocks .= '<figure class="wp-block-gallery bio-photo-gallery__gallery">' . "\n";
	foreach ( $section_urls as $url ) {
		$new_blocks .= '<figure class="wp-block-image"><img src="' . $url . '" alt="" /></figure>' . "\n";
	}
	$new_blocks .= '</figure>' . "\n<!-- /wp:gallery -->\n";
}

$old = '<!-- wp:heading {"level":2} --><h2>Skyline Cruises Picture Gallery</h2><!-- /wp:heading -->';
$gallery_start = strpos( $content, '<!-- wp:gallery {"columns":3,"className":"bio-photo-gallery__gallery"} -->' );
$gallery_end = strpos( $content, '<!-- /wp:gallery -->', $gallery_start ) + strlen( '<!-- /wp:gallery -->' );
$heading_start = strpos( $content, $old );
if ( $heading_start === false || $gallery_start === false ) {
	echo "anchors not found\n";
	exit;
}
$old_full = substr( $content, $heading_start, $gallery_end - $heading_start );
$new_content = str_replace( $old_full, rtrim( $new_blocks ), $content );

kses_remove_filters();
$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $new_content ) ], true );
kses_init_filters();

if ( is_wp_error( $result ) ) {
	echo "update error: " . $result->get_error_message() . "\n";
	exit;
}

$saved = get_post( $id )->post_content;
echo "h3 count: " . substr_count( $saved, '<h3>' ) . " (expect 4)\n";
echo "img count: " . substr_count( $saved, '<img ' ) . " (expect 34)\n";
