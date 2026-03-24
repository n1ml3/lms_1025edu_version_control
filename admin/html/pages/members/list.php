require_once __DIR__ . '/../../html/layouts/header.php';
require_once __DIR__ . '/../../html/layouts/sidebar.php';
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
                            <button class="btn-icon edit"><i class='bx bx-edit'></i></button>
                            <?php if ($a['id'] != $adminId): ?>
                            <button class="btn-icon delete" data-confirm="Xóa tài khoản này?"><i class='bx bx-trash'></i></button>
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
        <div class="modal-header"><h5 class="modal-title">Thêm Tài Khoản</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Họ tên</label><input type="text" class="form-control" name="name"></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email"></div>
            <div class="mb-3"><label class="form-label">Mật khẩu</label><input type="password" class="form-control" name="password"></div>
            <div class="mb-3"><label class="form-label">Vai trò</label><select class="form-select"><option>Admin</option><option>Staff</option></select></div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom">Tạo Tài Khoản</button></div>
    </div></div>
</div>
<?php require_once __DIR__ . '/../../html/layouts/footer.php'; ?>
