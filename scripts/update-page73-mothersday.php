<?php
// One-off content patch for Mother's Day Dinner Cruises (page ID 73): restores 2 real
// photo+copy sections that were missed in the original 8/24 scrape/build, plus the
// checklist's own real heading. Full post_content replaced with the corrected build-pages.js
// output (safe here since this page was never individually one-off-patched before, unlike
// page 70 — the live content matches the pre-fix manifest+composer exactly, confirmed by
// direct comparison before this patch).
require_once('/var/www/html/wp-load.php');

$page_id = 73;
$new_content = file_get_contents('/tmp/mothersday-content-new.json');
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

$checks = [
    'has queen photo' => strpos($after, 'skyline-may11-1024x536.jpg') !== false,
    'has queen copy' => strpos($after, 'treated like a queen') !== false,
    'has gift-of-time photo' => strpos($after, 'ny-lunch-cruises-1024x576.jpg') !== false,
    'has gift-of-time copy' => strpos($after, 'gift of your time') !== false,
    'has real checklist heading' => strpos($after, "The Skyline Mother's Day Cruise Includes:") !== false,
    'checklist items still intact' => strpos($after, 'Free parking at the marina') !== false,
    'testimonial still intact' => strpos($after, 'R. West') !== false,
    'closing CTA still intact' => strpos($after, '(718) 446-1100') !== false,
    'route map still intact' => strpos($after, 'route-map__canvas') !== false,
    'no [object Object]' => strpos($after, '[object Object]') === false,
];

$allPass = true;
foreach ($checks as $label => $pass) {
    echo ($pass ? "PASS" : "FAIL") . ": $label\n";
    if (!$pass) $allPass = false;
}

exit($allPass ? 0 : 1);
