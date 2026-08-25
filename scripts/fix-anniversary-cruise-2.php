<?php
/**
 * Follow-up to fix-anniversary-cruise.php: the "Celebrate Your Special Milestones"
 * section landed BEFORE the "Anniversary Dinner Cruises" h2/intro instead of after it
 * (real live order is: h2 intro -> Milestones section -> checklist). Move it.
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-anniversary-cruise-2.php";'
 */

$id = 78;
$post = get_post( $id );
$content = $post->post_content;

$milestones_block = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":3} --><h3>Celebrate Your Special Milestones with Skyline Cruises</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Skyline Cruises has an option for any size anniversary celebration. If you want a small, intimate celebration with just the two of you or a small gathering of friends and family, select one of our public dinner, lunch, or brunch cruises. If you wish, we can arrange a special table for just you and a few friends, a cake for your group, and whatever else you wish to make your anniversary celebration perfect. For a larger celebration, you can book an entire deck on one of our <a href="/nyc-dinner-cruises/">public dinner cruises</a> which depart from the World&#8217;s Fair Marina in Flushing, NY.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">Book Your Anniversary Celebration Today!</p><!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
';

if ( ! str_contains( $content, $milestones_block . '<!-- wp:group {"className":"photo-checklist-row"} -->' ) ) {
	echo "expected pattern not found - aborting\n";
	exit;
}

// Remove it from its current (wrong) position.
$content = str_replace( $milestones_block, '', $content );

// Re-insert it right after the photo-checklist-row__intro block closes, before
// photo-checklist-row__body opens.
$anchor = '<!-- wp:group {"className":"photo-checklist-row__body"} -->';
if ( ! str_contains( $content, $anchor ) ) {
	echo "insertion anchor not found - aborting\n";
	exit;
}
$content = str_replace( $anchor, $milestones_block . $anchor, $content );

kses_remove_filters();
$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $content ) ], true );
kses_init_filters();

if ( is_wp_error( $result ) ) {
	echo "update error: " . $result->get_error_message() . "\n";
	exit;
}

$saved = get_post( $id )->post_content;
$pos_h2 = strpos( $saved, 'Anniversary Dinner Cruises</h2>' );
$pos_milestones = strpos( $saved, 'Celebrate Your Special Milestones' );
$pos_body = strpos( $saved, 'photo-checklist-row__body' );
echo "h2 pos: $pos_h2, milestones pos: $pos_milestones, body pos: $pos_body\n";
echo ( $pos_h2 < $pos_milestones && $pos_milestones < $pos_body ) ? "ORDER OK\n" : "ORDER STILL WRONG\n";
