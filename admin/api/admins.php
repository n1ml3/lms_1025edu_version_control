<?php
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? $_GET['action'] ?? '';

try {
    if ($action === 'create') {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $role_id = (int)($input['role_id'] ?? 0);
        $is_active = !empty($input['is_active']) ? 1 : 0;
        
        $phone = trim($input['phone'] ?? '');
        $department = trim($input['department'] ?? '');
        $position = trim($input['position'] ?? '');
        $dob = !empty($input['dob']) ? $input['dob'] : null;
        $start_date = !empty($input['start_date']) ? $input['start_date'] : null;
        $hotline = trim($input['hotline'] ?? '');
        
        if (!$name || !$email || !$password || !$role_id) {
            throw new Exception('Vui lòng điền đầy đủ các thông tin bắt buộc.');
        }
        
        // Check duplicate email
        $chk = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) throw new Exception('Email này đã được sử dụng.');
        
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO admins (name, email, password_hash, role_id, is_active, created_at, phone, department, position, dob, start_date, hotline) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hash, $role_id, $is_active, $phone, $department, $position, $dob, $start_date, $hotline]);
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'update') {
        $id = (int)($input['id'] ?? 0);
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $role_id = (int)($input['role_id'] ?? 0);
        $is_active = !empty($input['is_active']) ? 1 : 0;

        $phone = trim($input['phone'] ?? '');
        $department = trim($input['department'] ?? '');
        $position = trim($input['position'] ?? '');
        $dob = !empty($input['dob']) ? $input['dob'] : null;
        $start_date = !empty($input['start_date']) ? $input['start_date'] : null;
        $hotline = trim($input['hotline'] ?? '');
        
        if (!$id || !$name || !$email || !$role_id) {
            throw new Exception('Dữ liệu không hợp lệ.');
        }
        
        // Check duplicate email (excluding self)
        $chk = $pdo->prepare("SELECT id FROM admins WHERE email = ? AND id != ?");
        $chk->execute([$email, $id]);
        if ($chk->fetch()) throw new Exception('Email này đã thuộc về người dùng khác.');
        
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admins SET name = ?, email = ?, password_hash = ?, role_id = ?, is_active = ?, phone = ?, department = ?, position = ?, dob = ?, start_date = ?, hotline = ? WHERE id = ?");
            $stmt->execute([$name, $email, $hash, $role_id, $is_active, $phone, $department, $position, $dob, $start_date, $hotline, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE admins SET name = ?, email = ?, role_id = ?, is_active = ?, phone = ?, department = ?, position = ?, dob = ?, start_date = ?, hotline = ? WHERE id = ?");
            $stmt->execute([$name, $email, $role_id, $is_active, $phone, $department, $position, $dob, $start_date, $hotline, $id]);
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'delete') {
        $id = (int)($input['id'] ?? 0);
        if (!$id) throw new Exception('ID không hợp lệ.');
        
        // Prevent deleting oneself
        // $adminId should come from auth_check.php usually
        // if ($id == $adminId) throw new Exception('Bạn không thể tự xóa tài khoản của chính mình.');
        
        $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'toggle_status') {
        $id = (int)($input['id'] ?? 0);
        $status = (int)($input['status'] ?? 0);
        
        $stmt = $pdo->prepare("UPDATE admins SET is_active = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        echo json_encode(['success' => true]);
        exit;
    }

    throw new Exception('Action không hợp lệ.');
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

