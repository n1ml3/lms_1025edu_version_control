<?php
/**
 * API: Notifications CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $type = $input['type'] ?? $_GET['type'] ?? 'general';
            $stmt = $pdo->prepare("SELECT * FROM notifications WHERE type = ? ORDER BY created_at DESC LIMIT 100");
            $stmt->execute([$type]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $title = trim($input['title'] ?? '');
            $content = trim($input['content'] ?? '');
            $type = trim($input['type'] ?? 'general');

            if (!$title || !$content) throw new Exception('Tiêu đề và nội dung là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO notifications (title, content, type, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$title, $content, $type]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM notifications WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
