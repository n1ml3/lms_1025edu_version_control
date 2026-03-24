<?php
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

// Fetch Appointments
$stmt = $pdo->query("SELECT a.*, l.name AS lead_name, l.phone AS lead_phone, b.name AS branch_name, adm.name AS staff_name 
                    FROM appointments a 
                    LEFT JOIN leads l ON l.id = a.lead_id 
                    LEFT JOIN branches b ON b.id = a.branch_id 
                    LEFT JOIN admins adm ON adm.id = a.staff_id 
                    ORDER BY a.appt_date DESC, a.appt_time DESC");
$appts = $stmt->fetchAll();

// Fetch Leads, Branches, Admins for Modal
$leads = $pdo->query("SELECT id, name, phone FROM leads ORDER BY name ASC")->fetchAll();
$branches = $pdo->query("SELECT id, name FROM branches ORDER BY name ASC")->fetchAll();
$admins = $pdo->query("SELECT id, name FROM admins WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAppt" onclick="resetApptForm()">
    <i class='bx bx-plus'></i> Thêm Lịch Hẹn
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
        <div class="content-card">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Khách hàng</th>
                            <th>SĐT</th>
                            <th>Thời gian</th>
                            <th>Nhân viên</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($appts): foreach ($appts as $i => $a): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($a['lead_name'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($a['lead_phone'] ?? '—') ?></td>
                            <td class="fs-13">
                                <div><?= date('d/m/Y', strtotime($a['appt_date'])) ?></div>
                                <div class="text-muted"><?= date('H:i', strtotime($a['appt_time'])) ?></div>
                            </td>
                            <td><?= htmlspecialchars($a['staff_name'] ?? '—') ?></td>
                            <td>
                                <?php 
                                $statusMap = ['scheduled'=>'warning', 'completed'=>'success', 'cancelled'=>'danger', 'no_show'=>'secondary'];
                                $statusText = ['scheduled'=>'Chờ', 'completed'=>'Hoàn thành', 'cancelled'=>'Hủy', 'no_show'=>'K.Đến'];
                                ?>
                                <span class="badge-status badge-<?= $statusMap[$a['status']] ?? 'secondary' ?>">
                                    <?= $statusText[$a['status']] ?? $a['status'] ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn-icon" onclick="editAppt(<?= htmlspecialchars(json_encode($a)) ?>)">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn-icon text-danger" onclick="deleteAppt(<?= $a['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có lịch hẹn nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Appt -->
<div class="modal fade" id="modalAppt" tabindex="-1">
    <div class="modal-dialog">
        <form id="formAppt" class="modal-content">
            <input type="hidden" name="id" id="appt_id">
            <div class="modal-header">
                <h5 class="modal-title" id="apptModalTitle">Thêm Lịch Hẹn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Chọn khách hàng *</label>
                    <select class="form-select" name="lead_id" required>
                        <option value="">— Tìm khách hàng —</option>
                        <?php foreach($leads as $l): ?>
                            <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['name']) ?> (<?= $l['phone'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Ngày hẹn</label>
                        <input type="date" class="form-control" name="appt_date" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Giờ hẹn</label>
                        <input type="time" class="form-control" name="appt_time" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cơ sở / Phòng</label>
                    <select class="form-select" name="branch_id">
                        <option value="">— Chọn cơ sở —</option>
                        <?php foreach($branches as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nhân viên phụ trách</label>
                    <select class="form-select" name="staff_id">
                        <option value="">— Chọn nhân viên —</option>
                        <?php foreach($admins as $adm): ?>
                            <option value="<?= $adm['id'] ?>"><?= htmlspecialchars($adm['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3" id="statusField" style="display:none">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                        <option value="scheduled">Chờ</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="cancelled">Hủy</option>
                        <option value="no_show">K.Đến</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control" name="note" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom">Lưu lịch hẹn</button>
            </div>
        </form>
    </div>
</div>

<?php 
$inlineScript = <<<JS
function resetApptForm() {
    $('#formAppt')[0].reset();
    $('#appt_id').val('');
    $('#apptModalTitle').text('Thêm Lịch Hẹn');
    $('#statusField').hide();
}

function editAppt(data) {
    resetApptForm();
    $('#appt_id').val(data.id);
    $('#apptModalTitle').text('Sửa Lịch Hẹn');
    $('#statusField').show();
    $('[name="lead_id"]').val(data.lead_id);
    $('[name="appt_date"]').val(data.appt_date);
    $('[name="appt_time"]').val(data.appt_time);
    $('[name="branch_id"]').val(data.branch_id);
    $('[name="staff_id"]').val(data.staff_id);
    $('[name="status"]').val(data.status);
    $('[name="note"]').val(data.note);
    $('#modalAppt').modal('show');
}

$('#formAppt').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/appointments.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã lưu lịch hẹn thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteAppt(id) {
    if(!confirm('Xóa lịch hẹn này?')) return;
    lmsAjax('/lms1025edu/admin/api/appointments.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa lịch hẹn!');
            setTimeout(() => location.reload(), 800);
        }
    });
}
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
