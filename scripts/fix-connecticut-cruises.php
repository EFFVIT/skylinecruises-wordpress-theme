<?php
/**
 * One-off content patch: Connecticut Cruises and Yacht Charters (81) restored to match
 * the real live page (https://skylinecruises.com/yacht-charter/connecticut/), which had
 * most of its earlier real body copy missing from the built page (it jumped straight to
 * the mid-page "Convenient Connecticut Ports" section):
 * - Added h2 "A Truly Memorable Corporate Event" + paragraph.
 * - Added h2 "Ideal for Connecticut Corporate Events" + paragraph.
 * - Added h3 "Connecticut's Premier Charter Yacht" + 2 paragraphs.
 * - Added "Contact Us for Connecticut Yacht Charter Availability!" CTA.
 * - Added h2 "Let Us Help You Plan The Perfect Event" + paragraph.
 * - Completed the truncated "Convenient Connecticut Ports" intro (was missing its 2nd
 *   real sentence/paragraph).
 * - Added "Contact Us Today To Get Started!" CTA and h2 "Perfect For Events Of All
 *   Kinds" + paragraph after the ports checklist.
 * - Replaced the empty closing-cta <h2></h2> with its real heading text.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-connecticut-cruises.php";'
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

$id = 81;

// 1. Add the entire missing opening block before the photo-checklist-row.
$opening = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":2} --><h2>A Truly Memorable Corporate Event</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Make any special event you are planning more special with a Connecticut yacht charter aboard the Skyline Princess. Your corporate outing will be a huge success when its backdrop is Connecticut&#8217;s scenic shores. Imagine an elegant wedding reception on our 120-foot private yacht. Throw a Bar/Bat Mitzvah bash that guests will be talking about for years to come. Having your Connecticut wedding, party, or corporate event on the beautiful Skyline Princess can turn these visions into the affair of your dreams.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2} --><h2>Ideal for Connecticut Corporate Events</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>The Skyline Princess offers companies maximum booking option flexibility: whether you want to sail or stay dockside, host a breakfast or lunch, how much and the configuration of needed meeting space, etc. The Skyline Princess is popular for dockside breakfast meetings followed by a corporate lunch. Our vessel is available with or without our catering and we are perfect for groups of all sizes.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Connecticut&#8217;s Premier Charter Yacht</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>The Skyline Princess is Connecticut&#8217;s premier charter yacht for one-of-a-kind events. From private Connecticut wedding cruises and extravagant corporate outings to exclusive private engagement and anniversary parties, guests will enjoy a picturesque Long Island Sound cruise that won&#8217;t soon be forgotten.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Connecticut dinner cruises, lighthouse cruises wedding receptions, bar and bat mitzvahs, and corporate outings on the Skyline Princess unite breathtaking sights, impeccable cuisine and superior service to create an unforgettable southern New England cruise.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Contact Us for Connecticut Yacht Charter Availability!</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2} --><h2>Let Us Help You Plan The Perfect Event</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>If you are looking for a charter yacht cruise in CT, a private cruise charter or a wedding cruise in the Connecticut area, our event planners are ready to help you. Just give us a call at (718) 446-1100 or <a href="/contact-us/">fill out our contact form</a>. Whether you are searching for a Connecticut wedding venue, corporate outing, anniversary party, bar/bat mitzvah, or other important affairs, when you book with Skyline Cruises, you&#8217;ll be on your way to a truly memorable event.</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
';

apply_fix( $id, '<!-- wp:group {"className":"photo-checklist-row"} -->', $opening . '<!-- wp:group {"className":"photo-checklist-row"} -->', 'Connecticut Cruises (opening sections)' );

// 2. Complete the truncated "Convenient Connecticut Ports" intro paragraph.
apply_fix( $id,
	'<!-- wp:paragraph {"className":"intro-copy"} --><p class="intro-copy">Our Connecticut departure ports are in Stamford &amp; Norwalk which makes the Skyline Princess an ideal location for corporate entertaining.</p><!-- /wp:paragraph -->',
	'<!-- wp:paragraph {"className":"intro-copy"} --><p class="intro-copy">Our Connecticut departure ports are in <a href="/ponus-yacht-club/">Stamford</a> &amp; <a href="/veterans-memorial-park-marina/">Norwalk</a> which makes the Skyline Princess an ideal location for corporate entertaining. For more information please visit <a href="http://www.ConnecticutHarborCruises.com">our Connecticut Harbor Cruises website</a>. We are available for private dinner cruises, corporate events and entertaining, fundraising, school cruises as well as social and family parties, sailing from both Norwalk, CT and Stamford, CT. These are perfect for groups of all sizes.</p><!-- /wp:paragraph -->',
	'Connecticut Cruises (complete ports intro)'
);

// 3. Add the missing sections after the checklist, before features-pair.
$missing2 = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Contact Us Today To Get Started!</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2} --><h2>Perfect For Events Of All Kinds</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>These Connecticut dinner yacht charter cruises, feature lighthouses, mansions, scenic islands, impressive yachts, and the beautiful Connecticut harbors while cruising the Long Island Sound. Cruises from Norwalk and Stamford are ideal for unique events of all kinds. Make your special event cruise a Fireworks Cruise in July and August, <a href="/contact-us/">ask how</a>.</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
';

apply_fix( $id, '<!-- wp:group {"className":"features-pair"} -->', $missing2 . '<!-- wp:group {"className":"features-pair"} -->', 'Connecticut Cruises (Perfect For Events Of All Kinds section)' );

// 4. Replace the empty closing-cta heading with its real heading text.
apply_fix( $id,
	'<!-- wp:heading {"level":2} --><h2></h2><!-- /wp:heading -->',
	'<!-- wp:heading {"level":2} --><h2>Skyline will make your Connecticut event truly special so start planning today!</h2><!-- /wp:heading -->',
	'Connecticut Cruises (closing heading)'
);

$saved = get_post( $id )->post_content;
echo "final checks:\n";
echo "  opening h2: " . ( str_contains( $saved, 'A Truly Memorable Corporate Event' ) ? 'yes' : 'NO' ) . "\n";
echo "  premier charter h3: " . ( str_contains( $saved, 'Connecticut&#8217;s Premier Charter Yacht' ) ? 'yes' : 'NO' ) . "\n";
echo "  ports intro complete: " . ( str_contains( $saved, 'ConnecticutHarborCruises.com' ) ? 'yes' : 'NO' ) . "\n";
echo "  perfect for events: " . ( str_contains( $saved, 'Perfect For Events Of All Kinds' ) ? 'yes' : 'NO' ) . "\n";
echo "  closing heading: " . ( str_contains( $saved, 'Skyline will make your Connecticut event truly special' ) ? 'yes' : 'NO' ) . "\n";
