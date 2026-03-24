<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Quản Trị Viên</h1>
            <p class="page-subtitle">Danh sách tài khoản admin và nhân viên</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAdmin">
            <i class='bx bx-plus'></i> Thêm Tài Khoản
        </button>
    </div>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead><tr><th>#</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Ngày tạo</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php if ($admins): foreach ($admins as $i => $a): ?>
                    <tr>
                        <td class="fs-13 text-muted"><?= $i+1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($a['name']) ?></td>
                        <td><?= htmlspecialchars($a['email']) ?></td>
                        <td><span class="badge-status badge-info"><?= htmlspecialchars($a['role_name'] ?? 'Staff') ?></span></td>
                        <td class="fs-13 text-muted"><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
                        <td><span class="badge-status badge-<?= ($a['is_active'] ?? 1) ? 'success' : 'gray' ?>"><?= ($a['is_active'] ?? 1) ? 'Hoạt động' : 'Vô hiệu' ?></span></td>
                        <td>
                            <button class="btn-icon edit btn-edit-admin"
                                data-id="<?= $a['id'] ?>"
                                data-name="<?= htmlspecialchars($a['name']) ?>"
                                data-email="<?= htmlspecialchars($a['email']) ?>"
                                data-role="<?= $a['role_id'] ?>"
                                data-active="<?= $a['is_active'] ?? 1 ?>">
                                <i class='bx bx-edit'></i>
                            </button>
                            <?php if ($a['id'] != $adminId): ?>
                            <button class="btn-icon delete btn-delete-admin" data-id="<?= $a['id'] ?>" data-confirm="Xóa tài khoản này?"><i class='bx bx-trash'></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có tài khoản nào</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main></div>
<!-- Modal Add Admin -->
<div class="modal fade" id="modalAdmin" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Tài Khoản</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <form id="formAdmin">
                <input type="hidden" name="id" id="admin_id">
                <div class="mb-3"><label class="form-label">Họ tên</label><input type="text" class="form-control" name="name" id="admin_name" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" id="admin_email" required></div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu <span id="pwd-hint" class="text-muted fw-normal fs-12"></span></label>
                    <input type="password" class="form-control" name="password" id="admin_password">
                </div>
                <div class="mb-3"><label class="form-label">Vai trò</label>
                    <select class="form-select" name="role_id" id="admin_role" required>
                        <option value="">-- Chọn vai trò --</option>
                        <?php foreach($roles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3 form-check" id="active-wrapper">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="admin_active" checked>
                    <label class="form-check-label" for="admin_active">Kích hoạt tài khoản</label>
                </div>
            </form>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button type="button" class="btn-primary-custom" id="btnSaveAdmin">Lưu Tài Khoản</button></div>
    </div></div>
</div>

<?php
$inlineScript = <<<'JS'
$('#modalAdmin').on('hidden.bs.modal', function () {
    $('#formAdmin')[0].reset();
    $('#admin_id').val('');
    $('#admin_password').prop('required', true);
    $('#pwd-hint').text('');
    $('.modal-title').text('Thêm Tài Khoản');
    $('#admin_active').prop('checked', true);
});

$('.btn-edit-admin').on('click', function() {
    const id = $(this).data('id');
    $('#admin_id').val(id);
    $('#admin_name').val($(this).data('name'));
    $('#admin_email').val($(this).data('email'));
    $('#admin_role').val($(this).data('role'));
    $('#admin_active').prop('checked', $(this).data('active') == 1);
    
    // For edit, password is empty = no change
    $('#admin_password').prop('required', false);
    $('#pwd-hint').text('(Để trống nếu không đổi)');
    
    $('.modal-title').text('Sửa Tài Khoản');
    $('#modalAdmin').modal('show');
});

$('#btnSaveAdmin').on('click', function() {
    // Validate form natively
    if(!$('#formAdmin')[0].checkValidity()) {
        $('#formAdmin')[0].reportValidity();
        return;
    }
    
    const id = $('#admin_id').val();
    const action = id ? 'update' : 'create';
    const data = $('#formAdmin').serialize() + '&action=' + action;
    
    lmsAjax('/lms1025edu/admin/api/admins.php', data, function(res) {
        if(res.success) {
            lmsToast('success', 'Lưu tài khoản thành công!');
            $('#modalAdmin').modal('hide');
            setTimeout(() => location.reload(), 1000);
        } else {
            lmsToast('danger', res.error || 'Lỗi hệ thống');
        }
    });
});

$('.btn-delete-admin').on('click', function() {
    const id = $(this).data('id');
    const msg = $(this).data('confirm') || 'Bạn có chắc chắn muốn xóa?';
    if(!confirm(msg)) return;

    lmsAjax('/lms1025edu/admin/api/admins.php', { action: 'delete', id: id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa tài khoản!');
            setTimeout(() => location.reload(), 1000);
        } else {
            lmsToast('danger', res.error || 'Lỗi khi xóa!');
        }
    });
});
JS;
?>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
