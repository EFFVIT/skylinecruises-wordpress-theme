<?php
/**
 * One-off content patch: Graduation Cruises & Parties (410) restored to match the real
 * live page (https://skylinecruises.com/school-events/graduation-cruises-parties/):
 * - Added the real intro sentence before the ports list and split it into its real
 *   "Private and Public Cruises available" (2 ports) / "Private Cruises Only"
 *   (11 ports) groupings.
 * - Added the missing "Dockside events..." paragraph + the Gerry H. testimonial.
 * - Added h3 "Project Graduation Cruises" + its 2 real paragraphs.
 * - Added the missing "Cruise along the spectacular Manhattan Skyline..." paragraph.
 * - Added h3 "A Floating Banquet for Stellar Events" + its 2 real paragraphs.
 * - Added the missing Gail testimonial in the closing section.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-graduation-cruises.php";'
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

$id = 410;

// 1. Replace the whole ports-list block: add real intro sentence, group into
//    "Private and Public Cruises available" (2) / "Private Cruises Only" (11).
$old_ports = '<!-- wp:group {"className":"ports-list"} --><div class="wp-block-group ports-list"><h2>Select one of the following ports with their own special itineraries</h2><div class="ports-list__items"><div class="ports-list__item"><a href="/worlds-fair-marina/">The World&#8217;s Fair Marina</a> &#8211; Flushing, Queens, NY</div><div class="ports-list__item"><a href="/glen-cove-ferry-terminal/">Glen Cove Ferry Terminal</a> &#8211; Glen Cove, NY</div><div class="ports-list__item"><a href="/chelsea-piers/">Chelsea Piers</a> &#8211; Manhattan, New York (Private Cruises Only)</div><div class="ports-list__item"><a href="/pier-36/">Pier 36</a> &#8211; Downtown Manhattan, New York (Private Cruises Only)</div><div class="ports-list__item">Pier 81 &#8211; West 41st St and Hudson, New York (Private Cruises Only)</div><div class="ports-list__item"><a href="/liberty-landing-marina/">Liberty Landing Marina</a> &#8211; Jersey City, New Jersey (Private Cruises Only)</div><div class="ports-list__item">Hyatt Regency &#8211; Jersey City, New Jersey (Customers of Hyatt Regency Only) (Private Cruises Only)</div><div class="ports-list__item"><a href="/town-dock-park/">Town Dock Park</a> &#8211; Port Washington, Long Island (Private Cruises Only)</div><div class="ports-list__item"><a href="/ponus-yacht-club/">Ponus Yacht Club</a> &#8211; Stamford, CT (Private Cruises Only)</div><div class="ports-list__item"><a href="/veterans-memorial-park-marina/">Veteran&#8217;s Memorial Park Marina</a> &#8211; Norwalk, CT (Private Cruises Only)</div><div class="ports-list__item"><a href="/new-rochelle-municipal-marina/">Municipal Marina</a> &#8211; New Rochelle, New York (Private Cruises Only)</div><div class="ports-list__item"><a href="/lincoln-harbor/">Lincoln Harbor</a> &#8211; Weehawken, New Jersey (Private Cruises Only)</div><div class="ports-list__item"><a href="/yonkers-city-pier/">Yonkers City Pier</a> &#8211; Yonkers, New York (Private Cruises Only)</div></div></div><!-- /wp:group -->';

$new_ports = '<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:paragraph --><p>Skyline&#8217;s New York Harbor cruises feature iconic landmarks like the Statue of Liberty, the Brooklyn Bridge, and more. For private graduation cruises, select one of the following ports with their own special itineraries:</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"ports-list"} --><div class="wp-block-group ports-list">
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Private and Public Cruises available</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"ports-list__items"} --><div class="wp-block-group ports-list__items">
<div class="ports-list__item"><a href="/worlds-fair-marina/">The World&#8217;s Fair Marina</a> &#8211; Flushing, Queens, NY</div>
<div class="ports-list__item"><a href="/glen-cove-ferry-terminal/">Glen Cove Ferry Terminal</a> &#8211; Glen Cove, NY</div>
</div><!-- /wp:group -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Private Cruises Only</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"ports-list__items"} --><div class="wp-block-group ports-list__items">
<div class="ports-list__item"><a href="/chelsea-piers/">Chelsea Piers</a> &#8211; Manhattan, New York</div>
<div class="ports-list__item"><a href="/pier-36/">Pier 36</a> &#8211; Downtown Manhattan, New York</div>
<div class="ports-list__item">Pier 81 &#8211; West 41st St and Hudson, New York</div>
<div class="ports-list__item"><a href="/liberty-landing-marina/">Liberty Landing Marina</a> &#8211; Jersey City, New Jersey</div>
<div class="ports-list__item">Hyatt Regency &#8211; Jersey City, New Jersey (Customers of Hyatt Regency Only)</div>
<div class="ports-list__item"><a href="/town-dock-park/">Town Dock Park</a> &#8211; Port Washington, Long Island</div>
<div class="ports-list__item"><a href="/ponus-yacht-club/">Ponus Yacht Club</a> &#8211; Stamford, CT</div>
<div class="ports-list__item"><a href="/veterans-memorial-park-marina/">Veteran&#8217;s Memorial Park Marina</a> &#8211; Norwalk, CT</div>
<div class="ports-list__item"><a href="/new-rochelle-municipal-marina/">Municipal Marina</a> &#8211; New Rochelle, New York</div>
<div class="ports-list__item"><a href="/lincoln-harbor/">Lincoln Harbor</a> &#8211; Weehawken, New Jersey</div>
<div class="ports-list__item"><a href="/yonkers-city-pier/">Yonkers City Pier</a> &#8211; Yonkers, New York</div>
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:paragraph --><p>Dockside events are also available for those who want a unique event without sailing. Skyline&#8217;s event planners will work with you to plan a departure time that suits your school, including late-night and after-midnight Project Graduation cruises.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__quote"} --><p class="testimonial__quote">&#8220;I can sum up my classmates reaction to the cruise last Saturday night in one word &#8211; Fantastic! Every person aboard said it was the highlight of a great weekend full of memorable events. The skipper, the crew, wait staff, bartenders, etc. were awesome! They couldn&#8217;t do enough for us. All together they made it a night to remember. You can rest assured that we will pass the word to our follow classes that Skyline Cruises is the way to go.&#8221; &#8211; Gerry H.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Project Graduation Cruises</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Whether you&#8217;re planning a large or small event, the Skyline Cruises event planners will work with you to create a celebration that is fun and safe for your students.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>For prom-style events, you can have a sit-down dinner as elegant as you&#8217;d like &#8211; or go with a more casual event with a buffet and waiter-served hors d&#8217;oeuvres. Menus can range from foods that are teen favorites to something a bit more grown-up to make them feel like adults.</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->';

apply_fix( $id, $old_ports, $new_ports, 'Graduation Cruises (ports intro/grouping + Gerry H. + Project Graduation section)' );

// 2. Add the missing paragraph right after the entertainment-row chips, before the
//    Michele L. testimonial.
apply_fix( $id,
	'<div class="entertainment-row__chips"><span class="entertainment-chip">DJs</span><span class="entertainment-chip">Photo booth</span><span class="entertainment-chip">Caricaturist</span><span class="entertainment-chip">Magician</span><span class="entertainment-chip">And more&#8230;</span></div></div><!-- /wp:group -->',
	'<div class="entertainment-row__chips"><span class="entertainment-chip">DJs</span><span class="entertainment-chip">Photo booth</span><span class="entertainment-chip">Caricaturist</span><span class="entertainment-chip">Magician</span><span class="entertainment-chip">And more&#8230;</span></div></div><!-- /wp:group -->
<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:paragraph --><p>Cruise along the spectacular Manhattan Skyline, beautiful Long Island Sound and CT shore, or the scenic New Jersey coastline while dancing in our enclosed third deck dance floor and DJ space. The Skyline Princess can hold up to 450 passengers for events of various styles.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
	'Graduation Cruises (Cruise along the spectacular paragraph)'
);

// 3. Add "A Floating Banquet for Stellar Events" section after the Michele L.
//    testimonial, before the closing-cta.
apply_fix( $id,
	'<!-- wp:paragraph {"className":"testimonial__quote"} --><p class="testimonial__quote">&#8220;Thanks for everything! You are wonderful. I really appreciate everything you do for our school. I want to wish you a fantastic summer. Thanks again!!&#8221; &#8211; Sincerely, Michele L.</p><!-- /wp:paragraph -->

</div>
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Discuss Your Graduation Party with Us</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Give our event planners a call at (718) 446-1100 or contact us through the inquiry form. We look forward to making your graduation party one of a kind!</p><!-- /wp:paragraph -->',
	'<!-- wp:paragraph {"className":"testimonial__quote"} --><p class="testimonial__quote">&#8220;Thanks for everything! You are wonderful. I really appreciate everything you do for our school. I want to wish you a fantastic summer. Thanks again!!&#8221; &#8211; Sincerely, Michele L.</p><!-- /wp:paragraph -->

</div>
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":3} --><h3>A Floating Banquet for Stellar Events</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>While the Skyline Princess is specifically designed for year-round cruising and is fully climate-controlled, spring is when it really shines. Enjoy the gorgeous weather and let Skyline Cruises create graduation party memories for your students.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>To help plan your event, visit <a href="/the-ships-layout/">the ship&#8217;s layout</a> or take a look at our <a href="/picture-gallery/">photo gallery</a> of the Skyline Princess. The ship layout can feature whatever d&#233;cor and lighting combination suits your graduation cruise.</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Discuss Your Graduation Party with Us</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"testimonial__quote"} --><p class="testimonial__quote">&#8220;Thanks so much for everything. You guys outdid yourselves. The level of service was impeccable and I greatly appreciate it. Everyone is saying that its the best party they&#8217;ve ever attended.&#8221; &#8211; Thanks, Gail</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Give our event planners a call at (718) 446-1100 or contact us through the inquiry form. We look forward to making your graduation party one of a kind!</p><!-- /wp:paragraph -->',
	'Graduation Cruises (Floating Banquet section + Gail testimonial)'
);

$saved = get_post( $id )->post_content;
echo "final checks:\n";
echo "  ports grouping: " . ( str_contains( $saved, 'Private and Public Cruises available' ) ? 'yes' : 'NO' ) . "\n";
echo "  gerry h testimonial: " . ( str_contains( $saved, 'Gerry H.' ) ? 'yes' : 'NO' ) . "\n";
echo "  project graduation heading: " . ( str_contains( $saved, 'Project Graduation Cruises' ) ? 'yes' : 'NO' ) . "\n";
echo "  cruise along paragraph: " . ( str_contains( $saved, '450 passengers' ) ? 'yes' : 'NO' ) . "\n";
echo "  floating banquet heading: " . ( str_contains( $saved, 'A Floating Banquet for Stellar Events' ) ? 'yes' : 'NO' ) . "\n";
echo "  gail testimonial: " . ( str_contains( $saved, 'Thanks, Gail' ) ? 'yes' : 'NO' ) . "\n";
