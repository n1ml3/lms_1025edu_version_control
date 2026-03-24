<?php
/**
 * Products — Thêm sản phẩm
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Thêm Sản Phẩm';
$activePage = 'products_add';
$breadcrumb = [['label'=>'Sản Phẩm'],['label'=>'Thêm mới']];

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $stock = (int)   ($_POST['stock'] ?? 0);

    if ($name && $price >= 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $desc, $price, $stock, '']);
            $success = 'Đã thêm sản phẩm thành công!';
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = 'Vui lòng nhập tên và giá sản phẩm.';
    }
}

require_once __DIR__ . '/../../html/pages/products/add.php';
