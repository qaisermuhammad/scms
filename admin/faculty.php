<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();

$faculty = admin_load_faculty();
usort($faculty, function ($a, $b) {
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
        if (isset($faculty[$idx])) {
            array_splice($faculty, $idx, 1);
            admin_save_faculty($faculty);
            admin_flash('success', 'Faculty member deleted successfully.');
        }
    }
    header('Location: faculty.php');
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_faculty'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $name = trim($_POST['name'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $qualification = trim($_POST['qualification'] ?? '');
        $experience = trim($_POST['experience'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $edit_index_value = trim((string) ($_POST['edit_index'] ?? ''));
        $edit_index = $edit_index_value === '' ? -1 : (int) $edit_index_value;

        if ($name === '' || $designation === '') {
            admin_flash('error', 'Name and designation are required.');
        } else {
            $new_item = [
                'name' => $name,
                'designation' => $designation,
                'qualification' => $qualification,
                'experience' => $experience,
                'department' => $department,
                'image' => $image,
                'bio' => $bio,
                'created_at' => (int) ($faculty[$edit_index]['created_at'] ?? time()),
                'updated_at' => time(),
            ];

            if ($edit_index >= 0 && isset($faculty[$edit_index])) {
                $faculty[$edit_index] = $new_item;
                admin_flash('success', 'Faculty member updated successfully.');
            } else {
                array_unshift($faculty, $new_item);
                admin_flash('success', 'Faculty member added successfully.');
            }
            admin_save_faculty($faculty);
        }
    }
    header('Location: faculty.php');
    exit;
}

// Load edit item
if (isset($_GET['edit'])) {
    $edit_index = (int) $_GET['edit'];
    if (isset($faculty[$edit_index])) {
        $edit_item = $faculty[$edit_index];
    }
}

admin_render_header('Faculty');
admin_render_flash();
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card">
            <h5><?php echo $edit_item ? 'Edit Faculty' : 'Add Faculty'; ?></h5>
            <form method="POST" action="faculty.php" class="mt-3">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <input type="hidden" name="edit_index" value="<?php echo $edit_index >= 0 ? $edit_index : ''; ?>">
                <div class="mb-3">
                    <label class="admin-form-label">Full Name *</label>
                    <input type="text" name="name" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['name'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Designation *</label>
                    <input type="text" name="designation" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['designation'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Qualification</label>
                    <input type="text" name="qualification" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['qualification'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Experience</label>
                    <input type="text" name="experience" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['experience'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Department</label>
                    <input type="text" name="department" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['department'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Image Filename</label>
                    <input type="text" name="image" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['image'] ?? ''); ?>" placeholder="e.g. drsiyyar.jpg">
                    <small class="text-muted">Upload image to assets/images/ first.</small>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Bio</label>
                    <textarea name="bio" class="admin-form-control"><?php echo htmlspecialchars($edit_item['bio'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="save_faculty" class="admin-btn-sm admin-btn-primary">
                    <i class="fas <?php echo $edit_item ? 'fa-save' : 'fa-plus'; ?> me-1"></i><?php echo $edit_item ? 'Update Faculty' : 'Add Faculty'; ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="faculty.php" class="admin-btn-sm admin-btn-danger ms-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="margin:0;">All Faculty</h5>
                <span class="admin-badge admin-badge-success"><?php echo count($faculty); ?> Total</span>
            </div>
            <?php if (empty($faculty)): ?>
                <p class="text-muted">No faculty members yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>#</th><th>Name</th><th>Designation</th><th>Department</th><th style="width:120px;">Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faculty as $i => $item): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo htmlspecialchars($item['name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($item['designation'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($item['department'] ?? ''); ?></td>
                                    <td>
                                        <a href="faculty.php?edit=<?php echo $i; ?>" class="admin-btn-sm admin-btn-primary"><i class="fas fa-edit"></i></a>
                                        <form method="POST" action="faculty.php" onsubmit="return confirm('Delete this faculty member?');" style="display:inline;">
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
