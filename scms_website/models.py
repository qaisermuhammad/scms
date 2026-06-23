from datetime import datetime, timezone

from .extensions import db


class CollegeProfile(db.Model):
    __tablename__ = "college_profile"

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(255), nullable=False)
    short_name = db.Column(db.String(50), nullable=False)
    tagline = db.Column(db.String(255), nullable=False)
    location = db.Column(db.String(255), nullable=False)
    address = db.Column(db.String(255), nullable=False)
    maps_url = db.Column(db.String(255), nullable=False)
    phone = db.Column(db.String(50), nullable=False)
    mobile = db.Column(db.String(50), nullable=False)
    email = db.Column(db.String(255), nullable=False)
    contact_email = db.Column(db.String(255), nullable=False)
    admissions_email = db.Column(db.String(255), nullable=False)
    principal_email = db.Column(db.String(255), nullable=False)
    website = db.Column(db.String(255), nullable=False)
    facebook_url = db.Column(db.String(255), nullable=False)
    established = db.Column(db.String(20), nullable=False)
    affiliation = db.Column(db.String(255), nullable=False)
    recognition = db.Column(db.String(255), nullable=False)


class OwnerProfile(db.Model):
    __tablename__ = "owner_profile"

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(255), nullable=False)
    designation = db.Column(db.String(255), nullable=False)
    qualification = db.Column(db.String(255), nullable=False)
    specialty = db.Column(db.String(255), nullable=False)
    image = db.Column(db.String(255), nullable=False)


class Program(db.Model):
    __tablename__ = "programs"

    id = db.Column(db.String(64), primary_key=True)
    title = db.Column(db.String(255), nullable=False)
    duration = db.Column(db.String(100), nullable=False)
    seats = db.Column(db.Integer, nullable=False)
    eligibility = db.Column(db.String(255), nullable=False)
    affiliation = db.Column(db.String(255), nullable=False)
    description = db.Column(db.Text, nullable=False)
    icon = db.Column(db.String(100), nullable=False)
    color = db.Column(db.String(50), nullable=False)
    sort_order = db.Column(db.Integer, nullable=False, default=0)
    featured = db.Column(db.Boolean, nullable=False, default=False)

    @classmethod
    def ordered(cls):
        return cls.query.order_by(cls.sort_order.asc(), cls.title.asc())

    @classmethod
    def featured_programs(cls, limit=6):
        return cls.ordered().filter_by(featured=True).limit(limit)


class NewsItem(db.Model):
    __tablename__ = "news_items"

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(255), nullable=False)
    date = db.Column(db.String(10), nullable=False)
    category = db.Column(db.String(100), nullable=False)
    summary = db.Column(db.Text, nullable=False)
    link = db.Column(db.String(255), nullable=False, default="#")
    sort_order = db.Column(db.Integer, nullable=False, default=0)

    @classmethod
    def latest(cls):
        return cls.query.order_by(cls.date.desc(), cls.sort_order.asc())


class Facility(db.Model):
    __tablename__ = "facilities"

    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(255), nullable=False)
    description = db.Column(db.Text, nullable=False)
    icon = db.Column(db.String(100), nullable=False)
    sort_order = db.Column(db.Integer, nullable=False, default=0)

    @classmethod
    def ordered(cls):
        return cls.query.order_by(cls.sort_order.asc(), cls.title.asc())


class ContactMessage(db.Model):
    __tablename__ = "contact_messages"

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(255), nullable=False)
    email = db.Column(db.String(255), nullable=False)
    phone = db.Column(db.String(50), nullable=True)
    subject = db.Column(db.String(255), nullable=False)
    message = db.Column(db.Text, nullable=False)
    status = db.Column(db.String(32), nullable=False, default="received")
    created_at = db.Column(
        db.DateTime(timezone=True),
        nullable=False,
        default=lambda: datetime.now(timezone.utc),
    )
