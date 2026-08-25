<?php
/**
 * One-off content patch: "Picture Gallery of Skyline Cruises" (344) is missing
 * two real caption paragraphs that appear on the live page right after the first
 * hero photo and before the rest of the gallery grid: Buck's personal wedding
 * testimonial, and the "Skyline Bistro Table" add-on description.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-picgallery1-captions.php";'
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

$captions = '<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">I don&#8217;t just &#8220;sell&#8221; or plan events on the Skyline Princess because I want to do my job. I believe in what we are doing. My wife and I had our wedding reception on the Skyline Princess on February 22, 2014, because I know we offer an amazing, unique experience with great service and panoramic views of the NYC Skyline. We&#8217;ve been to many lovely land-based wedding venues, but our event is still talked about to this day. &#8211; Buck, Senior Sales Manager in the Skyline Office</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Add-on: The Skyline Bistro Table (below) is definitely a room enhancer. It adds an instant &#8220;wow&#8221; to the room when your guests first enter. It&#8217;s great to snack off while socializing. Finger foods with an assortment of crudit&#233;s, fresh fruit, Italian meats, cheeses, bread, and crackers.</p><!-- /wp:paragraph -->
';

apply_fix( 344,
	'<!-- wp:gallery {"columns":3,"className":"bio-photo-gallery__gallery"} -->',
	$captions . '<!-- wp:gallery {"columns":3,"className":"bio-photo-gallery__gallery"} -->',
	'Picture Gallery of Skyline Cruises (captions)'
);
