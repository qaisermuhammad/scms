from .extensions import db
from .models import CollegeProfile, Facility, NewsItem, OwnerProfile, Program


COLLEGE_DATA = {
    "name": "Safi College of Medical Sciences",
    "short_name": "SCMS",
    "tagline": "Excellence in Healthcare Education",
    "location": "Mandani, Charsadda, Khyber Pakhtunkhwa, Pakistan",
    "address": "Mandani Road, Charsadda, KPK, Pakistan",
    "maps_url": "https://maps.app.goo.gl/hqrkXv3j3TMb9wRv6",
    "phone": "+92 333 0926111",
    "mobile": "+92 333 0926111",
    "email": "scms.charsadda@gmail.com",
    "contact_email": "scms.charsadda@gmail.com",
    "admissions_email": "scms.charsadda@gmail.com",
    "principal_email": "scms.charsadda@gmail.com",
    "website": "https://www.scms.edu.pk",
    "facebook_url": "https://www.facebook.com/profile.php?id=61561925787504",
    "established": "2026",
    "affiliation": "Khyber Medical University (KMU), Peshawar",
    "recognition": "Pakistan Nursing Council (PNC) & Higher Education Commission (HEC)",
}

OWNER_DATA = {
    "name": "Dr Siyyar Ahmad Safi",
    "designation": "Director",
    "qualification": "MBBS, FCPS, MHPE",
    "specialty": "ENT and Rhinoplasty Surgeon",
    "image": "drsiyyar.jpeg",
}

PROGRAM_DATA = [
    {
        "id": "bsn-generic",
        "title": "BS Generic",
        "duration": "4 Years",
        "seats": 50,
        "eligibility": "FSc Pre-Medical with at least 50% marks",
        "affiliation": "KMU Peshawar",
        "description": "A comprehensive nursing program designed to produce competent, compassionate, and professional nurses equipped with modern healthcare knowledge and clinical skills.",
        "icon": "fa-user-nurse",
        "color": "primary",
        "sort_order": 1,
        "featured": True,
    },
    {
        "id": "lhv",
        "title": "Lady Health Visitor (LHV)",
        "duration": "TBA",
        "seats": 0,
        "eligibility": "Details to be announced",
        "affiliation": "SCMS",
        "description": "A health-focused program for students interested in community-level maternal and child healthcare services.",
        "icon": "fa-user-doctor",
        "color": "success",
        "sort_order": 2,
        "featured": True,
    },
    {
        "id": "cna",
        "title": "Certified Nursing Assistant (CNA)",
        "duration": "TBA",
        "seats": 0,
        "eligibility": "Details to be announced",
        "affiliation": "SCMS",
        "description": "A practical entry-level nursing support program focused on bedside care, patient assistance, and clinical support skills.",
        "icon": "fa-hand-holding-medical",
        "color": "info",
        "sort_order": 3,
        "featured": True,
    },
]

NEWS_DATA = [
    {
        "title": "Admissions Open Fall 2026",
        "date": "2026-05-20",
        "category": "Admissions",
        "summary": "Applications are now open for all BS programs for the Fall 2026 semester. Last date to apply: July 15, 2026.",
        "link": "#",
        "sort_order": 1,
    },
    {
        "title": "SCMS Signs MOU with KMU Peshawar",
        "date": "2026-04-15",
        "category": "Academic",
        "summary": "Safi College of Medical Sciences has officially signed an affiliation agreement with Khyber Medical University for all degree programs.",
        "link": "#",
        "sort_order": 2,
    },
    {
        "title": "New State-of-the-Art Simulation Lab Inaugurated",
        "date": "2026-03-10",
        "category": "Facilities",
        "summary": "A modern nursing simulation laboratory equipped with high-fidelity mannequins and virtual reality training systems has been inaugurated.",
        "link": "#",
        "sort_order": 3,
    },
    {
        "title": "Free Medical Camp at Mandani",
        "date": "2026-02-28",
        "category": "Community",
        "summary": "SCMS organized a free medical camp providing healthcare services to over 500 patients in Mandani and surrounding areas.",
        "link": "#",
        "sort_order": 4,
    },
]

FACILITY_DATA = [
    {
        "title": "Modern Classrooms",
        "description": "Spacious, air-conditioned classrooms equipped with multimedia projectors, smart boards, and high-speed internet connectivity.",
        "icon": "fa-chalkboard-teacher",
        "sort_order": 1,
    },
    {
        "title": "Advanced Laboratories",
        "description": "State-of-the-art labs for MLT, Radiology, Pharmacy, and Nursing with modern equipment and diagnostic tools.",
        "icon": "fa-flask",
        "sort_order": 2,
    },
    {
        "title": "Simulation Center",
        "description": "High-fidelity nursing simulation lab with patient mannequins, virtual reality training, and clinical skill stations.",
        "icon": "fa-hospital",
        "sort_order": 3,
    },
    {
        "title": "Digital Library",
        "description": "Comprehensive library with thousands of medical textbooks, journals, e-books, and online database access.",
        "icon": "fa-book",
        "sort_order": 4,
    },
    {
        "title": "Clinical Training",
        "description": "Affiliated with top hospitals in Peshawar and Charsadda for hands-on clinical training and internships.",
        "icon": "fa-user-md",
        "sort_order": 5,
    },
    {
        "title": "Hostel Accommodation",
        "description": "Separate, secure hostel facilities for male and female students with mess, Wi-Fi, and recreational areas.",
        "icon": "fa-bed",
        "sort_order": 6,
    },
    {
        "title": "Sports Complex",
        "description": "Indoor and outdoor sports facilities including cricket, football, badminton, and gymnasium.",
        "icon": "fa-running",
        "sort_order": 7,
    },
    {
        "title": "Transport Service",
        "description": "College buses covering major routes in Charsadda, Peshawar, and surrounding areas for student convenience.",
        "icon": "fa-bus",
        "sort_order": 8,
    },
]


def seed_database():
    CollegeProfile.query.delete()
    OwnerProfile.query.delete()
    Program.query.delete()
    NewsItem.query.delete()
    Facility.query.delete()

    db.session.add(CollegeProfile(**COLLEGE_DATA))
    db.session.add(OwnerProfile(**OWNER_DATA))
    db.session.add_all(Program(**item) for item in PROGRAM_DATA)
    db.session.add_all(NewsItem(**item) for item in NEWS_DATA)
    db.session.add_all(Facility(**item) for item in FACILITY_DATA)
    db.session.commit()
