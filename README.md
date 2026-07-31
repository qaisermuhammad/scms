# Safi College of Medical Sciences

Official website for **Safi College of Medical Sciences, Mandani, Charsadda, Khyber Pakhtunkhwa, Pakistan**.

## Overview

This is a Flask-based website for SCMS with a server-rendered frontend, a lightweight database layer, and a contact form for inquiries.

## Key Features

- Home page with college introduction and program highlights
- About page with college profile and director information
- Programs page for the current offerings:
  - BS Generic
  - Lady Health Visitor (LHV)
  - Certified Nursing Assistant (CNA)
- Faculty, facilities, admissions, news, gallery, scholarships, downloads, and contact pages
- Contact form with message storage and email support
- Custom 404 page, sitemap, and robots file

## Tech Stack

- Python 3
- Flask
- Jinja2 templates
- Flask-SQLAlchemy
- Flask-Mail
- SQLite for local development
- PostgreSQL for production
- Bootstrap 5, Font Awesome, and AOS for the UI

## Project Structure

```text
.
|-- app.py
|-- requirements.txt
|-- Procfile
|-- scms_website/
|   |-- __init__.py
|   |-- config.py
|   |-- extensions.py
|   |-- models.py
|   |-- routes.py
|   |-- seed.py
|   |-- tests/
|   |-- templates/
|   `-- static/
|-- README.md
|-- README.txt
`-- DEPLOYMENT.md
```

## Run Locally

1. Install dependencies:

```bash
pip install -r requirements.txt
```

2. Start the app:

```bash
python app.py
```

3. Open:

```text
http://127.0.0.1:5000
```

## Testing

Run the test suite with:

```bash
scms_website\.venv\Scripts\python.exe -m pytest -q
```

## Deployment

- Domain: `scms.edu.pk` via PKNIC
- Hosting: Railway
- Database: PostgreSQL

Production uses `DATABASE_URL` automatically when available. Local development falls back to SQLite.

## Contact

- Email: `scms.charsadda@gmail.com`
- Phone: `+92 333 0926111`

## Developer Credit

Website developed by **Qaiser Muhammad Abdur Rehman**  
Email: `qaisermuhammad@hotmail.com`  
Facebook: `www.facebook.com/qaisermuhammad`
