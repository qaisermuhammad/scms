<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = is_string($_POST['current_password'] ?? null) ? $_POST['current_password'] : '';
    $new = is_string($_POST['new_password'] ?? null) ? $_POST['new_password'] : '';
    $confirm = is_string($_POST['confirm_password'] ?? null) ? $_POST['confirm_password'] : '';
    if (!admin_verify_csrf(is_string($_POST['csrf'] ?? null) ? $_POST['csrf'] : '')) $error = 'Invalid request. Please try again.';
    elseif (!password_verify($current, admin_password_hash())) $error = 'The current password is incorrect.';
    elseif (strlen($new) < 12 || strlen($new) > 72) $error = 'Choose a password between 12 and 72 bytes.';
    elseif ($new !== $confirm) $error = 'The new passwords do not match.';
    elseif ($new === $current) $error = 'Choose a password different from the current password.';
    elseif (getenv('SCMS_ADMIN_PASSWORD_HASH')) $error = 'This server manages the password through an environment variable. Contact your hosting administrator to change it.';
    else {
        global $credentialFile;
        $directory = dirname($credentialFile);
        $temporary = @tempnam($directory, '.scms-credential-');
        if ($temporary === false) $error = 'The credential folder is not writable. Ask your hosting provider to enable writing to the protected credential file folder.';
        else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $content = "<?php\nreturn " . var_export(['username' => admin_username(), 'password_hash' => $hash], true) . ";\n";
            $written = @file_put_contents($temporary, $content, LOCK_EX);
            if ($written !== strlen($content) || !@chmod($temporary, 0600) || !@rename($temporary, $credentialFile)) {
                @unlink($temporary);
                $error = 'Could not save the password. Ask your hosting provider to check permissions on the protected credential folder.';
            } else {
                if (function_exists('opcache_invalidate')) @opcache_invalidate($credentialFile, true);
                session_regenerate_id(true);
                unset($_SESSION['admin_csrf']);
                admin_flash('success', 'Password changed successfully. Use your new password next time you sign in.');
                header('Location: password.php', true, 303);
                exit;
            }
        }
    }
}
admin_render_header('Change Password');
admin_render_flash();
?>
<div class="admin-card" style="max-width:600px"><h5>Change your password</h5><p>Enter your current password and choose a new one with at least 12 characters.</p>
<?php if ($error): ?><div class="admin-alert admin-alert-error" role="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
<div class="mb-3"><label class="admin-form-label" for="current_password">Current password</label><input class="admin-form-control" id="current_password" type="password" name="current_password" autocomplete="current-password" required></div>
<div class="mb-3"><label class="admin-form-label" for="new_password">New password</label><input class="admin-form-control" id="new_password" type="password" name="new_password" autocomplete="new-password" minlength="12" maxlength="72" required></div>
<div class="mb-3"><label class="admin-form-label" for="confirm_password">Confirm new password</label><input class="admin-form-control" id="confirm_password" type="password" name="confirm_password" autocomplete="new-password" minlength="12" maxlength="72" required></div>
<button class="admin-btn-sm admin-btn-primary" type="submit">Change password</button></form></div>
<?php admin_render_footer(); ?>
