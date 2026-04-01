<?php
/**
 * API: Profile Actions
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
if (empty($input) && !empty($_POST)) {
    $input = $_POST;
}
$action = $input['action'] ?? $_GET['action'] ?? '';

$admin_id = $_SESSION['admin']['id'] ?? 0;

try {
    if (!$admin_id) throw new Exception('Không tìm thấy phiên đăng nhập hợp lệ.');

    switch ($action) {
        case 'update_info':
            $name  = trim($input['name'] ?? '');
            $phone = trim($input['phone'] ?? '');

            if (!$name) throw new Exception('Họ tên không được để trống.');

            $stmt = $pdo->prepare("UPDATE admins SET name=?, phone=? WHERE id=?");
            $stmt->execute([$name, $phone, $admin_id]);
            
            // Cập nhật session
            $_SESSION['admin']['name'] = $name;

            echo json_encode(['success' => true]);
            break;

        case 'change_password':
            $current_pw = $input['current_password'] ?? '';
            $new_pw     = $input['new_password'] ?? '';
            $confirm_pw = $input['confirm_password'] ?? '';

            if (!$current_pw || !$new_pw || !$confirm_pw) {
                throw new Exception('Vui lòng điền đầy đủ các trường mật khẩu.');
            }
            if ($new_pw !== $confirm_pw) {
                throw new Exception('Mật khẩu mới và xác nhận không khớp.');
            }

            // Lấy mật khẩu hiện tại trong DB
            $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id=?");
            $stmt->execute([$admin_id]);
            $admin = $stmt->fetch();

            if (!$admin || !password_verify($current_pw, $admin['password_hash'])) {
                throw new Exception('Mật khẩu hiện tại không chính xác.');
            }

            // Cập nhật mật khẩu mới
            $new_hash = password_hash($new_pw, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE admins SET password_hash=? WHERE id=?");
            $update->execute([$new_hash, $admin_id]);

            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
