<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAgent" onclick="resetAgentForm()">
    <i class='bx bx-plus'></i> Thêm Đại Lý
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
                <table class="table table-custom" id="agentsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Số điện thoại</th>
                            <th>Hoa hồng (%)</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($agents): foreach ($agents as $i => $a): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($a['name']) ?></td>
                            <td><?= htmlspecialchars($a['phone'] ?? '—') ?></td>
                            <td><span class="badge-status badge-success"><?= $a['commission_rate'] ?? 0 ?>%</span></td>
                            <td>
                                <button class="btn-icon" onclick="editAgent(<?= htmlspecialchars(json_encode($a)) ?>)">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn-icon text-danger" onclick="deleteAgent(<?= $a['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Chưa có đại lý nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Agent -->
<div class="modal fade" id="modalAgent" tabindex="-1">
    <div class="modal-dialog">
        <form id="formAgent" class="modal-content">
            <input type="hidden" name="id" id="agent_id">
            <div class="modal-header">
                <h5 class="modal-title" id="agentModalTitle">Thêm Đại Lý</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Họ tên *</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone">
                </div>
                <div class="mb-3">
                    <label class="form-label">Hoa hồng (%)</label>
                    <input type="number" class="form-control" name="commission_rate" min="0" max="100" step="0.1" value="10">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom">Lưu đại lý</button>
            </div>
        </form>
    </div>
</div>

<?php 
$inlineScript = <<<JS
function resetAgentForm() {
    $('#formAgent')[0].reset();
    $('#agent_id').val('');
    $('#agentModalTitle').text('Thêm Đại Lý');
}

function editAgent(data) {
    resetAgentForm();
    $('#agent_id').val(data.id);
    $('#agentModalTitle').text('Sửa Đại Lý');
    $('[name="name"]').val(data.name);
    $('[name="phone"]').val(data.phone);
    $('[name="commission_rate"]').val(data.commission_rate);
    $('#modalAgent').modal('show');
}

$('#formAgent').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/agents.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã lưu đại lý thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteAgent(id) {
    if(!confirm('Xóa đại lý này?')) return;
    lmsAjax('/lms1025edu/admin/api/agents.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa đại lý!');
            setTimeout(() => location.reload(), 800);
        }
    });
}

$('#tableSearch').on('keyup', function() {
    const val = $(this).val().toLowerCase();
    $('#agentsTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
