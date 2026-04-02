<?php
require_once __DIR__ . '/../../../includes/auth_check.php';
require_once __DIR__ . '/../../../../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;

if ($id > 0) {
    try {
         $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
         $stmt->execute([$id]);
         $student = $stmt->fetch();
    } catch (PDOException $e) {
         die("Lỗi truy vấn: " . $e->getMessage());
    }
}

// Fetch classes for the dropdown
$classes = [];
try {
    $stmt = $pdo->query("SELECT c.id, p.name as program_name FROM classes c JOIN programs p ON c.program_id = p.id");
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Ignore if not loaded perfectly
}

require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<a href="/lms1025edu/admin/pages/students/list.php" class="btn-outline-custom">
    <i class='bx bx-arrow-back'></i> Quay lại
</a>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
        <div class="content-card">
            <div class="content-card-header">
                <h3 class="content-card-title"><?= $student ? 'Sửa Học Sinh' : 'Thêm Học Sinh Mới' ?></h3>
            </div>
             <div class="content-card-body">
                 <form id="formStudent">
                     <input type="hidden" name="id" value="<?= $student['id'] ?? '' ?>">
                     <div class="row g-4">
                         <div class="col-md-8">
                             <div class="mb-3">
                                 <label class="form-label fw-semibold">Tên học sinh <span class="text-danger">*</span></label>
                                 <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($student['name'] ?? '') ?>" required>
                             </div>
                             <div class="row g-3">
                                 <div class="col-sm-6">
                                     <label class="form-label fw-semibold">Số điện thoại</label>
                                     <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
                                 </div>
                                 <div class="col-sm-6">
                                     <label class="form-label fw-semibold">Email</label>
                                     <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($student['email'] ?? '') ?>">
                                 </div>
                             </div>
                             <div class="mb-3 mt-3">
                                 <label class="form-label fw-semibold">Lớp học</label>
                                 <select class="form-select" name="class_id">
                                     <option value="">-- Chọn lớp học --</option>
                                     <?php foreach ($classes as $c): ?>
                                     <option value="<?= $c['id'] ?>" <?= ($student && $student['class_id'] == $c['id']) ? 'selected' : '' ?>>
                                         <?= htmlspecialchars($c['program_name']) ?> (ID: <?= $c['id'] ?>)
                                     </option>
                                     <?php endforeach; ?>
                                 </select>
                             </div>
                         </div>
                     </div>
                     <hr class="my-4">
                     <div class="d-flex gap-3">
                         <button type="submit" class="btn-primary-custom" id="btnSubmit">
                             <i class='bx bx-save'></i> <?= $student ? 'Cập nhật học sinh' : 'Lưu học sinh' ?>
                         </button>
                         <a href="/lms1025edu/admin/pages/students/list.php" class="btn-outline-custom">Hủy</a>
                     </div>
                 </form>
             </div>
        </div>
    </main>
</div>

<?php 
$inlineScript = <<<JS
$('#formStudent').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const action = $('[name="id"]').val() ? 'update' : 'create';
    formData.append('action', action);

    // Disable button to prevent double submit
    $('#btnSubmit').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Đang xử lý...');

    $.ajax({
        url: '/lms1025edu/admin/api/students.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            if(res.success) {
                lmsToast('success', 'Đã lưu học sinh thành công!');
                setTimeout(() => location.href = '/lms1025edu/admin/pages/students/list.php', 1000);
            } else {
                lmsToast('error', res.message || res.error || 'Có lỗi xảy ra!');
                $('#btnSubmit').prop('disabled', false).html('<i class="bx bx-save"></i> Lưu học sinh');
            }
        },
        error: function(err) {
            let msg = 'Lỗi kết nối máy chủ!';
            if (err.responseJSON && err.responseJSON.error) msg = err.responseJSON.error;
            lmsToast('error', msg);
            $('#btnSubmit').prop('disabled', false).html('<i class="bx bx-save"></i> Lưu học sinh');
        }
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
