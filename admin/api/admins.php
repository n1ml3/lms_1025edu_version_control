<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

try {
    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role_id = (int)($_POST['role_id'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (!$name || !$email || !$password || !$role_id) {
            throw new Exception('Vui lòng điền đầy đủ các thông tin bắt buộc.');
        }
        
        // Check duplicate email
        $chk = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) throw new Exception('Email này đã được sử dụng.');
        
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO admins (name, email, password_hash, role_id, is_active, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $hash, $role_id, $is_active]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role_id = (int)($_POST['role_id'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (!$id || !$name || !$email || !$role_id) {
            throw new Exception('Dữ liệu không hợp lệ.');
        }
        
        // Check duplicate email (excluding self)
        $chk = $pdo->prepare("SELECT id FROM admins WHERE email = ? AND id != ?");
        $chk->execute([$email, $id]);
        if ($chk->fetch()) throw new Exception('Email này đã thuộc về người dùng khác.');
        
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admins SET name = ?, email = ?, password_hash = ?, role_id = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $email, $hash, $role_id, $is_active, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE admins SET name = ?, email = ?, role_id = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $email, $role_id, $is_active, $id]);
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) throw new Exception('ID không hợp lệ.');
        
        // Prevent deleting oneself
        if ($id == $adminId) {
            throw new Exception('Bạn không thể tự xóa tài khoản của chính mình.');
        }
        
        $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
        exit;
    }

    throw new Exception('Action không hợp lệ.');
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
