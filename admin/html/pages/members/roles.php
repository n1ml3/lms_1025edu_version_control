<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Phân Quyền</h1>
            <p class="page-subtitle">Quản lý vai trò và quyền hạn trong hệ thống</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalRole">
            <i class='bx bx-plus'></i> Thêm Vai Trò
        </button>
    </div>
    <div class="row g-4">
<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Phân Quyền</h1>
            <p class="page-subtitle">Quản lý vai trò và quyền hạn trong hệ thống</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalRole">
            <i class='bx bx-plus'></i> Thêm Vai Trò
        </button>
    </div>
    <div class="row g-4">
        <?php if ($roles): foreach ($roles as $role):
            $perms = is_string($role['permissions']) ? json_decode($role['permissions'], true) : ($role['permissions'] ?? []);
        ?>
        <div class="col-md-6 col-xl-4">
            <div class="content-card">
                <div class="content-card-header">
                    <h3 class="content-card-title"><?= htmlspecialchars($role['name']) ?></h3>
                    <div>
                        <button class="btn-icon edit btn-edit-role" 
                            data-id="<?= $role['id'] ?>" 
                            data-name="<?= htmlspecialchars($role['name']) ?>" 
                            data-perms="<?= htmlspecialchars(json_encode($perms)) ?>">
                            <i class='bx bx-edit'></i>
                        </button>
                        <button class="btn-icon delete btn-delete-role" data-id="<?= $role['id'] ?>" data-confirm="Xóa vai trò này?"><i class='bx bx-trash'></i></button>
                    </div>
                </div>
                <div class="content-card-body">
                    <?php if ($perms): ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($perms as $p): ?>
                        <span class="badge-status badge-info"><?= htmlspecialchars($p) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted fs-13 mb-0">Chưa có quyền nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div class="col-12">
            <div class="content-card content-card-body text-center py-5 text-muted">
                <i class='bx bx-shield-x d-block mb-2' style="font-size:36px"></i>
                Chưa có vai trò nào. Thêm vai trò đầu tiên!
            </div>
        </div>
        <?php endif; ?>
    </div>
</main></div>
<div class="modal fade" id="modalRole" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Vai Trò</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <form id="formRole">
                <input type="hidden" name="id" id="role_id">
                <div class="mb-3">
                    <label class="form-label">Tên vai trò</label>
                    <input type="text" class="form-control" name="name" id="role_name" placeholder="VD: Manager" required>
                </div>
                <div class="mb-3"><label class="form-label d-block">Quyền hạn</label>
                    <?php foreach (['dashboard','crm','members','courses','products','instructors','promotions','settings'] as $perm): ?>
                    <div class="form-check">
                        <input class="form-check-input role-perm-checkbox" type="checkbox" name="permissions[]" value="<?= $perm ?>" id="perm_<?= $perm ?>">
                        <label class="form-check-label fs-13" for="perm_<?= $perm ?>"><?= ucfirst($perm) ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom" id="btnSaveRole">Lưu</button></div>
    </div></div>
</div>

<?php
$inlineScript = <<<'JS'
$('#modalRole').on('hidden.bs.modal', function () {
    $('#formRole')[0].reset();
    $('#role_id').val('');
    $('.modal-title').text('Thêm Vai Trò');
});

$('.btn-edit-role').on('click', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    const perms = $(this).data('perms'); // array

    $('#role_id').val(id);
    $('#role_name').val(name);
    $('.modal-title').text('Sửa Vai Trò');

    // Check checkboxes
    $('.role-perm-checkbox').prop('checked', false);
    if(Array.isArray(perms)) {
        perms.forEach(p => {
            $(`#perm_${p}`).prop('checked', true);
        });
    }
    
    $('#modalRole').modal('show');
});

$('#btnSaveRole').on('click', function() {
    const id = $('#role_id').val();
    const action = id ? 'update' : 'create';
    
    // Serialize
    const data = $('#formRole').serialize() + '&action=' + action;
    
    lmsAjax('/lms1025edu/admin/api/roles.php', data, function(res) {
        if(res.success) {
            lmsToast('success', 'Lưu vai trò thành công!');
            $('#modalRole').modal('hide');
            setTimeout(() => location.reload(), 1000);
        } else {
            lmsToast('danger', res.error || 'Lỗi hệ thống');
        }
    });
});

$('.btn-delete-role').on('click', function() {
    const id = $(this).data('id');
    const msg = $(this).data('confirm') || 'Bạn có chắc chắn muốn xóa?';
    if(!confirm(msg)) return;

    lmsAjax('/lms1025edu/admin/api/roles.php', { action: 'delete', id: id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa vai trò!');
            setTimeout(() => location.reload(), 1000);
        } else {
            lmsToast('danger', res.error || 'Lỗi khi xóa!');
        }
    });
});
JS;
?>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
