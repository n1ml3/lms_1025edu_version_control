<?php
/**
 * Auth Guard — Session Check
 * Include this at the top of every protected admin page.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    $loginUrl = '/lms1025edu/admin/login.php';
    header('Location: ' . $loginUrl);
    exit;
}

// Convenience variables available to all pages
$adminId   = $_SESSION['admin_id'];
$adminName = $_SESSION['admin_name'] ?? 'Admin';
$adminRole = $_SESSION['admin_role'] ?? 'staff';
