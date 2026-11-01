<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();

$news = admin_load_news();
usort($news, function($a, $b) {
    return strtotime($b['date'] ?? '') <=> strtotime($a['date'] ?? '');
});
$edit_item = null;


// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $delete_id = $_POST['delete_id'];
        $news = array_values(array_filter($news, fn($n) => ($n['id'] ?? '') !== $delete_id));
        admin_save_news($news);
        admin_flash('success', 'News item deleted successfully.');
    }
    header('Location: news.php');
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_news'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $title = trim($_POST['title'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $edit_id = trim($_POST['edit_id'] ?? '');

        if ($title === '' || $date === '' || $summary === '') {
            admin_flash('error', 'Title, date, and summary are required.');
        } else {
            $new_item = [
                'id' => $edit_id ?: bin2hex(random_bytes(4)),
                'title' => $title,
                'date' => $date,
                'category' => $category,
                'summary' => $summary,
                'link' => $link ?: 'news.php',
            ];

            if ($edit_id) {
                $found = false;
                foreach ($news as $i => $item) {
                    if (($item['id'] ?? '') === $edit_id) {
                        $news[$i] = $new_item;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $news[] = $new_item;
                }
                admin_flash('success', 'News item updated successfully.');
            } else {
                $news[] = $new_item;
                admin_flash('success', 'News item added successfully.');
            }
            admin_save_news($news);
        }
    }
    header('Location: news.php');
    exit;
}

// Load edit item
if (isset($_GET['edit'])) {
    foreach ($news as $item) {
        if (($item['id'] ?? '') === $_GET['edit']) {
            $edit_item = $item;
            break;
        }
    }
}

admin_render_header('News');
admin_render_flash();
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card">
            <h5><?php echo $edit_item ? 'Edit News Item' : 'Add News Item'; ?></h5>
            <form method="POST" action="news.php" class="mt-3">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($edit_item['id'] ?? ''); ?>">
                <div class="mb-3">
                    <label class="admin-form-label">Title *</label>
                    <input type="text" name="title" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['title'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Date *</label>
                    <input type="date" name="date" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['date'] ?? date('Y-m-d')); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Category</label>
                    <input type="text" name="category" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['category'] ?? 'General'); ?>">
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Summary *</label>
                    <textarea name="summary" class="admin-form-control" required><?php echo htmlspecialchars($edit_item['summary'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Link</label>
                    <input type="text" name="link" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['link'] ?? 'news.php'); ?>">
                </div>
                <button type="submit" name="save_news" class="admin-btn-sm admin-btn-primary">
                    <i class="fas <?php echo $edit_item ? 'fa-save' : 'fa-plus'; ?> me-1"></i><?php echo $edit_item ? 'Update News' : 'Add News'; ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="news.php" class="admin-btn-sm admin-btn-danger ms-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="margin:0;">All News Items</h5>
                <span class="admin-badge admin-badge-success"><?php echo count($news); ?> Total</span>
            </div>
            <?php if (empty($news)): ?>
                <p class="text-muted">No news items yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th style="width:120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($news as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['date'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($item['title'] ?? ''); ?></td>
                                    <td><span class="admin-badge admin-badge-success"><?php echo htmlspecialchars($item['category'] ?? ''); ?></span></td>
                                    <td>
                                        <a href="news.php?edit=<?php echo htmlspecialchars($item['id'] ?? ''); ?>" class="admin-btn-sm admin-btn-primary"><i class="fas fa-edit"></i></a>
                                        <form method="POST" action="news.php" onsubmit="return confirm('Delete this news item?');" style="display:inline;">
                                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                                            <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>">
                                            <button type="submit" class="admin-btn-sm admin-btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php admin_render_footer(); ?>
