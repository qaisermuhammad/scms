</main>
<footer class="site-footer">
    <div class="container">
        <?php $college = college_profile(); ?>
        <div class="footer-main">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand-block">
                        <div class="footer-brand">
                            <img src="./assets/images/logo.jpg" alt="SCMS logo" class="footer-logo">
                            <div>
                                <h4>Safi College</h4>
                                <p>Medical Sciences</p>
                            </div>
                        </div>
                        <p class="footer-about-text">
                            A premier institution in Mandani, Charsadda, dedicated to excellence in nursing and allied health sciences education. Affiliated with KMU Peshawar.
                        </p>
                        <div class="footer-social">
                            <a href="<?php echo htmlspecialchars($college['facebook_url']); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-column">
                        <h5>Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="about.php"><i class="fas fa-chevron-right"></i>About SCMS</a></li>
                            <li><a href="programs.php"><i class="fas fa-chevron-right"></i>Programs</a></li>
                            <li><a href="admissions.php"><i class="fas fa-chevron-right"></i>Admissions</a></li>
                            <li><a href="faculty.php"><i class="fas fa-chevron-right"></i>Faculty</a></li>
                            <li><a href="facilities.php"><i class="fas fa-chevron-right"></i>Facilities</a></li>
                            <li><a href="news.php"><i class="fas fa-chevron-right"></i>News</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-column">
                        <h5>Programs</h5>
                        <ul class="footer-links">
                            <li><a href="program.php?id=bsn-generic"><i class="fas fa-chevron-right"></i>BS Generic</a></li>
                            <li><a href="program.php?id=bsn-nursing"><i class="fas fa-chevron-right"></i>BS Nursing</a></li>
                            <li><a href="program.php?id=lhv"><i class="fas fa-chevron-right"></i>LHV</a></li>
                            <li><a href="program.php?id=cna"><i class="fas fa-chevron-right"></i>CNA</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-column">
                        <h5>Contact Us</h5>
                        <ul class="footer-contact">
                            <li><i class="fas fa-map-marker-alt"></i><span><?php echo htmlspecialchars($college['address']); ?></span></li>
                            <li><i class="fas fa-phone-alt"></i><span><?php echo htmlspecialchars($college['phone']); ?></span></li>
                            <li><i class="fas fa-envelope"></i><span><?php echo htmlspecialchars($college['email']); ?></span></li>
                            <li><i class="fas fa-globe"></i><span><?php echo htmlspecialchars($college['website']); ?></span></li>
                        </ul>
                        <a href="contact.php" class="footer-btn">Get Directions</a>
                        <a href="admin/login.php" class="footer-btn" style="margin-top: 0.6rem;"><i class="fas fa-lock me-1" aria-hidden="true"></i> Admin Login</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-bar">
            <div class="footer-bottom-left">
                <span>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(site_name()); ?>. All rights reserved.</span>
                <span class="footer-sep">|</span>
                <span><?php echo htmlspecialchars($college['affiliation']); ?></span>
            </div>
            <div class="footer-bottom-right">
                <span>Developed by <strong>Qaiser Muhammad Abdur Rehman</strong></span>
                <a href="mailto:qaisermuhammad@hotmail.com" class="footer-dev-link" title="Email"><i class="fas fa-envelope"></i></a>
                <a href="https://www.facebook.com/qaisermuhammad" target="_blank" rel="noopener" class="footer-dev-link" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="admin/login.php" class="footer-dev-link footer-admin" title="Admin Login"><i class="fas fa-lock"></i> Admin</a>
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Auto-apply AOS to common elements (cards, sections, grids)
    document.querySelectorAll('.program-card, .feature-box, .facility-card, .news-card, .value-item, .mv-card, .faculty-card, .facility-inner-card, .scholarship-card, .criteria-item, .admission-step, .download-card, .email-card, .career-card, .info-card-block, .timeline-item, .owner-card').forEach((el, i) => {
        if (!el.hasAttribute('data-aos')) {
            el.setAttribute('data-aos', 'fade-up');
            el.setAttribute('data-aos-delay', String((i % 6) * 100));
        }
    });
    AOS.init({ duration: 800, once: true, offset: 100 });

    // Back to Top button
    const backToTop = document.createElement('div');
    backToTop.id = 'backToTop';
    backToTop.className = 'back-to-top';
    backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTop.style.cssText = 'position:fixed;bottom:30px;right:30px;width:45px;height:45px;background:var(--primary-color);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:0;visibility:hidden;transition:all 0.3s;z-index:1000;box-shadow:0 4px 15px rgba(26,95,42,0.4);';
    document.body.appendChild(backToTop);

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.style.opacity = '1';
            backToTop.style.visibility = 'visible';
        } else {
            backToTop.style.opacity = '0';
            backToTop.style.visibility = 'hidden';
        }
    });
    backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    const navbar = document.querySelector('.scms-navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 2px 30px rgba(0,0,0,0.15)';
            } else {
                navbar.style.boxShadow = '0 10px 30px rgba(15, 23, 42, 0.08)';
            }
        });
    }
</script>
</body>
</html>
