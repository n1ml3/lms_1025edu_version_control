<?php
/**
 * Students — Danh sách học sinh
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Học Sinh';
$activePage = 'students_list';
$breadcrumb = [['label'=>'Học Sinh'],['label'=>'Danh sách']];

require_once __DIR__ . '/../../html/pages/students/list.php';
