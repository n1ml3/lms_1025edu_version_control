<?php
/**
 * Promotions — Mã Giảm Giá
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Mã Giảm Giá';
$activePage = 'promo_coupons';
$breadcrumb = [['label'=>'Khuyến Mãi'],['label'=>'Mã giảm giá']];

try {
    $coupons = $pdo->query("SELECT * FROM coupons ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) { $coupons = []; }

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Mã Giảm Giá</h1>
            <p class="page-subtitle">Quản lý coupon và khuyến mãi</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalCoupon">
            <i class='bx bx-plus'></i> Tạo Mã Giảm
        </button>
    </div>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead><tr><th>#</th><th>Mã code</th><th>Loại</th><th>Giá trị</th><th>Hết hạn</th><th>Đã dùng / Max</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php if ($coupons): foreach ($coupons as $i => $c): ?>
                    <tr>
                        <td class="fs-13 text-muted"><?= $i+1 ?></td>
                        <td><code class="fw-semibold fs-13" style="background:var(--primary-light);color:var(--primary);padding:3px 8px;border-radius:6px"><?= htmlspecialchars($c['code']) ?></code></td>
                        <td><span class="badge-status badge-<?= $c['type']==='percent'?'info':'success' ?>"><?= $c['type']==='percent' ? 'Phần trăm' : 'Cố định' ?></span></td>
                        <td class="fw-semibold"><?= $c['type']==='percent' ? $c['value'].'%' : number_format($c['value']).' đ' ?></td>
                        <td class="fs-13 text-muted"><?= $c['expires_at'] ? date('d/m/Y', strtotime($c['expires_at'])) : '∞' ?></td>
                        <td class="fs-13"><?= $c['used_count'] ?> / <?= $c['usage_limit'] ?? '∞' ?></td>
                        <td><button class="btn-icon edit"><i class='bx bx-edit'></i></button><button class="btn-icon delete" data-confirm="Xóa mã này?"><i class='bx bx-trash'></i></button></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có mã giảm giá nào</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main></div>
<div class="modal fade" id="modalCoupon" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tạo Mã Giảm Giá</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Mã code</label><div class="input-group"><input type="text" class="form-control text-uppercase" name="code" placeholder="SUMMER2024"><button class="btn btn-outline-secondary" type="button" id="btnGenCode">Tạo ngẫu nhiên</button></div></div>
            <div class="row g-3">
                <div class="col-sm-6"><label class="form-label">Loại</label><select class="form-select" id="couponType"><option value="percent">Phần trăm (%)</option><option value="fixed">Cố định (đ)</option></select></div>
                <div class="col-sm-6"><label class="form-label">Giá trị</label><input type="number" class="form-control" name="value" min="0" value="10"></div>
                <div class="col-sm-6"><label class="form-label">Ngày hết hạn</label><input type="date" class="form-control" name="expires_at"></div>
                <div class="col-sm-6"><label class="form-label">Giới hạn dùng</label><input type="number" class="form-control" name="usage_limit" placeholder="Không giới hạn" min="1"></div>
            </div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom">Tạo Mã</button></div>
    </div></div>
</div>
<?php
$inlineScript = <<<'JS'
$('#btnGenCode').on('click', function () {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) code += chars[Math.floor(Math.random() * chars.length)];
    $('[name="code"]').val(code);
});
JS;
require_once __DIR__ . '/../../includes/footer.php'; ?>
