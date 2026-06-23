# Safi College of Medical Sciences - Website

A complete Flask website for **Safi College of Medical Sciences, Mandani, Charsadda, KPK, Pakistan**.

## Website URL

**https://www.scms.edu.pk**

## Features

- Modern, responsive design built with Bootstrap 5 and custom CSS
- Home page with hero section, programs preview, stats counter, news, and CTA
- About page with mission, vision, values, and timeline
- Programs listing with 3 current programs
- Individual program detail pages with fee structure
- Faculty directory
- Facilities showcase with gallery
- Admissions page with process and FAQ
- Contact page with form and email addresses
- News, gallery, scholarships, downloads, and custom 404 pages

## Email Addresses

- scms.charsadda@gmail.com

## Programs Offered

1. BS Generic
2. Lady Health Visitor (LHV)
3. Certified Nursing Assistant (CNA)

## Installation

1. Install dependencies:

```bash
pip install -r requirements.txt
```

2. Configure email settings through environment variables or `scms_website/config.py`:

```python
SCMS_MAIL_SERVER=your-smtp-server
SCMS_MAIL_PORT=587
SCMS_MAIL_USE_TLS=true
SCMS_MAIL_USERNAME=scms.charsadda@gmail.com
SCMS_MAIL_PASSWORD=your-email-password
```

3. Run the application:

```bash
python scms_website/app.py
```

4. Open `http://localhost:5000` in your browser.

## Production Notes

- Local development uses SQLite automatically.
- Production on Railway should use PostgreSQL via the `DATABASE_URL` environment variable.
- The app seeds data only when it is using SQLite locally.

## Directory Structure

```text
scms_website/
|-- app.py
|-- __init__.py
|-- config.py
|-- extensions.py
|-- models.py
|-- routes.py
|-- seed.py
|-- requirements.txt
|-- README.md
|-- tests/
|   `-- test_app.py
|-- templates/
|   |-- base.html
|   |-- 404.html
|   |-- about.html
|   |-- admissions.html
|   |-- contact.html
|   |-- downloads.html
|   |-- faculty.html
|   |-- facilities.html
|   |-- gallery.html
|   |-- index.html
|   |-- news.html
|   |-- program_detail.html
|   |-- programs.html
|   `-- scholarships.html
`-- static/
    |-- images/
    |-- videos/
    |-- robots.txt
    `-- sitemap.xml
```

## Affiliation

- Khyber Medical University (KMU), Peshawar
- Pakistan Nursing Council (PNC)
- Higher Education Commission (HEC)

## Deployment Notes

For production, set `debug=False`, configure real SMTP credentials through a safer configuration method, and run the app behind a production WSGI server and HTTPS reverse proxy.

Copyright 2026 Safi College of Medical Sciences. All Rights Reserved.
