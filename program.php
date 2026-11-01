<?php
require_once __DIR__ . '/includes/functions.php';

$program = program_by_id($_GET['id'] ?? '');
if (!$program) {
    header('Location: programs.php');
    exit;
}

require_once __DIR__ . '/includes/header.php';

page_breadcrumb($program['title'], [$program['title'] => '']);
?>

<section class="program-detail-hero">
    <div class="container">
        <div style="width:100px;height:100px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2.5rem;color:#ffffff;">
            <i class="fas <?php echo htmlspecialchars($program['icon']); ?>"></i>
        </div>
        <h1><?php echo htmlspecialchars($program['title']); ?></h1>
        <p><?php echo htmlspecialchars($program['description']); ?></p>
        <div class="d-flex justify-content-center gap-4 mt-4 flex-wrap" style="color: rgba(255,255,255,0.9);">
            <span><i class="far fa-clock me-2"></i><?php echo htmlspecialchars($program['duration']); ?></span>
            <span><i class="fas fa-users me-2"></i><?php echo htmlspecialchars((string) $program['seats']); ?> Seats</span>
            <span><i class="fas fa-university me-2"></i><?php echo htmlspecialchars($program['affiliation']); ?></span>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; background: #ffffff;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1rem;">Program Overview</h3>
                    <p style="color: var(--text-soft); line-height: 1.8; margin-bottom: 1rem;">
                        The <?php echo htmlspecialchars($program['title']); ?> program at Safi College of Medical Sciences is designed to provide students with comprehensive theoretical knowledge and practical skills required for a successful career in the healthcare sector.
                    </p>
                    <p style="color: var(--text-soft); line-height: 1.8; margin-bottom: 1.5rem;">
                        Students will undergo rigorous academic training combined with hands-on clinical experience at our affiliated hospitals and medical centers. The curriculum is regularly updated to meet international standards and industry requirements.
                    </p>
                </div>

                <div class="info-card-block">
                    <h4><i class="fas fa-clipboard-list me-2"></i>Eligibility Criteria</h4>
                    <ul>
                        <li><i class="fas fa-check"></i><?php echo htmlspecialchars($program['eligibility']); ?></li>
                        <li><i class="fas fa-check"></i>Valid CNIC/B-Form</li>
                        <li><i class="fas fa-check"></i>Domicile of Khyber Pakhtunkhwa (preferred)</li>
                        <li><i class="fas fa-check"></i>Entry test as per KMU policy</li>
                        <li><i class="fas fa-check"></i>Medical fitness certificate</li>
                    </ul>
                </div>

                <div class="info-card-block">
                    <h4><i class="fas fa-book me-2"></i>Program Structure</h4>
                    <ul>
                        <li><i class="fas fa-check"></i>Duration: <?php echo htmlspecialchars($program['duration']); ?></li>
                        <li><i class="fas fa-check"></i>Total Seats: <?php echo htmlspecialchars((string) $program['seats']); ?></li>
                        <li><i class="fas fa-check"></i>Semester System</li>
                        <li><i class="fas fa-check"></i>Theory + Practical + Clinical Training</li>
                        <li><i class="fas fa-check"></i>Final Year Project/Dissertation</li>
                        <li><i class="fas fa-check"></i>Internship at Affiliated Hospitals</li>
                    </ul>
                </div>

                <div style="margin-top: 2rem;">
                    <h3 style="margin-bottom: 1.25rem;">Fee Structure</h3>
                    <table class="syllabus-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Admission Fee (One-time)</td><td>TBA</td></tr>
                            <tr><td>Tuition Fee</td><td>TBA</td></tr>
                            <tr><td>Examination Fee</td><td>TBA</td></tr>
                            <tr><td>Library Fee</td><td>TBA</td></tr>
                            <tr><td>Lab Fee</td><td>TBA</td></tr>
                            <tr><td>Security Deposit (Refundable)</td><td>TBA</td></tr>
                        </tbody>
                    </table>
                    <p style="color: #888; font-size: 0.85rem; margin-top: 1rem;"><i class="fas fa-info-circle me-1"></i> Fees are subject to revision. Scholarships available for deserving students.</p>
                </div>
            </div>

            <div class="col-lg-4">
                <div style="position: sticky; top: 100px;">
                    <div class="info-card-block info-card-dark">
                    <h4><i class="fas fa-file-alt me-2"></i>Required Documents</h4>
                    <ul>
                        <li><i class="fas fa-check"></i>Matric Certificate</li>
                        <li><i class="fas fa-check"></i>Relevant prerequisite certificate</li>
                        <li><i class="fas fa-check"></i>CNIC/B-Form</li>
                        <li><i class="fas fa-check"></i>Domicile Certificate</li>
                        <li><i class="fas fa-check"></i>4 Passport Size Photos</li>
                        <li><i class="fas fa-check"></i>Character Certificate</li>
                    </ul>
                </div>

                <div class="info-card-block">
                    <h4><i class="fas fa-calendar-alt me-2"></i>Important Dates</h4>
                    <ul>
                        <li><i class="fas fa-calendar"></i>Admissions Open: May 1, 2026</li>
                        <li><i class="fas fa-calendar"></i>Last Date to Apply: To be announced</li>
                        <li><i class="fas fa-calendar"></i>Entry Test: To be announced</li>
                        <li><i class="fas fa-calendar"></i>Result Announcement: To be announced</li>
                        <li><i class="fas fa-calendar"></i>Classes Begin: To be announced</li>
                    </ul>
                </div>
            </div>
            </div>
        </div>

        <div style="margin-top: 3rem;">
            <h3 style="margin-bottom: 2rem; text-align: center;">Career Opportunities</h3>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="career-card">
                        <i class="fas fa-hospital"></i>
                        <h5>Hospitals & Clinics</h5>
                        <p>Work in public and private healthcare facilities</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="career-card">
                        <i class="fas fa-heart"></i>
                        <h5>Community Care</h5>
                        <p>Support patients in community and outreach settings</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="career-card">
                        <i class="fas fa-university"></i>
                        <h5>Academia</h5>
                        <p>Teach and mentor future healthcare professionals</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="career-card">
                        <i class="fas fa-globe"></i>
                        <h5>International</h5>
                        <p>Opportunities abroad with recognized skills</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section-inner">
    <div class="container">
        <h2>Ready to Start Your Journey?</h2>
        <p>Apply for the <?php echo htmlspecialchars($program['title']); ?> program and take the first step towards a rewarding healthcare career.</p>
        <div class="hero-actions justify-content-center">
            <a href="admissions.php" class="btn btn-primary-custom">Apply Now</a>
            <a href="contact.php" class="btn btn-outline-light-custom">Contact for More Info</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
