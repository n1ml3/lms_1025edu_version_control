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
    $classes = $pdo->query("SELECT cl.*, t.name AS teacher_name, p.name AS program_name FROM classes cl LEFT JOIN teachers t ON t.id = cl.teacher_id LEFT JOIN programs p ON p.id = cl.program_id ORDER BY cl.id DESC LIMIT 50")->fetchAll();
} catch (Exception $e) { $classes = []; }

require_once __DIR__ . '/../../html/pages/courses/classes.php';
