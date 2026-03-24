require_once __DIR__ . '/../../html/layouts/header.php';
require_once __DIR__ . '/../../html/layouts/sidebar.php';
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
                    <button class="btn-icon edit"><i class='bx bx-edit'></i></button>
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
            <div class="mb-3"><label class="form-label">Tên vai trò</label><input type="text" class="form-control" name="name" placeholder="VD: Manager"></div>
            <div class="mb-3"><label class="form-label d-block">Quyền hạn</label>
                <?php foreach (['dashboard','crm','members','courses','products','instructors','promotions','settings'] as $perm): ?>
                <div class="form-check"><input class="form-check-input" type="checkbox" value="<?= $perm ?>" id="perm_<?= $perm ?>"><label class="form-check-label fs-13" for="perm_<?= $perm ?>"><?= ucfirst($perm) ?></label></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom">Lưu</button></div>
    </div></div>
</div>
<?php require_once __DIR__ . '/../../html/layouts/footer.php'; ?>
