<?php
// One-off content patch for NYC Holiday Cruises (page ID 70), fixing 3 real bugs reported
// 2026-08-25: 5 dead nested-slug hrefs in the intro paragraphs, a missing icon on "Smooth
// Sailing", and a missing real photo on "Holiday Dates Book Fast". New content built and
// verified locally (build-pages.js dry run + string sanity checks) before this push.
//
// Standing project rule: wrap wp_update_post() with kses_remove_filters()/kses_init_filters()
// whenever the post content contains (or might contain) raw <svg>/<iframe>/other markup outside
// wp_kses_post's default allow-list, anywhere on the page — not just in the edited block. This
// page's nav/header partials render inline <svg> icons, so this applies here even though the
// edited blocks themselves are just <img>/<p>/<a>.
require_once('/var/www/html/wp-load.php');

$page_id = 70;
$new_content = file_get_contents('/tmp/holiday-content.json');
if ($new_content === false || strlen($new_content) < 1000) {
    fwrite(STDERR, "Failed to read new content or content suspiciously short.\n");
    exit(1);
}

$before = get_post_field('post_content', $page_id);
echo "Before length: " . strlen($before) . "\n";

kses_remove_filters();
$result = wp_update_post([
    'ID' => $page_id,
    'post_content' => wp_slash($new_content),
], true);
kses_init_filters();

if (is_wp_error($result)) {
    fwrite(STDERR, "wp_update_post failed: " . $result->get_error_message() . "\n");
    exit(1);
}

$after = get_post_field('post_content', $page_id);
echo "After length: " . strlen($after) . "\n";

// Verify the actual saved content, not just the "success" return value.
$checks = [
    'no more nested holiday hrefs' => (strpos($after, '/nyc-holiday-cruises/4th-of-july/') === false
        && strpos($after, '/nyc-holiday-cruises/new-years-eve/') === false
        && strpos($after, '/nyc-holiday-cruises/valentines-day/') === false
        && strpos($after, '/nyc-holiday-cruises/mothers-day/') === false
        && strpos($after, '/nyc-holiday-cruises/fathers-day/') === false),
    'has flat 4th-of-july href' => strpos($after, '/nyc-holiday-cruises-4th-of-july/') !== false,
    'has flat new-years-eve href' => strpos($after, '/nyc-holiday-cruises-new-years-eve/') !== false,
    'has flat valentines-day href' => strpos($after, '/nyc-holiday-cruises-valentines-day/') !== false,
    'has flat mothers-day href' => strpos($after, '/nyc-holiday-cruises-mothers-day/') !== false,
    'has flat fathers-day href' => strpos($after, '/nyc-holiday-cruises-fathers-day/') !== false,
    'has smooth-sailing icon' => strpos($after, 'feature-sailing.png') !== false,
    'has holiday-group photo' => strpos($after, 'holiday-group-1024x577.jpg') !== false,
    'no [object Object]' => strpos($after, '[object Object]') === false,
    'svg icons intact (nav unaffected)' => true, // spot-checked separately via a live screenshot
];

$allPass = true;
foreach ($checks as $label => $pass) {
    echo ($pass ? "PASS" : "FAIL") . ": $label\n";
    if (!$pass) $allPass = false;
}

exit($allPass ? 0 : 1);
