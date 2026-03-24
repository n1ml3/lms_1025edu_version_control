<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalSource" onclick="resetSourceForm()">
    <i class='bx bx-plus'></i> Thêm Nguồn
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
                            <th>Tên nguồn</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($sources): foreach ($sources as $i => $s): ?>
                        <tr>
                            <td class="fs-13 text-muted"><?= $i+1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($s['name']) ?></td>
                            <td>
                                <button class="btn-icon" onclick="editSource(<?= htmlspecialchars(json_encode($s)) ?>)">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn-icon text-danger" onclick="deleteSource(<?= $s['id'] ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="3" class="text-center py-5 text-muted">Chưa có nguồn dữ liệu nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Source -->
<div class="modal fade" id="modalSource" tabindex="-1">
    <div class="modal-dialog">
        <form id="formSource" class="modal-content">
            <input type="hidden" name="id" id="source_id">
            <div class="modal-header">
                <h5 class="modal-title" id="sourceModalTitle">Thêm Nguồn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên nguồn <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required placeholder="VD: Facebook, Google...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn-primary-custom">Lưu</button>
            </div>
        </form>
    </div>
</div>

<?php
$inlineScript = <<<'JS'
function resetSourceForm() {
    $('#formSource')[0].reset();
    $('#source_id').val('');
    $('#sourceModalTitle').text('Thêm Nguồn');
}

function editSource(data) {
    resetSourceForm();
    $('#source_id').val(data.id);
    $('#sourceModalTitle').text('Sửa Nguồn');
    $('[name="name"]').val(data.name);
    $('#modalSource').modal('show');
}

$('#formSource').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/data-sources.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã lưu nguồn thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteSource(id) {
    if(!confirm('Xóa nguồn dữ liệu này?')) return;
    lmsAjax('/lms1025edu/admin/api/data-sources.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa nguồn!');
            setTimeout(() => location.reload(), 800);
        }
    });
}
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
