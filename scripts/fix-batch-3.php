<?php
/**
 * One-off content patch, batch 3: missing closing "book now" taglines on Birthday/Bar-Bat-
 * Mitzvah/Sweet 16 (each page's own real wording), plus Birthday's one missing intro clause.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-batch-3.php";'
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

// Birthday (8): missing clause in the "Intimate Gatherings" card paragraph.
apply_fix( 8,
	'<h3>Intimate Gatherings</h3><!-- /wp:heading --><!-- wp:paragraph --><p>The Skyline Princess is one of the most versatile charter yachts in the NYC area. Smaller,',
	'<h3>Intimate Gatherings</h3><!-- /wp:heading --><!-- wp:paragraph --><p>The Skyline Princess is one of the most versatile charter yachts in the NYC area, and we can host a truly stunning array of birthday festivities. Smaller,',
	'Birthday (intro clause)'
);

// Birthday (8): missing closing tagline, right before closing-cta.
apply_fix( 8,
	'<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>For more information about Birthday Party cruises',
	'<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Skyline Cruises will make your Birthday Party a memorable one&#8230;. so book now!</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>For more information about Birthday Party cruises',
	'Birthday (closing tagline)'
);

// Bar/Bat Mitzvah (392): missing closing tagline.
apply_fix( 392,
	'<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>For more information about our Bat/Bar Mitzvah cruises,',
	'<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Skyline Cruises will make your day special so book now!</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>For more information about our Bat/Bar Mitzvah cruises,',
	'Bar/Bat Mitzvah (closing tagline)'
);

// Sweet 16 (393): missing closing tagline.
apply_fix( 393,
	'<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>For more information about our Sweet 16 cruises,',
	'<!-- wp:group {"className":"text-section"} --><div class="wp-block-group text-section"><!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Skyline Cruises will make your daughter&#8217;s special day truly memorable! Start Planning Today!</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>Ready To Set Sail?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>For more information about our Sweet 16 cruises,',
	'Sweet 16 (closing tagline)'
);

echo "\nDone.\n";
