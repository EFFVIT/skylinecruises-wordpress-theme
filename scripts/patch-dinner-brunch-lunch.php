<?php
require_once('/var/www/html/wp-load.php');

function patch_page($page_id, $filename, $checks) {
    $new_content = file_get_contents($filename);
    if ($new_content === false || strlen($new_content) < 1000) {
        fwrite(STDERR, "Failed to read $filename\n");
        return false;
    }
    $before = get_post_field('post_content', $page_id);
    echo "Page $page_id — before: " . strlen($before) . "\n";
    kses_remove_filters();
    $result = wp_update_post(['ID' => $page_id, 'post_content' => wp_slash($new_content)], true);
    kses_init_filters();
    if (is_wp_error($result)) {
        fwrite(STDERR, "wp_update_post failed for $page_id: " . $result->get_error_message() . "\n");
        return false;
    }
    $after = get_post_field('post_content', $page_id);
    echo "Page $page_id — after: " . strlen($after) . "\n";
    $allPass = true;
    foreach ($checks as $label => $needle) {
        $pass = strpos($after, $needle) !== false;
        echo ($pass ? "PASS" : "FAIL") . ": $label\n";
        if (!$pass) $allPass = false;
    }
    return $allPass;
}

$r1 = patch_page(6, '/tmp/dinner-content-new.json', [
    'has intro photo' => 'dinner-intro-1024x576.jpg',
    'checklist heading still intact' => 'Your Skyline Experience Includes:',
    'testimonial intact' => 'Olga R.',
]);

$r2 = patch_page(68, '/tmp/brunch-content-new.json', [
    'has intro photo' => 'brunch-intro-1024x576.jpg',
    'has checklist heading' => 'Your Skyline Experience Includes:',
]);
// special-case the "no empty h2" check (needle=null means "must NOT contain")
$after68 = get_post_field('post_content', 68);
echo (strpos($after68, '<h2></h2>') === false ? "PASS" : "FAIL") . ": no empty h2 (brunch)\n";

$r3 = patch_page(69, '/tmp/lunch-content-new.json', [
    'has intro photo' => 'lunch-intro-1024x576.jpg',
]);
$after69 = get_post_field('post_content', 69);
echo (strpos($after69, '<h2></h2>') === false ? "PASS" : "FAIL") . ": no empty h2 (lunch)\n";

exit(($r1 && $r2 && $r3) ? 0 : 1);
