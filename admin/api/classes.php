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
            $stmt = $pdo->query("SELECT cl.*, p.name AS program_name, t.name AS teacher_name 
                                FROM classes cl 
                                LEFT JOIN programs p ON p.id = cl.program_id 
                                LEFT JOIN teachers t ON t.id = cl.teacher_id 
                                ORDER BY cl.id DESC");
            $data = $stmt->fetchAll();
            // Decode schedule JSON
            foreach ($data as &$row) {
                $row['schedule'] = json_decode($row['schedule'], true);
            }
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'create':
            $program_id = (int)($input['program_id'] ?? 0);
            $teacher_id = (int)($input['teacher_id'] ?? 0);
            $schedule = json_encode($input['schedule'] ?? '');
            $max_students = (int)($input['max_students'] ?? 30);

            if (!$program_id) throw new Exception('Chương trình học là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO classes (program_id, teacher_id, schedule, max_students) VALUES (?,?,?,?)");
            $stmt->execute([$program_id, $teacher_id, $schedule, $max_students]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $program_id = (int)($input['program_id'] ?? 0);
            $teacher_id = (int)($input['teacher_id'] ?? 0);
            $schedule = json_encode($input['schedule'] ?? []);
            $max_students = (int)($input['max_students'] ?? 30);

            if (!$id || !$program_id) throw new Exception('Dữ liệu không hợp lệ.');

            $stmt = $pdo->prepare("UPDATE classes SET program_id=?, teacher_id=?, schedule=?, max_students=? WHERE id=?");
            $stmt->execute([$program_id, $teacher_id, $schedule, $max_students, $id]);
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
