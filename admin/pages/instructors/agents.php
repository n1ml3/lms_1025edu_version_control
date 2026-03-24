<?php
/**
 * Instructors — Đại Lý
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Đại Lý';
$activePage = 'inst_agents';
$breadcrumb = [['label'=>'Giảng Viên'],['label'=>'Đại lý']];

try {
    $agents = $pdo->query("SELECT * FROM agents ORDER BY name")->fetchAll();
} catch (Exception $e) { $agents = []; }

require_once __DIR__ . '/../../html/pages/instructors/agents.php';
