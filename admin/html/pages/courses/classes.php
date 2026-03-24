<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalClass">
    <i class='bx bx-plus'></i> Thêm Lớp
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
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
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
