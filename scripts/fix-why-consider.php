<?php
/**
 * One-off content patch: "Why To Consider Skyline Cruises For Your Next Event" (345)
 * had its 3 key-point subheadings collapsed into one merged sentence, was missing the
 * real COVID-19 paragraph, and was missing 2 closing sentences that appear on live under
 * the "Great Food..." section. Rebuilt to match the real live structure (3 separate
 * bulleted/subheaded points), verbatim from https://skylinecruises.com/why-to-consider-skyline-cruises-for-your-next-event/
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-why-consider.php";'
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

$old = '<!-- wp:paragraph --><p>The key points to think about when planning your event.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Safety, 3-Open Air Decks, Fresh Air Events. Unique Event Space, Scenery, Awesome Guest Experience. Great Food, Great Service, Professional Business Rating A+.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Without safety, the rest of the checklist doesn\'t matter. We pride ourselves on our safety record. We exceed USCG requirements and go the extra mile to ensure that you have peace of mind before, during, and after the event.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Wowing your guests is an important aspect of all events. When a guest walks in and goes "WOW! This is cool!" you know you made that critical first impression. It\'s also important to keep that up throughout the event. Panoramic views of the NYC Skyline or Long Island Sound and its majestic lighthouses and Goldcoast keep the guests entertained. When guests see familiar sites they can share a story or take a selfie with it.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>As we all look for activities outdoors and being in the open air, Skyline Cruises is a great option. Always an amazing landmark, building, or scenic opportunity to see as we sail around the majestic harbors of the Tri-State.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>After you\'ve made sure to select a company where safety is the top priority and awed them with the novelty of a moving panoramic view, food and service can make or break the event. At Skyline Cruises, we have our own onboard chef complete with professionally dressed wait staff and bar service. Menus to accommodate most budgets.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>We carry an A+ rating and accreditation with the Better Business Bureau.</p><!-- /wp:paragraph -->';

$new = '<!-- wp:paragraph --><p><strong>The key points to think about when planning your event.</strong></p><!-- /wp:paragraph -->
<!-- wp:list --><ul><li>Safety &#8226; 3-Open Air Decks &#8226; Fresh Air Events</li><li>Unique Event Space &#8226; Scenery &#8226; Awesome Guest Experience</li><li>Great Food &#8226; Great Service &#8226; Professional Business Rating A+</li></ul><!-- /wp:list -->
<!-- wp:heading {"level":3} --><h3>Safety, 3-Open Air Decks, Fresh Air Events</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Without safety, the rest of the checklist doesn\'t matter. We pride ourselves on our safety record. We exceed USCG requirements and go the extra mile to ensure that you have peace of mind before, during, and after the event.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>What are we are doing about COVID19 regulations? We have 3 open-air decks and are conducting temperature checks before the employees and guests board the Skyline. We have tables at the recommended distance and have dance floor zones set up. We also clean and sterilize the Skyline Princess before, during, and after each cruise. To see our full COVID19 statement and policy, <a href="/about-covid-19-statement/">click here</a>.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Unique Event Space, Scenery, Awesome Guest Experience</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Wowing your guests is an important aspect of all events. When a guest walks in and goes "WOW! This is cool!" you know you made that critical first impression. It\'s also important to keep that up throughout the event. Panoramic views of the NYC Skyline or Long Island Sound and its majestic lighthouses and Goldcoast keep the guests entertained. When guests see familiar sites they can share a story or take a selfie with it.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>As we all look for activities outdoors and being in the open air, Skyline Cruises is a great option. Always an amazing landmark, building, or scenic opportunity to see as we sail around the majestic harbors of the Tri-State.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Great Food, Great Service, Professional A+ Business Rating</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>After you\'ve made sure to select a company where safety is the top priority and awed them with the novelty of a moving panoramic view, food and service can make or break the event. At Skyline Cruises, we have our own onboard chef complete with professionally dressed wait staff and bar service. Menus to accommodate most budgets. See why our clients come back year after year after year.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>We carry an A+ rating and accreditation with the Better Business Bureau. See what our many happy clients have said about us over the years, <a href="/about-clients-testimonials/">click to see testimonials</a>.</p><!-- /wp:paragraph -->';

apply_fix( 345, $old, $new, 'Why To Consider Skyline Cruises (restructure + COVID + closing sentences)' );
