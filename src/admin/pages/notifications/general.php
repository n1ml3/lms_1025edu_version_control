<?php
/**
 * Notifications — Chung
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Thông Báo Chung';
$activePage = 'notif_general';
$breadcrumb = [['label'=>'Thông Báo'],['label'=>'Chung']];

try {
    $notifs = $pdo->query("SELECT * FROM notifications WHERE type='general' ORDER BY created_at DESC LIMIT 50")->fetchAll();
} catch (Exception $e) { $notifs = []; }

require_once __DIR__ . '/../../html/pages/notifications/general.php';
