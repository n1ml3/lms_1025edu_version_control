<?php
/**
 * Courses — Bài Kiểm Tra (Quiz)
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Bài Kiểm Tra';
$activePage = 'courses_quiz';
$breadcrumb = [['label'=>'Khóa Học'],['label'=>'Bài kiểm tra']];

try {
    $quizzes = $pdo->query("SELECT q.*, p.name AS program_name FROM quizzes q LEFT JOIN programs p ON p.id = q.program_id ORDER BY p.name ASC, q.name ASC")->fetchAll();
    $programs = $pdo->query("SELECT id, name FROM programs ORDER BY name ASC")->fetchAll();
} catch (Exception $e) { $quizzes = []; $programs = []; }

require_once __DIR__ . '/../../html/pages/courses/quiz.php';
