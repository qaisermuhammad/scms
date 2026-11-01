<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/functions.php';
send_security_headers();

$college = college_profile();
$page_titles = [
    'index.php' => 'Home',
    'about.php' => 'About',
    'programs.php' => 'Programs',
    'program.php' => 'Program Details',
    'admissions.php' => 'Admissions',
    'faculty.php' => 'Faculty',
    'facilities.php' => 'Facilities',
    'news.php' => 'News',
    'hera-inspection.php' => 'HERA Inspection',
    'gallery.php' => 'Gallery',
    'downloads.php' => 'Downloads',
    'scholarships.php' => 'Scholarships',
    'contact.php' => 'Contact',
    '404.php' => 'Page Not Found',
];
$current_page = basename($_SERVER['PHP_SELF']);
$page_label = $page_titles[$current_page] ?? 'Page';
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo htmlspecialchars(page_meta_description()); ?>">
    <meta name="keywords" content="SCMS, Safi College, Medical Sciences, BS Generic, LHV, CNA, Nursing, Charsadda, KPK, Pakistan, KMU, Healthcare Education">
    <meta name="author" content="Safi College of Medical Sciences">
    <link rel="canonical" href="<?php echo htmlspecialchars(canonical_url()); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars(canonical_url()); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_label . ' | ' . site_name()); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars(page_meta_description()); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars('https://www.scms.edu.pk/assets/images/profile.jpg'); ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars(site_name()); ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo htmlspecialchars(canonical_url()); ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_label . ' | ' . site_name()); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars(page_meta_description()); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars('https://www.scms.edu.pk/assets/images/profile.jpg'); ?>">

    <!-- Structured Data: Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "<?php echo htmlspecialchars(site_name()); ?>",
        "alternateName": "SCMS",
        "url": "https://www.scms.edu.pk",
        "logo": "https://www.scms.edu.pk/assets/images/logo.jpg",
        "image": "https://www.scms.edu.pk/assets/images/profile.jpg",
        "description": "<?php echo htmlspecialchars(page_meta_description()); ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Harichand Road, Mandani",
            "addressLocality": "Charsadda",
            "addressRegion": "Khyber Pakhtunkhwa",
            "addressCountry": "PK"
        },
        "telephone": "<?php echo htmlspecialchars($college['phone']); ?>",
        "email": "<?php echo htmlspecialchars($college['email']); ?>",
        "sameAs": [
            "<?php echo htmlspecialchars($college['facebook_url']); ?>"
        ],
        "affiliation": {
            "@type": "EducationalOrganization",
            "name": "Khyber Medical University (KMU), Peshawar"
        }
    }
    </script>

    <title><?php echo htmlspecialchars($page_label . ' | ' . site_name()); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/site.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/site.css'); ?>">
    <link rel="icon" href="./assets/images/favicon.svg?v=<?php echo filemtime(__DIR__ . '/../assets/images/favicon.svg'); ?>" type="image/svg+xml">
    <style>
        /* Fixed navbar compensation */
        body { padding-top: 66px; }
        @media (min-width: 992px) { body { padding-top: 76px; } }
        body.home-page { padding-top: 0; }
        body.home-page .scms-navbar { background: rgba(255,255,255,0.95); }
        body.home-page .scms-navbar .nav-link,
        body.home-page .scms-navbar .navbar-brand { color: var(--secondary-color); }
        .scms-navbar { transition: background 0.3s, box-shadow 0.3s; }
    </style>
</head>
<body class="<?php echo $current_page === 'index.php' ? 'home-page' : 'inner-page'; ?>">
<header>
    <div class="top-bar">
        <div class="container">
            <div class="row g-2 align-items-center">
                <div class="col-lg-6">
                    <div class="top-bar-items">
                        <span><i class="fas fa-phone-alt"></i><?php echo htmlspecialchars($college['phone']); ?></span>
                        <span><i class="fas fa-envelope"></i><?php echo htmlspecialchars($college['email']); ?></span>
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <div class="top-bar-links">
                        <a href="admissions.php"><i class="fas fa-graduation-cap"></i>Admissions</a>
                        <a href="contact.php"><i class="fas fa-headset"></i>Support</a>
                        <a href="admin/login.php"><i class="fas fa-lock"></i>Admin</a>
                        <a href="<?php echo htmlspecialchars($college['facebook_url']); ?>" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top scms-navbar" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="./assets/images/logo.jpg" alt="<?php echo htmlspecialchars(site_name()); ?> logo" class="brand-logo">
                <div class="brand-text">
                    <strong>Safi College</strong>
                    <small>Medical Sciences</small>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo in_array($current_page, ['about.php', 'faculty.php'], true) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">About</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="about.php"><i class="fas fa-university me-2"></i>About SCMS</a></li>
                            <li><a class="dropdown-item" href="faculty.php"><i class="fas fa-chalkboard-teacher me-2"></i>Our Faculty</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'facilities.php' ? 'active' : ''; ?>" href="facilities.php">Facilities</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo in_array($current_page, ['programs.php', 'program.php'], true) ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Programs</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="programs.php"><i class="fas fa-list me-2"></i>All Programs</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php foreach (array_slice(programs_data(), 0, 6) as $prog): ?>
                                <li><a class="dropdown-item" href="program.php?id=<?php echo htmlspecialchars($prog['id']); ?>"><i class="fas <?php echo htmlspecialchars($prog['icon']); ?> me-2"></i><?php echo htmlspecialchars($prog['title']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'admissions.php' ? 'active' : ''; ?>" href="admissions.php">Admissions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'news.php' ? 'active' : ''; ?>" href="news.php">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'gallery.php' ? 'active' : ''; ?>" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'downloads.php' ? 'active' : ''; ?>" href="downloads.php">Downloads</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'scholarships.php' ? 'active' : ''; ?>" href="scholarships.php">Scholarships</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'contact.php' ? 'active' : ''; ?>" href="contact.php">Contact</a>
                    </li>
                </ul>
                <a href="admissions.php" class="btn btn-primary-custom ms-lg-3 d-none d-lg-inline-flex">Apply Now</a>
            </div>
        </div>
    </nav>
</header>
<main class="page-content">
    <div class="container flash-wrap">
        <?php render_flash(); ?>
    </div>
