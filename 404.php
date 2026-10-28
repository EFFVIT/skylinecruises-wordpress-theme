<?php
/**
 * 404 template — same shell as page.php, built from the site's own patterns (hero--short,
 * text-section) so a missing URL looks like any other page instead of falling back to
 * index.php's post loop. Offers a site search (searchform.php) and a Back to Home button.
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
			</div>
		</div>
	</div>

	<div class="wp-block-group text-section">
		<h2>Sorry, we couldn&#8217;t find that page</h2>
		<p>The page you&#8217;re looking for may have moved or no longer exists. Try searching the site, or call us at <strong><a href="tel:+17184461100">(718) 446-1100</a></strong> and our event planners will be happy to help.</p>
		<?php get_search_form(); ?>
		<div class="wp-block-buttons site-search__home"><div class="wp-block-button"><a class="wp-block-button__link btn btn-outline-navy" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Home</a></div></div>
	</div>
</main>

<?php get_template_part( 'template-parts/newsletter-cta' ); ?>
<?php get_template_part( 'template-parts/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
