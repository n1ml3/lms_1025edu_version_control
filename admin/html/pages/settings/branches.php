<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalBranch" onclick="resetBranchForm()">
    <i class='bx bx-plus'></i> Thêm Cơ Sở
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
                            <th>Tên cơ sở</th>
                            <th>Địa chỉ</th>
                            <th>Điện thoại</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($branches): foreach ($branches as $i => $b): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($b['name']) ?></td>
                            <td><?= htmlspecialchars($b['address'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($b['phone'] ?? '—') ?></td>
                            <td class="fs-13 text-muted"><?= date('d/m/Y', strtotime($b['created_at'])) ?></td>
                            <td>
                                <button class="btn-icon" onclick="editBranch(<?= htmlspecialchars(json_encode($b)) ?>)">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn-icon text-danger" onclick="deleteBranch(<?= $b['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có cơ sở nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Add/Edit Branch -->
<div class="modal fade" id="modalBranch" tabindex="-1">
    <div class="modal-dialog">
        <form id="formBranch" class="modal-content">
            <input type="hidden" name="id" id="branch_id">
            <div class="modal-header">
                <h5 class="modal-title" id="branchModalTitle">Thêm Cơ Sở</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên cơ sở <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address">
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom">Lưu Thông Tin</button>
            </div>
        </form>
    </div>
</div>

<?php
$inlineScript = <<<'JS'
function resetBranchForm() {
    $('#formBranch')[0].reset();
    $('#branch_id').val('');
    $('#branchModalTitle').text('Thêm Cơ Sở');
}

function editBranch(data) {
    resetBranchForm();
    $('#branch_id').val(data.id);
    $('#branchModalTitle').text('Sửa Cơ Sở');
    $('[name="name"]').val(data.name);
    $('[name="address"]').val(data.address);
    $('[name="phone"]').val(data.phone);
    $('#modalBranch').modal('show');
}

$('#formBranch').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/branches.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Lưu cơ sở thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteBranch(id) {
    if(!confirm('Bạn có chắc chắn muốn xóa cơ sở này?')) return;
    lmsAjax('/lms1025edu/admin/api/branches.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa cơ sở!');
            setTimeout(() => location.reload(), 800);
        }
    });
}
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
