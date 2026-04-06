<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalStaffNotif" onclick="resetNotifForm()">
    <i class='bx bx-send'></i> Gửi Thông Báo
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
                <table class="table table-custom" id="staffTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tiêu đề</th>
                            <th>Nội dung</th>
                            <th>Ngày gửi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($notifs): foreach ($notifs as $i => $n): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($n['title']) ?></td>
                            <td class="fs-13 text-muted"><?= htmlspecialchars(mb_substr($n['content'], 0, 80)) ?><?= mb_strlen($n['content']) > 80 ? '...' : '' ?></td>
                            <td class="fs-13 text-muted"><?= date('d/m/Y H:i', strtotime($n['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">Chưa có thông báo nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Send Staff Notification -->
<div class="modal fade" id="modalStaffNotif" tabindex="-1">
    <div class="modal-dialog">
        <form id="formStaffNotif" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gửi Thông Báo Nhân Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="content" rows="4" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom"><i class='bx bx-send me-1'></i>Gửi Nhân Viên</button>
            </div>
        </form>
    </div>
</div>

<?php
$inlineScript = <<<'JS'
function resetNotifForm() {
    $('#formStaffNotif')[0].reset();
}

$('#formStaffNotif').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    lmsAjax('/lms1025edu/admin/api/notifications.php', { action: 'create', type: 'staff', ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã gửi thông báo nhân viên thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

$('#tableSearch').on('keyup', function() {
    const val = $(this).val().toLowerCase();
    $('#staffTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
