<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalTeacher" onclick="resetTeacherForm()">
    <i class='bx bx-plus'></i> Thêm Giáo Viên
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
        <div class="row g-3">
            <?php if ($teachers): foreach ($teachers as $t): ?>
            <div class="col-sm-6 col-xl-4">
                <div class="content-card">
                    <div class="content-card-body d-flex align-items-center gap-3">
                        <div class="avatar-circle flex-shrink-0" style="width:48px;height:48px;border-radius:14px;font-size:20px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:600">
                            <?= strtoupper(substr($t['name'],0,1)) ?>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <div class="fw-semibold text-truncate"><?= htmlspecialchars($t['name']) ?></div>
                            <div class="fs-13 text-muted text-truncate"><?= htmlspecialchars($t['email'] ?? '') ?></div>
                            <div class="fs-13 text-muted"><?= htmlspecialchars($t['phone'] ?? '') ?></div>
                        </div>
                        <div class="d-flex gap-1 ms-auto">
                            <button class="btn-icon" onclick="editTeacher(<?= htmlspecialchars(json_encode($t)) ?>)">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-icon text-danger" onclick="deleteTeacher(<?= $t['id'] ?>)">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; else: ?>
            <div class="col-12">
                <div class="content-card content-card-body text-center py-5 text-muted">Chưa có giáo viên nào</div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modal Teacher -->
<div class="modal fade" id="modalTeacher" tabindex="-1">
    <div class="modal-dialog">
        <form id="formTeacher" class="modal-content">
            <input type="hidden" name="id" id="teacher_id">
            <div class="modal-header">
                <h5 class="modal-title" id="teacherModalTitle">Thêm Giáo Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Họ tên *</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tiểu sử / Ghi chú</label>
                    <textarea class="form-control" name="bio" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom">Lưu giáo viên</button>
            </div>
        </form>
    </div>
</div>

<?php 
$inlineScript = <<<JS
function resetTeacherForm() {
    $('#formTeacher')[0].reset();
    $('#teacher_id').val('');
    $('#teacherModalTitle').text('Thêm Giáo Viên');
}

function editTeacher(data) {
    resetTeacherForm();
    $('#teacher_id').val(data.id);
    $('#teacherModalTitle').text('Sửa Giáo Viên');
    $('[name="name"]').val(data.name);
    $('[name="phone"]').val(data.phone);
    $('[name="email"]').val(data.email);
    $('[name="bio"]').val(data.bio);
    $('#modalTeacher').modal('show');
}

$('#formTeacher').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/teachers.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã lưu giáo viên thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteTeacher(id) {
    if(!confirm('Xóa giáo viên này?')) return;
    lmsAjax('/lms1025edu/admin/api/teachers.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa giáo viên!');
            setTimeout(() => location.reload(), 800);
        }
    });
}
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
