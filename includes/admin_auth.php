<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/admin_config.php';

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Strict');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ini_set('session.cookie_secure', '1');
}

function admin_require_auth(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $key = admin_session_key();
    $timeout = admin_session_timeout();

    if (empty($_SESSION[$key]) || empty($_SESSION['admin_login_time'])) {
        header('Location: login.php');
        exit;
    }

    if (time() - (int) $_SESSION['admin_login_time'] > $timeout) {
        admin_logout();
        header('Location: login.php?timeout=1');
        exit;
    }

    // Refresh login time on activity
    $_SESSION['admin_login_time'] = time();
}

function admin_is_logged_in(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $key = admin_session_key();
    $timeout = admin_session_timeout();

    if (empty($_SESSION[$key]) || empty($_SESSION['admin_login_time'])) {
        return false;
    }

    if (time() - (int) $_SESSION['admin_login_time'] > $timeout) {
        return false;
    }

    return true;
}

function admin_login(string $username, string $password): bool
{
    if ($username !== admin_username()) {
        return false;
    }
    if (admin_password_hash() === '' || !password_verify($password, admin_password_hash())) {
        return false;
    }

    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    session_regenerate_id(true);
    unset($_SESSION['admin_csrf']);
    $_SESSION[admin_session_key()] = true;
    $_SESSION['admin_login_time'] = time();
    $_SESSION['admin_username'] = $username;
    return true;
}

function admin_logout(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    unset($_SESSION[admin_session_key()]);
    unset($_SESSION['admin_login_time']);
    unset($_SESSION['admin_username']);
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function admin_csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['admin_csrf'];
}

function admin_verify_csrf(string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['admin_csrf']) && hash_equals($_SESSION['admin_csrf'], $token);
}

function admin_nav_link(string $page, string $label, string $icon): string
{
    $current = basename($_SERVER['PHP_SELF']);
    $active = $current === $page ? 'active' : '';
    return '<a href="' . htmlspecialchars($page) . '" class="admin-nav-link ' . $active . '"><i class="fas ' . htmlspecialchars($icon) . ' me-2"></i>' . htmlspecialchars($label) . '</a>';
}

function admin_render_header(string $title): void
{
    $username = $_SESSION['admin_username'] ?? 'Admin';
    echo '<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>' . htmlspecialchars($title) . ' | SCMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/site.css">
    <style>
        .admin-body { background: linear-gradient(135deg, #f7faf8 0%, #eef4ef 100%); min-height: 100vh; }
        .admin-sidebar { background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); min-height: 100vh; position: sticky; top: 0; padding: 1.5rem; color: #fff; }
        .admin-sidebar-brand { font-weight: 800; font-size: 1.25rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; }
        .admin-nav-link { display: flex; align-items: center; padding: 0.75rem 1rem; border-radius: 12px; color: rgba(255,255,255,0.85); text-decoration: none; font-weight: 600; transition: all 0.25s; margin-bottom: 0.35rem; }
        .admin-nav-link:hover, .admin-nav-link.active { background: rgba(255,255,255,0.15); color: #fff; }
        .admin-topbar { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
        .admin-content { padding: 2rem; }
        .admin-card { background: #fff; border-radius: 18px; padding: 1.5rem; box-shadow: 0 12px 30px rgba(15,23,42,0.06); border: none; height: 100%; }
        .admin-card h5 { font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; color: #888; margin-bottom: 0.5rem; }
        .admin-card .value { font-size: 2rem; font-weight: 800; color: var(--primary-color); }
        .admin-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .admin-table th { background: var(--primary-color); color: #fff; padding: 0.85rem 1rem; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .admin-table th:first-child { border-radius: 12px 0 0 0; }
        .admin-table th:last-child { border-radius: 0 12px 0 0; }
        .admin-table td { padding: 0.85rem 1rem; border-bottom: 1px solid #eee; color: #444; font-size: 0.95rem; vertical-align: middle; }
        .admin-table tr:hover td { background: #f8f9fa; }
        .admin-table tr:last-child td:first-child { border-radius: 0 0 0 12px; }
        .admin-table tr:last-child td:last-child { border-radius: 0 0 12px 0; }
        .admin-badge { display: inline-block; padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }
        .admin-badge-success { background: #ecfdf3; color: #166534; }
        .admin-badge-warning { background: #fffbeb; color: #92400e; }
        .admin-badge-danger { background: #fef2f2; color: #991b1b; }
        .admin-btn-sm { padding: 0.4rem 0.85rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; }
        .admin-btn-danger { background: #fef2f2; color: #991b1b; }
        .admin-btn-danger:hover { background: #fecaca; }
        .admin-btn-primary { background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: #fff; }
        .admin-btn-primary:hover { opacity: 0.9; color: #fff; }
        .admin-form-label { font-weight: 600; font-size: 0.9rem; color: var(--secondary-color); margin-bottom: 0.35rem; display: block; }
        .admin-form-control { width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 0.95rem; transition: all 0.2s; }
        .admin-form-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(26,95,42,0.1); }
        textarea.admin-form-control { min-height: 120px; resize: vertical; }
        .admin-alert { padding: 0.85rem 1rem; border-radius: 12px; margin-bottom: 1rem; font-weight: 600; font-size: 0.9rem; }
        .admin-alert-success { background: #ecfdf3; color: #166534; }
        .admin-alert-error { background: #fef2f2; color: #991b1b; }
        .login-card { max-width: 420px; margin: 0 auto; padding: 2.5rem; background: #fff; border-radius: 24px; box-shadow: 0 20px 60px rgba(15,23,42,0.1); }
        .login-logo { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-bottom: 1rem; }
        @media (max-width: 991px) { .admin-sidebar { min-height: auto; position: relative; } }
    </style>
</head>
<body class="admin-body">
<div class="container-fluid">
<div class="row">
    <div class="col-lg-2 admin-sidebar">
        <div class="admin-sidebar-brand">
            <img src="../assets/images/logo.jpg" alt="SCMS" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
            <span>SCMS Admin</span>
        </div>
        <nav>
            ' . admin_nav_link('dashboard.php', 'Dashboard', 'fa-chart-line') . '
            ' . admin_nav_link('applications.php', 'Applications', 'fa-file-alt') . '
            ' . admin_nav_link('messages.php', 'Messages', 'fa-envelope') . '
            ' . admin_nav_link('news.php', 'News', 'fa-newspaper') . '
            ' . admin_nav_link('programs.php', 'Programs', 'fa-list') . '
            ' . admin_nav_link('facilities.php', 'Facilities', 'fa-building') . '
            ' . admin_nav_link('faculty.php', 'Faculty', 'fa-chalkboard-teacher') . '
            ' . admin_nav_link('gallery.php', 'Gallery', 'fa-images') . '
            ' . admin_nav_link('downloads.php', 'Downloads', 'fa-download') . '
            ' . admin_nav_link('scholarships.php', 'Scholarships', 'fa-hand-holding-usd') . '
            ' . admin_nav_link('settings.php', 'Settings', 'fa-cog') . '
            ' . admin_nav_link('password.php', 'Change Password', 'fa-key') . '
        </nav>
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.15);">
            <a href="logout.php" class="admin-nav-link" style="color: rgba(255,255,255,0.7);">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </div>
    </div>
    <div class="col-lg-10">
        <div class="admin-topbar">
            <h4 style="margin:0;font-weight:700;">' . htmlspecialchars($title) . '</h4>
            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-md-inline" style="color:#666;font-size:0.9rem;"><i class="fas fa-user-circle me-1"></i>' . htmlspecialchars($username) . '</span>
                <a href="../index.php" target="_blank" class="btn btn-outline-custom btn-sm" style="font-size:0.85rem;padding:0.5rem 1rem;">
                    <i class="fas fa-external-link-alt me-1"></i>View Site
                </a>
                <a href="logout.php" class="btn btn-outline-custom btn-sm d-lg-none" style="font-size:0.85rem;padding:0.5rem 1rem;">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
        <div class="admin-content">
';
}

function admin_render_footer(): void
{
    echo '
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
}

function admin_flash(string $type, string $message): void
{
    $_SESSION['admin_flash'] = ['type' => $type, 'message' => $message];
}

function admin_render_flash(): void
{
    if (empty($_SESSION['admin_flash'])) return;
    $flash = $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
    $cls = $flash['type'] === 'success' ? 'admin-alert-success' : 'admin-alert-error';
    echo '<div class="admin-alert ' . $cls . '">' . htmlspecialchars($flash['message']) . '</div>';
}

function admin_messages_file(): string
{
    return dirname(__DIR__) . '/data/contact_messages.json';
}

function admin_load_messages(): array
{
    $file = admin_messages_file();
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    if ($json === false) return [];
    $data = json_decode($json, true);
    return is_array($data) ? array_reverse($data) : [];
}

function admin_save_messages(array $messages): bool
{
    $file = admin_messages_file();
    $dir = dirname($file);
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    return file_put_contents($file, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}

function admin_news_file(): string
{
    return dirname(__DIR__) . '/data/news.json';
}

function admin_load_news(): array
{
    $file = admin_news_file();
    if (!file_exists($file)) {
        // Seed with default news data
        return array_map(fn($n) => array_merge($n, ['id' => bin2hex(random_bytes(4))]), news_data());
    }
    $json = file_get_contents($file);
    if ($json === false) return [];
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function admin_save_news(array $news): bool
{
    $file = admin_news_file();
    $dir = dirname($file);
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    return file_put_contents($file, json_encode($news, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}

function admin_settings_file(): string
{
    return dirname(__DIR__) . '/data/settings.json';
}

function admin_load_settings(): array
{
    $file = admin_settings_file();
    if (!file_exists($file)) {
        return array_intersect_key(college_profile(), array_flip([
            'name', 'short_name', 'tagline', 'location', 'address', 'phone', 'mobile',
            'email', 'contact_email', 'admissions_email', 'website', 'facebook_url',
            'established', 'affiliation', 'recognition', 'maps_url', 'maps_embed_url'
        ]));
    }
    $json = file_get_contents($file);
    if ($json === false) return [];
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function admin_save_settings(array $settings): bool
{
    $file = admin_settings_file();
    $dir = dirname($file);
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    return file_put_contents($file, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}

function admin_read_json(string $file, array $fallback): array
{
    if (!file_exists($file)) return $fallback;
    $json = file_get_contents($file);
    if ($json === false) return $fallback;
    $data = json_decode($json, true);
    return is_array($data) ? $data : $fallback;
}

function admin_write_json(string $file, array $data): bool
{
    $dir = dirname($file);
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}

function admin_programs_file(): string
{
    return dirname(__DIR__) . '/data/programs.json';
}

function admin_load_programs(): array
{
    return admin_read_json(admin_programs_file(), programs_data());
}

function admin_save_programs(array $programs): bool
{
    return admin_write_json(admin_programs_file(), $programs);
}

function admin_facilities_file(): string
{
    return dirname(__DIR__) . '/data/facilities.json';
}

function admin_load_facilities(): array
{
    return admin_read_json(admin_facilities_file(), facility_data());
}

function admin_save_facilities(array $facilities): bool
{
    return admin_write_json(admin_facilities_file(), $facilities);
}

function admin_faculty_file(): string
{
    return dirname(__DIR__) . '/data/faculty.json';
}

function admin_load_faculty(): array
{
    return admin_read_json(admin_faculty_file(), faculty_data());
}

function admin_save_faculty(array $faculty): bool
{
    return admin_write_json(admin_faculty_file(), $faculty);
}

function admin_gallery_photos_file(): string
{
    return dirname(__DIR__) . '/data/gallery_photos.json';
}

function admin_load_gallery_photos(): array
{
    $stored = admin_read_json(admin_gallery_photos_file(), []);
    if (empty($stored)) {
        return gallery_photos_data();
    }

    $legacyTitles = [
        'Main Campus Building', 'Campus Aerial View', 'Campus Facilities',
        'Student Activities', 'Clinical Training', 'Campus Grounds',
        'Student Gathering', 'College Events', 'Campus Life',
        'Healthcare Training', 'Community Outreach', 'Expert Faculty',
        'Mentorship & Guidance', 'HERA Inspection Team', 'Campus Review',
        'Official Visit', 'Academic Excellence', 'Faculty Interaction',
        'HERA Visit Gallery', 'Campus Gallery',
    ];
    $legacyItems = [];
    $customBundles = [];
    foreach ($stored as $bundle) {
        if (empty($bundle['admin_bundle']) && in_array((string) ($bundle['title'] ?? ''), $legacyTitles, true)) {
            $legacyItems[] = $bundle;
        } else {
            $customBundles[] = gallery_normalize_bundle($bundle);
        }
    }

    $normalized = $customBundles;
    if (!empty($legacyItems)) {
        $legacyGroups = gallery_group_legacy_photos($legacyItems);
        foreach (gallery_photos_data() as $fallbackGroup) {
            if (!in_array(($fallbackGroup['title'] ?? ''), ['HERA Visit Gallery', 'Campus Gallery'], true)) {
                continue;
            }
            $matched = false;
            foreach ($legacyGroups as $legacyIndex => $legacyGroup) {
                if (($legacyGroup['title'] ?? '') !== ($fallbackGroup['title'] ?? '')) {
                    continue;
                }
                $matched = true;
                $legacyGroups[$legacyIndex]['image'] = array_values(array_unique(array_merge($legacyGroup['image'] ?? [], $fallbackGroup['image'] ?? [])));
                $legacyGroups[$legacyIndex]['thumb'] = array_values(array_unique(array_merge($legacyGroup['thumb'] ?? [], $fallbackGroup['thumb'] ?? [])));
            }
            if (!$matched) {
                $fallbackGroup['sort_order'] = ($fallbackGroup['title'] ?? '') === 'HERA Visit Gallery' ? 0 : 1;
                $legacyGroups[] = $fallbackGroup;
            }
        }
        $normalized = array_merge($normalized, array_map('gallery_normalize_bundle', $legacyGroups));
    }

    $normalized = array_values(array_filter($normalized, function ($bundle) {
        return !empty($bundle['image']);
    }));

    usort($normalized, function ($a, $b) {
        $aHera = !empty(array_filter($a['image'] ?? [], 'gallery_is_hera_path'));
        $bHera = !empty(array_filter($b['image'] ?? [], 'gallery_is_hera_path'));
        if (!isset($a['sort_order']) && !isset($b['sort_order']) && ($a['title'] ?? '') !== ($b['title'] ?? '')) {
            if (($a['title'] ?? '') === 'HERA Visit Gallery' || ($b['title'] ?? '') === 'HERA Visit Gallery') {
                return (($b['title'] ?? '') === 'HERA Visit Gallery') <=> (($a['title'] ?? '') === 'HERA Visit Gallery');
            }
        }
        if (!isset($a['sort_order']) && !isset($b['sort_order']) && $aHera !== $bHera) {
            return $bHera <=> $aHera;
        }

        if (isset($a['sort_order']) || isset($b['sort_order'])) {
            $aOrder = (int) ($a['sort_order'] ?? PHP_INT_MAX);
            $bOrder = (int) ($b['sort_order'] ?? PHP_INT_MAX);
            if ($aOrder !== $bOrder) {
                return $aOrder <=> $bOrder;
            }
        }
        $aTime = (int) ($a['updated_at'] ?? $a['created_at'] ?? 0);
        $bTime = (int) ($b['updated_at'] ?? $b['created_at'] ?? 0);
        if ($aTime !== $bTime) {
            return $bTime <=> $aTime;
        }

        return $bHera <=> $aHera;
    });

    return $normalized;
}

function admin_save_gallery_photos(array $photos): bool
{
    return admin_write_json(admin_gallery_photos_file(), $photos);
}

function admin_downloads_file(): string
{
    return dirname(__DIR__) . '/data/downloads.json';
}

function admin_load_downloads(): array
{
    return admin_read_json(admin_downloads_file(), download_groups_data());
}

function admin_save_downloads(array $downloads): bool
{
    return admin_write_json(admin_downloads_file(), $downloads);
}

function admin_scholarships_file(): string
{
    return dirname(__DIR__) . '/data/scholarships.json';
}

function admin_load_scholarships(): array
{
    return admin_read_json(admin_scholarships_file(), scholarship_types_data());
}

function admin_save_scholarships(array $scholarships): bool
{
    return admin_write_json(admin_scholarships_file(), $scholarships);
}

function admin_scholarship_eligibility_file(): string
{
    return dirname(__DIR__) . '/data/scholarship_eligibility.json';
}

function admin_load_scholarship_eligibility(): array
{
    return admin_read_json(admin_scholarship_eligibility_file(), scholarship_eligibility_data());
}

function admin_save_scholarship_eligibility(array $items): bool
{
    return admin_write_json(admin_scholarship_eligibility_file(), $items);
}
