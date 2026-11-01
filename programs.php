<?php
require_once __DIR__ . '/includes/header.php';

$programs = programs_data();

page_breadcrumb('Programs', [], [
    'kicker' => 'Academic Pathways',
    'heading' => 'Our Programs',
    'description' => 'Explore healthcare education programs designed to build strong academic foundations, practical skills, and meaningful professional opportunities.',
]);
?>

<section style="padding: 1.75rem 0 3.5rem; background: #ffffff;">
    <div class="container">
        <?php foreach ($programs as $program): ?>
            <div class="program-list-card">
                <div class="program-list-icon">
                    <i class="fas <?php echo htmlspecialchars($program['icon']); ?>"></i>
                </div>
                <div class="program-list-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                        <h3><?php echo htmlspecialchars($program['title']); ?></h3>
                        <span class="program-list-badge"><?php echo htmlspecialchars($program['affiliation']); ?></span>
                    </div>
                    <p><?php echo htmlspecialchars($program['description']); ?></p>
                    <div class="program-list-meta">
                        <span><i class="far fa-clock"></i>Duration: <?php echo htmlspecialchars($program['duration']); ?></span>
                        <span><i class="fas fa-users"></i>Seats: <?php echo htmlspecialchars((string) $program['seats']); ?></span>
                        <span><i class="fas fa-graduation-cap"></i>Eligibility: <?php echo htmlspecialchars($program['eligibility']); ?></span>
                    </div>
                    <a href="program.php?id=<?php echo htmlspecialchars($program['id']); ?>" class="btn btn-outline-custom">View Program Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section style="padding: 5rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Program Highlights</h2>
            <p>What students can expect from SCMS programs.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="value-item">
                    <div class="value-icon"><i class="fas fa-book-reader"></i></div>
                    <div>
                        <h4>Structured Learning</h4>
                        <p>Programs combine classroom understanding, discipline, and professional development to help students grow with confidence.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="value-item">
                    <div class="value-icon"><i class="fas fa-hospital"></i></div>
                    <div>
                        <h4>Practical Preparation</h4>
                        <p>Students are prepared for real healthcare environments through skills-based learning and clinical orientation.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="value-item">
                    <div class="value-icon"><i class="fas fa-briefcase"></i></div>
                    <div>
                        <h4>Career Direction</h4>
                        <p>SCMS focuses on building academic foundations that support future service, training, and healthcare opportunities.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section-inner">
    <div class="container">
        <h2>Start Your Application Today</h2>
        <p>Admissions are now open for the current programs at SCMS. Secure your place in one of Pakistan's emerging healthcare education institutions.</p>
        <a href="admissions.php" class="btn btn-primary-custom">Apply Now</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
