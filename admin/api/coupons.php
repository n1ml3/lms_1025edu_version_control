<?php
/**
 * API: Coupons CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM coupons ORDER BY created_at DESC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $code = strtoupper(trim($input['code'] ?? ''));
            $type = trim($input['type'] ?? 'percent');
            $value = (float)($input['value'] ?? 0);
            $expires_at = !empty($input['expires_at']) ? $input['expires_at'] : null;
            $usage_limit = !empty($input['usage_limit']) ? (int)$input['usage_limit'] : null;

            if (!$code || $value <= 0) throw new Exception('Mã code và giá trị là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO coupons (code, type, value, expires_at, usage_limit, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$code, $type, $value, $expires_at, $usage_limit]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $code = strtoupper(trim($input['code'] ?? ''));
            $type = trim($input['type'] ?? 'percent');
            $value = (float)($input['value'] ?? 10);
            $expires_at = !empty($input['expires_at']) ? $input['expires_at'] : null;
            $usage_limit = !empty($input['usage_limit']) ? (int)$input['usage_limit'] : null;

            if (!$id || !$code) throw new Exception('Dữ liệu không hợp lệ.');

            $stmt = $pdo->prepare("UPDATE coupons SET code=?, type=?, value=?, expires_at=?, usage_limit=? WHERE id=?");
            $stmt->execute([$code, $type, $value, $expires_at, $usage_limit, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM coupons WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
