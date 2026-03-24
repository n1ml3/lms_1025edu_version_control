<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn btn-info text-white px-4 py-2" style="background-color:#0dcaf0; border:none; border-radius:6px; font-weight:500;" data-bs-toggle="modal" data-bs-target="#modalAdmin">
    Thêm tài khảo
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
            <input type="text" class="form-control form-control-sm" style="width:200px;">
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light text-muted fs-13" style="text-transform:none;">
                <tr>
                    <th class="py-3 px-3 fw-semibold border-bottom">STT <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Họ tên <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Email <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Số điện thoại <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Phòng ban <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Chức vụ <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Ngày sinh <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Ngày bắt đầu làm <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Hotline <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Trạng thái <i class='bx bx-sort ms-1'></i></th>
                    <th class="py-3 px-3 fw-semibold border-bottom">Quyền <i class='bx bx-sort ms-1'></i></th>
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
                    <td class="px-3 text-muted"><?= $a['dob'] ? date('Y-m-d', strtotime($a['dob'])) : '' ?></td>
                    <td class="px-3 text-muted"><?= $a['start_date'] ? date('Y-m-d', strtotime($a['start_date'])) : '' ?></td>
                    <td class="px-3 text-muted"><?= htmlspecialchars($a['hotline'] ?? '') ?></td>
                    <td class="px-3">
                        <div class="form-check form-switch fs-5 m-0 p-0 d-flex justify-content-center">
                            <input class="form-check-input btn-toggle-status" style="cursor:pointer;" type="checkbox" role="switch" <?= ($a['is_active'] ?? 1) ? 'checked' : '' ?> data-id="<?= $a['id'] ?>">
                        </div>
                    </td>
                    <td class="px-3 text-center">
                        <?php
                            $roleName = htmlspecialchars($a['role_name'] ?? 'Staff');
                            $badgeColor = '#e74c3c'; // red like Admin/Kế toán
                            if (strpos(strtolower($roleName), 'admin') === false && strpos(strtolower($roleName), 'kế toán') === false) {
                                $badgeColor = '#f39c12'; // orange or other colors for variations
                            }
                        ?>
                        <span class="badge rounded-pill fw-normal px-2 py-1" style="background-color:<?= $badgeColor ?>; font-size:11px;">
                            <?= $roleName ?>
                        </span>
                    </td>
                    <td class="px-3 text-center">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm px-2 text-muted border-0" type="button" data-bs-toggle="dropdown" style="background:#f1f5f9;">
                                <i class='bx bx-dots-horizontal-rounded'></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-14">
                                <li>
                                    <a class="dropdown-item btn-edit-admin py-2" href="javascript:void(0)"
                                        data-id="<?= $a['id'] ?>"
                                        data-name="<?= htmlspecialchars($a['name']) ?>"
                                        data-email="<?= htmlspecialchars($a['email']) ?>"
                                        data-role="<?= $a['role_id'] ?>"
                                        data-active="<?= $a['is_active'] ?? 1 ?>"
                                        data-phone="<?= htmlspecialchars($a['phone'] ?? '') ?>"
                                        data-department="<?= htmlspecialchars($a['department'] ?? '') ?>"
                                        data-position="<?= htmlspecialchars($a['position'] ?? '') ?>"
                                        data-dob="<?= $a['dob'] ?>"
                                        data-startdate="<?= $a['start_date'] ?>"
                                        data-hotline="<?= htmlspecialchars($a['hotline'] ?? '') ?>">
                                        <i class='bx bx-edit me-2'></i> Sửa
                                    </a>
                                </li>
                                <?php if ($a['id'] != $adminId): ?>
                                <li>
                                    <a class="dropdown-item text-danger btn-delete-admin py-2" href="javascript:void(0)" data-id="<?= $a['id'] ?>">
                                        <i class='bx bx-trash me-2'></i> Xóa
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="12" class="text-center py-5 text-muted">Chưa có user nào</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Footer nav -->
    <div class="d-flex justify-content-between align-items-center mt-3 text-muted fs-13">
        <?php $c = count($admins); ?>
        <div>Showing <?= $c > 0 ? 1 : 0 ?> to <?= $c ?> of <?= $c ?> entries</div>
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item active"><a class="page-link bg-info border-info" href="#">1</a></li>
            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
        </ul>
    </div>
</main></div>

<!-- Modal Add/Edit Admin -->
<div class="modal fade" id="modalAdmin" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-bottom-0 pb-0">
            <h5 class="modal-title fs-5 fw-bold">Thêm tài khoản</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="formAdmin">
                <input type="hidden" name="id" id="admin_id">
                
                <h6 class="fw-semibold mb-3 text-primary border-bottom pb-2">Thông tin đăng nhập</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fs-14">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="admin_email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Mật khẩu <span id="pwd-hint" class="text-muted fw-normal" style="font-size:12px;"></span></label>
                        <input type="password" class="form-control" name="password" id="admin_password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Vai trò <span class="text-danger">*</span></label>
                        <select class="form-select" name="role_id" id="admin_role" required>
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
                        <input type="text" class="form-control" name="name" id="admin_name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" id="admin_phone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Phòng ban</label>
                        <input type="text" class="form-control" name="department" id="admin_department">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-14">Chức vụ</label>
                        <input type="text" class="form-control" name="position" id="admin_position">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-14">Ngày sinh</label>
                        <input type="date" class="form-control" name="dob" id="admin_dob">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-14">Ngày bắt đầu làm</label>
                        <input type="date" class="form-control" name="start_date" id="admin_start_date">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-14">Hotline cá nhân</label>
                        <input type="text" class="form-control" name="hotline" id="admin_hotline">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer border-top-0 pt-0">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
            <button type="button" class="btn btn-info text-white px-4" id="btnSaveAdmin" style="background-color:#0dcaf0; border:none; font-weight:500;">
                Lưu tài khoản
            </button>
        </div>
    </div></div>
</div>

<?php
$inlineScript = <<<'JS'
$('#modalAdmin').on('hidden.bs.modal', function () {
    $('#formAdmin')[0].reset();
    $('#admin_id').val('');
    $('#admin_password').prop('required', true);
    $('#pwd-hint').text('');
    $('.modal-title').text('Thêm tài khoản');
    $('#admin_active').prop('checked', true);
});

$('.btn-edit-admin').on('click', function() {
    const data = $(this).data();
    $('#admin_id').val(data.id);
    $('#admin_name').val(data.name);
    $('#admin_email').val(data.email);
    $('#admin_role').val(data.role);
    $('#admin_active').prop('checked', data.active == 1);
    
    $('#admin_phone').val(data.phone);
    $('#admin_department').val(data.department);
    $('#admin_position').val(data.position);
    $('#admin_dob').val(data.dob);
    $('#admin_start_date').val(data.startdate);
    $('#admin_hotline').val(data.hotline);
    
    // For edit, password is empty = no change
    $('#admin_password').prop('required', false);
    $('#pwd-hint').text('(Để trống để giữ nguyên)');
    
    $('.modal-title').text('Cập nhật tài khoản');
    $('#modalAdmin').modal('show');
});

$('#btnSaveAdmin').on('click', function() {
    if(!$('#formAdmin')[0].checkValidity()) {
        $('#formAdmin')[0].reportValidity();
        return;
    }
    
    const id = $('#admin_id').val();
    const action = id ? 'update' : 'create';
    const data = $('#formAdmin').serialize() + '&action=' + action;
    
    lmsAjax('/lms1025edu/admin/api/admins.php', data, function(res) {
        if(res.success) {
            lmsToast('success', 'Lưu tài khoản thành công!');
            $('#modalAdmin').modal('hide');
            setTimeout(() => location.reload(), 1000);
        } else {
            lmsToast('danger', res.error || 'Lỗi hệ thống');
        }
    });
});

$('.btn-delete-admin').on('click', function() {
    const id = $(this).data('id');
    if(!confirm('Xác nhận xóa tài khoản này?')) return;

    lmsAjax('/lms1025edu/admin/api/admins.php', { action: 'delete', id: id }, function(res) {
        if(res.success) {
            lmsToast('success', 'Đã xóa tài khoản!');
            setTimeout(() => location.reload(), 1000);
        } else {
            lmsToast('danger', res.error || 'Lỗi khi xóa!');
        }
    });
});

$('.btn-toggle-status').on('change', function() {
    const id = $(this).data('id');
    const status = this.checked ? 1 : 0;
    
    lmsAjax('/lms1025edu/admin/api/admins.php', { action: 'toggle_status', id: id, status: status }, function(res) {
        if(!res.success) {
            lmsToast('danger', res.error || 'Không thể đổi trạng thái!');
            setTimeout(() => location.reload(), 1000);
        }
    });
});
JS;
?>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
