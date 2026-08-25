<?php
/**
 * One-off content patch: NYC Booze Cruises (76) restored to match the real live page
 * (https://skylinecruises.com/nyc-party-cruises/booze-cruises/):
 * - Added the entire missing opening section: h2 "NYC Party Booze Cruise" + its real
 *   intro paragraph (was skipped, built page jumped straight to the checklist row).
 * - Added the missing "Contact Us for Yacht Charter Availability!" CTA line.
 * - Added the missing h2 "Full Service NYC Skyline Harbor Cruises" heading and its real
 *   opening sentence ("The Skyline Princess is not your average NYC party boat.") above
 *   the features-pair section.
 * - Added the missing 2nd testimonial (Kim Phelan).
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-booze-cruises.php";'
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

$id = 76;

// 1. Add the missing opening section before the photo-checklist-row.
$opening = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":2} --><h2>NYC Party Booze Cruise</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Book a booze cruise on the superyacht Skyline Princess for an experience that you won&#8217;t soon forget. Experience the magnificence of the New York Harbor or Long Island Sound &#8211; full yacht charters sail from Manhattan, North Shore Long Island, or Connecticut &#8211; while reaping the benefits and ultimate fun of Skyline&#8217;s booze cruises.</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
';

apply_fix( $id,
	'<!-- wp:group {"className":"photo-checklist-row"} -->',
	$opening . '<!-- wp:group {"className":"photo-checklist-row"} -->',
	'Booze Cruises (opening section)'
);

// 2. Add the "Contact Us for Yacht Charter Availability!" CTA line right after the
//    photo-checklist-row block closes.
apply_fix( $id,
	'</div><!-- /wp:group -->
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"features-pair"} -->',
	'</div><!-- /wp:group -->
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Contact Us for Yacht Charter Availability!</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:heading {"level":2} --><h2>Full Service NYC Skyline Harbor Cruises</h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"features-pair"} -->',
	'Booze Cruises (CTA line + Full Service heading)'
);

// 3. Prepend the real opening sentence to the first features-pair item's paragraph.
apply_fix( $id,
	'<!-- wp:paragraph --><p>Skyline&#8217;s attentive staff serves premium beer, wine, and liquor at the cash bar. Want something special? <a href="https://skylinecruises.com/contact-us/">Contact the event experts at Skyline</a> to customize your experience.</p><!-- /wp:paragraph -->',
	'<!-- wp:paragraph --><p>The Skyline Princess is not your average NYC party boat. Skyline&#8217;s attentive staff serves premium beer, wine, and liquor at the cash bar. Want something special? <a href="https://skylinecruises.com/contact-us/">Contact the event experts at Skyline</a> to customize your experience.</p><!-- /wp:paragraph -->',
	'Booze Cruises (features-pair opening sentence)'
);

// 4. Add the missing 2nd testimonial (Kim Phelan).
apply_fix( $id,
	'<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- Marino Catalan</p><!-- /wp:paragraph -->
</div>
</div>
<!-- /wp:group -->',
	'<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- Marino Catalan</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__quote"} --><p class="testimonial__quote">&#8220;Great cruise! Will go again!&#8221;</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- Kim Phelan</p><!-- /wp:paragraph -->
</div>
</div>
<!-- /wp:group -->',
	'Booze Cruises (2nd testimonial)'
);

$saved = get_post( $id )->post_content;
echo "final checks:\n";
echo "  opening section: " . ( str_contains( $saved, 'NYC Party Booze Cruise' ) ? 'yes' : 'NO' ) . "\n";
echo "  cta line: " . ( str_contains( $saved, 'Contact Us for Yacht Charter Availability!' ) ? 'yes' : 'NO' ) . "\n";
echo "  full service heading: " . ( str_contains( $saved, 'Full Service NYC Skyline Harbor Cruises' ) ? 'yes' : 'NO' ) . "\n";
echo "  kim phelan: " . ( str_contains( $saved, 'Kim Phelan' ) ? 'yes' : 'NO' ) . "\n";
