<?php
require_once __DIR__ . '/includes/header.php';

$college = college_profile();
$facilities = facility_data();
$photos = gallery_photos_data();

page_breadcrumb('Our Facilities', [], [
    'kicker' => 'Campus Infrastructure',
    'heading' => 'World-Class Facilities',
    'description' => 'SCMS provides state-of-the-art facilities to ensure our students receive the best possible education and training environment. From modern laboratories to comfortable hostels, we have everything needed for a complete learning experience.',
]);
?>

<section style="padding: 5rem 0; background: #ffffff;">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Our Facilities</h2>
            <p>Comprehensive infrastructure designed for healthcare education excellence.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($facilities as $facility): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="facility-inner-card">
                        <div class="facility-inner-icon">
                            <i class="fas <?php echo htmlspecialchars($facility['icon']); ?>"></i>
                        </div>
                        <h4><?php echo htmlspecialchars($facility['title']); ?></h4>
                        <p><?php echo htmlspecialchars($facility['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== VIRTUAL CAMPUS TOUR ===================== -->
<style>
.tour-video-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 6px 28px rgba(0,0,0,0.09);
    transition: transform .3s ease, box-shadow .3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.tour-video-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 48px rgba(0,0,0,0.16);
}
.tour-video-wrap {
    position: relative;
    aspect-ratio: 16/9;
    background: #0a1628;
    overflow: hidden;
    cursor: pointer;
}
.tour-video-wrap video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.tour-play-btn {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(5,20,50,0.38);
    transition: background .3s ease, opacity .3s ease;
}
.tour-play-btn i {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.92);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: var(--primary-color, #1a5276);
    transition: transform .25s ease;
    padding-left: 4px;
}
.tour-video-wrap:hover .tour-play-btn i {
    transform: scale(1.12);
}
.tour-video-wrap.playing .tour-play-btn {
    opacity: 0;
    pointer-events: none;
}
.tour-video-body {
    padding: 1.4rem 1.6rem 1.8rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.tour-video-badge {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--primary-color, #1a5276);
    background: rgba(26,82,118,0.08);
    padding: .25rem .75rem;
    border-radius: 50px;
    margin-bottom: .85rem;
    width: fit-content;
}
.tour-video-body h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: .55rem;
    line-height: 1.35;
}
.tour-video-body p {
    font-size: .93rem;
    color: #5a6377;
    line-height: 1.65;
    margin: 0;
    flex: 1;
}
</style>

<section id="virtual-tour" style="padding: 5.5rem 0 6rem; background: linear-gradient(160deg, #0d1f3c 0%, #1a3a5c 100%);">
    <div class="container">
        <div class="section-heading text-center" style="margin-bottom: 3rem;">
            <span class="section-kicker" style="color: rgba(255,255,255,0.6); background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);">
                <i class="fas fa-play-circle me-1"></i>Virtual Campus Tour
            </span>
            <h2 style="color: #fff;">See Our Campus Come Alive</h2>
            <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto;">
                Explore Safi College of Medical Sciences from anywhere in the world. Our virtual tour gives you an authentic look at the spaces where Pakistan's next generation of nurses and healthcare professionals are trained.
            </p>
        </div>

        <div class="row g-4">

            <!-- Anatomy Museum -->
            <div class="col-lg-4 col-md-6">
                <div class="tour-video-card">
                    <div class="tour-video-wrap" id="wrap-anatomy">
                        <video id="vid-anatomy" src="./assets/videos/Anatomy_museum.mp4" preload="metadata" playsinline muted loop></video>
                        <div class="tour-play-btn" id="btn-anatomy">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="tour-video-body">
                        <span class="tour-video-badge"><i class="fas fa-bone"></i> Science</span>
                        <h4>Anatomy Museum</h4>
                        <p>Step inside our anatomy museum — a dedicated learning space housing preserved specimens, anatomical models, and skeletal displays that bring the human body to life. Students gain deep morphological understanding essential for clinical practice.</p>
                    </div>
                </div>
            </div>

            <!-- Library -->
            <div class="col-lg-4 col-md-6">
                <div class="tour-video-card">
                    <div class="tour-video-wrap" id="wrap-library">
                        <video id="vid-library" src="./assets/videos/Library.mp4" preload="metadata" playsinline muted loop></video>
                        <div class="tour-play-btn" id="btn-library">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="tour-video-body">
                        <span class="tour-video-badge"><i class="fas fa-book-open"></i> Resources</span>
                        <h4>Library & Resource Centre</h4>
                        <p>Our fully stocked library offers thousands of medical and nursing textbooks, peer-reviewed journals, and digital databases. A quiet, well-lit environment designed to support focused study, research, and academic excellence.</p>
                    </div>
                </div>
            </div>

            <!-- Principal's Office -->
            <div class="col-lg-4 col-md-6">
                <div class="tour-video-card">
                    <div class="tour-video-wrap" id="wrap-office">
                        <video id="vid-office" src="./assets/videos/principal_office.mp4" preload="metadata" playsinline muted loop></video>
                        <div class="tour-play-btn" id="btn-office">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="tour-video-body">
                        <span class="tour-video-badge"><i class="fas fa-user-tie"></i> Leadership</span>
                        <h4>Principal's Office</h4>
                        <p>The office of the Principal reflects the college's culture of professionalism and open-door leadership. A hub of academic governance where strategic decisions, student welfare, and faculty coordination come together.</p>
                    </div>
                </div>
            </div>

            <!-- Science Lab -->
            <div class="col-lg-6 col-md-6">
                <div class="tour-video-card">
                    <div class="tour-video-wrap" id="wrap-science">
                        <video id="vid-science" src="./assets/videos/Science_lab.mp4" preload="metadata" playsinline muted loop></video>
                        <div class="tour-play-btn" id="btn-science">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="tour-video-body">
                        <span class="tour-video-badge"><i class="fas fa-flask"></i> Laboratory</span>
                        <h4>Science Laboratory</h4>
                        <p>Equipped with modern instruments and reagents, our science lab provides hands-on training in biology, chemistry, and allied health sciences. Students conduct real experiments that reinforce classroom theory and prepare them for clinical environments.</p>
                    </div>
                </div>
            </div>

            <!-- Skill Lab -->
            <div class="col-lg-6 col-md-6">
                <div class="tour-video-card">
                    <div class="tour-video-wrap" id="wrap-skill">
                        <video id="vid-skill" src="./assets/videos/Skill_lab.mp4" preload="metadata" playsinline muted loop></video>
                        <div class="tour-play-btn" id="btn-skill">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="tour-video-body">
                        <span class="tour-video-badge"><i class="fas fa-heartbeat"></i> Clinical Training</span>
                        <h4>Nursing Skills Lab</h4>
                        <p>Our state-of-the-art nursing skills lab is where theory meets practice. Equipped with high-fidelity mannequins, IV trainers, wound care stations, and monitoring equipment — students master essential nursing procedures in a safe, supervised setting before entering real wards.</p>
                    </div>
                </div>
            </div>

        </div><!-- /.row -->
    </div><!-- /.container -->
</section>

<script>
(function () {
    var videos = ['anatomy', 'library', 'office', 'science', 'skill'];
    videos.forEach(function (id) {
        var wrap = document.getElementById('wrap-' + id);
        var vid  = document.getElementById('vid-' + id);
        var btn  = document.getElementById('btn-' + id);
        if (!wrap || !vid || !btn) return;

        wrap.addEventListener('click', function () {
            if (vid.paused) {
                // Pause all others first
                videos.forEach(function (other) {
                    var ov = document.getElementById('vid-' + other);
                    var ow = document.getElementById('wrap-' + other);
                    if (ov && !ov.paused) { ov.pause(); ow.classList.remove('playing'); }
                });
                vid.play();
                wrap.classList.add('playing');
            } else {
                vid.pause();
                wrap.classList.remove('playing');
            }
        });

        vid.addEventListener('pause', function () { wrap.classList.remove('playing'); });
        vid.addEventListener('play',  function () { wrap.classList.add('playing'); });
    });
}());
</script>
<!-- =============================================================== -->

<section style="padding: 5rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Campus Gallery</h2>
            <p>Take a visual tour of our modern campus and facilities.</p>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($photos, 0, 6) as $photo): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="gallery-item-box">
                        <img src="<?php echo htmlspecialchars(is_array($photo['image'] ?? null) ? ($photo['image'][0] ?? '') : ($photo['image'] ?? '')); ?>" alt="<?php echo htmlspecialchars($photo['title']); ?>">
                        <div class="gallery-overlay">
                            <h5><?php echo htmlspecialchars($photo['title']); ?></h5>
                            <p><?php echo htmlspecialchars($photo['description']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section-inner">
    <div class="container">
        <h2>Visit Our Campus</h2>
        <p>Experience our world-class facilities firsthand. Schedule a campus visit today.</p>
        <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; margin-top:1.5rem;">
            <a href="contact.php" class="btn btn-primary-custom">Schedule a Visit</a>
            <a href="facilities.php#virtual-tour" class="btn btn-outline-light-custom">Watch Virtual Tour</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
