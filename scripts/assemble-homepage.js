#!/usr/bin/env node
/**
 * Resolves the raw Homepage manifest's `*LocalFile` (single image) and `*LocalFiles` (array of
 * images) fields to real uploaded WP media URLs via media-map.tsv, producing a final manifest
 * build-pages.js can push directly.
 *
 * Field convention (deliberately simple since this is a single one-off page, not a repeatable
 * batch): every `fooLocalFile` key on an object resolves in place to a `foo` key on that SAME
 * object (e.g. `hero.heroBgImageLocalFile` -> `hero.heroBgImage`, NOT the gap-fill batch's
 * top-level-sibling convention — this page's raw manifest just nests it directly where it's used,
 * which is simpler when there's only one page and no shared cross-batch resolver to worry about).
 * Every `fooLocalFiles` key (array) resolves to a `foo` array of real URLs, same-object, in order.
 *
 * Usage: node scripts/assemble-homepage.js <raw-manifest.json> <media-map.tsv> <output.json>
 */
const fs = require('fs');
const path = require('path');

const [, , rawPath, mediaMapPath, outPath] = process.argv;
if (!rawPath || !mediaMapPath || !outPath) {
	console.error('Usage: node scripts/assemble-homepage.js <raw-manifest.json> <media-map.tsv> <output.json>');
	process.exit(1);
}

const mediaMap = {};
for (const line of fs.readFileSync(path.resolve(mediaMapPath), 'utf8').split('\n')) {
	const [file, id, url] = line.split('\t');
	if (file && id && url) mediaMap[file.trim()] = url.trim();
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

function resolveLocalFiles(node) {
	if (Array.isArray(node)) {
		node.forEach(resolveLocalFiles);
		return;
	}
	if (node && typeof node === 'object') {
		for (const key of Object.keys(node)) {
			if (key.endsWith('LocalFiles')) {
				const target = key.slice(0, -'LocalFiles'.length);
				node[target] = node[key].map(resolveImg);
				delete node[key];
			} else if (key.endsWith('LocalFile')) {
				const target = key.slice(0, -'LocalFile'.length);
				node[target] = resolveImg(node[key]);
				delete node[key];
			} else {
				resolveLocalFiles(node[key]);
			}
		}
	}
}

const raw = JSON.parse(fs.readFileSync(path.resolve(rawPath), 'utf8'));
raw.forEach((row) => resolveLocalFiles(row.data));

fs.writeFileSync(path.resolve(outPath), JSON.stringify(raw, null, '\t'));
console.log(`Assembled ${raw.length} page(s) -> ${outPath}`);
if (missing.length) {
	console.log(`WARNING: ${missing.length} local image filenames had no match in media-map.tsv:`);
	[...new Set(missing)].forEach((f) => console.log(`  - ${f}`));
} else {
	console.log('All image references resolved.');
}
