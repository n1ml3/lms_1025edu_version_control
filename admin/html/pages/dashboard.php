<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="main-area" id="mainArea">
    <?php require_once __DIR__ . '/../layouts/topbar.php'; ?>
    <main class="page-content">

        <!-- Alert Banners
        <div class="alert alert-success d-flex align-items-center mb-3 px-3 py-2" style="background:#e6fcf5; color:#00a786; border:none; border-radius:6px;">
            <i class='bx bx-check-circle me-2' style="font-size:18px;"></i>
            <span style="font-size:13.5px; font-weight:500;">Bạn đã được kết nối với tổng đài.</span>
        </div>
        <div class="alert alert-danger d-flex align-items-center mb-4 px-3 py-2" style="background:#fff0f6; color:#e64980; border:none; border-radius:6px;">
            <i class='bx bx-error-circle me-2' style="font-size:18px;"></i>
            <span style="font-size:13.5px; font-weight:500;">Bạn chưa chia ca cho tuần sau.</span>
        </div> -->

        <!-- Top 4 Stat Cards -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format($statBranches) ?></div>
                        <p class="stat-label">Cơ sở</p>
                    </div>
                    <div class="stat-icon" style="background:#00bfa5; color:#fff;"><i class='bx bx-building-house'></i></div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format($statSources) ?></div>
                        <p class="stat-label">Nguồn Lead</p>
                    </div>
                    <div class="stat-icon" style="background:#3b82f6; color:#fff;"><i class='bx bx-globe'></i></div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format($statCourses) ?></div>
                        <p class="stat-label">Khóa học</p>
                    </div>
                    <div class="stat-icon" style="background:#f59e0b; color:#fff;"><i class='bx bx-book-open'></i></div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-info">
                        <div class="stat-value"><?= number_format($statTeachers) ?></div>
                        <p class="stat-label">Giảng viên</p>
                    </div>
                    <div class="stat-icon" style="background:#8b5cf6; color:#fff;"><i class='bx bx-chalkboard'></i></div>
                </div>
            </div>
        </div>

        <!-- Thống kê Card -->
        <div class="content-card mb-4" id="statsFilterCard">
            <div class="content-card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="fw-bold text-dark" style="font-size: 15px;">Thống kê</span>
                    <select class="form-select form-select-sm" id="filterBranch" style="width: auto; min-width: 140px; font-size:13px;">
                        <option value="">Chọn cơ sở</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['id'] ?>"><?= $branch['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select class="form-select form-select-sm" id="filterSource" style="width: auto; min-width: 140px; font-size:13px;">
                        <option value="">Chọn nguồn</option>
                    </select>
                    <select class="form-select form-select-sm" id="filterStaff" style="width: auto; min-width: 140px; font-size:13px;">
                        <option value="">Chọn nhân viên</option>
                    </select>
                    <div class="input-group input-group-sm" style="width: auto; min-width: 240px;">
                        <input type="date" class="form-control" id="filterDateFrom" value="<?= date('Y-m-01') ?>" style="font-size:13px;">
                        <span class="input-group-text bg-light border-start-0 border-end-0 text-muted">-</span>
                        <input type="date" class="form-control" id="filterDateTo" value="<?= date('Y-m-d') ?>" style="font-size:13px;">
                    </div>
                </div>
                <button class="btn-primary-custom" id="btnFilter" style="background: #00bfa5; padding: 6px 16px; font-size:13px;">
                    <i class='bx bx-filter-alt'></i> Lọc
                </button>
            </div>
            <div class="content-card-body p-4">
                <div class="row g-4" id="miniStats">
                    <?php
                    $miniStats = [
                        ['icon'=>'bx-phone-call',  'color'=>'#4f46e5', 'bg'=>'#e0e7ff', 'label'=>'Lead liên hệ',  'val'=> number_format($statLeads)],
                        ['icon'=>'bx-user',        'color'=>'#0891b2', 'bg'=>'#cffafe', 'label'=>'Khách hàng',    'val'=> number_format($statStudents)],
                        ['icon'=>'bx-time-five',   'color'=>'#059669', 'bg'=>'#d1fae5', 'label'=>'Lịch hẹn',      'val'=> number_format($statAppts)],
                        ['icon'=>'bx-cart',        'color'=>'#d97706', 'bg'=>'#fef3c7', 'label'=>'Đơn hàng',      'val'=> number_format($statOrders)],
                        ['icon'=>'bx-money',       'color'=>'#7c3aed', 'bg'=>'#ede9fe', 'label'=>'Dự thu',        'val'=> number_format($statRevExpected)],
                        ['icon'=>'bx-check-shield','color'=>'#059669', 'bg'=>'#d1fae5', 'label'=>'Đã thu',        'val'=> number_format($statRevActual)],
                    ];
                    foreach ($miniStats as $ms): ?>
                    <div class="col-6 col-md-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon-mini" style="width:48px; height:48px; border-radius:12px; background:<?= $ms['bg'] ?>; color:<?= $ms['color'] ?>; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0;">
                                <i class='bx <?= $ms['icon'] ?>'></i>
                            </div>
                            <div>
                                <div style="font-size:20px; font-weight:700; color:var(--text-dark); line-height:1.2;"><?= $ms['val'] ?></div>
                                <div style="font-size:13px; color:var(--text-muted); font-weight:500; margin-top:2px;"><?= $ms['label'] ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Schedule Row -->
        <div class="content-card mb-5">
            <div class="content-card-body p-4">
                <ul class="nav schedule-tabs" id="scheduleTabs">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabShift">Lịch trực tuần</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabAppt">Lịch hẹn</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabClass">Lịch học trực tiếp</button>
                    </li>
                </ul>
                <div class="tab-content mt-4">
                    <div class="tab-pane fade show active" id="tabShift">
                        <div class="empty-state text-center py-5">
                            <i class='bx bx-calendar-x d-block mx-auto' style="font-size:64px; color:#cbd5e1; margin-bottom:16px;"></i>
                            <p style="color:var(--text-muted); font-size:14.5px;">Không tìm thấy bản ghi nào!</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabAppt">
                        <div class="empty-state text-center py-5">
                            <i class='bx bx-calendar-x d-block mx-auto' style="font-size:64px; color:#cbd5e1; margin-bottom:16px;"></i>
                            <p style="color:var(--text-muted); font-size:14.5px;">Không tìm thấy bản ghi nào!</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabClass">
                        <div class="empty-state text-center py-5">
                            <i class='bx bx-calendar-x d-block mx-auto' style="font-size:64px; color:#cbd5e1; margin-bottom:16px;"></i>
                            <p style="color:var(--text-muted); font-size:14.5px;">Không tìm thấy bản ghi nào!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAB -->
        <div class="fab-fixed" title="Cài đặt">
            <i class='bx bx-cog'></i>
        </div>

    </main>
</div><!-- /.main-area -->

<?php
$extraScripts = ['/lms1025edu/admin/js/dashboard.js'];
require_once __DIR__ . '/../layouts/footer.php';
?>
