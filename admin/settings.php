<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();

$settings = admin_load_settings();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    if (!admin_verify_csrf($_POST['csrf'] ?? '')) {
        admin_flash('error', 'Invalid request.');
    } else {
        $settings = [
            'name' => trim($_POST['name'] ?? ''),
            'short_name' => trim($_POST['short_name'] ?? ''),
            'tagline' => trim($_POST['tagline'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'mobile' => trim($_POST['mobile'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'contact_email' => trim($_POST['contact_email'] ?? ''),
            'admissions_email' => trim($_POST['admissions_email'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'facebook_url' => trim($_POST['facebook_url'] ?? ''),
            'established' => trim($_POST['established'] ?? ''),
            'affiliation' => trim($_POST['affiliation'] ?? ''),
            'recognition' => trim($_POST['recognition'] ?? ''),
            'maps_url' => trim($_POST['maps_url'] ?? ''),
            'maps_embed_url' => trim($_POST['maps_embed_url'] ?? ''),
        ];
        admin_save_settings($settings);
        admin_flash('success', 'Settings saved successfully.');
    }
    header('Location: settings.php');
    exit;
}

admin_render_header('Settings');
admin_render_flash();
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h5>College Profile Settings</h5>
            <form method="POST" action="settings.php" class="mt-3">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="admin-form-label">College Name</label>
                        <input type="text" name="name" class="admin-form-control" value="<?php echo htmlspecialchars($settings['name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Short Name</label>
                        <input type="text" name="short_name" class="admin-form-control" value="<?php echo htmlspecialchars($settings['short_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Tagline</label>
                        <input type="text" name="tagline" class="admin-form-control" value="<?php echo htmlspecialchars($settings['tagline'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Established Year</label>
                        <input type="text" name="established" class="admin-form-control" value="<?php echo htmlspecialchars($settings['established'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Phone</label>
                        <input type="text" name="phone" class="admin-form-control" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Mobile</label>
                        <input type="text" name="mobile" class="admin-form-control" value="<?php echo htmlspecialchars($settings['mobile'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Email</label>
                        <input type="email" name="email" class="admin-form-control" value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="admin-form-control" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Admissions Email</label>
                        <input type="email" name="admissions_email" class="admin-form-control" value="<?php echo htmlspecialchars($settings['admissions_email'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Website</label>
                        <input type="text" name="website" class="admin-form-control" value="<?php echo htmlspecialchars($settings['website'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Facebook URL</label>
                        <input type="text" name="facebook_url" class="admin-form-control" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Affiliation</label>
                        <input type="text" name="affiliation" class="admin-form-control" value="<?php echo htmlspecialchars($settings['affiliation'] ?? ''); ?>">
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Recognition</label>
                        <input type="text" name="recognition" class="admin-form-control" value="<?php echo htmlspecialchars($settings['recognition'] ?? ''); ?>">
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Location</label>
                        <input type="text" name="location" class="admin-form-control" value="<?php echo htmlspecialchars($settings['location'] ?? ''); ?>">
                    </div>
                    <div class="col-12">
                        <label class="admin-form-label">Address</label>
                        <input type="text" name="address" class="admin-form-control" value="<?php echo htmlspecialchars($settings['address'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Google Maps URL (Open Link)</label>
                        <input type="text" name="maps_url" class="admin-form-control" value="<?php echo htmlspecialchars($settings['maps_url'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="admin-form-label">Google Maps Embed URL (Iframe)</label>
                        <input type="text" name="maps_embed_url" class="admin-form-control" value="<?php echo htmlspecialchars($settings['maps_embed_url'] ?? ''); ?>">
                    </div>
                </div>
                <button type="submit" name="save_settings" class="admin-btn-sm admin-btn-primary mt-3">
                    <i class="fas fa-save me-1"></i>Save Settings
                </button>
            </form>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <h5>Info</h5>
            <p class="text-muted" style="font-size:0.9rem;">
                These settings are stored in <code>data/settings.json</code>. The main site reads from this file when it exists, otherwise falls back to default values defined in <code>includes/functions.php</code>.
            </p>
            <p class="text-muted" style="font-size:0.9rem;">
                To change the admin password, update the password hash in the protected credential file outside the web root or the server environment.
            </p>
        </div>
    </div>
</div>

<?php admin_render_footer(); ?>
