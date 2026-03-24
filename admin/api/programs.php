<?php
/**
 * API: Course Programs CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT p.*, c.name AS course_name FROM programs p LEFT JOIN courses c ON c.id = p.course_id ORDER BY c.name ASC, p.sort_order ASC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $name = trim($input['name'] ?? '');
            $course_id = (int)($input['course_id'] ?? 0);
            $sort_order = (int)($input['sort_order'] ?? 1);

            if (!$name || !$course_id) throw new Exception('Tên chương trình và khóa học là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO programs (name, course_id, sort_order) VALUES (?,?,?)");
            $stmt->execute([$name, $course_id, $sort_order]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $course_id = (int)($input['course_id'] ?? 0);
            $sort_order = (int)($input['sort_order'] ?? 1);

            if (!$id || !$name || !$course_id) throw new Exception('Dữ liệu không hợp lệ.');

            $stmt = $pdo->prepare("UPDATE programs SET name=?, course_id=?, sort_order=? WHERE id=?");
            $stmt->execute([$name, $course_id, $sort_order, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM programs WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
