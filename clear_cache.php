<?php
require_once __DIR__ . '/includes/admin_auth.php';
if (!admin_is_logged_in()) {
    header('Location: admin/login.php');
    exit;
}
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
$result = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf(is_string($_POST['csrf'] ?? null) ? $_POST['csrf'] : '')) {
        http_response_code(403);
        $result = 'Invalid request. Refresh the page and try again.';
    } elseif (!function_exists('opcache_reset')) {
        $result = 'OPcache reset is unavailable. Ask your hosting provider to clear the PHP cache.';
    } elseif (@opcache_reset()) {
        $result = 'A PHP OPcache reset was requested. This does not clear browser, CDN, or other server caches.';
    } else {
        $result = 'PHP OPcache could not be reset or is not active. Ask your hosting provider to check.';
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Clear PHP Cache | SCMS</title><style>body{font-family:system-ui,sans-serif;background:#f3f7f4;color:#173b29;display:grid;place-items:center;min-height:100vh;margin:0;padding:1rem}.card{background:white;padding:2rem;border-radius:16px;max-width:32rem;box-shadow:0 10px 35px #0001}button,a{display:inline-block;padding:.7rem 1rem;border-radius:8px}button{background:#17623a;color:white;border:0;cursor:pointer}p{line-height:1.5}</style></head><body><main class="card"><h1>Clear PHP cache</h1><p>This requests an OPcache reset for this PHP environment. It does not clear browser or CDN caches.</p><?php if ($result !== ''): ?><p role="status"><?php echo htmlspecialchars($result); ?></p><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>"><button type="submit">Request PHP cache reset</button></form><a href="index.php">Return to website</a></main></body></html>
