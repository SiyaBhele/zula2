<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Require login to access page
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Check user role
 */
function hasRole($role_name) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role_name;
}

/**
 * Require specific role
 */
function requireRole($role_name) {
    requireLogin();
    if (!hasRole($role_name)) {
        header("Location: index.php?error=unauthorized");
        exit();
    }
}

/**
 * Redirect based on role after login
 */
function redirectBasedOnRole($role) {
    switch ($role) {
        case 'Admin':
            header("Location: admin/dashboard.php");
            break;
        case 'Seller':
            header("Location: seller/dashboard.php");
            break;
        case 'Buyer':
            header("Location: index.php");
            break;
        default:
            header("Location: index.php");
            break;
    }
    exit();
}
?>
