<?php
/**
 * Members — Phân quyền
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Phân Quyền';
$activePage = 'members_roles';
$breadcrumb = [['label'=>'Thành Viên'],['label'=>'Phân quyền']];

try {
    $roles = $pdo->query("SELECT * FROM roles ORDER BY id")->fetchAll();
} catch (Exception $e) { $roles = []; }

require_once __DIR__ . '/../../html/pages/members/roles.php';
