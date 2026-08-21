<?php
/**
 * Newsletter signup — full-bleed photo card, gold "Subscribe" pill.
 * Pixel-identical across all 55 sampled Figma pages. Always injected, not page content.
 */
$bg_image = get_template_directory_uri() . '/assets/images/newsletter-bg.jpg'; // TODO: swap in the real photo
?>
<section class="newsletter-section">
	<div class="newsletter-card" style="background-image: linear-gradient(162.03deg, rgba(11,44,77,0.663) 0%, rgba(31,78,121,0.585) 100%), url('<?php echo esc_url( $bg_image ); ?>');">
		<div class="newsletter-card__inner">
			<h2>Sign Up For <em>Our Newsletter</em></h2>
			<p>Get exclusive offers, event tips, and the latest news from the deck</p>
			<form class="newsletter-form" method="post" action="">
				<input type="email" name="newsletter_email" placeholder="Enter your email address" required />
				<button type="submit" class="btn btn-gold">Subscribe</button>
			</form>
		</div>
	</div>
</section>
