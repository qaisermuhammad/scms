<div align="center">
  <img src="assets/images/logo.jpg" alt="Safi College of Medical Sciences logo" width="110">
  <h1>Safi College of Medical Sciences</h1>
  <p><strong>Education for a healthier tomorrow.</strong></p>
  <p>A college website and admissions workspace for SCMS, Mandani, Charsadda.</p>
  <p>
    <a href="https://www.scms.edu.pk/">Visit the website</a> ·
    <a href="#getting-started">Get started</a> ·
    <a href="#deployment">Deploy</a> ·
    <a href="#data-and-security">Data and security</a>
  </p>
  <p><img src="https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white" alt="PHP 8.1 or newer"> <img src="https://img.shields.io/badge/Database-SQLite-003B57?logo=sqlite&logoColor=white" alt="SQLite"> <img src="https://img.shields.io/badge/Interface-Bootstrap-7952B3?logo=bootstrap&logoColor=white" alt="Bootstrap"></p>
</div>

---

## A website for the whole college

The public site introduces SCMS, its programs, faculty, facilities, and campus life. The admin area gives staff a place to maintain content and review inquiries and applications.

| For visitors | For administrators |
| --- | --- |
| Explore programs, admissions, scholarships, news, and the gallery | Review, add, edit, and delete applications |
| Submit an online application and receive a reference number | View contact messages and manage website content |
| Contact the college and download the prospectus | See dashboard counts and recent activity |

> **Prospectus:** The included PDF is a placeholder. Replace `assets/documents/prospectus.pdf` with the approved prospectus before relying on it for admissions.

## Getting started

### Requirements

- PHP **8.1 or newer**, with `pdo_sqlite`, `sqlite3`, `mbstring`, and `gd` enabled.
- A PHP-capable web server such as Apache in XAMPP.
- Write access for the web server to `data/` and, for gallery uploads, `assets/images/`.

### Run locally with XAMPP

1. Place the project in `C:\xampp\htdocs\scms` and start Apache.
2. Open `http://localhost/scms/`.
3. Open `http://localhost/scms/admin/login.php` to access the admin area after configuring credentials.

The first application submission creates a SQLite database in `data/`. Other site content and messages may be stored as JSON in the same directory. Runtime data is excluded from Git, so a fresh checkout does not include existing applications or custom content.

## Admin access

Create a PHP credential file **outside the web root** named `scms-admin-credentials.php`. For a standard cPanel installation at `/home/ACCOUNT/public_html`, place it at `/home/ACCOUNT/scms-admin-credentials.php`:

```php
<?php
return [
    'username' => 'admin',
    'password_hash' => 'REPLACE_WITH_A_PASSWORD_HASH',
];
```

Generate the hash with PHP's `password_hash()` function and keep the password and credential file private. The admin sidebar includes **Change Password**; the PHP process must be able to write to the protected credential directory for that feature to work. Alternatively, configure `SCMS_ADMIN_USERNAME` and `SCMS_ADMIN_PASSWORD_HASH` in the server environment. When the password is managed by an environment variable, change it in the server configuration instead of the admin page.

On XAMPP, the application also supports the older credential location `C:\xampp\scms-admin-credentials.php`. For other layouts, set `SCMS_ADMIN_CREDENTIAL_FILE` to an absolute path outside the web root.

## Deployment

1. Back up existing website files, uploaded images, and data before replacing a live installation.
2. Upload the site files to the domain's document root, including `.htaccess`. Preserve existing `data/` and uploaded images when updating an existing site.
3. Configure PHP and the protected admin credential file. Confirm `data/` and the gallery upload directory are writable by PHP.
4. Enable HTTPS. Verify that direct web requests to `data/` and `includes/` are denied before accepting submissions.
5. Test login, application submission, contact messages, uploads, and the prospectus download on the hosting server.

The contact form stores messages for the admin team and attempts email delivery through PHP `mail()`. Successful email delivery depends on the server's mail configuration and should be tested independently.

## Data and security

- Applications are stored in SQLite; other editable content and contact messages use JSON files under `data/`.
- `.gitignore` excludes runtime data, databases, credential files, archives, and admin-uploaded gallery images. It does not remove data from old commits, forks, or backups.
- Keep `data/` inaccessible to visitors, use HTTPS, restrict file permissions, and back up data and uploads regularly.
- The percentage-based grade displayed on the application form is **indicative**; the applicant's official institution determines its own grade.

## Tests

The test script uses a temporary copy of the website and does not intentionally modify live content. It requires Python, the `requests` package, and PHP. On Windows with XAMPP:

```powershell
python tools/test_site.py
```

Set `PHP_BINARY` to your PHP executable if it is not at `C:\xampp\php\php.exe`. The script covers selected public pages, admin workflows, form validation, and uploads; it is not a substitute for browser, hosting, email, or security testing.

## Project layout

```text
admin/               Admin dashboard and management pages
assets/              Styles, public images, videos, and prospectus
includes/            Shared layout, application logic, and configuration
data/                Local runtime data (ignored by Git)
tools/               Local maintenance and automated checks
*.php                Public website pages
```

---

<div align="center"><strong>SCMS</strong> · Learning, care, and community</div>
