<?php
require_once __DIR__ . '/includes/header.php';

$college = college_profile();
$owner = owner_profile_data();
$values = about_values_data();
$timeline = about_timeline_data();

page_breadcrumb('About Us');
?>

<section style="padding: 5rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <img src="./assets/images/profile.jpg" alt="Safi College of Medical Sciences campus" class="about-image">
            </div>
            <div class="col-lg-6">
                <span class="section-kicker">Who We Are</span>
                <h2 style="font-size: clamp(2rem, 4vw, 2.6rem); margin-bottom: 1rem;">Safi College of Medical Sciences</h2>
                <p style="color: var(--text-soft); margin-bottom: 1rem;">
                    Safi College of Medical Sciences (SCMS) is a premier institution of higher learning located in Mandani, Charsadda, Khyber Pakhtunkhwa. Established with a vision to address the growing need for qualified healthcare professionals in Pakistan, SCMS currently offers BS Generic, BS Nursing (Post RN), LHV, and CNA programs.
                </p>
                <p style="color: var(--text-soft); margin-bottom: 1rem;">
                    Our college is proudly affiliated with Khyber Medical University (KMU) Peshawar, one of the most prestigious medical universities in Pakistan. All our programs are recognized by the Pakistan Nursing Council (PNC) and the Higher Education Commission (HEC), ensuring that our graduates receive degrees that are valued and accepted nationwide.
                </p>
                <p style="color: var(--text-soft); margin-bottom: 1.5rem;">
                    At SCMS, we believe in a holistic approach to education that combines rigorous academic training with extensive practical experience. Our modern campus features advanced laboratories, simulation centers, and a comprehensive library, while our partnerships with leading hospitals provide students with invaluable clinical exposure.
                </p>
                <div class="check-grid">
                    <div><i class="fas fa-check-circle"></i><span>KMU Affiliated</span></div>
                    <div><i class="fas fa-check-circle"></i><span>PNC Recognized</span></div>
                    <div><i class="fas fa-check-circle"></i><span>HEC Approved</span></div>
                    <div><i class="fas fa-check-circle"></i><span>Modern Campus</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="owner-section">
    <div class="container">
        <div class="owner-card">
            <div class="row g-0 align-items-center">
                <div class="col-lg-5">
                    <div style="position:relative; width:100%; height:430px; background:linear-gradient(135deg, var(--primary-color), var(--primary-dark)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:5rem;">
                        <i class="fas fa-user-md" style="position:absolute; z-index:0;"></i>
                        <img src="./assets/images/<?php echo htmlspecialchars($owner['image']); ?>" alt="<?php echo htmlspecialchars($owner['name']); ?>" class="owner-photo" style="position:absolute; inset:0; z-index:1; width:100%; height:100%; object-fit:cover; object-position:center top;">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="owner-content">
                        <span class="section-kicker">College Leadership</span>
                        <h2><?php echo htmlspecialchars($owner['name']); ?></h2>
                        <p class="owner-role"><?php echo htmlspecialchars($owner['designation']); ?></p>
                        <div class="owner-meta"><i class="fas fa-user-md"></i><?php echo htmlspecialchars($owner['qualification']); ?></div>
                        <p style="color: var(--text-soft); margin-bottom: 1rem;">
                            <?php echo htmlspecialchars($owner['specialty']); ?> leading Safi College of Medical Sciences with a commitment to quality healthcare education, clinical excellence, and service to the community.
                        </p>
                        <p style="color: var(--text-soft); margin: 0;">
                            Under his leadership, SCMS aims to prepare skilled, compassionate healthcare professionals for Khyber Pakhtunkhwa and beyond.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; background: #ffffff;">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Mission & Vision</h2>
            <p>Guiding principles that drive our commitment to excellence in healthcare education.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="mv-card">
                    <div class="mv-icon"><i class="fas fa-bullseye"></i></div>
                    <h3>Our Mission</h3>
                    <p>To provide quality education in nursing and allied health sciences that empowers students with the knowledge, skills, and values essential for delivering compassionate, evidence-based healthcare. We are committed to producing competent healthcare professionals who can meet the challenges of modern medicine and contribute positively to the health and well-being of individuals and communities across Pakistan.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mv-card">
                    <div class="mv-icon"><i class="fas fa-eye"></i></div>
                    <h3>Our Vision</h3>
                    <p>To be recognized as a center of excellence in healthcare education in Khyber Pakhtunkhwa and beyond, known for producing compassionate, competent, and innovative healthcare professionals. We envision SCMS as a leading institution that transforms aspiring students into skilled practitioners who excel as caregivers, leaders, researchers, and advocates for health in their communities and the nation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Our Core Values</h2>
            <p>The principles that define our culture and guide every aspect of our institution.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($values as $value): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="value-item">
                        <div class="value-icon"><i class="fas fa-star"></i></div>
                        <div>
                            <h4><?php echo htmlspecialchars($value['title']); ?></h4>
                            <p><?php echo htmlspecialchars($value['description']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="timeline-section">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Our Journey</h2>
            <p>Key milestones in the development of Safi College of Medical Sciences.</p>
        </div>
        <div class="timeline">
            <?php foreach ($timeline as $item): ?>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h4><?php echo htmlspecialchars($item['year']); ?> - <?php echo htmlspecialchars($item['title']); ?></h4>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="owner-section">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">Technology Team</span>
            <h2>IT Department</h2>
            <p>Building and maintaining the digital infrastructure of Safi College of Medical Sciences.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="faculty-card">
                    <div class="faculty-img" style="height:300px; position:relative;">
                        <div style="position:absolute; inset:0; background:linear-gradient(135deg, var(--primary-color), var(--primary-dark)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:5rem;">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <img src="./assets/images/qaiser.jpg" alt="Qaiser Muhammad Abdur Rehman" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:1;">
                    </div>
                    <div class="faculty-body">
                        <h4>Qaiser Muhammad Abdur Rehman</h4>
                        <p class="faculty-designation" style="color:var(--primary-color); font-weight:600; font-size:0.9rem; margin-bottom:10px;">Web Developer & IT Lead</p>
                        <p style="color:#666; font-size:0.85rem; line-height:1.6;">Designed and developed the SCMS website. Responsible for digital infrastructure, web presence, and technical operations.</p>
                        <div class="footer-social justify-content-center" style="margin-top:1rem;">
                            <a href="https://www.facebook.com/qaisermuhammad" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="mailto:qaisermuhammad@hotmail.com" aria-label="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section-inner">
    <div class="container">
        <h2>Be Part of Our Journey</h2>
        <p>Join hundreds of students who have chosen SCMS as their pathway to a successful healthcare career.</p>
        <a href="admissions.php" class="btn btn-primary-custom">Apply Now</a>
    </div>
</section>

<section style="padding: 2rem 0 4rem; background: #ffffff;">
    <div class="container">
        <div class="dev-credits">
            <p style="margin: 0;">
                Website developed by
                <strong>Qaiser Muhammad Abdur Rehman</strong>
                <span class="mx-2">|</span>
                <a href="mailto:qaisermuhammad@hotmail.com">qaisermuhammad@hotmail.com</a>
                <span class="mx-2">|</span>
                <a href="https://www.facebook.com/qaisermuhammad" target="_blank" rel="noopener">Facebook</a>
            </p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
