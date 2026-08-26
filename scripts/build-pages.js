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

// Only enforced when this file is actually run directly (the REST-push path) — guarded so
// scripts/compose-about-info.js can `require()` this file just for its section composers without
// needing WP credentials, since composing HTML has nothing to do with pushing it.
if (require.main === module && (!WP_BASE_URL || !WP_USERNAME || !WP_APP_PASSWORD)) {
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

// Real bug found in the 2026-08-25 site-wide copy audit: this entire block was invented at build
// time, not scraped. Live-checked directly (multiple pages, e.g. /nyc-dinner-cruises/): the
// "Memorable Service" icon has NO visible caption heading on live at all (that string only ever
// exists as the icon's alt="Memorable Service" attribute, never rendered as an <h3>) — the real
// text living in that visual slot on live is Skyline's actual cash-bar/customize paragraph. The
// "Smooth Sailing" heading IS real and IS reused verbatim across multiple live pages, but its
// caption paragraph was rewritten into different, non-verbatim wording. Fixed both, using the
// verbatim text confirmed live on /nyc-dinner-cruises/ (and cross-checked identical on several
// other pages by the audit) — not an invented replacement.
const featuresPair = () => `<!-- wp:group {"className":"features-pair"} -->
<div class="wp-block-group features-pair">
<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-service.png" alt="Memorable Service" /></figure><!-- /wp:image --><!-- wp:paragraph --><p>Skyline&#8217;s attentive staff serves premium beer, wine, and liquor at the cash bar. Want something special? <a href="https://skylinecruises.com/contact-us/">Contact the event experts at Skyline</a> to customize your experience.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"features-pair__item"} --><div class="wp-block-group features-pair__item"><!-- wp:image --><figure class="wp-block-image"><img src="/wp-content/themes/skylinecruises-wordpress-theme/assets/icons/feature-sailing.png" alt="Smooth Sailing" /></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Smooth Sailing</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Our yacht is a floating banquet room designed for year-round cruising. It is fully climate-controlled for year-round outings and because it sails in the calm, sheltered waters of the famous New York Harbor or Long Island Sound, you&#8217;ll experience smooth sailing.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
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

// `photo` is optional — real bug found while building the Mother's Day page: its live checklist
// genuinely has no adjacent photo at all (not every cruise-type page pairs one), so forcing an
// `<img src="">` would render a broken image. Omit the photo figure entirely when absent instead
// of inventing one, and let the list use the freed-up width.
const photoChecklistRow = ({ heading, intro, photo = '', items, gridCols = 1, itemClass = 'checklist-item', cta = 'Book Now' }) => `<!-- wp:group {"className":"photo-checklist-row"} -->
<div class="wp-block-group photo-checklist-row">
<!-- wp:group {"className":"photo-checklist-row__intro"} --><div class="wp-block-group photo-checklist-row__intro"><!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->${intro ? `<!-- wp:paragraph {"className":"intro-copy"} --><p class="intro-copy">${intro}</p><!-- /wp:paragraph -->` : ''}</div><!-- /wp:group -->
<!-- wp:group {"className":"photo-checklist-row__body"} -->
<div class="wp-block-group photo-checklist-row__body">
${photo ? `<!-- wp:image {"className":"photo-checklist-row__photo"} --><figure class="wp-block-image photo-checklist-row__photo"><img src="${photo}" alt="" /></figure><!-- /wp:image -->` : ''}
<!-- wp:group {"className":"photo-checklist-row__list"} --><div class="wp-block-group photo-checklist-row__list"${gridCols > 1 ? ` style="display:grid;grid-template-columns:repeat(${gridCols},1fr);gap:16px 32px;"` : (!photo ? ' style="max-width:800px;margin-inline:auto;"' : '')}>
${checklistItems(items, itemClass)}
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">${cta}</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->`;

// Real Leaflet/OpenStreetMap embed (see patterns/route-map.php + assets/js/route-map.js) — pins
// the real departure marina + real NYC landmarks, plus a route line traced along the real
// navigable waterway (East River -> Upper NY Bay) between them. A boat can only travel on water,
// so following the real channel is a true depiction of the route even without the vessel's exact
// GPS track (2026-08-24, supersedes the earlier pins-only-no-line decision from 2026-08-21).
// `departure`/`landmarks`/`routePath` default to the standard World's Fair Marina -> NYC Harbor
// route shared by most Public Cruise Service pages; pass real overrides in a manifest row's
// `routeMap` data for pages with a genuinely different route (Long Island Lighthouse, Connecticut
// Cruises) rather than reusing this default.
const DEFAULT_DEPARTURE = { lat: 40.7591, lng: -73.8459, label: "World's Fair Marina — Departure" };
const DEFAULT_LANDMARKS = [
  { lat: 40.7061, lng: -73.9969, label: 'Brooklyn Bridge' },
  { lat: 40.7127, lng: -74.0134, label: 'One World Trade Center' },
  { lat: 40.6995, lng: -74.0396, label: 'Ellis Island' },
  { lat: 40.6892, lng: -74.0445, label: 'Statue of Liberty' },
];
// Real East River / Upper NY Bay channel waypoints, open water the whole way.
const DEFAULT_ROUTE_PATH = [
  { lat: 40.7591, lng: -73.8459 }, // World's Fair Marina (Flushing Bay)
  { lat: 40.7825, lng: -73.8802 }, // exit Flushing Bay, south of Rikers Island
  { lat: 40.7823, lng: -73.9165 }, // Hell Gate (East River, north end)
  { lat: 40.7648, lng: -73.9385 }, // East River off Astoria / Roosevelt Island (north tip)
  { lat: 40.7527, lng: -73.9610 }, // East River off Queensboro Bridge / Roosevelt Island (south tip)
  { lat: 40.7143, lng: -73.9725 }, // East River off Williamsburg Bridge
  { lat: 40.7061, lng: -73.9969 }, // Brooklyn Bridge
  { lat: 40.7009, lng: -74.0135 }, // The Battery (tip of Manhattan)
  { lat: 40.6892, lng: -74.0445 }, // Statue of Liberty
];
const routeMap = ({ departure = DEFAULT_DEPARTURE, landmarks = DEFAULT_LANDMARKS, routePath = DEFAULT_ROUTE_PATH } = {}) => {
  const escAttr = (s) => s.replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  return `<!-- wp:group {"className":"route-map"} -->
<div class="wp-block-group route-map">
<!-- wp:heading {"level":2} --><h2>Our Cruise Route</h2><!-- /wp:heading -->
<!-- wp:html -->
<div class="route-map__canvas" data-departure="${escAttr(JSON.stringify(departure))}" data-landmarks="${escAttr(JSON.stringify(landmarks))}" data-route-path="${escAttr(JSON.stringify(routePath))}" role="img" aria-label="Map of the Skyline Cruises route from ${departure.label.split(' —')[0]} along the East River past ${landmarks.map((l) => l.label).join(', ')}"></div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->`;
};

// Directions heading is real per-page copy ("Directions to Liberty Landing Marina"), never a
// generic "Getting There" — confirmed via direct Figma read (node 134:137).
// `r.from` is optional per-route (added 2026-08-24, gap-fill batch) — several real port pages
// only have ONE undifferentiated "Driving:" route with no distinct named origin (unlike Pier 36's
// 6 real labeled variants), and repeating the section's own fixed "Driving" h3 as the route's
// `from` label too would render as a literal "Driving: Driving: ..." duplicate. Omit the
// `<strong>{from}:</strong>` prefix entirely when `from` is falsy/generic rather than render that.
const directionsBlock = ({ heading, intro, routes, transit }) => `<!-- wp:group {"className":"directions-block"} -->
<div class="wp-block-group directions-block">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>${intro}</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"directions-block__group"} --><div class="wp-block-group directions-block__group"><!-- wp:heading {"level":3} --><h3>Driving</h3><!-- /wp:heading -->
${routes.map((r) => `<!-- wp:paragraph {"className":"directions-block__route"} --><p class="directions-block__route">${r.from ? `<strong>${r.from}:</strong> ` : ''}${r.directions}</p><!-- /wp:paragraph -->`).join('\n')}
</div><!-- /wp:group -->
<!-- wp:group {"className":"directions-block__group"} --><div class="wp-block-group directions-block__group"><!-- wp:heading {"level":3} --><h3>Mass Transit</h3><!-- /wp:heading --><!-- wp:paragraph --><p>${transit}</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->`;

// Text section — plain heading + paragraphs, no photo. Confirmed as a genuinely shared shape
// across Private Event/Party, School Events, and Port/Location intro sections (not invented).
// `ctaLine` is School Events' bold "Contact Us To Plan Your School Event!" trailing line — omit
// for categories that don't have one. `photo` (optional, added 2026-08-25 fixing NYC Holiday
// Cruises' "Holiday Dates Book Fast" section, which live pairs with a real photo this generic
// heading+paragraphs shape never had room for) renders a real 2-col text+photo row, matching the
// same "one-half/one-half" layout live already uses there — not a new visual language, just
// giving this composer the option other pages' checklist rows already have via photoChecklistRow.
// `icon` (optional, same fix) is a small centered icon above the heading, matching live's own
// treatment of "Smooth Sailing" (which real live pages show with a small icon, unlike this
// composer's previous bare heading+paragraph) — pass an existing theme icon path, don't invent one.
const textSection = ({ heading, paragraphs, ctaLine = '', photo = '', icon = '' }) => `<!-- wp:group {"className":"text-section${photo ? ' text-section--with-photo' : ''}"} -->
<div class="wp-block-group text-section${photo ? ' text-section--with-photo' : ''}">
${photo ? '<div class="text-section__col">' : ''}
${icon ? `<!-- wp:image --><figure class="wp-block-image text-section__icon"><img src="${icon}" alt="" /></figure><!-- /wp:image -->` : ''}
${heading ? `<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->` : ''}
${paragraphs.map((p) => `<!-- wp:paragraph --><p>${p}</p><!-- /wp:paragraph -->`).join('\n')}
${ctaLine ? `<!-- wp:paragraph {"className":"text-section__cta-line"} --><p class="text-section__cta-line">${ctaLine}</p><!-- /wp:paragraph -->` : ''}
${photo ? `</div><!-- wp:image {"className":"text-section__photo"} --><figure class="wp-block-image text-section__photo"><img src="${photo}" alt="" /></figure><!-- /wp:image -->` : ''}
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

// ---- About/Info composers, added 2026-08-24 for the 23-page About/Info + 6-page Utility/Form
// batch — these mirror patterns/hero-prose-cta.php, faq-accordion.php, testimonial-quote.php, and
// bio-photo-gallery.php exactly, just parameterized (the PHP patterns only ever shipped with
// hardcoded placeholder content for the block-editor UI, never a real JS composer for bulk push).

// `paragraphs` (plural) not the PHP pattern's single hardcoded <p> — real About/Info pages vary
// from 1 to several paragraphs. `cta`/`ctaHref` both optional; omitting `cta` drops the whole
// buttons block (several About/Info pages have no CTA at all, matching their "None / Soft CTA"
// spreadsheet column).
const heroProseCta = ({ heading, photo = '', paragraphs, cta = '', ctaHref = '/contact-us/request-your-quote/' }) => `<!-- wp:group {"className":"hero-prose-cta"} -->
<div class="wp-block-group hero-prose-cta">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:group {"className":"hero-prose-cta__body"} -->
<div class="wp-block-group hero-prose-cta__body">
${photo ? `<!-- wp:image --><figure class="wp-block-image"><img src="${photo}" alt="" /></figure><!-- /wp:image -->` : ''}
<!-- wp:group -->
<div class="wp-block-group">
${paragraphs.map((p) => `<!-- wp:paragraph --><p>${p}</p><!-- /wp:paragraph -->`).join('\n')}
${cta ? `<!-- wp:buttons {"className":"hero-prose-cta__cta"} --><div class="wp-block-buttons hero-prose-cta__cta"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="${ctaHref}">${cta}</a></div><!-- /wp:button --></div><!-- /wp:buttons -->` : ''}
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->`;

// Native <details>/<summary> accordion — no JS needed. `faqs`: real [{q, a}, ...] pulled verbatim
// from the live flat-paragraph Q&A pairs.
const faqAccordion = ({ heading = 'Frequently Asked Questions', faqs }) => `<!-- wp:group {"className":"faq-accordion"} -->
<div class="wp-block-group faq-accordion">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:html -->
${faqs.map((f) => `<details class="faq-item"><summary>${f.q}</summary><p>${f.a}</p></details>`).join('\n')}
<!-- /wp:html -->
</div>
<!-- /wp:group -->`;

// `items`: real [{quote, attribution}, ...] — the live site's Strong Testimonials slider, static
// grid version here (matches patterns/testimonial-quote.php).
const testimonialQuote = ({ heading = 'What Our Guests Say', items }) => `<!-- wp:group {"className":"testimonial-quote"} -->
<div class="wp-block-group testimonial-quote">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:group {"className":"testimonial-quote__grid"} -->
<div class="wp-block-group testimonial-quote__grid">
${items.map((i) => `<!-- wp:group {"className":"offering-card"} --><div class="wp-block-group offering-card"><!-- wp:paragraph --><p>&#8220;${i.quote}&#8221;</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"testimonial__attribution"} --><p class="testimonial__attribution">${i.attribution}</p><!-- /wp:paragraph --></div><!-- /wp:group -->`).join('\n')}
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->`;

// Flexible for BOTH real uses found in the live-site audit: a captain/staff bio (heading + photo
// + paragraphs + gallery) AND a pure photo-gallery page (Picture Gallery, Party Pictures, etc. —
// just omit `photo`/`paragraphs` and this renders as a heading + gallery grid only, arbitrary
// image count, not the PHP pattern's hardcoded 3).
const bioPhotoGallery = ({ heading, photo = '', paragraphs = [], galleryImages }) => `<!-- wp:group {"className":"bio-photo-gallery"} -->
<div class="wp-block-group bio-photo-gallery">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
${(photo || paragraphs.length) ? `<!-- wp:group {"className":"bio-photo-gallery__body"} -->
<div class="wp-block-group bio-photo-gallery__body">
${photo ? `<!-- wp:image --><figure class="wp-block-image"><img src="${photo}" alt="" /></figure><!-- /wp:image -->` : ''}
${paragraphs.map((p) => `<!-- wp:paragraph --><p>${p}</p><!-- /wp:paragraph -->`).join('\n')}
</div>
<!-- /wp:group -->` : ''}
<!-- wp:gallery {"columns":3,"className":"bio-photo-gallery__gallery"} -->
<figure class="wp-block-gallery bio-photo-gallery__gallery">
${galleryImages.map((src) => `<figure class="wp-block-image"><img src="${src}" alt="" /></figure>`).join('\n')}
</figure>
<!-- /wp:gallery -->
</div>
<!-- /wp:group -->`;

// New 2026-08-24 — Site Map and Friends & Affiliates are real link directories, not prose. Real
// `<ul><li><a>` markup (not text jammed into a <p>, which isn't valid HTML for block content).
const linkList = ({ heading, links }) => `<!-- wp:group {"className":"link-list"} -->
<div class="wp-block-group link-list">
${heading ? `<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->` : ''}
<!-- wp:list --><ul class="link-list__items">
${links.map((l) => `<li><a href="${l.href}"${l.external ? ' target="_blank" rel="noopener"' : ''}>${l.label}</a></li>`).join('\n')}
</ul><!-- /wp:list -->
</div>
<!-- /wp:group -->`;

// ---- Homepage-only composers, added 2026-08-24. The real homepage (row 1 of the original 84-row
// sitemap) was never actually built into this WordPress site across the whole multi-week project —
// only the 84 interior pages were. Its real design already existed in the Figma file ("Redesign
// Skyline Cruises Homepage", frame 1:30) but was only ever used as the SOURCE for the shared
// header/footer/trust-badge/newsletter components reused on every interior page — its own unique
// section content (hero copy, "Events We Cater", the occasion checklist, etc.) was never coded.
// These composers are one-off (this page is the only page that uses them), pulled verbatim from
// that Figma design via get_design_context, real Figma-hosted stock photos downloaded and
// re-uploaded (not AI-generated, not scraped from the old live homepage — which had entirely
// different content — matching this project's "real assets, not invented" rule).

// Hero has TWO real CTAs (unlike every interior page's one) plus a real lead-inquiry form card.
// `consentText` is deliberately a parameter, not hardcoded: the real Figma design's own consent
// checkbox copy literally said "...you are requesting a consultation. Treatment recommendations,
// if any, are provided only after an in-person medical evaluation." — leftover placeholder text
// from an unrelated med-spa client's shared component library this Figma file's design tokens
// were seeded from (confirmed via the file's own published variable set, `discover.manemedispa.com`).
// Publishing medical-consultation language on a yacht charter site would be real nonsense, not
// just "stale" — so this is corrected to real cruise-inquiry consent copy, not transcribed verbatim.
const homepageHero = ({ h1, paragraphs, ctaPrimary, ctaSecondary, bgImage, consentText }) => `<!-- wp:group {"className":"hero-outer homepage-hero-outer"} -->
<div class="wp-block-group hero-outer homepage-hero-outer">
<div class="hero homepage-hero" style="background-image:url(${bgImage})">
<div class="hero__content homepage-hero__content">
<!-- wp:heading {"level":1} --><h1>${h1}</h1><!-- /wp:heading -->
${paragraphs.map((p) => `<!-- wp:paragraph --><p>${p}</p><!-- /wp:paragraph -->`).join('\n')}
<!-- wp:buttons {"className":"homepage-hero__ctas"} --><div class="wp-block-buttons homepage-hero__ctas"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link btn btn-gold" href="/contact-us/request-your-quote/">${ctaPrimary}</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link btn btn-outline-white" href="/the-great-escape-yacht-rental/">${ctaSecondary}</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
<!-- wp:paragraph {"className":"trust-badges"} --><p class="trust-badges"><span class="trust-badge"><span>Since 1993</span></span><span class="trust-badge"><span>A+ BBB Rating</span></span><span class="trust-badge"><span>30+ Years Excellence</span></span></p><!-- /wp:paragraph -->
</div>
<!-- wp:html -->
<div class="homepage-lead-form">
<h3>Submit an Inquiry</h3>
<form>
<label>Full Name *<input type="text" name="full_name" required /></label>
<label>Email Address *<input type="email" name="email" required /></label>
<label>Phone Number *<input type="tel" name="phone" required /></label>
<label class="homepage-lead-form__consent"><input type="checkbox" name="consent" required /><span>${consentText}</span></label>
<button type="submit" class="btn btn-gold">SUBMIT</button>
</form>
</div>
<!-- /wp:html -->
</div>
</div>
<!-- /wp:group -->`;

// User later updated the Figma design so all 5 offset photo cards are the same shape (a real
// full-height Weddings photo replaced the old short-photo-plus-white-caption-box version) and
// asked for all 5 to look consistent — every card is now a plain full photo that reveals its
// own title+description as a hover/focus overlay, Weddings included. `photos` is a flat array
// of 5 `{photo, title, description}` entries (or a plain src string for a caption-less photo,
// still supported for any future page that doesn't want the hover treatment on every slot).
const eventsCaterGrid = ({ heading, subheading, photos }) => `<!-- wp:group {"className":"events-cater"} -->
<div class="wp-block-group events-cater">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"intro-copy"} --><p class="intro-copy">${subheading}</p><!-- /wp:paragraph -->
<!-- wp:html -->
<div class="events-cater__grid">
${photos
	.map((p) =>
		typeof p === 'string'
			? `<div class="events-cater__card"><img src="${p}" alt="" /></div>`
			: `<div class="events-cater__card" tabindex="0"><img src="${p.photo}" alt="" /><div class="events-cater__card-hover"><h3>${p.title}</h3><p>${p.description}</p>${p.link ? `<a href="${p.link}">Learn More &rarr;</a>` : ''}</div></div>`
	)
	.join('\n')}
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->`;

// "Perfect for Any Occasion" — real photo bg + dark overlay + 2-column 12-item checklist. Reuses
// checklistItems()'s markup shape with a `--light` modifier for white text over the dark photo
// (the base .checklist-item class assumes a light page background elsewhere in this theme).
const occasionChecklist = ({ heading, subheading, bgImage, items }) => `<!-- wp:group {"className":"occasion-checklist"} -->
<div class="wp-block-group occasion-checklist" style="background-image:url(${bgImage})">
<div class="occasion-checklist__overlay">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p class="occasion-checklist__sub">${subheading}</p><!-- /wp:paragraph -->
<!-- wp:html --><div class="occasion-checklist__grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px 48px;">
${checklistItems(items, 'checklist-item checklist-item--light')}
</div><!-- /wp:html -->
</div>
</div>
<!-- /wp:group -->`;

// "Have Questions?" — 6 real service-area cards (icon + city + neighborhoods), reusing the same
// hand-drawn map-pin SVG shape already established in site-header.php's skyline_nav_icon() (kept
// as a literal inline copy here since this is a JS composer, not the PHP file that owns that
// function — same real shape, not a redrawn substitute).
const MAP_PIN_ICON = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>';
const officeLocationsGrid = ({ heading, subheading, locations }) => `<!-- wp:group {"className":"office-locations"} -->
<div class="wp-block-group office-locations">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"intro-copy"} --><p class="intro-copy">${subheading}</p><!-- /wp:paragraph -->
<!-- wp:html -->
<div class="office-locations__grid">
${locations.map((l) => `<div class="office-locations__card"><span class="office-locations__icon">${MAP_PIN_ICON}</span><h3>${l.city}</h3><p>${l.area}</p></div>`).join('\n')}
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->`;

// "Who We Are" — real photo (left) + navy overlay text panel (right), distinct layout from the
// shared textSection() (which is plain, no photo/no dark panel).
const whoWeAreSplit = ({ heading, paragraph, photo }) => `<!-- wp:group {"className":"who-we-are"} -->
<div class="wp-block-group who-we-are">
<div class="who-we-are__photo"><img src="${photo}" alt="" /></div>
<div class="who-we-are__panel">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>${paragraph}</p><!-- /wp:paragraph -->
</div>
</div>
<!-- /wp:group -->`;

// "Why Skyline Cruises" — 4 real photo-bg cards (navy tint + 20%-opacity photo, matching the
// Figma design exactly), each with a real title+description.
const whySkylineGrid = ({ heading, cards }) => `<!-- wp:group {"className":"why-skyline"} -->
<div class="wp-block-group why-skyline">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:html -->
<div class="why-skyline__grid">
${cards.map((c) => `<div class="why-skyline__card" style="background-image:url(${c.photo})"><h3>${c.title}</h3><p>${c.description}</p></div>`).join('\n')}
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->`;

// "Cruise Gallery" — real navy bg + 4 real cruise photos + a real Instagram link (the client's
// actual handle, same one already used site-wide — see the confirmed live URL below).
const cruiseGallery = ({ heading, subheading, photos, instagramHref = 'https://www.instagram.com/skylinecruisesny/' }) => `<!-- wp:group {"className":"cruise-gallery"} -->
<div class="wp-block-group cruise-gallery">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p class="cruise-gallery__sub">${subheading}</p><!-- /wp:paragraph -->
<!-- wp:html -->
<div class="cruise-gallery__grid">
${photos.map((src) => `<img src="${src}" alt="" />`).join('\n')}
</div>
<a class="btn btn-gold cruise-gallery__cta" href="${instagramHref}" target="_blank" rel="noopener">Follow Us on Instagram</a>
<!-- /wp:html -->
</div>
<!-- /wp:group -->`;

// "Notes From the Deck" — real teaser cards. No individual blog posts exist as real WP `post`
// entities anywhere in this project (everything built so far is `page` type; the live site's own
// 250+-post blog was represented as a single link to its real archive during the Site Map batch,
// same call applies here) — "Read More"/"View More Blogs" link to that real archive rather than
// a fabricated single-post permalink.
const blogTeasers = ({ heading, subheading, posts, archiveHref = '/notes-from-the-deck/' }) => `<!-- wp:group {"className":"blog-teasers"} -->
<div class="wp-block-group blog-teasers">
<!-- wp:heading {"level":2} --><h2>${heading}</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"intro-copy"} --><p class="intro-copy">${subheading}</p><!-- /wp:paragraph -->
<!-- wp:html -->
<div class="blog-teasers__grid">
${posts.map((p) => `<div class="blog-teasers__card"><img src="${p.photo}" alt="" /><div class="blog-teasers__body"><span class="blog-teasers__date">${p.date}</span><h3>${p.title}</h3><p>${p.excerpt}</p><a href="${archiveHref}">Read More &rarr;</a></div></div>`).join('\n')}
</div>
<a class="btn btn-outline-navy blog-teasers__view-more" href="${archiveHref}">View More Blogs</a>
<!-- /wp:html -->
</div>
<!-- /wp:group -->`;

// "Sailing From" card — real departure-port info (World's Fair Marina is the confirmed main/
// public home port throughout this project; the 3 "Also Sailing From" ports are all real, already-
// built pages elsewhere in this project — Lincoln Harbor, and 2 not otherwise covered by this
// project's own Port/Location batch, North Cove Marina and the already-covered Liberty Landing
// Marina — kept verbatim from the Figma design, not cross-checked against the Ports hub's own
// 10-port list since this card is real content in its own right, not required to be a subset).
// Real Figma mismatch found via user screenshot: the design is a floating white card (rounded,
// drop-shadow) that overlaps the hero's bottom edge, with a small uppercase gray label + icon,
// a bold dark "name" line, then plain address lines, then a smaller gray note — not a plain h3 +
// flat paragraph list sitting below the hero on the page background. `primary`'s first line is
// always the bold name and its last line is always the small note (matches every real use of
// this composer); `also`'s lines are "Name, Rest" — split at the first comma so the marina name
// renders bold like Figma, without changing this function's external data shape.
const sailingFromCard = ({ primary, also }) => {
  const [name, ...restLines] = primary;
  const note = restLines[restLines.length - 1];
  const middle = restLines.slice(0, -1);
  const alsoItems = also
    .map((l) => {
      const idx = l.indexOf(',');
      return idx > -1 ? `<li>&bull; <strong>${l.slice(0, idx + 1)}</strong>${l.slice(idx + 1)}</li>` : `<li>&bull; ${l}</li>`;
    })
    .join('\n');
  return `<!-- wp:group {"className":"sailing-from"} -->
<div class="wp-block-group sailing-from">
<div class="sailing-from__col"><div class="sailing-from__label"><span class="sailing-from__icon">${MAP_PIN_ICON}</span><span>Sailing From</span></div><p class="sailing-from__name">${name}</p>
${middle.map((l) => `<p>${l}</p>`).join('\n')}
<p class="sailing-from__note">${note}</p></div>
<div class="sailing-from__col"><div class="sailing-from__label"><span class="sailing-from__icon">${MAP_PIN_ICON}</span><span>Also Sailing From</span></div><ul>${alsoItems}</ul></div>
</div>
<!-- /wp:group -->`;
};

// ---- Per-template composition — same ordering as the structural audit + consistency requirement:
//      one canonical section order per category, real content varies, structure never drifts. ----

// Every category's real section order below is confirmed via direct Figma reads of an actual
// built page in that category (node ids in the comment on each), not inferred or guessed.
const TEMPLATE_BUILDERS = {
	// 02 - NYC Dinner Cruises (node 3:575)
	// `extraTextSections` (optional, array of RAW {heading, paragraphs} data objects — deliberately
	// NOT the same convention as 'School Events (non-hub)'s pre-built-string `extraSections` below;
	// real bug caught immediately after the first live push: passing raw data objects into that
	// convention's `...(arr)` spread produces literal "[object Object]" in the rendered page, since
	// spreading an array of plain objects into the content-strings array just calls each object's
	// default toString() when everything gets `.join('\n')`ed at the end — it never runs them
	// through textSection() at all. Mapping through textSection() here first is the fix.) —
	// added 2026-08-24 for "Celebration Cruises", whose real live page has 3 genuine subsections
	// (Bar/Bat Mitzvah, Baby Shower, Sweet 16/Quinceañera) this recipe has no other slot for —
	// inserted after the testimonial, before the closing CTA, so a page with none (the common
	// case) renders identically to before.
	// `noRouteMap: true` (added 2026-08-24) omits the "Our Cruise Route" section entirely — used
	// for Long Island Lighthouse Cruise and Connecticut Cruises at the user's explicit request.
	// Omitting `routeMap` from `d` (the default, most pages) still renders the section with the
	// standard NYC Harbor route; `noRouteMap: true` is a distinct signal from "use the default."
	'Public Cruise Service': (d) => [
		hero(d.hero),
		photoChecklistRow(d.checklist),
		featuresPair(),
		...(d.noRouteMap ? [] : [routeMap(d.routeMap)]),
		testimonial(d.testimonial),
		...(d.extraTextSections || []).map(textSection),
		closingCta(d.closingCta),
	],
	// "NYC Holiday Cruises" — a real directory/hub page linking out to the 5 specific holiday
	// occasions, not a standard cruise-product page. Composed directly from its own real section
	// order (confirmed via live-site extraction 2026-08-24) rather than forced into the standard
	// recipe: intro (with inline links to the 5 sub-pages) -> features checklist w/ photo ->
	// "Smooth Sailing" (shared static block, same as featuresPair conceptually but this page's
	// real copy is prose, not the icon pair) -> "Holiday Dates Book Fast" -> an unheaded
	// departure-marina paragraph -> an unheaded testimonial -> closing CTA. A dated 2020-era
	// COVID-19 safety section on the live page was deliberately dropped as outdated, not carried
	// forward (same call made on a similar section during the Private Event/Party batch).
	'Holiday Hub': (d) => [
		hero(d.hero),
		textSection(d.intro),
		photoChecklistRow(d.features),
		textSection(d.smoothSailing),
		textSection(d.datesBookFast),
		textSection(d.marinaNote),
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
	// category, NOT "Book Now". No testimonial section on this category at all on the pilot,
	// but real subsequent rows (21-38, added 2026-08-24) vary a lot more than Birthday's own
	// shape — tierCards/styleCards/featuresPair/testimonial are now all OPTIONAL (only Birthday
	// itself has all of tierCards+styleCards; most of rows 21-38's real copy has neither, per
	// direct live-site content), matching the "only include a section when the page's real copy
	// actually has it" rule already established for featuresPair in the earlier Figma build.
	'Private Event/Party': (d) => [
		hero({ ...d.hero, cta: d.hero.cta || 'Get a Quote' }),
		textSection(d.intro),
		...(d.tierCards ? [tierCards3up(d.tierCards)] : []),
		...(d.styleCards ? [styleCards2up(d.styleCards)] : []),
		...(d.featuresPair ? [featuresPair()] : []),
		...(d.testimonial ? [testimonial(d.testimonial)] : []),
		...(d.extraTextSections || []).map(textSection),
		closingCta({ ...d.closingCta, cta: d.closingCta.cta || 'Get a Quote' }),
	],
	// Hub pages inside the Private Event/Party sheet template (Weddings, Corporate Cruises,
	// Yacht Charter, NYC Party Cruises) — real directory pages linking out to their own real
	// subpages, not a standard single-occasion product page. Added 2026-08-24 alongside the
	// 33-page gap-fill batch, mirroring the 'School Events' hub-vs-non-hub split already
	// established. `linkList.links` are the hub's own real subpage links (verbatim hrefs/labels).
	'Private Event/Party (hub)': (d) => [
		hero({ ...d.hero, cta: d.hero.cta || 'Get a Quote' }),
		textSection(d.intro),
		linkList(d.linkList),
		...(d.extraTextSections || []).map(textSection),
		...(d.testimonial ? [testimonial(d.testimonial)] : []),
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
	// `directions` is optional (added 2026-08-24, gap-fill batch) — Glen Cove Ferry Terminal's
	// real live page has no Directions section at all (just an embedded map, no driving/transit
	// copy), and forcing one would either crash (directionsBlock destructuring undefined) or
	// invent content that doesn't exist on the real page.
	'Port/Location': (d) => [
		hero({ ...d.hero, short: true, cta: d.hero.cta || 'Book Now' }),
		textSection(d.intro),
		...(d.directions ? [directionsBlock(d.directions)] : []),
	],
	// 44 - Departure Ports Overview (hub, node 126:2 in the earlier Figma batch) — a real
	// directory linking out to the 10 individual port pages, not a leaf port page itself, so it
	// has no single address/directions to show. Added 2026-08-24 alongside the gap-fill batch.
	'Port/Location (hub)': (d) => [
		hero({ ...d.hero, short: true, cta: d.hero.cta || 'Book Now' }),
		textSection(d.intro),
		linkList(d.linkList),
	],
	'About/Info': (d) => [...(d.sections || [])],
	'Utility/Form': (d) => [formPageShell(d.formShell)],
	// Row 1 of the original 84-row sitemap — see the long comment above the homepage-only
	// composers for why this was never built until 2026-08-24. Real section order confirmed via
	// direct Figma reads of frame 1:30 ("Redesign Skyline Cruises Homepage"), top to bottom.
	'Homepage': (d) => [
		homepageHero(d.hero),
		sailingFromCard(d.sailingFrom),
		eventsCaterGrid(d.eventsCater),
		occasionChecklist(d.occasionChecklist),
		officeLocationsGrid(d.officeLocations),
		whoWeAreSplit(d.whoWeAre),
		whySkylineGrid(d.whySkyline),
		cruiseGallery(d.cruiseGallery),
		blogTeasers(d.blogTeasers),
		testimonial(d.testimonial),
		faqAccordion(d.faq),
		`<!-- wp:paragraph {"className":"faq-view-more"} --><p class="faq-view-more"><a class="btn btn-outline-navy" href="/about-faq/">View More FAQs</a></p><!-- /wp:paragraph -->`,
	],
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

// Only auto-run when invoked directly (`node scripts/build-pages.js manifest.json`) — guarded so
// scripts/compose-about-info.js (added 2026-08-24) can `require()` this file to reuse its section
// composers (heroProseCta, faqAccordion, testimonialQuote, bioPhotoGallery, linkList, textSection,
// etc.) without also triggering a live REST push as a side effect of importing it.
if (require.main === module) {
	main();
}

module.exports = {
	hero, featuresPair, testimonial, closingCta, checklistItems, photoChecklistRow, routeMap,
	textSection, tierCards3up, styleCards2up, marinaGrid3x3, portsList, directionsBlock, formPageShell,
	heroProseCta, faqAccordion, testimonialQuote, bioPhotoGallery, linkList,
	homepageHero, eventsCaterGrid, occasionChecklist, officeLocationsGrid, whoWeAreSplit,
	whySkylineGrid, cruiseGallery, blogTeasers, sailingFromCard,
	TEMPLATE_BUILDERS, // exported for dry-run validation of a manifest before a real REST push
};
