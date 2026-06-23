from flask import current_app, flash, redirect, render_template, request, send_from_directory, url_for
from flask_mail import Message

from .extensions import db, mail
from .models import CollegeProfile, ContactMessage, Facility, NewsItem, OwnerProfile, Program


def _college():
    return CollegeProfile.query.first()


def _owner():
    return OwnerProfile.query.first()


def register_routes(app):
    @app.context_processor
    def inject_globals():
        return {
            "college": _college(),
            "owner": _owner(),
            "programs": Program.ordered().all(),
        }

    @app.route("/")
    def home():
        college = _college()
        programs = Program.ordered().limit(6).all()
        news = NewsItem.latest().limit(3).all()
        facilities = Facility.ordered().limit(4).all()
        return render_template(
            "index.html",
            college=college,
            programs=programs,
            news=news,
            facilities=facilities,
        )

    @app.route("/about")
    def about():
        return render_template("about.html", college=_college())

    @app.route("/programs")
    def programs():
        return render_template("programs.html", college=_college(), programs=Program.ordered().all())

    @app.route("/program/<program_id>")
    def program_detail(program_id):
        program = db.session.get(Program, program_id)
        if not program:
            return redirect(url_for("programs"))
        return render_template("program_detail.html", college=_college(), program=program)

    @app.route("/faculty")
    def faculty():
        return render_template("faculty.html", college=_college(), faculty=[_owner()])

    @app.route("/facilities")
    def facilities():
        return render_template("facilities.html", college=_college(), facilities=Facility.ordered().all())

    @app.route("/admissions")
    def admissions():
        return render_template("admissions.html", college=_college(), programs=Program.ordered().all())

    @app.route("/news")
    def news():
        return render_template("news.html", college=_college(), news=NewsItem.latest().all())

    @app.route("/contact", methods=["GET", "POST"])
    def contact():
        college = _college()

        if request.method == "POST":
            name = (request.form.get("name") or "").strip()
            email = (request.form.get("email") or "").strip()
            phone = (request.form.get("phone") or "").strip()
            subject = (request.form.get("subject") or "").strip()
            message_text = (request.form.get("message") or "").strip()

            if not all([name, email, subject, message_text]):
                flash("Please complete all required fields.", "error")
                return redirect(url_for("contact"))

            message_row = ContactMessage(
                name=name,
                email=email,
                phone=phone or None,
                subject=subject,
                message=message_text,
                status="received",
            )
            db.session.add(message_row)
            db.session.flush()

            try:
                if not current_app.config.get("MAIL_SUPPRESS_SEND"):
                    msg = Message(
                        subject=f"Contact Form: {subject}",
                        recipients=[college.contact_email],
                        body=(
                            f"Name: {name}\n"
                            f"Email: {email}\n"
                            f"Phone: {phone}\n\n"
                            f"Message:\n{message_text}"
                        ),
                        reply_to=email,
                    )
                    mail.send(msg)
                    message_row.status = "emailed"
                flash("Thank you for contacting us! We will get back to you soon.", "success")
            except Exception:
                message_row.status = "stored"
                flash("Message received. We will contact you shortly.", "info")

            db.session.commit()
            return redirect(url_for("contact"))

        return render_template("contact.html", college=college)

    @app.route("/downloads")
    def downloads():
        return render_template("downloads.html", college=_college())

    @app.route("/scholarships")
    def scholarships():
        return render_template("scholarships.html", college=_college())

    @app.route("/gallery")
    def gallery():
        return render_template("gallery.html", college=_college())

    @app.route("/sitemap.xml")
    def sitemap():
        return send_from_directory(current_app.static_folder, "sitemap.xml")

    @app.route("/robots.txt")
    def robots():
        return send_from_directory(current_app.static_folder, "robots.txt")

    @app.errorhandler(404)
    def page_not_found(e):
        return render_template("404.html", college=_college()), 404
