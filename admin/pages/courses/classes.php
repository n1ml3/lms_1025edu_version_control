<?php
/**
 * Courses — Lớp Học
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Lớp Học';
$activePage = 'courses_classes';
$breadcrumb = [['label'=>'Khóa Học'],['label'=>'Lớp học']];

try {
    $classes = $pdo->query("
        SELECT c.*, p.name AS program_name, t.name AS teacher_name 
        FROM classes c 
        LEFT JOIN programs p ON p.id = c.program_id 
        LEFT JOIN teachers t ON t.id = c.teacher_id 
        ORDER BY c.created_at DESC
        LIMIT 100
    ")->fetchAll();

    // Fetch Secondary Data for Modal
    $programs = $pdo->query("SELECT id, name FROM programs ORDER BY name ASC")->fetchAll();
    $teachers = $pdo->query("SELECT id, name FROM teachers WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

} catch (Exception $e) {
    $classes = []; $programs = []; $teachers = [];
}

require_once __DIR__ . '/../../html/pages/courses/classes.php';
