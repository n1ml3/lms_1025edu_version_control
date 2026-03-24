<?php
/**
 * Members — Quản trị viên
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Quản Trị Viên';
$activePage = 'members_list';
$breadcrumb = [['label'=>'Thành Viên'],['label'=>'Quản trị viên']];

try {
    $admins = $pdo->query("SELECT a.*, r.name AS role_name FROM admins a LEFT JOIN roles r ON r.id = a.role_id ORDER BY a.created_at DESC")->fetchAll();
} catch (Exception $e) { $admins = []; }

require_once __DIR__ . '/../../html/pages/members/list.php';
