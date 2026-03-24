<?php
/**
 * Settings — Lưu Trữ
 */
require_once __DIR__ . '/../../includes/auth_check.php';

$pageTitle  = 'Lưu Trữ';
$activePage = 'settings_storage';
$breadcrumb = [['label'=>'Cài Đặt'],['label'=>'Lưu trữ']];

// Disk usage info
$uploadPath = __DIR__ . '/../../assets/img/uploads/';
@mkdir($uploadPath, 0755, true);
$totalFiles = count(glob($uploadPath . '*'));
$diskFree   = @disk_free_space(dirname($uploadPath));
$diskTotal  = @disk_total_space(dirname($uploadPath));
$diskUsedPct = $diskTotal ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 1) : 0;

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header">
        <h1 class="page-title">Lưu Trữ</h1>
        <p class="page-subtitle">Thông tin dung lượng và quản lý file</p>
    </div>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="content-card">
                <div class="content-card-header"><h3 class="content-card-title">Dung Lượng Ổ Đĩa</h3></div>
                <div class="content-card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fs-13 text-muted">Đã dùng</span>
                        <span class="fw-semibold"><?= $diskUsedPct ?>%</span>
                    </div>
                    <div class="progress" style="height:10px;border-radius:8px">
                        <div class="progress-bar <?= $diskUsedPct > 80 ? 'bg-danger' : 'bg-primary' ?>"
                             style="width:<?= $diskUsedPct ?>%;border-radius:8px"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="fs-12 text-muted">Trống: <?= $diskFree ? round($diskFree/1024/1024/1024, 1).'GB' : 'N/A' ?></span>
                        <span class="fs-12 text-muted">Tổng: <?= $diskTotal ? round($diskTotal/1024/1024/1024, 1).'GB' : 'N/A' ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-card">
                <div class="content-card-header"><h3 class="content-card-title">Thư Mục Upload</h3></div>
                <div class="content-card-body">
                    <div class="stat-card" style="border:none;padding:0;box-shadow:none">
                        <div class="stat-icon green"><i class='bx bx-folder'></i></div>
                        <div class="stat-info">
                            <p class="stat-label">Tổng file</p>
                            <div class="stat-value"><?= $totalFiles ?></div>
                            <p class="stat-change up">uploads/</p>
                        </div>
                    </div>
                    <hr>
                    <a href="/lms1025edu/admin/pages/settings/media.php" class="btn-primary-custom d-inline-flex">
                        <i class='bx bx-images'></i> Xem tất cả media
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="content-card">
                <div class="content-card-header"><h3 class="content-card-title">Thông Tin Hệ Thống</h3></div>
                <div class="content-card-body">
                    <table class="table table-custom">
                        <tbody>
                            <tr><td class="fw-semibold" style="width:220px">PHP Version</td><td><?= PHP_VERSION ?></td></tr>
                            <tr><td class="fw-semibold">Server Software</td><td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?></td></tr>
                            <tr><td class="fw-semibold">Max Upload Size</td><td><?= ini_get('upload_max_filesize') ?></td></tr>
                            <tr><td class="fw-semibold">Max POST Size</td><td><?= ini_get('post_max_size') ?></td></tr>
                            <tr><td class="fw-semibold">Memory Limit</td><td><?= ini_get('memory_limit') ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main></div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
