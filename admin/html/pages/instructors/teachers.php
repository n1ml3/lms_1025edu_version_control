require_once __DIR__ . '/../../html/layouts/header.php';
require_once __DIR__ . '/../../html/layouts/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Giáo Viên</h1>
            <p class="page-subtitle">Danh sách giảng viên của hệ thống</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalTeacher">
            <i class='bx bx-plus'></i> Thêm Giáo Viên
        </button>
    </div>
    <div class="row g-3">
        <?php if ($teachers): foreach ($teachers as $t): ?>
        <div class="col-sm-6 col-xl-4">
            <div class="content-card">
                <div class="content-card-body d-flex align-items-center gap-3">
                    <div class="avatar-circle flex-shrink-0" style="width:48px;height:48px;border-radius:14px;font-size:20px"><?= strtoupper(substr($t['name'],0,1)) ?></div>
                    <div class="flex-1">
                        <div class="fw-semibold"><?= htmlspecialchars($t['name']) ?></div>
                        <div class="fs-13 text-muted"><?= htmlspecialchars($t['email'] ?? '') ?></div>
                        <div class="fs-13 text-muted"><?= htmlspecialchars($t['phone'] ?? '') ?></div>
                    </div>
                    <div class="d-flex gap-1 ms-auto">
                        <button class="btn-icon edit"><i class='bx bx-edit'></i></button>
                        <button class="btn-icon delete" data-confirm="Xóa giáo viên?"><i class='bx bx-trash'></i></button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div class="col-12"><div class="content-card content-card-body text-center py-5 text-muted">Chưa có giáo viên nào</div></div>
        <?php endif; ?>
    </div>
</main></div>
<div class="modal fade" id="modalTeacher" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Giáo Viên</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Họ tên</label><input type="text" class="form-control" name="name"></div>
            <div class="mb-3"><label class="form-label">Số điện thoại</label><input type="text" class="form-control" name="phone"></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email"></div>
            <div class="mb-3"><label class="form-label">Tiểu sử</label><textarea class="form-control" rows="3"></textarea></div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom">Lưu</button></div>
    </div></div>
</div>
<?php require_once __DIR__ . '/../../html/layouts/footer.php'; ?>
