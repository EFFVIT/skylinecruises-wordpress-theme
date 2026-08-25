<?php
/**
 * One-off content patch: fixes the invented "Memorable Service" caption + rewritten "Smooth
 * Sailing" caption (see the 2026-08-25 site-wide copy audit) on every already-live page that has
 * this block. build-pages.js/features-pair.php were already fixed for future pushes — this
 * script brings the 18 already-published pages in line with the same real, verbatim text.
 *
 * kses_remove_filters()/kses_init_filters() wrap is this project's standing rule for any one-off
 * script that calls wp_update_post() on a page that might have <svg>/<form>/<input>/<iframe>
 * anywhere in its content — several of these pages have inline SVG check-circle icons elsewhere.
 *
 * Run inside the container: wp --allow-root --path=/var/www/html eval-file fix-features-pair.php
 */

$page_ids = [ 6, 7, 8, 68, 69, 71, 72, 73, 74, 75, 76, 77, 78, 80, 81, 82, 83, 84 ];

$old_block = '<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-service.png" alt="Memorable Service" /></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Memorable Service</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Our attentive crew takes care of every detail, so you can focus on making memories with your guests.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-sailing.png" alt="Smooth Sailing" /></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Smooth Sailing</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Enjoy calm, scenic waters aboard a well-maintained fleet built for a comfortable ride, rain or shine.</p><!-- /wp:paragraph --></div><!-- /wp:group -->';

$new_block = '<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-service.png" alt="Memorable Service" /></figure><!-- /wp:image --><!-- wp:paragraph --><p>Skyline’s attentive staff serves premium beer, wine, and liquor at the cash bar. Want something special? <a href="https://skylinecruises.com/contact-us/">Contact the event experts at Skyline</a> to customize your experience.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-sailing.png" alt="Smooth Sailing" /></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Smooth Sailing</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Our yacht is a floating banquet room designed for year-round cruising. It is fully climate-controlled for year-round outings and because it sails in the calm, sheltered waters of the famous New York Harbor or Long Island Sound, you’ll experience smooth sailing.</p><!-- /wp:paragraph --></div><!-- /wp:group -->';

$fixed = 0;
$skipped = [];

foreach ( $page_ids as $id ) {
	$post = get_post( $id );
	if ( ! $post ) {
		$skipped[] = "$id (not found)";
		continue;
	}
	if ( ! str_contains( $post->post_content, $old_block ) ) {
		$skipped[] = "$id (old block not found verbatim — needs manual check)";
		continue;
	}
	$new_content = str_replace( $old_block, $new_block, $post->post_content );

	kses_remove_filters();
	$result = wp_update_post( [
		'ID'           => $id,
		'post_content' => wp_slash( $new_content ),
	], true );
	kses_init_filters();

	if ( is_wp_error( $result ) ) {
		$skipped[] = "$id (update error: " . $result->get_error_message() . ')';
		continue;
	}
	$fixed++;
	echo "Fixed page $id ({$post->post_title})\n";
}

echo "\nFixed: $fixed / " . count( $page_ids ) . "\n";
if ( $skipped ) {
	echo "Skipped:\n" . implode( "\n", $skipped ) . "\n";
}

// Verify: re-check a couple of pages that the new text is really there and the old isn't.
foreach ( [ 6, 8 ] as $check_id ) {
	$saved = get_post( $check_id )->post_content;
	$has_old_heading = str_contains( $saved, '<h3>Memorable Service</h3>' );
	$has_new_text     = str_contains( $saved, 'attentive staff serves premium beer' );
	$has_real_sailing = str_contains( $saved, 'floating banquet room designed for year-round cruising' );
	echo "Page $check_id verify: old_heading_gone=" . ( $has_old_heading ? 'NO(still there!)' : 'yes' )
		. ' new_cashbar_text=' . ( $has_new_text ? 'yes' : 'NO' )
		. ' real_smooth_sailing=' . ( $has_real_sailing ? 'yes' : 'NO' ) . "\n";
}
