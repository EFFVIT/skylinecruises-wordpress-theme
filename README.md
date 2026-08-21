# Skyline Cruises — Custom WordPress Theme

Custom theme (no page builder) for the Skyline Cruises site rebuild. Block patterns encode the
section types established across 55 pages already designed in Figma ("Skyline Redesign v2",
file key `zLYZyLCy4w7JAO6EeF5jGl`), plus 2 additional categories (About/Info, Utility/Form)
planned from a live-site structural audit rather than Figma.

## Status

- Theme scaffold + all 7 template categories' block patterns: built, this repo.
- Staging host: **not yet live.** Target is a Coolify-hosted WordPress instance at
  `skyline-build.effvit.com`, fully independent of the client's own hosting — needs to be created
  in Coolify (New Project -> New Resource -> WordPress template -> domain -> DNS A record to
  `178.156.192.164` -> deploy) before this theme can be installed and actually previewed.
- Once that instance exists: install this theme (zip upload via `wp-admin` or `git clone` into
  `wp-content/themes/`), activate it, then run `scripts/build-pages.js` against it.
- Later, separately: install the same theme onto the client's existing `staging.skylinecruises.com`
  (access pending as of 2026-08-21) and re-run the bulk script there. Final cutover to the live
  `skylinecruises.com` domain is a separate, later, explicitly-approved step — not part of this repo's scope.

## Structure

- `style.css`, `functions.php` — theme entry points. `functions.php` enqueues fonts/CSS and
  registers every file in `/patterns` as a real block-editor pattern (category "Skyline Sections").
- `assets/css/tokens.css` — design tokens (colors, fonts, grid) confirmed by direct Figma reads.
- `assets/css/patterns.css` — all section styles, one block per pattern.
- `template-parts/` — header/footer/newsletter-signup. Theme-level, always injected on every page,
  not editable page content (pixel-identical across all 55 sampled Figma pages).
- `patterns/` — one PHP file per section type, registered as a WordPress block pattern.
- `scripts/build-pages.js` — bulk page creation via the WP REST API (`/wp-json/wp/v2/pages`,
  always `status: "draft"`, never auto-published). Mirrors the same section shapes as `/patterns`
  with real per-page data substituted in. See `scripts/pages-manifest.example.json` for the
  manifest shape; the real manifest is derived from `Skyline Interior Pages.xlsx` plus real copy
  scraped verbatim from the live site (never invented — same convention used throughout the Figma build).

## Consistency requirement

Per explicit user feedback (2026-08-21): some later Figma pages drifted structurally as that build
progressed (inconsistent testimonial counts/CTA composition, one-off sections). This rebuild does
not carry that drift forward — every page within a template category uses the same header, H1
styling, CTA pattern, and testimonial pattern. Real content (copy, images) still varies per page,
pulled verbatim from the live site; only structure is standardized.

## Forms

Utility/Form pages (contact, quote request, sign-up, school quote) keep their existing third-party
embeds as-is (EmailMeForm, Mailchimp, Infusionsoft/Keap) rather than consolidating onto one form
plugin — explicit client decision, 2026-08-21. See `patterns/form-page-shell.php` for the real
embed identifiers found on the live site.
