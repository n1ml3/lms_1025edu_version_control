<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalQuiz" onclick="resetQuizForm()">
    <i class='bx bx-plus'></i> Thêm Bài Test
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
        <!-- Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2 text-muted fs-14">
                <span>Hiển thị</span>
                <select class="form-select form-select-sm" style="width:70px; display:inline-block;">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>dòng</span>
            </div>
            <div class="d-flex align-items-center gap-2 text-muted fs-14">
                <span>Tìm kiếm:</span>
                <input type="text" class="form-control form-control-sm" style="width:200px;" id="tableSearch">
            </div>
        </div>

        <div class="content-card">
            <div class="table-responsive">
                <table class="table table-custom" id="quizTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên bài thi</th>
                            <th>Chương trình</th>
                            <th>Thời gian</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($quizzes): foreach ($quizzes as $i => $q): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($q['name']) ?></td>
                            <td><?= htmlspecialchars($q['program_name'] ?? '—') ?></td>
                            <td><?= $q['duration'] ?> phút</td>
                            <td>
                                <button class="btn-icon" onclick="editQuiz(<?= htmlspecialchars(json_encode($q)) ?>)">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn-icon text-danger" onclick="deleteQuiz(<?= $q['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Chưa có bài thi nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Add/Edit Quiz -->
<div class="modal fade" id="modalQuiz" tabindex="-1">
    <div class="modal-dialog">
        <form id="formQuiz" class="modal-content">
            <input type="hidden" name="id" id="quiz_id">
            <div class="modal-header">
                <h5 class="modal-title" id="quizModalTitle">Thêm Bài Thi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên bài thi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Chương trình <span class="text-danger">*</span></label>
                    <select class="form-select" name="program_id" required>
                        <option value="">-- Chọn chương trình --</option>
                        <?php foreach($programs as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Thời gian (phút) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="duration" required min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom" id="btnSaveQuiz">Lưu Thông Tin</button>
            </div>
        </form>
    </div>
</div>

<?php
$inlineScript = <<<'JS'
function resetQuizForm() {
    $('#formQuiz')[0].reset();
    $('#quiz_id').val('');
    $('#quizModalTitle').text('Thêm Bài Thi');
}

function editQuiz(data) {
    resetQuizForm();
    $('#quiz_id').val(data.id);
    $('#quizModalTitle').text('Sửa Bài Thi');
    $('[name="name"]').val(data.name);
    $('[name="program_id"]').val(data.program_id);
    $('[name="duration"]').val(data.duration);
    $('#modalQuiz').modal('show');
}

$('#formQuiz').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/quizzes.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Lưu bài thi thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteQuiz(id) {
    if(!confirm('Bạn có chắc chắn muốn xóa bài thi này?')) return;
    lmsAjax('/lms1025edu/admin/api/quizzes.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa bài thi!');
            setTimeout(() => location.reload(), 800);
        }
    });
}

$('#tableSearch').on('keyup', function() {
    const val = $(this).val().toLowerCase();
    $('#quizTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
