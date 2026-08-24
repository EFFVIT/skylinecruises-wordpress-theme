#!/usr/bin/env node
/**
 * Preprocesses a RAW tagged manifest (real scraped content, sections marked by composer type) for
 * the About/Info + Utility/Form batch into a final manifest build-pages.js can push directly.
 *
 * Raw page shape:
 *   {
 *     title, slug, parent: 0, template: "About/Info" | "Utility/Form",
 *     sections: [ { type: "heroProseCta" | "faqAccordion" | "testimonialQuote" |
 *                   "bioPhotoGallery" | "textSection" | "linkList" | "directionsBlock",
 *                   data: {...} }, ... ],
 *     formShell: { heading, intro, embedHtml }   // Utility/Form only
 *   }
 *
 * Any `*LocalFile` field inside a section's `data` (photoLocalFile, bgImageLocalFile,
 * galleryImageLocalFiles — an array) is resolved to the real uploaded WP media URL via
 * media-map.tsv (same filename->URL map file used for the Public Cruise Service batch), written
 * into the matching `*Url`-less field the composer actually expects (photo, bgImage,
 * galleryImages) — never left as a bare local filename in the pushed content.
 *
 * Usage: node scripts/compose-about-info.js <raw-manifest.json> <media-map.tsv> <output.json>
 */
const fs = require('fs');
const path = require('path');
const composers = require('./build-pages.js');

const [, , rawManifestPath, mediaMapPath, outPath] = process.argv;
if (!rawManifestPath || !mediaMapPath || !outPath) {
	console.error('Usage: node scripts/compose-about-info.js <raw-manifest.json> <media-map.tsv> <output.json>');
	process.exit(1);
}

const mediaMap = {};
for (const line of fs.readFileSync(path.resolve(mediaMapPath), 'utf8').split('\n')) {
	const [file, id, url] = line.split('\t');
	if (file && id && id !== 'FAILED' && url) mediaMap[file] = url.trim();
}

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

const raw = JSON.parse(fs.readFileSync(path.resolve(rawManifestPath), 'utf8'));
const output = [];

for (const page of raw) {
	if (page.template === 'Utility/Form') {
		output.push({
			title: page.title,
			slug: page.slug,
			parent: 0,
			template: 'Utility/Form',
			data: { formShell: page.formShell },
		});
		continue;
	}

	// About/Info — resolve each section's data, then call the matching composer.
	const sectionHtml = (page.sections || []).map((section) => {
		const composer = composers[section.type];
		if (!composer) {
			throw new Error(`Unknown section type "${section.type}" on page "${page.slug}"`);
		}
		const d = { ...section.data };

		if (d.photoLocalFile !== undefined) {
			d.photo = resolveImg(d.photoLocalFile);
			delete d.photoLocalFile;
		}
		if (d.bgImageLocalFile !== undefined) {
			d.bgImage = resolveImg(d.bgImageLocalFile);
			delete d.bgImageLocalFile;
		}
		if (Array.isArray(d.galleryImageLocalFiles)) {
			d.galleryImages = d.galleryImageLocalFiles.map(resolveImg).filter(Boolean);
			delete d.galleryImageLocalFiles;
		}

		return composer(d);
	});

	output.push({
		title: page.title,
		slug: page.slug,
		parent: 0,
		template: 'About/Info',
		data: { sections: sectionHtml },
	});
}

fs.writeFileSync(path.resolve(outPath), JSON.stringify(output, null, '\t'));
console.log(`Wrote ${output.length} pages to ${outPath}`);
if (missing.length) {
	console.warn(`MISSING media mappings for: ${[...new Set(missing)].join(', ')}`);
}
