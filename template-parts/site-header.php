<?php
/**
 * Site header — floating white pill nav overlaid on the hero.
 * Pixel-identical across all 55 sampled Figma pages: logo, 8-item nav,
 * phone line, gold "Book Now" button. Always injected by page.php/index.php,
 * never editable page content, so a non-technical editor can't delete it.
 *
 * Dropdown content (Cruises, Special Events, Weddings, The Ship) is pulled verbatim from the REAL
 * live skylinecruises.com mega menu (Max Mega Menu), reorganized under this redesign's already-
 * approved 8-item top-level label set — see the 2026-08-24 nav-build memory for the source.
 *
 * VISUAL DESIGN 2026-08-24: rebuilt to match the client's own biltmorehairrestoration.com nav
 * (a real EFFVIT build, not just a Figma mock) at the user's explicit request — same three
 * dropdown styles, same icon-badge treatment, same layout, with Skyline's navy/gold palette and
 * cruise-themed icons swapped in for Biltmore's teal/medical ones (confirmed via a live screenshot
 * of biltmorehairrestoration.com's open dropdowns, not guessed from memory):
 * - 'tabs'   : vertical left-hand tab list + a right-hand content panel of icon+title+description
 *              rows for the active tab (mirrors Biltmore's "Treatments" dropdown). Used for
 *              Cruises, the one category with real per-item blurbs worth showing.
 * - 'list'   : multiple headed columns of icon+label rows, no description (mirrors "Learn").
 *              Used for Special Events.
 * - 'simple' : one unheaded column of icon+label rows (mirrors "About"). Used for Weddings and
 *              The Ship.
 */
$logo = get_template_directory_uri() . '/assets/icons/logo.png'; // TODO: swap in the real logo asset

/**
 * Small inline-SVG icon set for the nav dropdown badges — simple original line-icon shapes (not
 * copied from any icon library), 24x24 viewBox, stroke-only, reused across items the same way
 * Biltmore reuses its own 3 icon badges across ~10 treatment items. One name per visual concept;
 * pick the closest match per item rather than drawing a new icon for every single link.
 */
function skyline_nav_icon( $name ) {
	$paths = [
		'anchor'    => '<circle cx="12" cy="6" r="2.5"/><line x1="12" y1="8.5" x2="12" y2="20"/><path d="M6 14a6 6 0 0 0 12 0"/>',
		'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
		'utensils'  => '<path d="M8 2v6M12 2v6M16 2v6M12 8v14"/>',
		'gift'      => '<rect x="3" y="8" width="18" height="4"/><rect x="3" y="12" width="18" height="9"/><line x1="12" y1="8" x2="12" y2="21"/><path d="M12 8c0-3-2-5-4-5s-3 2-1 5M12 8c0-3 2-5 4-5s3 2 1 5"/>',
		'heart'     => '<path d="M12 21s-7-4.35-9.5-8.5C.5 8.5 3 4 7 4c2 0 4 1.5 5 3.5C13 5.5 15 4 17 4c4 0 6.5 4.5 4.5 8.5C19 16.65 12 21 12 21z"/>',
		'star'      => '<polygon points="12,2 15,9 22,9 16.5,13.5 18.5,21 12,17 5.5,21 7.5,13.5 2,9 9,9"/>',
		'flag'      => '<line x1="5" y1="3" x2="5" y2="21"/><path d="M5 5h13l-3 4 3 4H5"/>',
		'wine'      => '<path d="M4 4h16l-8 9z"/><line x1="12" y1="13" x2="12" y2="21"/><line x1="8" y1="21" x2="16" y2="21"/>',
		'grad-cap'  => '<path d="M2 9l10-5 10 5-10 5-10-5z"/><path d="M6 11v5c0 1.5 3 3 6 3s6-1.5 6-3v-5"/>',
		'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="2" y1="13" x2="22" y2="13"/>',
		'users'     => '<circle cx="9" cy="8" r="3"/><path d="M2 21v-2a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v2"/><circle cx="18" cy="9" r="2.5"/><path d="M22 21v-1.5a4 4 0 0 0-3-3.87"/>',
		'camera'    => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7l1.5-3h5L16 7"/><circle cx="12" cy="13.5" r="3.5"/>',
		'map-pin'   => '<path d="M12 22s7-7.5 7-12A7 7 0 0 0 5 10c0 4.5 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/>',
		'compass'   => '<circle cx="12" cy="12" r="9"/><polygon points="15,9 13,13 9,15 11,11"/>',
		'gem'       => '<path d="M6 8l6-5 6 5-6 13z"/><path d="M6 8h12M9 8l3 13 3-13"/>',
		'sun'       => '<circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="22"/><line x1="2" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="22" y2="12"/><line x1="4.6" y1="4.6" x2="6.7" y2="6.7"/><line x1="17.3" y1="17.3" x2="19.4" y2="19.4"/><line x1="4.6" y1="19.4" x2="6.7" y2="17.3"/><line x1="17.3" y1="6.7" x2="19.4" y2="4.6"/>',
	];
	$inner = $paths[ $name ] ?? $paths['anchor'];
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $inner . '</svg>';
}

$nav_menu = [
	[
		'label' => 'Cruises',
		'href'  => '/nyc-dinner-cruises/',
		'style' => 'tabs',
		'tabs'  => [
			[
				'label' => 'Public Cruises',
				'items' => [
					[ 'icon' => 'utensils', 'label' => 'NYC Dinner Cruises', 'href' => '/nyc-dinner-cruises/', 'description' => 'An evening cruise with dinner, live DJ, and skyline views.', 'children' => [
						[ 'icon' => 'utensils', 'label' => 'Dinner Cruise Buffet Menu', 'href' => '/nyc-dinner-cruises/menu/' ],
					] ],
					[ 'icon' => 'sun', 'label' => 'NYC Brunch Cruises', 'href' => '/nyc-brunch-cruises/', 'description' => 'A daytime cruise with brunch and harbor views.', 'children' => [
						[ 'icon' => 'utensils', 'label' => 'Brunch Cruise Buffet Menu', 'href' => '/nyc-brunch-cruises/brunch-menu/' ],
					] ],
					[ 'icon' => 'utensils', 'label' => 'NYC Lunch Cruises', 'href' => '/nyc-lunch-cruises/', 'description' => 'A midday cruise with lunch and skyline views.', 'children' => [
						[ 'icon' => 'utensils', 'label' => 'Lunch Cruise Buffet Menu', 'href' => '/nyc-lunch-cruises/lunch-menu/' ],
					] ],
				],
			],
			[
				'label' => 'Holiday Cruises',
				'items' => [
					[ 'icon' => 'gift', 'label' => 'All Holiday Cruises', 'href' => '/nyc-holiday-cruises/', 'description' => 'Seasonal cruises for every holiday on the calendar.' ],
					[ 'icon' => 'star', 'label' => "New Year's Eve", 'href' => '/nyc-holiday-cruises/new-years-eve/', 'description' => 'Ring in the new year on the water.' ],
					[ 'icon' => 'heart', 'label' => "Valentine's Day", 'href' => '/nyc-holiday-cruises/valentines-day/', 'description' => 'A romantic cruise for two.' ],
					[ 'icon' => 'gift', 'label' => "Mother's Day", 'href' => '/nyc-holiday-cruises/mothers-day/', 'description' => 'Celebrate Mom with brunch on the harbor.' ],
					[ 'icon' => 'gift', 'label' => "Father's Day", 'href' => '/nyc-holiday-cruises/fathers-day/', 'description' => 'Celebrate Dad with a cruise on the water.' ],
					[ 'icon' => 'flag', 'label' => '4th of July Cruise', 'href' => '/nyc-holiday-cruises/4th-of-july/', 'description' => 'Watch the fireworks from the water.' ],
				],
			],
			[
				'label' => 'More Ways to Cruise',
				'items' => [
					[ 'icon' => 'compass', 'label' => 'Lighthouse Cruises', 'href' => '/nyc-party-cruises/lighthouse-cruise/', 'description' => "A scenic cruise past Long Island's lighthouses." ],
					[ 'icon' => 'wine', 'label' => 'Booze Cruises', 'href' => '/nyc-party-cruises/booze-cruises/', 'description' => 'A cash bar cruise built for a night out.' ],
					[ 'icon' => 'flag', 'label' => 'US Open Charters & Transportation', 'href' => '/us-open-charters-and-transportation/', 'description' => 'Charter transportation to the US Open.' ],
					[ 'icon' => 'anchor', 'label' => 'Connecticut Cruises & Yacht Charters', 'href' => '/yacht-charter/connecticut/', 'description' => 'Cruises and charters departing Connecticut.' ],
					[ 'icon' => 'calendar', 'label' => 'Public Cruise Schedule', 'href' => 'https://fareharbor.com/embeds/book/skylinecruises/items/date/?full-items=yes', 'description' => 'See dates and book your seat.', 'external' => true ],
				],
			],
		],
	],
	[
		'label' => 'Special Events',
		'href'  => '/nyc-party-cruises/',
		'style' => 'list',
		'columns' => [
			[
				'heading' => 'Celebrations',
				'items'   => [
					[ 'icon' => 'gift', 'label' => 'Birthday Party Cruises', 'href' => '/nyc-party-cruises/birthday-party/' ],
					[ 'icon' => 'star', 'label' => 'Bar/Bat Mitzvah Cruises', 'href' => '/nyc-party-cruises/nyc-bar-bat-mitzvah-cruise/' ],
					[ 'icon' => 'star', 'label' => 'Sweet 16 Cruises', 'href' => '/nyc-party-cruises/sweet-16-party-cruise/' ],
					[ 'icon' => 'star', 'label' => 'Quinceañera Cruises', 'href' => '/nyc-party-cruises/quinceanera-cruises-nyc/' ],
					[ 'icon' => 'users', 'label' => 'Church Group Cruises', 'href' => '/nyc-party-cruises/church-group-outings/' ],
				],
			],
			[
				'heading' => 'School Events',
				'items'   => [
					[ 'icon' => 'grad-cap', 'label' => 'All School Events', 'href' => '/school-events/' ],
					[ 'icon' => 'grad-cap', 'label' => 'Graduation Cruises & Parties', 'href' => '/school-events/graduation-cruises-parties/' ],
					[ 'icon' => 'star', 'label' => 'Prom & After Prom', 'href' => '/school-events/prom/' ],
					[ 'icon' => 'grad-cap', 'label' => 'College Cruises', 'href' => '/school-events/college-cruises/' ],
				],
			],
			[
				'heading' => 'Corporate & Charters',
				'items'   => [
					[ 'icon' => 'briefcase', 'label' => 'Corporate Cruises', 'href' => '/corporate-cruises/', 'children' => [
						[ 'icon' => 'heart', 'label' => 'Fundraisers', 'href' => '/corporate-cruises/fundraisers/' ],
					] ],
					[ 'icon' => 'anchor', 'label' => 'Private Yacht Charters', 'href' => '/yacht-charter/' ],
					[ 'icon' => 'map-pin', 'label' => 'Waterfront Event Venue', 'href' => '/yacht-charter/waterfront-event-venue/' ],
					[ 'icon' => 'anchor', 'label' => 'Great Escape Yacht Rental', 'href' => '/the-great-escape-yacht-rental/' ],
				],
			],
		],
	],
	[
		'label' => 'Weddings',
		'href'  => '/weddings/',
		'style' => 'simple',
		'items' => [
			[ 'icon' => 'heart', 'label' => 'Weddings', 'href' => '/weddings/' ],
			[ 'icon' => 'users', 'label' => 'Meet Captain Arnold', 'href' => '/weddings/captain-arnold-wedding-officiant/' ],
			[ 'icon' => 'heart', 'label' => 'LGBT Wedding Cruises', 'href' => '/weddings/lgbt-wedding-cruises/' ],
			[ 'icon' => 'heart', 'label' => 'Anniversary Cruise', 'href' => '/weddings/anniversary-cruise-nyc/' ],
			[ 'icon' => 'gem', 'label' => 'Engagement Party', 'href' => '/weddings/engagement-party-cruises-nyc/' ],
			[ 'icon' => 'gift', 'label' => 'Bridal Showers', 'href' => '/weddings/bridal-showers/' ],
			[ 'icon' => 'users', 'label' => 'Bridal Shows', 'href' => '/weddings/bridal-show-cruises/' ],
		],
	],
	[
		'label' => 'The Ship',
		'href'  => '/the-ship/',
		'style' => 'simple',
		'items' => [
			[ 'icon' => 'compass', 'label' => "The Ship's Layout", 'href' => '/the-ship/the-ships-layout/' ],
			[ 'icon' => 'camera', 'label' => 'Virtual Tour (360°)', 'href' => '/the-ship/skyline-princess-360-photosphere/' ],
			[ 'icon' => 'camera', 'label' => 'Photo Gallery', 'href' => '/the-ship/picture-gallery/' ],
			[ 'icon' => 'map-pin', 'label' => 'Our Departure Ports', 'href' => '/ports/' ],
		],
	],
	[
		'label' => 'Testimonials',
		'href'  => '/about/clients-testimonials/',
	],
	[
		'label' => 'FAQs',
		'href'  => '/about/faq/',
	],
	[
		'label' => 'Blog',
		'href'  => '/notes-from-the-deck/',
	],
	[
		'label' => 'Contact',
		'href'  => '/contact-us/',
	],
];

/** Renders one icon+label(+description) row, used by both the 'tabs' content panel and 'list'/'simple' columns. */
function skyline_render_nav_row( $item, $with_description = false ) {
	$external = ! empty( $item['external'] ) ? ' target="_blank" rel="noopener"' : '';
	echo '<li class="nav-dropdown__row">';
	echo '<a class="nav-dropdown__row-link" href="' . esc_url( $item['href'] ) . '"' . $external . '>';
	echo '<span class="nav-icon-badge">' . skyline_nav_icon( $item['icon'] ?? 'anchor' ) . '</span>';
	echo '<span class="nav-dropdown__row-text">';
	echo '<span class="nav-dropdown__row-title">' . esc_html( $item['label'] ) . '</span>';
	if ( $with_description && ! empty( $item['description'] ) ) {
		echo '<span class="nav-dropdown__row-desc">' . esc_html( $item['description'] ) . '</span>';
	}
	echo '</span></a>';
	if ( ! empty( $item['children'] ) ) {
		echo '<ul class="nav-dropdown__sublist">';
		foreach ( $item['children'] as $child ) {
			echo '<li class="nav-dropdown__row nav-dropdown__row--sub">';
			echo '<a class="nav-dropdown__row-link" href="' . esc_url( $child['href'] ) . '">';
			echo '<span class="nav-icon-badge nav-icon-badge--sm">' . skyline_nav_icon( $child['icon'] ?? 'utensils' ) . '</span>';
			echo '<span class="nav-dropdown__row-text"><span class="nav-dropdown__row-title">' . esc_html( $child['label'] ) . '</span></span>';
			echo '</a></li>';
		}
		echo '</ul>';
	}
	echo '</li>';
}
?>
<header class="site-header">
	<nav class="nav-pill">
		<a class="nav-pill__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( $logo ); ?>" alt="Skyline Cruises" width="114" height="51" />
		</a>
		<ul class="nav-pill__links">
			<?php foreach ( $nav_menu as $item ) : ?>
				<?php if ( empty( $item['style'] ) ) : ?>
					<li class="nav-pill__item">
						<a href="<?php echo esc_url( $item['href'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
					</li>
				<?php elseif ( 'tabs' === $item['style'] ) : ?>
					<li class="nav-pill__item nav-pill__item--has-dropdown">
						<a href="<?php echo esc_url( $item['href'] ); ?>" class="nav-pill__trigger" aria-haspopup="true" aria-expanded="false">
							<?php echo esc_html( $item['label'] ); ?>
							<svg class="nav-chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true"><path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
						</a>
						<div class="nav-dropdown nav-dropdown--tabs">
							<div class="nav-dropdown__panel">
								<div class="nav-tabs__list" role="tablist">
									<?php foreach ( $item['tabs'] as $i => $tab ) : ?>
										<button type="button" class="nav-tabs__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" data-tab-index="<?php echo esc_attr( $i ); ?>" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"><?php echo esc_html( $tab['label'] ); ?></button>
									<?php endforeach; ?>
								</div>
								<div class="nav-tabs__panels">
									<?php foreach ( $item['tabs'] as $i => $tab ) : ?>
										<ul class="nav-tabs__panel<?php echo 0 === $i ? ' is-active' : ''; ?>" data-tab-panel="<?php echo esc_attr( $i ); ?>" role="tabpanel">
											<?php foreach ( $tab['items'] as $row ) : ?>
												<?php skyline_render_nav_row( $row, true ); ?>
											<?php endforeach; ?>
										</ul>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</li>
				<?php elseif ( 'list' === $item['style'] ) : ?>
					<li class="nav-pill__item nav-pill__item--has-dropdown">
						<a href="<?php echo esc_url( $item['href'] ); ?>" class="nav-pill__trigger" aria-haspopup="true" aria-expanded="false">
							<?php echo esc_html( $item['label'] ); ?>
							<svg class="nav-chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true"><path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
						</a>
						<div class="nav-dropdown nav-dropdown--list">
							<div class="nav-dropdown__panel">
								<div class="nav-dropdown__columns">
									<?php foreach ( $item['columns'] as $column ) : ?>
										<div class="nav-dropdown__column">
											<p class="nav-dropdown__heading"><?php echo esc_html( $column['heading'] ); ?></p>
											<ul>
												<?php foreach ( $column['items'] as $row ) : ?>
													<?php skyline_render_nav_row( $row, false ); ?>
												<?php endforeach; ?>
											</ul>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</li>
				<?php elseif ( 'simple' === $item['style'] ) : ?>
					<li class="nav-pill__item nav-pill__item--has-dropdown">
						<a href="<?php echo esc_url( $item['href'] ); ?>" class="nav-pill__trigger" aria-haspopup="true" aria-expanded="false">
							<?php echo esc_html( $item['label'] ); ?>
							<svg class="nav-chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true"><path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
						</a>
						<div class="nav-dropdown nav-dropdown--simple">
							<div class="nav-dropdown__panel">
								<ul>
									<?php foreach ( $item['items'] as $row ) : ?>
										<?php skyline_render_nav_row( $row, false ); ?>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</nav>
	<div class="header-right">
		<a class="header-right__phone" href="tel:17184461100">
			<svg viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
			Call (718) 446-1100 For The Next Event
		</a>
		<a class="btn btn-gold" href="/contact-us/request-your-quote/">Book Now</a>
	</div>
</header>
