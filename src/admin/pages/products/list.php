<?php
/**
 * Products — Danh sách sản phẩm
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Sản Phẩm';
$activePage = 'products_list';
$breadcrumb = [['label'=>'Sản Phẩm'],['label'=>'Danh sách']];

try {
    $products = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 50")->fetchAll();
} catch (Exception $e) { $products = []; }

require_once __DIR__ . '/../../html/pages/products/list.php';
