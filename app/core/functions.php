<?php

// ── CSRF Protection ──────────────────────────────────────────────────────

/**
 * Returns the current session CSRF token, generating one if it doesn't exist.
 */
function csrf_token(): string {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Outputs a hidden <input> carrying the CSRF token — call inside every POST form.
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

/**
 * Verifies the submitted CSRF token matches the session token.
 * Terminates with 403 on failure — called automatically for all POST routes.
 */
function verify_csrf(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $submitted = $_POST['csrf_token'] ?? '';
    $expected  = $_SESSION['csrf_token'] ?? '';
    if (empty($submitted) || empty($expected) || !hash_equals($expected, $submitted)) {
        http_response_code(403);
        die('Security check failed. Please go back and try again.');
    }
}

// ── Auth & Role Helpers ──────────────────────────────────────────────────

function is_logged_in() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

function is_admin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return is_logged_in() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /login');
        exit();
    }
}

function require_admin() {
    if (!is_admin()) {
        header('Location: /menu');
        exit();
    }
}

function checkEventValidity() {
    // Connect to DB (scope is local to this function)
    require __DIR__ . '/../config/database.php';
    
    // Update query: Close events that have passed and are currently open
    // NOW() checks against the current date and time
    $sql = "UPDATE evenements 
            SET est_cloture = 1 
            WHERE date_evenement < NOW() 
            AND est_cloture = 0";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function get_latest_notifications($limit = 3) {
    if (!is_admin()) return [];
    
    require __DIR__ . '/../config/database.php';
    $stmt = $pdo->prepare("SELECT * FROM notifications ORDER BY created_at DESC LIMIT :limit");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_user_notifications($limit = 3) {
    if (!is_logged_in()) return [];
    
    require __DIR__ . '/../config/database.php';
    // Users only see 'new_event' type
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE type = 'new_event' ORDER BY created_at DESC LIMIT :limit");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

