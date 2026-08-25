# Fix: duplicate Brunch/Lunch checklist photos

Brunch Cruises (68) and Lunch Cruises (69) both used the same uploaded file
(`brunch-checklist.jpg` / `lunch-checklist.jpg`, identical md5) for their
`photo-checklist-row__photo` image, even though they're different filenames.

Replaced the underlying files in place with real, distinct photos scraped
verbatim from the live site's own uploads:

- `brunch-checklist.jpg` <- `https://skylinecruises.com/wp-content/uploads/2020/04/nyc-brunch-cruise-statue-of-liberty-1-scaled.jpg`
  (real live NYC Brunch Cruises page photo)
- `lunch-checklist.jpg` <- `https://skylinecruises.com/wp-content/uploads/2020/04/nyc-lunch-scaled.jpg`
  (real live NYC Lunch Cruises page photo)

Deployed via `docker cp` directly onto the existing filenames in
`wp-content/uploads/2026/08/` (no post_content change needed — the URLs were
already correct/distinct, only the underlying file bytes were duplicated).
Verified via md5sum (now distinct) and Playwright element screenshot of
`.photo-checklist-row__photo` on both live pages.

Note: the 3 duplicate port-page hero photos (Chelsea Piers/Pier 36/Town Dock
Park, ids 416/417/418) were investigated too, but the real live site itself
reuses the same generic stock skyline photos across all 3 of those specific
port pages (no dedicated photography exists live for those docks) — so that
finding is not a fixable content gap and was left as-is.
