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
            $stmt = $pdo->query("SELECT a.*, l.name AS lead_name 
                                FROM appointments a 
                                LEFT JOIN leads l ON l.id = a.lead_id 
                                ORDER BY a.datetime DESC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $lead_id = (int)($input['lead_id'] ?? 0);
            $datetime = $input['datetime'] ?? null;
            $note = trim($input['note'] ?? '');

            if (!$lead_id || !$datetime) throw new Exception('Vui lòng chọn khách hàng và ngày giờ hẹn.');

            $stmt = $pdo->prepare("INSERT INTO appointments (lead_id, datetime, note, status) VALUES (?,?,?, 'pending')");
            $stmt->execute([$lead_id, $datetime, $note]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $datetime = $input['datetime'] ?? null;
            $status = $input['status'] ?? 'pending';
            $note = trim($input['note'] ?? '');
            
            if (!$id) throw new Exception('ID là bắt buộc');
            
            if ($datetime) {
                $stmt = $pdo->prepare("UPDATE appointments SET datetime=?, status=?, note=? WHERE id=?");
                $stmt->execute([$datetime, $status, $note, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE appointments SET status=?, note=? WHERE id=?");
                $stmt->execute([$status, $note, $id]);
            }
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
