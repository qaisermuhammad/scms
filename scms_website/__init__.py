from pathlib import Path

from flask import Flask

from .config import Config
from .extensions import db, mail
from .routes import register_routes
from .seed import seed_database


def create_app(test_config=None):
    app = Flask(__name__, instance_relative_config=True)
    app.config.from_object(Config)

    if test_config:
        app.config.update(test_config)

    Path(app.instance_path).mkdir(parents=True, exist_ok=True)

    if not app.config.get("SQLALCHEMY_DATABASE_URI"):
        db_path = Path(app.instance_path) / "scms.sqlite3"
        app.config["SQLALCHEMY_DATABASE_URI"] = f"sqlite:///{db_path.as_posix()}"

    db.init_app(app)
    mail.init_app(app)
    register_routes(app)

    with app.app_context():
        db.create_all()
        if app.config["SQLALCHEMY_DATABASE_URI"].startswith("sqlite:///"):
            seed_database()

    return app
