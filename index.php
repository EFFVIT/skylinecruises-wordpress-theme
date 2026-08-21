<?php
/**
 * Fallback template — required by WordPress for a theme to be valid.
 * Same shell as page.php; this site is pages-only, index.php should rarely if ever render.
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
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	else :
		echo '<p style="padding:120px 157px;">Nothing found.</p>';
	endif;
	?>
</main>

<?php get_template_part( 'template-parts/newsletter-cta' ); ?>
<?php get_template_part( 'template-parts/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
