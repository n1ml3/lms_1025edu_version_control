<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalNotif">
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
                <thead><tr><th>#</th><th>Tiêu đề</th><th>Nội dung</th><th>Ngày gửi</th></tr></thead>
                <tbody>
                <?php if ($notifs): foreach ($notifs as $i => $n): ?>
                    <tr>
                        <td class="fs-13 text-muted"><?= $i+1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($n['title']) ?></td>
                        <td class="fs-13 text-muted"><?= htmlspecialchars(substr($n['content'], 0, 80)) ?>...</td>
                        <td class="fs-13 text-muted"><?= date('d/m/Y H:i', strtotime($n['created_at'])) ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-5 text-muted">Chưa có thông báo nào</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main></div>
<div class="modal fade" id="modalNotif" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Gửi Thông Báo</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Tiêu đề</label><input type="text" class="form-control"></div>
            <div class="mb-3"><label class="form-label">Nội dung</label><textarea class="form-control" rows="4"></textarea></div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom"><i class='bx bx-send me-1'></i>Gửi</button></div>
    </div></div>
</div>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
