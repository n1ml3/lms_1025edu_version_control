<?php
/**
 * Promotions — Mã Giảm Giá
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Mã Giảm Giá';
$activePage = 'promo_coupons';
$breadcrumb = [['label'=>'Khuyến Mãi'],['label'=>'Mã giảm giá']];

try {
    $coupons = $pdo->query("SELECT * FROM coupons ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) { $coupons = []; }

require_once __DIR__ . '/../../html/pages/promotions/coupons.php';
