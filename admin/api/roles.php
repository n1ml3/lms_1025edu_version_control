<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

try {
    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $perms = $_POST['permissions'] ?? [];
        if (!$name) throw new Exception('Tên vai trò không được để trống.');
        
        $permsJson = json_encode(array_values($perms));
        
        $stmt = $pdo->prepare("INSERT INTO roles (name, permissions, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$name, $permsJson]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $perms = $_POST['permissions'] ?? [];
        if (!$id || !$name) throw new Exception('Dữ liệu không hợp lệ.');
        
        $permsJson = json_encode(array_values($perms));
        
        $stmt = $pdo->prepare("UPDATE roles SET name = ?, permissions = ? WHERE id = ?");
        $stmt->execute([$name, $permsJson, $id]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) throw new Exception('ID không hợp lệ.');
        
        // Prevent deleting roles currently assigned to admins
        $check = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE role_id = ?");
        $check->execute([$id]);
        if ($check->fetchColumn() > 0) {
            throw new Exception('Không thể xóa vai trò đang được gán cho nhân viên.');
        }
        
        $stmt = $pdo->prepare("DELETE FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
        exit;
    }

    throw new Exception('Action không hợp lệ.');
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
