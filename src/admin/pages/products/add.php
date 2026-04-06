<?php
/**
 * Products — Thêm & Sửa sản phẩm
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$id = $_GET['id'] ?? null;
$product = null;

if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
    } catch (Exception $e) { $product = null; }
}

$pageTitle  = $product ? 'Sửa Sản Phẩm' : 'Thêm Sản Phẩm';
$activePage = 'products_list';
$breadcrumb = [['label'=>'Sản Phẩm'],['label'=> $product ? 'Sửa sản phẩm' : 'Thêm mới']];

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $stock = (int)   ($_POST['stock'] ?? 0);

    if ($name && $price >= 0) {
        try {
            if ($id && $product) {
                $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, stock=? WHERE id=?");
                $stmt->execute([$name, $desc, $price, $stock, $id]);
                $success = 'Đã cập nhật sản phẩm thành công!';
                // Refresh product data
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
            } else {
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $desc, $price, $stock, '']);
                $success = 'Đã thêm sản phẩm thành công!';
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = 'Vui lòng nhập tên và giá sản phẩm.';
    }
}

require_once __DIR__ . '/../../html/pages/products/add.php';
