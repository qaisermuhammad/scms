<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();

$programs = admin_load_programs();
usort($programs, function ($a, $b) {
    return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
});
$edit_item = null;

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $delete_id = $_POST['delete_id'];
        $programs = array_values(array_filter($programs, fn($p) => ($p['id'] ?? '') !== $delete_id));
        admin_save_programs($programs);
        admin_flash('success', 'Program deleted successfully.');
    }
    header('Location: programs.php');
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_program'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $title = trim($_POST['title'] ?? '');
        $id = trim($_POST['id'] ?? '');
        $duration = trim($_POST['duration'] ?? '');
        $seats = (int) ($_POST['seats'] ?? 0);
        $eligibility = trim($_POST['eligibility'] ?? '');
        $affiliation = trim($_POST['affiliation'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-user-nurse');
        $edit_id = trim($_POST['edit_id'] ?? '');

        if ($title === '' || $id === '' || $duration === '') {
            admin_flash('error', 'Title, ID, and duration are required.');
        } else {
            $existing_created_at = time();
            foreach ($programs as $existing_program) {
                if (($existing_program['id'] ?? '') === ($edit_id ?: $id)) {
                    $existing_created_at = (int) ($existing_program['created_at'] ?? time());
                    break;
                }
            }
            $new_item = [
                'id' => $edit_id ?: $id,
                'title' => $title,
                'duration' => $duration,
                'seats' => $seats,
                'eligibility' => $eligibility,
                'affiliation' => $affiliation,
                'description' => $description,
                'icon' => $icon,
                'created_at' => $existing_created_at,
                'updated_at' => time(),
            ];

            if ($edit_id) {
                $found = false;
                foreach ($programs as $i => $item) {
                    if (($item['id'] ?? '') === $edit_id) {
                        $programs[$i] = $new_item;
                        $found = true;
                        break;
                    }
                }
                if (!$found) $programs[] = $new_item;
                admin_flash('success', 'Program updated successfully.');
            } else {
                // Check ID uniqueness
                $exists = false;
                foreach ($programs as $p) {
                    if (($p['id'] ?? '') === $id) { $exists = true; break; }
                }
                if ($exists) {
                    admin_flash('error', 'A program with this ID already exists.');
                } else {
                    $programs[] = $new_item;
                    admin_flash('success', 'Program added successfully.');
                }
            }
            admin_save_programs($programs);
        }
    }
    header('Location: programs.php');
    exit;
}

// Load edit item
if (isset($_GET['edit'])) {
    foreach ($programs as $item) {
        if (($item['id'] ?? '') === $_GET['edit']) {
            $edit_item = $item;
            break;
        }
    }
}

$icon_options = [
    'fa-user-nurse' => 'Nursing',
    'fa-user-doctor' => 'Doctor',
    'fa-hand-holding-medical' => 'Medical Assistant',
    'fa-heartbeat' => 'Heart/Health',
    'fa-stethoscope' => 'Stethoscope',
    'fa-hospital' => 'Hospital',
    'fa-microscope' => 'Lab',
    'fa-pills' => 'Pharmacy',
    'fa-ambulance' => 'Ambulance',
    'fa-brain' => 'Brain/Mental Health',
];

admin_render_header('Programs');
admin_render_flash();
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card">
            <h5><?php echo $edit_item ? 'Edit Program' : 'Add Program'; ?></h5>
            <form method="POST" action="programs.php" class="mt-3">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($edit_item['id'] ?? ''); ?>">
                <div class="mb-3">
                    <label class="admin-form-label">Program ID (unique slug) *</label>
                    <input type="text" name="id" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['id'] ?? ''); ?>" <?php echo $edit_item ? 'readonly' : 'required'; ?> placeholder="e.g. bsn-generic">
                    <?php if ($edit_item): ?><small class="text-muted">ID cannot be changed after creation.</small><?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Title *</label>
                    <input type="text" name="title" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['title'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Duration *</label>
                    <input type="text" name="duration" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['duration'] ?? ''); ?>" required placeholder="e.g. 4 Years">
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Seats</label>
                    <input type="number" name="seats" class="admin-form-control" value="<?php echo htmlspecialchars((string)($edit_item['seats'] ?? 0)); ?>">
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Eligibility</label>
                    <input type="text" name="eligibility" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['eligibility'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="admin-form-label">Affiliation</label>
                    <input type="text" name="affiliation" class="admin-form-control" value="<?php echo htmlspecialchars($edit_item['affiliation'] ?? ''); ?>">
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
                    <label class="admin-form-label">Description</label>
                    <textarea name="description" class="admin-form-control"><?php echo htmlspecialchars($edit_item['description'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="save_program" class="admin-btn-sm admin-btn-primary">
                    <i class="fas <?php echo $edit_item ? 'fa-save' : 'fa-plus'; ?> me-1"></i><?php echo $edit_item ? 'Update Program' : 'Add Program'; ?>
                </button>
                <?php if ($edit_item): ?>
                    <a href="programs.php" class="admin-btn-sm admin-btn-danger ms-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 style="margin:0;">All Programs</h5>
                <span class="admin-badge admin-badge-success"><?php echo count($programs); ?> Total</span>
            </div>
            <?php if (empty($programs)): ?>
                <p class="text-muted">No programs yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>ID</th><th>Title</th><th>Duration</th><th>Seats</th><th style="width:120px;">Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($programs as $item): ?>
                                <tr>
                                    <td><code><?php echo htmlspecialchars($item['id'] ?? ''); ?></code></td>
                                    <td><?php echo htmlspecialchars($item['title'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($item['duration'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars((string)($item['seats'] ?? 0)); ?></td>
                                    <td>
                                        <a href="programs.php?edit=<?php echo htmlspecialchars($item['id'] ?? ''); ?>" class="admin-btn-sm admin-btn-primary"><i class="fas fa-edit"></i></a>
                                        <form method="POST" action="programs.php" onsubmit="return confirm('Delete this program?');" style="display:inline;">
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
