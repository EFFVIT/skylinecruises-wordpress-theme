<?php
/**
 * One-off content patch: NYC Anniversary Dinner Cruises (78) restored to match the real
 * live page (https://skylinecruises.com/weddings/anniversary-cruise-nyc/), which had
 * roughly half its real body copy missing from the built page:
 * - Added "Celebrate Your Special Milestones with Skyline Cruises" h3 + paragraph, and
 *   the "Book Your Anniversary Celebration Today!" CTA (before the ports checklist).
 * - Added "Skyline Can Host A Private Celebration For Larger Groups" h3 + intro
 *   sentence directly above the existing (already-correct) 10-port checklist.
 * - Added the "Contact Us Today To Start Planning..." CTA, the entire "Renew Your
 *   Wedding Vows Beneath the Stars" section (2 real paragraphs), the "Need Help?..."
 *   CTA, the 3-menus/private-charter-capacity paragraph, and the closing
 *   event-planners paragraph - all entirely missing from the built page.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-anniversary-cruise.php";'
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

$id = 78;

// 1. Add the "Celebrate Your Special Milestones" section + CTA before the checklist row.
$section1 = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":3} --><h3>Celebrate Your Special Milestones with Skyline Cruises</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Skyline Cruises has an option for any size anniversary celebration. If you want a small, intimate celebration with just the two of you or a small gathering of friends and family, select one of our public dinner, lunch, or brunch cruises. If you wish, we can arrange a special table for just you and a few friends, a cake for your group, and whatever else you wish to make your anniversary celebration perfect. For a larger celebration, you can book an entire deck on one of our <a href="/nyc-dinner-cruises/">public dinner cruises</a> which depart from the World&#8217;s Fair Marina in Flushing, NY.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Book Your Anniversary Celebration Today!</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
';

apply_fix( $id, '<!-- wp:group {"className":"photo-checklist-row"} -->', $section1 . '<!-- wp:group {"className":"photo-checklist-row"} -->', 'Anniversary Cruise (Celebrate Milestones section)' );

// 2. Add "Skyline Can Host A Private Celebration For Larger Groups" heading + intro
//    sentence directly above the existing checklist items.
apply_fix( $id,
	'<!-- wp:group {"className":"photo-checklist-row__list"} --><div class="wp-block-group photo-checklist-row__list">
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />The World\'s Fair Marina, Flushing, Queens, NY</p><!-- /wp:paragraph -->',
	'<!-- wp:group {"className":"photo-checklist-row__list"} --><div class="wp-block-group photo-checklist-row__list">
<!-- wp:heading {"level":3} --><h3>Skyline Can Host A Private Celebration For Larger Groups</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>For large groups, plan a <a href="/yacht-charter/">custom cruise</a> on either gorgeous Long Island Sound or the famous New York Harbor. Private yacht charter cruises can depart from:</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />The World\'s Fair Marina, Flushing, Queens, NY</p><!-- /wp:paragraph -->',
	'Anniversary Cruise (Larger Groups heading)'
);

// 3. Add all the missing sections between the checklist row and the features-pair:
//    Contact CTA -> Renew Your Wedding Vows -> Need Help CTA -> menus paragraph ->
//    closing event-planners paragraph.
$missing = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Contact Us Today To Start Planning Your Anniversary Celebration!</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":2} --><h2>Renew Your Wedding Vows Beneath the Stars</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>If you would like your anniversary party to include a vow renewal, the Skyline Princess is a perfect place to do it. Our enclosed third deck features spectacular views that will provide the ideal backdrop for pledging your love all over again. Thanks to a fully climate-controlled cabin, you can throw your anniversary party on the Skyline Princess at any time of the year and your guests will be comfortable.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Watch the sunset and take photos with stunning New York landmarks in the background, including the Empire State Building, South Street Seaport, and Statue of Liberty. We even have our own officiant, <a href="/captain-arnold-wedding-officiant/">Captain Arnold</a>, to conduct the ceremony if you wish.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Need Help? Contact Us To Plan Your Perfect Anniversary Celebration!</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Skyline Cruises offers three different menus &#8211; <a href="/nyc-brunch-cruise-buffet-menu/">brunch</a>, <a href="/nyc-lunch-cruise-buffet-menu/">lunch</a>, or <a href="/nyc-dinner-cruise-buffet-menu/">dinner</a>. Private charters accommodate groups of sizes 15 to 450 staged as cocktail receptions, a buffet, or sit-down meal. Our third level all-weather rooftop deck features a dance floor and DJ space so you can dance the night away. No matter the type of anniversary party you envision to commemorate your love, Skyline Cruise&#8217;s event planners will make it a day you&#8217;ll always remember.</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
';

apply_fix( $id, '<!-- wp:group {"className":"features-pair"} -->', $missing . '<!-- wp:group {"className":"features-pair"} -->', 'Anniversary Cruise (vow renewal + closing sections)' );

// 4. Add the closing event-planners paragraph right after Smooth Sailing (inside
//    the features-pair item 2's paragraph, appended, since it directly follows Smooth
//    Sailing on live).
apply_fix( $id,
	'Our yacht is a floating banquet room designed for year-round cruising. It is fully climate-controlled for year-round outings and because it sails in the calm, sheltered waters of the famous New York Harbor or Long Island Sound, you’ll experience smooth sailing.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"route-map"} -->
<div class="wp-block-group route-map">
<!-- wp:heading {"level":2} --><h2>Our Cruise Route</h2><!-- /wp:heading -->
<!-- wp:html -->
<div class="route-map__canvas" data-departure="{&quot;lat&quot;:40.7591,&quot;lng&quot;:-73.8459,&quot;label&quot;:&quot;World&#039;s Fair Marina — Departure&quot;}" data-landmarks="[{&quot;lat&quot;:40.7061,&quot;lng&quot;:-73.9969,&quot;label&quot;:&quot;Brooklyn Bridge&quot;},{&quot;lat&quot;:40.7127,&quot;lng&quot;:-74.0134,&quot;label&quot;:&quot;One World Trade Center&quot;},{&quot;lat&quot;:40.6995,&quot;lng&quot;:-74.0396,&quot;label&quot;:&quot;Ellis Island&quot;},{&quot;lat&quot;:40.6892,&quot;lng&quot;:-74.0445,&quot;label&quot;:&quot;Statue of Liberty&quot;}]" data-route-path="[{&quot;lat&quot;:40.7591,&quot;lng&quot;:-73.8459},{&quot;lat&quot;:40.7825,&quot;lng&quot;:-73.8802},{&quot;lat&quot;:40.7823,&quot;lng&quot;:-73.9165},{&quot;lat&quot;:40.7648,&quot;lng&quot;:-73.9385},{&quot;lat&quot;:40.7527,&quot;lng&quot;:-73.961},{&quot;lat&quot;:40.7143,&quot;lng&quot;:-73.9725},{&quot;lat&quot;:40.7061,&quot;lng&quot;:-73.9969},{&quot;lat&quot;:40.7009,&quot;lng&quot;:-74.0135},{&quot;lat&quot;:40.6892,&quot;lng&quot;:-74.0445}]" role="img" aria-label="Map of the Skyline Cruises route from World\'s Fair Marina along the East River past Brooklyn Bridge, One World Trade Center, Ellis Island, Statue of Liberty"></div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->',
	"Our yacht is a floating banquet room designed for year-round cruising. It is fully climate-controlled for year-round outings and because it sails in the calm, sheltered waters of the famous New York Harbor or Long Island Sound, you’ll experience smooth sailing.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {\"className\":\"text-section\"} --><div class=\"wp-block-group text-section\"><!-- wp:paragraph --><p>Talk to one of our experienced Skyline Cruises event planners to discuss what type of Skyline event would work best for your anniversary. If you are looking to book a private party or a corporate event, keep Skyline Cruises in mind. We have great custom packages for any size event. Call us at (718) 446-1100 and let us make your anniversary a truly memorable one that you’ll both always remember.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {\"className\":\"route-map\"} -->
<div class=\"wp-block-group route-map\">
<!-- wp:heading {\"level\":2} --><h2>Our Cruise Route</h2><!-- /wp:heading -->
<!-- wp:html -->
<div class=\"route-map__canvas\" data-departure=\"{&quot;lat&quot;:40.7591,&quot;lng&quot;:-73.8459,&quot;label&quot;:&quot;World&#039;s Fair Marina — Departure&quot;}\" data-landmarks=\"[{&quot;lat&quot;:40.7061,&quot;lng&quot;:-73.9969,&quot;label&quot;:&quot;Brooklyn Bridge&quot;},{&quot;lat&quot;:40.7127,&quot;lng&quot;:-74.0134,&quot;label&quot;:&quot;One World Trade Center&quot;},{&quot;lat&quot;:40.6995,&quot;lng&quot;:-74.0396,&quot;label&quot;:&quot;Ellis Island&quot;},{&quot;lat&quot;:40.6892,&quot;lng&quot;:-74.0445,&quot;label&quot;:&quot;Statue of Liberty&quot;}]\" data-route-path=\"[{&quot;lat&quot;:40.7591,&quot;lng&quot;:-73.8459},{&quot;lat&quot;:40.7825,&quot;lng&quot;:-73.8802},{&quot;lat&quot;:40.7823,&quot;lng&quot;:-73.9165},{&quot;lat&quot;:40.7648,&quot;lng&quot;:-73.9385},{&quot;lat&quot;:40.7527,&quot;lng&quot;:-73.961},{&quot;lat&quot;:40.7143,&quot;lng&quot;:-73.9725},{&quot;lat&quot;:40.7061,&quot;lng&quot;:-73.9969},{&quot;lat&quot;:40.7009,&quot;lng&quot;:-74.0135},{&quot;lat&quot;:40.6892,&quot;lng&quot;:-74.0445}]\" role=\"img\" aria-label=\"Map of the Skyline Cruises route from World's Fair Marina along the East River past Brooklyn Bridge, One World Trade Center, Ellis Island, Statue of Liberty\"></div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->",
	'Anniversary Cruise (closing event-planners paragraph)'
);

$saved = get_post( $id )->post_content;
echo "final checks:\n";
echo "  milestones section: " . ( str_contains( $saved, 'Celebrate Your Special Milestones with Skyline Cruises' ) ? 'yes' : 'NO' ) . "\n";
echo "  larger groups heading: " . ( str_contains( $saved, 'Skyline Can Host A Private Celebration For Larger Groups' ) ? 'yes' : 'NO' ) . "\n";
echo "  vow renewal section: " . ( str_contains( $saved, 'Renew Your Wedding Vows Beneath the Stars' ) ? 'yes' : 'NO' ) . "\n";
echo "  closing event-planners paragraph: " . ( str_contains( $saved, 'Talk to one of our experienced Skyline Cruises event planners' ) ? 'yes' : 'NO' ) . "\n";
