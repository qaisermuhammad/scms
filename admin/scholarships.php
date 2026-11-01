<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();

$scholarships = admin_load_scholarships();
usort($scholarships, function ($a, $b) {
    return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
});
$eligibility = admin_load_scholarship_eligibility();
$edit_item = null;
$edit_index = -1;

// Handle delete scholarship
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_index'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $idx = (int) $_POST['delete_index'];
        if (isset($scholarships[$idx])) {
            array_splice($scholarships, $idx, 1);
            admin_save_scholarships($scholarships);
            admin_flash('success', 'Scholarship deleted.');
        }
    }
    header('Location: scholarships.php');
    exit;
}

// Handle delete eligibility
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_eligibility_index'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $idx = (int) $_POST['delete_eligibility_index'];
        if (isset($eligibility[$idx])) {
            array_splice($eligibility, $idx, 1);
            admin_save_scholarship_eligibility($eligibility);
            admin_flash('success', 'Eligibility item deleted.');
        }
    }
    header('Location: scholarships.php');
    exit;
}

// Handle add/edit scholarship
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_scholarship'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $edit_index_value = trim((string) ($_POST['edit_index'] ?? ''));
        $edit_index = $edit_index_value === '' ? -1 : (int) $edit_index_value;

        if ($title === '' || $description === '') {
            admin_flash('error', 'Title and description are required.');
        } else {
            $new_item = [
                'title' => $title,
                'description' => $description,
                'created_at' => (int) ($scholarships[$edit_index]['created_at'] ?? time()),
                'updated_at' => time(),
            ];
            if ($edit_index >= 0 && isset($scholarships[$edit_index])) {
                $scholarships[$edit_index] = $new_item;
                admin_flash('success', 'Scholarship updated.');
            } else {
                array_unshift($scholarships, $new_item);
                admin_flash('success', 'Scholarship added.');
            }
            admin_save_scholarships($scholarships);
        }
    }
    header('Location: scholarships.php');
    exit;
}

// Handle add eligibility
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_eligibility'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $text = trim($_POST['eligibility_text'] ?? '');
        if ($text === '') {
            admin_flash('error', 'Eligibility text is required.');
        } else {
            $eligibility[] = $text;
            admin_save_scholarship_eligibility($eligibility);
            admin_flash('success', 'Eligibility item added.');
        }
    }
    header('Location: scholarships.php');
    exit;
}

// Load edit item
if (isset($_GET['edit'])) {
    $edit_index = (int) $_GET['edit'];
    if (isset($scholarships[$edit_index])) {
        $edit_item = $scholarships[$edit_index];
    }
}

admin_render_header('Scholarships');
admin_render_flash();
?>

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="admin-card">
            <h5><?php echo $edit_item ? 'Edit Scholarship' : 'Add Scholarship'; ?></h5>
            <form method="POST" action="scholarships.php" class="mt-3">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <input type="hidden" name="edit_index" value="<?php echo $edit_index >= 0 ? $edit_index : ''; ?>">
                <div class="mb-3">
                    <label class="admin-form-label">Title *</label>
                    <input type="text" name="title" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['title'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Description *</label>
                    <textarea name="description" class="admin-form-control" required><?php echo htmlspecialchars($edit_item['description'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="save_scholarship" class="admin-btn-sm admin-btn-primary">
                    <i class="fas <?php echo $edit_item ? 'fa-save' : 'fa-plus'; ?> me-1"></i><?php echo $edit_item ? 'Update' : 'Add'; ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="scholarships.php" class="admin-btn-sm admin-btn-danger ms-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="margin:0;">Scholarship Types</h5>
                <span class="admin-badge admin-badge-success"><?php echo count($scholarships); ?> Total</span>
            </div>
            <?php if (empty($scholarships)): ?>
                <p class="text-muted">No scholarships defined.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead><tr><th>#</th><th>Title</th><th style="width:120px;">Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($scholarships as $i => $item): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo htmlspecialchars($item['title'] ?? ''); ?></td>
                                    <td>
                                        <a href="scholarships.php?edit=<?php echo $i; ?>" class="admin-btn-sm admin-btn-primary"><i class="fas fa-edit"></i></a>
                                        <form method="POST" action="scholarships.php" onsubmit="return confirm('Delete?');" style="display:inline;">
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

<div class="row g-4">
    <div class="col-lg-5">
        <div class="admin-card">
            <h5>Add Eligibility Criteria</h5>
            <form method="POST" action="scholarships.php" class="mt-3">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <div class="mb-3">
                    <label class="admin-form-label">Criteria Text</label>
                    <input type="text" name="eligibility_text" class="admin-form-control" required placeholder="e.g. Minimum 70% marks in last exam">
                </div>
                <button type="submit" name="save_eligibility" class="admin-btn-sm admin-btn-primary"><i class="fas fa-plus me-1"></i>Add Criteria</button>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="margin:0;">Eligibility Criteria</h5>
                <span class="admin-badge admin-badge-success"><?php echo count($eligibility); ?> Total</span>
            </div>
            <?php if (empty($eligibility)): ?>
                <p class="text-muted">No criteria defined.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead><tr><th>#</th><th>Criteria</th><th style="width:80px;">Action</th></tr></thead>
                        <tbody>
                            <?php foreach ($eligibility as $i => $text): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo htmlspecialchars($text); ?></td>
                                    <td>
                                        <form method="POST" action="scholarships.php" onsubmit="return confirm('Delete?');" style="display:inline;">
                                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                                            <input type="hidden" name="delete_eligibility_index" value="<?php echo $i; ?>">
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
