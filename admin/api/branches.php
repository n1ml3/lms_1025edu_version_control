<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

try {
    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        if (!$name) throw new Exception('Tên cơ sở không được để trống.');
        
        $stmt = $pdo->prepare("INSERT INTO branches (name, address, phone, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $address, $phone]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        if (!$id || !$name) throw new Exception('Dữ liệu không hợp lệ.');
        
        $stmt = $pdo->prepare("UPDATE branches SET name = ?, address = ?, phone = ? WHERE id = ?");
        $stmt->execute([$name, $address, $phone, $id]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) throw new Exception('ID không hợp lệ.');
        
        // Let's assume we can delete a branch. Normally we might check if there are leads/courses associated.
        $stmt = $pdo->prepare("DELETE FROM branches WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
        exit;
    }

    throw new Exception('Action không hợp lệ.');
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
