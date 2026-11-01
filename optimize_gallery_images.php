<?php
/**
 * One-time: recompress existing gallery_*. uploads and create _thumb.jpg siblings.
 * Run: C:\xampp\php\php.exe optimize_gallery_images.php
 */
require_once __DIR__ . '/includes/functions.php';

if (!extension_loaded('gd')) {
    fwrite(STDERR, "GD extension is required.\n");
    exit(1);
}

$dir = __DIR__ . '/assets/images';
$files = glob($dir . '/gallery_*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE) ?: [];
$updatedJson = false;
$photos = gallery_photos_data();

foreach ($files as $file) {
    $base = basename($file);
    // Skip already-generated thumbs
    if (preg_match('/_thumb\.jpe?g$/i', $base)) {
        continue;
    }

    $info = pathinfo($file);
    $stem = $info['filename'];
    $fullOut = $info['dirname'] . DIRECTORY_SEPARATOR . $stem . '.jpg';
    $thumbOut = $info['dirname'] . DIRECTORY_SEPARATOR . $stem . '_thumb.jpg';

    $before = filesize($file);
    $tmp = $file . '.opt_tmp.jpg';

    if (!site_resize_image_to_jpeg($file, $tmp, 1600, 82)) {
        echo "SKIP (decode failed): $base\n";
        continue;
    }

    // Replace original: if original wasn't .jpg, remove it after writing jpg
    if (!@rename($tmp, $fullOut)) {
        @unlink($tmp);
        echo "FAIL write: $base\n";
        continue;
    }
    if (realpath($file) !== realpath($fullOut) && is_file($file)) {
        @unlink($file);
    }

    site_resize_image_to_jpeg($fullOut, $thumbOut, 640, 75);

    $after = filesize($fullOut);
    $thumbSize = is_file($thumbOut) ? filesize($thumbOut) : 0;
    echo sprintf(
        "OK %s  %0.1fKB -> %0.1fKB (thumb %0.1fKB)\n",
        $base,
        $before / 1024,
        $after / 1024,
        $thumbSize / 1024
    );

    // Update JSON paths if this file is referenced (esp. png -> jpg)
    $oldWeb = './assets/images/' . $base;
    $newWeb = './assets/images/' . $stem . '.jpg';
    $thumbWeb = './assets/images/' . $stem . '_thumb.jpg';

    foreach ($photos as &$photo) {
        $imgs = $photo['image'] ?? '';
        $list = is_array($imgs) ? $imgs : (empty($imgs) ? [] : [$imgs]);
        $thumbs = $photo['thumb'] ?? [];
        $thumbList = is_array($thumbs) ? $thumbs : (empty($thumbs) ? [] : [$thumbs]);

        $changed = false;
        foreach ($list as $idx => $path) {
            if ($path === $oldWeb || $path === $newWeb || basename((string) $path) === $base || basename((string) $path) === $stem . '.jpg') {
                $list[$idx] = $newWeb;
                $thumbList[$idx] = $thumbWeb;
                $changed = true;
            }
        }
        if ($changed) {
            $photo['image'] = $list;
            $photo['thumb'] = $thumbList;
            $updatedJson = true;
        }
    }
    unset($photo);
}

if ($updatedJson) {
    $jsonFile = __DIR__ . '/data/gallery_photos.json';
    file_put_contents($jsonFile, json_encode($photos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    echo "Updated data/gallery_photos.json with thumb paths.\n";
}

echo "Done.\n";
