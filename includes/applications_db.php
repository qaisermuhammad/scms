<?php
function application_database(): PDO
{
    $directory = dirname(__DIR__) . '/data';
    if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
        throw new RuntimeException('Application storage unavailable.');
    }
    $db = new PDO('sqlite:' . $directory . '/applications.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('PRAGMA busy_timeout = 5000');
    $db->exec('CREATE TABLE IF NOT EXISTS applications (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        reference TEXT NOT NULL UNIQUE,
        submitted_at TEXT NOT NULL,
        full_name TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT NOT NULL,
        program_id TEXT NOT NULL,
        program_title TEXT NOT NULL,
        education TEXT NOT NULL,
        notes TEXT NOT NULL
    )');
    $columns = $db->query('PRAGMA table_info(applications)')->fetchAll(PDO::FETCH_COLUMN, 1);
    if (!in_array('additional_info', $columns, true)) {
        try {
            $db->exec("ALTER TABLE applications ADD COLUMN additional_info TEXT NOT NULL DEFAULT '{}'");
        } catch (PDOException $e) {
            $columns = $db->query('PRAGMA table_info(applications)')->fetchAll(PDO::FETCH_COLUMN, 1);
            if (!in_array('additional_info', $columns, true)) throw $e;
        }
    }
    return $db;
}
