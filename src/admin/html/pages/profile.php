<?php
require_once __DIR__ . '/../../includes/auth_check.php';

// Verify $adminData exists (passed from controller)
if (!isset($adminData) || !$adminData) {
    die("Dữ liệu không hợp lệ.");
}

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>
    <main class="page-content">
        <div class="row g-4">
            <!-- Cập nhật thông tin -->
            <div class="col-md-6">
                <div class="content-card h-100">
                    <div class="content-card-header">
                        <h3 class="content-card-title">Hồ sơ cá nhân</h3>
                    </div>
                    <div class="content-card-body">
                        <form id="formProfile">
                            <input type="hidden" name="action" value="update_info">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email (Đăng nhập)</label>
                                <input type="email" class="form-control" value="<?= htmlspecialchars($adminData['email']) ?>" readonly style="background-color: var(--bs-secondary-bg)">
                                <div class="form-text">Email không thể thay đổi.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($adminData['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($adminData['phone'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Phòng ban / Vị trí</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars(($adminData['department'] ?? 'Chưa cập nhật') . ' - ' . ($adminData['position'] ?? 'Chưa cập nhật')) ?>" readonly style="background-color: var(--bs-secondary-bg)">
                            </div>
                            
                            <hr class="my-4">
                            <button type="submit" class="btn-primary-custom" id="btnUpdateInfo">
                                <i class='bx bx-save'></i> Lưu Thay Đổi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Đổi mật khẩu -->
            <div class="col-md-6">
                <div class="content-card h-100">
                    <div class="content-card-header">
                        <h3 class="content-card-title">Đổi mật khẩu</h3>
                    </div>
                    <div class="content-card-body">
                        <form id="formPassword">
                            <input type="hidden" name="action" value="change_password">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="new_password" required minlength="6">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="confirm_password" required minlength="6">
                            </div>

                            <hr class="my-4">
                            <button type="submit" class="btn btn-danger" id="btnChangePassword">
                                <i class='bx bx-lock-alt'></i> Đổi Mật Khẩu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php 
$inlineScript = <<<JS
$('#formProfile').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#btnUpdateInfo');
    btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Đang lưu...');

    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);

    // Sử dụng $.ajax thay vì lmsAjax nếu cần truyền options phức tạp, nhưng lmsAjax đủ tốt
    lmsAjax('/lms1025edu/admin/api/profile.php', data, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã cập nhật thông tin thành công!');
            setTimeout(() => location.reload(), 1000);
        } else {
            lmsToast('error', res.error || res.message || 'Có lỗi xảy ra!');
            btn.prop('disabled', false).html('<i class="bx bx-save"></i> Lưu Thay Đổi');
        }
    });
});

$('#formPassword').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#btnChangePassword');
    
    if ($('[name="new_password"]').val() !== $('[name="confirm_password"]').val()) {
        lmsToast('error', 'Mật khẩu mới không khớp!');
        return;
    }

    btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Đang xử lý...');

    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);

    lmsAjax('/lms1025edu/admin/api/profile.php', data, function(res) {
        if(res.success) {
            lmsToast('success', 'Mật khẩu đã được thay đổi!');
            $('#formPassword')[0].reset();
            btn.prop('disabled', false).html('<i class="bx bx-lock-alt"></i> Đổi Mật Khẩu');
        } else {
            lmsToast('error', res.error || res.message || 'Có lỗi xảy ra!');
            btn.prop('disabled', false).html('<i class="bx bx-lock-alt"></i> Đổi Mật Khẩu');
        }
    });
});
JS;
require_once __DIR__ . '/../layouts/footer.php'; 
?>
