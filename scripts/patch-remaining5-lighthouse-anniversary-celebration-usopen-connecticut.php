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
    echo "\n";
    return $allPass;
}

$r1 = patch_page(77, '/tmp/lighthouse-content-new.json', [
    'has photo1' => 'lighthouse-1-1024x576.jpg',
    'has photo2' => 'lighthouse-2-1024x576.jpg',
    'cta line intact' => 'Check Out Our Schedule For Our Next Lighthouse Cruise!',
]);

$r2 = patch_page(78, '/tmp/anniversary-content-new.json', [
    'has anniv-1' => 'anniv-1-1024x576.jpg',
    'has anniv-2' => 'anniv-2-1024x576.jpg',
    'has anniv-3' => 'anniv-3-1024x576.jpg',
    'has anniv-4' => 'anniv-4-1024x576.jpg',
    'has shared beef photo' => 'lighthouse-2-1024x576.jpg',
    'ports checklist intact' => 'Pier 36, Downtown Manhattan, NYC',
    'testimonial intact' => 'Shannon Q.',
]);

$r3 = patch_page(84, '/tmp/celebration-content-new.json', [
    'has Book Your Event heading' => 'Book Your Event',
]);

$r4 = patch_page(80, '/tmp/usopen-content-new.json', [
    'has tennis photo' => 'usopen-1-768x1024.jpg',
    'has cta link' => '<a href="https://skylinecruises.com/contact-us/">Contact Us For Info',
    'ports list intact' => 'Private Event Cruise Ports',
]);

$r5 = patch_page(81, '/tmp/connecticut-content-new.json', [
    'has ct-1' => 'ct-1.jpg',
    'has ct-2' => 'ct-2-1024x576.jpg',
    'has ct-3' => 'ct-3-1024x576.jpg',
    'ports checklist intact' => 'Stamford, CT',
]);

exit(($r1 && $r2 && $r3 && $r4 && $r5) ? 0 : 1);
