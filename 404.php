<?php
/**
 * 404 template — same shell as page.php, built from the site's own patterns (hero--short,
 * text-section, link-list) so a missing URL looks like any other page instead of falling back
 * to index.php's post loop. Static markup: there is no page content to render on a 404.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'template-parts/site-header' ); ?>

<main class="page-content">
	<div class="wp-block-group hero-outer">
		<div class="hero hero--short" style="background-image:url(<?php echo esc_url( content_url( '/uploads/2026/08/hero-sm.jpg' ) ); ?>)">
			<div class="hero__content">
				<h1>Page Not Found</h1>
				<p class="trust-badges"><span class="trust-badge"><span>Since 1993</span></span><span class="trust-badge"><span>A+ BBB Rating</span></span><span class="trust-badge"><span>30+ Years Excellence</span></span></p>
				<div class="wp-block-buttons hero__cta"><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Home</a></div></div>
			</div>
		</div>
	</div>

	<div class="wp-block-group text-section">
		<h2>Sorry, we couldn&#8217;t find that page</h2>
		<p>The page you&#8217;re looking for may have moved or no longer exists. Try one of the pages below, or call us at <strong><a href="tel:+17184461100">(718) 446-1100</a></strong> and our event planners will be happy to help.</p>
	</div>

	<div class="wp-block-group link-list">
		<ul class="link-list__items">
			<li><a href="<?php echo esc_url( home_url( '/nyc-dinner-cruises/' ) ); ?>">NYC Dinner Cruises</a></li>
			<li><a href="<?php echo esc_url( home_url( '/yacht-charter/' ) ); ?>">Private Yacht Charter</a></li>
			<li><a href="<?php echo esc_url( home_url( '/nyc-party-cruises/' ) ); ?>">Party Cruises</a></li>
			<li><a href="<?php echo esc_url( home_url( '/corporate-cruises/' ) ); ?>">Corporate Cruises</a></li>
			<li><a href="<?php echo esc_url( home_url( '/weddings/' ) ); ?>">Weddings</a></li>
			<li><a href="<?php echo esc_url( home_url( '/ports/' ) ); ?>">Our Departure Ports</a></li>
			<li><a href="<?php echo esc_url( home_url( '/notes-from-the-deck/' ) ); ?>">Notes From the Deck (Blog)</a></li>
			<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a></li>
		</ul>
	</div>
</main>

<?php get_template_part( 'template-parts/newsletter-cta' ); ?>
<?php get_template_part( 'template-parts/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
