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
        LIMIT 100
    ")->fetchAll();

    // Fetch Sources & Branches for Modal
    $sources  = $pdo->query("SELECT id, name FROM lead_sources ORDER BY name ASC")->fetchAll();
    $branches = $pdo->query("SELECT id, name FROM branches WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

} catch (Exception $e) {
    $leads = []; $sources = []; $branches = [];
}

require_once __DIR__ . '/../../html/pages/crm/leads.php';
