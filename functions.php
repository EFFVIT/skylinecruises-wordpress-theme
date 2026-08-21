<?php
/**
 * Theme setup: enqueue styles/fonts, register block patterns from /patterns/*.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function skyline_enqueue_assets() {
	wp_enqueue_style(
		'skyline-google-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;1,400&family=Poppins:ital,wght@0,400;0,500;0,700&family=Lato:wght@400&display=swap',
		[],
		null
	);
	wp_enqueue_style( 'skyline-tokens', get_template_directory_uri() . '/assets/css/tokens.css', [], '0.1.0' );
	wp_enqueue_style( 'skyline-patterns', get_template_directory_uri() . '/assets/css/patterns.css', [ 'skyline-tokens' ], '0.1.0' );
}
add_action( 'wp_enqueue_scripts', 'skyline_enqueue_assets' );

/**
 * This theme has its own complete design system (tokens.css + patterns.css) for every block
 * used on the front end — WordPress core's own block CSS should never be relied on.
 *
 * First pass at this (dequeuing just the 'wp-block-library' handle) turned out to be incomplete:
 * since WP 6.3, core splits each block's CSS into its OWN small stylesheet/inline snippet
 * (`wp-block-group-inline-css`, `wp-block-buttons-inline-css`, etc. — one per block TYPE actually
 * used on the page) instead of one bundle, specifically so unused blocks' CSS never loads. That
 * splitting is controlled by `should_load_separate_core_block_assets` — forcing it false collapses
 * everything back into the single legacy bundle, which the dequeue below then correctly removes
 * in one place again. Separately, `wp_enqueue_global_styles()` prints its own
 * `global-styles-inline-css` block directly rather than through a dequeue-able style call in the
 * normal way it appeared to; removing the action outright (both hook points core uses) is more
 * reliable than trying to dequeue after the fact.
 */
add_filter( 'should_load_separate_core_block_assets', '__return_false' );
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );

function skyline_dequeue_core_block_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'skyline_dequeue_core_block_styles', 100 );

function skyline_theme_support() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'skyline_theme_support' );

/**
 * Register one custom pattern category, then register every pattern in /patterns/*.php.
 * Each pattern file returns an array: ['title' => ..., 'description' => ..., 'content' => <block markup>].
 * Keeping patterns as real registered block patterns means they're available in the block-inserter
 * UI for manual editing, not just something the bulk REST API script knows about.
 */
function skyline_register_block_patterns() {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'skyline-sections', [ 'label' => __( 'Skyline Sections', 'skyline-cruises' ) ] );
	}

	$patterns_dir = get_template_directory() . '/patterns';
	if ( ! is_dir( $patterns_dir ) ) {
		return;
	}

	foreach ( glob( $patterns_dir . '/*.php' ) as $file ) {
		$slug    = basename( $file, '.php' );
		$pattern = include $file;

		if ( ! is_array( $pattern ) || empty( $pattern['content'] ) ) {
			continue;
		}

		$pattern['categories'] = $pattern['categories'] ?? [ 'skyline-sections' ];

		register_block_pattern( 'skyline/' . $slug, $pattern );
	}
}
add_action( 'init', 'skyline_register_block_patterns' );
