<?php
/**
 * One-off content patch: adds a "Learn More" link to each of the 5 Events We Cater cards on the
 * already-live homepage (page 454). build-pages.js has no upsert-by-slug logic — it only ever
 * creates new pages — so an already-pushed page's content has to be patched directly like this.
 *
 * kses_remove_filters()/kses_init_filters() wrap is this project's standing rule for any one-off
 * script that calls wp_update_post() on a page that has (or might have, anywhere on the page)
 * raw <svg>/<form>/<input>/<iframe> — this homepage's "Have Questions?" section has inline SVG
 * pin icons elsewhere in the SAME post_content, so a bare wp_update_post() here would silently
 * strip them as collateral damage on save.
 *
 * Run inside the container: wp --allow-root --path=/var/www/html eval-file add-events-cater-links.php
 */

$post_id = 454;
$post = get_post( $post_id );
if ( ! $post ) {
	echo "Post $post_id not found\n";
	exit( 1 );
}

$content = $post->post_content;

$links = [
	'Weddings'                 => '/weddings/',
	'Corporate Cruises'        => '/corporate-cruises/',
	'Private Parties'          => '/nyc-party-cruises/',
	'Holiday Cruises'          => '/nyc-holiday-cruises/',
	'NYC Private Event Cruises' => '/the-great-escape-yacht-rental/',
];

$replacements = 0;
foreach ( $links as $title => $href ) {
	// Match this specific card's <h3>...</h3><p>...</p> block and insert the link right after
	// the </p>, before the card-hover div closes — anchored on the real heading text so each
	// replacement is unique and can't accidentally match a different card.
	$pattern = '#(<h3>' . preg_quote( $title, '#' ) . '</h3><p>.*?</p>)(</div></div>)#s';
	$new_content = preg_replace(
		$pattern,
		'$1<a href="' . esc_url( $href ) . '">Learn More &rarr;</a>$2',
		$content,
		1,
		$count
	);
	if ( $count === 1 ) {
		$content = $new_content;
		$replacements++;
	} else {
		echo "WARNING: no match (or multiple) for \"$title\" — count=$count\n";
	}
}

echo "Replacements made: $replacements / " . count( $links ) . "\n";

if ( $replacements !== count( $links ) ) {
	echo "Aborting — not all 5 cards matched, refusing to save a partial patch.\n";
	exit( 1 );
}

kses_remove_filters();
$result = wp_update_post( [
	'ID'           => $post_id,
	'post_content' => wp_slash( $content ),
], true );
kses_init_filters();

if ( is_wp_error( $result ) ) {
	echo "ERROR: " . $result->get_error_message() . "\n";
	exit( 1 );
}

// Verify: re-fetch and confirm all 5 links are really there, and the SVG icons elsewhere on the
// page survived (the exact collateral-damage class of bug this kses wrap exists to prevent).
$saved = get_post( $post_id )->post_content;
$link_count = 0;
foreach ( $links as $title => $href ) {
	if ( str_contains( $saved, '<a href="' . $href . '">Learn More' ) ) {
		$link_count++;
	}
}
$svg_count = substr_count( $saved, '<svg' );
echo "Verified after save: $link_count/5 links present, $svg_count <svg> tags still present.\n";
