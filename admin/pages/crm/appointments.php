<?php
/**
 * CRM — Lịch Hẹn
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Lịch Hẹn';
$activePage = 'crm_appointments';
$breadcrumb = [['label'=>'CRM'],['label'=>'Lịch Hẹn']];

try {
    $appts = $pdo->query("
        SELECT a.*, l.name AS lead_name, l.phone AS lead_phone
        FROM appointments a
        LEFT JOIN leads l ON l.id = a.lead_id
        ORDER BY a.datetime DESC LIMIT 50
    ")->fetchAll();
} catch (Exception $e) { $appts = []; }

require_once __DIR__ . '/../../html/pages/crm/appointments.php';
