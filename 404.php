<?php
require_once __DIR__ . '/includes/header.php';
$college = college_profile();

http_response_code(404);
?>

<section class="breadcrumb-section" style="padding: 6rem 0 5rem;">
    <div class="container">
        <h1 style="font-size: 4rem; margin-bottom: 1rem;">404</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">Page Not Found</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">404</li>
            </ol>
        </nav>
    </div>
</section>

<section style="padding: 5rem 0; background: #ffffff; text-align: center;">
    <div class="container">
        <div style="max-width: 600px; margin: 0 auto;">
            <i class="fas fa-exclamation-circle" style="font-size: 5rem; color: var(--primary-color); margin-bottom: 1.5rem;"></i>
            <h2 style="font-size: 2rem; margin-bottom: 1rem;">Oops! Page Not Found</h2>
            <p style="color: var(--text-soft); margin-bottom: 2rem; font-size: 1.05rem;">
                The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
            </p>
            <div class="hero-actions justify-content-center">
                <a href="index.php" class="btn btn-primary-custom"><i class="fas fa-home me-2"></i>Back to Home</a>
                <a href="contact.php" class="btn btn-outline-custom">Contact Support</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
