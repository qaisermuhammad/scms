<?php
session_start();
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/applications_db.php';
$programs = programs_data();
$values = array_fill_keys(['full_name', 'email', 'phone', 'program_id', 'education', 'notes'], '');
$extraFields = [
    'father_name' => ['Parent or guardian name', true, 120, 'text'],
    'guardian_phone' => ['Parent or guardian phone', true, 11, 'tel'],
    'date_of_birth' => ['Date of birth', true, 10, 'date'],
    'address' => ['Street address', true, 250, 'text'],
    'city' => ['City or district', true, 100, 'text'],
    'qualification' => ['Highest qualification', true, 120, 'text'],
    'institution' => ['School or college attended', true, 160, 'text'],
    'board' => ['Exam board', true, 160, 'text'],
    'graduation_year' => ['Year completed', true, 4, 'number'],
    'marks_obtained' => ['Marks obtained', true, 12, 'number'],
    'total_marks' => ['Total marks', true, 12, 'number'],
    'domicile' => ['Domicile district', false, 100, 'text'],
];
$qualifications = ['Matric / Secondary School Certificate', 'O Level', 'Intermediate / Higher Secondary School Certificate', 'A Level', 'Diploma of Associate Engineering (DAE)', 'Health or nursing diploma', 'Other diploma', "Bachelor's degree"];
$boards = ['BISE Peshawar', 'BISE Mardan', 'BISE Malakand', 'BISE Abbottabad', 'BISE Bannu', 'BISE Kohat', 'BISE Dera Ismail Khan', 'BISE Swat', 'FBISE Islamabad', 'BISE Lahore', 'BISE Gujranwala', 'BISE Faisalabad', 'BISE Rawalpindi', 'BISE Multan', 'BISE Sargodha', 'BISE Bahawalpur', 'BISE Sahiwal', 'BISE Dera Ghazi Khan', 'BISE Sialkot', 'BSE Karachi', 'BIE Karachi', 'BISE Hyderabad', 'BISE Sukkur', 'BISE Larkana', 'BISE Mirpurkhas', 'BISE Shaheed Benazirabad', 'BISE Quetta', 'BISE Turbat', 'BISE Loralai', 'BISE Mirpur (AJK)', 'Karakoram International University Examination Board', 'Aga Khan University Examination Board', 'Ziauddin University Examination Board', 'Khyber Pakhtunkhwa Board of Technical and Commerce Education', 'Punjab Board of Technical Education', 'Sindh Board of Technical Education', 'Balochistan Board of Technical Education', 'Khyber Pakhtunkhwa Medical Faculty', 'Cambridge International', 'Pearson Edexcel', 'Oxford AQA'];
$cities = ['Abbottabad', 'Attock', 'Bahawalnagar', 'Bahawalpur', 'Bannu', 'Battagram', 'Buner', 'Chakwal', 'Charsadda', 'Chiniot', 'Chitral', 'Dera Ghazi Khan', 'Dera Ismail Khan', 'Dir', 'Faisalabad', 'Gilgit', 'Gujranwala', 'Gujrat', 'Gwadar', 'Haripur', 'Hyderabad', 'Islamabad', 'Jacobabad', 'Jhang', 'Jhelum', 'Karachi', 'Kasur', 'Khairpur', 'Kohat', 'Lahore', 'Larkana', 'Layyah', 'Mansehra', 'Mardan', 'Mianwali', 'Mingora', 'Mirpur', 'Mirpur Khas', 'Multan', 'Muzaffarabad', 'Muzaffargarh', 'Nowshera', 'Okara', 'Peshawar', 'Quetta', 'Rahim Yar Khan', 'Rawalpindi', 'Sahiwal', 'Sargodha', 'Sheikhupura', 'Sialkot', 'Skardu', 'Sukkur', 'Swabi', 'Swat', 'Tank', 'Thatta', 'Turbat', 'Vehari'];
$extra = array_fill_keys(array_keys($extraFields), '');
$errors = [];
$fieldErrors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $unused) {
        $values[$key] = isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : '';
    }
    foreach ($extra as $key => $unused) {
        $extra[$key] = isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : '';
    }
    $boardChoice = $extra['board'];
    if ($boardChoice === '__other__') {
        $extra['board'] = is_string($_POST['board_other'] ?? null) ? trim($_POST['board_other']) : '';
    } elseif ($boardChoice !== '' && !in_array($boardChoice, $boards, true)) {
        $fieldErrors['board'] = 'Choose a board from the list or select Other.';
    }
    if ($extra['institution'] !== '' && !preg_match('/\p{L}/u', $extra['institution'])) $fieldErrors['institution'] = 'Enter the name of your school or college, including letters.';
    $qualificationChoice = $extra['qualification'];
    if ($qualificationChoice === '__other__') {
        $extra['qualification'] = is_string($_POST['qualification_other'] ?? null) ? trim($_POST['qualification_other']) : '';
    } elseif ($qualificationChoice !== '' && !in_array($qualificationChoice, $qualifications, true)) {
        $fieldErrors['qualification'] = 'Choose a qualification from the list or select Other.';
    }
    $cityChoice = $extra['city'];
    if ($cityChoice === '__other__') {
        $extra['city'] = is_string($_POST['city_other'] ?? null) ? trim($_POST['city_other']) : '';
    } elseif ($cityChoice !== '' && !in_array($cityChoice, $cities, true)) {
        $fieldErrors['city'] = 'Choose a city or district from the list or select Other.';
    }
    foreach ($extraFields as $key => [$label, $required, $limit, $type]) {
        if (($required && $extra[$key] === '') || mb_strlen($extra[$key]) > $limit) {
            $fieldErrors[$key] = $label . ($required ? ' is required' : ' is too long') . ' (maximum ' . $limit . ' characters).';
        }
    }
    if ($extra['date_of_birth'] !== '') {
        $dob = DateTimeImmutable::createFromFormat('!Y-m-d', $extra['date_of_birth']);
        if (!$dob || $dob->format('Y-m-d') !== $extra['date_of_birth'] || $dob > new DateTimeImmutable('today')) $fieldErrors['date_of_birth'] = 'Enter a valid date of birth in the past.';
    }
    if ($extra['graduation_year'] !== '' && (!preg_match('/^[0-9]{4}$/', $extra['graduation_year']) || (int) $extra['graduation_year'] < 1900 || (int) $extra['graduation_year'] > (int) date('Y'))) $fieldErrors['graduation_year'] = 'Enter a valid year completed.';
    if ($extra['guardian_phone'] !== '' && !preg_match('/^03[0-9]{9}$/D', $extra['guardian_phone'])) $fieldErrors['guardian_phone'] = 'Enter a valid guardian mobile number: 11 digits starting with 03.';
    foreach (['marks_obtained', 'total_marks'] as $markField) {
        if ($extra[$markField] !== '' && (!is_numeric($extra[$markField]) || !is_finite((float) $extra[$markField]) || (float) $extra[$markField] < 0 || (float) $extra[$markField] > 1000000)) $fieldErrors[$markField] = 'Enter a valid number between 0 and 1,000,000.';
    }
    if ($extra['total_marks'] !== '' && is_numeric($extra['total_marks']) && (float) $extra['total_marks'] <= 0) $fieldErrors['total_marks'] = 'Total marks must be greater than zero.';
    if (is_numeric($extra['marks_obtained']) && is_numeric($extra['total_marks']) && (float) $extra['marks_obtained'] > (float) $extra['total_marks']) $fieldErrors['marks_obtained'] = 'Marks obtained cannot exceed total marks.';
    $program = null;
    foreach ($programs as $item) {
        if (($item['id'] ?? '') === $values['program_id']) { $program = $item; break; }
    }
    if (!verify_csrf_token(is_string($_POST['csrf_token'] ?? null) ? $_POST['csrf_token'] : null)) $errors[] = 'Your session expired. Please try again.';
    if ($values['full_name'] === '' || mb_strlen($values['full_name']) > 120) $fieldErrors['full_name'] = 'Enter your full name (up to 120 characters).';
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || strlen($values['email']) > 254) $fieldErrors['email'] = 'Enter a valid email address.';
    elseif (!in_array(strtolower(substr(strrchr($values['email'], '@'), 1)), ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'], true)) $fieldErrors['email'] = 'Use a Gmail, Yahoo, Hotmail, or Outlook email address.';
    if (!preg_match('/^03[0-9]{9}$/D', $values['phone'])) $fieldErrors['phone'] = 'Enter a valid phone number: 11 digits starting with 03.';
    if (!$program) $fieldErrors['program_id'] = 'Select an available program.';
    if (count(preg_split('/\s+/u', trim($values['notes']), -1, PREG_SPLIT_NO_EMPTY)) > 100) $fieldErrors['notes'] = 'Additional information must contain no more than 100 words.';
    if (!$errors && !$fieldErrors) {
        try {
            $reference = strtoupper(bin2hex(random_bytes(8)));
            $storedValues = $values;
            $storedValues['education'] = '';
            $storedValues['phone'] = '+92' . substr($values['phone'], 1);
            $storedExtra = $extra;
            $percentage = (float) $extra['marks_obtained'] / (float) $extra['total_marks'] * 100;
            $storedExtra['percentage'] = number_format($percentage, 2, '.', '') . '%';
            $storedExtra['grade'] = $percentage >= 80 ? 'A' : ($percentage >= 70 ? 'B' : ($percentage >= 60 ? 'C' : ($percentage >= 50 ? 'D' : 'F')));
            if ($storedExtra['guardian_phone'] !== '') $storedExtra['guardian_phone'] = '+92' . substr($storedExtra['guardian_phone'], 1);
            $stmt = application_database()->prepare('INSERT INTO applications (reference, submitted_at, full_name, email, phone, program_id, program_title, education, notes, additional_info) VALUES (:reference, :submitted_at, :full_name, :email, :phone, :program_id, :program_title, :education, :notes, :additional_info)');
            $stmt->execute($storedValues + ['reference' => $reference, 'submitted_at' => gmdate('c'), 'program_title' => $program['title'], 'additional_info' => json_encode($storedExtra, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)]);
            unset($_SESSION['csrf_token']);
            $_SESSION['application_confirmation'] = $reference;
            header('Location: apply.php?submitted=1', true, 303);
            exit;
        } catch (Throwable $e) {
            error_log('Application submission failed: ' . $e->getMessage());
            $errors[] = 'We could not save your application. Please try again or contact the admissions office.';
        }
    }
}
$confirmation = $_SESSION['application_confirmation'] ?? null;
unset($_SESSION['application_confirmation']);
require __DIR__ . '/includes/header.php';
page_breadcrumb('Apply Online');
?>
<section style="padding: 3rem 0 5rem;"><div class="container"><div class="row justify-content-center"><div class="col-lg-8">
<?php if ($confirmation): ?>
<div class="alert alert-success" role="status">Your application has been submitted. Your reference number is <strong><?php echo htmlspecialchars($confirmation); ?></strong>. Please keep it for your records. The admissions office will contact you about the next steps.</div>
<?php endif; ?>
<?php if ($errors || $fieldErrors): ?><div class="alert alert-danger" role="alert"><ul class="mb-0"><?php foreach (array_merge($errors, $fieldErrors) as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<h2>Online Application</h2><p>Complete the form below. Fields marked (required) must be completed. The admissions office will contact you about documents and next steps.</p>
<form id="application-form" method="post" action="apply.php">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
<div class="row g-3">
<?php foreach (['full_name' => 'Full name', 'email' => 'Email address', 'phone' => 'Phone number'] as $key => $label): ?>
<div class="col-12 col-md-6 application-field"><label class="form-label" for="<?php echo $key; ?>"><?php echo $label; ?> (required)</label><input class="form-control" id="<?php echo $key; ?>" name="<?php echo $key; ?>" type="<?php echo $key === 'email' ? 'email' : ($key === 'phone' ? 'tel' : 'text'); ?>" value="<?php echo htmlspecialchars($values[$key]); ?>" required maxlength="<?php echo $key === 'email' ? 254 : ($key === 'phone' ? 11 : 120); ?>" <?php echo $key === 'phone' ? 'pattern="03[0-9]{9}" inputmode="numeric" aria-describedby="application-phone-help"' : ''; ?>><?php if ($key === 'phone'): ?><small id="application-phone-help" class="form-text">Enter your 11-digit number starting with 03.</small><?php endif; ?></div>
<?php endforeach; ?>
</div>
<div class="row g-3 mt-1">
<?php foreach ($extraFields as $key => [$label, $required, $limit, $type]): ?>
<div class="col-12 col-md-6 application-field"><label class="form-label" for="<?php echo $key; ?>"><?php echo htmlspecialchars($label); ?> (<?php echo $required ? 'required' : 'optional'; ?>)</label>
<?php if ($key === 'board'): $otherBoard = $extra['board'] !== '' && !in_array($extra['board'], $boards, true); ?>
<select class="form-select" id="board" name="board" required><option value="">Select your board or awarding body</option><?php foreach ($boards as $board): ?><option value="<?php echo htmlspecialchars($board); ?>" <?php echo $extra['board'] === $board ? 'selected' : ''; ?>><?php echo htmlspecialchars($board); ?></option><?php endforeach; ?><option value="__other__" <?php echo ($otherBoard || ($boardChoice ?? '') === '__other__') ? 'selected' : ''; ?>>Other (enter below)</option></select>
<div id="other-board-wrapper" class="mt-2"><label class="form-label" for="board_other">Other board or awarding body</label><input class="form-control" id="board_other" name="board_other" maxlength="160" value="<?php echo $otherBoard ? htmlspecialchars($extra['board']) : ''; ?>"></div>
<?php elseif ($key === 'qualification'): $otherQualification = $extra['qualification'] !== '' && !in_array($extra['qualification'], $qualifications, true); ?>
<select class="form-select" id="qualification" name="qualification" required><option value="">Select your highest qualification</option><?php foreach ($qualifications as $qualification): ?><option value="<?php echo htmlspecialchars($qualification); ?>" <?php echo $extra['qualification'] === $qualification ? 'selected' : ''; ?>><?php echo htmlspecialchars($qualification); ?></option><?php endforeach; ?><option value="__other__" <?php echo ($otherQualification || ($qualificationChoice ?? '') === '__other__') ? 'selected' : ''; ?>>Other (enter below)</option></select>
<div id="other-qualification-wrapper" class="mt-2"><label class="form-label" for="qualification_other">Other qualification</label><input class="form-control" id="qualification_other" name="qualification_other" maxlength="120" value="<?php echo $otherQualification ? htmlspecialchars($extra['qualification']) : ''; ?>"></div>
<?php elseif ($key === 'city'): $otherCity = $extra['city'] !== '' && !in_array($extra['city'], $cities, true); ?>
<select class="form-select" id="city" name="city" required><option value="">Select a city or district</option><?php foreach ($cities as $city): ?><option value="<?php echo htmlspecialchars($city); ?>" <?php echo $extra['city'] === $city ? 'selected' : ''; ?>><?php echo htmlspecialchars($city); ?></option><?php endforeach; ?><option value="__other__" <?php echo ($otherCity || ($cityChoice ?? '') === '__other__') ? 'selected' : ''; ?>>Other (enter below)</option></select>
<div id="other-city-wrapper" class="mt-2"><label class="form-label" for="city_other">Other city or district</label><input class="form-control" id="city_other" name="city_other" maxlength="100" value="<?php echo $otherCity ? htmlspecialchars($extra['city']) : ''; ?>"></div>
<?php else: ?><input class="form-control" id="<?php echo $key; ?>" name="<?php echo $key; ?>" type="<?php echo $type; ?>" value="<?php echo htmlspecialchars($extra[$key]); ?>" <?php echo $required ? 'required' : ''; ?> <?php echo $key === 'graduation_year' ? 'min="1900" max="' . date('Y') . '" step="1"' : ($type === 'number' ? 'min="0" max="1000000" step="any"' : 'maxlength="' . $limit . '"'); ?> <?php echo $type === 'date' ? 'max="' . date('Y-m-d') . '"' : ''; ?> <?php echo $key === 'guardian_phone' ? 'pattern="03[0-9]{9}" inputmode="numeric"' : ''; ?>><?php if ($key === 'guardian_phone'): ?><small class="form-text">Enter your 11-digit number starting with 03.</small><?php endif; ?><?php endif; ?></div>
<?php endforeach; ?>
<div class="col-12 col-md-6"><label class="form-label" for="calculated_percentage">Percentage (calculated)</label><input id="calculated_percentage" class="form-control" readonly></div>
<div class="col-12 col-md-6"><label class="form-label" for="calculated_grade">Grade (estimated)</label><input id="calculated_grade" class="form-control" readonly><small class="form-text">Indicative scale: A 80%+, B 70%+, C 60%+, D 50%+, F below 50%. Confirm your official grade with your institution.</small></div>
</div>
<h3 class="h5 mt-4">Program and additional details</h3>
<div class="row g-3">
<div class="col-12 col-md-6 application-field"><label class="form-label" for="program_id">Program (required)</label><select class="form-select" name="program_id" id="program_id" required><option value="">Select a program</option><?php foreach ($programs as $program): ?><option value="<?php echo htmlspecialchars($program['id']); ?>" <?php echo $values['program_id'] === $program['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($program['title']); ?></option><?php endforeach; ?></select></div>

<div class="col-12 application-field"><label class="form-label" for="notes">Additional information (optional)</label><textarea class="form-control" name="notes" id="notes" aria-describedby="application-notes-help"><?php echo htmlspecialchars($values['notes']); ?></textarea><small id="application-notes-help" class="form-text"><span id="application-notes-remaining">100</span> words remaining (maximum 100).</small></div>
</div>
<button class="btn btn-primary-custom mt-4" type="submit">Submit Application</button>
</form></div></div></div></section>
<script>
(function () {
    const form = document.getElementById('application-form');
    const notes = document.getElementById('notes');
    const email = document.getElementById('email');
    function validateEmailDomain() {
        const value = email.value.trim();
        const domain = value.slice(value.lastIndexOf('@') + 1).toLowerCase();
        const allowed = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
        email.setCustomValidity(value && value.includes('@') && !allowed.includes(domain) ? 'Use a Gmail, Yahoo, Hotmail, or Outlook email address.' : '');
    }
    email.addEventListener('input', validateEmailDomain);
    validateEmailDomain();
    const counter = document.getElementById('application-notes-remaining');
    const serverErrors = <?php echo json_encode($fieldErrors, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    form.noValidate = true;
    const boardSelect = document.getElementById('board');
    const boardOther = document.getElementById('board_other');
    const boardOtherWrapper = document.getElementById('other-board-wrapper');
    function updateBoard() {
        const other = boardSelect.value === '__other__';
        boardOtherWrapper.hidden = !other;
        boardOther.required = other;
        boardOther.disabled = !other;
    }
    boardSelect.addEventListener('change', updateBoard);
    updateBoard();
    const institution = document.getElementById('institution');
    function validateInstitution() {
        institution.setCustomValidity(institution.value.trim() && !/\p{L}/u.test(institution.value) ? 'Enter the name of your school or college, including letters.' : '');
    }
    institution.addEventListener('input', validateInstitution);
    validateInstitution();
    const qualificationSelect = document.getElementById('qualification');
    const qualificationOther = document.getElementById('qualification_other');
    const qualificationOtherWrapper = document.getElementById('other-qualification-wrapper');
    function updateQualification() {
        const other = qualificationSelect.value === '__other__';
        qualificationOtherWrapper.hidden = !other;
        qualificationOther.required = other;
        qualificationOther.disabled = !other;
    }
    qualificationSelect.addEventListener('change', updateQualification);
    updateQualification();
    const citySelect = document.getElementById('city');
    const cityOther = document.getElementById('city_other');
    const cityOtherWrapper = document.getElementById('other-city-wrapper');
    function updateCity() {
        const other = citySelect.value === '__other__';
        cityOtherWrapper.hidden = !other;
        cityOther.required = other;
        cityOther.disabled = !other;
    }
    citySelect.addEventListener('change', updateCity);
    updateCity();
    const obtained = document.getElementById('marks_obtained');
    const total = document.getElementById('total_marks');
    function calculateMarks() {
        const a = Number(obtained.value), b = Number(total.value);
        obtained.setCustomValidity(obtained.value !== '' && total.value !== '' && a > b ? 'Marks obtained cannot exceed total marks.' : '');
        total.setCustomValidity(total.value !== '' && b <= 0 ? 'Total marks must be greater than zero.' : '');
        const valid = obtained.value !== '' && total.value !== '' && Number.isFinite(a) && Number.isFinite(b) && a >= 0 && b > 0 && a <= b && a <= 1000000 && b <= 1000000;
        const pct = valid ? a / b * 100 : 0;
        document.getElementById('calculated_percentage').value = valid ? pct.toFixed(2) + '%' : '';
        document.getElementById('calculated_grade').value = valid ? (pct >= 80 ? 'A' : pct >= 70 ? 'B' : pct >= 60 ? 'C' : pct >= 50 ? 'D' : 'F') : '';
    }
    obtained.addEventListener('input', calculateMarks);
    total.addEventListener('input', calculateMarks);
    calculateMarks();
    function updateNotes() {
        const words = notes.value.trim() ? notes.value.trim().split(/\s+/u).length : 0;
        counter.textContent = Math.max(0, 100 - words);
        notes.setCustomValidity(words > 100 ? 'Please enter no more than 100 words.' : '');
    }
    function showError(field, message) {
        const wrapper = field.closest('.application-field');
        if (!wrapper) return;
        let error = wrapper.querySelector('.application-field-error');
        if (!error) {
            error = document.createElement('div');
            error.className = 'application-field-error invalid-feedback';
            error.id = field.id + '-error';
            wrapper.appendChild(error);
        }
        field.classList.toggle('is-invalid', !!message);
        field.setAttribute('aria-invalid', message ? 'true' : 'false');
        const describedBy = (field.getAttribute('aria-describedby') || '').split(/\s+/).filter(id => id && id !== error.id);
        if (message) describedBy.push(error.id);
        if (describedBy.length) field.setAttribute('aria-describedby', describedBy.join(' '));
        else field.removeAttribute('aria-describedby');
        error.textContent = message || '';
        error.style.display = message ? 'block' : 'none';
    }
    const fields = Array.from(form.querySelectorAll('input:not([type=hidden]), select, textarea'));
    let attempted = false;
    function validate(field) {
        if (field.disabled) { showError(field, ''); return true; }
        const message = field.validity.valid ? '' : (field.validity.badInput ? (field.id === 'graduation_year' ? 'Enter a valid year using numbers.' : 'Enter a valid number.') : field.validity.valueMissing ? 'This field is required.' : field.validity.typeMismatch && field.type === 'email' ? 'Enter a valid email address, such as name@example.com.' : field.validationMessage);
        showError(field, message);
        return !message;
    }
    fields.forEach(field => {
        field.addEventListener('input', () => { if (field === notes) updateNotes(); if (attempted || field.classList.contains('is-invalid')) validate(field); });
        field.addEventListener('change', () => { if (attempted || field.classList.contains('is-invalid')) validate(field); });
    });
    form.addEventListener('submit', event => {
        attempted = true;
        updateNotes();
        calculateMarks();
        validateEmailDomain();
        validateInstitution();
        const invalid = fields.filter(field => !validate(field));
        if (invalid.length) {
            event.preventDefault();
            invalid[0].focus();
            invalid[0].scrollIntoView({behavior: 'smooth', block: 'center'});
        }
    });
    updateNotes();
    let firstServerError = null;
    fields.forEach(field => {
        if (serverErrors[field.name]) {
            showError(field, serverErrors[field.name]);
            if (!firstServerError) firstServerError = field;
        }
    });
    if (firstServerError) { attempted = true; firstServerError.focus(); firstServerError.scrollIntoView({block: 'center'}); }
}());
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
