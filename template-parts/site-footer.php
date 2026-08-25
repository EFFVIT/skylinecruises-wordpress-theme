<?php
/**
 * Site footer — 4-column grid (Brand+contact / Our Services / Quick Links / Legal) + copyright bar.
 * Pixel-identical across all 55 sampled Figma pages. Always injected, not page content.
 */
$logo = get_template_directory_uri() . '/assets/icons/logo.png'; // TODO: swap in the real logo asset
$year = wp_date( 'Y' );
?>
<footer class="site-footer">
	<div class="site-footer__inner">
	<div class="site-footer__columns">
		<div class="site-footer__brand">
			<img src="<?php echo esc_url( $logo ); ?>" alt="Skyline Cruises" width="114" height="51" />
			<p>New York's <strong>premier luxury</strong> yacht experience since 1993.</p>
			<div class="site-footer__contact">
				<div class="site-footer__contact-row">
					<svg viewBox="0 0 24 24" fill="none" stroke="#252a32" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
					<span>(718) 446-1100</span>
				</div>
				<div class="site-footer__contact-row">
					<svg viewBox="0 0 24 24" fill="none" stroke="#252a32" stroke-width="2"><path d="M4 4h16v16H4z" opacity="0"/><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
					<span>info@skylinecruises.com</span>
				</div>
				<div class="site-footer__contact-row">
					<svg viewBox="0 0 24 24" fill="none" stroke="#252a32" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
					<span><strong>World's Fair Marina</strong><br />Flushing, NY 11368</span>
				</div>
			</div>
		</div>

		<div class="site-footer__services">
			<h4>Our Services</h4>
			<ul>
				<li><a href="/nyc-dinner-cruises/">Dinner Cruises</a></li>
				<li><a href="/yacht-charter/">Private Charters</a></li>
				<li><a href="/weddings/">Wedding Packages</a></li>
				<li><a href="/corporate-cruises/">Corporate Events</a></li>
				<li><a href="/nyc-party-cruises/">Special Occasions</a></li>
			</ul>
		</div>

		<div class="site-footer__quicklinks">
			<h4>Quick Links</h4>
			<ul>
				<li><a href="/about-skyline-cruises/">About Us</a></li>
				<li><a href="/the-ship/">Our Fleet</a></li>
				<li><a href="/about-clients-testimonials/">Testimonials</a></li>
				<li><a href="/picture-gallery/">Gallery</a></li>
				<li><a href="/notes-from-the-deck/">Blog</a></li>
				<li><a href="/about-faq/">FAQs</a></li>
			</ul>
		</div>

		<div class="site-footer__legal">
			<h4>Legal</h4>
			<ul>
				<li><a href="/privacy-policy/">Privacy Policy</a></li>
				<li><a href="/cookie-policy/">Cookie Policy</a></li>
				<li><a href="/terms-of-service/">Terms of Service</a></li>
				<li><a href="/cancellation-policy/">Cancellation Policy</a></li>
				<li><a href="/accessibility/">Accessibility</a></li>
			</ul>
		</div>
	</div>

	<div class="site-footer__copyright">
		&copy; <?php echo esc_html( $year ); ?> Skyline Cruises. All rights reserved. | Proudly serving New York since 1993 | <a href="/privacy-policy/">Privacy Policy</a> | <a href="/cookie-policy/">Cookie Policy</a>
	</div>
	</div><!-- /.site-footer__inner -->
</footer>
