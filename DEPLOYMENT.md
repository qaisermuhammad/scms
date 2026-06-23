# SCMS Deployment Notes

## Current state
- The website is a Flask app with SQLite for local data storage and PostgreSQL for production.
- The code currently runs locally through `START_WEBSITE.bat` or `python scms_website/app.py`.
- Production hosting, domain purchase, and DNS are still external steps.

## Recommended domain workflow
1. Buy `scms.edu.pk` or `www.scms.edu.pk` from PKNIC.
2. Deploy the Flask app to Railway.
3. Add PostgreSQL on Railway and set `DATABASE_URL`.
4. Point the domain to Railway with the DNS records Railway provides.
5. Enable HTTPS with the managed certificate Railway provides.
6. Update `scms_website/static/sitemap.xml` and `scms_website/static/robots.txt` if the public URL changes.
7. Set real mail credentials through environment variables:
   - `SCMS_MAIL_SERVER`
   - `SCMS_MAIL_PORT`
   - `SCMS_MAIL_USERNAME`
   - `SCMS_MAIL_PASSWORD`
   - `SCMS_MAIL_DEFAULT_SENDER`
   - Default contact email: `scms.charsadda@gmail.com`
   - Default contact number: `+92 333 0926111`

## Production recommendations
- Use a real database backup strategy once the site is hosted.
- Keep `SECRET_KEY` and mail credentials out of source control.
- Run behind a production WSGI server and HTTPS reverse proxy.
- Add migration handling before making schema changes in production.
- Keep production data in PostgreSQL, not SQLite.

## Website Credit
- Website developed by Qaiser Muhammad Abdur Rehman.
- Email: qaisermuhammad@hotmail.com
- Facebook: www.facebook.com/qaisermuhammad
