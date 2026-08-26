<?php
// One-off content patch for NYC New Years Eve Cruise (page ID 71): same defect class already
// found and fixed on Mother's Day / 4th of July — restores 2 missing real photo+copy sections.
require_once('/var/www/html/wp-load.php');

$page_id = 71;
$new_content = file_get_contents('/tmp/nye-content-new.json');
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
    'has intro heading' => strpos($after, 'Ring in the New Year in New York Harbor') !== false,
    'has pairA photo' => strpos($after, 'nye-pairA-1024x576.jpg') !== false,
    'has pairA copy' => strpos($after, 'world-renowned New York City skyline') !== false,
    'has pairC photo' => strpos($after, 'nye-pairC-1024x576.jpg') !== false,
    'has pairC copy' => strpos($after, 'Treat yourself and your friends') !== false,
    'checklist items still intact' => strpos($after, 'Free parking at the marina.') !== false,
    'testimonial still intact' => strpos($after, 'Elena S') !== false,
    'closing CTA has no empty h2' => strpos($after, '<h2></h2>') === false,
    'no [object Object]' => strpos($after, '[object Object]') === false,
];

$allPass = true;
foreach ($checks as $label => $pass) {
    echo ($pass ? "PASS" : "FAIL") . ": $label\n";
    if (!$pass) $allPass = false;
}

exit($allPass ? 0 : 1);
