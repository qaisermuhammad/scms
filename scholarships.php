<?php
require_once __DIR__ . '/includes/header.php';

$types = scholarship_types_data();
$criteria = scholarship_eligibility_data();

page_breadcrumb('Scholarships', [], [
    'kicker' => 'Financial Support',
    'heading' => 'Scholarships at SCMS',
    'description' => 'SCMS believes that financial constraints should never be a barrier to quality education. We offer various scholarships to support deserving and talented students in their academic journey.',
]);
?>

<section class="scholarships-page-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="scholarship-card">
                    <div class="scholarship-icon"><i class="fas fa-trophy"></i></div>
                    <h4>Merit-Based Scholarship</h4>
                    <p>Awarded to students with outstanding academic performance in FSc or previous degree programs.</p>
                    <span class="scholarship-amount">Up to 100% Fee Waiver</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="scholarship-card">
                    <div class="scholarship-icon"><i class="fas fa-hand-holding-heart"></i></div>
                    <h4>Need-Based Scholarship</h4>
                    <p>For students from underprivileged backgrounds who demonstrate financial need.</p>
                    <span class="scholarship-amount">Up to 75% Fee Waiver</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="scholarship-card">
                    <div class="scholarship-icon"><i class="fas fa-star"></i></div>
                    <h4>SCMS Excellence Award</h4>
                    <p>For students who achieve top positions in their class during ongoing semesters.</p>
                    <span class="scholarship-amount">50% Fee Waiver</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="scholarship-card">
                    <div class="scholarship-icon"><i class="fas fa-female"></i></div>
                    <h4>Women in Healthcare</h4>
                    <p>Special scholarship for female students pursuing careers in healthcare sciences.</p>
                    <span class="scholarship-amount">25% Fee Waiver</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="scholarship-card">
                    <div class="scholarship-icon"><i class="fas fa-running"></i></div>
                    <h4>Sports Scholarship</h4>
                    <p>For students who have represented at district, provincial, or national level in sports.</p>
                    <span class="scholarship-amount">25% Fee Waiver</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="scholarship-card">
                    <div class="scholarship-icon"><i class="fas fa-users"></i></div>
                    <h4>Sibling Discount</h4>
                    <p>Special fee concession for siblings studying simultaneously at SCMS.</p>
                    <span class="scholarship-amount">15% Fee Waiver</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Eligibility Criteria</h2>
            <p>Requirements to apply for scholarships at SCMS.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="criteria-item">
                    <div class="criteria-icon"><i class="fas fa-check"></i></div>
                    <div>
                        <h4>Academic Performance</h4>
                        <p>Minimum 70% marks in FSc Pre-Medical or equivalent for merit-based scholarships.</p>
                    </div>
                </div>
                <div class="criteria-item">
                    <div class="criteria-icon"><i class="fas fa-check"></i></div>
                    <div>
                        <h4>Financial Need Documentation</h4>
                        <p>Income certificate or affidavit for need-based scholarships.</p>
                    </div>
                </div>
                <div class="criteria-item">
                    <div class="criteria-icon"><i class="fas fa-check"></i></div>
                    <div>
                        <h4>Good Conduct</h4>
                        <p>Must maintain good disciplinary record and attendance (minimum 85%).</p>
                    </div>
                </div>
                <div class="criteria-item">
                    <div class="criteria-icon"><i class="fas fa-check"></i></div>
                    <div>
                        <h4>Continuous Performance</h4>
                        <p>For ongoing scholarships, students must maintain a minimum CGPA of 3.0.</p>
                    </div>
                </div>
                <div class="criteria-item">
                    <div class="criteria-icon"><i class="fas fa-check"></i></div>
                    <div>
                        <h4>No Other Scholarship</h4>
                        <p>Students cannot avail multiple SCMS scholarships simultaneously.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section-inner">
    <div class="container">
        <h2>Apply for Scholarship</h2>
        <p>Submit your scholarship application along with your admission form.</p>
        <a href="admissions.php" class="btn btn-primary-custom">Learn About Admissions</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
