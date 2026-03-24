<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Đại Lý</h1>
            <p class="page-subtitle">Quản lý đại lý tuyển sinh</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAgent">
            <i class='bx bx-plus'></i> Thêm Đại Lý
        </button>
    </div>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead><tr><th>#</th><th>Họ tên</th><th>SĐT</th><th>Hoa hồng (%)</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php if ($agents): foreach ($agents as $i => $a): ?>
                    <tr>
                        <td class="fs-13 text-muted"><?= $i+1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($a['name']) ?></td>
                        <td><?= htmlspecialchars($a['phone'] ?? '—') ?></td>
                        <td><span class="badge-status badge-success"><?= $a['commission_rate'] ?? 0 ?>%</span></td>
                        <td><button class="btn-icon edit"><i class='bx bx-edit'></i></button><button class="btn-icon delete"><i class='bx bx-trash'></i></button></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">Chưa có đại lý nào</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main></div>
<div class="modal fade" id="modalAgent" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Đại Lý</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Họ tên</label><input type="text" class="form-control"></div>
            <div class="mb-3"><label class="form-label">Số điện thoại</label><input type="text" class="form-control"></div>
            <div class="mb-3"><label class="form-label">Hoa hồng (%)</label><input type="number" class="form-control" min="0" max="100" step="0.5" value="10"></div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom">Lưu</button></div>
    </div></div>
</div>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
