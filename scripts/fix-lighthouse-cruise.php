<?php
/**
 * One-off content patch: Long Island Lighthouse Cruise (77) restored to match the real
 * live page (https://skylinecruises.com/nyc-party-cruises/lighthouse-cruise/):
 * - Added the missing h3 "Skyline Lighthouse Cruise Itinerary includes:" heading before
 *   the itinerary checklist.
 * - Added the missing mansion paragraph ("Typically done as a daytime cruise...").
 * - Added the missing "Check Out Our Schedule For Our Next Lighthouse Cruise!" CTA line.
 * - Added the missing real paragraph about the climate-controlled ship/rooftop deck,
 *   which was entirely absent from the built page.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-lighthouse-cruise.php";'
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

$id = 77;

// 1. Add the missing itinerary heading right before the first checklist item.
apply_fix( $id,
	'<!-- wp:group {"className":"photo-checklist-row__list"} --><div class="wp-block-group photo-checklist-row__list">
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Fort Schuyler</p><!-- /wp:paragraph -->',
	'<!-- wp:group {"className":"photo-checklist-row__list"} --><div class="wp-block-group photo-checklist-row__list">
<!-- wp:heading {"level":3} --><h3>Skyline Lighthouse Cruise Itinerary includes:</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"checklist-item"} --><p class="checklist-item"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Fort Schuyler</p><!-- /wp:paragraph -->',
	'Lighthouse Cruise (itinerary heading)'
);

// 2. Add the missing mansion paragraph + CTA line + climate-controlled-ship paragraph,
//    all between the photo-checklist-row block and the features-pair section.
$missing_sections = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:paragraph --><p>Typically done as a daytime cruise, the Skyline Princess lighthouse brunch or lunch cruises visit three historic Long Island Sound lighthouses. Your sightseeing voyage includes views of Long Island&#8217;s famed Gold Coast mansions and other coastal landmarks.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Check Out Our Schedule For Our Next Lighthouse Cruise!</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>The Skyline Princess is a fully climate-controlled ship with three spacious decks, including an enclosed, all-weather rooftop deck that allows for magnificent views and the comfort you desire on your lighthouse tour &#8211; rain or shine. Our top deck also features a dance floor and DJ space if you get tired of the spectacular view. While taking in the beauty of the spectacular landscape, enjoy a delicious meal prepared by our onboard chef.</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
';

apply_fix( $id,
	'<!-- wp:group {"className":"features-pair"} -->
<div class="wp-block-group features-pair">
<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-service.png" alt="Memorable Service" /></figure><!-- /wp:image --><!-- wp:paragraph --><p>Skyline',
	$missing_sections . '<!-- wp:group {"className":"features-pair"} -->
<div class="wp-block-group features-pair">
<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-service.png" alt="Memorable Service" /></figure><!-- /wp:image --><!-- wp:paragraph --><p>Skyline',
	'Lighthouse Cruise (missing mansion/CTA/climate paragraphs)'
);

$saved = get_post( $id )->post_content;
echo "final checks:\n";
echo "  itinerary heading: " . ( str_contains( $saved, 'Skyline Lighthouse Cruise Itinerary includes:' ) ? 'yes' : 'NO' ) . "\n";
echo "  mansion paragraph: " . ( str_contains( $saved, 'Gold Coast mansions and other coastal landmarks' ) ? 'yes' : 'NO' ) . "\n";
echo "  schedule cta: " . ( str_contains( $saved, 'Check Out Our Schedule For Our Next Lighthouse Cruise!' ) ? 'yes' : 'NO' ) . "\n";
echo "  climate paragraph: " . ( str_contains( $saved, 'fully climate-controlled ship with three spacious decks' ) ? 'yes' : 'NO' ) . "\n";
