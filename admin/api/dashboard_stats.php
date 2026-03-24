<?php
/**
 * API: Dashboard Stats
 * Returns filtered statistics as JSON
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$branch  = $input['branch']   ?? '';
$source  = $input['source']   ?? '';
$staff   = $input['staff']    ?? '';
$from    = $input['dateFrom'] ?? date('Y-m-01');
$to      = $input['dateTo']   ?? date('Y-m-d');

// Build WHERE conditions
$where = "WHERE DATE(l.created_at) BETWEEN :from AND :to";
$params = [':from' => $from, ':to' => $to];

if ($branch) { $where .= " AND l.branch_id = :branch"; $params[':branch'] = $branch; }
if ($source)  { $where .= " AND l.source_id = :source"; $params[':source'] = $source; }
if ($staff)   { $where .= " AND l.staff_id  = :staff";  $params[':staff']  = $staff; }

try {
    // Leads count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM leads l $where");
    $stmt->execute($params);
    $leads = $stmt->fetchColumn();

    // Orders & Revenue (uses order date range, not lead)
    $orderWhere  = "WHERE DATE(created_at) BETWEEN :from AND :to";
    $orderParams = [':from' => $from, ':to' => $to];
    if ($branch) { $orderWhere .= " AND branch_id = :branch"; $orderParams[':branch'] = $branch; }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders $orderWhere");
    $stmt->execute($orderParams);
    $orders = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(amount),0) FROM orders WHERE status='paid' AND DATE(created_at) BETWEEN :from AND :to");
    $stmt->execute([':from' => $from, ':to' => $to]);
    $revActual = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(amount),0) FROM orders WHERE status='pending' AND DATE(created_at) BETWEEN :from AND :to");
    $stmt->execute([':from' => $from, ':to' => $to]);
    $revExpected = $stmt->fetchColumn();

    echo json_encode([
        'success'     => true,
        'leads'       => (int) $leads,
        'orders'      => (int) $orders,
        'rev_actual'  => (float) $revActual,
        'rev_expected'=> (float) $revExpected,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
