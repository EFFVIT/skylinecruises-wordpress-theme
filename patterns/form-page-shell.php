<?php
/**
 * Form page shell — Utility/Form category. Superseded 2026-09-15: the 2026-08-21 "keep every
 * third-party embed as-is" decision was reversed — hardcoded/third-party forms sitewide are being
 * consolidated onto GHL (see assets/js/ghl-form-embed.js for the embed contract: params-before-
 * mount, form_embed.js required, never a second iframe-resizer). This pattern's embed container
 * now takes a .ghl-form-embed placeholder with the real GHL form id for that page, not a pasted-
 * in third-party snippet. Real embed history (for reference — most of these are now replaced):
 *   - /contact-us/, /contact-us/request-your-quote/,
 *     /request-a-proposal/, /request-a-quote-special-occasion/ -> was EmailMeForm rUx0EcCWN5ZfX42,
 *                                                                   now GHL form ci4lPfPZnWiuuOYq9pMG
 *   - /sign-up-form/                                -> was Mailchimp, now GHL form mIDvPYGXwOFDNhOfLEGO
 *   - /school-events/school-cruise-quote-and-itinerary/ -> Infusionsoft/Keap form (school name +
 *                                                       trip-type fields) — NOT replaced, no GHL
 *                                                       form with matching fields provided yet
 *   - /contact-us/employment/                        -> no form, mailto instructions only
 */
return [
	'title'       => __( 'Form Page Shell', 'skyline-cruises' ),
	'description' => __( 'Hero + intro wrapping a GHL form embed placeholder (.ghl-form-embed).', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"form-page-shell"} -->
<div class="wp-block-group form-page-shell">
<!-- wp:group {"className":"form-page-shell__intro"} -->
<div class="wp-block-group form-page-shell__intro">
<!-- wp:heading {"level":2} -->
<h2>Request Your Quote</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Fill out the form below and our team will get back to you shortly.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="form-page-shell__embed">
<!-- TODO: set the real GHL form id for this specific page, e.g.
     <div class="ghl-form-embed" data-ghl-form-id="ci4lPfPZnWiuuOYq9pMG" data-ghl-form-name="Contact Page Form" style="min-height:620px"></div> -->
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->',
];
