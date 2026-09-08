<?php
/**
 * Single post template — theme had none before this (confirmed via repo listing: only index.php,
 * page.php, home.php existed), so every one of the 340 migrated posts was silently falling back to
 * index.php's raw the_content() dump: no hero, no title styling beyond inherited <title>, no date,
 * no image treatment, full-width unstyled prose. Same class of gap as home.php before it existed.
 *
 * Reuses the site's existing components rather than inventing new ones: the standard
 * .hero-outer/.hero--short banner (patterns/hero.php) for the title, and the exact
 * .blog-teasers grid/card markup (home.php) for "More From The Deck" so related posts look
 * identical to the archive/homepage, not a third card style.
 */

/**
 * The live-to-staging migration (see 02 - Daily Notes/2026-09-07.md) pulled each post's raw
 * content verbatim, and the source posts consistently open with the SAME photo as a manually
 * inserted <figure> block that was also set as the post's featured image (confirmed via direct
 * REST inspection: post 716's featured image and its content's first <figure> both resolve to
 * "Skyline-8-2", just different generated sizes). Showing the hero banner AND that leading figure
 * back-to-back would duplicate the same photo twice in a row. Detect the duplicate by filename
 * (stripping WordPress's own "-WIDTHxHEIGHT" size suffix) rather than guessing a fixed HTML
 * position, so it still works regardless of exactly how many blocks precede it.
 */
function skyline_strip_duplicate_lead_image( $content, $featured_url ) {
	if ( ! $featured_url ) {
		return $content;
	}
	if ( ! preg_match( '#^\s*<figure[^>]*class="[^"]*wp-block-image[^"]*"[^>]*>\s*<img[^>]+src="([^"]+)"[^>]*/?>\s*</figure>#i', $content, $match ) ) {
		return $content;
	}

	$normalize = function ( $url ) {
		$name = wp_basename( (string) parse_url( $url, PHP_URL_PATH ) );
		return strtolower( preg_replace( '/-\d+x\d+(?=\.\w+$)/', '', $name ) );
	};

	if ( $normalize( $match[1] ) !== $normalize( $featured_url ) ) {
		return $content;
	}

	$content = substr( $content, strlen( $match[0] ) );
	// Also drop any now-orphaned empty spacer blocks the same source content puts directly after
	// the lead image (wp:spacer, used purely as gap-before-first-paragraph in the original layout).
	$content = preg_replace( '#^(\s*<div[^>]*class="[^"]*wp-block-spacer[^"]*"[^>]*>\s*</div>\s*)+#i', '', $content );

	return $content;
}

$featured_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
$hero_bg      = $featured_url ? $featured_url : get_template_directory_uri() . '/assets/images/testimonial-bg.jpg';

$prev_post = get_previous_post();
$next_post = get_next_post();

$related = new WP_Query( [
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'post__not_in'        => [ get_the_ID() ],
	'ignore_sticky_posts'  => true,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'no_found_rows'       => true,
] );
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
	<?php while ( have_posts() ) : the_post(); ?>

		<div class="wp-block-group hero-outer">
			<div class="hero hero--short" style="background-image:url(<?php echo esc_url( $hero_bg ); ?>)">
				<div class="hero__content">
					<h1><?php the_title(); ?></h1>
					<p class="hero__address"><?php echo esc_html( get_the_date() ); ?></p>
				</div>
			</div>
		</div>

		<article class="post-article">
			<div class="post-article__inner">
				<div class="post-article__body">
					<?php
					$content = get_the_content();
					$content = apply_filters( 'the_content', $content );
					$content = skyline_strip_duplicate_lead_image( $content, $featured_url );
					echo $content;
					?>
				</div>

				<?php if ( $prev_post || $next_post ) : ?>
					<nav class="post-nav" aria-label="Post navigation">
						<?php if ( $prev_post ) : ?>
							<a class="btn btn-outline-navy post-nav__prev" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">&larr; <?php echo esc_html( get_the_title( $prev_post ) ); ?></a>
						<?php else : ?>
							<span></span>
						<?php endif; ?>
						<a class="btn btn-outline-navy post-nav__all" href="<?php echo esc_url( home_url( '/notes-from-the-deck/' ) ); ?>">All Posts</a>
						<?php if ( $next_post ) : ?>
							<a class="btn btn-outline-navy post-nav__next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>"><?php echo esc_html( get_the_title( $next_post ) ); ?> &rarr;</a>
						<?php else : ?>
							<span></span>
						<?php endif; ?>
					</nav>
				<?php endif; ?>
			</div>
		</article>

	<?php endwhile; ?>

	<?php if ( $related->have_posts() ) : ?>
		<div class="wp-block-group blog-teasers">
			<h2>More From <em>The Deck</em></h2>
			<p class="intro-copy">Tips, stories, and inspiration from our crew</p>
			<div class="blog-teasers__grid">
				<?php
				while ( $related->have_posts() ) :
					$related->the_post();
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
			<a class="btn btn-outline-navy blog-teasers__view-more" href="<?php echo esc_url( home_url( '/notes-from-the-deck/' ) ); ?>">View All Posts</a>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</main>

<?php get_template_part( 'template-parts/newsletter-cta' ); ?>
<?php get_template_part( 'template-parts/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
