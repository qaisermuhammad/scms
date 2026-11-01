<?php
require_once __DIR__ . '/includes/header.php';

$programs = programs_data();
$steps = admissions_steps_data();
$faqs = admissions_faq_data();

page_breadcrumb('Admissions', [], [
    'kicker' => 'Join SCMS',
    'heading' => 'Admissions 2026',
    'description' => 'Applications are now open for the current programs at SCMS. Follow the steps below to apply and begin your journey towards a rewarding career in healthcare.',
    'actions' => [
        ['label' => 'Download Prospectus', 'href' => 'download-prospectus.php', 'class' => 'btn-primary-custom'],
        ['label' => 'Apply Online', 'href' => 'apply.php', 'class' => 'btn-outline-light-custom'],
    ],
]);
?>

<section style="padding: 5rem 0; background: #ffffff;">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Admission Process</h2>
            <p>Follow these simple steps to secure your admission at SCMS.</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="admission-step">
                    <div class="step-number">1</div>
                    <div>
                        <h4>Online Application</h4>
                        <p>Complete the online application form or contact the admissions office for a physical form. The admissions office will advise you about documents and fees.</p>
                    </div>
                </div>
                <div class="admission-step">
                    <div class="step-number">2</div>
                    <div>
                        <h4>Document Verification</h4>
                        <p>Our admissions committee will verify all submitted documents including academic certificates, CNIC, domicile, and other required paperwork.</p>
                    </div>
                </div>
                <div class="admission-step">
                    <div class="step-number">3</div>
                    <div>
                        <h4>Entry Test</h4>
                        <p>Eligible candidates will be required to appear for an entry test conducted as per KMU policy. The test assesses knowledge in relevant science subjects.</p>
                    </div>
                </div>
                <div class="admission-step">
                    <div class="step-number">4</div>
                    <div>
                        <h4>Interview</h4>
                        <p>Shortlisted candidates will be called for a personal interview to assess their motivation, communication skills, and suitability for the healthcare profession.</p>
                    </div>
                </div>
                <div class="admission-step">
                    <div class="step-number">5</div>
                    <div>
                        <h4>Final Selection & Enrollment</h4>
                        <p>Final selection is based on merit (academic record + entry test + interview). Selected candidates must complete fee payment and enrollment formalities within the given deadline.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Programs Overview</h2>
            <p>Quick reference for all programs offered at SCMS.</p>
        </div>
        <div class="table-responsive">
            <table class="programs-table">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Duration</th>
                        <th>Seats</th>
                        <th>Eligibility</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($programs as $program): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($program['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($program['duration']); ?></td>
                            <td><?php echo htmlspecialchars((string) $program['seats']); ?></td>
                            <td><?php echo htmlspecialchars($program['eligibility']); ?></td>
                            <td><a href="program.php?id=<?php echo htmlspecialchars($program['id']); ?>">Details <i class="fas fa-arrow-right ms-1"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; background: #ffffff;">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Frequently Asked Questions</h2>
            <p>Find answers to common questions about admissions at SCMS.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <?php foreach ($faqs as $index => $faq): ?>
                        <div class="accordion-item-custom">
                            <button class="accordion-button-custom <?php echo $index === 0 ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $index; ?>" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                                <?php echo htmlspecialchars($faq['question']); ?>
                                <i class="fas fa-chevron-down accordion-icon"></i>
                            </button>
                            <div id="faq<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body-custom">
                                    <?php echo htmlspecialchars($faq['answer']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section-inner">
    <div class="container">
        <h2>Ready to Apply?</h2>
        <p>Don't miss the opportunity to join one of Pakistan's leading healthcare education institutions.</p>
        <a href="apply.php" class="btn btn-primary-custom">Apply Online</a>
        <a href="contact.php" class="btn btn-outline-light-custom">Contact Admissions Office</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
