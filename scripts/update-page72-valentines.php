<?php
// One-off content patch for NYC Valentine's Day Dinner Cruise (page ID 72): same defect class
// already found and fixed on Mother's Day / 4th of July / New Year's Eve.
require_once('/var/www/html/wp-load.php');

$page_id = 72;
$new_content = file_get_contents('/tmp/valentines-content-new.json');
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
    'has intro heading' => strpos($after, "Celebrate Valentine's Day Aboard Skyline Cruises") !== false,
    'has ctaLine' => strpos($after, "All tables are private on Valentine's Day") !== false,
    'has pairA photo' => strpos($after, 'vday-pairA-1024x576.jpg') !== false,
    'has pairA copy' => strpos($after, 'Our accommodating staff will take care') !== false,
    'has pairB photo' => strpos($after, 'vday-pairB-1024x576.jpg') !== false,
    'has pairB copy' => strpos($after, 'Celebrate the most romantic day of the year') !== false,
    'checklist items still intact' => strpos($after, 'Free parking at the marina') !== false,
    'testimonial still intact' => strpos($after, 'Patti E.') !== false,
    'closing CTA has no empty h2' => strpos($after, '<h2></h2>') === false,
    'no [object Object]' => strpos($after, '[object Object]') === false,
];

$allPass = true;
foreach ($checks as $label => $pass) {
    echo ($pass ? "PASS" : "FAIL") . ": $label\n";
    if (!$pass) $allPass = false;
}

exit($allPass ? 0 : 1);
