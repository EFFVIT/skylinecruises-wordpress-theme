<?php
/**
 * One-off content patch, batch 2: several small missing-sentence/testimonial fixes from the
 * 2026-08-25 site-wide copy audit, each independent and verified against the live source.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-batch-2.php";'
 */

function apply_fix( $id, $old, $new, $label ) {
	$post = get_post( $id );
	if ( ! $post ) {
		echo "$label ($id): post not found\n";
		return false;
	}
	if ( ! str_contains( $post->post_content, $old ) ) {
		echo "$label ($id): OLD TEXT NOT FOUND\n";
		return false;
	}
	$new_content = str_replace( $old, $new, $post->post_content );
	kses_remove_filters();
	$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $new_content ) ], true );
	kses_init_filters();
	if ( is_wp_error( $result ) ) {
		echo "$label ($id): update error " . $result->get_error_message() . "\n";
		return false;
	}
	echo "$label ($id): fixed\n";
	return true;
}

// 1. Prom & After Prom Cruises (411): missing intro sentence before ports list.
apply_fix( 411,
	'<!-- wp:group {"className":"ports-list"} --><div class="wp-block-group ports-list"><h2>Available departure ports include</h2>',
	'<!-- wp:group {"className":"ports-list"} --><div class="wp-block-group ports-list"><p>We can cruise the waters of New York Harbor or the Long Island Sound with departure locations in New York, New Jersey, and Connecticut for full ship charters.</p><h2>Available departure ports include</h2>',
	'Prom (intro sentence)'
);

// 2. Prom & After Prom Cruises (411): missing closing paragraph right after the ports list,
//    before "Smooth Sailing" (matching live's real order).
apply_fix( 411,
	'<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:heading {"level":2} --><h2>Smooth Sailing</h2>',
	'<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:paragraph --><p>For a unique prom party location, choose the Skyline Princess. Our professional staff is waiting to customize your school party. Prime dates sell out fast so call our expert event planners today at (718) 446-1100 or <a href="/contact-us/">use this form to contact Skyline Cruises today</a> to customize your experience.</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:heading {"level":2} --><h2>Smooth Sailing</h2>',
	'Prom (closing paragraph)'
);

// 3. College Alumni Cruises (413): missing CTA line right after the testimonial, before closing-cta.
apply_fix( 413,
	'</p><!-- /wp:paragraph -->

</div>
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"closing-cta"} -->',
	'</p><!-- /wp:paragraph -->

</div>
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Skyline will make your day special. Book now!</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"closing-cta"} -->',
	'College Alumni (CTA line)'
);

// 4. Fundraiser Cruises (404): missing testimonial (Jim McCann), right before the cash-bar text-section.
apply_fix( 404,
	'<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">

<!-- wp:paragraph --><p>Skyline&#8217;s attentive staff serves premium beer, wines, and liquor at the cash bar.',
	'<!-- wp:group {"className":"testimonial-outer"} -->
<div class="wp-block-group testimonial-outer">
<div class="testimonial testimonial--card">
<!-- wp:heading {"level":2,"className":"testimonial__heading"} --><h2 class="testimonial__heading">Skyline <em>Testimonials</em></h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"testimonial__quote"} --><p class="testimonial__quote">&#8220;Fantastic evening &#8211; could not have been better! The food, as well as the service, was exceptional for our business event. Thank you for making this a memorable evening for our team!&#8221;</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- Jim McCann</p><!-- /wp:paragraph -->
</div>
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">

<!-- wp:paragraph --><p>Skyline&#8217;s attentive staff serves premium beer, wines, and liquor at the cash bar.',
	'Fundraiser Cruises (testimonial)'
);

// 5. Bridal Showers (397): missing closing tagline sentence.
apply_fix( 397,
	'<!-- wp:paragraph --><p>When you have your bridal shower cruise aboard the Skyline Princess, you can relax and have fun at your party &#8211; secure in the knowledge that our staff and event planners have all the details handled for you. Contact us today and let&#8217;s plan an incredible shower. When you&#8217;re ready to impress, call Skyline at (718) 446-1100 or contact us online.</p><!-- /wp:paragraph -->',
	'<!-- wp:paragraph --><p>When you have your bridal shower cruise aboard the Skyline Princess, you can relax and have fun at your party &#8211; secure in the knowledge that our staff and event planners have all the details handled for you. Contact us today and let&#8217;s plan an incredible shower. When you&#8217;re ready to impress, call Skyline at (718) 446-1100 or contact us online.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Skyline Cruises will make your bridal shower special&#8230; so book your event now!</p><!-- /wp:paragraph -->',
	'Bridal Showers (closing tagline)'
);

// 6. Cruise Catering (408): missing second testimonial (Christopher L.), same quote block.
apply_fix( 408,
	'<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- Bobby Fayder</p><!-- /wp:paragraph -->',
	'<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- Bobby Fayder</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__quote"} --><p class="testimonial__quote">&#8220;Wow what a great service&#8230;and the food is spectacular&#8221;</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- Christopher L.</p><!-- /wp:paragraph -->',
	'Cruise Catering (2nd testimonial)'
);

// 7. NYC Cruise Discounts (336): missing CTA line right after the cash-bar paragraph.
apply_fix( 336,
	"<!-- wp:paragraph --><p>Skyline's attentive staff also serves premium beer, wine and liquor at the cash bar. Want something special? Contact the event experts at Skyline to customize your experience.</p><!-- /wp:paragraph -->",
	"<!-- wp:paragraph --><p>Skyline's attentive staff also serves premium beer, wine and liquor at the cash bar. Want something special? Contact the event experts at Skyline to customize your experience.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {\"className\":\"text-section__cta-line\"} --><p class=\"text-section__cta-line\">Skyline will make your day special. Book now!</p><!-- /wp:paragraph -->",
	'NYC Cruise Discounts (CTA line)'
);

// 8. FAQ (338): Q10's answer is missing its entire "LI Sound" second half.
apply_fix( 338,
	'<p>NY Skyline – The majestic Manhattan Skyline, Ellis Island, the South Street Seaport, Brooklyn Bridge and the Statue of Liberty are just a few of the sights viewed on our Skyline Cruises.</p></details>',
	'<p>NY Skyline – The majestic Manhattan Skyline, Ellis Island, the South Street Seaport, Brooklyn Bridge and the Statue of Liberty are just a few of the sights viewed on our Skyline Cruises. LI Sound – On our cruises of Long Island Sound from Connecticut, Westchester, and Long Island sights include the majestic harbors of Connecticut and the North Sore of Long Island filled with beautiful yachts of all kinds. You will also see lighthouses and some of the largest and most inspiring mansions in the world.</p></details>',
	'FAQ Q10 (LI Sound half)'
);

echo "\nDone.\n";
