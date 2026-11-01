<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();
require_once __DIR__ . '/../includes/applications_db.php';
$messages = admin_load_messages();
$news = admin_load_news();
$programs = programs_data();
usort($messages, fn($a, $b) => strcmp($b['timestamp'] ?? '', $a['timestamp'] ?? ''));
usort($news, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
$months = [];
$now = new DateTimeImmutable('first day of this month', new DateTimeZone('UTC'));
for ($i = 5; $i >= 0; $i--) {
    $date = $now->modify('-' . $i . ' months');
    $months[$date->format('Y-m')] = ['label' => $date->format('M'), 'applications' => 0, 'messages' => 0];
}
foreach ($messages as $message) {
    $key = substr((string) ($message['timestamp'] ?? ''), 0, 7);
    if (isset($months[$key])) $months[$key]['messages']++;
}
$applicationCount = null;
$recentApplications = [];
try {
    $db = application_database();
    $applicationCount = (int) $db->query('SELECT COUNT(*) FROM applications')->fetchColumn();
    $stmt = $db->prepare('SELECT substr(submitted_at, 1, 7) AS month, COUNT(*) AS count FROM applications WHERE submitted_at >= :start GROUP BY month');
    $stmt->execute(['start' => array_key_first($months) . '-01']);
    foreach ($stmt as $row) if (isset($months[$row['month']])) $months[$row['month']]['applications'] = (int) $row['count'];
    $recentApplications = $db->query('SELECT reference, full_name, program_title, submitted_at FROM applications ORDER BY id DESC LIMIT 4')->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    error_log('Dashboard applications failed: ' . $e->getMessage());
    $applicationCount = null;
}
$maxCount = 1;
foreach ($months as $month) $maxCount = max($maxCount, $month['applications'], $month['messages']);
$cards = [
    ['Applications', $applicationCount, 'fa-file-alt', 'applications.php', 'Review forms', 'green'],
    ['Contact messages', count($messages), 'fa-envelope', 'messages.php', 'View inbox', 'blue'],
    ['News items', count($news), 'fa-newspaper', 'news.php', 'Manage news', 'gold'],
    ['Programs', count($programs), 'fa-graduation-cap', 'programs.php', 'Manage programs', 'violet'],
];
admin_render_header('Dashboard');
admin_render_flash();
?>
<style>
.dashboard { color:#17352a; max-width:1600px; margin:auto; }
.dashboard * { box-sizing:border-box; }
.dashboard-welcome { padding:2rem 2.25rem; border-radius:22px; background:linear-gradient(115deg,#123c2c,#1d6844 70%,#36875c); color:white; position:relative; overflow:hidden; margin-bottom:1.4rem; }
.dashboard-welcome:after { content:''; position:absolute; width:270px; height:270px; border:45px solid rgba(255,255,255,.05); border-radius:50%; right:-40px; top:-95px; }
.dashboard-welcome .eyebrow { text-transform:uppercase; font-size:.7rem; letter-spacing:.16em; font-weight:700; color:#b7e8c8; }
.dashboard-welcome h2 { color:white; font-size:clamp(1.5rem,2.5vw,2.2rem); margin:.45rem 0; font-weight:750; }
.dashboard-welcome p { color:#d0e8d8; margin:0; max-width:570px; }
.dash-stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:1rem; margin-bottom:1.25rem; }
.dash-stat,.dash-panel { background:white; border:1px solid #e5eee9; box-shadow:0 10px 30px rgba(24,68,43,.05); border-radius:18px; }
.dash-stat { padding:1.25rem; min-width:0; }
.dash-stat-top { display:flex; align-items:center; gap:.7rem; color:#687c71; font-size:.78rem; font-weight:700; }
.dash-icon { width:38px; height:38px; display:grid; place-items:center; border-radius:12px; flex-shrink:0; background:#e6f6ec; color:#268050; }
.dash-icon.blue {background:#e9f2ff;color:#3d6eae} .dash-icon.gold {background:#fff3dc;color:#a57520} .dash-icon.violet {background:#f1edff;color:#7660a9}
.dash-number { font-size:2rem; font-weight:800; margin:.8rem 0 .25rem; line-height:1; color:#153d2a; overflow-wrap:anywhere; }
.dash-number.unavailable { font-size:1.1rem; }
.dash-link { color:#24744a; font-weight:700; font-size:.82rem; text-decoration:none; }
.dash-layout { display:grid; grid-template-columns:minmax(0,1.65fr) minmax(280px,1fr); gap:1.25rem; margin-bottom:1.25rem; }
.dash-panel { padding:1.4rem; min-width:0; }
.dash-panel-head { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; margin-bottom:1rem; }
.dash-panel h3 { font-size:1.05rem; font-weight:750; margin:0; color:#17382a; }
.dash-muted { color:#788b80; font-size:.8rem; margin:.3rem 0 0; }
.dash-legend { display:flex; gap:1rem; flex-wrap:wrap; font-size:.75rem; color:#667a70; }
.dash-dot { display:inline-block; width:8px; height:8px; border-radius:50%; margin-right:5px; background:#268751; } .dash-dot.message {background:#a8cbb3}
.dash-chart { height:210px; display:grid; grid-template-columns:repeat(6,minmax(0,1fr)); gap:.6rem; border-bottom:1px solid #dfe9e2; padding:1rem .2rem 0; }
.dash-month { display:flex; align-items:center; justify-content:end; flex-direction:column; min-width:0; gap:.55rem; }
.dash-bars { height:165px; width:100%; display:flex; align-items:end; justify-content:center; gap:5px; }
.dash-bar { width:min(20px,30%); min-width:5px; border-radius:6px 6px 0 0; background:#268751; position:relative; } .dash-bar.message {background:#a8cbb3}
.dash-month-label { font-size:.74rem; color:#7b8c82; padding-bottom:.5rem; }
.dash-entry { display:flex; justify-content:space-between; gap:1rem; padding:.85rem 0; border-bottom:1px solid #edf2ee; }
.dash-entry:last-child { border-bottom:0; } .dash-entry strong { display:block; font-size:.86rem; color:#244436; overflow-wrap:anywhere; }
.dash-entry span { display:block; color:#7a8c82; font-size:.77rem; margin-top:.18rem; overflow-wrap:anywhere; }
.dash-empty { color:#819188; font-size:.9rem; padding:1.25rem 0; }
.dash-actions { display:flex; gap:.6rem; flex-wrap:wrap; } .dash-action { text-decoration:none; color:#255e3f; background:#f1f7f3; border:1px solid #e1eee5; padding:.65rem .85rem; border-radius:10px; font-size:.8rem; font-weight:700; }
.dash-action:hover { background:#e4f2e8; color:#15492e; }
@media(max-width:1199px) { .dash-stats {grid-template-columns:repeat(2,minmax(0,1fr))} }
@media(max-width:767px) { .dash-layout {grid-template-columns:1fr} .dashboard-welcome {padding:1.5rem} .dash-panel {padding:1.15rem} }
@media(max-width:420px) { .dash-stats {grid-template-columns:1fr} }
</style>
<div class="dashboard">
    <div class="dashboard-welcome"><div class="eyebrow">SCMS administration</div><h2>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>.</h2><p>A clear view of admissions, messages and the content that keeps your college connected.</p></div>
    <div class="dash-stats">
        <?php foreach ($cards as [$label, $value, $icon, $url, $action, $color]): ?>
        <div class="dash-stat"><div class="dash-stat-top"><span class="dash-icon <?php echo $color; ?>"><i class="fas <?php echo $icon; ?>" aria-hidden="true"></i></span><?php echo htmlspecialchars($label); ?></div><div class="dash-number <?php echo $value === null ? 'unavailable' : ''; ?>"><?php echo $value === null ? 'Unavailable' : (int) $value; ?></div><a class="dash-link" href="<?php echo htmlspecialchars($url); ?>"><?php echo htmlspecialchars($action); ?> &rarr;</a></div>
        <?php endforeach; ?>
    </div>
    <div class="dash-layout">
        <section class="dash-panel"><div class="dash-panel-head"><div><h3>Incoming activity</h3><p class="dash-muted">Applications and messages over the last six months</p></div><div class="dash-legend"><span><i class="dash-dot"></i>Applications</span><span><i class="dash-dot message"></i>Messages</span></div></div>
        <?php if ($applicationCount === null): ?><p class="dash-muted">Application counts are temporarily unavailable.</p><?php endif; ?>
        <div class="dash-chart" role="img" aria-label="Monthly application and message counts. Exact figures are listed below.">
            <?php foreach ($months as $key => $month): ?><div class="dash-month"><div class="dash-bars"><div class="dash-bar" style="height:<?php echo 100 * $month['applications'] / $maxCount; ?>%" title="<?php echo htmlspecialchars($key); ?>: <?php echo $month['applications']; ?> applications"></div><div class="dash-bar message" style="height:<?php echo 100 * $month['messages'] / $maxCount; ?>%" title="<?php echo htmlspecialchars($key); ?>: <?php echo $month['messages']; ?> messages"></div></div><span class="dash-month-label"><?php echo htmlspecialchars($month['label']); ?></span></div><?php endforeach; ?>
        </div><details class="mt-3"><summary class="dash-link">View exact monthly counts</summary><ul class="dash-muted mb-0 mt-2"><?php foreach ($months as $key => $month): ?><li><?php echo htmlspecialchars($key); ?>: <?php echo $applicationCount === null ? 'applications unavailable' : $month['applications'] . ' applications'; ?>, <?php echo $month['messages']; ?> messages</li><?php endforeach; ?></ul></details><p class="dash-muted">Applications use UTC; messages use the server time recorded when submitted.</p></section>
        <section class="dash-panel"><div class="dash-panel-head"><div><h3>Recent applications</h3><p class="dash-muted">Latest submissions</p></div><a class="dash-link" href="applications.php">View all &rarr;</a></div>
        <?php if ($applicationCount === null): ?><p class="dash-empty">Applications are temporarily unavailable.</p><?php elseif (!$recentApplications): ?><p class="dash-empty">No applications yet. New submissions will appear here.</p><?php else: foreach ($recentApplications as $item): ?><div class="dash-entry"><div><strong><?php echo htmlspecialchars($item['full_name']); ?></strong><span><?php echo htmlspecialchars($item['program_title']); ?></span></div><div><strong><?php echo htmlspecialchars($item['reference']); ?></strong><span><?php echo htmlspecialchars(substr($item['submitted_at'], 0, 10)); ?></span></div></div><?php endforeach; endif; ?></section>
    </div>
    <div class="dash-layout">
        <section class="dash-panel"><div class="dash-panel-head"><div><h3>Recent messages</h3><p class="dash-muted">Latest inquiries from your website</p></div><a class="dash-link" href="messages.php">Open inbox &rarr;</a></div><?php if (!$messages): ?><p class="dash-empty">No messages yet.</p><?php else: foreach (array_slice($messages, 0, 4) as $item): ?><div class="dash-entry"><div><strong><?php echo htmlspecialchars($item['name'] ?? ''); ?></strong><span><?php echo htmlspecialchars($item['subject'] ?? ''); ?></span></div><span><?php echo htmlspecialchars(substr($item['timestamp'] ?? '', 0, 10)); ?></span></div><?php endforeach; endif; ?></section>
        <section class="dash-panel"><div class="dash-panel-head"><div><h3>Quick actions</h3><p class="dash-muted">Go straight to the essentials</p></div></div><div class="dash-actions"><a class="dash-action" href="news.php"><i class="fas fa-pen me-1"></i> Manage news</a><a class="dash-action" href="gallery.php"><i class="fas fa-image me-1"></i> Update gallery</a><a class="dash-action" href="programs.php"><i class="fas fa-graduation-cap me-1"></i> Programs</a><a class="dash-action" href="settings.php"><i class="fas fa-cog me-1"></i> Settings</a><a class="dash-action" href="../index.php" target="_blank" rel="noopener"><i class="fas fa-external-link-alt me-1"></i> View website</a></div></section>
    </div>
</div>
<?php admin_render_footer(); ?>
