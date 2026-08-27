<?php
// One-off content patch: 2026-08-27 Buffet Menu category live-vs-built audit.
// Fixes 2 real gaps found on all 3 Buffet Menu pages (7 Dinner, 82 Brunch, 83 Lunch), confirmed
// via direct live-page diff against skylinecruises.com:
//   1. Missing real h3 subheading above the checklist ("Dinner Entree Choices" / "Brunch Entrée
//      Choices" / "Lunch Entrée Choices" — verbatim, including dinner's real inconsistent
//      "Entree" without accent vs brunch/lunch's accented "Entrée").
//   2. "Skyline's attentive staff serves..." should read "...staff ALSO serves..." on this
//      category specifically (confirmed real per-template variance, not a scrape error — the
//      main Public Cruise Service pages genuinely do NOT say "also").
// Uses the project's standing kses_remove_filters()/kses_init_filters() wrap for any one-off
// wp_update_post() call, since post content elsewhere on the page may contain markup outside
// wp_kses_post's allowed tags.

$pages = [
	7 => [
		'old_item' => '<!-- wp:paragraph {"className":"checklist-item--lg"} --><p class="checklist-item--lg"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Caesar Salad</p><!-- /wp:paragraph -->',
		'heading' => 'Dinner Entree Choices',
		'old_also' => "Skyline\xe2\x80\x99s attentive staff serves premium beer",
		'new_also' => "Skyline\xe2\x80\x99s attentive staff also serves premium beer",
	],
	82 => [
		'old_item' => '<!-- wp:paragraph {"className":"checklist-item--lg"} --><p class="checklist-item--lg"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Breakfast pastries served with assorted jellies, cream cheese, and butter</p><!-- /wp:paragraph -->',
		'heading' => 'Brunch Entr&eacute;e Choices',
		'old_also' => "Skyline\xe2\x80\x99s attentive staff serves premium beer",
		'new_also' => "Skyline\xe2\x80\x99s attentive staff also serves premium beer",
	],
	83 => [
		'old_item' => '<!-- wp:paragraph {"className":"checklist-item--lg"} --><p class="checklist-item--lg"><img class="check-icon" src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg" alt="" />Caesar Salad</p><!-- /wp:paragraph -->',
		'heading' => 'Lunch Entr&eacute;e Choices',
		'old_also' => "Skyline\xe2\x80\x99s attentive staff serves premium beer",
		'new_also' => "Skyline\xe2\x80\x99s attentive staff also serves premium beer",
	],
];

kses_remove_filters();

foreach ( $pages as $id => $fix ) {
	$post = get_post( $id );
	if ( ! $post ) {
		WP_CLI::log( "Page $id not found, skipping." );
		continue;
	}
	$content = $post->post_content;

	$heading_html = '<!-- wp:heading {"level":3,"className":"photo-checklist-row__list-heading"} --><h3 class="photo-checklist-row__list-heading" style="grid-column:1/-1;">' . $fix['heading'] . '</h3><!-- /wp:heading -->' . "\n";

	if ( strpos( $content, $fix['old_item'] ) === false ) {
		WP_CLI::log( "Page $id: checklist item anchor not found, skipping heading insert." );
	} elseif ( strpos( $content, $fix['heading'] ) !== false ) {
		WP_CLI::log( "Page $id: heading already present, skipping." );
	} else {
		$content = str_replace( $fix['old_item'], $heading_html . $fix['old_item'], $content );
	}

	if ( strpos( $content, $fix['old_also'] ) !== false ) {
		$content = str_replace( $fix['old_also'], $fix['new_also'], $content );
	} elseif ( strpos( $content, $fix['new_also'] ) !== false ) {
		WP_CLI::log( "Page $id: 'also' already present, skipping." );
	} else {
		WP_CLI::log( "Page $id: 'also serves' anchor not found." );
	}

	wp_update_post( [
		'ID'           => $id,
		'post_content' => wp_slash( $content ),
	] );

	$check = get_post( $id )->post_content;
	$ok_heading = strpos( $check, $fix['heading'] ) !== false;
	$ok_also    = strpos( $check, $fix['new_also'] ) !== false;
	WP_CLI::log( "Page $id saved. heading present: " . ( $ok_heading ? 'yes' : 'NO' ) . ", 'also' present: " . ( $ok_also ? 'yes' : 'NO' ) );
}

kses_init_filters();
