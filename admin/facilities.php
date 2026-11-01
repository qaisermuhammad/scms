<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();

$facilities = admin_load_facilities();
usort($facilities, function ($a, $b) {
    return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
});
$edit_item = null;
$edit_index = -1;

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_index'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $idx = (int) $_POST['delete_index'];
        if (isset($facilities[$idx])) {
            array_splice($facilities, $idx, 1);
            admin_save_facilities($facilities);
            admin_flash('success', 'Facility deleted successfully.');
        }
    }
    header('Location: facilities.php');
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_facility'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-building');
        $edit_index_value = trim((string) ($_POST['edit_index'] ?? ''));
        $edit_index = $edit_index_value === '' ? -1 : (int) $edit_index_value;

        if ($title === '' || $description === '') {
            admin_flash('error', 'Title and description are required.');
        } else {
            $new_item = [
                'title' => $title,
                'description' => $description,
                'icon' => $icon,
                'created_at' => (int) ($facilities[$edit_index]['created_at'] ?? time()),
                'updated_at' => time(),
            ];

            if ($edit_index >= 0 && isset($facilities[$edit_index])) {
                $facilities[$edit_index] = $new_item;
                admin_flash('success', 'Facility updated successfully.');
            } else {
                array_unshift($facilities, $new_item);
                admin_flash('success', 'Facility added successfully.');
            }
            admin_save_facilities($facilities);
        }
    }
    header('Location: facilities.php');
    exit;
}

// Load edit item
if (isset($_GET['edit'])) {
    $edit_index = (int) $_GET['edit'];
    if (isset($facilities[$edit_index])) {
        $edit_item = $facilities[$edit_index];
    }
}

$icon_options = [
    'fa-chalkboard-teacher' => 'Classroom',
    'fa-flask' => 'Laboratory',
    'fa-hospital' => 'Hospital',
    'fa-book' => 'Library',
    'fa-user-md' => 'Medical',
    'fa-bed' => 'Hostel',
    'fa-running' => 'Sports',
    'fa-bus' => 'Transport',
    'fa-wifi' => 'Wi-Fi',
    'fa-shield-alt' => 'Security',
    'fa-utensils' => 'Cafeteria',
    'fa-wheelchair' => 'Accessibility',
];

admin_render_header('Facilities');
admin_render_flash();
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card">
            <h5><?php echo $edit_item ? 'Edit Facility' : 'Add Facility'; ?></h5>
            <form method="POST" action="facilities.php" class="mt-3">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <input type="hidden" name="edit_index" value="<?php echo $edit_index >= 0 ? $edit_index : ''; ?>">
                <div class="mb-3">
                    <label class="admin-form-label">Title *</label>
                    <input type="text" name="title" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['title'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Icon</label>
                    <select name="icon" class="admin-form-control">
                        <?php foreach ($icon_options as $val => $label): ?>
                            <option value="<?php echo htmlspecialchars($val); ?>" <?php echo ($edit_item['icon'] ?? '') === $val ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Description *</label>
                    <textarea name="description" class="admin-form-control" required><?php echo htmlspecialchars($edit_item['description'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="save_facility" class="admin-btn-sm admin-btn-primary">
                    <i class="fas <?php echo $edit_item ? 'fa-save' : 'fa-plus'; ?> me-1"></i><?php echo $edit_item ? 'Update Facility' : 'Add Facility'; ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="facilities.php" class="admin-btn-sm admin-btn-danger ms-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="margin:0;">All Facilities</h5>
                <span class="admin-badge admin-badge-success"><?php echo count($facilities); ?> Total</span>
            </div>
            <?php if (empty($facilities)): ?>
                <p class="text-muted">No facilities yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>#</th><th>Title</th><th>Icon</th><th style="width:120px;">Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facilities as $i => $item): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo htmlspecialchars($item['title'] ?? ''); ?></td>
                                    <td><i class="fas <?php echo htmlspecialchars($item['icon'] ?? ''); ?>"></i></td>
                                    <td>
                                        <a href="facilities.php?edit=<?php echo $i; ?>" class="admin-btn-sm admin-btn-primary"><i class="fas fa-edit"></i></a>
                                        <form method="POST" action="facilities.php" onsubmit="return confirm('Delete this facility?');" style="display:inline;">
                                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                                            <input type="hidden" name="delete_index" value="<?php echo $i; ?>">
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
