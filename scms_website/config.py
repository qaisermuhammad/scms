import os


class Config:
    SECRET_KEY = os.environ.get("SCMS_SECRET_KEY", "scms-secret-key-2026")
    SQLALCHEMY_DATABASE_URI = os.environ.get("DATABASE_URL") or os.environ.get("SCMS_DATABASE_URI")
    SQLALCHEMY_TRACK_MODIFICATIONS = False
    MAIL_SERVER = os.environ.get("SCMS_MAIL_SERVER", "smtp.gmail.com")
    MAIL_PORT = int(os.environ.get("SCMS_MAIL_PORT", "587"))
    MAIL_USE_TLS = os.environ.get("SCMS_MAIL_USE_TLS", "true").lower() in {
        "1",
        "true",
        "yes",
        "on",
    }
    MAIL_USERNAME = os.environ.get("SCMS_MAIL_USERNAME", "scms.charsadda@gmail.com")
    MAIL_PASSWORD = os.environ.get("SCMS_MAIL_PASSWORD", "your-email-password")
    MAIL_DEFAULT_SENDER = os.environ.get("SCMS_MAIL_DEFAULT_SENDER", "scms.charsadda@gmail.com")
    MAIL_SUPPRESS_SEND = os.environ.get("SCMS_MAIL_SUPPRESS_SEND", "false").lower() in {
        "1",
        "true",
        "yes",
        "on",
    }
