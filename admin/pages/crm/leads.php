<?php
/**
 * CRM — Danh sách Lead
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Danh Sách Lead';
$activePage = 'crm_leads';
$breadcrumb = [
    ['label' => 'CRM'],
    ['label' => 'Danh sách Lead'],
];

try {
    $leads = $pdo->query("
        SELECT l.*, ls.name AS source_name, b.name AS branch_name
        FROM leads l
        LEFT JOIN lead_sources ls ON ls.id = l.source_id
        LEFT JOIN branches b ON b.id = l.branch_id
        ORDER BY l.created_at DESC
        LIMIT 50
    ")->fetchAll();
} catch (Exception $e) {
    $leads = [];
}

require_once __DIR__ . '/../../html/pages/crm/leads.php';
