<?php
/**
 * Template Name: Homepage 2
 *
 * Alternate, more editorial/elegant treatment of the real homepage (page 454) — same facts
 * (copy, photos, stats, locations) reused verbatim, restructured into a fresh composition:
 * asymmetric cinematic hero, a stat strip, alternating "Why Skyline" rows, a pill-cloud
 * occasions band, and an editorial pull-quote testimonial. Deliberately isolated from the rest
 * of the theme — its own stylesheet (homepage-2.css), conditionally enqueued only on this
 * template (functions.php) — so nothing here can affect any other page, and page.php's own
 * design stays untouched. Header/footer/newsletter reuse the normal site-wide template parts on
 * purpose: navigation should stay identical wherever a visitor lands.
 */

$hero_bg = get_template_directory_uri() . '/assets/images/hero-placeholder.jpg';
$uploads = 'https://178-156-192-164.sslip.io/wp-content/uploads/2026/08/';
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

<main class="hp2">

	<!-- ===== Hero ===== -->
	<section class="hp2-hero">
		<div class="hp2-hero__bg" style="background-image:url(<?php echo esc_url( $uploads . 'home-hero-bg-1024x683.jpg' ); ?>)" data-parallax></div>
		<div class="hp2-hero__inner">
			<div class="hp2-hero__content">
				<span class="hp2__eyebrow hp2__eyebrow--light">New York Harbor &middot; Since 1993</span>
				<h1 class="hp2-hero__headline" data-split-lines>
					<span class="hp2-hero__line"><span>NYC Dinner Cruises</span></span>
					<span class="hp2-hero__line"><span>&amp; Private Yacht Charters</span></span>
				</h1>
				<p class="hp2-hero__sub">Our &ldquo;Super Yacht&rdquo; Skyline Princess is the only vessel sailing New York Harbor with an enclosed rooftop deck &mdash; the most versatile charter yacht in the Northeast, sailing past the Statue of Liberty in every season.</p>
				<div class="hp2-hero__ctas">
					<a class="btn btn-gold" href="/contact-us/request-your-quote/">Schedule a Public Cruise</a>
					<a class="btn btn-outline-white" href="/the-great-escape-yacht-rental/">The Great Escape &mdash; Private Charter</a>
				</div>
				<div class="hp2-hero__badges">
					<span class="hp2-hero__badge"><strong>1993</strong>Est.</span>
					<span class="hp2-hero__badge"><strong>A+</strong>BBB Rating</span>
					<span class="hp2-hero__badge"><strong>450</strong>Guest Capacity</span>
					<span class="hp2-hero__badge"><strong>30+</strong>Years Excellence</span>
				</div>
			</div>

			<form class="hp2-hero__form">
				<h3>Submit an Inquiry</h3>
				<label>Full Name <span>*</span><input type="text" name="full_name" required /></label>
				<label>Email Address <span>*</span><input type="email" name="email" required /></label>
				<label>Phone Number <span>*</span><input type="tel" name="phone" required /></label>
				<button type="submit" class="btn btn-gold">Submit</button>
			</form>
		</div>

		<div class="hp2-hero__scrollcue" aria-hidden="true"><span></span><em>Scroll</em></div>
	</section>

	<!-- ===== Stat strip ===== -->
	<section class="hp2-stats">
		<div class="hp2-stats__row">
			<div class="hp2-stat"><span class="hp2-stat__num" data-count="30">0</span><span class="hp2-stat__num-suffix">+</span><div class="hp2-stat__label">Years Sailing NY Harbor</div></div>
			<div class="hp2-stat"><span class="hp2-stat__num" data-count="450">0</span><div class="hp2-stat__label">Guest Capacity</div></div>
			<div class="hp2-stat"><span class="hp2-stat__num" data-count="120">0</span><div class="hp2-stat__label">Feet, Three Full Decks</div></div>
			<div class="hp2-stat"><span class="hp2-stat__num-suffix">A+</span><div class="hp2-stat__label">Better Business Bureau</div></div>
		</div>
	</section>

	<!-- ===== Our Story ===== -->
	<section class="hp2-story reveal">
		<div class="hp2-story__body">
			<span class="hp2__eyebrow">Our Story</span>
			<p class="hp2-story__quote">New York&rsquo;s premier provider of <em>luxury yacht experiences</em> since 1993.</p>
			<p>We&rsquo;ve hosted thousands of unforgettable events on the waters surrounding Manhattan, combining breathtaking views with world-class service and cuisine. Our fleet of elegantly appointed yachts provides the perfect setting for your most important celebrations, from intimate gatherings to grand affairs.</p>
			<p style="margin-top:16px">With a commitment to excellence and attention to detail, we transform your vision into an experience you and your guests will treasure forever.</p>
		</div>
		<div class="hp2-story__photo">
			<img src="<?php echo esc_url( $uploads . 'home-who-we-are-1024x683.jpg' ); ?>" alt="Skyline Princess yacht on New York Harbor" />
		</div>
	</section>

	<!-- ===== Signature Experiences ===== -->
	<section class="hp2-experiences reveal">
		<div class="hp2-experiences__inner">
			<div class="hp2-section-head">
				<div>
					<span class="hp2__eyebrow">What We Host</span>
					<h2>Signature Experiences</h2>
				</div>
				<p>From intimate gatherings to grand celebrations &mdash; every event finds its setting aboard the Skyline Princess.</p>
			</div>
			<div class="hp2-exp-grid">
				<a class="hp2-exp-card" href="/weddings/">
					<img src="<?php echo esc_url( $uploads . 'home-events-featured-full.png' ); ?>" alt="Wedding aboard Skyline Cruises" />
					<span class="hp2-exp-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M17 7H8M17 7V16"/></svg></span>
					<div class="hp2-exp-card__body">
						<span class="hp2-exp-card__num">01</span>
						<h3>Weddings</h3>
						<p>A waterfront ceremony and reception surrounded by the NYC skyline.</p>
					</div>
				</a>
				<a class="hp2-exp-card" href="/corporate-cruises/">
					<img src="<?php echo esc_url( $uploads . 'home-events-photo2.png' ); ?>" alt="Corporate cruise" />
					<span class="hp2-exp-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M17 7H8M17 7V16"/></svg></span>
					<div class="hp2-exp-card__body">
						<span class="hp2-exp-card__num">02</span>
						<h3>Corporate Cruises</h3>
						<p>Business functions large and small, fully hosted on the water.</p>
					</div>
				</a>
				<a class="hp2-exp-card" href="/nyc-party-cruises/">
					<img src="<?php echo esc_url( $uploads . 'home-events-photo3.png' ); ?>" alt="Private party cruise" />
					<span class="hp2-exp-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M17 7H8M17 7V16"/></svg></span>
					<div class="hp2-exp-card__body">
						<span class="hp2-exp-card__num">03</span>
						<h3>Private Parties</h3>
						<p>Eat, drink, and dance the night away under the city skyline.</p>
					</div>
				</a>
				<a class="hp2-exp-card" href="/nyc-holiday-cruises/">
					<img src="<?php echo esc_url( $uploads . 'home-events-photo4.png' ); ?>" alt="Holiday cruise" />
					<span class="hp2-exp-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M17 7H8M17 7V16"/></svg></span>
					<div class="hp2-exp-card__body">
						<span class="hp2-exp-card__num">04</span>
						<h3>Holiday Cruises</h3>
						<p>Turnkey packages customized for your group, menu, and port.</p>
					</div>
				</a>
				<a class="hp2-exp-card" href="/the-great-escape-yacht-rental/">
					<img src="<?php echo esc_url( $uploads . 'home-events-photo5.png' ); ?>" alt="Private event cruise" />
					<span class="hp2-exp-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M17 7H8M17 7V16"/></svg></span>
					<div class="hp2-exp-card__body">
						<span class="hp2-exp-card__num">05</span>
						<h3>Private Event Cruises</h3>
						<p>Cocktails, buffet, and a DJ &mdash; for groups of any size.</p>
					</div>
				</a>
			</div>
		</div>
	</section>

	<!-- ===== Why Skyline — pinned scroll stack: section stays pinned in the viewport while its
	     4 text+photo panels crossfade over one another as the user scrolls; once the last panel
	     has been seen, the page continues scrolling normally into the next section. Driven by
	     hp2.js (data-pin-stack), pure CSS fallback (plain stacked rows) if JS/IntersectionObserver
	     is unavailable or prefers-reduced-motion is set — see homepage-2.css. ===== -->
	<section class="hp2-why">
		<div class="hp2-section-head">
			<div>
				<span class="hp2__eyebrow">The Difference</span>
				<h2>Why Skyline Cruises</h2>
			</div>
		</div>

		<div class="hp2-why-stack" data-pin-stack>
			<div class="hp2-why-sticky">
				<div class="hp2-why-panel is-active" data-panel="0">
					<div class="hp2-why-panel__text">
						<span class="hp2-why-row__num">01 / 04</span>
						<h3>Unmatched Views</h3>
						<p>Sail past iconic landmarks including the Statue of Liberty, Brooklyn Bridge, and the glittering Manhattan skyline &mdash; routes carefully planned to showcase the best of New York Harbor.</p>
					</div>
					<div class="hp2-why-panel__photo"><img src="<?php echo esc_url( $uploads . 'home-why-1-1024x683.jpg' ); ?>" alt="Manhattan skyline view from the water" /></div>
				</div>
				<div class="hp2-why-panel" data-panel="1">
					<div class="hp2-why-panel__text">
						<span class="hp2-why-row__num">02 / 04</span>
						<h3>Exceptional Cuisine</h3>
						<p>Our culinary team creates custom menus featuring fresh, locally-sourced ingredients &mdash; from elegant plated dinners to lavish buffets, every dish prepared to perfection.</p>
					</div>
					<div class="hp2-why-panel__photo"><img src="<?php echo esc_url( $uploads . 'home-why-2-1024x683.jpg' ); ?>" alt="Onboard dining" /></div>
				</div>
				<div class="hp2-why-panel" data-panel="2">
					<div class="hp2-why-panel__text">
						<span class="hp2-why-row__num">03 / 04</span>
						<h3>Professional Service</h3>
						<p>Our experienced crew and event coordinators ensure every detail is flawlessly executed &mdash; from planning to execution, we&rsquo;re with you every step of the way.</p>
					</div>
					<div class="hp2-why-panel__photo"><img src="<?php echo esc_url( $uploads . 'home-why-3-1024x683.jpg' ); ?>" alt="Skyline Cruises crew" /></div>
				</div>
				<div class="hp2-why-panel" data-panel="3">
					<div class="hp2-why-panel__text">
						<span class="hp2-why-row__num">04 / 04</span>
						<h3>Flexible Options</h3>
						<p>Choose from our public dinner cruises or charter an entire yacht for your private event &mdash; customizable packages built to fit any budget and vision.</p>
					</div>
					<div class="hp2-why-panel__photo"><img src="<?php echo esc_url( $uploads . 'home-why-4-1024x683.jpg' ); ?>" alt="Private charter yacht" /></div>
				</div>
				<div class="hp2-why-progress" aria-hidden="true">
					<span data-dot="0" class="is-active"></span>
					<span data-dot="1"></span>
					<span data-dot="2"></span>
					<span data-dot="3"></span>
				</div>
			</div>
		</div>
	</section>

	<!-- ===== Occasions ===== -->
	<section class="hp2-occasions reveal">
		<span class="hp2__eyebrow hp2__eyebrow--light">Every Celebration</span>
		<h2>Perfect for <em>Any Occasion</em></h2>
		<p class="hp2-occasions__sub">Whatever you&rsquo;re celebrating, we&rsquo;ll make it extraordinary.</p>
		<div class="hp2-pill-cloud">
			<span class="hp2-pill">Weddings &amp; Receptions</span>
			<span class="hp2-pill">Corporate Events</span>
			<span class="hp2-pill">Birthday Celebrations</span>
			<span class="hp2-pill">Anniversaries</span>
			<span class="hp2-pill">Bar &amp; Bat Mitzvahs</span>
			<span class="hp2-pill">Graduation Parties</span>
			<span class="hp2-pill">Holiday Parties</span>
			<span class="hp2-pill">Team Building Events</span>
			<span class="hp2-pill">Product Launches</span>
			<span class="hp2-pill">Client Appreciation</span>
			<span class="hp2-pill">Retirement Parties</span>
			<span class="hp2-pill">Engagement Celebrations</span>
		</div>
	</section>

	<!-- ===== Gallery ===== -->
	<section class="hp2-gallery reveal">
		<div class="hp2-section-head">
			<div>
				<span class="hp2__eyebrow">In Focus</span>
				<h2>Cruise Gallery</h2>
			</div>
			<p>See what makes our experiences unforgettable.</p>
		</div>
		<div class="hp2-gallery__grid">
			<a href="https://www.instagram.com/skylinecruisesny/" target="_blank" rel="noopener"><img src="<?php echo esc_url( $uploads . 'home-gallery-1-1024x683.jpg' ); ?>" alt="" /></a>
			<a href="https://www.instagram.com/skylinecruisesny/" target="_blank" rel="noopener"><img src="<?php echo esc_url( $uploads . 'home-gallery-2-1024x731.jpg' ); ?>" alt="" /></a>
			<a href="https://www.instagram.com/skylinecruisesny/" target="_blank" rel="noopener"><img src="<?php echo esc_url( $uploads . 'home-gallery-3-1-1024x683.jpg' ); ?>" alt="" /></a>
			<a href="https://www.instagram.com/skylinecruisesny/" target="_blank" rel="noopener"><img src="<?php echo esc_url( $uploads . 'home-gallery-4-1024x683.jpg' ); ?>" alt="" /></a>
		</div>
		<div class="hp2-gallery__cta">
			<a class="btn btn-outline-navy" href="https://www.instagram.com/skylinecruisesny/" target="_blank" rel="noopener">Follow Us on Instagram</a>
		</div>
	</section>

	<!-- ===== Testimonial ===== -->
	<section class="hp2-testimonial reveal">
		<span class="hp2-testimonial__mark">&ldquo;</span>
		<blockquote>Carol, a sincere thanks to you and the entire crew of Skyline Cruises. You really made my husband Nygil&rsquo;s 50th birthday a memorable one &mdash; the hospitality of the crew made everything seem so easy.</blockquote>
		<cite>Leslin Fraiser</cite>
	</section>

	<!-- ===== Notes From the Deck ===== -->
	<section class="hp2-notes reveal">
		<div class="hp2-section-head">
			<div>
				<span class="hp2__eyebrow">The Blog</span>
				<h2>Notes From the Deck</h2>
			</div>
			<p>Tips, stories, and inspiration from our crew.</p>
		</div>
		<div class="hp2-notes__grid">
			<a class="hp2-note" href="/notes-from-the-deck/">
				<div class="hp2-note__photo"><img src="<?php echo esc_url( $uploads . 'home-blog-1-1024x683.jpg' ); ?>" alt="" /></div>
				<span class="hp2-note__date">February 15, 2026</span>
				<h3>Planning the Perfect Yacht Wedding in NYC</h3>
				<p>Top tips for creating a magical waterfront wedding, from timing to menu.</p>
			</a>
			<a class="hp2-note" href="/notes-from-the-deck/">
				<div class="hp2-note__photo"><img src="<?php echo esc_url( $uploads . 'home-blog-2-1024x640.jpg' ); ?>" alt="" /></div>
				<span class="hp2-note__date">February 8, 2026</span>
				<h3>Best Times to Cruise New York Harbor</h3>
				<p>The most spectacular times to see the skyline, from golden hour to sunset.</p>
			</a>
			<a class="hp2-note" href="/notes-from-the-deck/">
				<div class="hp2-note__photo"><img src="<?php echo esc_url( $uploads . 'home-blog-3-1024x683.jpg' ); ?>" alt="" /></div>
				<span class="hp2-note__date">January 28, 2026</span>
				<h3>5 Reasons to Choose a Yacht for Your Corporate Event</h3>
				<p>Why hosting on the water creates lasting impressions and stronger teams.</p>
			</a>
		</div>
	</section>

	<!-- ===== Where We Sail ===== -->
	<section class="hp2-sail reveal">
		<div class="hp2-sail__inner">
			<div class="hp2-sail__main">
				<span class="hp2__eyebrow">Home Port</span>
				<h3>World&rsquo;s Fair Marina</h3>
				<p>1 World&rsquo;s Fair Marina, Flushing, NY 11368 &mdash; free parking available.</p>
				<ul class="hp2-sail__also">
					<li><strong>Also sailing from:</strong></li>
					<li>Lincoln Harbor, Weehawken, NJ</li>
					<li>North Cove Marina, Battery Park</li>
					<li>Liberty Landing Marina, Jersey City</li>
				</ul>
			</div>
			<div class="hp2-region-grid">
				<div class="hp2-region"><h4>Westchester</h4><p>Yonkers &amp; New Rochelle</p></div>
				<div class="hp2-region"><h4>Queens</h4><p>Flushing Meadows Park</p></div>
				<div class="hp2-region"><h4>Manhattan</h4><p>Chelsea Piers, WTC, Pier 36</p></div>
				<div class="hp2-region"><h4>New Jersey</h4><p>Jersey City</p></div>
				<div class="hp2-region"><h4>Connecticut</h4><p>Stamford &amp; Norwalk</p></div>
				<div class="hp2-region"><h4>Long Island</h4><p>Port Washington &amp; Glen Cove</p></div>
			</div>
		</div>
	</section>

	<!-- ===== FAQ ===== -->
	<section class="hp2-faq reveal">
		<div class="hp2-faq__head">
			<span class="hp2__eyebrow">Good to Know</span>
			<h2>Frequently Asked Questions</h2>
		</div>

		<div class="hp2-faq__list">
			<details class="hp2-faq-item">
				<summary><span>Isn&rsquo;t chartering a yacht more expensive than a catering hall?</span></summary>
				<p>The costs are not as different as you think &mdash; many pricing options meet most budgets, and seasonal rates can run lower than land-based venues. It&rsquo;s also about value: an event that&rsquo;s unique and unforgettable is worth a little more.</p>
			</details>
			<details class="hp2-faq-item">
				<summary><span>What happens if the weather is bad? Will my guests get seasick?</span></summary>
				<p>No need to worry &mdash; the Skyline Princess is fully climate-controlled and sails rain or shine. We sail in calm, sheltered harbor waters, not the open ocean, so seasickness is never a problem.</p>
			</details>
			<details class="hp2-faq-item">
				<summary><span>Is the food as good as a restaurant?</span></summary>
				<p>Our food is some of the best in the harbor, prepared on board in our full galley the day of your event by our own chef, paired with high-quality premium liquors.</p>
			</details>
			<details class="hp2-faq-item">
				<summary><span>I have a large party &mdash; are there boats big enough?</span></summary>
				<p>The Skyline Princess is 120 feet long with three full passenger levels, US Coast Guard certified for up to 450 passengers &mdash; the most versatile dinner cruise charter yacht based outside Manhattan.</p>
			</details>
		</div>

		<p class="faq-view-more" style="text-align:center;margin-top:48px"><a class="btn btn-outline-navy" href="/about-faq/">View More FAQs</a></p>
	</section>

</main>

<?php get_template_part( 'template-parts/newsletter-cta' ); ?>
<?php get_template_part( 'template-parts/site-footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
