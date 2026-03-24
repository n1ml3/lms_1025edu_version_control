<?php
/**
 * Settings — Lưu Trữ
 */
require_once __DIR__ . '/../../includes/auth_check.php';

$pageTitle  = 'Lưu Trữ';
$activePage = 'settings_storage';
$breadcrumb = [['label'=>'Cài Đặt'],['label'=>'Lưu trữ']];

// Disk usage info
$uploadPath = __DIR__ . '/../../assets/img/uploads/';
@mkdir($uploadPath, 0755, true);
$totalFiles = count(glob($uploadPath . '*'));
$diskFree   = @disk_free_space(dirname($uploadPath));
$diskTotal  = @disk_total_space(dirname($uploadPath));
$diskUsedPct = $diskTotal ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 1) : 0;

require_once __DIR__ . '/../../html/pages/settings/storage.php';
