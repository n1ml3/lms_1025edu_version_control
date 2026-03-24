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
            $type = trim($input['type'] ?? 'consultant');
            $status = trim($input['status'] ?? 'active');

            if (!$name) throw new Exception('Tên Agent là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO agents (name, type, status, created_at) VALUES (?,?,?, NOW())");
            $stmt->execute([$name, $type, $status]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $type = trim($input['type'] ?? 'consultant');
            $status = trim($input['status'] ?? 'active');

            if (!$id || !$name) throw new Exception('Dữ liệu không hợp lệ.');

            $stmt = $pdo->prepare("UPDATE agents SET name=?, type=?, status=? WHERE id=?");
            $stmt->execute([$name, $type, $status, $id]);
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
