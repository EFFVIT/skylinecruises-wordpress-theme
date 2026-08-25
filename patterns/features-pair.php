<?php
/**
 * Features pair — real cash-bar/customize caption + "Smooth Sailing" icon pair. Used on Public
 * Cruise Service, Buffet Menu, and Private Event/Party pages only — confirmed absent on School
 * Events, Graduation, and Port/Location pages. Not a truly global pattern, so it's opt-in per
 * page, not in page.php.
 *
 * Real bug found in the 2026-08-25 site-wide copy audit: this block was invented at build time,
 * not scraped, and both captions were wrong. Live-checked directly (multiple pages, e.g.
 * /nyc-dinner-cruises/): the "Memorable Service" icon has NO visible caption heading on live at
 * all — that string only ever exists as the icon's alt attribute. The real text in that visual
 * slot is Skyline's actual cash-bar/customize paragraph. "Smooth Sailing" IS a real heading,
 * reused verbatim across multiple live pages, but its caption was rewritten into non-verbatim
 * wording. Both fixed here using the verbatim live text.
 */
$service_icon = get_template_directory_uri() . '/assets/icons/feature-service.png';
$sailing_icon = get_template_directory_uri() . '/assets/icons/feature-sailing.png';

return [
	'title'       => __( 'Features Pair', 'skyline-cruises' ),
	'description' => __( 'Cash-bar/customize + Smooth Sailing two-up icon feature row.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"features-pair"} -->
<div class="wp-block-group features-pair">
<!-- wp:group {"className":"features-pair__item"} -->
<div class="wp-block-group features-pair__item">
<!-- wp:image {"sizeSlug":"full"} -->
<figure class="wp-block-image"><img src="' . esc_url( $service_icon ) . '" alt="Memorable Service" /></figure>
<!-- /wp:image -->
<!-- wp:paragraph -->
<p>Skyline&#8217;s attentive staff serves premium beer, wine, and liquor at the cash bar. Want something special? <a href="https://skylinecruises.com/contact-us/">Contact the event experts at Skyline</a> to customize your experience.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"features-pair__item"} -->
<div class="wp-block-group features-pair__item">
<!-- wp:image {"sizeSlug":"full"} -->
<figure class="wp-block-image"><img src="' . esc_url( $sailing_icon ) . '" alt="Smooth Sailing" /></figure>
<!-- /wp:image -->
<!-- wp:heading {"level":3} -->
<h3>Smooth Sailing</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Our yacht is a floating banquet room designed for year-round cruising. It is fully climate-controlled for year-round outings and because it sails in the calm, sheltered waters of the famous New York Harbor or Long Island Sound, you&#8217;ll experience smooth sailing.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
];
