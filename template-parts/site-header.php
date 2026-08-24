<?php
/**
 * Site header — floating white pill nav overlaid on the hero.
 * Pixel-identical across all 55 sampled Figma pages: logo, 8-item nav,
 * phone line, gold "Book Now" button. Always injected by page.php/index.php,
 * never editable page content, so a non-technical editor can't delete it.
 *
 * Dropdown content below (Cruises, Special Events, Weddings, The Ship) is pulled verbatim from
 * the REAL live skylinecruises.com mega menu (Max Mega Menu plugin) — real labels, real relative
 * URLs, reorganized under this redesign's already-approved 8-item top-level label set (Cruises /
 * Special Events / Weddings / The Ship / Testimonials / FAQs / Blog / Contact), not invented.
 * Testimonials/FAQs/Blog/Contact stay flat single links, same as the live site. Fixed the Blog
 * link while here: it was a placeholder "/blog/" — the live site's real slug is
 * "/notes-from-the-deck/".
 *
 * 'columns' with more than one entry renders a multi-column mega panel (Cruises, Special Events);
 * a single column renders a simple list dropdown (Weddings, The Ship) — same "mega vs simple-list"
 * distinction used for the Biltmore/Sage Figma nav systems, just coded instead of mocked up.
 */
$logo = get_template_directory_uri() . '/assets/icons/logo.png'; // TODO: swap in the real logo asset

$nav_menu = [
	[
		'label' => 'Cruises',
		'href'  => '/nyc-dinner-cruises/',
		'columns' => [
			[
				'heading' => 'Public Cruises',
				'items'   => [
					[ 'label' => 'NYC Dinner Cruises', 'href' => '/nyc-dinner-cruises/', 'children' => [
						[ 'label' => 'Dinner Cruise Buffet Menu', 'href' => '/nyc-dinner-cruises/menu/' ],
					] ],
					[ 'label' => 'NYC Brunch Cruises', 'href' => '/nyc-brunch-cruises/', 'children' => [
						[ 'label' => 'Brunch Cruise Buffet Menu', 'href' => '/nyc-brunch-cruises/brunch-menu/' ],
					] ],
					[ 'label' => 'NYC Lunch Cruises', 'href' => '/nyc-lunch-cruises/', 'children' => [
						[ 'label' => 'Lunch Cruise Buffet Menu', 'href' => '/nyc-lunch-cruises/lunch-menu/' ],
					] ],
				],
			],
			[
				'heading' => 'Holiday Cruises',
				'items'   => [
					[ 'label' => 'All Holiday Cruises', 'href' => '/nyc-holiday-cruises/' ],
					[ 'label' => "New Year's Eve", 'href' => '/nyc-holiday-cruises/new-years-eve/' ],
					[ 'label' => "Valentine's Day", 'href' => '/nyc-holiday-cruises/valentines-day/' ],
					[ 'label' => "Mother's Day", 'href' => '/nyc-holiday-cruises/mothers-day/' ],
					[ 'label' => "Father's Day", 'href' => '/nyc-holiday-cruises/fathers-day/' ],
					[ 'label' => '4th of July Cruise', 'href' => '/nyc-holiday-cruises/4th-of-july/' ],
				],
			],
			[
				'heading' => 'More Ways to Cruise',
				'items'   => [
					[ 'label' => 'Lighthouse Cruises', 'href' => '/nyc-party-cruises/lighthouse-cruise/' ],
					[ 'label' => 'Booze Cruises', 'href' => '/nyc-party-cruises/booze-cruises/' ],
					[ 'label' => 'US Open Charters & Transportation', 'href' => '/us-open-charters-and-transportation/' ],
					[ 'label' => 'Connecticut Cruises & Yacht Charters', 'href' => '/yacht-charter/connecticut/' ],
					[ 'label' => 'Public Cruise Schedule', 'href' => 'https://fareharbor.com/embeds/book/skylinecruises/items/date/?full-items=yes', 'external' => true ],
				],
			],
		],
	],
	[
		'label' => 'Special Events',
		'href'  => '/nyc-party-cruises/',
		'columns' => [
			[
				'heading' => 'Celebrations',
				'items'   => [
					[ 'label' => 'Birthday Party Cruises', 'href' => '/nyc-party-cruises/birthday-party/' ],
					[ 'label' => 'Bar/Bat Mitzvah Cruises', 'href' => '/nyc-party-cruises/nyc-bar-bat-mitzvah-cruise/' ],
					[ 'label' => 'Sweet 16 Cruises', 'href' => '/nyc-party-cruises/sweet-16-party-cruise/' ],
					[ 'label' => 'Quinceañera Cruises', 'href' => '/nyc-party-cruises/quinceanera-cruises-nyc/' ],
					[ 'label' => 'Church Group Cruises', 'href' => '/nyc-party-cruises/church-group-outings/' ],
				],
			],
			[
				'heading' => 'School Events',
				'items'   => [
					[ 'label' => 'All School Events', 'href' => '/school-events/' ],
					[ 'label' => 'Graduation Cruises & Parties', 'href' => '/school-events/graduation-cruises-parties/' ],
					[ 'label' => 'Prom & After Prom', 'href' => '/school-events/prom/' ],
					[ 'label' => 'College Cruises', 'href' => '/school-events/college-cruises/' ],
				],
			],
			[
				'heading' => 'Corporate & Charters',
				'items'   => [
					[ 'label' => 'Corporate Cruises', 'href' => '/corporate-cruises/', 'children' => [
						[ 'label' => 'Fundraisers', 'href' => '/corporate-cruises/fundraisers/' ],
					] ],
					[ 'label' => 'Private Yacht Charters', 'href' => '/yacht-charter/' ],
					[ 'label' => 'Waterfront Event Venue', 'href' => '/yacht-charter/waterfront-event-venue/' ],
					[ 'label' => 'Great Escape Yacht Rental', 'href' => '/the-great-escape-yacht-rental/' ],
				],
			],
		],
	],
	[
		'label' => 'Weddings',
		'href'  => '/weddings/',
		'columns' => [
			[
				'items' => [
					[ 'label' => 'Weddings', 'href' => '/weddings/' ],
					[ 'label' => 'Meet Captain Arnold', 'href' => '/weddings/captain-arnold-wedding-officiant/' ],
					[ 'label' => 'LGBT Wedding Cruises', 'href' => '/weddings/lgbt-wedding-cruises/' ],
					[ 'label' => 'Anniversary Cruise', 'href' => '/weddings/anniversary-cruise-nyc/' ],
					[ 'label' => 'Engagement Party', 'href' => '/weddings/engagement-party-cruises-nyc/' ],
					[ 'label' => 'Bridal Showers', 'href' => '/weddings/bridal-showers/' ],
					[ 'label' => 'Bridal Shows', 'href' => '/weddings/bridal-show-cruises/' ],
				],
			],
		],
	],
	[
		'label' => 'The Ship',
		'href'  => '/the-ship/',
		'columns' => [
			[
				'items' => [
					[ 'label' => "The Ship's Layout", 'href' => '/the-ship/the-ships-layout/' ],
					[ 'label' => 'Virtual Tour (360°)', 'href' => '/the-ship/skyline-princess-360-photosphere/' ],
					[ 'label' => 'Photo Gallery', 'href' => '/the-ship/picture-gallery/' ],
					[ 'label' => 'Our Departure Ports', 'href' => '/ports/' ],
				],
			],
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
?>
<header class="site-header">
	<nav class="nav-pill">
		<a class="nav-pill__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( $logo ); ?>" alt="Skyline Cruises" width="114" height="51" />
		</a>
		<ul class="nav-pill__links">
			<?php foreach ( $nav_menu as $item ) : ?>
				<?php if ( empty( $item['columns'] ) ) : ?>
					<li class="nav-pill__item">
						<a href="<?php echo esc_url( $item['href'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
					</li>
				<?php else : ?>
					<?php $is_mega = count( $item['columns'] ) > 1; ?>
					<li class="nav-pill__item nav-pill__item--has-dropdown">
						<a href="<?php echo esc_url( $item['href'] ); ?>" class="nav-pill__trigger" aria-haspopup="true" aria-expanded="false">
							<?php echo esc_html( $item['label'] ); ?>
							<svg class="nav-chevron" viewBox="0 0 12 8" fill="none" aria-hidden="true"><path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
						</a>
						<div class="nav-dropdown <?php echo $is_mega ? 'nav-dropdown--mega' : 'nav-dropdown--simple'; ?>">
							<div class="nav-dropdown__columns">
								<?php foreach ( $item['columns'] as $column ) : ?>
									<div class="nav-dropdown__column">
										<?php if ( ! empty( $column['heading'] ) ) : ?>
											<p class="nav-dropdown__heading"><?php echo esc_html( $column['heading'] ); ?></p>
										<?php endif; ?>
										<ul>
											<?php foreach ( $column['items'] as $link ) : ?>
												<li>
													<a href="<?php echo esc_url( $link['href'] ); ?>"<?php echo ! empty( $link['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $link['label'] ); ?></a>
													<?php if ( ! empty( $link['children'] ) ) : ?>
														<ul class="nav-dropdown__sublist">
															<?php foreach ( $link['children'] as $child ) : ?>
																<li><a href="<?php echo esc_url( $child['href'] ); ?>"><?php echo esc_html( $child['label'] ); ?></a></li>
															<?php endforeach; ?>
														</ul>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endforeach; ?>
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
