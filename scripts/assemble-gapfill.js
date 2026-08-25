#!/usr/bin/env node
/**
 * Merges the 33-page gap-fill batch's raw scraped manifest rows (batch-A.json .. batch-K.json,
 * each an array of { title, slug, parent, template, data, _agentNote? }) into one final manifest
 * build-pages.js can push directly.
 *
 * Resolves `heroBgImageLocalFile` / `testimonialBgImageLocalFile` (bare filenames the scraping
 * agents downloaded into the shared images dir and then `wp media import`ed) to real uploaded WP
 * media URLs via media-map.tsv (same filename->URL map convention as the earlier About/Info and
 * Public Cruise Service batches), writing the resolved URL into the matching `bgImage` field the
 * composer actually expects and deleting the LocalFile key.
 *
 * Usage: node scripts/assemble-gapfill.js <batches-dir> <media-map.tsv> <output.json>
 */
const fs = require('fs');
const path = require('path');

const [, , batchesDir, mediaMapPath, outPath] = process.argv;
if (!batchesDir || !mediaMapPath || !outPath) {
	console.error('Usage: node scripts/assemble-gapfill.js <batches-dir> <media-map.tsv> <output.json>');
	process.exit(1);
}

const mediaMap = {};
for (const line of fs.readFileSync(path.resolve(mediaMapPath), 'utf8').split('\n')) {
	const [file, id, url] = line.split('\t');
	if (file && id && id !== 'FAILED' && url) mediaMap[file.trim()] = url.trim();
}

// Real, already-uploaded, already-reused-across-the-site hero photo (attachment id 11,
// "Skyline Cruises Hero") — used as the fallback for any page in this batch whose own live page
// genuinely has no hero photo at all (13 of the 33 pages, mostly hub pages and older-template
// port pages with no featured image anywhere). This is reusing a real approved asset already
// live on the site, not inventing new content, matching the same "reuse a real shared photo"
// practice already established for the Memorable-Service/Smooth-Sailing icons and testimonial-bg.
const FALLBACK_HERO_IMAGE = 'https://178-156-192-164.sslip.io/wp-content/uploads/2026/08/hero-sm.jpg';

const missing = [];
function resolveImg(localFile) {
	if (!localFile) return '';
	const url = mediaMap[localFile];
	if (!url) {
		missing.push(localFile);
		return '';
	}
	return url;
}

// Recursively walk any object/array in a page's `data`, resolving *LocalFile keys wherever they
// appear. The scraping agents in practice placed `heroBgImageLocalFile`/`testimonialBgImageLocalFile`
// as TOP-LEVEL keys on `data` itself (sibling to `hero`/`testimonial`), not nested inside those
// sub-objects — confirmed by inspecting the completed batch-A/E/F outputs. These two must route
// into `data.hero.bgImage` / `data.testimonial.bgImage` specifically, not a same-level `bgImage`
// on whatever object happens to contain the key (which is what a naive generic strip-suffix rule
// would do). Any OTHER `*LocalFile` key (nested or otherwise) still falls back to the generic rule.
function resolveLocalFiles(node) {
	if (Array.isArray(node)) {
		node.forEach(resolveLocalFiles);
		return;
	}
	if (node && typeof node === 'object') {
		if (node.heroBgImageLocalFile !== undefined) {
			node.hero = node.hero || {};
			node.hero.bgImage = resolveImg(node.heroBgImageLocalFile);
			delete node.heroBgImageLocalFile;
		}
		if (node.testimonialBgImageLocalFile !== undefined) {
			node.testimonial = node.testimonial || {};
			node.testimonial.bgImage = resolveImg(node.testimonialBgImageLocalFile);
			delete node.testimonialBgImageLocalFile;
		}
		for (const key of Object.keys(node)) {
			if (key.endsWith('LocalFile')) {
				const target = key.slice(0, -'LocalFile'.length);
				node[target] = resolveImg(node[key]);
				delete node[key];
			} else {
				resolveLocalFiles(node[key]);
			}
		}
	}
}

const batchFiles = fs.readdirSync(path.resolve(batchesDir))
	.filter((f) => /^batch-[A-Z]\.json$/.test(f))
	.sort();

if (batchFiles.length === 0) {
	console.error(`No batch-*.json files found in ${batchesDir}`);
	process.exit(1);
}

const output = [];
const notes = [];
const notesFallbackHero = [];
const seenSlugs = new Set();

for (const file of batchFiles) {
	const rows = JSON.parse(fs.readFileSync(path.join(batchesDir, file), 'utf8'));
	if (!Array.isArray(rows)) {
		console.error(`${file} is not a JSON array — skipping`);
		continue;
	}
	for (const row of rows) {
		if (!row.title || !row.slug || !row.template || !row.data) {
			console.error(`${file}: malformed row (missing title/slug/template/data): ${JSON.stringify(row).slice(0, 200)}`);
			continue;
		}
		if (seenSlugs.has(row.slug)) {
			console.error(`${file}: DUPLICATE slug "${row.slug}" — skipping second occurrence`);
			continue;
		}
		seenSlugs.add(row.slug);

		resolveLocalFiles(row.data);

		// Fall back to the shared real hero photo when this page genuinely had none of its own
		// (an empty `background-image:url()` is a known bad pattern in this theme — same class of
		// bug as the earlier empty `<img src="">` fix — never ship a hero with no image at all).
		if (row.data.hero && !row.data.hero.bgImage) {
			row.data.hero.bgImage = FALLBACK_HERO_IMAGE;
			notesFallbackHero.push(row.slug);
		}

		if (row._agentNote) {
			notes.push(`[${file} / ${row.slug}] ${row._agentNote}`);
		}

		output.push({
			title: row.title,
			slug: row.slug,
			parent: row.parent || 0,
			template: row.template,
			data: row.data,
		});
	}
}

fs.writeFileSync(path.resolve(outPath), JSON.stringify(output, null, '\t'));

console.log(`Assembled ${output.length} pages from ${batchFiles.length} batch files -> ${outPath}`);
if (missing.length) {
	console.log(`\nWARNING: ${missing.length} local image filenames had NO match in media-map.tsv (left blank):`);
	[...new Set(missing)].forEach((f) => console.log(`  - ${f}`));
}
if (notes.length) {
	console.log(`\n${notes.length} _agentNote flags surfaced for review:`);
	notes.forEach((n) => console.log(`  - ${n}`));
}
if (notesFallbackHero.length) {
	console.log(`\n${notesFallbackHero.length} pages had no real hero photo of their own, fell back to the shared hero-sm.jpg:`);
	notesFallbackHero.forEach((s) => console.log(`  - ${s}`));
}
