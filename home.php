<?php
/**
 * Posts page (home.php) — used automatically for whichever page is set as "Posts page" in
 * Settings > Reading (here, /notes-from-the-deck/, matching the label every nav/footer link on
 * this theme already points at — see template-parts/site-header.php and site-footer.php). No
 * archive template existed before this file; WordPress silently fell back to index.php, which
 * dumps each post's full the_content() one after another (no template exists to do otherwise).
 *
 * Reuses the exact ".blog-teasers" card markup/CSS already built for the homepage's own "Notes
 * From the Deck" preview section (patterns.css) — same card shape, now looped over the real,
 * paginated post query instead of 3 hardcoded posts. Also gets the same .hero-outer/.hero banner
 * every other page opens with, instead of skipping straight to the grid.
 */

// Reuses .btn/.btn-outline-navy (patterns.css) on the prev/next links, same component used for
// the homepage's own "View More Blogs" button, rather than introducing a second button style.
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
		<div class="hero" style="background-image:url(<?php echo esc_url( get_template_directory_uri() . '/assets/images/testimonial-bg.jpg' ); ?>)">
			<div class="hero__content">
				<h1>Notes From the Deck</h1>
			</div>
		</div>
	</div>

	<div class="wp-block-group blog-teasers">
		<p class="intro-copy">Tips, stories, and inspiration from our crew</p>

		<?php if ( have_posts() ) : ?>
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
							<span class="blog-teasers__date"><?php echo esc_html( get_the_date() ); ?></span>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<a href="<?php the_permalink(); ?>">Read More &rarr;</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>

			<nav class="blog-teasers-pagination" aria-label="Blog pagination">
				<?php
				previous_posts_link( '&larr; Newer Posts' );
				next_posts_link( 'Older Posts &rarr;' );
				?>
			</nav>
		<?php else : ?>
			<p>No posts yet &mdash; check back soon.</p>
		<?php endif; ?>
	</div>
</main>

<?php get_template_part( 'template-parts/newsletter-cta' ); ?>
<?php get_template_part( 'template-parts/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
