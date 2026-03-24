<?php
/**
 * Instructors — Nguồn Dữ Liệu
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Nguồn Dữ Liệu';
$activePage = 'inst_sources';
$breadcrumb = [['label'=>'Giảng Viên'],['label'=>'Nguồn dữ liệu']];

try {
    $sources = $pdo->query("SELECT * FROM lead_sources ORDER BY name")->fetchAll();
} catch (Exception $e) { $sources = []; }

require_once __DIR__ . '/../../html/pages/instructors/data-sources.php';
