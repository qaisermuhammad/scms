<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

$college = college_profile();
$subjects = contact_subjects_data();
$departments = contact_departments_data();

$values = array_fill_keys(['name', 'email', 'country_code', 'phone', 'subject', 'message'], '');

$values['country_code'] = '+92';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $unused) {
        $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    }
    $token = is_string($_POST['csrf_token'] ?? null) ? $_POST['csrf_token'] : '';
    if (!verify_csrf_token($token)) {
        flash_message('error', 'Invalid request. Please refresh the page and try again.');
    } else {
        extract($values, EXTR_SKIP);

        if ($name === '' || $email === '' || $subject === '' || $message === '') {
            flash_message('error', 'Please complete all required fields.');
        } elseif (preg_match('/[\r\n]/', $name . $email . $subject)) {
            flash_message('error', 'Please remove line breaks from your name, email and subject.');
        } elseif (mb_strlen($name) > 120 || strlen($email) > 254 || mb_strlen($phone) > 40 || mb_strlen($subject) > 120) {
            flash_message('error', 'One or more fields exceed the allowed length.');
        } elseif (count(preg_split('/\s+/u', trim($message), -1, PREG_SPLIT_NO_EMPTY)) > 100) {
            flash_message('error', 'Your message must contain no more than 100 words.');
        } elseif ($phone !== '' && ($country_code !== '+92' || !preg_match('/^3[0-9]{9}$/D', $phone))) {
            flash_message('error', 'Enter a valid phone number: 10 digits starting with 3, without the leading 0, or leave it blank.');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_message('error', 'Please enter a valid email address.');
        } else {
            // Save to JSON (always works locally)
            $saved = save_contact_message([
                'name' => $name,
                'email' => $email,
                'phone' => $phone === '' ? '' : $country_code . $phone,
                'subject' => $subject,
                'message' => $message,
            ]);

            if (!$saved) {
                flash_message('error', 'Your message could not be saved. Please try again later.');
                header('Location: contact.php');
                exit;
            }

            // Try to send email via PHP mail()
            $to = $college['contact_email'] ?? 'scms.charsadda@gmail.com';
            $email_subject = "[SCMS Website Contact] " . $subject;
            $email_body = "Name: $name\n";
            $email_body .= "Email: $email\n";
            $email_body .= "Phone: " . ($phone === '' ? '' : $country_code . $phone) . "\n";
            $email_body .= "Subject: $subject\n";
            $email_body .= "Message:\n$message\n";
            $email_body .= "\n---\nSent from: " . $_SERVER['HTTP_HOST'] . "\nDate: " . date('Y-m-d H:i:s') . "\n";

            $headers = "From: SCMS Website <noreply@localhost>\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            $mail_result = @mail($to, $email_subject, $email_body, $headers);

            if ($mail_result) {
                flash_message('success', 'Thank you. Your message has been saved for the admissions team.');
            } else {
                // Email failed but message was saved locally
                flash_message('success', 'Thank you. Your message has been saved for the admissions team.');
            }

            header('Location: contact.php');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
page_breadcrumb('Contact Us');
?>

<section class="contact-page-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="contact-info-card">
                    <h3><i class="fas fa-address-book me-2"></i>Contact Information</h3>

                    <div class="contact-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h5>Address</h5>
                            <p><?php echo htmlspecialchars($college['address']); ?></p>
                            <p><a href="<?php $mu = !empty($college['maps_url']) && strpos($college['maps_url'], 'http') === 0 ? $college['maps_url'] : 'https://www.google.com/maps/search/?api=1&query=34.346169,71.788426'; echo htmlspecialchars($mu); ?>" target="_blank" rel="noopener">Open in Google Maps <i class="fas fa-external-link-alt ms-1" style="font-size:0.75rem;"></i></a></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <h5>Phone</h5>
                            <p><?php echo htmlspecialchars($college['phone']); ?></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i class="fas fa-mobile-alt"></i>
                        <div>
                            <h5>Mobile / WhatsApp</h5>
                            <p><a href="https://wa.me/<?php echo htmlspecialchars(str_replace(['+', ' ', '-'], '', $college['mobile'])); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($college['mobile']); ?> <i class="fab fa-whatsapp ms-1" style="color:#25D366;"></i></a></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h5>General Inquiries</h5>
                            <p><a href="mailto:<?php echo htmlspecialchars($college['email']); ?>"><?php echo htmlspecialchars($college['email']); ?></a></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h5>Admissions</h5>
                            <p><a href="mailto:<?php echo htmlspecialchars($college['admissions_email']); ?>"><?php echo htmlspecialchars($college['admissions_email']); ?></a></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i class="fas fa-globe"></i>
                        <div>
                            <h5>Website</h5>
                            <p><a href="<?php echo htmlspecialchars($college['website']); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($college['website']); ?></a></p>
                        </div>
                    </div>

                    <div class="footer-social" style="margin-top: 1.5rem;">
                        <a href="<?php echo htmlspecialchars($college['facebook_url']); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="contact-form-card">
                    <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-paper-plane me-2" style="color:var(--primary-color);"></i>Send Us a Message</h3>
                    <?php render_flash(); ?>
                    <form method="POST" action="contact.php">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom" for="contact-name">Full Name (required)</label>
                                <input type="text" id="contact-name" name="name" class="form-control form-control-custom" value="<?php echo htmlspecialchars($values['name']); ?>" maxlength="120" placeholder="Your full name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom" for="contact-email">Email Address (required)</label>
                                <input type="email" id="contact-email" name="email" class="form-control form-control-custom" value="<?php echo htmlspecialchars($values['email']); ?>" maxlength="254" placeholder="your@email.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom" for="contact-phone">Phone Number (optional)</label>
                                <div class="input-group"><label class="visually-hidden" for="contact-country">Country code</label><select id="contact-country" name="country_code" class="form-select" style="max-width: 160px;"><option value="+92">Pakistan +92</option></select><input type="tel" id="contact-phone" name="phone" class="form-control form-control-custom" value="<?php echo htmlspecialchars($values['phone']); ?>" maxlength="10" pattern="3[0-9]{9}" inputmode="numeric" aria-describedby="contact-phone-help"></div>
                                <small id="contact-phone-help" class="form-text">Enter 10 digits starting with 3, without the leading 0. Example: 3456453236.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom" for="contact-subject">Subject (required)</label>
                                <select id="contact-subject" name="subject" class="form-control form-control-custom" required>
                                    <option value="">Select a subject</option>
                                    <?php foreach ($subjects as $sub): ?>
                                        <option value="<?php echo htmlspecialchars($sub); ?>" <?php echo $values['subject'] === $sub ? 'selected' : ''; ?>><?php echo htmlspecialchars($sub); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom" for="contact-message">Message (required)</label>
                                <textarea id="contact-message" name="message" class="form-control form-control-custom" rows="5" aria-describedby="contact-message-help" placeholder="Write your message here..." required><?php echo htmlspecialchars($values['message']); ?></textarea>
                                <small id="contact-message-help" class="form-text"><span id="contact-message-remaining">100</span> words remaining (maximum 100).</small>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-custom">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="section-heading text-center">
            <h2>Department Emails</h2>
            <p>Contact specific departments directly for faster response.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($departments as $dept): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="email-card">
                        <i class="fas fa-envelope"></i>
                        <h4><?php echo htmlspecialchars($dept['title']); ?></h4>
                        <a href="mailto:<?php echo htmlspecialchars($dept['email']); ?>"><?php echo htmlspecialchars($dept['email']); ?></a>
                        <p>Direct departmental contact</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="map-section">
    <!-- Leaflet.js Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div id="scms-map" style="width:100%;height:100%;min-height:450px;"></div>
    <div class="map-info" style="z-index: 1005;">
        <div>
            <h4><i class="fas fa-map-marker-alt me-2" style="color:var(--primary-color);"></i>Visit SCMS</h4>
            <p><?php echo htmlspecialchars($college['address']); ?></p>
        </div>
        <a href="<?php $mu = !empty($college['maps_url']) && strpos($college['maps_url'], 'http') === 0 ? $college['maps_url'] : 'https://www.google.com/maps/search/?api=1&query=34.346169,71.788426'; echo htmlspecialchars($mu); ?>" target="_blank" rel="noopener" class="btn btn-primary-custom">Open in Google Maps</a>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    (function() {
        var lat = <?php echo json_encode(floatval($college['latitude'] ?? 34.346169)); ?>;
        var lng = <?php echo json_encode(floatval($college['longitude'] ?? 71.788426)); ?>;
        var name = <?php echo json_encode($college['name'] ?? 'Safi College of Medical Sciences'); ?>;
        var address = <?php echo json_encode($college['address'] ?? 'Harichand Road, Mandani, Charsadda, Khyber Pakhtunkhwa, Pakistan'); ?>;

        var map = L.map('scms-map', { zoomControl: true, scrollWheelZoom: false }).setView([lat, lng], 17);

        // Tile Layers
        var googleHybrid = L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '&copy; Google Maps'
        });

        var googleTerrain = L.tileLayer('https://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '&copy; Google Maps'
        });

        var googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '&copy; Google Maps'
        });

        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        });

        // Set Google Hybrid (Satellite + Terrain + Labels) as default
        googleHybrid.addTo(map);

        // Layer switcher control
        var baseMaps = {
            "Satellite & Terrain": googleHybrid,
            "Google Terrain": googleTerrain,
            "Google Roadmap": googleStreets,
            "OpenStreetMap": osm
        };
        L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

        var icon = L.divIcon({
            className: '',
            html: '<div class="scms-map-pin-wrap"><div class="scms-map-pin-pulse"></div><div class="scms-map-pin-inner"><i class="fas fa-graduation-cap"></i></div></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 36],
            popupAnchor: [0, -36]
        });

        var mapsUrl = <?php echo json_encode($mu); ?>;

        var popupContent = ''
            + '<div class="scms-popup-card">'
            + '  <div class="scms-popup-header">'
            + '    <span class="scms-popup-badge"><i class="fas fa-university me-1"></i>Main Campus</span>'
            + '    <h5 style="margin-top:0.35rem;">' + name + '</h5>'
            + '  </div>'
            + '  <div class="scms-popup-body">'
            + '    <p><i class="fas fa-map-marker-alt me-1" style="color:var(--primary-color);"></i>' + address + '</p>'
            + '    <a href="' + mapsUrl + '" target="_blank" rel="noopener"><i class="fas fa-directions me-1"></i>Get Directions</a>'
            + '  </div>'
            + '</div>';

        var marker = L.marker([lat, lng], { icon: icon }).addTo(map);
        marker.bindPopup(popupContent, { maxWidth: 280, closeButton: false }).openPopup();
    })();
    </script>
</section>

<script>
(function () {
    const field = document.getElementById('contact-message');
    const counter = document.getElementById('contact-message-remaining');
    if (!field || !counter) return;
    function updateCounter() {
        const words = field.value.trim() ? field.value.trim().split(/\s+/u).length : 0;
        counter.textContent = Math.max(0, 100 - words).toLocaleString();
        field.setCustomValidity(words > 100 ? 'Please enter no more than 100 words.' : '');
    }
    field.addEventListener('input', updateCounter);
    updateCounter();
}());
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
