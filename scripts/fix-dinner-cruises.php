<?php
/**
 * One-off content patch: NYC Dinner Cruises (page 6) — restores the real headline, feature list,
 * testimonial, and closing paragraph, all scraped verbatim from https://skylinecruises.com/nyc-dinner-cruises/
 * during the 2026-08-25 site-wide copy audit. This page's original build invented all of this
 * content rather than scraping it, including a fabricated quote attributed to a real reviewer
 * (Olga R.) who never said those words.
 *
 * Run inside the container: wp --allow-root --path=/var/www/html eval-file fix-dinner-cruises.php
 */

$id = 6;
$post = get_post( $id );
if ( ! $post ) {
	echo "Post $id not found\n";
	exit( 1 );
}
$content = $post->post_content;

$replacements = [
	// 1. Real headline + intro paragraph (was entirely rewritten).
	[
		'old' => '<h2>A Truly Memorable Dinner Cruise</h2><!-- /wp:heading --><!-- wp:paragraph {"className":"intro-copy"} --><p class="intro-copy">Step aboard for an unforgettable evening on the water, complete with skyline views, great food, and live entertainment.</p>',
		'new' => '<h2>Our Legendary, Truly Memorable, Skyline Dinner Cruise Experience</h2><!-- /wp:heading --><!-- wp:paragraph {"className":"intro-copy"} --><p class="intro-copy">Enjoy a memorable NYC Dinner Cruise along the stunning New York harbor on the beautiful Skyline Princess. A leisurely Manhattan Dinner cruise around the world-famous Statue of Liberty, Brooklyn Bridge, and other iconic landmarks while enjoying fabulous food, entertainment, and spectacular views is the perfect way to celebrate a special event – or make any day one of a kind.</p>',
	],
	// 2. Real 7-item feature list (was 7 entirely invented items) + the real "Your Skyline
	//    Experience Includes:" label the live page has before it (missing on built).
	[
		'old' => '<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Complimentary hors d&#8217;oeuvres upon boarding</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Live DJ and dance floor</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Indoor and outdoor deck seating</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Full-service cash bar</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Panoramic Manhattan skyline views</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Climate-controlled interior cabin</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Professional, attentive crew</p><!-- /wp:paragraph -->',
		'new' => '<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Your Skyline Experience Includes:</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />An enchanting 4-hour cruise of NYC Harbor</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Scenic views of the Manhattan/NYC skyline including the Statue of Liberty, Brooklyn Bridge, Ellis Island, the South Street Seaport, and more</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />A delicious three-course meal prepared by our celebrated chefs</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Lively musical entertainment and a dance floor</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Three all-weather decks and a climate-controlled cabin</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />A reserved table for the size of your group</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Free parking</p><!-- /wp:paragraph -->',
	],
	// 3. Real testimonial — the built page had a FABRICATED quote attributed to a real reviewer
	//    (Olga R.) who never said those words. This is the real, verbatim quote from her.
	[
		'old' => '<p class="testimonial__quote">&#8220;An unforgettable night on the water &#8211; the crew went above and beyond and the views of the city were incredible.&#8221;</p>',
		'new' => '<p class="testimonial__quote">&#8220;Amazing Cruise! The staff was professional and courteous. The drinks and the dinner buffet was wonderful. We had a great time in the Skyline Cruise. I definitely recommend.&#8221;</p>',
	],
	// 4. Real closing paragraph (phone number + real CTA line) — live has no heading here at all
	//    ("Ready To Set Sail?" doesn't appear anywhere on this specific live page), so the
	//    invented heading is dropped too, not just the paragraph text.
	[
		'old' => "<!-- wp:heading {\"level\":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->\n<!-- wp:paragraph --><p>Reserve your spot today and experience New York City like never before &#8211; from the deck of a Skyline Cruises yacht.</p>",
		'new' => '<!-- wp:paragraph --><p>For more information about our public NYC skyline dinner cruises, give us a call at (718) 446-1100 or <a href="https://skylinecruises.com/contact-us/">contact us today</a>. We look forward to cruising with you.</p>',
	],
];

$applied = 0;
foreach ( $replacements as $i => $r ) {
	if ( ! str_contains( $content, $r['old'] ) ) {
		echo 'WARNING: replacement #' . ( $i + 1 ) . " old text not found verbatim — skipping\n";
		continue;
	}
	$content = str_replace( $r['old'], $r['new'], $content );
	$applied++;
}

echo "Applied $applied / " . count( $replacements ) . " replacements\n";

if ( $applied !== count( $replacements ) ) {
	echo "Not all replacements matched — aborting save, refusing to push a partial/uncertain patch.\n";
	exit( 1 );
}

kses_remove_filters();
$result = wp_update_post( [
	'ID'           => $id,
	'post_content' => wp_slash( $content ),
], true );
kses_init_filters();

if ( is_wp_error( $result ) ) {
	echo 'ERROR: ' . $result->get_error_message() . "\n";
	exit( 1 );
}

$saved = get_post( $id )->post_content;
echo 'Verify: real headline present=' . ( str_contains( $saved, 'Our Legendary, Truly Memorable' ) ? 'yes' : 'NO' ) . "\n";
echo 'Verify: real testimonial present=' . ( str_contains( $saved, 'Amazing Cruise! The staff was professional' ) ? 'yes' : 'NO' ) . "\n";
echo 'Verify: fabricated testimonial gone=' . ( str_contains( $saved, 'An unforgettable night on the water' ) ? 'NO(still there!)' : 'yes' ) . "\n";
echo 'Verify: real feature list present=' . ( str_contains( $saved, 'An enchanting 4-hour cruise of NYC Harbor' ) ? 'yes' : 'NO' ) . "\n";
echo 'Verify: real closing paragraph present=' . ( str_contains( $saved, 'For more information about our public NYC skyline dinner cruises' ) ? 'yes' : 'NO' ) . "\n";
