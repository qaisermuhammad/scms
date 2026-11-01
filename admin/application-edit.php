<?php
require_once __DIR__ . '/../includes/admin_auth.php';
admin_require_auth();
require_once __DIR__ . '/../includes/applications_db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 0;
$fields = [
    'full_name' => ['Full name', true, 'text', 120], 'email' => ['Email', true, 'email', 254],
    'phone' => ['Mobile number (Pakistan, starting with 03)', true, 'tel', 11],
    'father_name' => ['Parent or guardian name', false, 'text', 120], 'guardian_phone' => ['Guardian mobile (Pakistan, starting with 03)', true, 'tel', 11], 'date_of_birth' => ['Date of birth', false, 'date', 10],
    'address' => ['Street address', false, 'text', 250], 'city' => ['City or district', false, 'text', 100],
    'qualification' => ['Highest qualification', false, 'text', 120], 'institution' => ['School or college attended', false, 'text', 160], 'board' => ['Board or awarding body', false, 'text', 160],
    'graduation_year' => ['Year completed', false, 'number', 4], 'marks_obtained' => ['Marks obtained', false, 'number', 12],
    'total_marks' => ['Total marks', false, 'number', 12],
    'domicile' => ['Domicile district', false, 'text', 100], 'education' => ['Previous education details', false, 'textarea', 500],
    'notes' => ['Additional information', false, 'textarea', 2000],
];
$errors = [];
$programs = programs_data();
$values = array_fill_keys(array_keys($fields), '');
$programId = '';
$original = null;
try {
    $db = application_database();
    if ($id) {
        $query = $db->prepare('SELECT * FROM applications WHERE id = ?');
        $query->execute([$id]);
        $original = $query->fetch(PDO::FETCH_ASSOC);
        if (!$original) { http_response_code(404); exit('Application not found.'); }
        $details = json_decode($original['additional_info'] ?? '{}', true) ?: [];
        foreach ($values as $key => $unused) $values[$key] = (string) ($original[$key] ?? $details[$key] ?? '');
        foreach (['phone', 'guardian_phone'] as $key) if (str_starts_with($values[$key], '+92')) $values[$key] = '0' . substr($values[$key], 3);
        $programId = $original['program_id'];
    }
} catch (Throwable $e) {
    error_log('Application editor failed: ' . $e->getMessage());
    http_response_code(503); exit('Applications are temporarily unavailable.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf(is_string($_POST['csrf'] ?? null) ? $_POST['csrf'] : '')) $errors[] = 'Invalid request. Please refresh and try again.';
    foreach ($values as $key => $unused) $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
    $programId = is_string($_POST['program_id'] ?? null) ? trim($_POST['program_id']) : '';
    foreach ($fields as $key => [$label, $required, $type, $max]) {
        if ($required && $values[$key] === '') $errors[] = $label . ' is required.';
        elseif (mb_strlen($values[$key]) > $max) $errors[] = $label . ' is too long.';
    }
    if (!$original) foreach (['father_name','date_of_birth','address','city','qualification','institution','board','graduation_year','marks_obtained','total_marks'] as $key) if ($values[$key] === '') $errors[] = $fields[$key][0] . ' is required for a new application.';
    if ($values['institution'] !== '' && !preg_match('/\p{L}/u', $values['institution'])) $errors[] = 'Enter a school or college name containing letters.';
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    elseif (!in_array(strtolower(substr(strrchr($values['email'], '@'), 1)), ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'], true)) $errors[] = 'Use a Gmail, Yahoo, Hotmail, or Outlook email address.';
    foreach (['phone', 'guardian_phone'] as $key) if ($values[$key] !== '' && !preg_match('/^03[0-9]{9}$/D', $values[$key])) $errors[] = $fields[$key][0] . ' must be 11 digits starting with 03.';
    if ($values['date_of_birth'] !== '') {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $values['date_of_birth']);
        if (!$date || $date->format('Y-m-d') !== $values['date_of_birth'] || $date > new DateTimeImmutable('today')) $errors[] = 'Enter a valid date of birth.';
    }
    if ($values['graduation_year'] !== '' && (!preg_match('/^[0-9]{4}$/D', $values['graduation_year']) || (int) $values['graduation_year'] < 1900 || (int) $values['graduation_year'] > (int) date('Y'))) $errors[] = 'Enter a valid year completed.';
    $obtained = $values['marks_obtained']; $total = $values['total_marks'];
    if (($obtained === '') !== ($total === '')) $errors[] = 'Enter both marks obtained and total marks.';
    elseif ($obtained !== '' && (!is_numeric($obtained) || !is_numeric($total) || !is_finite((float) $obtained) || !is_finite((float) $total) || (float) $obtained < 0 || (float) $total <= 0 || (float) $total > 1000000 || (float) $obtained > (float) $total)) $errors[] = 'Marks must be non-negative, no greater than the total, and the total must be between 0 and 1,000,000.';
    if (count(preg_split('/\s+/u', trim($values['notes']), -1, PREG_SPLIT_NO_EMPTY)) > 100) $errors[] = 'Additional information must contain no more than 100 words.';
    $selected = null;
    foreach ($programs as $program) if (($program['id'] ?? '') === $programId) { $selected = $program; break; }
    if (!$selected && $original && $programId === $original['program_id']) $selected = ['id' => $programId, 'title' => $original['program_title']];
    if (!$selected) $errors[] = 'Select a valid program.';
    if (!$errors) {
        try {
            $details = $original ? (json_decode($original['additional_info'] ?? '{}', true) ?: []) : [];
            foreach (['father_name','date_of_birth','address','city','qualification','institution','board','graduation_year','marks_obtained','total_marks','guardian_phone','domicile'] as $key) $details[$key] = $values[$key];
            if ($values['guardian_phone'] !== '') $details['guardian_phone'] = '+92' . substr($values['guardian_phone'], 1);
            if ($obtained !== '') {
                $percentage = (float) $obtained / (float) $total * 100;
                $details['percentage'] = number_format($percentage, 2, '.', '') . '%';
                $details['grade'] = $percentage >= 80 ? 'A' : ($percentage >= 70 ? 'B' : ($percentage >= 60 ? 'C' : ($percentage >= 50 ? 'D' : 'F')));
            } else { unset($details['percentage'], $details['grade']); }
            $record = ['full_name' => $values['full_name'], 'email' => $values['email'], 'phone' => '+92' . substr($values['phone'], 1), 'program_id' => $programId, 'program_title' => $selected['title'], 'education' => $values['education'], 'notes' => $values['notes'], 'additional_info' => json_encode($details, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)];
            if ($id) {
                $expected = is_string($_POST['version'] ?? null) ? $_POST['version'] : '';
                if (!hash_equals(hash('sha256', json_encode($original)), $expected)) throw new RuntimeException('This application changed while you were editing. Reload it and try again.');
                $stmt = $db->prepare('UPDATE applications SET full_name=:full_name,email=:email,phone=:phone,program_id=:program_id,program_title=:program_title,education=:education,notes=:notes,additional_info=:additional_info WHERE id=:id AND additional_info=:old_details AND full_name=:old_name AND email=:old_email AND phone=:old_phone AND program_id=:old_program AND education=:old_education AND notes=:old_notes');
                $stmt->execute($record + ['id'=>$id,'old_details'=>$original['additional_info'],'old_name'=>$original['full_name'],'old_email'=>$original['email'],'old_phone'=>$original['phone'],'old_program'=>$original['program_id'],'old_education'=>$original['education'],'old_notes'=>$original['notes']]);
                if (!$stmt->rowCount()) throw new RuntimeException('This application changed or was removed. Reload it and try again.');
            } else {
                $stmt = $db->prepare('INSERT INTO applications (reference,submitted_at,full_name,email,phone,program_id,program_title,education,notes,additional_info) VALUES (:reference,:submitted_at,:full_name,:email,:phone,:program_id,:program_title,:education,:notes,:additional_info)');
                $stmt->execute($record + ['reference'=>strtoupper(bin2hex(random_bytes(8))), 'submitted_at'=>gmdate('c')]);
            }
            admin_flash('success', $id ? 'Application updated.' : 'Application added.');
            header('Location: applications.php', true, 303); exit;
        } catch (Throwable $e) {
            error_log('Application save failed: ' . $e->getMessage());
            $errors[] = $e instanceof RuntimeException ? $e->getMessage() : 'Could not save the application. Please try again.';
        }
    }
}
admin_render_header($id ? 'Edit Application' : 'Add Application');
?>
<div class="admin-card"><a href="applications.php">&larr; Back to applications</a><h2 class="h4 mt-3"><?php echo $id ? 'Edit application ' . htmlspecialchars($original['reference']) : 'Add application'; ?></h2><p>Reference and submission date are preserved when editing. Extra details may be left blank for older applications.</p>
<?php if ($errors): ?><div class="admin-alert admin-alert-error" role="alert"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?php echo htmlspecialchars(admin_csrf_token()); ?>"><?php if ($original): ?><input type="hidden" name="version" value="<?php echo hash('sha256', json_encode($original)); ?>"><?php endif; ?><div class="row g-3">
<?php foreach ($fields as $key => [$label, $required, $type, $max]): $required = $required || (!$original && in_array($key, ['father_name','date_of_birth','address','city','qualification','institution','board','graduation_year','marks_obtained','total_marks'], true)); ?><div class="col-12 <?php echo $type === 'textarea' ? '' : 'col-md-6'; ?>"><label class="admin-form-label" for="<?php echo $key; ?>"><?php echo htmlspecialchars($label); ?> (<?php echo $required ? 'required' : 'optional'; ?>)</label><?php if ($type === 'textarea'): ?><textarea class="admin-form-control" id="<?php echo $key; ?>" name="<?php echo $key; ?>" maxlength="<?php echo $max; ?>" <?php echo $required ? 'required' : ''; ?>><?php echo htmlspecialchars($values[$key]); ?></textarea><?php else: ?><input class="admin-form-control" id="<?php echo $key; ?>" name="<?php echo $key; ?>" type="<?php echo $type; ?>" value="<?php echo htmlspecialchars($values[$key]); ?>" <?php echo $required ? 'required' : ''; ?> <?php echo $type === 'number' ? 'step="any"' : 'maxlength="' . $max . '"'; ?>><?php endif; ?></div><?php endforeach; ?>
<div class="col-12 col-md-6"><label class="admin-form-label" for="program_id">Program (required)</label><select class="admin-form-control" id="program_id" name="program_id" required><option value="">Select a program</option><?php $hasCurrent = false; foreach ($programs as $program): $selected = $programId === ($program['id'] ?? ''); $hasCurrent = $hasCurrent || $selected; ?><option value="<?php echo htmlspecialchars($program['id']); ?>" <?php echo $selected ? 'selected' : ''; ?>><?php echo htmlspecialchars($program['title']); ?></option><?php endforeach; ?><?php if ($original && !$hasCurrent): ?><option selected value="<?php echo htmlspecialchars($original['program_id']); ?>"><?php echo htmlspecialchars($original['program_title']); ?> (no longer listed)</option><?php endif; ?></select></div></div><p class="mt-3">Percentage and estimated grade are recalculated from marks when saved.</p><button type="submit" class="admin-btn-sm admin-btn-primary mt-2">Save application</button></form></div>
<?php admin_render_footer(); ?>
