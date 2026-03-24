<?php
/**
 * CRM — Lịch Hẹn
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Lịch Hẹn';
$activePage = 'crm_appointments';
$breadcrumb = [['label'=>'CRM'],['label'=>'Lịch Hẹn']];

try {
    $appts = $pdo->query("
        SELECT a.*, l.name AS lead_name, l.phone AS lead_phone
        FROM appointments a
        LEFT JOIN leads l ON l.id = a.lead_id
        ORDER BY a.datetime DESC LIMIT 50
    ")->fetchAll();
} catch (Exception $e) { $appts = []; }

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Lịch Hẹn</h1>
            <p class="page-subtitle">Quản lý lịch hẹn với khách hàng</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAppt">
            <i class='bx bx-plus'></i> Thêm Lịch Hẹn
        </button>
    </div>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead><tr><th>#</th><th>Khách hàng</th><th>SĐT</th><th>Thời gian</th><th>Ghi chú</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php if ($appts): foreach ($appts as $i => $a): ?>
                    <tr>
                        <td class="fs-13 text-muted"><?= $i+1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($a['lead_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($a['lead_phone'] ?? '—') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($a['datetime'])) ?></td>
                        <td><?= htmlspecialchars($a['note'] ?? '') ?></td>
                        <td><span class="badge-status badge-<?= $a['status']==='done'?'success':'warning' ?>"><?= $a['status']==='done'?'Hoàn thành':'Chờ' ?></span></td>
                        <td><button class="btn-icon edit"><i class='bx bx-edit'></i></button></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có lịch hẹn</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main></div>
<!-- Modal Appt -->
<div class="modal fade" id="modalAppt" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Lịch Hẹn</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Lead</label><select class="form-select" name="lead_id"><option>— Chọn khách hàng —</option></select></div>
            <div class="mb-3"><label class="form-label">Thời gian</label><input type="datetime-local" class="form-control" name="datetime"></div>
            <div class="mb-3"><label class="form-label">Ghi chú</label><textarea class="form-control" name="note" rows="3"></textarea></div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom">Lưu</button></div>
    </div></div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
