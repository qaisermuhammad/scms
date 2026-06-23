# SCMS Copilot Instructions

## System Overview
- Project: Safi College of Medical Sciences website.
- Stack: Python 3, Flask, Jinja2, Flask-SQLAlchemy, Flask-Mail, SQLite, Bootstrap 5, Font Awesome, AOS.
- App type: server-rendered website with a single Flask backend and Jinja templates.
- Data layer: SQLite-backed models for college profile, owner profile, programs, news, facilities, and contact messages.
- Current content: 3 programs only - BS Generic, LHV, and CNA.
- Leadership: the director is Dr Siyyar Ahmad Safi.

## Project Intelligence
- `scms_website/app.py`: local startup entry point.
- `scms_website/__init__.py`: app factory, extension wiring, DB creation, seed bootstrap.
- `scms_website/config.py`: environment-based config and mail defaults.
- `scms_website/extensions.py`: shared Flask extensions.
- `scms_website/models.py`: SQLAlchemy models.
- `scms_website/seed.py`: canonical seed data for college profile, director, programs, news, and facilities.
- `scms_website/routes.py`: all routes, contact form handling, sitemap/robots serving.
- `scms_website/templates/base.html`: shared layout, nav, footer, global CSS/JS, flash messages.
- `scms_website/templates/index.html`: home page.
- `scms_website/templates/about.html`: college overview, mission, vision, timeline, and founder profile.
- `scms_website/templates/programs.html` and `program_detail.html`: program catalog and detail pages.
- `scms_website/templates/faculty.html`: director/faculty profile.
- `scms_website/templates/facilities.html`: facilities and gallery-style content.
- `scms_website/templates/admissions.html`: admissions flow, FAQs, program table.
- `scms_website/templates/news.html`: news and announcements.
- `scms_website/templates/gallery.html`: gallery and 9 campus video embeds.
- `scms_website/templates/scholarships.html`: scholarships page.
- `scms_website/templates/downloads.html`: download placeholders.
- `scms_website/templates/contact.html`: contact page and form target.
- `scms_website/templates/404.html`: custom 404 page.
- `scms_website/static/images/`: logo, profile image, founder image, favicon.
- `scms_website/static/videos/`: `campus-video-1.mp4` through `campus-video-9.mp4`.
- `scms_website/static/sitemap.xml` and `robots.txt`: SEO files.
- `scms_website/tests/test_app.py`: smoke tests for routes, database seeding, and contact form persistence.
- Root helpers: `START_WEBSITE.bat` for Windows launch, `DEPLOYMENT.md` for deployment notes.

## Operational Commands
- Install deps: `cd scms_website && pip install -r requirements.txt`
- Run locally: `cd scms_website && python app.py`
- Windows launcher: `START_WEBSITE.bat`
- Run tests: `scms_website\\.venv\\Scripts\\python.exe -m pytest -q`
- Quick manual check: browse `http://127.0.0.1:5000`
- No migration tooling yet: schema is created on startup with `db.create_all()`.

## Development Standards
- Prefer server-rendered Flask routes and Jinja templates.
- Keep shared UI in `templates/base.html` unless a page truly needs local styling.
- Keep canonical content in `seed.py` and models, not scattered across templates.
- Keep route names stable and match `url_for(...)` references exactly.
- Preserve the green medical/academic visual style and responsive Bootstrap layout.
- Use semantic HTML, alt text, and clear labels for accessibility.
- Store assets in `scms_website/static/` and reference them with `url_for('static', filename=...)`.

## Guardrails
- Never remove the Flask app factory or route registration pattern.
- Never commit secret keys, SMTP passwords, API keys, or registrar credentials.
- Never hardcode production secrets in `app.py`.
- Never rename routes or templates without updating every reference.
- Never remove `START_WEBSITE.bat` unless an equivalent start path replaces it.
- Never use inline styles for shared reusable UI patterns.
- Never delete media files unless all references are updated first.
- Never assume the mailer, videos, or static assets work without checking the files/config.
- Never edit database records manually in a way that drifts from `seed.py` without updating the seed source.

## Learned Corrections
- If content says more than 3 programs, it is stale unless the seed data changes too.
- If the director is referenced, use `Dr Siyyar Ahmad Safi` with title `Director`.
- If a request mentions backend/database/testing, treat it as a real implementation task.
- If asked for the current stack, report the real codebase stack, not the desired stack.
- If a domain is added later, document registrar, DNS, SSL, and hosting steps here.

## Domain / Deployment Notes
- Public SEO files currently point to `https://www.scms.edu.pk`.
- The app is local-preview friendly, but production still needs hosting, HTTPS, and live SMTP settings.
- Domain purchase and DNS setup are external tasks, not code changes.
- Keep deployment secrets out of the repo.
