<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Sản Phẩm</h1>
            <p class="page-subtitle">Quản lý danh sách sản phẩm bán hàng</p>
        </div>
        <a href="/lms1025edu/admin/pages/products/add.php" class="btn-primary-custom">
            <i class='bx bx-plus'></i> Thêm Sản Phẩm
        </a>
    </div>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead><tr><th>#</th><th>Hình ảnh</th><th>Tên sản phẩm</th><th>Giá</th><th>Tồn kho</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php if ($products): foreach ($products as $i => $p): ?>
                    <tr>
                        <td class="fs-13 text-muted"><?= $i+1 ?></td>
                        <td>
                            <?php if ($p['image']): ?>
                            <img src="<?= htmlspecialchars($p['image']) ?>" width="44" height="44" style="border-radius:8px;object-fit:cover">
                            <?php else: ?>
                            <div style="width:44px;height:44px;border-radius:8px;background:var(--primary-light);display:flex;align-items:center;justify-content:center"><i class='bx bx-image' style="color:var(--primary)"></i></div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-semibold"><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= number_format($p['price']) ?> đ</td>
                        <td><span class="badge-status badge-<?= $p['stock'] > 0 ? 'success' : 'danger' ?>"><?= number_format($p['stock']) ?></span></td>
                        <td>
                            <button class="btn-icon edit"><i class='bx bx-edit'></i></button>
                            <button class="btn-icon delete" data-confirm="Xóa sản phẩm này?"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có sản phẩm nào</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main></div>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
