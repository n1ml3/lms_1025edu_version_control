<?php
require_once __DIR__ . '/../../../includes/auth_check.php';
require_once __DIR__ . '/../../../../config/db.php';

try {
    // Fetch all programs with course names
    $programs = $pdo->query("SELECT p.*, c.name AS course_name 
                           FROM programs p 
                           LEFT JOIN courses c ON c.id = p.course_id 
                           ORDER BY p.order ASC, p.name ASC")->fetchAll();
    
    // Fetch all courses for the modal dropdown
    $courses = $pdo->query("SELECT id, name FROM courses ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalProgram" onclick="resetProgramForm()">
    <i class='bx bx-plus'></i> Thêm Chương Trình
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
                <table class="table table-custom" id="programsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên chương trình</th>
                            <th>Khóa học</th>
                            <th>Thứ tự</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($programs): foreach ($programs as $i => $p): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= htmlspecialchars($p['course_name'] ?? '—') ?></td>
                            <td><?= $p['order'] ?? 0 ?></td>
                            <td>
                                <button class="btn-icon" onclick="editProgram(<?= htmlspecialchars(json_encode($p)) ?>)">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn-icon text-danger" onclick="deleteProgram(<?= $p['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Chưa có chương trình học nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Program -->
<div class="modal fade" id="modalProgram" tabindex="-1">
    <div class="modal-dialog">
        <form id="formProgram" class="modal-content">
            <input type="hidden" name="id" id="program_id">
            <div class="modal-header">
                <h5 class="modal-title" id="programModalTitle">Thêm Chương Trình</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Khóa học *</label>
                    <select class="form-select" name="course_id" required>
                        <option value="">— Chọn khóa học —</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tên chương trình *</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" class="form-control" name="order" value="1" min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom">Lưu chương trình</button>
            </div>
        </form>
    </div>
</div>

<?php 
$inlineScript = <<<JS
function resetProgramForm() {
    $('#formProgram')[0].reset();
    $('#program_id').val('');
    $('#programModalTitle').text('Thêm Chương Trình');
}

function editProgram(data) {
    resetProgramForm();
    $('#program_id').val(data.id);
    $('#programModalTitle').text('Sửa Chương Trình');
    $('[name="course_id"]').val(data.course_id);
    $('[name="name"]').val(data.name);
    $('[name="order"]').val(data.order);
    $('#modalProgram').modal('show');
}

$('#formProgram').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/programs.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã lưu chương trình thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteProgram(id) {
    if(!confirm('Xóa chương trình này?')) return;
    lmsAjax('/lms1025edu/admin/api/programs.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa chương trình!');
            setTimeout(() => location.reload(), 800);
        }
    });
}

$('#tableSearch').on('keyup', function() {
    const val = $(this).val().toLowerCase();
    $('#programsTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
