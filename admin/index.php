<?php
/**
 * Admin Dashboard (index.php)
 */
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

$pageTitle = 'Tổng Quan';
$activePage = 'dashboard';
$breadcrumb = [];

// ── Quick Stats (fallback dummy data if tables don't exist yet) ──
try {
    $statBranches  = $pdo->query("SELECT COUNT(*) FROM branches")->fetchColumn();
    $statSources   = $pdo->query("SELECT COUNT(*) FROM lead_sources")->fetchColumn();
    $statCourses   = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    $statTeachers  = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();

    $statLeads       = $pdo->query("SELECT COUNT(*) FROM leads WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    $statStudents    = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $statAppts       = $pdo->query("SELECT COUNT(*) FROM appointments WHERE DATE(datetime) = CURDATE()")->fetchColumn();
    $statOrders      = $pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    $statRevExpected = $pdo->query("SELECT COALESCE(SUM(amount),0) FROM orders WHERE status='pending' AND MONTH(created_at)=MONTH(CURDATE())")->fetchColumn();
    $statRevActual   = $pdo->query("SELECT COALESCE(SUM(amount),0) FROM orders WHERE status='paid' AND MONTH(created_at)=MONTH(CURDATE())")->fetchColumn();
} catch (Exception $e) {
    // Tables not yet created — use demo data
    $statBranches = 3; $statSources = 8; $statCourses = 24; $statTeachers = 12;
    $statLeads = 7; $statStudents = 348; $statAppts = 5; $statOrders = 15;
    $statRevExpected = 125000000; $statRevActual = 98000000;
}

require_once __DIR__ . '/html/pages/dashboard.php';
