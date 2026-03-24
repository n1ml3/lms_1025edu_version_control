<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalClass" onclick="resetClassForm()">
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
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lớp / Chương trình</th>
                            <th>Giảng viên</th>
                            <th>Sĩ số</th>
                            <th>Lịch học</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($classes): foreach ($classes as $i => $cl): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($cl['name']) ?></div>
                                <div class="fs-12 text-muted"><?= htmlspecialchars($cl['program_name'] ?? '—') ?></div>
                            </td>
                            <td><?= htmlspecialchars($cl['teacher_name'] ?? '—') ?></td>
                            <td><?= $cl['current_students'] ?? 0 ?> / <?= $cl['max_students'] ?? '30' ?></td>
                            <td class="fs-13">
                                <?= htmlspecialchars($cl['schedule'] ?? 'Chưa xếp lịch') ?>
                            </td>
                            <td>
                                <button class="btn-icon" onclick="editClass(<?= htmlspecialchars(json_encode($cl)) ?>)">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn-icon text-danger" onclick="deleteClass(<?= $cl['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có lớp học nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Class -->
<div class="modal fade" id="modalClass" tabindex="-1">
    <div class="modal-dialog">
        <form id="formClass" class="modal-content">
            <input type="hidden" name="id" id="class_id">
            <div class="modal-header">
                <h5 class="modal-title" id="classModalTitle">Thêm Lớp Học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên lớp *</label>
                    <input type="text" class="form-control" name="name" required placeholder="VD: IELTS-K24-A">
                </div>
                <div class="mb-3">
                    <label class="form-label">Chương trình *</label>
                    <select class="form-select" name="program_id" required>
                        <option value="">— Chọn chương trình —</option>
                        <?php foreach($programs as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Giảng viên</label>
                    <select class="form-select" name="teacher_id">
                        <option value="">— Chọn giảng viên —</option>
                        <?php foreach($teachers as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Sĩ số tối đa</label>
                        <input type="number" class="form-control" name="max_students" value="30" min="1">
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Lịch học (Text)</label>
                    <textarea class="form-control" name="schedule" rows="2" placeholder="VD: T2-T4-T6 (18:00 - 20:00)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom">Lưu lớp học</button>
            </div>
        </form>
    </div>
</div>

<?php 
$inlineScript = <<<JS
function resetClassForm() {
    $('#formClass')[0].reset();
    $('#class_id').val('');
    $('#classModalTitle').text('Thêm Lớp Học');
}

function editClass(data) {
    resetClassForm();
    $('#class_id').val(data.id);
    $('#classModalTitle').text('Sửa Lớp Học');
    $('[name="name"]').val(data.name);
    $('[name="program_id"]').val(data.program_id);
    $('[name="teacher_id"]').val(data.teacher_id);
    $('[name="max_students"]').val(data.max_students);
    $('[name="schedule"]').val(data.schedule);
    $('#modalClass').modal('show');
}

$('#formClass').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/classes.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã lưu lớp học thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteClass(id) {
    if(!confirm('Xóa lớp học này?')) return;
    lmsAjax('/lms1025edu/admin/api/classes.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa lớp học!');
            setTimeout(() => location.reload(), 800);
        }
    });
}
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
