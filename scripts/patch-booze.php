<?php
require_once('/var/www/html/wp-load.php');
$page_id = 76;
$new_content = file_get_contents('/tmp/booze-content-new.json');
if ($new_content === false || strlen($new_content) < 1000) { fwrite(STDERR, "read fail\n"); exit(1); }
$before = get_post_field('post_content', $page_id);
echo "Before: " . strlen($before) . "\n";
kses_remove_filters();
$result = wp_update_post(['ID' => $page_id, 'post_content' => wp_slash($new_content)], true);
kses_init_filters();
if (is_wp_error($result)) { fwrite(STDERR, $result->get_error_message() . "\n"); exit(1); }
$after = get_post_field('post_content', $page_id);
echo "After: " . strlen($after) . "\n";
$checks = [
    'has skyline-night photo' => strpos($after, 'booze-1-1024x576.jpg') !== false,
    'has dj photo' => strpos($after, 'booze-2-1024x576.jpg') !== false,
    'has real Full Service paragraph' => strpos($after, 'three large levels, three bars') !== false,
    'fabricated prefix removed' => strpos($after, 'is not your average NYC party boat. Skyline') === false,
    'checklist items still intact' => strpos($after, 'keep the drinks coming') !== false,
    'testimonials still intact' => strpos($after, 'Marino Catalan') !== false && strpos($after, 'Kim Phelan') !== false,
];
$allPass = true;
foreach ($checks as $label => $pass) { echo ($pass?"PASS":"FAIL").": $label\n"; if (!$pass) $allPass = false; }
exit($allPass ? 0 : 1);
