#!/usr/bin/env node
/**
 * Bulk-create Skyline Cruises pages via the WordPress REST API.
 *
 * Reads a JSON manifest (see pages-manifest.example.json for the shape — the real manifest is
 * derived from /Users/jefeffvit/Documents/Skyline Interior Pages.xlsx), composes each page's
 * block content from the same section shapes registered as PHP patterns in /patterns, and POSTs
 * every page to /wp-json/wp/v2/pages as status:"draft" — never "publish". Auth is HTTP Basic
 * using a WordPress Application Password (NOT the account's real login password — see
 * Users -> Profile -> Application Passwords on the target site).
 *
 * IMPORTANT: real page copy/images must be pulled verbatim from the live skylinecruises.com page
 * (curl + an HTML parser, same convention used for the Figma build — never invented), and filled
 * into each manifest row's `data` object before running this against real rows. The example
 * manifest ships with placeholder copy only, to prove the pipeline end-to-end.
 *
 * Usage:
 *   WP_BASE_URL=https://skyline-build.effvit.com \
 *   WP_USERNAME=your_wp_username \
 *   WP_APP_PASSWORD="abcd 1234 efgh 5678" \
 *   node scripts/build-pages.js scripts/pages-manifest.example.json
 */

const fs = require('fs');
const path = require('path');

const WP_BASE_URL = process.env.WP_BASE_URL;
const WP_USERNAME = process.env.WP_USERNAME;
const WP_APP_PASSWORD = process.env.WP_APP_PASSWORD;

if (!WP_BASE_URL || !WP_USERNAME || !WP_APP_PASSWORD) {
	console.error('Missing WP_BASE_URL, WP_USERNAME, or WP_APP_PASSWORD env vars. See file header for usage.');
	process.exit(1);
}

const checkIcon = '/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/check-circle.svg';

// ---- Section builders — mirror the block markup registered in /patterns/*.php, parameterized ----

// Hero photo is a rounded card (border-radius 23px) inset 28px on every side of the hero frame,
// confirmed via direct Figma read of node 109:9 "Photo Card" — not a flush full-bleed rectangle.
// ".hero-outer" is the inset/page-bg frame; ".hero" is the rounded card itself.
const hero = ({ h1, addressSubhead = '', cta = 'Book Now', bgImage = '', short = false }) => `<!-- wp:group {"className":"hero-outer"} -->
<div class="wp-block-group hero-outer">
<div class="hero${short ? ' hero--short' : ''}" style="background-image:url(${bgImage})">
<div class="hero__content">
<!-- wp:heading {"level":1} --><h1>${h1}</h1><!-- /wp:heading -->
${addressSubhead ? `<!-- wp:paragraph {"className":"hero__address"} --><p class="hero__address">${addressSubhead}</p><!-- /wp:paragraph -->` : ''}
<!-- wp:paragraph {"className":"trust-badges"} --><p class="trust-badges"><span class="trust-badge"><span>Since 1993</span></span><span class="trust-badge"><span>A+ BBB Rating</span></span><span class="trust-badge"><span>30+ Years Excellence</span></span></p><!-- /wp:paragraph -->
<!-- wp:buttons {"className":"hero__cta"} --><div class="wp-block-buttons hero__cta"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">${cta}</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div>
</div>
</div>
<!-- /wp:group -->`;

const featuresPair = () => `<!-- wp:group {"className":"features-pair"} -->
<div class="wp-block-group features-pair">
<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-service.png" alt="Memorable Service" /></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Memorable Service</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Our attentive crew takes care of every detail, so you can focus on making memories with your guests.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-sailing.png" alt="Smooth Sailing" /></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Smooth Sailing</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Enjoy calm, scenic waters aboard a well-maintained fleet built for a comfortable ride, rain or shine.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->`;

// Canonical "Testimonial Template" (Figma node 103:177): full-bleed rounded photo card, "Skyline
// Testimonials" heading fixed, quote wrapped in literal curly quotes (not a separate glyph),
// attribution prefixed with a plain hyphen "- Name" (this template's own convention — the plain
// flat "7:21" version used elsewhere had a different en-dash convention; don't mix the two up).
// Heading + background photo are fixed section chrome; only quote + attribution vary per page.
// NOTE: the `card` param is a placeholder for a possible School Events variant — unverified
// against that category's real Figma node, don't trust it until checked directly.
// Two genuinely different components, not one modified by a class (confirmed via direct Figma
// reads of nodes 103:177 vs 120:282): the default is a full-bleed rounded PHOTO card; the School
// Events "card" variant is a flat light-gray box with NO photo, and its real quote text embeds
// its own sign-off inline (e.g. "...thank you all! – Sincerely, Susan Fitzgerald") rather than
// using a separate attribution line — so `attribution` is ignored and no attribution paragraph
// is rendered when card=true; fold the sign-off into `quote` itself for that variant.
const testimonial = ({ quote, attribution, bgImage = '/wp-content/themes/skylinecruises-wordpress-theme/assets/images/testimonial-bg.jpg', card = false }) => `<!-- wp:group {"className":"testimonial-outer"} -->
<div class="wp-block-group testimonial-outer">
<div class="testimonial${card ? ' testimonial--card' : ''}"${card ? '' : ` style="background-image:url(${bgImage})"`}>
<!-- wp:heading {"level":2,"className":"testimonial__heading"} --><h2 class="testimonial__heading">Skyline <em>Testimonials</em></h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"testimonial__quote"} --><p class="testimonial__quote">${card ? quote : `&#8220;${quote}&#8221;`}</p><!-- /wp:paragraph -->
${card ? '' : `<!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">- ${attribution}</p><!-- /wp:paragraph -->`}
</div>
</div>
<!-- /wp:group -->`;

const closingCta = ({ heading, body, cta = 'Book Now' }) => `<!-- wp:group {"className":"closing-cta"} -->
<div class="wp-block-group closing-cta">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>${body}</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">${cta}</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div>
<!-- /wp:group -->`;

const checklistItems = (items, itemClass = 'checklist-item') => items.map(
	(item) => `<!-- wp:paragraph {"className":"${itemClass}"} --><p class="${itemClass}"><img class="check-icon" src="${checkIcon}" alt="" />${item}</p><!-- /wp:paragraph -->`
).join('\n');

const photoChecklistRow = ({ heading, intro, photo, items, gridCols = 1, itemClass = 'checklist-item', cta = 'Book Now' }) => `<!-- wp:group {"className":"photo-checklist-row"} -->
<div class="wp-block-group photo-checklist-row">
<!-- wp:group {"className":"photo-checklist-row__intro"} --><div class="wp-block-group photo-checklist-row__intro"><!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->${intro ? `<!-- wp:paragraph {"className":"intro-copy"} --><p class="intro-copy">${intro}</p><!-- /wp:paragraph -->` : ''}</div><!-- /wp:group -->
<!-- wp:group {"className":"photo-checklist-row__body"} -->
<div class="wp-block-group photo-checklist-row__body">
<!-- wp:image {"className":"photo-checklist-row__photo"} --><figure class="wp-block-image photo-checklist-row__photo"><img src="${photo}" alt="" /></figure><!-- /wp:image -->
<!-- wp:group {"className":"photo-checklist-row__list"} --><div class="wp-block-group photo-checklist-row__list"${gridCols > 1 ? ` style="display:grid;grid-template-columns:repeat(${gridCols},1fr);gap:16px 32px;"` : ''}>
${checklistItems(items, itemClass)}
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">${cta}</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->`;

const routeMap = ({ image }) => `<!-- wp:group {"className":"route-map"} -->
<div class="wp-block-group route-map">
<!-- wp:heading {"level":2} --><h2>Our Cruise Route</h2><!-- /wp:heading -->
<!-- wp:image --><figure class="wp-block-image"><img src="${image}" alt="Skyline Cruises route map" /></figure><!-- /wp:image -->
</div>
<!-- /wp:group -->`;

// Directions heading is real per-page copy ("Directions to Liberty Landing Marina"), never a
// generic "Getting There" — confirmed via direct Figma read (node 134:137).
const directionsBlock = ({ heading, intro, routes, transit }) => `<!-- wp:group {"className":"directions-block"} -->
<div class="wp-block-group directions-block">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>${intro}</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"directions-block__group"} --><div class="wp-block-group directions-block__group"><!-- wp:heading {"level":3} --><h3>Driving</h3><!-- /wp:heading -->
${routes.map((r) => `<!-- wp:paragraph {"className":"directions-block__route"} --><p class="directions-block__route"><strong>${r.from}:</strong> ${r.directions}</p><!-- /wp:paragraph -->`).join('\n')}
</div><!-- /wp:group -->
<!-- wp:group {"className":"directions-block__group"} --><div class="wp-block-group directions-block__group"><!-- wp:heading {"level":3} --><h3>Mass Transit</h3><!-- /wp:heading --><!-- wp:paragraph --><p>${transit}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->`;

// Text section — plain heading + paragraphs, no photo. Confirmed as a genuinely shared shape
// across Private Event/Party, School Events, and Port/Location intro sections (not invented).
// `ctaLine` is School Events' bold "Contact Us To Plan Your School Event!" trailing line — omit
// for categories that don't have one.
const textSection = ({ heading, paragraphs, ctaLine = '' }) => `<!-- wp:group {"className":"text-section"} -->
<div class="wp-block-group text-section">
${heading ? `<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->` : ''}
${paragraphs.map((p) => `<!-- wp:paragraph --><p>${p}</p><!-- /wp:paragraph -->`).join('\n')}
${ctaLine ? `<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">${ctaLine}</p><!-- /wp:paragraph -->` : ''}
</div>
<!-- /wp:group -->`;

// Tier cards (3-up) — Private Event/Party "Section - Party Tiers" (node 96:93).
const tierCards3up = ({ heading, cards }) => `<!-- wp:group {"className":"card-grid-section"} -->
<div class="wp-block-group card-grid-section">
<!-- wp:group {"className":"card-grid-section__intro"} --><div class="wp-block-group card-grid-section__intro"><!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading --></div><!-- /wp:group -->
<!-- wp:group {"className":"card-grid card-grid--3up"} --><div class="wp-block-group card-grid card-grid--3up">
${cards.map((c) => `<!-- wp:group {"className":"offering-card"} --><div class="wp-block-group offering-card"><!-- wp:heading {"level":3} --><h3>${c.title}</h3><!-- /wp:heading --><!-- wp:paragraph --><p>${c.body}</p><!-- /wp:paragraph --></div><!-- /wp:group -->`).join('\n')}
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->`;

// Style cards (2-up) — Private Event/Party "Section - Realizing Your Vision" (node 96:94).
// `closing` is the real trailing catering paragraph below the cards — don't drop it.
const styleCards2up = ({ heading, subheading, cards, closing }) => `<!-- wp:group {"className":"card-grid-section"} -->
<div class="wp-block-group card-grid-section">
<!-- wp:group {"className":"card-grid-section__intro"} --><div class="wp-block-group card-grid-section__intro"><!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading --><!-- wp:paragraph --><p>${subheading}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"card-grid card-grid--2up"} --><div class="wp-block-group card-grid card-grid--2up">
${cards.map((c) => `<!-- wp:group {"className":"offering-card"} --><div class="wp-block-group offering-card"><!-- wp:heading {"level":3} --><h3>${c.title}</h3><!-- /wp:heading --><!-- wp:paragraph --><p>${c.body}</p><!-- /wp:paragraph --></div><!-- /wp:group -->`).join('\n')}
</div><!-- /wp:group -->
<!-- wp:paragraph {"className":"card-grid-section__closing"} --><p class="card-grid-section__closing">${closing}</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->`;

// Marina grid (3x3) — School Events hub, real items must include the location suffix
// (e.g. "Pier 36 – Downtown Manhattan, NY"), confirmed via direct Figma read (node 120:234).
const marinaGrid3x3 = ({ items }) => `<!-- wp:group {"className":"marina-grid"} -->
<div class="wp-block-group marina-grid">
${checklistItems(items)}
</div>
<!-- /wp:group -->`;

// Ports list — non-hub School Events pages (e.g. Graduation), single-column with an optional
// "Private Cruises Only" flag per item.
const portsList = ({ heading, items }) => `<!-- wp:group {"className":"ports-list"} -->
<div class="wp-block-group ports-list">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:html --><div class="ports-list__items">
${items.map((i) => `<div class="ports-list__item">${checkIcon ? `<img class="check-icon" src="${checkIcon}" alt="" />` : ''}<span>${i.name}${i.private ? '<span class="private-flag">Private Cruises Only</span>' : ''}</span></div>`).join('\n')}
</div><!-- /wp:html -->
</div>
<!-- /wp:group -->`;

const formPageShell = ({ heading, intro, embedHtml }) => `<!-- wp:group {"className":"form-page-shell"} -->
<div class="wp-block-group form-page-shell">
<!-- wp:group {"className":"form-page-shell__intro"} --><div class="wp-block-group form-page-shell__intro"><!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading --><!-- wp:paragraph --><p>${intro}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:html --><div class="form-page-shell__embed">${embedHtml}</div><!-- /wp:html -->
</div>
<!-- /wp:group -->`;

// ---- Per-template composition — same ordering as the structural audit + consistency requirement:
//      one canonical section order per category, real content varies, structure never drifts. ----

// Every category's real section order below is confirmed via direct Figma reads of an actual
// built page in that category (node ids in the comment on each), not inferred or guessed.
const TEMPLATE_BUILDERS = {
	// 02 - NYC Dinner Cruises (node 3:575)
	'Public Cruise Service': (d) => [
		hero(d.hero),
		photoChecklistRow(d.checklist),
		featuresPair(),
		routeMap(d.routeMap),
		testimonial(d.testimonial),
		closingCta(d.closingCta),
	],
	// 17 - Dinner Cruise Buffet Menu (node 31:3) — separate plain intro section before the
	// buffet-options grid, no route map, larger 40px check icon (gridCols:2 + checklist-item--lg).
	'Buffet Menu': (d) => [
		hero(d.hero),
		textSection(d.intro),
		photoChecklistRow({ ...d.checklist, gridCols: 2, itemClass: 'checklist-item--lg' }),
		featuresPair(),
		testimonial(d.testimonial),
		closingCta(d.closingCta),
	],
	// 20 - Birthday Party Cruises (node 96:90) — CTA is "Get a Quote" everywhere on this
	// category, NOT "Book Now". No testimonial section on this category at all.
	'Private Event/Party': (d) => [
		hero({ ...d.hero, cta: d.hero.cta || 'Get a Quote' }),
		textSection(d.intro),
		tierCards3up(d.tierCards),
		styleCards2up(d.styleCards),
		featuresPair(),
		closingCta({ ...d.closingCta, cta: d.closingCta.cta || 'Get a Quote' }),
	],
	// 39 - School Events hub (node 117:209) — CTA is "Request a Quote". No features-pair
	// section on this category. Testimonial uses the flat gray "card" variant, not the photo
	// card. `itinerarySections` covers the paragraphs inside "Section - Itineraries" that
	// precede the marina grid on the same real section (node 120:228).
	'School Events': (d) => [
		hero({ ...d.hero, short: true, cta: d.hero.cta || 'Request a Quote' }),
		textSection(d.intro),
		textSection(d.itinerarySections),
		marinaGrid3x3(d.marinaGrid),
		textSection(d.spectacularViews),
		testimonial({ ...d.testimonial, card: true }),
		closingCta({ ...d.closingCta, cta: d.closingCta.cta || 'Request a Quote' }),
	],
	// Non-hub School Events pages (e.g. "40 Graduation") use a ports LIST instead of the hub's
	// marina GRID, and may include an entertainment-chips section — pass `extraSections` as an
	// array of already-built section strings (portsList(), entertainmentChips()-shaped) for
	// whichever page-specific sections apply; don't force chips onto pages that don't have them.
	'School Events (non-hub)': (d) => [
		hero({ ...d.hero, short: true, cta: d.hero.cta || 'Request a Quote' }),
		textSection(d.intro),
		...(d.extraSections || []),
		testimonial({ ...d.testimonial, card: true }),
		closingCta({ ...d.closingCta, cta: d.closingCta.cta || 'Request a Quote' }),
	],
	// 48 - Liberty Landing Marina (node 133:225) — leanest template, confirmed: hero (short,
	// with an address subhead, CTA "Book Now") + plain intro + directions block only. No
	// features/testimonial/closing-CTA-button section exists on this category at all — don't add one.
	'Port/Location': (d) => [
		hero({ ...d.hero, short: true, cta: d.hero.cta || 'Book Now' }),
		textSection(d.intro),
		directionsBlock(d.directions),
	],
	'About/Info': (d) => [...(d.sections || [])],
	'Utility/Form': (d) => [formPageShell(d.formShell)],
};

// ---- WP REST push ----

async function createDraftPage({ title, slug, parent, content }) {
	const auth = Buffer.from(`${WP_USERNAME}:${WP_APP_PASSWORD}`).toString('base64');
	const res = await fetch(`${WP_BASE_URL}/wp-json/wp/v2/pages`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			Authorization: `Basic ${auth}`,
		},
		body: JSON.stringify({ title, slug, parent, content, status: 'draft' }),
	});

	if (!res.ok) {
		const body = await res.text();
		throw new Error(`WP REST error ${res.status} for "${title}": ${body}`);
	}

	return res.json();
}

async function main() {
	const manifestPath = process.argv[2];
	if (!manifestPath) {
		console.error('Usage: node scripts/build-pages.js <manifest.json>');
		process.exit(1);
	}

	const manifest = JSON.parse(fs.readFileSync(path.resolve(manifestPath), 'utf8'));

	for (const row of manifest) {
		const builder = TEMPLATE_BUILDERS[row.template];
		if (!builder) {
			console.warn(`Skipping "${row.title}" — unknown template "${row.template}"`);
			continue;
		}

		const content = builder(row.data).join('\n');

		try {
			const page = await createDraftPage({ title: row.title, slug: row.slug, parent: row.parent, content });
			console.log(`Created draft: ${row.title} -> ${page.link} (id ${page.id})`);
		} catch (err) {
			console.error(`Failed: ${row.title} — ${err.message}`);
		}
	}
}

main();
