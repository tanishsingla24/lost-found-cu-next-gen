<?php
require_once 'db.php';

/**
 * Ensure PHP session is started.
 */
function ensure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Require a logged-in user. Starts session if needed.
 */
function require_login() {
    ensure_session();
    if (empty($_SESSION['student_id'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Return current logged-in user row (or null).
 */
function current_user() {
    ensure_session();
    global $pdo;
    if (empty($_SESSION['student_id'])) return null;
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['student_id']]);
    return $stmt->fetch() ?: null;
}

/**
 * Sanitize UID format.
 */
function sanitize_uid($uid) {
    $uid = trim($uid);
    if (preg_match('/^[0-9]{2}[A-Za-z]{2}[0-9]{5}$/', $uid)) return $uid;
    if (preg_match('/^[0-9]{9}$/', $uid)) return $uid;
    return false;
}

/**
 * Duplicate detection: image hash, exact title+location (30d), fuzzy LIKE
 */
function check_duplicate_item($pdo, $title, $location, $photo_hash = null) {
    $norm_title = mb_strtolower(trim($title));

    if ($photo_hash) {
        $stmt = $pdo->prepare("SELECT * FROM items WHERE photo_hash = ? LIMIT 1");
        $stmt->execute([$photo_hash]);
        $r = $stmt->fetch();
        if ($r) return $r;
    }

    $stmt = $pdo->prepare("SELECT * FROM items WHERE LOWER(title) = ? AND location = ? AND created_at >= (NOW() - INTERVAL 30 DAY) LIMIT 1");
    $stmt->execute([$norm_title, $location]);
    $r = $stmt->fetch();
    if ($r) return $r;

    $snippet = substr($norm_title, 0, 30);
    if ($snippet !== '') {
        $stmt = $pdo->prepare("SELECT * FROM items WHERE title LIKE ? AND location = ? LIMIT 1");
        $stmt->execute(['%' . $snippet . '%', $location]);
        $r = $stmt->fetch();
        if ($r) return $r;
    }

    return false;
}
