<?php
/**
 * Settings — Cơ sở
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Cơ Sở (Chi Nhánh)';
$activePage = 'settings_branches';
$breadcrumb = [['label'=>'Cài Đặt'],['label'=>'Cơ sở']];

try {
    $branches = $pdo->query("SELECT * FROM branches ORDER BY name")->fetchAll();
} catch (Exception $e) { $branches = []; }

require_once __DIR__ . '/../../html/pages/settings/branches.php';
