<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getRole() {
    return $_SESSION['role'] ?? null;
}

function requireRole($roles) {
    if (!isLoggedIn()) {
        header("Location: /login.php");
        exit;
    }
    if (is_string($roles)) {
        $roles = [$roles];
    }
    if (!in_array(getRole(), $roles)) {
        http_response_code(403);
        die("Unauthorized access.");
    }
}

function redirectBasedOnRole($role) {
    switch ($role) {
        case 'patient':
            header("Location: /patient/dashboard.php");
            break;
        case 'doctor':
            header("Location: /doctor/dashboard.php");
            break;
        case 'admin':
            header("Location: /admin/dashboard.php");
            break;
        case 'director':
            header("Location: /director/dashboard.php");
            break;
        default:
            header("Location: /index.php");
    }
    exit;
}
?>
