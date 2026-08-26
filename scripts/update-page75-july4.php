<?php
// One-off content patch for NYC 4th of July Firework Dinner Cruises (page ID 75): restores 2
// missing real photo+copy sections and the checklist's own real heading + real photo, per the
// same live-vs-built gap class already found and fixed on Mother's Day (commit c5c0937). Full
// post_content replaced with the corrected build-pages.js output — safe here since this page
// was never individually one-off-patched before (confirmed: its live content matched the
// pre-fix manifest+composer output exactly, same verification done before every prior full
// replacement on this project).
require_once('/var/www/html/wp-load.php');

$page_id = 75;
$new_content = file_get_contents('/tmp/july4-content-new.json');
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
    'has pair1 photo' => strpos($after, 'july4-pair1-1024x576.jpg') !== false,
    'has pair2 photo' => strpos($after, 'july4-pair2-1024x576.jpg') !== false,
    'has real checklist photo' => strpos($after, 'july4-checklist-real.jpg') !== false,
    'has pair1 heading' => strpos($after, '4th of July And Skyline Cruises') !== false,
    'has real checklist heading' => strpos($after, "The Skyline 4th of July Experience Includes:") !== false,
    'has pair2 copy' => strpos($after, 'Celebrate 4th of July in style') !== false,
    'checklist items still intact' => strpos($after, 'Free parking at the marina') !== false,
    'testimonial still intact' => strpos($after, 'Dennis H.') !== false,
    'route map still intact' => strpos($after, 'route-map__canvas') !== false,
    'no [object Object]' => strpos($after, '[object Object]') === false,
];

$allPass = true;
foreach ($checks as $label => $pass) {
    echo ($pass ? "PASS" : "FAIL") . ": $label\n";
    if (!$pass) $allPass = false;
}

exit($allPass ? 0 : 1);
