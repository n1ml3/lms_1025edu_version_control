<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalNotif" onclick="resetNotifForm()">
    <i class='bx bx-send'></i> Gửi Thông Báo
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

<!-- Modal Send Notification -->
<div class="modal fade" id="modalNotif" tabindex="-1">
    <div class="modal-dialog">
        <form id="formNotif" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gửi Thông Báo Mới</h5>
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
                <button type="submit" class="btn-primary-custom"><i class='bx bx-send me-1'></i>Gửi Ngay</button>
            </div>
        </form>
    </div>
</div>

<?php
$inlineScript = <<<'JS'
$('#formNotif').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    lmsAjax('/lms1025edu/admin/api/notifications.php', { action: 'create', ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã gửi thông báo thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
