<?php
/**
 * Hero — every page's first block. Parameterized in practice by the REST API bulk script
 * (H1, optional address subhead for Port pages, CTA label, background photo, trust badges).
 * This registered pattern uses the "02 - NYC Dinner Cruises" pilot page as its example content.
 *
 * Confirmed via direct Figma read (node 109:9 "Photo Card"): the hero photo is a rounded card
 * inset 28px on every side of the hero frame, border-radius 23px — not a flush full-bleed
 * rectangle. ".hero-outer" provides the inset/page-bg frame; ".hero" is the rounded card itself.
 */
return [
	'title'       => __( 'Hero (Cruise Page)', 'skyline-cruises' ),
	'description' => __( 'Rounded-card photo hero (inset 28px, border-radius 23px, per confirmed Figma spec) with H1, trust badges, and a primary CTA. First block on every page.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"hero-outer"} -->
<div class="wp-block-group hero-outer">
<div class="hero" style="background-image:url(' . esc_url( get_template_directory_uri() . '/assets/images/hero-placeholder.jpg' ) . ')">
<div class="hero__content">
<!-- wp:heading {"level":1} -->
<h1>NYC Dinner Cruises</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"trust-badges"} -->
<p class="trust-badges"><span class="trust-badge"><span>Since 1993</span></span><span class="trust-badge"><span>A+ BBB Rating</span></span><span class="trust-badge"><span>30+ Years Excellence</span></span></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"hero__cta"} -->
<div class="wp-block-buttons hero__cta">
<!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">Book Now</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
</div>
</div>
<!-- /wp:group -->',
];
