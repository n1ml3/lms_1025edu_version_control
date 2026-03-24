<?php
/**
 * API: Quizzes CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT q.*, p.name AS program_name FROM quizzes q LEFT JOIN programs p ON p.id = q.program_id ORDER BY p.name ASC, q.name ASC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $name = trim($input['name'] ?? '');
            $program_id = (int)($input['program_id'] ?? 0);
            $duration = (int)($input['duration'] ?? 0);

            if (!$name || !$program_id) throw new Exception('Tên quiz và chương trình là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO quizzes (name, program_id, duration, created_at) VALUES (?,?,?, NOW())");
            $stmt->execute([$name, $program_id, $duration]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $program_id = (int)($input['program_id'] ?? 0);
            $duration = (int)($input['duration'] ?? 0);

            if (!$id || !$name || !$program_id) throw new Exception('Dữ liệu không hợp lệ.');

            $stmt = $pdo->prepare("UPDATE quizzes SET name=?, program_id=?, duration=? WHERE id=?");
            $stmt->execute([$name, $program_id, $duration, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM quizzes WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
