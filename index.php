<?php
require_once __DIR__ . '/includes/header.php';

$college = college_profile();
$programs = programs_data();
$news_items = news_data();
$features = feature_data();
$facilities = facility_data();
$hero_stats = [
    ['value' => '4', 'label' => 'Programs'],
    ['value' => 'TBA', 'label' => 'Students'],
    ['value' => $college['established'], 'label' => 'Established'],
    ['value' => '98%', 'label' => 'Success Rate'],
];
?>

<!-- Integrated Hero with Carousel Background -->
<style>
/* Force carousel images to fill the full hero height reliably on all hosts */
#heroCarousel,
#heroCarousel .carousel-inner,
#heroCarousel .carousel-item {
    height: 85vh;
    min-height: 500px;
}
#heroCarousel .carousel-item img {
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
</style>
<section id="heroCarousel" class="carousel slide" data-bs-ride="carousel" style="position: relative; overflow: hidden; opacity: 0; transition: opacity 0.5s ease; background: #0a1628;">
    <div class="carousel-indicators" style="z-index: 4; bottom: 30px;">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="./assets/images/profile.jpg" alt="Safi College of Medical Sciences profile">
        </div>
        <div class="carousel-item">
            <img src="./assets/images/campus_1.jpg" alt="SCMS Campus Aerial">
        </div>
        <div class="carousel-item">
            <img src="./assets/images/campus_2.jpg" alt="SCMS Facilities">
        </div>
        <div class="carousel-item">
            <img src="./assets/images/campus_3.jpg" alt="Student Activities">
        </div>
    </div>
    <!-- Bottom-heavy gradient so text is always readable -->
    <div class="hero-overlay" style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.10) 0%, rgba(0,0,0,0.20) 40%, rgba(0,0,0,0.55) 70%, rgba(0,0,0,0.78) 100%); z-index: 1; pointer-events: none;"></div>
    <!-- Hero text -->
    <div class="container hero-copy" style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); z-index: 2; width: 100%; padding-bottom: 80px;">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="hero-content" style="padding: 20px 10px; max-width: 900px; margin: 0 auto;">
                    <div class="hero-badge" style="margin: 0 auto 20px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(8px);"><i class="fas fa-star"></i>Affiliated with KMU Peshawar</div>
                    <h1 style="text-shadow: 0 2px 20px rgba(0,0,0,0.7); font-size: clamp(2.4rem, 6vw, 4.5rem); line-height: 1.1; margin-bottom: 20px; color: #fff;">Shaping the Future of <span style="color: var(--accent-color);">Healthcare</span> Professionals</h1>
                    <p style="max-width: 650px; margin: 0 auto 30px; font-size: 1.15rem; color: rgba(255,255,255,0.92); text-shadow: 0 1px 8px rgba(0,0,0,0.6); line-height: 1.7;">Welcome to <?php echo htmlspecialchars(site_name()); ?>, Mandani — a premier institution dedicated to excellence in nursing and allied health sciences education in Khyber Pakhtunkhwa.</p>
                    <div class="hero-actions justify-content-center" style="margin-bottom: 25px;">
                        <a href="programs.php" class="btn btn-primary-custom">Explore Programs</a>
                        <a href="about.php" class="btn btn-outline-light-custom">Learn More</a>
                    </div>
                    <div class="hero-stats justify-content-center" style="gap: 2rem 3rem;">
                        <?php foreach ($hero_stats as $item): ?>
                            <div class="hero-stat">
                                <h3 style="font-size: 2.4rem;"><?php echo htmlspecialchars($item['value']); ?></h3>
                                <p style="font-size: 0.95rem; opacity: 0.85;"><?php echo htmlspecialchars($item['label']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="z-index: 3; width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; top: 50%; transform: translateY(-50%); left: 25px; border: 1px solid rgba(255,255,255,0.5); position: absolute;">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 20px; height: 20px;"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="z-index: 3; width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; top: 50%; transform: translateY(-50%); right: 25px; border: 1px solid rgba(255,255,255,0.5); position: absolute;">
        <span class="carousel-control-next-icon" aria-hidden="true" style="width: 20px; height: 20px;"></span>
        <span class="visually-hidden">Next</span>
    </button>
</section>

<section class="about-preview-section full-width">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-image-wrap">
                    <img src="./assets/images/campus_1.jpg" alt="Safi College of Medical Sciences campus" class="about-image">
                    <div class="about-badge">
                        <h3><?php echo htmlspecialchars($college['established']); ?></h3>
                        <p>Established</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="section-kicker">About Our College</span>
                <h2>Excellence in Healthcare Education Since <?php echo htmlspecialchars($college['established']); ?></h2>
                <p><?php echo htmlspecialchars(site_name()); ?>, located in <?php echo htmlspecialchars($college['location']); ?>, is a leading institution dedicated to producing competent healthcare professionals. We currently offer BS Generic, BS Nursing (Post RN), LHV, and CNA programs.</p>
                <p>Our college is affiliated with <?php echo htmlspecialchars($college['affiliation']); ?> and recognized by <?php echo htmlspecialchars($college['recognition']); ?>. With modern facilities and committed faculty, we work to provide strong academic and clinical training.</p>
                <div class="check-grid">
                    <div><i class="fas fa-check-circle"></i><span>KMU Affiliated</span></div>
                    <div><i class="fas fa-check-circle"></i><span>PNC Recognized</span></div>
                    <div><i class="fas fa-check-circle"></i><span>Modern Labs</span></div>
                    <div><i class="fas fa-check-circle"></i><span>Clinical Training</span></div>
                </div>
                <a href="about.php" class="btn btn-primary-custom">Read More About Us</a>
            </div>
        </div>
    </div>
</section>

<section class="programs-section full-width">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">Academic Pathways</span>
            <h2>Our Programs</h2>
            <p>We offer nursing and allied health programs designed to meet the growing need for qualified healthcare professionals.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($programs as $program): ?>
                <div class="col-lg-4 col-md-6">
                    <article class="program-card">
                        <div class="program-icon"><i class="fas <?php echo htmlspecialchars($program['icon']); ?>"></i></div>
                        <h3><?php echo htmlspecialchars($program['title']); ?></h3>
                        <p><?php echo htmlspecialchars($program['description']); ?></p>
                        <div class="program-meta">
                            <span><i class="far fa-clock"></i><?php echo htmlspecialchars($program['duration']); ?></span>
                            <span><i class="fas fa-users"></i><?php echo htmlspecialchars((string) $program['seats']); ?> Seats</span>
                        </div>
                        <p class="program-eligibility"><strong>Eligibility:</strong> <?php echo htmlspecialchars($program['eligibility']); ?></p>
                        <a href="programs.php" class="btn btn-outline-custom w-100">View Details</a>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="features-section full-width">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">Why Choose SCMS?</span>
            <h2>Healthcare Education With Purpose</h2>
            <p>Discover what makes <?php echo htmlspecialchars($college['short_name']); ?> a strong choice for your academic and professional journey.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($features as $feature): ?>
                <div class="col-lg-4 col-md-6">
                    <article class="feature-box">
                        <div class="feature-icon"><i class="fas <?php echo htmlspecialchars($feature['icon']); ?>"></i></div>
                        <div>
                            <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                            <p><?php echo htmlspecialchars($feature['description']); ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="facilities-section full-width">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">Campus Facilities</span>
            <h2>Learning Spaces That Support Professional Growth</h2>
            <p>SCMS continues to build a campus environment that supports classroom learning, practical skills, and student development.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($facilities as $facility): ?>
                <div class="col-lg-3 col-md-6">
                    <article class="facility-card">
                        <div class="facility-icon"><i class="fas <?php echo htmlspecialchars($facility['icon']); ?>"></i></div>
                        <h3><?php echo htmlspecialchars($facility['title']); ?></h3>
                        <p><?php echo htmlspecialchars($facility['description']); ?></p>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="news-section full-width">
    <div class="container">
        <div class="section-heading text-center">
            <span class="section-kicker">Latest Updates</span>
            <h2>News & Announcements</h2>
            <p>Stay informed with the latest developments, admissions notices, and academic updates from SCMS.</p>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($news_items, 0, 3) as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <article class="news-card">
                        <div class="news-card-visual"><i class="fas fa-newspaper"></i></div>
                        <div class="news-card-body">
                            <div class="news-meta">
                                <span class="news-date"><?php echo htmlspecialchars(format_news_date($item['date'])); ?></span>
                                <span class="news-category"><?php echo htmlspecialchars($item['category']); ?></span>
                            </div>
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p><?php echo htmlspecialchars($item['summary']); ?></p>
                            <a href="<?php echo htmlspecialchars($item['link']); ?>" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section full-width">
    <div class="container">
        <div class="cta-box">
            <span class="section-kicker">Begin Your Journey</span>
            <h2>Ready to Start Your Healthcare Career?</h2>
            <p>Join Safi College of Medical Sciences and become part of the next generation of healthcare professionals serving Pakistan and beyond.</p>
            <div class="hero-actions justify-content-center">
                <a href="admissions.php" class="btn btn-primary-custom">Apply for Admission</a>
                <a href="contact.php" class="btn btn-outline-light-custom">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<script>
// Fade in hero after Bootstrap carousel initializes to prevent FOUC
window.addEventListener('load', () => {
    const hero = document.getElementById('heroCarousel');
    if (hero) hero.style.opacity = '1';
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
