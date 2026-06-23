from pathlib import Path

import pytest

from scms_website import create_app
from scms_website.extensions import db
from scms_website.models import ContactMessage, CollegeProfile, Program


@pytest.fixture()
def app(tmp_path):
    db_path = tmp_path / "scms_test.sqlite3"
    app = create_app(
        {
            "TESTING": True,
            "SQLALCHEMY_DATABASE_URI": f"sqlite:///{db_path.as_posix()}",
            "MAIL_SUPPRESS_SEND": True,
        }
    )
    yield app


@pytest.fixture()
def client(app):
    return app.test_client()


def test_home_page_loads(client):
    response = client.get("/")
    assert response.status_code == 200
    assert b"Safi College of Medical Sciences" in response.data


def test_database_is_seeded(app):
    with app.app_context():
        assert CollegeProfile.query.count() == 1
        assert Program.query.count() == 3


def test_program_detail_page_loads(client):
    response = client.get("/program/bsn-generic")
    assert response.status_code == 200
    assert b"BS Generic" in response.data


def test_gallery_page_contains_videos(client):
    response = client.get("/gallery")
    assert response.status_code == 200
    assert b"campus-video-9.mp4" in response.data


def test_contact_form_persists_message(client, app):
    response = client.post(
        "/contact",
        data={
            "name": "Test User",
            "email": "test@example.com",
            "phone": "+1-555-0100",
            "subject": "Admission Inquiry",
            "message": "I would like to know more.",
        },
        follow_redirects=True,
    )
    assert response.status_code == 200
    assert b"Thank you for contacting us" in response.data

    with app.app_context():
        assert ContactMessage.query.count() == 1
        saved = ContactMessage.query.first()
        assert saved.name == "Test User"
        assert saved.status in {"received", "stored", "emailed"}


def test_404_handler(client):
    response = client.get("/does-not-exist")
    assert response.status_code == 404
