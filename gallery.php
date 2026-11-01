<?php
require_once __DIR__ . '/includes/header.php';

$batches = gallery_photos_data();
$videos = gallery_videos_data();

page_breadcrumb('Gallery', [], [
    'kicker' => 'Visual Journey',
    'heading' => 'SCMS Photo Gallery',
    'description' => 'Explore moments from our campus, events, student activities, and facilities through our photo gallery. Click any image to view in full size.',
]);
?>

<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css">

<section style="padding: 2rem 0 5rem; background: #ffffff;">
    <div class="container">
        <?php foreach ($batches as $batchIndex => $batch):
            $images = is_array($batch['image'] ?? null) ? $batch['image'] : [$batch['image'] ?? ''];
            $thumbs = is_array($batch['thumb'] ?? null) ? $batch['thumb'] : [$batch['thumb'] ?? ''];
            $batchImages = array_values(array_filter($images, fn($image) => $image !== ''));
            if (empty($batchImages)) continue;
        ?>
            <div class="gallery-batch mb-5">
                <div class="section-heading mb-3">
                    <span class="section-kicker">Photo Batch <?php echo $batchIndex + 1; ?></span>
                    <h3><?php echo htmlspecialchars($batch['title'] ?? 'Gallery Batch'); ?></h3>
                    <p><?php echo htmlspecialchars($batch['description'] ?? ''); ?></p>
                </div>
                <div class="row g-3 gallery-photo-grid">
                    <?php foreach ($batchImages as $imageIndex => $image):
                        $thumb = $thumbs[$imageIndex] ?? $image;
                        $lightboxGroup = 'gallery-batch-' . $batchIndex;
                    ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="<?php echo htmlspecialchars($image); ?>" class="glightbox gallery-item-box" data-gallery="<?php echo htmlspecialchars($lightboxGroup); ?>" data-glightbox="title:<?php echo htmlspecialchars($batch['title'] ?? 'Gallery Batch'); ?>; description:<?php echo htmlspecialchars($batch['description'] ?? ''); ?>">
                                <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($batch['title'] ?? 'Gallery Batch'); ?>" loading="lazy" width="640" height="480" decoding="async">
                                <div class="gallery-overlay">
                                    <span style="display:inline-block;background:rgba(255,255,255,0.2);padding:4px 12px;border-radius:50px;font-size:0.75rem;margin-top:8px;"><i class="fas fa-expand"></i> Click to Enlarge</span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php if (!empty($videos)): ?>
<section style="padding: 5rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Campus Videos</h2>
            <p>Watch videos about SCMS life, events, and achievements.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($videos as $video): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="video-card">
                        <video controls preload="metadata" style="width:100%; border-radius: 8px 8px 0 0;">
                            <source src="<?php echo htmlspecialchars($video['file']); ?>#t=0.1" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="video-body">
                            <h5><?php echo htmlspecialchars($video['title']); ?></h5>
                            <p>Video highlight from Safi College of Medical Sciences.</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="cta-section-inner">
    <div class="container">
        <h2>Be Part of Our Story</h2>
        <p>Join SCMS and create your own memorable moments.</p>
        <a href="admissions.php" class="btn btn-primary-custom">Apply Now</a>
    </div>
</section>

<!-- GLightbox JS -->
<script src="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js"></script>
<script>
    const lightbox = GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true,
        plyr: {
            css: 'https://cdn.plyr.io/3.7.8/plyr.css',
            js: 'https://cdn.plyr.io/3.7.8/plyr.js',
            config: {
                ratio: '16:9',
                muted: false,
                hideControls: true,
                youtube: {
                    noCookie: true,
                    rel: 0,
                    showinfo: 0,
                    iv_load_policy: 3
                },
                vimeo: {
                    byline: 0,
                    portrait: 0,
                    title: 0,
                    speed: true,
                    transparent: false
                }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
