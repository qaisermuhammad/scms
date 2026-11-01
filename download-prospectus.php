<?php
$file = __DIR__ . '/assets/documents/prospectus.pdf';
if (!is_file($file) || !is_readable($file)) {
    http_response_code(404);
    exit('The prospectus is currently unavailable.');
}
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="SCMS-Prospectus.pdf"');
header('Content-Length: ' . filesize($file));
header('X-Content-Type-Options: nosniff');
readfile($file);
