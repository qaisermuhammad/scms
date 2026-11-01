<?php
require_once __DIR__ . '/includes/header.php';

$news = news_data();

page_breadcrumb('News & Updates', [], [
    'kicker' => 'Stay Informed',
    'heading' => 'Latest News & Updates',
    'description' => 'Follow the latest announcements, admissions notices, academic developments, and achievements from Safi College of Medical Sciences.',
]);
?>

<section style="padding: 1.75rem 0 3.5rem; background: #ffffff;">
    <div class="container">
        <?php foreach ($news as $item):
            $parts = news_date_parts($item['date']);
        ?>
            <div class="news-list-card">
                <div class="news-list-date">
                    <span class="day"><?php echo htmlspecialchars($parts['day']); ?></span>
                    <span class="month"><?php echo htmlspecialchars($parts['month']); ?></span>
                    <span class="year"><?php echo htmlspecialchars($parts['year']); ?></span>
                </div>
                <div class="news-list-body">
                    <span class="category-badge"><?php echo htmlspecialchars($item['category']); ?></span>
                    <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                    <p><?php echo htmlspecialchars($item['summary']); ?></p>
                    <a href="<?php echo htmlspecialchars($item['link']); ?>" class="read-more-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="cta-section-inner">
    <div class="container">
        <h2>Subscribe to Our Newsletter</h2>
        <p>Get the latest news and updates delivered directly to your inbox.</p>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="input-group">
                    <input type="email" class="form-control form-control-custom" placeholder="Enter your email address" style="border-radius:50px 0 0 50px;">
                    <button class="btn btn-primary-custom" type="button" style="border-radius:0 50px 50px 0;">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
