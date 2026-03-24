<?php
/**
 * Admin Dashboard (index.php)
 */
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

$pageTitle = 'Tổng Quan';
$activePage = 'dashboard';
$breadcrumb = [];

// ── Quick Stats ──
try {
    $statBranches  = $pdo->query("SELECT COUNT(*) FROM branches WHERE is_active = 1")->fetchColumn();
    $statSources   = 0; // Placeholder until lead_sources table is ready
    $statCourses   = $pdo->query("SELECT COUNT(*) FROM programs")->fetchColumn();
    $statTeachers  = $pdo->query("SELECT COUNT(*) FROM admins WHERE role = 'teacher'")->fetchColumn();

    $statLeads       = 0; // Placeholder
    $statStudents    = 0; // Placeholder
    $statAppts       = 0; // Placeholder
    $statOrders      = 0; // Placeholder
    $statRevExpected = 0; // Placeholder
    $statRevActual   = 0; // Placeholder

    // Fetch Branches for Filter
    $branches = $pdo->query("SELECT id, name FROM branches WHERE is_active = 1")->fetchAll();
} catch (Exception $e) {
    // Basic fallback data
    $statBranches = 0; $statSources = 0; $statCourses = 0; $statTeachers = 0;
    $statLeads = 0; $statStudents = 0; $statAppts = 0; $statOrders = 0;
    $statRevExpected = 0; $statRevActual = 0;
    $branches = [];
}

require_once __DIR__ . '/html/pages/dashboard.php';
?>
