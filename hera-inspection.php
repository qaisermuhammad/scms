<?php
require_once __DIR__ . '/includes/header.php';
page_breadcrumb('HERA Inspection', [], [
    'kicker' => 'Accreditation',
    'heading' => 'HERA Inspection: SCMS Recognised as a Premier Nursing Institution in KPK',
    'description' => 'July 31, 2026  |  Mandani, Charsadda',
]);
?>

<!-- Article Body -->
<section style="padding: 5rem 0; background: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Lead image -->
                <div style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.12); margin-bottom: 2.5rem;">
                    <img src="./assets/images/visit/1visit.jpeg"
                         alt="HERA Inspection Team at Safi College of Medical Sciences"
                         class="w-100" style="object-fit: cover; max-height: 480px;">
                </div>

                <!-- Article text -->
                <div class="news-article-body" style="font-size: 1.08rem; line-height: 1.9; color: #333;">

                    <p>
                        The <strong>Higher Education Regulatory Authority (HERA)</strong> team conducted a thorough
                        inspection of <strong>Safi College of Medical Sciences, Mandani, Charsadda</strong>.
                        The team commended the college on its exceptional standards, acknowledging it as one of the
                        <strong>premier nursing institutions in Khyber Pakhtunkhwa</strong>.
                    </p>

                    <p>
                        The inspection highlighted the college's outstanding educational facilities, dedicated faculty,
                        and robust academic programs, underscoring its commitment to excellence in nursing education.
                        The recognition from HERA underscores Safi College of Medical Sciences's pivotal role in
                        shaping the future of nursing professionals in the region.
                    </p>

                    <!-- Highlight box -->
                    <div style="background: linear-gradient(135deg, var(--primary-color, #1a5276) 0%, var(--accent-color, #2e86c1) 100%);
                                color: #fff; border-radius: 12px; padding: 2rem 2.2rem; margin: 2.5rem 0;">
                        <h5 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; opacity: .9; text-transform: uppercase; letter-spacing: .05em;">
                            <i class="fas fa-star me-2"></i>Key Highlights of the Inspection
                        </h5>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: .75rem;">
                            <li><i class="fas fa-check-circle me-2" style="color:#a8d8f0;"></i>Outstanding educational facilities &amp; infrastructure</li>
                            <li><i class="fas fa-check-circle me-2" style="color:#a8d8f0;"></i>Dedicated and qualified faculty members</li>
                            <li><i class="fas fa-check-circle me-2" style="color:#a8d8f0;"></i>Robust and comprehensive academic programs</li>
                            <li><i class="fas fa-check-circle me-2" style="color:#a8d8f0;"></i>Recognised as one of the premier nursing institutions in KPK</li>
                            <li><i class="fas fa-check-circle me-2" style="color:#a8d8f0;"></i>Strong commitment to excellence in nursing education</li>
                        </ul>
                    </div>

                    <p>
                        This commendation reflects the tireless efforts of the college's management, faculty, and
                        staff who continue to raise the bar in healthcare education across the province. SCMS
                        remains steadfast in its mission to produce highly competent, compassionate, and
                        well-rounded nursing professionals ready to serve Pakistan's healthcare system.
                    </p>

                </div><!-- /.news-article-body -->

                <!-- Tags / share row -->
                <div style="display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid #eee;">
                    <span style="background: #eaf3fb; color: #1a5276; border-radius: 50px; padding: .3rem .9rem; font-size: .85rem; font-weight: 600;">
                        <i class="fas fa-tag me-1"></i>Accreditation
                    </span>
                    <span style="background: #eaf3fb; color: #1a5276; border-radius: 50px; padding: .3rem .9rem; font-size: .85rem; font-weight: 600;">
                        HERA
                    </span>
                    <span style="background: #eaf3fb; color: #1a5276; border-radius: 50px; padding: .3rem .9rem; font-size: .85rem; font-weight: 600;">
                        KPK Nursing
                    </span>
                    <a href="news.php" class="btn btn-outline-custom ms-auto" style="font-size:.9rem;">
                        <i class="fas fa-arrow-left me-1"></i> Back to News
                    </a>
                </div>

            </div><!-- /.col-lg-8 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>

<!-- Photo Gallery -->
<section style="padding: 4rem 0 5rem; background: #f8f9fa;">
    <div class="container">
        <div class="section-heading text-center" style="margin-bottom: 2.5rem;">
            <span class="section-kicker"><i class="fas fa-camera me-2"></i>Photo Gallery</span>
            <h2>Inspection Visit in Pictures</h2>
            <p>A visual account of the HERA team's visit to Safi College of Medical Sciences, Mandani.</p>
        </div>

        <!-- Lightbox Gallery Grid -->
        <div class="row g-3" id="heraGallery">

            <?php
            $gallery_images = [
                ['src' => './assets/images/visit/1visit.jpeg',   'alt' => 'HERA Inspection Visit — Photo 1'],
                ['src' => './assets/images/visit/2visit.jpeg',   'alt' => 'HERA Inspection Visit — Photo 2'],
                ['src' => './assets/images/visit/3visit.jpeg',   'alt' => 'HERA Inspection Visit — Photo 3'],
                ['src' => './assets/images/visit/Weixin Image_20260731164805_1621_25.jpg', 'alt' => 'HERA Inspection Visit — Photo 4'],
                ['src' => './assets/images/visit/Weixin Image_20260731164806_1622_25.jpg', 'alt' => 'HERA Inspection Visit — Photo 5'],
                ['src' => './assets/images/visit/Weixin Image_20260731164807_1623_25.jpg', 'alt' => 'HERA Inspection Visit — Photo 6'],
                ['src' => './assets/images/visit/Weixin Image_20260731164808_1624_25.jpg', 'alt' => 'HERA Inspection Visit — Photo 7'],
                ['src' => './assets/images/visit/Weixin Image_20260731164809_1625_25.jpg', 'alt' => 'HERA Inspection Visit — Photo 8'],
            ];

            foreach ($gallery_images as $i => $img): ?>
                <div class="col-lg-3 col-md-4 col-6">
                    <a href="<?php echo htmlspecialchars($img['src']); ?>"
                       class="hera-gallery-item"
                       data-index="<?php echo $i; ?>"
                       title="<?php echo htmlspecialchars($img['alt']); ?>">
                        <div class="gallery-thumb-wrap">
                            <img src="<?php echo htmlspecialchars($img['src']); ?>"
                                 alt="<?php echo htmlspecialchars($img['alt']); ?>"
                                 class="gallery-thumb-img"
                                 loading="lazy">
                            <div class="gallery-thumb-overlay">
                                <i class="fas fa-expand-alt"></i>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

        </div><!-- /#heraGallery -->
    </div>
</section>

<!-- Lightbox Modal -->
<div id="heraLightbox" style="display:none; position:fixed; inset:0; z-index:9999;
     background:rgba(0,0,0,0.92); align-items:center; justify-content:center;">
    <button id="lbClose" title="Close"
        style="position:absolute; top:20px; right:24px; background:none; border:none;
               color:#fff; font-size:2rem; cursor:pointer; line-height:1;">
        <i class="fas fa-times"></i>
    </button>
    <button id="lbPrev" title="Previous"
        style="position:absolute; left:20px; top:50%; transform:translateY(-50%);
               background:rgba(255,255,255,0.15); border:none; color:#fff;
               width:50px; height:50px; border-radius:50%; font-size:1.4rem;
               cursor:pointer; display:flex; align-items:center; justify-content:center;">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button id="lbNext" title="Next"
        style="position:absolute; right:20px; top:50%; transform:translateY(-50%);
               background:rgba(255,255,255,0.15); border:none; color:#fff;
               width:50px; height:50px; border-radius:50%; font-size:1.4rem;
               cursor:pointer; display:flex; align-items:center; justify-content:center;">
        <i class="fas fa-chevron-right"></i>
    </button>
    <img id="lbImage" src="" alt=""
         style="max-width:90vw; max-height:88vh; border-radius:8px;
                box-shadow:0 16px 64px rgba(0,0,0,0.7); object-fit:contain;">
    <p id="lbCaption"
       style="position:absolute; bottom:20px; left:50%; transform:translateX(-50%);
              color:rgba(255,255,255,0.8); font-size:.95rem; white-space:nowrap;"></p>
</div>

<style>
/* Gallery thumbnails */
.gallery-thumb-wrap {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 4/3;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(0,0,0,0.10);
    transition: transform .25s ease, box-shadow .25s ease;
}
.gallery-thumb-wrap:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.gallery-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s ease;
}
.gallery-thumb-wrap:hover .gallery-thumb-img {
    transform: scale(1.07);
}
.gallery-thumb-overlay {
    position: absolute;
    inset: 0;
    background: rgba(26, 82, 118, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity .25s ease;
    color: #fff;
    font-size: 1.6rem;
}
.gallery-thumb-wrap:hover .gallery-thumb-overlay {
    opacity: 1;
}
</style>

<script>
(function () {
    const images = [
        { src: './assets/images/visit/1visit.jpeg',   caption: 'HERA Inspection Visit — Photo 1' },
        { src: './assets/images/visit/2visit.jpeg',   caption: 'HERA Inspection Visit — Photo 2' },
        { src: './assets/images/visit/3visit.jpeg',   caption: 'HERA Inspection Visit — Photo 3' },
        { src: './assets/images/visit/Weixin Image_20260731164805_1621_25.jpg', caption: 'HERA Inspection Visit — Photo 4' },
        { src: './assets/images/visit/Weixin Image_20260731164806_1622_25.jpg', caption: 'HERA Inspection Visit — Photo 5' },
        { src: './assets/images/visit/Weixin Image_20260731164807_1623_25.jpg', caption: 'HERA Inspection Visit — Photo 6' },
        { src: './assets/images/visit/Weixin Image_20260731164808_1624_25.jpg', caption: 'HERA Inspection Visit — Photo 7' },
        { src: './assets/images/visit/Weixin Image_20260731164809_1625_25.jpg', caption: 'HERA Inspection Visit — Photo 8' },
    ];

    let current = 0;
    const lb      = document.getElementById('heraLightbox');
    const lbImg   = document.getElementById('lbImage');
    const lbCap   = document.getElementById('lbCaption');

    function show(index) {
        current = (index + images.length) % images.length;
        lbImg.src       = images[current].src;
        lbImg.alt       = images[current].caption;
        lbCap.textContent = images[current].caption + '  (' + (current + 1) + ' / ' + images.length + ')';
        lb.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function close() {
        lb.style.display = 'none';
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.hera-gallery-item').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            show(parseInt(this.dataset.index, 10));
        });
    });

    document.getElementById('lbClose').addEventListener('click', close);
    document.getElementById('lbPrev').addEventListener('click', function () { show(current - 1); });
    document.getElementById('lbNext').addEventListener('click', function () { show(current + 1); });

    lb.addEventListener('click', function (e) {
        if (e.target === lb) close();
    });

    document.addEventListener('keydown', function (e) {
        if (lb.style.display !== 'flex') return;
        if (e.key === 'Escape')     close();
        if (e.key === 'ArrowLeft')  show(current - 1);
        if (e.key === 'ArrowRight') show(current + 1);
    });
}());
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
