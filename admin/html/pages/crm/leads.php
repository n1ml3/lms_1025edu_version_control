<?php
require_once __DIR__ . '/../../../../config/db.php';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

// Fetch Leads with Joins
$stmt = $pdo->query("SELECT l.*, ls.name AS source_name, b.name AS branch_name 
                    FROM leads l 
                    LEFT JOIN lead_sources ls ON ls.id = l.source_id 
                    LEFT JOIN branches b ON b.id = l.branch_id 
                    ORDER BY l.created_at DESC");
$leads = $stmt->fetchAll();

// Fetch Sources for Select
$sources = $pdo->query("SELECT id, name FROM lead_sources ORDER BY name ASC")->fetchAll();

// Fetch Branches for Select
$branches = $pdo->query("SELECT id, name FROM branches ORDER BY name ASC")->fetchAll();

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalLead" onclick="resetLeadForm()">
    <i class='bx bx-plus'></i> Thêm Lead
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
        <!-- Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2 text-muted fs-14">
                <span>Hiển thị</span>
                <select class="form-select form-select-sm" style="width:70px; display:inline-block;">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>dòng</span>
            </div>
            <div class="d-flex align-items-center gap-2 text-muted fs-14">
                <span>Tìm kiếm:</span>
                <input type="text" class="form-control form-control-sm" style="width:200px;" id="tableSearch">
            </div>
        </div>

        <div class="content-card">
            <div class="table-responsive">
                <table class="table table-custom" id="leadsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Nguồn</th>
                            <th>Cơ sở</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($leads): foreach ($leads as $i => $l): ?>
                        <tr>
                            <td class="text-muted fs-13"><?= $i + 1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($l['name']) ?></td>
                            <td><?= htmlspecialchars($l['phone']) ?></td>
                            <td><?= htmlspecialchars($l['email']) ?></td>
                            <td><?= htmlspecialchars($l['source_name'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($l['branch_name'] ?? '—') ?></td>
                            <td>
                                <?php
                                $st = $l['status'] ?? 'new';
                                $badgeMap = ['new'=>'info','contacted'=>'warning','converted'=>'success','lost'=>'danger'];
                                $labelMap = ['new'=>'Mới','contacted'=>'Đã liên hệ','converted'=>'Chuyển đổi','lost'=>'Mất'];
                                ?>
                                <span class="badge-status badge-<?= $badgeMap[$st] ?? 'gray' ?>"><?= $labelMap[$st] ?? $st ?></span>
                            </td>
                            <td class="fs-13 text-muted"><?= date('d/m/Y', strtotime($l['created_at'])) ?></td>
                            <td>
                                <button class="btn-icon" onclick="editLead(<?= htmlspecialchars(json_encode($l)) ?>)"><i class='bx bx-edit'></i></button>
                                <button class="btn-icon text-danger" onclick="deleteLead(<?= $l['id'] ?>)"><i class='bx bx-trash'></i></button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="9" class="text-center py-5 text-muted">Chưa có dữ liệu lead</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Add/Edit Lead -->
<div class="modal fade" id="modalLead" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formLead" class="modal-content">
            <input type="hidden" name="id" id="lead_id">
            <div class="modal-header">
                <h5 class="modal-title" id="leadModalTitle">Thêm Lead Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nguồn</label>
                        <select class="form-select" name="source_id">
                            <option value="">— Chọn nguồn —</option>
                            <?php foreach($sources as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cơ sở</label>
                        <select class="form-select" name="branch_id">
                            <option value="">— Chọn cơ sở —</option>
                            <?php foreach($branches as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="new">Mới</option>
                            <option value="contacted">Đã liên hệ</option>
                            <option value="converted">Chuyển đổi</option>
                            <option value="lost">Mất</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="note" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom" id="btnSaveLead">Lưu Lead</button>
            </div>
        </form>
    </div>
</div>

<?php
$inlineScript = <<<'JS'
function resetLeadForm() {
    $('#formLead')[0].reset();
    $('#lead_id').val('');
    $('#leadModalTitle').text('Thêm Lead Mới');
}

function editLead(data) {
    resetLeadForm();
    $('#lead_id').val(data.id);
    $('#leadModalTitle').text('Sửa Lead');
    $('#formLead [name="name"]').val(data.name);
    $('#formLead [name="phone"]').val(data.phone);
    $('#formLead [name="email"]').val(data.email);
    $('#formLead [name="source_id"]').val(data.source_id);
    $('#formLead [name="branch_id"]').val(data.branch_id);
    $('#formLead [name="status"]').val(data.status);
    $('#formLead [name="note"]').val(data.note);
    $('#modalLead').modal('show');
}

$('#formLead').on('submit', function (e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(f => data[f.name] = f.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/leads.php', { action, ...data }, function (res) {
        if (res.success) {
            lmsToast('success', 'Đã lưu lead thành công!');
            $('#modalLead').modal('hide');
            setTimeout(() => location.reload(), 800);
        } else lmsToast('danger', res.error || 'Lỗi!');
    });
});

function deleteLead(id) {
    if(!confirm('Xóa lead này?')) return;
    lmsAjax('/lms1025edu/admin/api/leads.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa lead!');
            setTimeout(() => location.reload(), 800);
        }
    });
}

$('#tableSearch').on('keyup', function() {
    const val = $(this).val().toLowerCase();
    $('#leadsTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php';
?>
