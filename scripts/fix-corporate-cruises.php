<?php
/**
 * One-off content patch: Corporate Cruises (page 402) — restores the real "Private Corporate
 * Event Cruise Ports" list and the real Virginia Gregal / J.P. Morgan testimonial, both scraped
 * verbatim from https://skylinecruises.com/corporate-cruises/ during the 2026-08-25 audit.
 *
 * The existing 2-item "Fundraisers / Connecticut Corporate Cruises" link list is replaced by the
 * real ports list in the same position (right after the intro, matching live's real page order)
 * — those 2 real sub-pages are still linked from the nav and from elsewhere, so nothing is lost,
 * just no longer standing in for content that belongs there instead.
 *
 * Ports that have no matching page on this site (Pier 81, Marina Del Rey, Hyatt Regency) are
 * listed as plain text, not linked — matching the live page exactly, which also leaves these
 * three unlinked (no <a> tag) rather than inventing a destination.
 *
 * Run inside the container: wp --allow-root --path=/var/www/html eval-file fix-corporate-cruises.php
 */

$id = 402;
$post = get_post( $id );
$content = $post->post_content;

$old_linklist = '<!-- wp:group {"className":"link-list"} -->
<div class="wp-block-group link-list">

<!-- wp:list --><ul class="link-list__items">
<li><a href="/fundraiser-cruises/">Fundraisers</a></li>
<li><a href="/connecticut-corporate-cruises/">Connecticut Corporate Cruises</a></li>
</ul><!-- /wp:list -->
</div>
<!-- /wp:group -->';

$new_ports_section = '<!-- wp:group {"className":"ports-list"} -->
<div class="wp-block-group ports-list">
<!-- wp:heading {"level":3} --><h3>Private Corporate Event Cruise Ports</h3><!-- /wp:heading -->

<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Private Charters and Public Cruises</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"ports-list__items"} --><div class="wp-block-group ports-list__items">
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/worlds-fair-marina/">The World&#8217;s Fair Marina</a> &#8211; Flushing, Queens, NY</div>
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/glen-cove-ferry-terminal/">Glen Cove Ferry Terminal</a> &#8211; Glen Cove, NY <span class="private-flag">Private Charters Only</span></div>
</div><!-- /wp:group -->

<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">New York City <span class="private-flag">Private Only</span></p><!-- /wp:paragraph -->
<!-- wp:group {"className":"ports-list__items"} --><div class="wp-block-group ports-list__items">
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/chelsea-piers/">Chelsea Piers</a> &#8211; Manhattan</div>
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/pier-36/">Pier 36</a> &#8211; Downtown Manhattan</div>
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Pier 81 &#8211; Midtown Manhattan</div>
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Marina Del Rey &#8211; Bronx</div>
</div><!-- /wp:group -->

<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">New Jersey <span class="private-flag">Private Only</span></p><!-- /wp:paragraph -->
<!-- wp:group {"className":"ports-list__items"} --><div class="wp-block-group ports-list__items">
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/liberty-landing-marina/">Liberty Landing Marina</a> &#8211; Jersey City</div>
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Hyatt Regency &#8211; Jersey City (HR Customers only)</div>
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/lincoln-harbor/">Lincoln Harbor</a> &#8211; Weehawken</div>
</div><!-- /wp:group -->

<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Long Island <span class="private-flag">Private Only</span></p><!-- /wp:paragraph -->
<!-- wp:group {"className":"ports-list__items"} --><div class="wp-block-group ports-list__items">
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/town-dock-park/">PW Town Dock</a> &#8211; Port Washington</div>
</div><!-- /wp:group -->

<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Westchester <span class="private-flag">Private Only</span></p><!-- /wp:paragraph -->
<!-- wp:group {"className":"ports-list__items"} --><div class="wp-block-group ports-list__items">
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/new-rochelle-municipal-marina/">Municipal Marina</a> &#8211; New Rochelle</div>
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/yonkers-city-pier/">Yonkers City Pier</a> &#8211; Yonkers</div>
</div><!-- /wp:group -->

<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Connecticut <span class="private-flag">Private Only</span></p><!-- /wp:paragraph -->
<!-- wp:group {"className":"ports-list__items"} --><div class="wp-block-group ports-list__items">
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/ponus-yacht-club/">Ponus Yacht Club</a> &#8211; Stamford</div>
<div class="ports-list__item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" /><a href="/veterans-memorial-park-marina/">Veteran&#8217;s Memorial Park Marina</a> &#8211; Norwalk</div>
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->';

if ( ! str_contains( $content, $old_linklist ) ) {
	echo "OLD link-list block not found verbatim — aborting.\n";
	exit( 1 );
}
$content = str_replace( $old_linklist, $new_ports_section, $content );

// Add the missing second testimonial (Virginia Gregal, J.P. Morgan) right after the existing
// Scott Herman testimonial block, as a plain quote — matching how it appears on live (a plain
// paragraph + attribution, not the photo-card testimonial treatment used for the first one).
$old_after_testimonial = '<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- Scott Herman, Vice President &amp; General Manager, 1010 WINS</p><!-- /wp:paragraph -->
</div>
</div>
<!-- /wp:group -->';

$new_after_testimonial = '<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- Scott Herman, Vice President &amp; General Manager, 1010 WINS</p><!-- /wp:paragraph -->
</div>
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:paragraph --><p>&#8220;Just wanted to let you know that we were very pleased with the Skyline Princess, its staff, the food, drinks, cake etc. We were also very pleased with the Ma&#238;tre D&#8217;, who was very attentive to our needs and requests. It was a pleasure working with you on this event. Everything far exceeded my expectations and people are still talking about the party to this day. I want to thank you and applaud you all!&#8221;</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">&#8212; Virginia Gregal, J.P. Morgan Investment</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->';

if ( ! str_contains( $content, $old_after_testimonial ) ) {
	echo "OLD testimonial-end block not found verbatim — aborting.\n";
	exit( 1 );
}
$content = str_replace( $old_after_testimonial, $new_after_testimonial, $content );

kses_remove_filters();
$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $content ) ], true );
kses_init_filters();
if ( is_wp_error( $result ) ) {
	echo 'ERROR: ' . $result->get_error_message() . "\n";
	exit( 1 );
}

$saved = get_post( $id )->post_content;
echo 'Verify: ports list present=' . ( str_contains( $saved, 'Private Corporate Event Cruise Ports' ) ? 'yes' : 'NO' ) . "\n";
echo 'Verify: Virginia Gregal testimonial present=' . ( str_contains( $saved, 'Virginia Gregal' ) ? 'yes' : 'NO' ) . "\n";
echo 'Verify: old link-list gone=' . ( str_contains( $saved, '>Fundraisers</a>' ) ? 'NO(still there)' : 'yes' ) . "\n";
