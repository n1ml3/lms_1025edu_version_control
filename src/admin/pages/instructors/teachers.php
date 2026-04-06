<?php
/**
 * Instructors — Giáo Viên
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Giáo Viên';
$activePage = 'inst_teachers';
$breadcrumb = [['label'=>'Giảng Viên'],['label'=>'Giáo viên']];

try {
    $teachers = $pdo->query("SELECT * FROM teachers ORDER BY name")->fetchAll();
} catch (Exception $e) { $teachers = []; }

require_once __DIR__ . '/../../html/pages/instructors/teachers.php';
