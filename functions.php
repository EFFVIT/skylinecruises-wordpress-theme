<?php
/**
 * Theme setup: enqueue styles/fonts, register block patterns from /patterns/*.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * filemtime() of the actual file, not a hand-maintained version string — a static '0.1.0' left
 * unbumped is exactly what let a browser keep serving a stale cached copy of patterns.css after
 * an edit (real incident, 2026-08-24: the route-map CSS shipped correctly to the server but a
 * visitor's browser kept the old cached stylesheet since the URL's ?ver= never changed). Deriving
 * the version from the file's own mtime means every future edit auto-busts the cache, the same
 * "fix the class of bug, not the instance" approach used for the core block-restructuring filters
 * above — never hardcode a version string for theme-owned CSS/JS again.
 */
function skyline_asset_version( $relative_path ) {
	$file = get_template_directory() . $relative_path;
	return file_exists( $file ) ? filemtime( $file ) : '0.1.0';
}

function skyline_enqueue_assets() {
	wp_enqueue_style(
		'skyline-google-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;1,400&family=Poppins:ital,wght@0,400;0,500;0,700&family=Lato:wght@400&display=swap',
		[],
		null
	);
	wp_enqueue_style( 'skyline-tokens', get_template_directory_uri() . '/assets/css/tokens.css', [], skyline_asset_version( '/assets/css/tokens.css' ) );
	wp_enqueue_style( 'skyline-patterns', get_template_directory_uri() . '/assets/css/patterns.css', [ 'skyline-tokens' ], skyline_asset_version( '/assets/css/patterns.css' ) );
	// Mobile breakpoints, loaded LAST and depending on both of the above so its overrides always
	// win the cascade without needing !important anywhere except the handful of spots fighting an
	// inline style attribute (see mobile.css's own header comment). Every desktop pattern in this
	// theme is authored at full 1713px scale with hardcoded --side-margin/80px paddings and fixed
	// px widths on flex/grid children (620px feature icons, 560px checklist photos, etc.) — none
	// of that shrinks on its own, so this file is where every section actually becomes responsive.
	wp_enqueue_style( 'skyline-mobile', get_template_directory_uri() . '/assets/css/mobile.css', [ 'skyline-tokens', 'skyline-patterns' ], skyline_asset_version( '/assets/css/mobile.css' ) );

	// Site-wide, every page: nav dropdown touch/keyboard support + scroll-reveal section
	// animations. Both degrade gracefully with no JS (dropdowns still work via CSS :hover, sections
	// just render already-visible instead of animating in).
	wp_enqueue_script( 'skyline-nav-menu', get_template_directory_uri() . '/assets/js/nav-menu.js', [], skyline_asset_version( '/assets/js/nav-menu.js' ), true );
	wp_enqueue_script( 'skyline-mobile-nav', get_template_directory_uri() . '/assets/js/mobile-nav.js', [], skyline_asset_version( '/assets/js/mobile-nav.js' ), true );
	wp_enqueue_script( 'skyline-scroll-reveal', get_template_directory_uri() . '/assets/js/scroll-reveal.js', [], skyline_asset_version( '/assets/js/scroll-reveal.js' ), true );

	// Route Map pattern (Public Cruise Service pages only) embeds a real Leaflet/OpenStreetMap
	// map — self-hosted (not a CDN) so the page never depends on a third party being up. Only
	// enqueued on pages that actually contain the pattern's markup, not site-wide.
	if ( is_singular() && is_a( get_post(), 'WP_Post' ) && str_contains( get_post()->post_content, 'route-map__canvas' ) ) {
		wp_enqueue_style( 'leaflet', get_template_directory_uri() . '/assets/vendor/leaflet/leaflet.css', [], '1.9.4' );
		wp_enqueue_script( 'leaflet', get_template_directory_uri() . '/assets/vendor/leaflet/leaflet.js', [], '1.9.4', true );
		wp_enqueue_script( 'skyline-route-map', get_template_directory_uri() . '/assets/js/route-map.js', [ 'leaflet' ], skyline_asset_version( '/assets/js/route-map.js' ), true );
	}
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

/**
 * Beyond styles: WordPress also RESTRUCTURES the actual saved HTML of every block at render time
 * (found by direct inspection of live output, traced to wp-includes/block-supports/layout.php):
 * - wp_render_layout_support_flag() injects "is-layout-flow"/"is-layout-flex" classes into
 *   whatever it heuristically decides is each block's root element — with our patterns' hand-
 *   nested custom divs inside a single wp:group comment (not the flat structure Gutenberg itself
 *   would author), that heuristic picks the WRONG element, e.g. landing classes on .hero__content
 *   three levels deep instead of the actual block root. That's what put the School Events H1
 *   outside the hero card entirely.
 * - wp_restore_group_inner_container() is what inserts the .wp-block-group__inner-container
 *   wrapper in the first place (the thing that broke every multi-column layout earlier).
 * - wp_restore_image_outer_container() does the same for core/image.
 * Removing these three outright stops WordPress from touching our static markup at all, which is
 * more correct than continuing to patch around whatever it decides to restructure next.
 */
remove_filter( 'render_block', 'wp_render_layout_support_flag', 10 );
remove_filter( 'render_block_core/group', 'wp_restore_group_inner_container', 10 );
remove_filter( 'render_block_core/image', 'wp_restore_image_outer_container', 10 );

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
