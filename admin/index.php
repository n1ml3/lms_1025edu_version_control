<?php
/**
 * Admin Dashboard (index.php)
 */
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

$pageTitle = 'Tổng Quan';
$activePage = 'dashboard';
$breadcrumb = [];

// ── Quick Stats (fallback dummy data if tables don't exist yet) ──
try {
    $statBranches  = $pdo->query("SELECT COUNT(*) FROM branches")->fetchColumn();
    $statSources   = $pdo->query("SELECT COUNT(*) FROM lead_sources")->fetchColumn();
    $statCourses   = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    $statTeachers  = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();

    $statLeads       = $pdo->query("SELECT COUNT(*) FROM leads WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    $statStudents    = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $statAppts       = $pdo->query("SELECT COUNT(*) FROM appointments WHERE DATE(datetime) = CURDATE()")->fetchColumn();
    $statOrders      = $pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    $statRevExpected = $pdo->query("SELECT COALESCE(SUM(amount),0) FROM orders WHERE status='pending' AND MONTH(created_at)=MONTH(CURDATE())")->fetchColumn();
    $statRevActual   = $pdo->query("SELECT COALESCE(SUM(amount),0) FROM orders WHERE status='paid' AND MONTH(created_at)=MONTH(CURDATE())")->fetchColumn();
} catch (Exception $e) {
    // Tables not yet created — use demo data
    $statBranches = 3; $statSources = 8; $statCourses = 24; $statTeachers = 12;
    $statLeads = 7; $statStudents = 348; $statAppts = 5; $statOrders = 15;
    $statRevExpected = 125000000; $statRevActual = 98000000;
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-area">
    <?php /* Topbar already rendered in header.php */ ?>
    <main class="page-content">

        <!-- Page Header -->
        <div class="page-header d-flex align-items-center justify-content-between">
            <div>
                <h1 class="page-title">Tổng Quan</h1>
                <p class="page-subtitle">Chào buổi sáng, <strong><?= htmlspecialchars($adminName) ?></strong> — Hôm nay là <?= date('d/m/Y') ?></p>
            </div>
            <button class="btn-primary-custom" id="btnRefreshStats">
                <i class='bx bx-refresh'></i> Làm mới
            </button>
        </div>

        <!-- Top 4 Stat Cards -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon indigo"><i class='bx bx-building-house'></i></div>
                    <div class="stat-info">
                        <p class="stat-label">Cơ sở</p>
                        <div class="stat-value"><?= number_format($statBranches) ?></div>
                        <p class="stat-change up"><i class='bx bx-trending-up'></i> Hoạt động</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon cyan"><i class='bx bx-globe'></i></div>
                    <div class="stat-info">
                        <p class="stat-label">Nguồn Lead</p>
                        <div class="stat-value"><?= number_format($statSources) ?></div>
                        <p class="stat-change up"><i class='bx bx-trending-up'></i> Nguồn đang theo dõi</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon green"><i class='bx bx-book-open'></i></div>
                    <div class="stat-info">
                        <p class="stat-label">Khóa học</p>
                        <div class="stat-value"><?= number_format($statCourses) ?></div>
                        <p class="stat-change up"><i class='bx bx-trending-up'></i> Đang mở</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon orange"><i class='bx bx-chalkboard'></i></div>
                    <div class="stat-info">
                        <p class="stat-label">Giảng viên</p>
                        <div class="stat-value"><?= number_format($statTeachers) ?></div>
                        <p class="stat-change up"><i class='bx bx-trending-up'></i> Đang giảng dạy</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar mb-4" id="filterBar">
            <div>
                <div class="form-label">Cơ sở</div>
                <select class="form-select" id="filterBranch" style="min-width:150px">
                    <option value="">Tất cả cơ sở</option>
                </select>
            </div>
            <div>
                <div class="form-label">Nguồn</div>
                <select class="form-select" id="filterSource" style="min-width:140px">
                    <option value="">Tất cả nguồn</option>
                </select>
            </div>
            <div>
                <div class="form-label">Nhân viên</div>
                <select class="form-select" id="filterStaff" style="min-width:150px">
                    <option value="">Tất cả nhân viên</option>
                </select>
            </div>
            <div>
                <div class="form-label">Từ ngày</div>
                <input type="date" class="form-control" id="filterDateFrom" style="min-width:145px"
                       value="<?= date('Y-m-01') ?>">
            </div>
            <div>
                <div class="form-label">Đến ngày</div>
                <input type="date" class="form-control" id="filterDateTo" style="min-width:145px"
                       value="<?= date('Y-m-d') ?>">
            </div>
            <div class="ms-auto">
                <button class="btn-primary-custom" id="btnFilter">
                    <i class='bx bx-filter-alt'></i> Lọc
                </button>
            </div>
        </div>

        <!-- Mini Stats 6 cards -->
        <div class="row g-3 mb-4" id="miniStats">
            <?php
            $miniStats = [
                ['icon'=>'bx-phone-call',  'color'=>'indigo', 'label'=>'Lead liên hệ',  'val'=> number_format($statLeads)],
                ['icon'=>'bx-user-check',  'color'=>'cyan',   'label'=>'Học viên',       'val'=> number_format($statStudents)],
                ['icon'=>'bx-calendar',    'color'=>'green',  'label'=>'Lịch hẹn HN',   'val'=> number_format($statAppts)],
                ['icon'=>'bx-receipt',     'color'=>'orange', 'label'=>'Đơn hàng HN',   'val'=> number_format($statOrders)],
                ['icon'=>'bx-dollar-circle','color'=>'purple','label'=>'Dự thu (tháng)','val'=> number_format($statRevExpected/1000000,1).'M đ'],
                ['icon'=>'bx-check-circle','color'=>'green',  'label'=>'Đã thu (tháng)', 'val'=> number_format($statRevActual/1000000,1).'M đ'],
            ];
            foreach ($miniStats as $ms): ?>
            <div class="col-sm-6 col-md-4 col-xl-2">
                <div class="content-card p-3 text-center" style="cursor:default">
                    <div class="stat-icon <?= $ms['color'] ?> mx-auto mb-2" style="width:44px;height:44px;font-size:22px">
                        <i class='bx <?= $ms['icon'] ?>'></i>
                    </div>
                    <div style="font-size:20px;font-weight:700;color:var(--text-dark)"><?= $ms['val'] ?></div>
                    <div style="font-size:12px;color:var(--text-muted);font-weight:500;margin-top:2px"><?= $ms['label'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Chart + Schedule Row -->
        <div class="row g-4 mb-4">
            <!-- Revenue Chart -->
            <div class="col-xl-7">
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">Doanh Thu Theo Tuần</h3>
                        <select class="form-select form-select-sm" id="chartPeriod" style="width:120px">
                            <option value="week">Tuần này</option>
                            <option value="month">Tháng này</option>
                            <option value="quarter">Quý này</option>
                        </select>
                    </div>
                    <div class="content-card-body">
                        <div style="height:280px;position:relative">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Tabs -->
            <div class="col-xl-5">
                <div class="content-card h-100">
                    <div class="content-card-header">
                        <h3 class="content-card-title">Lịch</h3>
                    </div>
                    <div class="content-card-body">
                        <ul class="nav schedule-tabs gap-2 mb-3" id="scheduleTabs">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabShift">Trực tuần</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabAppt">Lịch hẹn</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabClass">Học trực tiếp</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tabShift">
                                <p class="text-muted fs-13 text-center py-4">
                                    <i class='bx bx-calendar-check d-block mb-2' style="font-size:32px"></i>
                                    Không có lịch trực hôm nay
                                </p>
                            </div>
                            <div class="tab-pane fade" id="tabAppt">
                                <p class="text-muted fs-13 text-center py-4">
                                    <i class='bx bx-phone d-block mb-2' style="font-size:32px"></i>
                                    Không có lịch hẹn hôm nay
                                </p>
                            </div>
                            <div class="tab-pane fade" id="tabClass">
                                <p class="text-muted fs-13 text-center py-4">
                                    <i class='bx bx-chalkboard d-block mb-2' style="font-size:32px"></i>
                                    Không có lớp học hôm nay
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div><!-- /.main-area -->

<?php
$extraScripts = ['/lms1025edu/admin/assets/js/dashboard.js'];
$inlineScript = <<<'JS'
$(function () {
    // Init revenue chart
    initDashboardCharts();

    // Filter AJAX
    $('#btnFilter, #btnRefreshStats').on('click', function () {
        const data = {
            branch:   $('#filterBranch').val(),
            source:   $('#filterSource').val(),
            staff:    $('#filterStaff').val(),
            dateFrom: $('#filterDateFrom').val(),
            dateTo:   $('#filterDateTo').val(),
        };
        lmsAjax('/lms1025edu/admin/api/dashboard_stats.php', data, function (res) {
            lmsToast('success', 'Đã cập nhật thống kê!');
            // TODO: update mini stat values from res
        });
    });
});
JS;

require_once __DIR__ . '/includes/footer.php';
?>
