<?php
/**
 * Form page shell — Utility/Form category. Per the user's explicit decision (2026-08-21), the
 * existing third-party forms are kept as-is, not consolidated onto one form plugin. This pattern
 * only provides the new page design (intro copy + embed container) around whichever real embed
 * belongs on that specific page — the embed itself must be pasted in verbatim when the page is
 * actually composed, never fabricated here. Real embed identifiers confirmed via live-site audit
 * (2026-08-20), for reference when composing each real page:
 *   - /contact-us/                                  -> EmailMeForm embed id rUx0EcCWN5ZfX42
 *   - /contact-us/request-your-quote/               -> EmailMeForm embed id rUx0EcCWN5ZfX42 (main)
 *                                                       + a second EmailMeForm id tA8789pefQi08Ulw (sidebar)
 *   - /sign-up-form/                                -> Mailchimp form, posts to skylinecruises.us20.list-manage.com
 *   - /school-events/school-cruise-quote-and-itinerary/ -> Infusionsoft/Keap form, posts to skyline.infusionsoft.com/app/form/process/...
 *   - /contact-us/employment/                        -> no form, mailto instructions only
 */
return [
	'title'       => __( 'Form Page Shell', 'skyline-cruises' ),
	'description' => __( 'Hero + intro wrapping an existing third-party form embed (EmailMeForm/Mailchimp/Infusionsoft), kept as-is per client decision.', 'skyline-cruises' ),
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
<!-- TODO: paste the real, existing third-party form embed for this specific page here, verbatim.
     Do not replace with a different form vendor per the 2026-08-21 decision to keep forms as-is. -->
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->',
];
