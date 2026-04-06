<?php
require_once __DIR__ . '/../../../includes/auth_check.php';
require_once __DIR__ . '/../../../../config/db.php';

try {
    $students = $pdo->query("SELECT s.*, 
                             (SELECT p.name FROM classes c JOIN programs p ON c.program_id = p.id WHERE c.id = s.class_id) as class_name 
                             FROM students s ORDER BY s.enrolled_at DESC")->fetchAll();
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<a href="/lms1025edu/admin/pages/students/add.php" class="btn-primary-custom text-decoration-none">
    <i class='bx bx-plus'></i> Thêm học sinh
</a>
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
                <table class="table table-custom" id="studentsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên học sinh</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Lớp</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($students): foreach ($students as $i => $s): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($s['name']) ?></td>
                            <td><?= htmlspecialchars($s['phone'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['email'] ?? '-') ?></td>
                            <td>
                                <?php if ($s['class_id']): ?>
                                    <span class="badge-status badge-success">
                                        <?= htmlspecialchars($s['class_name'] ?? ('Lớp ID: ' . $s['class_id'])) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge-status badge-secondary text-muted">Chưa xếp lớp</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/lms1025edu/admin/pages/students/add.php?id=<?= $s['id'] ?>" class="btn-icon">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button class="btn-icon text-danger" onclick="deleteStudent(<?= $s['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có học sinh nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php 
$inlineScript = <<<JS
function deleteStudent(id) {
    if(!confirm('Xóa học sinh này?')) return;
    lmsAjax('/lms1025edu/admin/api/students.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa học sinh!');
            setTimeout(() => location.reload(), 800);
        }
    });
}

$('#tableSearch').on('keyup', function() {
    const val = $(this).val().toLowerCase();
    $('#studentsTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
