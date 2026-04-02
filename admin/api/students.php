<?php
/**
 * API: Students CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
if (empty($input) && !empty($_POST)) {
    $input = $_POST;
}
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT s.*, 
                                 (SELECT p.name FROM classes c JOIN programs p ON c.program_id = p.id WHERE c.id = s.class_id) as class_name 
                                 FROM students s ORDER BY s.enrolled_at DESC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $name = trim($input['name'] ?? '');
            $phone = trim($input['phone'] ?? '');
            $email = trim($input['email'] ?? '');
            $class_id = !empty($input['class_id']) ? (int)$input['class_id'] : null;

            if (!$name) throw new Exception('Tên học sinh là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO students (name, phone, email, class_id, enrolled_at) VALUES (?,?,?,?, NOW())");
            $stmt->execute([$name, $phone, $email, $class_id]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $phone = trim($input['phone'] ?? '');
            $email = trim($input['email'] ?? '');
            $class_id = !empty($input['class_id']) ? (int)$input['class_id'] : null;

            if (!$id || !$name) throw new Exception('Dữ liệu không hợp lệ.');

            $stmt = $pdo->prepare("UPDATE students SET name=?, phone=?, email=?, class_id=? WHERE id=?");
            $stmt->execute([$name, $phone, $email, $class_id, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM students WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
