<?php
/**
 * Search results (search.php) — without it WordPress fell back to index.php, which dumps each
 * matching page's full the_content() one after another. Reuses home.php's .blog-teasers card
 * grid and .btn-outline-navy pagination, with a hero--short banner and the site search form
 * (searchform.php) so visitors can refine the query.
 */

add_filter( 'previous_posts_link_attributes', fn() => 'class="btn btn-outline-navy"' );
add_filter( 'next_posts_link_attributes', fn() => 'class="btn btn-outline-navy"' );
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
				<h1>Search Results</h1>
			</div>
		</div>
	</div>

	<div class="wp-block-group text-section">
		<p>
			<?php if ( have_posts() ) : ?>
				Showing results for &#8220;<?php echo esc_html( get_search_query() ); ?>&#8221;.
			<?php else : ?>
				Sorry, nothing matched &#8220;<?php echo esc_html( get_search_query() ); ?>&#8221;. Try different keywords, or call us at <strong><a href="tel:+17184461100">(718) 446-1100</a></strong> and our event planners will be happy to help.
			<?php endif; ?>
		</p>
		<?php get_search_form(); ?>
		<?php if ( ! have_posts() ) : ?>
			<div class="wp-block-buttons site-search__home"><div class="wp-block-button"><a class="wp-block-button__link btn btn-outline-navy" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Home</a></div></div>
		<?php endif; ?>
	</div>

	<?php if ( have_posts() ) : ?>
		<div class="wp-block-group blog-teasers">
			<div class="blog-teasers__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="blog-teasers__card">
						<a href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large' ); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/newsletter-bg.jpg' ); ?>" alt="" />
							<?php endif; ?>
						</a>
						<div class="blog-teasers__body">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<a href="<?php the_permalink(); ?>">Read More &rarr;</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>

			<nav class="blog-teasers-pagination" aria-label="Search results pagination">
				<?php
				previous_posts_link( '&larr; Previous Results' );
				next_posts_link( 'More Results &rarr;' );
				?>
			</nav>
		</div>
	<?php endif; ?>
</main>

<?php get_template_part( 'template-parts/newsletter-cta' ); ?>
<?php get_template_part( 'template-parts/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
