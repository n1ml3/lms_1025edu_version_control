<?php require_once __DIR__ . '/../../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../layouts/sidebar.php'; ?>

<div class="main-area"><main class="page-content bg-white">
    <div class="page-header d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
        <div>
            <h1 class="page-title fs-4 mb-0 text-dark">Kiểm tra kết nối Database</h1>
            <p class="text-muted mt-1 mb-0 fs-14">Thông tin cấu hình và kết nối cơ sở dữ liệu hiện tại</p>
        </div>
        <button class="btn btn-info text-white px-4 py-2" style="background-color:#0dcaf0; border:none; border-radius:6px; font-weight:500;" onclick="location.reload()">
            <i class='bx bx-refresh me-2'></i> Kiểm tra lại
        </button>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4 text-primary">Trạng thái kết nối</h5>
                    <?php if ($status === 'Connected'): ?>
                        <div class="alert alert-success d-flex align-items-center bg-success text-white border-0" role="alert">
                            <i class='bx bxs-check-circle fs-4 me-2'></i>
                            <div>Kết nối tới cơ sở dữ liệu thành công!</div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger d-flex align-items-center bg-danger text-white border-0" role="alert">
                            <i class='bx bxs-error-circle fs-4 me-2'></i>
                            <div>Lỗi kết nối: <?= htmlspecialchars($errorMsg) ?></div>
                        </div>
                    <?php endif; ?>

                    <ul class="list-group list-group-flush mt-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                            <span class="text-muted">Host/Server</span>
                            <span class="fw-semibold text-dark">localhost</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                            <span class="text-muted">Cơ sở dữ liệu (Database)</span>
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($dbInfo['database'] ?? 'N/A') ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                            <span class="text-muted">Người dùng (User)</span>
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($dbInfo['user'] ?? 'N/A') ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                            <span class="text-muted">Phiên bản MySQL/MariaDB</span>
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($dbInfo['version'] ?? 'N/A') ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4 text-primary">Danh sách Bảng (Tables)</h5>
                    <?php if (!empty($dbInfo['tables'])): ?>
                        <div class="d-flex flex-wrap gap-2">
                        <?php foreach($dbInfo['tables'] as $tbl): ?>
                            <span class="badge bg-light text-dark border px-3 py-2 fw-normal" style="font-size:13px;">
                                <i class='bx bx-table text-muted me-1'></i> <?= htmlspecialchars($tbl) ?>
                            </span>
                        <?php endforeach; ?>
                        </div>
                        <div class="mt-4 text-muted fs-14">
                            Tổng cộng: <strong><?= count($dbInfo['tables']) ?></strong> bảng được tìm thấy.
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Không có dữ liệu bảng.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main></div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
