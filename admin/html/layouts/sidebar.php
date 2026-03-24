<?php
/**
 * Admin Sidebar Navigation
 * Variables expected: $activePage (string key)
 */
$activePage = $activePage ?? '';

$navItems = [
    [
        'label' => 'Dashboards',
        'icon'  => 'bx-home-alt-2',
        'key'   => 'dashboard',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/index.php',
    ],
    [
        'label'    => 'CRM',
        'icon'     => 'bx-target-lock',
        'key'      => 'crm',
        'children' => [
            ['label' => 'Lead liên hệ',   'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/crm/leads.php',        'key' => 'crm_leads', 'badge' => '1'],
            ['label' => 'Lịch Hẹn',         'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/crm/appointments.php', 'key' => 'crm_appointments', 'badge' => '0'],
            ['label' => 'Đơn hàng',         'url' => '#', 'key' => 'crm_orders', 'badge' => '0'],
        ],
    ],
    [
        'label'    => 'Thành Viên',
        'icon'     => 'bx-group',
        'key'      => 'members',
        'children' => [
            ['label' => 'Quản trị viên', 'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/members/list.php',  'key' => 'members_list'],
            ['label' => 'Phân quyền',    'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/members/roles.php', 'key' => 'members_roles'],
        ],
    ],
    [
        'label'    => 'Khóa Học',
        'icon'     => 'bx-book-open',
        'key'      => 'courses',
        'children' => [
            ['label' => 'Chương trình học', 'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/courses/programs.php', 'key' => 'courses_programs'],
            ['label' => 'Lớp học',          'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/courses/classes.php',  'key' => 'courses_classes'],
            ['label' => 'Bài kiểm tra',     'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/courses/quiz.php',     'key' => 'courses_quiz'],
        ],
    ],
    [
        'label'    => 'Sản Phẩm',
        'icon'     => 'bx-package',
        'key'      => 'products',
        'children' => [
            ['label' => 'Danh sách', 'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/products/list.php', 'key' => 'products_list'],
            ['label' => 'Thêm mới',  'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/products/add.php',  'key' => 'products_add'],
        ],
    ],
    [
        'label'    => 'Giảng Viên',
        'icon'     => 'bx-chalkboard',
        'key'      => 'instructors',
        'children' => [
            ['label' => 'Giáo viên',    'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/instructors/teachers.php',     'key' => 'inst_teachers'],
            ['label' => 'Đại lý',       'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/instructors/agents.php',       'key' => 'inst_agents'],
            ['label' => 'Nguồn dữ liệu','url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/instructors/data-sources.php','key' => 'inst_sources'],
        ],
    ],
    [
        'label'    => 'Thông Báo',
        'icon'     => 'bx-bell',
        'key'      => 'notifications',
        'children' => [
            ['label' => 'Chung',      'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/notifications/general.php', 'key' => 'notif_general'],
            ['label' => 'Nhân viên',  'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/notifications/staff.php',   'key' => 'notif_staff'],
        ],
    ],
    [ 'section' => 'PAGE' ],
    [
        'label'    => 'Kế toán',
        'icon'     => 'bx-pie-chart-alt',
        'key'      => 'accounting',
        'children' => [
            ['label' => 'Doanh thu', 'url' => '#', 'key' => 'acc_revenue'],
        ],
    ],
    [
        'label'    => 'Khuyến Mãi',
        'icon'     => 'bx-purchase-tag',
        'key'      => 'promotions',
        'children' => [
            ['label' => 'Mã giảm giá', 'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/promotions/coupons.php', 'key' => 'promo_coupons'],
        ],
    ],
    [
        'label'    => 'Cài Đặt',
        'icon'     => 'bx-cog',
        'key'      => 'settings',
        'children' => [
            ['label' => 'Cơ sở',  'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/settings/branches.php', 'key' => 'settings_branches'],
            ['label' => 'Media',  'url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/settings/media.php',   'key' => 'settings_media'],
            ['label' => 'Lưu trữ','url' => ($baseUrl ?? '/lms1025edu/admin') . '/pages/settings/storage.php', 'key' => 'settings_storage'],
        ],
    ],
];

function isParentActive(array $item, string $activePage): bool
{
    if (!empty($item['children'])) {
        foreach ($item['children'] as $child) {
            if ($child['key'] === $activePage) return true;
        }
    }
    return false;
}
?>

<aside class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-brand">
        <a href="<?= $baseUrl ?? '/lms1025edu/admin' ?>/index.php" class="d-block text-center w-100">
            <img src="<?= $baseUrl ?? '/lms1025edu/admin' ?>/images/logo-2.png" alt="Logo" class="sidebar-logo-img w-100" style="max-height: 45px; object-fit: contain;">
            <img src="<?= $baseUrl ?? '/lms1025edu/admin' ?>/images/favicon.png" alt="Icon" class="sidebar-logo-icon" style="max-height: 32px; object-fit: contain; display: none;">
        </a>
    </div>

    <div class="sidebar-nav">
        <?php foreach ($navItems as $item): ?>
            <?php 
            if (isset($item['section'])) {
                echo '<div class="sidebar-section">' . htmlspecialchars($item['section']) . '</div>';
                continue;
            }
            ?>

            <?php
            $hasChildren  = !empty($item['children']);
            $isActive     = ($activePage === $item['key']) || isParentActive($item, $activePage);
            $collapseId   = 'nav-' . $item['key'];
            ?>

            <?php if (!$hasChildren): ?>
                <a href="<?= $item['url'] ?>" class="sidebar-link <?= $isActive ? 'active' : '' ?>">
                    <i class='bx <?= $item['icon'] ?>'></i>
                    <span><?= $item['label'] ?></span>
                    <?php if (isset($item['badge'])): ?>
                        <span class="sidebar-badge"><?= $item['badge'] ?></span>
                    <?php endif; ?>
                </a>
            <?php else: ?>
                <button class="sidebar-link sidebar-link-toggle <?= $isActive ? 'active' : '' ?>"
                        data-bs-toggle="collapse"
                        data-bs-target="#<?= $collapseId ?>"
                        aria-expanded="<?= $isActive ? 'true' : 'false' ?>">
                    <i class='bx <?= $item['icon'] ?>'></i>
                    <span><?= $item['label'] ?></span>
                    <?php if (isset($item['badge'])): ?>
                        <span class="sidebar-badge"><?= $item['badge'] ?></span>
                    <?php endif; ?>
                    <i class='bx bx-chevron-right sidebar-arrow ms-auto'></i>
                </button>
                <div class="collapse <?= $isActive ? 'show' : '' ?>" id="<?= $collapseId ?>">
                    <div class="sidebar-submenu">
                        <?php foreach ($item['children'] as $child): ?>
                            <a href="<?= $child['url'] ?>"
                               class="sidebar-sublink <?= ($activePage === $child['key']) ? 'active' : '' ?>">
                                <i class='bx bx-circle'></i>
                                <?= $child['label'] ?>
                                <?php if (isset($child['badge'])): ?>
                                    <span class="sidebar-badge" style="margin-left:auto; transform: scale(0.9);"><?= $child['badge'] ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</aside>
