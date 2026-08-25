<?php
/**
 * One-off content patch: The Great Escape Yacht Rental (407) had an invented "Ready To
 * Set Sail?" closing section not present on the live page
 * (https://skylinecruises.com/the-great-escape-yacht-rental/) - the live page just ends
 * after the "Holiday pricing applies to..." line, with no closing CTA heading. Also adds
 * the real highlights image + 3 real photos that live between the intro line and the
 * repeated booking-info paragraphs.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-great-escape.php";'
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

$id = 407;

// 1. Remove the fabricated closing-cta section entirely (not on live).
apply_fix( $id,
	'<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>To make your booking for The Great Escape, give us a call at (718) 446-1100 or email TheGreatEscape@SkylineCruises.com. We look forward to cruising with you.</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">Get a Quote</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
	'',
	'Great Escape (remove fabricated closing)'
);

// 2. Insert the real highlights image + 3 real photos right after the first booking-info
//    paragraph (matches live's real order).
$photos = '<!-- wp:image --><figure class="wp-block-image"><img src="https://178-156-192-164.sslip.io/wp-content/uploads/2026/08/greatescape-highlights.png" alt="The Great Escape Yacht Rental" /></figure><!-- /wp:image -->
<!-- wp:gallery {"columns":3} --><figure class="wp-block-gallery">
<figure class="wp-block-image"><img src="https://178-156-192-164.sslip.io/wp-content/uploads/2026/08/greatescape-photo1.jpg" alt="" /></figure>
<figure class="wp-block-image"><img src="https://178-156-192-164.sslip.io/wp-content/uploads/2026/08/greatescape-photo2.jpg" alt="" /></figure>
<figure class="wp-block-image"><img src="https://178-156-192-164.sslip.io/wp-content/uploads/2026/08/greatescape-photo3.jpg" alt="" /></figure>
</figure><!-- /wp:gallery -->
';

apply_fix( $id,
	'<!-- wp:paragraph --><p>To make your booking, please call us at (718)446-1100 or email TheGreatEscape@SkylineCruises.com.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Times are adjustable.',
	'<!-- wp:paragraph --><p>To make your booking, please call us at (718)446-1100 or email TheGreatEscape@SkylineCruises.com.</p><!-- /wp:paragraph -->
' . $photos . '<!-- wp:paragraph --><p>Times are adjustable.',
	'Great Escape (real images)'
);

$saved = get_post( $id )->post_content;
echo "final checks:\n";
echo "  fabricated closing removed: " . ( ! str_contains( $saved, 'Ready To Set Sail?' ) ? 'yes' : 'NO' ) . "\n";
echo "  highlights image present: " . ( str_contains( $saved, 'greatescape-highlights.png' ) ? 'yes' : 'NO' ) . "\n";
echo "  photo count: " . substr_count( $saved, 'greatescape-photo' ) . " (expect 3)\n";
