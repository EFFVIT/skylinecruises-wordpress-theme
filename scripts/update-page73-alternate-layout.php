<?php
// One-off content patch for Mother's Day Dinner Cruises (page ID 73): per direct user request,
// move the "gift of your time" photo+text section above the checklist (alternating photo side
// with the section above it), and give the checklist its 2-col card treatment instead of a bare
// centered single column. Full post_content replaced with the corrected build-pages.js output —
// safe here since this page's content was already a full-replacement patch (c5c0937), so this is
// the same trusted path, not a first-time surgical edit.
require_once('/var/www/html/wp-load.php');

$page_id = 73;
$new_content = file_get_contents('/tmp/mothersday-content-new2.json');
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
    'has photo-left class' => strpos($after, 'text-section--photo-left') !== false,
    'has checklist card class' => strpos($after, 'photo-checklist-row__list--card') !== false,
    'has 2-col grid' => strpos($after, 'grid-template-columns:repeat(2,1fr)') !== false,
    'gift-of-time section before checklist' => strpos($after, 'gift of your time') < strpos($after, 'photo-checklist-row'),
    'queen section still intact' => strpos($after, 'treated like a queen') !== false,
    'checklist items still intact' => strpos($after, 'Free parking at the marina') !== false,
    'testimonial still intact' => strpos($after, 'R. West') !== false,
    'route map still intact' => strpos($after, 'route-map__canvas') !== false,
    'no [object Object]' => strpos($after, '[object Object]') === false,
];

$allPass = true;
foreach ($checks as $label => $pass) {
    echo ($pass ? "PASS" : "FAIL") . ": $label\n";
    if (!$pass) $allPass = false;
}

exit($allPass ? 0 : 1);
