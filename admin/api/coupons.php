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
            $type = $input['type'] ?? 'percent';
            $value = (float)($input['value'] ?? 0);
            $usage_limit = !empty($input['usage_limit']) ? (int)$input['usage_limit'] : null;
            $expires_at = !empty($input['expires_at']) ? $input['expires_at'] : null;

            if (!$code || $value <= 0) throw new Exception('Mã code và giá trị hợp lệ là bắt buộc.');

            // Check duplicate code
            $chk = $pdo->prepare("SELECT id FROM coupons WHERE code = ?");
            $chk->execute([$code]);
            if ($chk->fetch()) throw new Exception('Mã giảm giá này đã tồn tại.');

            $stmt = $pdo->prepare("INSERT INTO coupons (code, type, value, usage_limit, expires_at, created_at) VALUES (?,?,?,?,?, NOW())");
            $stmt->execute([$code, $type, $value, $usage_limit, $expires_at]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $code = strtoupper(trim($input['code'] ?? ''));
            $type = $input['type'] ?? 'percent';
            $value = (float)($input['value'] ?? 0);
            $usage_limit = !empty($input['usage_limit']) ? (int)$input['usage_limit'] : null;
            $expires_at = !empty($input['expires_at']) ? $input['expires_at'] : null;

            if (!$id || !$code || $value <= 0) throw new Exception('Dữ liệu không hợp lệ.');

            // Check duplicate code (excluding self)
            $chk = $pdo->prepare("SELECT id FROM coupons WHERE code = ? AND id != ?");
            $chk->execute([$code, $id]);
            if ($chk->fetch()) throw new Exception('Mã giảm giá này đã tồn tại.');

            $stmt = $pdo->prepare("UPDATE coupons SET code=?, type=?, value=?, usage_limit=?, expires_at=? WHERE id=?");
            $stmt->execute([$code, $type, $value, $usage_limit, $expires_at, $id]);
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
