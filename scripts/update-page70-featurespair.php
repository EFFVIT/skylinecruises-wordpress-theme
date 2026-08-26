<?php
// One-off content patch for NYC Holiday Cruises (page ID 70): swap the bespoke
// icon+heading+paragraph "Smooth Sailing" text block for the real shared featuresPair()
// component (Memorable Service + Smooth Sailing icon-badge card), matching every other
// Public Cruise Service / Buffet Menu page on the site — per direct user request 2026-08-26.
require_once('/var/www/html/wp-load.php');

$page_id = 70;
$new_content = file_get_contents('/tmp/holiday-content2.json');
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
    'has features-pair' => strpos($after, 'features-pair__item') !== false,
    'has Memorable Service' => strpos($after, 'Memorable Service') !== false,
    'has Smooth Sailing heading' => strpos($after, '<h3>Smooth Sailing</h3>') !== false,
    'old bare Smooth Sailing text-section gone' => strpos($after, 'Our yacht is designed for year-round cruising. It is fully climate-controlled') === false,
    'no [object Object]' => strpos($after, '[object Object]') === false,
    'holiday-group photo still present' => strpos($after, 'holiday-group-1024x577.jpg') !== false,
    'flat hrefs still present' => strpos($after, '/nyc-holiday-cruises-4th-of-july/') !== false,
];

$allPass = true;
foreach ($checks as $label => $pass) {
    echo ($pass ? "PASS" : "FAIL") . ": $label\n";
    if (!$pass) $allPass = false;
}

exit($allPass ? 0 : 1);
