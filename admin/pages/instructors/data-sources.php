<?php
/**
 * Instructors — Nguồn Dữ Liệu
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Nguồn Dữ Liệu';
$activePage = 'inst_sources';
$breadcrumb = [['label'=>'Giảng Viên'],['label'=>'Nguồn dữ liệu']];

try {
    $sources = $pdo->query("SELECT * FROM lead_sources ORDER BY name")->fetchAll();
} catch (Exception $e) { $sources = []; }

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Nguồn Dữ Liệu</h1>
            <p class="page-subtitle">Quản lý nguồn lead / nguồn dữ liệu tuyển sinh</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalSource">
            <i class='bx bx-plus'></i> Thêm Nguồn
        </button>
    </div>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead><tr><th>#</th><th>Tên nguồn</th><th>Số lead</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php if ($sources): foreach ($sources as $i => $s): ?>
                    <tr>
                        <td class="fs-13 text-muted"><?= $i+1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($s['name']) ?></td>
                        <td class="text-muted fs-13">—</td>
                        <td><button class="btn-icon edit"><i class='bx bx-edit'></i></button><button class="btn-icon delete"><i class='bx bx-trash'></i></button></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-5 text-muted">Chưa có nguồn dữ liệu</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main></div>
<div class="modal fade" id="modalSource" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Nguồn</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><div class="mb-3"><label class="form-label">Tên nguồn</label><input type="text" class="form-control" placeholder="VD: Facebook, Google, Zalo..."></div></div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom">Lưu</button></div>
    </div></div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
