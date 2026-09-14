<?php
/**
 * Newsletter signup — full-bleed photo card, gold "Subscribe" pill.
 * Pixel-identical across all 55 sampled Figma pages. Always injected, not page content.
 *
 * 2026-09-15: the hardcoded <form> (no method/action wired to anything real) replaced with the
 * real GHL newsletter form (assets/js/ghl-form-embed.js — params-before-mount, form_embed.js
 * required). This template part is injected on every page (page.php/single.php/index.php/
 * home.php), so this is the single edit point for the newsletter form sitewide.
 */
$bg_image = get_template_directory_uri() . '/assets/images/newsletter-bg.jpg'; // TODO: swap in the real photo
?>
<section class="newsletter-section">
	<div class="newsletter-card" style="background-image: linear-gradient(162.03deg, rgba(11,44,77,0.663) 0%, rgba(31,78,121,0.585) 100%), url('<?php echo esc_url( $bg_image ); ?>');">
		<div class="newsletter-card__inner">
			<h2>Sign Up For <em>Our Newsletter</em></h2>
			<p>Get exclusive offers, event tips, and the latest news from the deck</p>
			<div class="ghl-form-embed newsletter-form" data-ghl-form-id="mIDvPYGXwOFDNhOfLEGO" data-ghl-form-name="Website Newsletter" style="min-height:340px"></div>
		</div>
	</div>
</section>
