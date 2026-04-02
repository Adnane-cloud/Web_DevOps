<?php

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
        header('Location: /python/public/login');
        exit();
    }
}

function require_admin() {
    if (!is_admin()) {
        header('Location: /python/public/menu');
        exit();
    }
}

function checkEventValidity() {
    // Connect to DB (scope is local to this function)
    require '../app/config/database.php';
    
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
    
    require '../app/config/database.php';
    $stmt = $pdo->prepare("SELECT * FROM notifications ORDER BY created_at DESC LIMIT :limit");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_user_notifications($limit = 3) {
    if (!is_logged_in()) return [];
    
    require '../app/config/database.php';
    // Users only see 'new_event' type
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE type = 'new_event' ORDER BY created_at DESC LIMIT :limit");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
