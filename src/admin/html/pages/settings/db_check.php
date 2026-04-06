<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" onclick="location.reload()">
    <i class='bx bx-refresh'></i> Kiểm tra lại
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="content-card h-100">
                <div class="content-card-header"><h3 class="content-card-title">Trạng thái kết nối</h3></div>
                <div class="content-card-body">
                    <?php if ($status === 'Connected'): ?>
                        <div class="alert alert-success border-0 rounded-3 mb-4 d-flex align-items-center gap-2">
                            <i class='bx bxs-check-circle fs-4'></i>
                            <div>Kết nối tới cơ sở dữ liệu thành công!</div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger border-0 rounded-3 mb-4 d-flex align-items-center gap-2">
                            <i class='bx bxs-error-circle fs-4'></i>
                            <div>Lỗi kết nối: <?= htmlspecialchars($errorMsg) ?></div>
                        </div>
                    <?php endif; ?>

                    <ul class="list-group list-group-flush mt-0">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-0">
                            <span class="text-muted fs-13">Host/Server</span>
                            <span class="fw-semibold">localhost</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-top">
                            <span class="text-muted fs-13">Cơ sở dữ liệu (Database)</span>
                            <span class="fw-semibold"><?= htmlspecialchars($dbInfo['database'] ?? 'N/A') ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-top">
                            <span class="text-muted fs-13">Người dùng (User)</span>
                            <span class="fw-semibold"><?= htmlspecialchars($dbInfo['user'] ?? 'N/A') ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-top">
                            <span class="text-muted fs-13">Phiên bản MySQL/MariaDB</span>
                            <span class="fw-semibold"><?= htmlspecialchars($dbInfo['version'] ?? 'N/A') ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="content-card h-100">
                <div class="content-card-header"><h3 class="content-card-title">Danh sách Bảng (Tables)</h3></div>
                <div class="content-card-body">
                    <?php if (!empty($dbInfo['tables'])): ?>
                        <div class="d-flex flex-wrap gap-2">
                        <?php foreach($dbInfo['tables'] as $tbl): ?>
                            <span class="badge border text-dark px-3 py-2 fw-normal fs-13" style="background:var(--bg-light)">
                                <i class='bx bx-table text-muted me-1'></i> <?= htmlspecialchars($tbl) ?>
                            </span>
                        <?php endforeach; ?>
                        </div>
                        <div class="mt-4 text-muted fs-13">
                            Tổng cộng: <strong><?= count($dbInfo['tables']) ?></strong> bảng được tìm thấy.
                        </div>
                    <?php else: ?>
                        <p class="text-muted fs-13">Không có dữ liệu bảng.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main></div>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
