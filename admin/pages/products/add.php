<?php
/**
 * Products — Thêm sản phẩm
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Thêm Sản Phẩm';
$activePage = 'products_add';
$breadcrumb = [['label'=>'Sản Phẩm'],['label'=>'Thêm mới']];

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $stock = (int)   ($_POST['stock'] ?? 0);

    if ($name && $price >= 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $desc, $price, $stock, '']);
            $success = 'Đã thêm sản phẩm thành công!';
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = 'Vui lòng nhập tên và giá sản phẩm.';
    }
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header">
        <h1 class="page-title">Thêm Sản Phẩm</h1>
        <p class="page-subtitle"><a href="/lms1025edu/admin/pages/products/list.php">← Quay lại danh sách</a></p>
    </div>

    <?php if ($success): ?><div class="alert alert-success border-0 rounded-3"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger border-0 rounded-3"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="content-card">
        <div class="content-card-header"><h3 class="content-card-title">Thông tin sản phẩm</h3></div>
        <div class="content-card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mô tả</label>
                            <textarea class="form-control" name="description" rows="5"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Giá (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="price" value="<?= $_POST['price'] ?? '' ?>" min="0" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Số lượng tồn</label>
                                <input type="number" class="form-control" name="stock" value="<?= $_POST['stock'] ?? 0 ?>" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Hình ảnh</label>
                        <div style="border:2px dashed var(--border);border-radius:14px;padding:32px;text-align:center;cursor:pointer" onclick="$('#productImage').click()">
                            <i class='bx bx-image-add' style="font-size:36px;color:var(--primary)"></i>
                            <p class="mt-2 mb-0 fs-13 text-muted">Click để chọn ảnh</p>
                        </div>
                        <input type="file" id="productImage" name="image" accept="image/*" class="d-none">
                    </div>
                </div>
                <hr class="my-4">
                <div class="d-flex gap-3">
                    <button type="submit" class="btn-primary-custom"><i class='bx bx-save'></i> Lưu Sản Phẩm</button>
                    <a href="/lms1025edu/admin/pages/products/list.php" class="btn-outline-custom">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</main></div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
