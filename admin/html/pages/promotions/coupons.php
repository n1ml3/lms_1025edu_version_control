<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalCoupon" onclick="resetCouponForm()">
    <i class='bx bx-plus'></i> Thêm Mã Giảm Giá
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
        <div class="content-card">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã code</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Hết hạn</th>
                            <th>Đã dùng / Max</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($coupons): foreach ($coupons as $i => $c): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td><code class="fw-semibold fs-13" style="background:var(--primary-light);color:var(--primary);padding:3px 8px;border-radius:6px"><?= htmlspecialchars($c['code']) ?></code></td>
                            <td><span class="badge-status badge-<?= $c['type'] === 'percent' ? 'info' : 'success' ?>"><?= $c['type'] === 'percent' ? 'Phần trăm' : 'Cố định' ?></span></td>
                            <td class="fw-semibold"><?= $c['type'] === 'percent' ? $c['value'] . '%' : number_format($c['value']) . ' đ' ?></td>
                            <td class="fs-13 text-muted"><?= $c['expires_at'] ? date('d/m/Y', strtotime($c['expires_at'])) : '∞' ?></td>
                            <td class="fs-13"><?= $c['used_count'] ?> / <?= $c['usage_limit'] ?? '∞' ?></td>
                            <td>
                                <button class="btn-icon" onclick="editCoupon(<?= htmlspecialchars(json_encode($c)) ?>)">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn-icon text-danger" onclick="deleteCoupon(<?= $c['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có mã giảm giá nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Coupon -->
<div class="modal fade" id="modalCoupon" tabindex="-1">
    <div class="modal-dialog">
        <form id="formCoupon" class="modal-content">
            <input type="hidden" name="id" id="coupon_id">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalTitle">Tạo Mã Giảm Giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Mã code *</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-uppercase" name="code" placeholder="SUMMER2024" required>
                        <button class="btn btn-outline-secondary" type="button" id="btnGenCode">Tạo ngẫu nhiên</button>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">Loại</label>
                        <select class="form-select" name="type" id="couponType">
                            <option value="percent">Phần trăm (%)</option>
                            <option value="fixed">Cố định (đ)</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Giá trị *</label>
                        <input type="number" class="form-control" name="value" min="0" value="10" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Ngày hết hạn</label>
                        <input type="date" class="form-control" name="expires_at">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Giới hạn dùng</label>
                        <input type="number" class="form-control" name="usage_limit" placeholder="Không giới hạn" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom">Lưu Mã</button>
            </div>
        </form>
    </div>
</div>

<?php
$inlineScript = <<<'JS'
function resetCouponForm() {
    $('#formCoupon')[0].reset();
    $('#coupon_id').val('');
    $('#couponModalTitle').text('Tạo Mã Giảm Giá');
}

function editCoupon(data) {
    resetCouponForm();
    $('#coupon_id').val(data.id);
    $('#couponModalTitle').text('Sửa Mã Giảm Giá');
    $('[name="code"]').val(data.code);
    $('[name="type"]').val(data.type);
    $('[name="value"]').val(data.value);
    $('[name="expires_at"]').val(data.expires_at ? data.expires_at.split(' ')[0] : '');
    $('[name="usage_limit"]').val(data.usage_limit);
    $('#modalCoupon').modal('show');
}

$('#btnGenCode').on('click', function () {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) code += chars[Math.floor(Math.random() * chars.length)];
    $('[name="code"]').val(code);
});

$('#formCoupon').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/coupons.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã lưu mã giảm giá thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteCoupon(id) {
    if(!confirm('Xóa mã giảm giá này?')) return;
    lmsAjax('/lms1025edu/admin/api/coupons.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa mã giảm giá!');
            setTimeout(() => location.reload(), 800);
        }
    });
}
JS;
require_once __DIR__ . '/../../layouts/footer.php'; ?>
