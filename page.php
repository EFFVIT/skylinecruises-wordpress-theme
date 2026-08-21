<?php
/**
 * Page template — full-width shell, no sidebar. Deliberately kept minimal since this theme
 * only needs to support this site's pages, not arbitrary WP feature depth.
 * Structure: site-header (overlaid on whatever the first content block is) -> the_content() ->
 * newsletter-cta -> site-footer. Header/footer/newsletter are theme-level, never page content.
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
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>

<?php get_template_part( 'template-parts/newsletter-cta' ); ?>
<?php get_template_part( 'template-parts/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
