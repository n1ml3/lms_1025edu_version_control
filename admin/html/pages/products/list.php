<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

// Fetch Products
$stmt = $pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC");
$products = $stmt->fetchAll();

$pageAction = <<<HTML
<a href="pages/products/add.php" class="btn-primary-custom text-decoration-none">
    <i class='bx bx-plus'></i> Thêm Sản Phẩm
</a>
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
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($products): foreach ($products as $i => $p): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td>
                                <?php if ($p['image']): ?>
                                <img src="<?= htmlspecialchars($p['image']) ?>" width="44" height="44" style="border-radius:8px;object-fit:cover">
                                <?php else: ?>
                                <div style="width:44px;height:44px;border-radius:8px;background:var(--primary-light);display:flex;align-items:center;justify-content:center">
                                    <i class='bx bx-image' style="color:var(--primary)"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-semibold"><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= number_format($p['price']) ?> đ</td>
                            <td>
                                <span class="badge-status badge-<?= $p['stock'] > 0 ? 'success' : 'danger' ?>">
                                    <?= number_format($p['stock']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/lms1025edu/admin/pages/products/add.php?id=<?= $p['id'] ?>" class="btn-icon">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button class="btn-icon text-danger" onclick="deleteProduct(<?= $p['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có sản phẩm nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php 
$inlineScript = <<<JS
function deleteProduct(id) {
    if(!confirm('Xóa sản phẩm này?')) return;
    lmsAjax('/lms1025edu/admin/api/products.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa sản phẩm!');
            setTimeout(() => location.reload(), 800);
        }
    });
}
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
