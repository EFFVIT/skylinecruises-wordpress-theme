<?php
/**
 * FAQ accordion — About/Info category, /about/faq/. The live site currently renders 21 flat
 * <p class="question">/<p class="answer"> pairs with no JS toggle at all. This upgrades that into
 * a real accordion using native <details>/<summary> — no custom JS needed, works everywhere,
 * fully accessible by default. Real question/answer copy still needs to be pulled verbatim from
 * the live page when this is composed into an actual page (not invented here).
 */
$faqs = [
	[
		'q' => 'How far in advance should I book my cruise?',
		'a' => 'We recommend booking as early as possible, especially for holidays and weekends, as cruises sell out quickly.',
	],
	[
		'q' => 'What should I wear?',
		'a' => 'Casual, comfortable attire is fine for most public cruises. Check your specific cruise page for any dress code details.',
	],
	[
		'q' => 'Is parking available?',
		'a' => "Yes, parking is available at our home port, World's Fair Marina, and at most of our departure locations.",
	],
];

$items_markup = '';
foreach ( $faqs as $faq ) {
	$items_markup .= '<details class="faq-item"><summary>' . esc_html( $faq['q'] ) . '</summary><p>' . esc_html( $faq['a'] ) . '</p></details>
';
}

return [
	'title'       => __( 'FAQ Accordion', 'skyline-cruises' ),
	'description' => __( 'Native details/summary accordion, replacing the live site\'s flat non-JS Q&A pairs. About/Info category.', 'skyline-cruises' ),
	'categories'  => [ 'skyline-sections' ],
	'content'     => '<!-- wp:group {"className":"faq-accordion"} -->
<div class="wp-block-group faq-accordion">
<!-- wp:heading {"level":2} -->
<h2>Frequently Asked Questions</h2>
<!-- /wp:heading -->
<!-- wp:html -->
' . $items_markup . '<!-- /wp:html -->
</div>
<!-- /wp:group -->',
];
