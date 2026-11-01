<?php
// Set SCMS_ADMIN_USERNAME and SCMS_ADMIN_PASSWORD_HASH in the server environment.
// If no hash is configured, login is disabled rather than using a public default.
$credentialFile = getenv('SCMS_ADMIN_CREDENTIAL_FILE') ?: dirname(__DIR__, 2) . '/scms-admin-credentials.php';
// Keep existing XAMPP installations using the previous location working.
if (!getenv('SCMS_ADMIN_CREDENTIAL_FILE') && !is_file($credentialFile)) {
    $credentialFile = dirname(__DIR__, 3) . '/scms-admin-credentials.php';
}
$localCredentials = is_file($credentialFile) ? require $credentialFile : [];
if (!is_array($localCredentials)) $localCredentials = [];
$ADMIN_CONFIG = [
    'username' => getenv('SCMS_ADMIN_USERNAME') ?: ($localCredentials['username'] ?? 'admin'),
    'password_hash' => getenv('SCMS_ADMIN_PASSWORD_HASH') ?: ($localCredentials['password_hash'] ?? ''),
    'session_key' => 'scms_admin_logged_in',
    'session_timeout' => 3600,
];

function admin_password_hash(): string
{
    global $ADMIN_CONFIG;
    return $ADMIN_CONFIG['password_hash'];
}

function admin_username(): string
{
    global $ADMIN_CONFIG;
    return $ADMIN_CONFIG['username'];
}

function admin_session_key(): string
{
    global $ADMIN_CONFIG;
    return $ADMIN_CONFIG['session_key'];
}

function admin_session_timeout(): int
{
    global $ADMIN_CONFIG;
    return $ADMIN_CONFIG['session_timeout'];
}
