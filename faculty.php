<?php
require_once __DIR__ . '/includes/header.php';

$college = college_profile();
$faculty = faculty_data();
$owner = owner_profile_data();

page_breadcrumb('Our Faculty', [], [
    'kicker' => 'Meet Our Team',
    'heading' => 'Experienced Faculty & Leadership',
    'description' => 'Our faculty consists of qualified healthcare professionals and educators dedicated to shaping the next generation of nursing and allied health professionals.',
]);
?>

<!-- Director Spotlight -->
<section style="padding: 5rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="owner-card" style="border-radius:18px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.12);">
                    <div style="position:relative; width:100%; height:400px; background:linear-gradient(135deg, var(--primary-color), var(--primary-dark)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:5rem;">
                        <i class="fas fa-user-md" style="position:absolute; z-index:0;"></i>
                        <img src="./assets/images/<?php echo htmlspecialchars($owner['image']); ?>" alt="<?php echo htmlspecialchars($owner['name']); ?>" style="position:absolute; inset:0; z-index:1; width:100%; height:100%; object-fit:cover; object-position:center top;">
                    </div>
                    <div style="padding:30px;background:var(--primary-color);color:#fff;text-align:center;">
                        <h3 style="margin:0;font-size:1.5rem;"><?php echo htmlspecialchars($owner['name']); ?></h3>
                        <p style="margin:8px 0 0;opacity:0.9;font-weight:600;"><?php echo htmlspecialchars($owner['designation']); ?></p>
                        <p style="margin:5px 0 0;opacity:0.8;font-size:0.85rem;"><i class="fas fa-user-md me-2"></i><?php echo htmlspecialchars($owner['specialty']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <span style="color:var(--primary-color);font-weight:600;text-transform:uppercase;letter-spacing:2px;font-size:0.85rem;">College Leadership</span>
                <h2 style="font-size:2.2rem;margin:15px 0 20px;"><?php echo htmlspecialchars($owner['name']); ?></h2>
                <p class="owner-role" style="color:var(--primary-color);font-weight:700;margin-bottom:14px;font-size:1.1rem;"><?php echo htmlspecialchars($owner['designation']); ?></p>
                <div style="display:inline-flex;align-items:center;gap:10px;background:var(--primary-color);color:#fff;padding:8px 16px;border-radius:50px;font-weight:600;margin-bottom:20px;font-size:0.9rem;">
                    <i class="fas fa-graduation-cap"></i><?php echo htmlspecialchars($owner['qualification']); ?>
                </div>
                <p style="color:#666;margin-bottom:18px;line-height:1.8;">
                    Dr. Siyyar Ahmad Safi is an ENT and Rhinoplasty Surgeon with extensive experience in medical education and clinical practice. He founded Safi College of Medical Sciences with the vision of bringing quality healthcare education to Charsadda and the surrounding Khyber Pakhtunkhwa region.
                </p>
                <p style="color:#666;margin-bottom:18px;line-height:1.8;">
                    Under his leadership, SCMS is committed to producing skilled, compassionate healthcare professionals who will serve communities across Pakistan. His dedication to both medical excellence and education drives the college's mission of accessible, quality healthcare training.
                </p>
                <div class="row mb-4">
                    <div class="col-6"><div class="d-flex align-items-center"><i class="fas fa-check-circle" style="color:var(--primary-color);font-size:1.2rem;margin-right:10px;"></i><span style="font-weight:600;">MBBS Graduate</span></div></div>
                    <div class="col-6"><div class="d-flex align-items-center"><i class="fas fa-check-circle" style="color:var(--primary-color);font-size:1.2rem;margin-right:10px;"></i><span style="font-weight:600;">FCPS (ENT) Certified</span></div></div>
                    <div class="col-6 mt-3"><div class="d-flex align-items-center"><i class="fas fa-check-circle" style="color:var(--primary-color);font-size:1.2rem;margin-right:10px;"></i><span style="font-weight:600;">MHPE Qualified</span></div></div>
                    <div class="col-6 mt-3"><div class="d-flex align-items-center"><i class="fas fa-check-circle" style="color:var(--primary-color);font-size:1.2rem;margin-right:10px;"></i><span style="font-weight:600;">15+ Years Experience</span></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Faculty Grid -->
<section class="faculty-section" style="padding: 5rem 0; background: #ffffff;">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Our Faculty Members</h2>
            <p>Dedicated educators and healthcare professionals guiding our students.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($faculty as $member): ?>
                <?php if ($member['name'] === $owner['name']) continue; // Skip director as already shown above ?>
                <div class="col-lg-4 col-md-6">
                    <div class="faculty-card" style="background:#fff;border-radius:var(--border-radius);overflow:hidden;box-shadow:0 5px 25px rgba(0,0,0,0.08);transition:all 0.3s;height:100%;text-align:center;">
                        <div class="faculty-img" style="height:280px;background:linear-gradient(135deg,var(--primary-color),#0d3d16);display:flex;align-items:center;justify-content:center;color:#fff;font-size:4rem;position:relative;overflow:hidden;">
                            <?php if (!empty($member['image'])): ?>
                                <img src="./assets/images/<?php echo htmlspecialchars($member['image']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" style="width:100%;height:100%;object-fit:cover;object-position:center top;">
                            <?php else: ?>
                                <i class="fas fa-user-md"></i>
                            <?php endif; ?>
                        </div>
                        <div class="faculty-body" style="padding:25px;">
                            <h4 style="font-size:1.2rem;margin-bottom:5px;color:var(--dark-color);"><?php echo htmlspecialchars($member['name']); ?></h4>
                            <p class="faculty-designation" style="color:var(--primary-color);font-weight:600;font-size:0.9rem;margin-bottom:10px;"><?php echo htmlspecialchars($member['designation']); ?></p>
                            <p class="faculty-qualification" style="color:#666;font-size:0.85rem;margin-bottom:5px;"><i class="fas fa-graduation-cap me-2" style="color:var(--primary-color);"></i><?php echo htmlspecialchars($member['qualification']); ?></p>
                            <p class="faculty-experience" style="color:#888;font-size:0.85rem;margin-bottom:12px;"><i class="fas fa-stethoscope me-2" style="color:var(--primary-color);"></i><?php echo htmlspecialchars($member['experience']); ?></p>
                            <?php if (!empty($member['bio'])): ?>
                                <p style="color:#666;font-size:0.85rem;margin-bottom:12px;line-height:1.6;"><?php echo htmlspecialchars($member['bio']); ?></p>
                            <?php endif; ?>
                            <span style="display:inline-block;background:var(--accent-color);color:#fff;padding:4px 15px;border-radius:50px;font-size:0.75rem;font-weight:500;"><?php echo htmlspecialchars($member['department']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section-inner">
    <div class="container">
        <h2>Be Part of Our Academic Community</h2>
        <p>Join SCMS and learn from experienced educators dedicated to your success in healthcare.</p>
        <a href="admissions.php" class="btn btn-primary-custom">Apply Now</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
