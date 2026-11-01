<?php
require_once __DIR__ . '/includes/header.php';

$groups = download_groups_data();

page_breadcrumb('Downloads', [], [
    'kicker' => 'Resources',
    'heading' => 'Download Center',
    'description' => 'Download important documents, forms, and resources related to admissions, academics, and college policies.',
]);
?>

<section style="padding: 5rem 0; background: #ffffff;">
    <div class="container">
        <div class="row">
            <?php foreach ($groups as $group): ?>
                <div class="col-lg-6 mb-5">
                    <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-file-alt me-2" style="color:var(--primary-color);"></i><?php echo htmlspecialchars($group['title']); ?></h3>
                    <?php foreach ($group['items'] as $item): ?>
                        <div class="download-card">
                            <div class="download-icon"><i class="fas fa-file-pdf"></i></div>
                            <div class="download-info">
                                <h5><?php echo htmlspecialchars($item['label']); ?></h5>
                                <p><?php echo htmlspecialchars($item['meta']); ?></p>
                            </div>
                            <?php if ($item['available'] && !empty($item['href'])): ?>
                                <a href="<?php echo htmlspecialchars($item['href']); ?>" class="download-btn" download><i class="fas fa-download me-1"></i>Download</a>
                            <?php else: ?>
                                <span class="download-btn disabled"><i class="fas fa-clock me-1"></i>Coming Soon</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section-inner">
    <div class="container">
        <h2>Need Help?</h2>
        <p>If you cannot find the document you need, please contact our admissions office.</p>
        <a href="contact.php" class="btn btn-primary-custom">Contact Us</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
