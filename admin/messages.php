<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();

$messages = admin_load_messages();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $delete_id = $_POST['delete_id'];
        $messages = array_values(array_filter($messages, fn($m) => ($m['id'] ?? '') !== $delete_id));
        admin_save_messages($messages);
        admin_flash('success', 'Message deleted successfully.');
    }
    header('Location: messages.php');
    exit;
}

admin_render_header('Messages');
admin_render_flash();
?>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 style="margin:0;">All Contact Messages</h5>
        <span class="admin-badge admin-badge-success"><?php echo count($messages); ?> Total</span>
    </div>
    <?php if (empty($messages)): ?>
        <p class="text-muted">No messages have been submitted yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th style="width:80px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($msg['timestamp'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($msg['name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($msg['email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($msg['phone'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($msg['subject'] ?? ''); ?></td>
                            <td style="min-width:250px;max-width:450px;overflow-wrap:anywhere;white-space:pre-wrap;"><?php echo htmlspecialchars($msg['message'] ?? ''); ?></td>
                            <td>
                                <form method="POST" action="messages.php" onsubmit="return confirm('Delete this message?');" style="display:inline;">
                                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($msg['id'] ?? ''); ?>">
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

<?php admin_render_footer(); ?>
