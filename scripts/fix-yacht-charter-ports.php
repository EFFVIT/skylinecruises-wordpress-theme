<?php
/**
 * One-off content patch: Private Yacht Charter (403, /yacht-charter/) is missing the
 * entire "Private Event Cruise Ports" 10-dock list that appears on live right after
 * the "Contact Us to Book Your Event Today!" line and before "Smooth Sailing" (same
 * fix pattern already applied to Corporate Cruises, 402).
 * Run via: php -d display_errors=1 -r 'require "/var/www/html/wp-load.php"; include "/tmp/fix-yacht-charter-ports.php";'
 */

$id = 403;
$post = get_post( $id );
if ( ! $post ) { echo "post not found\n"; exit; }
$content = $post->post_content;

$ports_block = '<!-- wp:group {"className":"ports-list"} -->
<div class="wp-block-group ports-list">
<!-- wp:heading {"level":3} --><h3>Private Event Cruise Ports</h3><!-- /wp:heading -->
<!-- wp:group {"className":"ports-list__items"} --><div class="wp-block-group ports-list__items">
<div class="ports-list__item"><a href="/worlds-fair-marina/">The World&#8217;s Fair Marina</a> &#8211; Flushing, Queens, NY</div>
<div class="ports-list__item"><a href="/chelsea-piers/">Chelsea Piers</a> &#8211; Manhattan, NY (Private Cruises Only)</div>
<div class="ports-list__item"><a href="/pier-36/">Pier 36</a> &#8211; Downtown Manhattan, NY</div>
<div class="ports-list__item"><a href="/liberty-landing-marina/">Liberty Landing Marina</a> &#8211; Jersey City, NJ</div>
<div class="ports-list__item"><a href="/town-dock-park/">Town Dock Park</a> &#8211; Port Washington, NY</div>
<div class="ports-list__item"><a href="/ponus-yacht-club/">Ponus Yacht Club</a> &#8211; Stamford, CT</div>
<div class="ports-list__item"><a href="/veterans-memorial-park-marina/">Veteran&#8217;s Memorial Park Marina</a> &#8211; Norwalk, CT</div>
<div class="ports-list__item"><a href="/new-rochelle-municipal-marina/">Municipal Marina</a> &#8211; New Rochelle, NY</div>
<div class="ports-list__item"><a href="/yonkers-city-pier/">Yonkers City Pier</a> &#8211; Yonkers, NY</div>
<div class="ports-list__item"><a href="/lincoln-harbor/">Lincoln Harbor</a> &#8211; Weehawken, NJ</div>
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->
' . "\n";

$anchor = '<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
<!-- wp:heading {"level":2} --><h2>Smooth Sailing</h2>';

if ( ! str_contains( $content, $anchor ) ) {
	echo "ANCHOR NOT FOUND\n";
	exit;
}

$new_content = str_replace( $anchor, $ports_block . $anchor, $content );

kses_remove_filters();
$result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( $new_content ) ], true );
kses_init_filters();

if ( is_wp_error( $result ) ) {
	echo "update error: " . $result->get_error_message() . "\n";
	exit;
}

$saved = get_post( $id )->post_content;
echo "ports heading present: " . ( str_contains( $saved, 'Private Event Cruise Ports' ) ? 'yes' : 'NO' ) . "\n";
echo "ports-list__item count: " . substr_count( $saved, 'ports-list__item"' ) . " (expect 10)\n";
