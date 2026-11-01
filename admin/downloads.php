<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();

$downloads = admin_load_downloads();
usort($downloads, function ($a, $b) {
    return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
});
$edit_group = null;
$edit_group_index = -1;
$edit_item = null;
$edit_item_index = -1;

// Handle delete group
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_group_index'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $idx = (int) $_POST['delete_group_index'];
        if (isset($downloads[$idx])) {
            array_splice($downloads, $idx, 1);
            admin_save_downloads($downloads);
            admin_flash('success', 'Download group deleted.');
        }
    }
    header('Location: downloads.php');
    exit;
}

// Handle delete item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $gidx = (int) $_POST['group_index'];
        $iidx = (int) $_POST['item_index'];
        if (isset($downloads[$gidx]['items'][$iidx])) {
            array_splice($downloads[$gidx]['items'], $iidx, 1);
            admin_save_downloads($downloads);
            admin_flash('success', 'Download item deleted.');
        }
    }
    header('Location: downloads.php');
    exit;
}

// Handle add/edit group
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_group'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $title = trim($_POST['group_title'] ?? '');
        if ($title === '') {
            admin_flash('error', 'Group title is required.');
        } else {
            $edit_group_value = trim((string) ($_POST['edit_group_index'] ?? ''));
            $edit_group_index = $edit_group_value === '' ? -1 : (int) $edit_group_value;
            if ($edit_group_index >= 0 && isset($downloads[$edit_group_index])) {
                $downloads[$edit_group_index]['title'] = $title;
                $downloads[$edit_group_index]['updated_at'] = time();
                admin_flash('success', 'Group updated.');
            } else {
                array_unshift($downloads, ['title' => $title, 'items' => [], 'created_at' => time(), 'updated_at' => time()]);
                admin_flash('success', 'Group added.');
            }
            admin_save_downloads($downloads);
        }
    }
    header('Location: downloads.php');
    exit;
}

// Handle add/edit item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_item'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $gidx = (int) $_POST['group_index'];
        $label = trim($_POST['item_label'] ?? '');
        $href = trim($_POST['item_href'] ?? '');
        $meta = trim($_POST['item_meta'] ?? '');
        $available = isset($_POST['item_available']);
        if (!isset($downloads[$gidx])) {
            admin_flash('error', 'Group not found.');
        } elseif ($label === '') {
            admin_flash('error', 'Label is required.');
        } else {
            $edit_item_value = trim((string) ($_POST['edit_item_index'] ?? ''));
            $iidx = $edit_item_value === '' ? -1 : (int) $edit_item_value;
            $new_item = [
                'label' => $label,
                'href' => $href,
                'meta' => $meta,
                'available' => $available,
                'created_at' => (int) ($downloads[$gidx]['items'][$iidx]['created_at'] ?? time()),
                'updated_at' => time(),
            ];
            if ($iidx >= 0 && isset($downloads[$gidx]['items'][$iidx])) {
                $downloads[$gidx]['items'][$iidx] = $new_item;
                admin_flash('success', 'Item updated.');
            } else {
                array_unshift($downloads[$gidx]['items'], $new_item);
                admin_flash('success', 'Item added.');
            }
            $downloads[$gidx]['updated_at'] = time();
            admin_save_downloads($downloads);
        }
    }
    header('Location: downloads.php');
    exit;
}

admin_render_header('Downloads');
admin_render_flash();
?>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="admin-card">
            <h5>Add Download Group</h5>
            <form method="POST" action="downloads.php" class="mt-3">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <div class="mb-3">
                    <label class="admin-form-label">Group Title</label>
                    <input type="text" name="group_title" class="admin-form-control" required placeholder="e.g. Admission Documents">
                </div>
                <button type="submit" name="save_group" class="admin-btn-sm admin-btn-primary"><i class="fas fa-plus me-1"></i>Add Group</button>
            </form>
        </div>
    </div>
</div>

<?php foreach ($downloads as $gidx => $group): ?>
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 style="margin:0;"><?php echo htmlspecialchars($group['title'] ?? ''); ?></h5>
        <div class="d-flex gap-2">
            <form method="POST" action="downloads.php" onsubmit="return confirm('Delete this group and all its items?');" style="display:inline;">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <input type="hidden" name="delete_group_index" value="<?php echo $gidx; ?>">
                <button type="submit" class="admin-btn-sm admin-btn-danger"><i class="fas fa-trash-alt"></i> Delete Group</button>
            </form>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-4">
            <div style="background:#f8f9fa;border-radius:12px;padding:1.25rem;">
                <h6 style="font-size:0.85rem;margin-bottom:1rem;">Add Item</h6>
                <form method="POST" action="downloads.php">
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                    <input type="hidden" name="group_index" value="<?php echo $gidx; ?>">
                    <div class="mb-2">
                        <input type="text" name="item_label" class="admin-form-control" placeholder="Label" required style="padding:0.5rem 0.75rem;font-size:0.85rem;">
                    </div>
                    <div class="mb-2">
                        <input type="text" name="item_href" class="admin-form-control" placeholder="File path or URL" style="padding:0.5rem 0.75rem;font-size:0.85rem;">
                    </div>
                    <div class="mb-2">
                        <input type="text" name="item_meta" class="admin-form-control" placeholder="Meta text" style="padding:0.5rem 0.75rem;font-size:0.85rem;">
                    </div>
                    <div class="mb-2">
                        <label style="font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;">
                            <input type="checkbox" name="item_available" checked> Available for download
                        </label>
                    </div>
                    <button type="submit" name="save_item" class="admin-btn-sm admin-btn-primary" style="font-size:0.8rem;"><i class="fas fa-plus me-1"></i>Add Item</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <?php if (empty($group['items'] ?? [])): ?>
                <p class="text-muted">No items in this group.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Label</th><th>Path/URL</th><th>Meta</th><th>Status</th><th style="width:80px;">Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group['items'] as $iidx => $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['label'] ?? ''); ?></td>
                                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($item['href'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($item['meta'] ?? ''); ?></td>
                                    <td><?php echo ($item['available'] ?? false) ? '<span class="admin-badge admin-badge-success">Available</span>' : '<span class="admin-badge admin-badge-warning">Coming Soon</span>'; ?></td>
                                    <td>
                                        <form method="POST" action="downloads.php" onsubmit="return confirm('Delete this item?');" style="display:inline;">
                                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                                            <input type="hidden" name="group_index" value="<?php echo $gidx; ?>">
                                            <input type="hidden" name="item_index" value="<?php echo $iidx; ?>">
                                            <input type="hidden" name="delete_item" value="1">
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
<?php endforeach; ?>

<?php admin_render_footer(); ?>
