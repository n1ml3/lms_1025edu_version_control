<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn btn-info text-white px-4 py-2" style="background-color:#0dcaf0; border:none; border-radius:6px; font-weight:500;" data-bs-toggle="modal" data-bs-target="#modalAdmin" onclick="resetAdminForm()">
    Thêm tài khoản
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content bg-white">

        <!-- Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2 text-muted fs-14">
                <span>Show</span>
                <select class="form-select form-select-sm" style="width:70px; display:inline-block;">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entries</span>
            </div>
            <div class="d-flex align-items-center gap-2 text-muted fs-14">
                <span>Search:</span>
                <input type="text" class="form-control form-control-sm" style="width:200px;" id="tableSearch">
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border" id="memberTable">
                <thead class="table-light text-muted fs-13" style="text-transform:none;">
                    <tr>
                        <th class="py-3 px-3 fw-semibold border-bottom">STT</th>
                        <th class="py-3 px-3 fw-semibold border-bottom">Họ tên</th>
                        <th class="py-3 px-3 fw-semibold border-bottom">Email</th>
                        <th class="py-3 px-3 fw-semibold border-bottom">Số điện thoại</th>
                        <th class="py-3 px-3 fw-semibold border-bottom">Phòng ban</th>
                        <th class="py-3 px-3 fw-semibold border-bottom">Chức danh</th>
                        <th class="py-3 px-3 fw-semibold border-bottom">Trạng thái</th>
                        <th class="py-3 px-3 fw-semibold border-bottom">Quyền</th>
                        <th class="border-bottom"></th>
                    </tr>
                </thead>
                <tbody class="fs-14">
                    <?php if ($admins): foreach ($admins as $i => $a): ?>
                    <tr>
                        <td class="px-3"><?= $i+1 ?></td>
                        <td class="px-3 text-info fw-medium"><?= htmlspecialchars($a['name']) ?></td>
                        <td class="px-3 text-muted"><?= htmlspecialchars($a['email']) ?></td>
                        <td class="px-3 text-muted"><?= htmlspecialchars($a['phone'] ?? '') ?></td>
                        <td class="px-3 text-muted"><?= htmlspecialchars($a['department'] ?? '') ?></td>
                        <td class="px-3 text-muted"><?= htmlspecialchars($a['position'] ?? '') ?></td>
                        <td class="px-3">
                            <div class="form-check form-switch fs-5 m-0 p-0 d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" role="switch" <?= ($a['is_active'] ?? 1) ? 'checked' : '' ?> onchange="toggleAdminStatus(<?= $a['id'] ?>, this.checked)">
                            </div>
                        </td>
                        <td class="px-3 text-center">
                            <?php
                                $roleName = $a['role_name'] ?? 'Staff';
                                $badgeClass = (strpos(strtolower($roleName), 'admin') !== false) ? 'bg-danger' : 'bg-warning';
                            ?>
                            <span class="badge rounded-pill fw-normal px-2 py-1 <?= $badgeClass ?>" style="font-size:11px;">
                                <?= htmlspecialchars($roleName) ?>
                            </span>
                        </td>
                        <td class="px-3 text-center">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm px-2 text-muted border-0" type="button" data-bs-toggle="dropdown">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-14">
                                    <li>
                                        <a class="dropdown-item py-2" href="javascript:void(0)" onclick="editAdmin(<?= htmlspecialchars(json_encode($a)) ?>)">
                                            <i class='bx bx-edit me-2'></i> Sửa
                                        </a>
                                    </li>
                                    <?php if ($a['id'] != $adminId): ?>
                                    <li>
                                        <a class="dropdown-item text-danger py-2" href="javascript:void(0)" onclick="deleteAdmin(<?= $a['id'] ?>)">
                                            <i class='bx bx-trash me-2'></i> Xóa
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="9" class="text-center py-5 text-muted">Chưa có người dùng nào</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Modal Add/Edit Admin -->
<div class="modal fade" id="modalAdmin" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formAdmin" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="id" id="admin_id">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="adminModalTitle">Thêm tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-semibold mb-3 text-primary border-bottom pb-2">Thông tin đăng nhập</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fs-14">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Mật khẩu <span id="pwd-hint" class="text-muted fw-normal" style="font-size:12px;"></span></label>
                        <input type="password" class="form-control" name="password" id="admin_password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Vai trò <span class="text-danger">*</span></label>
                        <select class="form-select" name="role_id" required>
                            <option value="">-- Chọn quyền --</option>
                            <?php foreach($roles as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch mb-2 pt-1">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="admin_active" checked>
                            <label class="form-check-label ms-2" for="admin_active">Kích hoạt</label>
                        </div>
                    </div>
                </div>

                <h6 class="fw-semibold mb-3 text-primary border-bottom pb-2">Thông tin cá nhân</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fs-14">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Phòng ban</label>
                        <input type="text" class="form-control" name="department">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Chức vụ</label>
                        <input type="text" class="form-control" name="position">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-14">Ngày sinh</label>
                        <input type="date" class="form-control" name="dob">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-14">Ngày bắt đầu làm</label>
                        <input type="date" class="form-control" name="start_date">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-14">Hotline cá nhân</label>
                        <input type="text" class="form-control" name="hotline">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-info text-white px-4" style="background-color:#0dcaf0; border:none; font-weight:500;">
                    Lưu tài khoản
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$inlineScript = <<<'JS'
function resetAdminForm() {
    $('#formAdmin')[0].reset();
    $('#admin_id').val('');
    $('#adminModalTitle').text('Thêm tài khoản');
    $('#admin_password').prop('required', true);
    $('#pwd-hint').text('');
}

function editAdmin(data) {
    resetAdminForm();
    $('#admin_id').val(data.id);
    $('#adminModalTitle').text('Cập nhật tài khoản');
    $('[name="name"]').val(data.name);
    $('[name="email"]').val(data.email);
    $('[name="role_id"]').val(data.role_id);
    $('#admin_active').prop('checked', data.is_active == 1);
    
    $('[name="phone"]').val(data.phone);
    $('[name="department"]').val(data.department);
    $('[name="position"]').val(data.position);
    $('[name="dob"]').val(data.dob);
    $('[name="start_date"]').val(data.start_date);
    $('[name="hotline"]').val(data.hotline);
    
    $('#admin_password').prop('required', false);
    $('#pwd-hint').text('(Để trống để giữ nguyên)');
    $('#modalAdmin').modal('show');
}

$('#formAdmin').on('submit', function(e) {
    e.preventDefault();
    const data = {};
    $(this).serializeArray().forEach(item => data[item.name] = item.value);
    
    const action = data.id ? 'update' : 'create';
    lmsAjax('/lms1025edu/admin/api/admins.php', { action, ...data }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã lưu tài khoản thành công!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});

function deleteAdmin(id) {
    if(!confirm('Xác nhận xóa tài khoản này?')) return;
    lmsAjax('/lms1025edu/admin/api/admins.php', { action: 'delete', id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa tài khoản!');
            setTimeout(() => location.reload(), 800);
        }
    });
}

function toggleAdminStatus(id, status) {
    lmsAjax('/lms1025edu/admin/api/admins.php', { action: 'toggle_status', id, status: status ? 1 : 0 }, function(res) {
        if(!res.success) {
            lmsToast('danger', 'Không thể đổi trạng thái!');
            setTimeout(() => location.reload(), 500);
        }
    });
}

$('#tableSearch').on('keyup', function() {
    const val = $(this).val().toLowerCase();
    $('#memberTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
    });
});
JS;
require_once __DIR__ . '/../../layouts/footer.php'; 
?>
