<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();
require_once __DIR__ . '/../includes/applications_db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf(is_string($_POST['csrf'] ?? null) ? $_POST['csrf'] : '')) {
        admin_flash('error', 'Invalid request. No application was deleted.');
    } elseif (isset($_POST['delete_id']) && ctype_digit((string) $_POST['delete_id'])) {
        try {
            $db = application_database();
            $delete = $db->prepare('DELETE FROM applications WHERE id = :id AND reference = :reference');
            $delete->execute(['id' => (int) $_POST['delete_id'], 'reference' => (string) ($_POST['reference'] ?? '')]);
            admin_flash($delete->rowCount() ? 'success' : 'error', $delete->rowCount() ? 'Application deleted.' : 'Application not found.');
        } catch (Throwable $e) {
            error_log('Application deletion failed: ' . $e->getMessage());
            admin_flash('error', 'Could not delete the application.');
        }
    }
    header('Location: applications.php', true, 303);
    exit;
}
$applications = [];
$error = '';
try {
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]) ?: 1;
    $db = application_database();
    $total = (int) $db->query('SELECT COUNT(*) FROM applications')->fetchColumn();
    $pages = max(1, (int) ceil($total / 50));
    $page = min($page, $pages);
    $stmt = $db->prepare('SELECT * FROM applications ORDER BY id DESC LIMIT 50 OFFSET :offset');
    $stmt->bindValue(':offset', ($page - 1) * 50, PDO::PARAM_INT);
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    error_log('Application listing failed: ' . $e->getMessage());
    $error = 'Applications could not be loaded. Please check the server configuration.';
}
admin_render_header('Applications');
?>
<div class="admin-card"><div class="d-flex justify-content-between align-items-center flex-wrap gap-2"><h5>Online applications</h5><a class="admin-btn-sm admin-btn-primary" href="application-edit.php">Add application</a></div><?php admin_render_flash(); ?>
<?php if ($error): ?><div class="admin-alert admin-alert-error" role="alert"><?php echo htmlspecialchars($error); ?></div>
<?php elseif (!$applications): ?><p>No applications have been submitted yet.</p>
<?php else: ?><p><?php echo $total; ?> application(s). Times are UTC.</p><div class="table-responsive"><table class="admin-table"><thead><tr><th>Reference / date</th><th>Applicant</th><th>Program</th><th>Education / notes</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($applications as $application): ?><tr><td><?php echo htmlspecialchars($application['reference']); ?><br><?php echo htmlspecialchars($application['submitted_at']); ?></td><td><strong><?php echo htmlspecialchars($application['full_name']); ?></strong><br><?php echo htmlspecialchars($application['email']); ?><br><?php echo htmlspecialchars($application['phone']); ?></td><td><?php echo htmlspecialchars($application['program_title']); ?></td><td><?php $details = json_decode($application['additional_info'] ?? '{}', true) ?: []; $labels = ['father_name' => 'Parent or guardian', 'date_of_birth' => 'Date of birth', 'address' => 'Address', 'city' => 'City or district', 'qualification' => 'Highest qualification', 'institution' => 'School or college', 'board' => 'Board or awarding body', 'graduation_year' => 'Year completed', 'marks' => 'Marks or grade', 'marks_obtained' => 'Marks obtained', 'total_marks' => 'Total marks', 'percentage' => 'Percentage', 'grade' => 'Estimated grade', 'guardian_phone' => 'Guardian phone', 'domicile' => 'Domicile district']; foreach ($labels as $key => $label): if (!empty($details[$key])): ?><strong><?php echo htmlspecialchars($label); ?>:</strong> <?php echo htmlspecialchars($details[$key]); ?><br><?php endif; endforeach; ?><br><?php echo nl2br(htmlspecialchars($application['education'])); ?><?php if ($application['notes'] !== ''): ?><br><br><?php echo nl2br(htmlspecialchars($application['notes'])); ?><?php endif; ?></td><td><a class="admin-btn-sm admin-btn-primary" href="application-edit.php?id=<?php echo (int) $application['id']; ?>">Edit</a><form method="post" action="applications.php" class="mt-2" onsubmit="return confirm('Delete this application permanently?');"><input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>"><input type="hidden" name="delete_id" value="<?php echo (int) $application['id']; ?>"><input type="hidden" name="reference" value="<?php echo htmlspecialchars($application['reference']); ?>"><button type="submit" class="admin-btn-sm admin-btn-danger">Delete</button></form></td></tr><?php endforeach; ?>
</tbody></table></div><div class="mt-3">Page <?php echo $page; ?> of <?php echo $pages; ?>. <?php if ($page > 1): ?><a href="?page=<?php echo $page - 1; ?>">Previous</a> <?php endif; ?><?php if ($page < $pages): ?><a href="?page=<?php echo $page + 1; ?>">Next</a><?php endif; ?></div><?php endif; ?></div>
<?php admin_render_footer(); ?>
