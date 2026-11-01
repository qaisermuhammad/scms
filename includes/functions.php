<?php
function college_profile(): array
{
    $defaults = [
        'name' => 'Safi College of Medical Sciences',
        'short_name' => 'SCMS',
        'tagline' => 'Excellence in Healthcare Education',
        'location' => 'Mandani, Charsadda, Khyber Pakhtunkhwa, Pakistan',
        'address' => 'Harichand Road, Mandani, Charsadda, Khyber Pakhtunkhwa, Pakistan',
        'coordinates' => '34.346169, 71.788426',
        'maps_url' => 'https://www.google.com/maps/search/?api=1&query=34.346169,71.788426',
        'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1651.7!2d71.788426!3d34.346169!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2spk!4v1700000000000!5m2!1sen!2spk',
        'phone' => '+92 333 0926111',
        'mobile' => '+92 333 0926111',
        'email' => 'scms.charsadda@gmail.com',
        'contact_email' => 'scms.charsadda@gmail.com',
        'admissions_email' => 'scms.charsadda@gmail.com',
        'principal_email' => 'scms.charsadda@gmail.com',
        'website' => 'https://www.scms.edu.pk',
        'facebook_url' => 'https://www.facebook.com/profile.php?id=61561925787504',
        'established' => '2026',
        'affiliation' => 'Khyber Medical University (KMU), Peshawar',
        'recognition' => 'Pakistan Nursing Council (PNC) & Higher Education Commission (HEC)',
    ];

    $file = __DIR__ . '/../data/settings.json';
    if (file_exists($file)) {
        $json = file_get_contents($file);
        if ($json !== false) {
            $stored = json_decode($json, true);
            if (is_array($stored)) {
                return array_merge($defaults, $stored);
            }
        }
    }
    return $defaults;
}

function site_name(): string
{
    return college_profile()['name'];
}

function site_tagline(): string
{
    return college_profile()['tagline'];
}

function site_read_json(string $file, array $fallback): array
{
    if (!file_exists($file)) return $fallback;
    $json = file_get_contents($file);
    if ($json === false) return $fallback;
    $data = json_decode($json, true);
    return is_array($data) ? $data : $fallback;
}

function programs_data(): array
{
    $defaults = [
        [
            'id' => 'bsn-generic',
            'title' => 'BS Generic Nursing',
            'duration' => '4 Years',
            'seats' => 50,
            'eligibility' => 'FSc Pre-Medical with at least 50% marks',
            'affiliation' => 'KMU Peshawar',
            'description' => 'A comprehensive four-year nursing program designed to produce competent, compassionate, and professional nurses equipped with modern healthcare knowledge and clinical skills. Affiliated with KMU Peshawar and recognized by PNC.',
            'icon' => 'fa-user-nurse',
        ],
        [
            'id' => 'bsn-nursing',
            'title' => 'BS Nursing (Post RN)',
            'duration' => '2 Years',
            'seats' => 30,
            'eligibility' => 'Registered Nurse with Diploma in Nursing',
            'affiliation' => 'KMU Peshawar',
            'description' => 'An advanced nursing program for registered nurses seeking to upgrade their qualifications to a bachelor\'s degree. Focuses on leadership, research, and advanced clinical practice.',
            'icon' => 'fa-user-nurse',
        ],
        [
            'id' => 'lhv',
            'title' => 'Lady Health Visitor (LHV)',
            'duration' => '2 Years',
            'seats' => 25,
            'eligibility' => 'Matric with Science (Biology) minimum 50% marks',
            'affiliation' => 'PNC / KMU Peshawar',
            'description' => 'A community health-focused program training women to provide maternal and child healthcare services at the grassroots level. LHV graduates serve as frontline healthcare workers in rural communities.',
            'icon' => 'fa-user-doctor',
        ],
        [
            'id' => 'cna',
            'title' => 'Certified Nursing Assistant (CNA)',
            'duration' => '1 Year',
            'seats' => 20,
            'eligibility' => 'Matric with Science (Biology)',
            'affiliation' => 'SCMS / PNC',
            'description' => 'A practical entry-level nursing support program focused on bedside care, patient assistance, vital signs monitoring, and clinical support skills. Ideal for students starting their healthcare careers.',
            'icon' => 'fa-hand-holding-medical',
        ],
    ];
    $data = site_read_json(__DIR__ . '/../data/programs.json', $defaults);
    usort($data, function ($a, $b) {
        return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
    });
    return $data;
}

function news_data(): array
{
    $defaults = [
        [
            'title' => 'HERA Inspection: SCMS Commended as Premier Nursing Institution in KPK',
            'date' => '2026-07-31',
            'category' => 'Accreditation',
            'summary' => 'The Higher Education Regulatory Authority (HERA) team conducted a thorough inspection and commended Safi College of Medical Sciences, Mandani, as one of the premier nursing institutions in Khyber Pakhtunkhwa.',
            'link' => 'hera-inspection.php',
        ],
        [
            'title' => 'Admissions Open Fall 2026',
            'date' => '2026-05-20',
            'category' => 'Admissions',
            'summary' => 'Applications are now open for all BS programs for the Fall 2026 semester. Last date to apply: July 15, 2026.',
            'link' => 'admissions.php',
        ],
        [
            'title' => 'SCMS Signs MOU with KMU Peshawar',
            'date' => '2026-04-15',
            'category' => 'Academic',
            'summary' => 'Safi College of Medical Sciences has officially signed an affiliation agreement with Khyber Medical University for all degree programs.',
            'link' => 'news.php',
        ],
        [
            'title' => 'New State-of-the-Art Simulation Lab Inaugurated',
            'date' => '2026-03-10',
            'category' => 'Facilities',
            'summary' => 'A modern nursing simulation laboratory equipped with high-fidelity mannequins and virtual reality training systems has been inaugurated.',
            'link' => 'facilities.php',
        ],
        [
            'title' => 'Free Medical Camp at Mandani',
            'date' => '2026-02-28',
            'category' => 'Community',
            'summary' => 'SCMS organized a free medical camp providing healthcare services to over 500 patients in Mandani and surrounding areas.',
            'link' => 'news.php',
        ],
    ];

    $data = site_read_json(__DIR__ . '/../data/news.json', $defaults);
    
    // Sorts the news items chronologically (newest first)
    usort($data, function($a, $b) {
        return strtotime($b['date'] ?? '') <=> strtotime($a['date'] ?? '');
    });
    
    return $data;
}


function feature_data(): array
{
    return [
        [
            'title' => 'KMU Affiliated',
            'description' => 'All programs are affiliated with Khyber Medical University, ensuring recognized and valued academic pathways.',
            'icon' => 'fa-certificate',
        ],
        [
            'title' => 'Modern Laboratories',
            'description' => 'State-of-the-art labs and skills spaces support practical learning with up-to-date healthcare equipment.',
            'icon' => 'fa-microscope',
        ],
        [
            'title' => 'Clinical Training',
            'description' => 'Students build confidence through hospital-linked and community-based practical exposure.',
            'icon' => 'fa-user-md',
        ],
        [
            'title' => 'Experienced Faculty',
            'description' => 'Qualified teachers and healthcare professionals guide students academically and professionally.',
            'icon' => 'fa-award',
        ],
        [
            'title' => 'Scholarships',
            'description' => 'Merit-based and need-based opportunities help deserving students continue their education.',
            'icon' => 'fa-hand-holding-usd',
        ],
        [
            'title' => 'Career Support',
            'description' => 'SCMS prepares graduates for meaningful healthcare careers through mentoring and practical readiness.',
            'icon' => 'fa-briefcase',
        ],
    ];
}

function facility_data(): array
{
    $defaults = [
        [
            'title' => 'Modern Classrooms',
            'description' => 'Spacious, air-conditioned classrooms equipped with multimedia projectors, smart boards, and high-speed internet connectivity.',
            'icon' => 'fa-chalkboard-teacher',
        ],
        [
            'title' => 'Advanced Laboratories',
            'description' => 'State-of-the-art labs for MLT, Radiology, Pharmacy, and Nursing with modern equipment and diagnostic tools.',
            'icon' => 'fa-flask',
        ],
        [
            'title' => 'Simulation Center',
            'description' => 'High-fidelity nursing simulation lab with patient mannequins, virtual reality training, and clinical skill stations.',
            'icon' => 'fa-hospital',
        ],
        [
            'title' => 'Digital Library',
            'description' => 'Comprehensive library with thousands of medical textbooks, journals, e-books, and online database access.',
            'icon' => 'fa-book',
        ],
        [
            'title' => 'Clinical Training',
            'description' => 'Affiliated with top hospitals in Peshawar and Charsadda for hands-on clinical training and internships.',
            'icon' => 'fa-user-md',
        ],
        [
            'title' => 'Hostel Accommodation',
            'description' => 'Separate, secure hostel facilities for male and female students with mess, Wi-Fi, and recreational areas.',
            'icon' => 'fa-bed',
        ],
        [
            'title' => 'Sports Complex',
            'description' => 'Indoor and outdoor sports facilities including cricket, football, badminton, and gymnasium.',
            'icon' => 'fa-running',
        ],
        [
            'title' => 'Transport Service',
            'description' => 'College buses covering major routes in Charsadda, Peshawar, and surrounding areas for student convenience.',
            'icon' => 'fa-bus',
        ],
    ];
    $data = site_read_json(__DIR__ . '/../data/facilities.json', $defaults);
    usort($data, function ($a, $b) {
        return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
    });
    return $data;
}

function owner_profile_data(): array
{
    return [
        'name' => 'Dr Siyyar Ahmad Safi',
        'designation' => 'Director',
        'qualification' => 'MBBS, FCPS, MHPE',
        'specialty' => 'ENT and Rhinoplasty Surgeon',
        'image' => 'drsiyyar.jpg',
    ];
}

function faculty_data(): array
{
    $defaults = [
        [
            'name' => 'Dr Siyyar Ahmad Safi',
            'designation' => 'Director & Founder',
            'qualification' => 'MBBS, FCPS (ENT), MHPE',
            'experience' => '15+ Years in Medical Education & Practice',
            'department' => 'Administration & Academic Leadership',
            'image' => 'drsiyyar.jpg',
            'bio' => 'Dr. Siyyar Ahmad Safi is an ENT and Rhinoplasty Surgeon with extensive experience in medical education. He founded SCMS with the vision of bringing quality healthcare education to Charsadda and surrounding areas.',
        ],
        [
            'name' => 'Prof. Ahmad Khan',
            'designation' => 'Principal (Academic Affairs)',
            'qualification' => 'MSN, BScN, PGD Health Education',
            'experience' => '12+ Years in Nursing Education',
            'department' => 'BS Generic & Nursing Sciences',
            'image' => 'faculty_team_1.jpg',
            'bio' => 'Prof. Ahmad Khan brings over a decade of nursing education experience, specializing in medical-surgical nursing and curriculum development. He has served at multiple leading nursing institutions in KPK.',
        ],
        [
            'name' => 'Dr. Fatima Bibi',
            'designation' => 'Head of Clinical Training',
            'qualification' => 'PhD Nursing, MSN, BScN',
            'experience' => '10+ Years in Clinical Nursing & Research',
            'department' => 'Clinical Skills & Practical Training',
            'image' => 'faculty_team_2.jpg',
            'bio' => 'Dr. Fatima Bibi leads the clinical training division at SCMS, overseeing hospital placements, simulation lab training, and student mentorship. Her research focuses on community health nursing.',
        ],
        [
            'name' => 'Mr. Muhammad Ismail',
            'designation' => 'LHV Program Coordinator',
            'qualification' => 'LHV, Diploma in Community Health',
            'experience' => '8+ Years in Community Health',
            'department' => 'Lady Health Visitor & Community Programs',
            'image' => '',
            'bio' => 'Mr. Muhammad Ismail coordinates the LHV program, bringing practical field experience from community health settings across Charsadda and Peshawar districts.',
        ],
        [
            'name' => 'Ms. Shabana Gul',
            'designation' => 'CNA Program Coordinator',
            'qualification' => 'BScN, Certified Nursing Educator',
            'experience' => '6+ Years in Patient Care Training',
            'department' => 'Certified Nursing Assistant Program',
            'image' => '',
            'bio' => 'Ms. Shabana Gul leads the CNA program with hands-on training in patient care, bedside nursing, and clinical assistance. She ensures students develop strong foundational skills.',
        ],
        [
            'name' => 'Clinical Skills Team',
            'designation' => 'Practical Training Supervisors',
            'qualification' => 'Registered Nurses & Allied Health Professionals',
            'experience' => 'Combined 20+ Years Clinical Experience',
            'department' => 'Labs, Skills & Clinical Placement',
            'image' => '',
            'bio' => 'A dedicated team of registered nurses and healthcare professionals who supervise practical training, lab sessions, and hospital placements for all SCMS programs.',
        ],
    ];
    $data = site_read_json(__DIR__ . '/../data/faculty.json', $defaults);
    usort($data, function ($a, $b) {
        return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
    });
    return $data;
}

function about_values_data(): array
{
    return [
        ['title' => 'Integrity', 'description' => 'We uphold honesty, transparency, and ethical conduct in every academic and professional responsibility.'],
        ['title' => 'Excellence', 'description' => 'We pursue high standards in teaching, clinical preparation, student support, and institutional growth.'],
        ['title' => 'Discipline', 'description' => 'We believe disciplined learning builds confident professionals ready for responsible healthcare service.'],
        ['title' => 'Empathy', 'description' => 'We encourage compassionate care and respect for patients, families, colleagues, and communities.'],
        ['title' => 'Service', 'description' => 'We prepare students to contribute meaningfully to society through healthcare knowledge and action.'],
        ['title' => 'Leadership', 'description' => 'We nurture the confidence and responsibility needed to lead in educational and healthcare environments.'],
    ];
}

function about_timeline_data(): array
{
    return [
        ['year' => '2026', 'title' => 'College Established', 'description' => 'SCMS began its journey with the vision of building a strong center for healthcare education in Mandani, Charsadda.'],
        ['year' => '2026', 'title' => 'Academic Planning', 'description' => 'Programs, facilities, and institutional foundations were organized to support quality teaching and professional training.'],
        ['year' => '2026', 'title' => 'Recognition Goals', 'description' => 'The college aligned itself with recognized academic and regulatory standards in healthcare education.'],
        ['year' => '2026', 'title' => 'Program Launch', 'description' => 'SCMS prepared BS Generic, LHV, and CNA pathways to serve students seeking careers in healthcare.'],
        ['year' => 'Future', 'title' => 'Continued Growth', 'description' => 'The institution continues working toward expanded facilities, stronger training opportunities, and wider academic impact.'],
    ];
}

function admissions_steps_data(): array
{
    return [
        ['title' => 'Choose a Program', 'description' => 'Review available SCMS programs and decide which academic path fits your interest and eligibility.'],
        ['title' => 'Prepare Documents', 'description' => 'Collect academic certificates, CNIC or B-Form, photographs, and any additional supporting documents.'],
        ['title' => 'Submit Inquiry or Application', 'description' => 'Contact the admissions office for guidance and complete the application process within the official schedule.'],
        ['title' => 'Verification and Review', 'description' => 'The college reviews eligibility, academic records, and required documents before final selection.'],
        ['title' => 'Confirm Admission', 'description' => 'Selected students complete fee submission and final registration according to SCMS instructions.'],
    ];
}

function admissions_faq_data(): array
{
    return [
        ['question' => 'Which programs are currently offered?', 'answer' => 'SCMS currently highlights BS Generic, Lady Health Visitor (LHV), and Certified Nursing Assistant (CNA) pathways.'],
        ['question' => 'How can I apply for admission?', 'answer' => 'You can contact the college directly through the contact page or admissions office to receive the latest admission guidance and timelines.'],
        ['question' => 'Are scholarships available?', 'answer' => 'SCMS shares scholarship opportunities as they are announced. Eligibility may depend on merit, need, or special categories.'],
        ['question' => 'Is hostel facility available?', 'answer' => 'Student support facilities continue to develop, and the college shares accommodation guidance when available.'],
        ['question' => 'Are programs affiliated or recognized?', 'answer' => 'SCMS highlights affiliation with KMU Peshawar and recognition aligned with PNC and HEC-related standards.'],
    ];
}

function scholarship_types_data(): array
{
    $defaults = [
        ['title' => 'Merit-Based Scholarship', 'description' => 'Supports academically outstanding students who demonstrate high marks and consistent educational performance.'],
        ['title' => 'Need-Based Scholarship', 'description' => 'Helps deserving students facing financial challenges continue their education with institutional support.'],
        ['title' => 'SCMS Excellence Award', 'description' => 'Recognizes students who show academic distinction, discipline, and strong professional potential.'],
        ['title' => 'Women in Healthcare Support', 'description' => 'Encourages female students pursuing healthcare education and leadership in community service.'],
        ['title' => 'Sports and Co-Curricular Support', 'description' => 'May assist students who contribute positively to extracurricular life and student engagement.'],
        ['title' => 'Sibling Discount Opportunity', 'description' => 'Provides support for families with more than one student studying at SCMS, subject to policy.'],
    ];
    $data = site_read_json(__DIR__ . '/../data/scholarships.json', $defaults);
    usort($data, function ($a, $b) {
        return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
    });
    return $data;
}

function scholarship_eligibility_data(): array
{
    $defaults = [
        'Strong academic record in the most recent examination',
        'Required financial documents for need-based categories',
        'Good conduct, regular attendance, and responsible behavior',
        'Satisfactory academic performance during the program',
        'One scholarship policy may apply where multiple categories overlap',
    ];
    return site_read_json(__DIR__ . '/../data/scholarship_eligibility.json', $defaults);
}

function download_groups_data(): array
{
    $defaults = [
        [
            'title' => 'Admission Documents',
            'items' => [
                ['label' => 'College Profile', 'meta' => 'Available now', 'href' => './assets/docs/college_profile.txt', 'available' => true],
                ['label' => 'Admission Guidelines', 'meta' => 'Available now', 'href' => './assets/docs/admission_guidelines.txt', 'available' => true],
                ['label' => 'Application Form', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
                ['label' => 'Scholarship Form', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
            ],
        ],
        [
            'title' => 'Academic Documents',
            'items' => [
                ['label' => 'Academic Calendar 2026', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
                ['label' => 'Course Catalog', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
                ['label' => 'Exam Rules', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
                ['label' => 'Student Handbook', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
            ],
        ],
        [
            'title' => 'Policies and Forms',
            'items' => [
                ['label' => 'Code of Conduct', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
                ['label' => 'Hostel Rules', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
                ['label' => 'Leave Application', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
            ],
        ],
        [
            'title' => 'Affiliation and Recognition',
            'items' => [
                ['label' => 'KMU Affiliation Letter', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
                ['label' => 'PNC Recognition Certificate', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
                ['label' => 'HEC Related Document', 'meta' => 'Coming soon', 'href' => '', 'available' => false],
            ],
        ],
    ];
    $data = site_read_json(__DIR__ . '/../data/downloads.json', $defaults);
    usort($data, function ($a, $b) {
        return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
    });
    foreach ($data as &$group) {
        if (!empty($group['items']) && is_array($group['items'])) {
            usort($group['items'], function ($a, $b) {
                return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
            });
        }
    }
    unset($group);
    return $data;
}




function gallery_normalize_bundle(array $bundle): array
{
    $imageField = $bundle['image'] ?? [];
    $thumbField = $bundle['thumb'] ?? [];

    if (!is_array($imageField)) {
        $imageField = empty($imageField) ? [] : [$imageField];
    }
    if (!is_array($thumbField)) {
        $thumbField = empty($thumbField) ? [] : [$thumbField];
    }

    $images = [];
    $thumbs = [];
    $seen = [];

    foreach ($imageField as $index => $imageValue) {
        $imagePath = trim((string) $imageValue);
        if ($imagePath === '' || !site_gallery_file_exists($imagePath) || isset($seen[$imagePath])) {
            continue;
        }

        $seen[$imagePath] = true;
        $images[] = $imagePath;
        $thumbPath = trim((string) ($thumbField[$index] ?? ''));
        $thumbs[] = site_gallery_file_exists($thumbPath)
            ? $thumbPath
            : (site_gallery_thumb_path_for($imagePath) ?: $imagePath);
    }

    $bundle['image'] = $images;
    $bundle['thumb'] = $thumbs;
    $bundle['created_at'] = (int) ($bundle['created_at'] ?? $bundle['created'] ?? time());
    $bundle['updated_at'] = (int) ($bundle['updated_at'] ?? $bundle['updated'] ?? $bundle['created_at'] ?? time());

    return $bundle;
}

function gallery_is_hera_path(string $path): bool
{
    $normalized = strtolower(str_replace('\\', '/', $path));
    return str_contains($normalized, '/visit/') || str_contains($normalized, 'hera') || str_contains($normalized, 'inspection');
}

function gallery_group_legacy_photos(array $items): array
{
    $campus = [
        'title' => 'Campus Gallery',
        'description' => 'Campus life, events, and student moments.',
        'image' => [],
        'thumb' => [],
        'created_at' => (int) strtotime('2026-05-01'),
        'updated_at' => (int) strtotime('2026-05-01'),
        'sort_order' => PHP_INT_MAX,
    ];

    $hera = [
        'title' => 'HERA Visit Gallery',
        'description' => 'Inspection visits and official HERA review moments.',
        'image' => [],
        'thumb' => [],
        'created_at' => (int) strtotime('2026-07-31'),
        'updated_at' => (int) strtotime('2026-07-31'),
        'sort_order' => PHP_INT_MAX,
    ];

    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $itemTime = (int) ($item['updated_at'] ?? $item['created_at'] ?? 0);
        $itemOrder = isset($item['sort_order']) ? (int) $item['sort_order'] : PHP_INT_MAX;
        if ($itemTime <= 0) {
            $itemTime = isset($item['date']) ? (int) strtotime((string) $item['date']) : 0;
        }

        $images = is_array($item['image'] ?? null) ? ($item['image'] ?? []) : (empty($item['image'] ?? '') ? [] : [$item['image']]);
        $thumbs = is_array($item['thumb'] ?? null) ? ($item['thumb'] ?? []) : (empty($item['thumb'] ?? '') ? [] : [$item['thumb']]);

        foreach ($images as $index => $imagePath) {
            if ($imagePath === '' || $imagePath === null) {
                continue;
            }

            if (gallery_is_hera_path((string) $imagePath)) {
                $path = (string) $imagePath;
                if (site_gallery_file_exists($path) && !in_array($path, $hera['image'], true)) {
                    $hera['image'][] = $path;
                    $hera['thumb'][] = $thumbs[$index] ?? site_gallery_thumb_path_for($path) ?: $path;
                }
                if ($itemTime > 0) {
                    $hera['updated_at'] = max((int) $hera['updated_at'], $itemTime);
                    $hera['created_at'] = min((int) $hera['created_at'], $itemTime);
                }
                $hera['sort_order'] = min((int) $hera['sort_order'], $itemOrder);
            } else {
                $path = (string) $imagePath;
                if (site_gallery_file_exists($path) && !in_array($path, $campus['image'], true)) {
                    $campus['image'][] = $path;
                    $campus['thumb'][] = $thumbs[$index] ?? site_gallery_thumb_path_for($path) ?: $path;
                }
                if ($itemTime > 0) {
                    $campus['updated_at'] = max((int) $campus['updated_at'], $itemTime);
                    $campus['created_at'] = min((int) $campus['created_at'], $itemTime);
                }
                $campus['sort_order'] = min((int) $campus['sort_order'], $itemOrder);
            }
        }
    }

    $groups = [];
    if (!empty($campus['image'])) {
        $groups[] = gallery_normalize_bundle($campus);
    }
    if (!empty($hera['image'])) {
        $groups[] = gallery_normalize_bundle($hera);
    }

    usort($groups, function($a, $b) {
        if (($a['sort_order'] ?? PHP_INT_MAX) !== ($b['sort_order'] ?? PHP_INT_MAX)) {
            return (($a['sort_order'] ?? PHP_INT_MAX) <=> ($b['sort_order'] ?? PHP_INT_MAX));
        }
        return (($b['updated_at'] ?? $b['created_at'] ?? 0) <=> ($a['updated_at'] ?? $a['created_at'] ?? 0));
    });

    return $groups;
}

function gallery_photos_data(): array
{
    $defaults = [
        ['title' => 'Main Campus Building', 'description' => 'The heart of SCMS education.', 'image' => './assets/images/profile.jpg'],
        ['title' => 'Campus Aerial View', 'description' => 'Beautiful campus surrounded by greenery.', 'image' => './assets/images/campus_1.jpg'],
        ['title' => 'Campus Facilities', 'description' => 'Modern infrastructure for quality education.', 'image' => './assets/images/campus_2.jpg'],
        ['title' => 'Student Activities', 'description' => 'Engaging campus life at SCMS.', 'image' => './assets/images/campus_3.jpg'],
        ['title' => 'Clinical Training', 'description' => 'Hands-on practice in real healthcare settings.', 'image' => './assets/images/campus_4.jpg'],
        ['title' => 'Campus Grounds', 'description' => 'Spacious campus grounds for outdoor learning.', 'image' => './assets/images/campus_5.jpg'],
        ['title' => 'Student Gathering', 'description' => 'Students collaborating on campus.', 'image' => './assets/images/campus_6.jpg'],
        ['title' => 'College Events', 'description' => 'Academic and cultural events at SCMS.', 'image' => './assets/images/campus_7.jpg'],
        ['title' => 'Campus Life', 'description' => 'Day-to-day life at Safi College.', 'image' => './assets/images/campus_8.jpg'],
        ['title' => 'Healthcare Training', 'description' => 'Practical nursing skills training.', 'image' => './assets/images/campus_9.jpg'],
        ['title' => 'Community Outreach', 'description' => 'Serving the local community.', 'image' => './assets/images/campus_10.jpg'],
        ['title' => 'HERA Inspection Team', 'description' => 'Regulatory authority team visiting Safi College.', 'image' => './assets/images/visit/3visit.jpeg'],
        ['title' => 'HERA Visit 1621', 'description' => 'Official HERA inspection visit at SCMS.', 'image' => './assets/images/visit/Weixin Image_20260731164805_1621_25.jpg'],
        ['title' => 'HERA Visit 1622', 'description' => 'Official HERA inspection visit at SCMS.', 'image' => './assets/images/visit/Weixin Image_20260731164806_1622_25.jpg'],
        ['title' => 'HERA Visit 1623', 'description' => 'Official HERA inspection visit at SCMS.', 'image' => './assets/images/visit/Weixin Image_20260731164807_1623_25.jpg'],
        ['title' => 'HERA Visit 1624', 'description' => 'Official HERA inspection visit at SCMS.', 'image' => './assets/images/visit/Weixin Image_20260731164808_1624_25.jpg'],
        ['title' => 'HERA Visit 1625', 'description' => 'Official HERA inspection visit at SCMS.', 'image' => './assets/images/visit/Weixin Image_20260731164809_1625_25.jpg'],
        ['title' => 'Expert Faculty', 'description' => 'Our dedicated and highly qualified teaching staff.', 'image' => './assets/images/faculty_team_1.jpg'],
        ['title' => 'Mentorship & Guidance', 'description' => 'Committed to shaping the future of healthcare professionals.', 'image' => './assets/images/faculty_team_2.jpg'],
    ];

    $saved = site_read_json(__DIR__ . '/../data/gallery_photos.json', []);
    if (!empty($saved)) {
        $legacyTitles = [
            'Main Campus Building', 'Campus Aerial View', 'Campus Facilities',
            'Student Activities', 'Clinical Training', 'Campus Grounds',
            'Student Gathering', 'College Events', 'Campus Life',
            'Healthcare Training', 'Community Outreach', 'Expert Faculty',
            'Mentorship & Guidance', 'HERA Inspection Team', 'Campus Review',
            'Official Visit', 'Academic Excellence', 'Faculty Interaction',
        ];
        $legacyItems = [];
        $savedBundles = [];
        foreach ($saved as $item) {
            if (!is_array($item)) {
                continue;
            }
            $isAdminBundle = !empty($item['admin_bundle']);
            if (!$isAdminBundle && in_array((string) ($item['title'] ?? ''), $legacyTitles, true)) {
                $legacyItems[] = $item;
            } else {
                $normalized = gallery_normalize_bundle($item);
                if (!empty($normalized['image'])) {
                    $savedBundles[] = $normalized;
                }
            }
        }

        if (!empty($legacyItems)) {
            $savedBundles = array_merge($savedBundles, gallery_group_legacy_photos($legacyItems));
        }

        $fallbackGroups = gallery_group_legacy_photos($defaults);
        $savedTitles = array_map(fn($bundle) => $bundle['title'] ?? '', $savedBundles);
        foreach ($fallbackGroups as $fallbackGroup) {
            if (!in_array($fallbackGroup['title'] ?? '', $savedTitles, true)) {
                $savedBundles[] = $fallbackGroup;
            }
        }

        usort($savedBundles, function ($a, $b) {
            if (isset($a['sort_order']) || isset($b['sort_order'])) {
                $aOrder = (int) ($a['sort_order'] ?? PHP_INT_MAX);
                $bOrder = (int) ($b['sort_order'] ?? PHP_INT_MAX);
                if ($aOrder !== $bOrder) {
                    return $aOrder <=> $bOrder;
                }
            }
            return ((int) ($b['updated_at'] ?? $b['created_at'] ?? 0)) <=> ((int) ($a['updated_at'] ?? $a['created_at'] ?? 0));
        });
        return $savedBundles;
    }

    return gallery_group_legacy_photos($defaults);
}

/**
 * Flatten gallery bundles into one card per photo, with thumb for grid + full for lightbox.
 */
function gallery_photos_for_display(): array
{
    $items = [];
    $batches = array_map('gallery_normalize_bundle', gallery_photos_data());
    usort($batches, function($a, $b) {
        return (($b['updated_at'] ?? $b['created_at'] ?? 0) <=> ($a['updated_at'] ?? $a['created_at'] ?? 0));
    });

    foreach ($batches as $photo) {
        $images = $photo['image'] ?? '';
        $thumbs = $photo['thumb'] ?? null;

        $imageList = is_array($images) ? $images : (empty($images) ? [] : [$images]);
        $thumbList = is_array($thumbs) ? $thumbs : (empty($thumbs) ? [] : [$thumbs]);

        foreach ($imageList as $i => $imagePath) {
            if ($imagePath === '' || $imagePath === null) {
                continue;
            }
            $thumbPath = $thumbList[$i] ?? null;
            if (!$thumbPath) {
                $thumbPath = site_gallery_thumb_path_for($imagePath);
            }
            $items[] = [
                'title' => $photo['title'] ?? '',
                'description' => $photo['description'] ?? '',
                'image' => $imagePath,
                'thumb' => $thumbPath ?: $imagePath,
            ];
        }
    }
    return $items;
}

/** Relative web path for a matching _thumb.jpg next to a gallery image, if it exists. */
function site_gallery_thumb_path_for(string $imageWebPath): ?string
{
    $web = str_replace('\\', '/', $imageWebPath);
    if (strpos($web, './') === 0) {
        $web = substr($web, 2);
    }
    $abs = dirname(__DIR__) . '/' . ltrim($web, '/');
    $info = pathinfo($abs);
    if (empty($info['dirname']) || empty($info['filename'])) {
        return null;
    }
    $thumbAbs = $info['dirname'] . '/' . $info['filename'] . '_thumb.jpg';
    if (!is_file($thumbAbs)) {
        return null;
    }
    $rel = str_replace('\\', '/', substr($thumbAbs, strlen(dirname(__DIR__)) + 1));
    return './' . $rel;
}

function site_gallery_file_exists(string $imageWebPath): bool
{
    if ($imageWebPath === '') {
        return false;
    }

    $web = str_replace('\\', '/', $imageWebPath);
    while (strpos($web, '../') === 0) {
        $web = substr($web, 3);
    }
    if (strpos($web, './') === 0) {
        $web = substr($web, 2);
    }

    return is_file(dirname(__DIR__) . '/' . ltrim($web, '/'));
}

/**
 * Save an uploaded phone/camera photo as compressed full + thumbnail JPEGs.
 * Accepts large originals (~10MB); stores ~100–250KB full and ~30–60KB thumb.
 *
 * @return array{image:string,thumb:string}|null
 */
function site_save_gallery_upload(string $tmpPath, string $uploadDir, string $baseName): ?array
{
    if (!extension_loaded('gd') || !is_file($tmpPath)) {
        return null;
    }

    $uploadDir = rtrim(str_replace('\\', '/', $uploadDir), '/') . '/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        return null;
    }

    $fullPath = $uploadDir . $baseName . '.jpg';
    $thumbPath = $uploadDir . $baseName . '_thumb.jpg';

    if (!site_resize_image_to_jpeg($tmpPath, $fullPath, 1600, 82)) {
        return null;
    }
    if (!site_resize_image_to_jpeg($fullPath, $thumbPath, 640, 75)) {
        // Full image is still usable without a separate thumb
        $thumbPath = $fullPath;
    }

    $webBase = './assets/images/' . $baseName;
    return [
        'image' => $webBase . '.jpg',
        'thumb' => ($thumbPath === $fullPath) ? ($webBase . '.jpg') : ($webBase . '_thumb.jpg'),
    ];
}

/**
 * Resize/recompress any image to JPEG. Handles EXIF orientation when available.
 */
function site_resize_image_to_jpeg(string $sourcePath, string $destPath, int $maxEdge, int $quality): bool
{
    if (!extension_loaded('gd') || !is_file($sourcePath)) {
        return false;
    }

    $info = @getimagesize($sourcePath);
    if ($info === false) {
        return false;
    }

    $src = null;
    switch ($info[2]) {
        case IMAGETYPE_JPEG:
            $src = @imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $src = @imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $src = @imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            if (function_exists('imagecreatefromwebp')) {
                $src = @imagecreatefromwebp($sourcePath);
            }
            break;
    }
    if (!$src) {
        return false;
    }

    // Fix phone camera rotation from EXIF
    if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($sourcePath);
        if (!empty($exif['Orientation'])) {
            switch ((int) $exif['Orientation']) {
                case 3:
                    $src = imagerotate($src, 180, 0);
                    break;
                case 6:
                    $src = imagerotate($src, -90, 0);
                    break;
                case 8:
                    $src = imagerotate($src, 90, 0);
                    break;
            }
        }
    }

    $width = imagesx($src);
    $height = imagesy($src);
    if ($width < 1 || $height < 1) {
        imagedestroy($src);
        return false;
    }

    $scale = min(1.0, $maxEdge / max($width, $height));
    $newW = max(1, (int) round($width * $scale));
    $newH = max(1, (int) round($height * $scale));

    $dst = imagecreatetruecolor($newW, $newH);
    if (!$dst) {
        imagedestroy($src);
        return false;
    }

    // Flatten transparency onto white for JPEG
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
    imagedestroy($src);

    $ok = imagejpeg($dst, $destPath, $quality);
    imagedestroy($dst);
    return (bool) $ok;
}

/** Delete gallery image + matching thumb from disk when path is under ./assets/. */
function site_delete_gallery_file(string $webPath): void
{
    if ($webPath === '' || strpos($webPath, './assets/') !== 0) {
        return;
    }
    $physical = dirname(__DIR__) . '/' . substr($webPath, 2);
    $filename = pathinfo($physical, PATHINFO_FILENAME);
    if (strpos($filename, 'gallery_') !== 0) {
        return;
    }
    if (is_file($physical)) {
        @unlink($physical);
    }
    $info = pathinfo($physical);
    if (!empty($info['dirname']) && !empty($info['filename'])) {
        $thumb = $info['dirname'] . '/' . $info['filename'] . '_thumb.jpg';
        if (is_file($thumb)) {
            @unlink($thumb);
        }
        // If deleting a thumb entry directly, also try removing the full image
        if (substr($info['filename'], -6) === '_thumb') {
            $full = $info['dirname'] . '/' . substr($info['filename'], 0, -6) . '.jpg';
            if (is_file($full)) {
                @unlink($full);
            }
        }
    }
}





function gallery_videos_data(): array
{
    $videos = [];
    $base = dirname(__DIR__) . '/assets/videos/';
    for ($index = 1; $index <= 9; $index++) {
        $file = $base . 'campus-video-' . $index . '.mp4';
        if (file_exists($file)) {
            $videos[] = [
                'title' => 'Campus Video ' . $index,
                'file' => './assets/videos/campus-video-' . $index . '.mp4',
            ];
        }
    }
    return $videos;
}

function contact_subjects_data(): array
{
    return [
        'Admission Inquiry',
        'Program Information',
        'Fee Structure',
        'Scholarships',
        'General Inquiry',
        'Feedback',
    ];
}

function contact_departments_data(): array
{
    $college = college_profile();
    return [
        ['title' => 'Admissions Office', 'email' => $college['admissions_email']],
        ['title' => 'General Information', 'email' => $college['contact_email']],
        ['title' => 'Principal Office', 'email' => $college['principal_email']],
    ];
}

function format_news_date(string $date): string
{
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return $date;
    }
    return date('M d, Y', $timestamp);
}

function save_contact_message(array $data): bool
{
    $storage_dir = dirname(__DIR__) . '/data';
    if (!is_dir($storage_dir)) {
        mkdir($storage_dir, 0777, true);
    }

    $file = $storage_dir . '/contact_messages.json';
    $handle = @fopen($file, 'c+');
    if (!$handle) return false;
    if (!flock($handle, LOCK_EX)) { fclose($handle); return false; }
    try {
        $json = stream_get_contents($handle);
        $messages = $json === '' ? [] : json_decode($json, true);
        if (!is_array($messages)) return false;
        $messages[] = [
            'id' => bin2hex(random_bytes(8)),
            'timestamp' => date('Y-m-d H:i:s'),
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'subject' => $data['subject'] ?? '',
            'message' => $data['message'] ?? '',
            'status' => 'received',
        ];
        $encoded = json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($encoded === false) return false;
        rewind($handle);
        if (!ftruncate($handle, 0)) return false;
        $written = fwrite($handle, $encoded);
        return $written === strlen($encoded) && fflush($handle);
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function flash_message(string $type, string $message): void
{
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function page_meta_description(): string
{
    $current = basename($_SERVER['PHP_SELF'] ?? 'index.php');
    $descriptions = [
        'index.php' => 'Safi College of Medical Sciences (SCMS) — offering BS Generic, BS Nursing (Post RN), LHV, and CNA programs in Mandani, Charsadda, KPK. Affiliated with KMU Peshawar.',
        'about.php' => 'Learn about Safi College of Medical Sciences, our mission, vision, leadership, and commitment to excellence in healthcare education in KPK.',
        'programs.php' => 'Explore healthcare programs at SCMS: BS Generic Nursing, BS Nursing (Post RN), Lady Health Visitor (LHV), and Certified Nursing Assistant (CNA).',
        'program.php' => 'Detailed program information for SCMS healthcare degrees including eligibility, structure, fees, and career opportunities.',
        'admissions.php' => 'Admissions 2026 at SCMS. Apply for BS Generic, BS Nursing (Post RN), LHV, or CNA programs. Download prospectus and application forms.',
        'faculty.php' => 'Meet the experienced faculty and leadership team at Safi College of Medical Sciences, led by Dr. Siyyar Ahmad Safi.',
        'facilities.php' => 'Discover world-class facilities at SCMS: modern classrooms, advanced labs, simulation center, digital library, hostel, and sports.',
        'news.php' => 'Latest news, announcements, and updates from Safi College of Medical Sciences.',
        'gallery.php' => 'Browse campus photos and videos of Safi College of Medical Sciences, Mandani, Charsadda.',
        'downloads.php' => 'Download admission forms, academic calendars, fee structures, and college documents from SCMS.',
        'scholarships.php' => 'Scholarship opportunities at SCMS: merit-based, need-based, and special category financial support for students.',
        'contact.php' => 'Contact Safi College of Medical Sciences for admissions, program inquiries, and general information.',
        '404.php' => 'Page not found. Navigate back to the SCMS homepage or contact us for assistance.',
    ];
    return $descriptions[$current] ?? $descriptions['index.php'];
}

function canonical_url(): string
{
    $base = 'https://www.scms.edu.pk';
    $current = basename($_SERVER['PHP_SELF'] ?? 'index.php');
    $path = ($current === 'index.php') ? '' : '/' . str_replace('.php', '', $current);
    return $base . $path;
}

function current_page_slug(): string
{
    $slug = basename($_SERVER['PHP_SELF'] ?? 'index.php');
    return $slug === 'index.php' ? 'home' : str_replace('.php', '', $slug);
}
function program_by_id(string $id): ?array
{
    foreach (programs_data() as $program) {
        if (($program['id'] ?? '') === $id) {
            return $program;
        }
    }
    return null;
}

function render_flash(): void
{
    if (empty($_SESSION['flash'])) return;
    foreach ($_SESSION['flash'] as $flash) {
        $cls = ($flash['type'] ?? 'info') === 'success' ? 'success' : 'error';
        echo '<div class="alert ' . $cls . '">' . htmlspecialchars($flash['message'] ?? '') . '</div>';
    }
    unset($_SESSION['flash']);
}

function page_breadcrumb(string $title, array $crumbs = [], array $intro = []): void
{
    if (empty($intro)) {
        return;
    }

    echo '<section class="breadcrumb-section">';
    echo '<div class="container">';
    echo '<div class="breadcrumb-intro">';
    if (!empty($intro['kicker'])) {
        echo '<span class="section-kicker">' . ($intro['kicker_html'] ?? htmlspecialchars($intro['kicker'])) . '</span>';
    }
    if (!empty($intro['heading'])) {
        echo '<h2>' . htmlspecialchars($intro['heading']) . '</h2>';
    }
    if (!empty($intro['description'])) {
        echo '<p>' . htmlspecialchars($intro['description']) . '</p>';
    }
    if (!empty($intro['actions']) && is_array($intro['actions'])) {
        echo '<div class="hero-actions justify-content-center mt-4">';
        foreach ($intro['actions'] as $action) {
            $actionClass = $action['class'] ?? 'btn-outline-light-custom';
            echo '<a href="' . htmlspecialchars($action['href'] ?? '#') . '" class="' . htmlspecialchars($actionClass) . '">' . htmlspecialchars($action['label'] ?? 'Open') . '</a>';
        }
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
    echo '</section>';
}

function news_date_parts(string $date): array
{
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return ['day' => $date, 'month' => '', 'year' => ''];
    }
    return [
        'day' => date('d', $timestamp),
        'month' => date('M', $timestamp),
        'year' => date('Y', $timestamp),
    ];
}

// Security helpers
function generate_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function sanitize_input(string $input): string
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

function escape_output(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function send_security_headers(): void
{
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}
