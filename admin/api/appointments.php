<?php
/**
 * API: Appointments CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT a.*, l.name AS lead_name, b.name AS branch_name, adm.name AS staff_name 
                                FROM appointments a 
                                LEFT JOIN leads l ON l.id = a.lead_id 
                                LEFT JOIN branches b ON b.id = a.branch_id 
                                LEFT JOIN admins adm ON adm.id = a.staff_id 
                                ORDER BY a.appt_date DESC, a.appt_time DESC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $lead_id = (int)($input['lead_id'] ?? 0);
            $branch_id = (int)($input['branch_id'] ?? 0);
            $staff_id = (int)($input['staff_id'] ?? 0);
            $appt_date = $input['appt_date'] ?? null;
            $appt_time = $input['appt_time'] ?? null;
            $note = trim($input['note'] ?? '');

            if (!$lead_id || !$appt_date) throw new Exception('Vui lòng chọn khách hàng và ngày hẹn.');

            $stmt = $pdo->prepare("INSERT INTO appointments (lead_id, branch_id, staff_id, appt_date, appt_time, note, status) VALUES (?,?,?,?,?,?,'scheduled')");
            $stmt->execute([$lead_id, $branch_id, $staff_id, $appt_date, $appt_time, $note]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $status = $input['status'] ?? 'scheduled';
            $note = trim($input['note'] ?? '');
            if (!$id) throw new Exception('ID là bắt buộc');
            $stmt = $pdo->prepare("UPDATE appointments SET status=?, note=? WHERE id=?");
            $stmt->execute([$status, $note, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM appointments WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
