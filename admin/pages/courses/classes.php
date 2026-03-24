<?php
/**
 * Courses — Lớp Học
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Lớp Học';
$activePage = 'courses_classes';
$breadcrumb = [['label'=>'Khóa Học'],['label'=>'Lớp học']];

try {
    $classes = $pdo->query("SELECT cl.*, t.name AS teacher_name, p.name AS program_name FROM classes cl LEFT JOIN teachers t ON t.id = cl.teacher_id LEFT JOIN programs p ON p.id = cl.program_id ORDER BY cl.id DESC LIMIT 50")->fetchAll();
} catch (Exception $e) { $classes = []; }

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Lớp Học</h1>
            <p class="page-subtitle">Danh sách lớp học đang hoạt động</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalClass">
            <i class='bx bx-plus'></i> Thêm Lớp
        </button>
    </div>
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead><tr><th>#</th><th>Chương trình</th><th>Giảng viên</th><th>Max học viên</th><th>Lịch học</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php if ($classes): foreach ($classes as $i => $cl): ?>
                    <tr>
                        <td class="fs-13 text-muted"><?= $i+1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($cl['program_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($cl['teacher_name'] ?? '—') ?></td>
                        <td><?= $cl['max_students'] ?? '—' ?></td>
                        <td class="fs-13 text-muted"><?= htmlspecialchars(is_string($cl['schedule']) ? $cl['schedule'] : json_encode($cl['schedule'])) ?></td>
                        <td><button class="btn-icon edit"><i class='bx bx-edit'></i></button><button class="btn-icon delete"><i class='bx bx-trash'></i></button></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có lớp học</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main></div>
<div class="modal fade" id="modalClass" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Lớp Học</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Chương trình</label><select class="form-select"><option>— Chọn chương trình —</option></select></div>
            <div class="mb-3"><label class="form-label">Giảng viên</label><select class="form-select"><option>— Chọn giảng viên —</option></select></div>
            <div class="mb-3"><label class="form-label">Số học viên tối đa</label><input type="number" class="form-control" value="30"></div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom">Lưu</button></div>
    </div></div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
