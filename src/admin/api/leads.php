<?php
/**
 * API: Leads CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT l.*, ls.name AS source_name, b.name AS branch_name, a.name AS staff_name 
                                FROM leads l 
                                LEFT JOIN lead_sources ls ON ls.id = l.source_id 
                                LEFT JOIN branches b ON b.id = l.branch_id 
                                LEFT JOIN admins a ON a.id = l.staff_id
                                ORDER BY l.created_at DESC LIMIT 100");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $name  = trim($input['name'] ?? '');
            $phone = trim($input['phone'] ?? '');
            $email = trim($input['email'] ?? '');
            $source_id = (int)($input['source_id'] ?? 0) ?: null;
            $branch_id = (int)($input['branch_id'] ?? 0) ?: null;
            $staff_id  = (int)($input['staff_id'] ?? 0) ?: null;
            $note  = trim($input['note'] ?? '');

            if (!$name) throw new Exception('Tên là bắt buộc');

            $stmt = $pdo->prepare("INSERT INTO leads (name, phone, email, source_id, branch_id, staff_id, note, status) VALUES (?,?,?,?,?,?,?,'new')");
            $stmt->execute([$name, $phone, $email, $source_id, $branch_id, $staff_id, $note]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id     = (int)($input['id'] ?? 0);
            $name   = trim($input['name'] ?? '');
            $phone  = trim($input['phone'] ?? '');
            $email  = trim($input['email'] ?? '');
            $source_id = (int)($input['source_id'] ?? 0) ?: null;
            $branch_id = (int)($input['branch_id'] ?? 0) ?: null;
            $staff_id  = (int)($input['staff_id'] ?? 0) ?: null;
            $status = $input['status'] ?? 'new';
            $note   = $input['note'] ?? '';

            if (!$id) throw new Exception('ID là bắt buộc');
            
            $stmt = $pdo->prepare("UPDATE leads SET name=?, phone=?, email=?, source_id=?, branch_id=?, staff_id=?, status=?, note=? WHERE id=?");
            $stmt->execute([$name, $phone, $email, $source_id, $branch_id, $staff_id, $status, $note, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM leads WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
