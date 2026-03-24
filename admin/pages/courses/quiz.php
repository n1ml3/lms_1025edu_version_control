<?php
/**
 * Courses — Bài Kiểm Tra (Quiz)
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Bài Kiểm Tra';
$activePage = 'courses_quiz';
$breadcrumb = [['label'=>'Khóa Học'],['label'=>'Bài kiểm tra']];

require_once __DIR__ . '/../../html/pages/courses/quiz.php';
