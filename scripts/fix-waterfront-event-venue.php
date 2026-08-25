<?php
/**
 * One-off content patch: Waterfront Event Venue (406) restructure to match the real
 * live page (https://skylinecruises.com/yacht-charter/waterfront-event-venue/):
 * - Amenities section: real content is a 5-item bullet list, not a run-on paragraph;
 *   added the real "Your Skyline Experience Includes:" subheading (live shows this
 *   paired with the same amenities list in its mobile responsive layout).
 * - Added the 2 real "Book The Skyline Princess..." / "...Book now!" CTA button lines
 *   that flank the Waterfront Event Hall section on live.
 * - Restored the real closing heading "Your Safety is Our First Priority" (was replaced
 *   by the generic "Ready To Set Sail?"), plus its 2 missing checklists: "Protect
 *   Yourself from Getting Sick" and "Valuable Resources on COVID-19" (5 real links).
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-waterfront-event-venue.php";'
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

$id = 406;

// 1. Amenities: convert prose to real bullet list + add real "Your Skyline Experience
//    Includes:" subheading, and add the "Book The Skyline Princess..." CTA line.
$old1 = '<!-- wp:heading {"level":2} --><h2>The Skyline Princess&#8217; Amenities</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Our three-deck super-yacht is the ideal location for small private events, micro parties, and other celebrations. With a passenger capacity of up to 100 guests, the Skyline Princess meets or exceeds NYC guidelines for an event facility capacity. A unique waterfront event venue, the Skyline Princess offers exclusive amenities to its clients: unsurpassed views of the world-famous New York City skyline and NY Harbor; an up-close and personal visual tour of historic landmarks including the Empire State Building, the BMW bridges (Brooklyn, Manhattan, and Williamsburg), and of course, the Statue of Liberty and Ellis Island; a maximum of 100 guests per event; more than 6,000 square feet of event space available; and free parking at our home port, <a href="/worlds-fair-marina/">the World&#8217;s Fair Marina</a>, in Flushing, Queens.</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->';

$new1 = '<!-- wp:heading {"level":2} --><h2>The Skyline Princess&#8217; Amenities</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Our three-deck super-yacht is the ideal location for small private events, micro parties, and other celebrations. With a passenger capacity of up to 100 guests, the Skyline Princess meets or exceeds NYC guidelines for an event facility capacity. A unique waterfront event venue, the Skyline Princess offers exclusive amenities to its clients:</p><!-- /wp:paragraph -->
<!-- wp:list --><ul>
<li>Unsurpassed views of the world-famous New York City skyline and NY Harbor.</li>
<li>An up-close and personal visual tour of historic landmarks including the Empire State Building, the BMW bridges (Brooklyn, Manhattan, and Williamsburg), and of course, the Statue of Liberty and Ellis Island.</li>
<li>A maximum of 100 guests per event.</li>
<li>More than 6,000 square feet of event space available.</li>
<li>Free parking at our home port, <a href="/worlds-fair-marina/">the World&#8217;s Fair Marina</a>, in Flushing, Queens.</li>
</ul><!-- /wp:list -->
<!-- wp:heading {"level":3} --><h3>Your Skyline Experience Includes:</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line"><a href="/contact-us/request-your-quote/">Book The Skyline Princess for Your Next Event</a></p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->';

apply_fix( $id, $old1, $new1, 'Waterfront Event Venue (amenities bullets + subheading + CTA)' );

// 2. Waterfront Event Hall: add the closing "...Book now!" CTA line right after.
$old2 = '<!-- wp:paragraph --><p>The Skyline Princess offers its clients unparalleled access to its three decks, including our all-weather rooftop deck with a dance floor and dedicated DJ space, should you choose to hire one. Event/party hosts can hire a caterer to provide their food and drink, and can hire their own staff to accommodate guest needs during private events. Alcohol is allowed but on a limited basis. We can provide a list of recommended vendors or you can choose your own. We provide the space; you bring the party!</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->';

$new2 = '<!-- wp:paragraph --><p>The Skyline Princess offers its clients unparalleled access to its three decks, including our all-weather rooftop deck with a dance floor and dedicated DJ space, should you choose to hire one. Event/party hosts can hire a caterer to provide their food and drink, and can hire their own staff to accommodate guest needs during private events. Alcohol is allowed but on a limited basis. We can provide a list of recommended vendors or you can choose your own. We provide the space; you bring the party!</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Skyline Cruises will make your private event truly special. Book now!</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->';

apply_fix( $id, $old2, $new2, 'Waterfront Event Venue (Waterfront Event Hall CTA)' );

// 3. Closing section: real heading + real safety checklists (replaces generic
//    "Ready To Set Sail?" heading; keeps the same real safety paragraph and CTA button).
$old3 = '<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>The Skyline Princess meets or exceeds NYC guidelines for health and safety. As these guidelines change frequently, please call (718) 446-1100 for the most accurate and up-to-date status. Skyline Cruises remains committed to providing superior service and support that you have come to rely on. We look forward to hearing from you.</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">Get a Quote</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div>
<!-- /wp:group -->';

$new3 = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":2} --><h2>Your Safety is Our First Priority</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>The Skyline Princess meets or exceeds NYC guidelines for health and safety. As these guidelines change frequently, please call (718) 446-1100 for the most accurate and up-to-date status. Skyline Cruises remains committed to providing superior service and support that you have come to rely on. We look forward to hearing from you.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":4} --><h4>Protect Yourself from Getting Sick</h4><!-- /wp:heading -->
<!-- wp:list --><ul>
<li>Wash your hands frequently, for at least 20 seconds each time</li>
<li>Avoid handshaking</li>
<li>Cover your cough or sneeze with a tissue (or your sleeve)</li>
<li>Stay at home if you feel sick</li>
</ul><!-- /wp:list -->
<!-- wp:heading {"level":4} --><h4>Valuable Resources on COVID-19</h4><!-- /wp:heading -->
<!-- wp:list --><ul>
<li><a href="https://www.who.int/emergencies/diseases/novel-coronavirus-2019/advice-for-public">WHO Recommendations for protecting yourself and others</a></li>
<li><a href="https://www.cdc.gov/coronavirus/2019-ncov/index.html">CDC Info on Coronavirus Disease 2019</a></li>
<li><a href="https://wwwnc.cdc.gov/travel/notices">CDC Travel Advisories</a></li>
<li><a href="https://www.who.int/emergencies/diseases/novel-coronavirus-2019">World Health Organization (WHO) Info about Coronavirus</a></li>
<li><a href="https://www1.nyc.gov/site/doh/covid/covid-19-main.page">NYC Dept of Health Info about Coronavirus</a></li>
</ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">Get a Quote</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div>
<!-- /wp:group -->';

apply_fix( $id, $old3, $new3, 'Waterfront Event Venue (safety heading + checklists)' );

$saved = get_post( $id )->post_content;
echo "final checks:\n";
echo "  bullets present: " . ( str_contains( $saved, 'A maximum of 100 guests per event.' ) ? 'yes' : 'NO' ) . "\n";
echo "  subheading present: " . ( str_contains( $saved, 'Your Skyline Experience Includes:' ) ? 'yes' : 'NO' ) . "\n";
echo "  safety heading present: " . ( str_contains( $saved, 'Your Safety is Our First Priority' ) ? 'yes' : 'NO' ) . "\n";
echo "  protect-yourself checklist present: " . ( str_contains( $saved, 'Avoid handshaking' ) ? 'yes' : 'NO' ) . "\n";
echo "  resources checklist present: " . ( str_contains( $saved, 'CDC Travel Advisories' ) ? 'yes' : 'NO' ) . "\n";
