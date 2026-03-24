<?php
/**
 * API: Agents CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM agents ORDER BY name ASC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $name = trim($input['name'] ?? '');
            $phone = trim($input['phone'] ?? '');
            $commission_rate = (float)($input['commission_rate'] ?? 10.00);

            if (!$name) throw new Exception('Tên Agent là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO agents (name, phone, commission_rate) VALUES (?,?,?)");
            $stmt->execute([$name, $phone, $commission_rate]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $phone = trim($input['phone'] ?? '');
            $commission_rate = (float)($input['commission_rate'] ?? 10.00);

            if (!$id || !$name) throw new Exception('Dữ liệu không hợp lệ.');

            $stmt = $pdo->prepare("UPDATE agents SET name=?, phone=?, commission_rate=? WHERE id=?");
            $stmt->execute([$name, $phone, $commission_rate, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM agents WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
