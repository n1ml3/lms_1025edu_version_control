<?php
/**
 * Students — Thêm & Sửa học sinh
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'] ?? null;
$pageTitle  = $id ? 'Sửa Học Sinh' : 'Thêm Học Sinh Mới';
$activePage = 'students_list';
$breadcrumb = [['label'=>'Học Sinh'],['label'=> $id ? 'Sửa học sinh' : 'Thêm mới']];

require_once __DIR__ . '/../../html/pages/students/add.php';
