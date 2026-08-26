<?php
// One-off content patch for NYC Father's Day Cruises (page ID 74): same defect class already
// found and fixed on Mother's Day / 4th of July / New Year's Eve / Valentine's Day.
require_once('/var/www/html/wp-load.php');

$page_id = 74;
$new_content = file_get_contents('/tmp/fathersday-content-new.json');
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
    'has intro heading' => strpos($after, "Father’s Day Brunch and Dinner Cruises") !== false,
    'has real checklist heading' => strpos($after, "Our Father's Day Cruise Includes:") !== false,
    'has continuation paragraph' => strpos($after, 'A four-hour cruise means family time') !== false,
    'has pairA heading' => strpos($after, "Spend Father's Day Cruising On New York Harbor") !== false,
    'has pairA photo' => strpos($after, 'fathersday-pairA-1024x576.jpg') !== false,
    'has pairB copy' => strpos($after, 'Take photographs on the deck') !== false,
    'has pairB photo' => strpos($after, 'fathersday-pairB-1024x576.jpg') !== false,
    'checklist items still intact' => strpos($after, 'Free parking at the marina') !== false,
    'testimonial still intact' => strpos($after, 'L. Zhinin') !== false,
    'closing CTA still intact' => strpos($after, "Skyline Cruises will make your Father") !== false,
    'no [object Object]' => strpos($after, '[object Object]') === false,
];

$allPass = true;
foreach ($checks as $label => $pass) {
    echo ($pass ? "PASS" : "FAIL") . ": $label\n";
    if (!$pass) $allPass = false;
}

exit($allPass ? 0 : 1);
