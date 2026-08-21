<?php
/**
 * Site header — floating white pill nav overlaid on the hero.
 * Pixel-identical across all 55 sampled Figma pages: logo, 8-item nav,
 * phone line, gold "Book Now" button. Always injected by page.php/index.php,
 * never editable page content, so a non-technical editor can't delete it.
 */
$logo = get_template_directory_uri() . '/assets/icons/logo.png'; // TODO: swap in the real logo asset
$nav_links = [
	'Cruises'        => '/cruises/',
	'Special Events'  => '/special-events/',
	'Weddings'        => '/weddings/',
	'The Ship'        => '/the-ship/',
	'Testimonials'    => '/about/clients-testimonials/',
	'FAQs'            => '/about/faq/',
	'Blog'            => '/blog/',
	'Contact'         => '/contact-us/',
];
?>
<header class="site-header">
	<nav class="nav-pill">
		<a class="nav-pill__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( $logo ); ?>" alt="Skyline Cruises" width="114" height="51" />
		</a>
		<ul class="nav-pill__links">
			<?php foreach ( $nav_links as $label => $href ) : ?>
				<li><a href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $label ); ?></a></li>
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
