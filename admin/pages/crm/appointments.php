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
        SELECT a.*, DATE(a.datetime) AS appt_date, TIME(a.datetime) AS appt_time,
               l.name AS lead_name, l.phone AS lead_phone, b.name AS branch_name, adm.name AS staff_name
        FROM appointments a
        LEFT JOIN leads l ON l.id = a.lead_id
        LEFT JOIN branches b ON b.id = l.branch_id
        LEFT JOIN admins adm ON adm.id = l.staff_id
        ORDER BY a.datetime DESC
        LIMIT 100
    ")->fetchAll();

    // Fetch Secondary Data for Modal
    $leads    = $pdo->query("SELECT id, name, phone FROM leads ORDER BY name ASC")->fetchAll();
    $branches = $pdo->query("SELECT id, name FROM branches ORDER BY name ASC")->fetchAll();
    $staff    = $pdo->query("SELECT id, name FROM admins WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

} catch (Exception $e) {
    $appts = []; $leads = []; $branches = []; $staff = [];
}

require_once __DIR__ . '/../../html/pages/crm/appointments.php';
