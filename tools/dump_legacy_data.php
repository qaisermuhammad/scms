<?php
// Dumps all legacy site data to JSON for Laravel seeders.
// Run: php tools/dump_legacy_data.php
require_once __DIR__ . '/../includes/functions.php';

$outDir = __DIR__ . '/../scms-laravel/database/seeders/data';
if (!is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

$datasets = [
    'college_profile' => college_profile(),
    'programs' => programs_data(),
    'news' => news_data(),
    'features' => feature_data(),
    'facilities' => facility_data(),
    'owner_profile' => owner_profile_data(),
    'faculty' => faculty_data(),
    'about_values' => about_values_data(),
    'about_timeline' => about_timeline_data(),
    'admissions_steps' => admissions_steps_data(),
    'admissions_faq' => admissions_faq_data(),
    'scholarship_types' => scholarship_types_data(),
    'scholarship_eligibility' => scholarship_eligibility_data(),
    'download_groups' => download_groups_data(),
    'gallery_photos' => gallery_photos_data(),
    'gallery_videos' => gallery_videos_data(),
    'contact_subjects' => contact_subjects_data(),
    'contact_departments' => contact_departments_data(),
];

foreach ($datasets as $name => $data) {
    file_put_contents(
        $outDir . '/' . $name . '.json',
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    );
    $count = is_countable($data) ? count($data) : 'assoc';
    echo "dumped $name ($count)\n";
}

// Runtime-written files (may not exist)
foreach (['settings' => __DIR__ . '/../data/settings.json',
          'contact_messages' => __DIR__ . '/../data/contact_messages.json'] as $name => $file) {
    if (file_exists($file)) {
        copy($file, $outDir . '/' . $name . '.json');
        echo "copied $name.json\n";
    } else {
        echo "missing $name.json (ok, defaults only)\n";
    }
}
echo "done\n";
