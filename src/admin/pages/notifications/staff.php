<?php
/**
 * Notifications — Nhân Viên
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Thông Báo Nhân Viên';
$activePage = 'notif_staff';
$breadcrumb = [['label'=>'Thông Báo'],['label'=>'Nhân viên']];

try {
    $notifs = $pdo->query("SELECT * FROM notifications WHERE type='staff' ORDER BY created_at DESC LIMIT 50")->fetchAll();
} catch (Exception $e) { $notifs = []; }

require_once __DIR__ . '/../../html/pages/notifications/staff.php';
