<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<a href="/lms1025edu/admin/pages/products/list.php" class="btn-outline-custom">
    <i class='bx bx-arrow-back'></i> Quay lại
</a>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
        <div class="content-card">
            <div class="content-card-header">
                <h3 class="content-card-title"><?= $product ? 'Sửa Sản Phẩm' : 'Thêm Sản Phẩm Mới' ?></h3>
            </div>
            <div class="content-card-body">
                <form id="formProduct" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mô tả</label>
                                <textarea class="form-control" name="description" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold">Giá (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="price" value="<?= $product['price'] ?? '' ?>" min="0" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold">Số lượng tồn</label>
                                    <input type="number" class="form-control" name="stock" value="<?= $product['stock'] ?? 0 ?>" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hình ảnh</label>
                            <div id="imagePreview" style="border:2px dashed var(--border);border-radius:14px;padding:12px;text-align:center;cursor:pointer;min-height:200px;display:flex;align-items:center;justify-content:center;flex-direction:column;overflow:hidden" onclick="$('#productImage').click()">
                                <?php if($product && $product['image']): ?>
                                    <img src="<?= htmlspecialchars($product['image']) ?>" style="max-width:100%;max-height:180px;border-radius:8px;object-fit:contain">
                                <?php else: ?>
                                    <i class='bx bx-image-add' style="font-size:36px;color:var(--primary)"></i>
                                    <p class="mt-2 mb-0 fs-13 text-muted">Click để chọn ảnh</p>
                                <?php endif; ?>
                            </div>
                            <input type="file" id="productImage" name="image" accept="image/*" class="d-none">
                            <p class="fs-12 text-muted mt-2 text-center">Định dạng hỗ trợ: JPG, PNG. Dung lượng < 2MB.</p>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-primary-custom" id="btnSubmit">
                            <i class='bx bx-save'></i> <?= $product ? 'Cập nhật sản phẩm' : 'Lưu sản phẩm' ?>
                        </button>
                        <a href="/lms1025edu/admin/pages/products/list.php" class="btn-outline-custom">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php 
$inlineScript = <<<JS
$('#productImage').on('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            $('#imagePreview').html('<img src="' + event.target.result + '" style="max-width:100%;max-height:180px;border-radius:8px;object-fit:contain">');
        }
        reader.readAsDataURL(file);
    }
});

$('#formProduct').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const action = $('[name="id"]').val() ? 'update' : 'create';
    formData.append('action', action);

    // Disable button to prevent double submit
    $('#btnSubmit').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Đang xử lý...');

    $.ajax({
        url: '/lms1025edu/admin/api/products.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            if(res.success) {
                lmsToast('success', 'Đã lưu sản phẩm thành công!');
                setTimeout(() => location.href = '/lms1025edu/admin/pages/products/list.php', 1000);
            } else {
                lmsToast('error', res.message || 'Có lỗi xảy ra!');
                $('#btnSubmit').prop('disabled', false).html('<i class="bx bx-save"></i> Lưu sản phẩm');
            }
        },
        error: function() {
            lmsToast('error', 'Lỗi kết nối máy chủ!');
            $('#btnSubmit').prop('disabled', false).html('<i class="bx bx-save"></i> Lưu sản phẩm');
        }
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
