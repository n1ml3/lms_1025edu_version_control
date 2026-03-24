<?php
/**
 * API: Classes CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT cl.*, c.name AS course_name, b.name AS branch_name, t.name AS teacher_name 
                                FROM classes cl 
                                LEFT JOIN courses c ON c.id = cl.course_id 
                                LEFT JOIN branches b ON b.id = cl.branch_id 
                                LEFT JOIN teachers t ON t.id = cl.teacher_id 
                                ORDER BY cl.start_date DESC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $name = trim($input['name'] ?? '');
            $course_id = (int)($input['course_id'] ?? 0);
            $branch_id = (int)($input['branch_id'] ?? 0);
            $teacher_id = (int)($input['teacher_id'] ?? 0);
            $start_date = $input['start_date'] ?? null;
            $max_students = (int)($input['max_students'] ?? 30);

            if (!$name || !$course_id) throw new Exception('Tên lớp và khóa học là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO classes (name, course_id, branch_id, teacher_id, start_date, max_students, status) VALUES (?,?,?,?,?,?,'active')");
            $stmt->execute([$name, $course_id, $branch_id, $teacher_id, $start_date, $max_students]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $status = $input['status'] ?? 'active';
            if (!$id) throw new Exception('ID là bắt buộc');
            $stmt = $pdo->prepare("UPDATE classes SET status=? WHERE id=?");
            $stmt->execute([$status, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM classes WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
