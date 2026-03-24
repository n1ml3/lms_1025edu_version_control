<?php
/**
 * Courses — Chương Trình Học
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Chương Trình Học';
$activePage = 'courses_programs';
$breadcrumb = [['label'=>'Khóa Học'],['label'=>'Chương trình học']];

try {
    $programs = $pdo->query("SELECT p.*, c.name AS course_name FROM programs p LEFT JOIN courses c ON c.id = p.course_id ORDER BY p.course_id, p.order")->fetchAll();
    $courses  = $pdo->query("SELECT * FROM courses ORDER BY name")->fetchAll();
} catch (Exception $e) { $programs = []; $courses = []; }

require_once __DIR__ . '/../../html/pages/courses/programs.php';
