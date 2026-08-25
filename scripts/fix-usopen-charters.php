<?php
/**
 * One-off content patch: US OPEN Charters and Transportation (80) was missing its
 * entire opening section - restored to match the real live page
 * (https://skylinecruises.com/us-open-charters-and-transportation/):
 * - Added the missing h2 "Your Premier US Open Party & Transport Venue" and its 5 real
 *   paragraphs (proximity to the tennis center, event flexibility, catering options,
 *   corporate transport), plus the "Contact Us For Info & Availability!" CTA.
 * - Added the missing h3 "All About the Skyline Princess" + intro paragraph directly
 *   above the existing (already-correct) ports checklist.
 * - Removed the empty <h2></h2> in the closing-cta (no real heading there on live).
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-usopen-charters.php";'
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

$id = 80;

// 1. Add the entire missing opening section before the photo-checklist-row.
$opening = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":2} --><h2>Your Premier US Open Party &amp; Transport Venue</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>As the <a href="https://www.usopen.org/index.html">US Open</a> approaches, finding the ideal venue that combines luxury, convenience, and versatility is paramount for an unforgettable experience. Look no further than <strong>Skyline Cruises</strong> and their magnificent yacht, the <em>Skyline Princess</em>.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Strategically docked at the <strong>World&#8217;s Fair Marina</strong>, we are literally minutes from the <em>USTA Billie Jean King National Tennis Center</em>, making us the undisputed perfect choice for US Open parties, seamless guest transportation, and distinguished corporate events. Imagine the ease of ferrying your guests directly from a stunning waterfront setting to the heart of the tennis action, avoiding the usual traffic and logistical headaches. Our prime location isn&#8217;t just a convenience; it&#8217;s a game-changer, ensuring your event runs smoothly from start to finish.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>The <strong>Skyline Princess</strong> offers an unparalleled setting for any <em>US Open-related occasion</em>. Whether you envision a vibrant pre-match celebration, an elegant post-match reception, or exclusive corporate hospitality, our yacht provides a sophisticated and adaptable space. With multiple decks, climate-controlled interiors, and breathtaking panoramic views of the <em>New York City skyline</em> and the very stadium where the <strong>US Open</strong> unfolds, your guests will be treated to an experience unlike any other. Furthermore, we offer complete flexibility with food and beverage options. You can opt for our exquisite in-house catering featuring a diverse menu crafted to impress or choose to bring in your preferred caterers &#8212; allowing for complete customization to fit your event&#8217;s specific needs and budget.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Contact Us For Info &amp; Availability!</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Beyond just parties, the <em>Skyline Princess</em> excels as a premier solution for corporate transports and efficient guest ferrying. Our ability to provide luxurious and comfortable transportation directly to and from the <strong>US Open</strong> grounds eliminates logistical stress, allowing your clients and guests to arrive refreshed and ready to enjoy the matches.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>This seamless integration of travel and event space, coupled with the option to include or exclude our top-tier food and beverage services, establishes <strong>Skyline Cruises</strong> as the definitive choice for elevating your US Open experience. Choose the <em>Skyline Princess</em> and let us transform your vision into an extraordinary reality, all from our unbeatable location at the World&#8217;s Fair Marina.</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
';

apply_fix( $id, '<!-- wp:group {"className":"photo-checklist-row"} -->', $opening . '<!-- wp:group {"className":"photo-checklist-row"} -->', 'US Open Charters (opening section)' );

// 2. Add "All About the Skyline Princess" heading + intro paragraph above the checklist.
apply_fix( $id,
	'<!-- wp:group {"className":"photo-checklist-row__list"} --><div class="wp-block-group photo-checklist-row__list">
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Flushing, Queens, NY</p><!-- /wp:paragraph -->',
	'<!-- wp:group {"className":"photo-checklist-row__list"} --><div class="wp-block-group photo-checklist-row__list">
<!-- wp:heading {"level":3} --><h3>All About the Skyline Princess</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>We sail from <a href="/departure-ports/">several ports</a> throughout the tri-state area. Our super yacht, the <strong>Skyline Princess</strong>, was specifically designed for year-round, all-weather operation. The entire vessel is fully climate-controlled. Our third deck can be open or completely enclosed, so no matter what the season, you have complete use of all levels, and your plans never have to change based on weather conditions.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Private Event Cruise Ports</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Flushing, Queens, NY</p><!-- /wp:paragraph -->',
	'US Open Charters (All About the Skyline Princess + Ports heading)'
);

// 3. Remove the empty h2 in closing-cta (no real heading there on live).
apply_fix( $id,
	'<!-- wp:heading {"level":2} --><h2></h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>For more information about our public NYC dinner cruises,',
	'<!-- wp:paragraph --><p>For more information about our public NYC dinner cruises,',
	'US Open Charters (remove empty heading)'
);

$saved = get_post( $id )->post_content;
echo "final checks:\n";
echo "  opening heading: " . ( str_contains( $saved, 'Your Premier US Open Party &amp; Transport Venue' ) ? 'yes' : 'NO' ) . "\n";
echo "  all about heading: " . ( str_contains( $saved, 'All About the Skyline Princess' ) ? 'yes' : 'NO' ) . "\n";
echo "  private ports heading: " . ( str_contains( $saved, 'Private Event Cruise Ports' ) ? 'yes' : 'NO' ) . "\n";
echo "  empty h2 removed: " . ( ! str_contains( $saved, '<h2></h2>' ) ? 'yes' : 'NO' ) . "\n";
