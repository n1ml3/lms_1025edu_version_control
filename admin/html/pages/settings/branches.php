<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Cơ Sở</h1>
            <p class="page-subtitle">Quản lý các cơ sở / chi nhánh của trung tâm</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalBranch">
            <i class='bx bx-plus'></i> Thêm Cơ Sở
        </button>
    </div>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead><tr><th>#</th><th>Tên cơ sở</th><th>Địa chỉ</th><th>Điện thoại</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php if ($branches): foreach ($branches as $i => $b): ?>
                    <tr>
                        <td class="fs-13 text-muted"><?= $i+1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($b['name']) ?></td>
                        <td><?= htmlspecialchars($b['address'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($b['phone'] ?? '—') ?></td>
                        <td class="fs-13 text-muted"><?= date('d/m/Y', strtotime($b['created_at'])) ?></td>
                        <td>
                            <button class="btn-icon edit btn-edit-branch"
                                data-id="<?= $b['id'] ?>"
                                data-name="<?= htmlspecialchars($b['name']) ?>"
                                data-address="<?= htmlspecialchars($b['address']) ?>"
                                data-phone="<?= htmlspecialchars($b['phone']) ?>">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-icon delete btn-delete-branch" data-id="<?= $b['id'] ?>" data-confirm="Xóa cơ sở này?"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có cơ sở nào</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main></div>

<!-- Modal Add/Edit Branch -->
<div class="modal fade" id="modalBranch" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Cơ Sở</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <form id="formBranch">
                <input type="hidden" name="id" id="branch_id">
                <div class="mb-3"><label class="form-label">Tên cơ sở <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" id="branch_name" required></div>
                <div class="mb-3"><label class="form-label">Địa chỉ</label><input type="text" class="form-control" name="address" id="branch_address"></div>
                <div class="mb-3"><label class="form-label">Số điện thoại</label><input type="text" class="form-control" name="phone" id="branch_phone"></div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="button" class="btn-primary-custom" id="btnSaveBranch">Lưu Thông Tin</button>
        </div>
    </div></div>
</div>

<?php
$inlineScript = <<<'JS'
$('#modalBranch').on('hidden.bs.modal', function () {
    $('#formBranch')[0].reset();
    $('#branch_id').val('');
    $('.modal-title').text('Thêm Cơ Sở');
});

$('.btn-edit-branch').on('click', function() {
    $('#branch_id').val($(this).data('id'));
    $('#branch_name').val($(this).data('name'));
    $('#branch_address').val($(this).data('address'));
    $('#branch_phone').val($(this).data('phone'));
    
    $('.modal-title').text('Sửa Cơ Sở');
    $('#modalBranch').modal('show');
});

$('#btnSaveBranch').on('click', function() {
    if(!$('#formBranch')[0].checkValidity()) {
        $('#formBranch')[0].reportValidity();
        return;
    }
    
    const id = $('#branch_id').val();
    const action = id ? 'update' : 'create';
    const data = $('#formBranch').serialize() + '&action=' + action;
    
    lmsAjax('/lms1025edu/admin/api/branches.php', data, function(res) {
        if(res.success) {
            lmsToast('success', 'Lưu cơ sở thành công!');
            $('#modalBranch').modal('hide');
            setTimeout(() => location.reload(), 1000);
        } else {
            lmsToast('danger', res.error || 'Lỗi hệ thống');
        }
    });
});

$('.btn-delete-branch').on('click', function() {
    const id = $(this).data('id');
    if(!confirm('Bạn có chắc chắn muốn xóa cơ sở này?')) return;

    lmsAjax('/lms1025edu/admin/api/branches.php', { action: 'delete', id: id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa cơ sở!');
            setTimeout(() => location.reload(), 1000);
        } else {
            lmsToast('danger', res.error || 'Lỗi khi xóa!');
        }
    });
});
JS;
?>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
