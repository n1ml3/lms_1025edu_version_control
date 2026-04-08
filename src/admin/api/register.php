<?php
/**
 * Admin Register API
 */
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$name     = trim($input['name'] ?? '');
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$role_id  = (int)($input['role_id'] ?? 0);

try {
    if (!$name || !$email || !$password || !$role_id) {
        throw new Exception('Vui lòng điền đầy đủ các thông tin bắt buộc.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email không hợp lệ.');
    }

    if (strlen($password) < 6) {
        throw new Exception('Mật khẩu phải từ 6 ký tự trở lên.');
    }

    // Role restriction: Prevent self-registering as Super Admin (ID 1)
    if ($role_id == 1) {
        throw new Exception('Không được phép đăng ký vai trò Super Admin.');
    }

    // Check if email already exists
    $chk = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
    $chk->execute([$email]);
    if ($chk->fetch()) {
        throw new Exception('Email này đã được sử dụng.');
    }

    // Hash the password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    // Default is_active to 1 (active) but could be 0 (pending)
    $stmt = $pdo->prepare("INSERT INTO admins (name, email, password_hash, role_id, is_active, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
    $stmt->execute([$name, $email, $hash, $role_id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
