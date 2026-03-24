<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalLead">
    <i class='bx bx-plus'></i> Thêm Lead
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Tất cả Lead <span class="badge bg-primary ms-2"><?= count($leads) ?></span></h3>
            <input type="search" class="form-control" id="searchLead" placeholder="Tìm kiếm..." style="width:220px">
        </div>
        <div class="table-responsive">
            <table class="table table-custom" id="leadsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Nguồn</th>
                        <th>Cơ sở</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($leads): foreach ($leads as $i => $l): ?>
                    <tr>
                        <td class="text-muted fs-13"><?= $i + 1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($l['name']) ?></td>
                        <td><?= htmlspecialchars($l['phone']) ?></td>
                        <td><?= htmlspecialchars($l['email']) ?></td>
                        <td><?= htmlspecialchars($l['source_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($l['branch_name'] ?? '—') ?></td>
                        <td>
                            <?php
                            $st = $l['status'] ?? 'new';
                            $badgeMap = ['new'=>'info','contacted'=>'warning','converted'=>'success','lost'=>'danger'];
                            $labelMap = ['new'=>'Mới','contacted'=>'Đã liên hệ','converted'=>'Chuyển đổi','lost'=>'Mất'];
                            ?>
                            <span class="badge-status badge-<?= $badgeMap[$st] ?? 'gray' ?>"><?= $labelMap[$st] ?? $st ?></span>
                        </td>
                        <td class="fs-13 text-muted"><?= date('d/m/Y', strtotime($l['created_at'])) ?></td>
                        <td>
                            <button class="btn-icon edit" title="Sửa"><i class='bx bx-edit'></i></button>
                            <button class="btn-icon delete" title="Xóa" data-confirm="Xóa lead này?"><i class='bx bx-trash'></i></button>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="9" class="text-center py-5 text-muted">Chưa có dữ liệu lead</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</div>

<!-- Modal Add Lead -->
<div class="modal fade" id="modalLead" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Lead Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formLead">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nguồn</label>
                        <select class="form-select" name="source_id">
                            <option value="">— Chọn nguồn —</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="note" rows="3"></textarea>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button class="btn-primary-custom" id="btnSaveLead">Lưu Lead</button>
            </div>
        </div>
    </div>
</div>

<?php
$inlineScript = <<<'JS'
// Search filter
$('#searchLead').on('input', function () {
    const q = $(this).val().toLowerCase();
    $('#leadsTable tbody tr').each(function () {
        $(this).toggle($(this).text().toLowerCase().includes(q));
    });
});
// Save lead AJAX
$('#btnSaveLead').on('click', function () {
    const data = {};
    $('#formLead').serializeArray().forEach(f => data[f.name] = f.value);
    lmsAjax('/lms1025edu/admin/api/leads.php', { action: 'create', ...data }, function (res) {
        if (res.success) {
            lmsToast('success', 'Đã thêm lead mới!');
            $('#modalLead').modal('hide');
            setTimeout(() => location.reload(), 1000);
        } else lmsToast('danger', res.error || 'Lỗi!');
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php';
?>
