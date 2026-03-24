<?php
/**
 * Admin Sidebar Navigation
 * Variables expected: $activePage (string key)
 */
$activePage = $activePage ?? '';

$navItems = [
    [
        'label' => 'Tổng Quan',
        'icon'  => 'bx-home-alt-2',
        'key'   => 'dashboard',
        'url'   => '/lms1025edu/admin/index.php',
    ],
    [
        'label'    => 'CRM',
        'icon'     => 'bx-target-lock',
        'key'      => 'crm',
        'children' => [
            ['label' => 'Danh sách Lead',   'url' => '/lms1025edu/admin/pages/crm/leads.php',        'key' => 'crm_leads'],
            ['label' => 'Lịch Hẹn',         'url' => '/lms1025edu/admin/pages/crm/appointments.php', 'key' => 'crm_appointments'],
        ],
    ],
    [
        'label'    => 'Thành Viên',
        'icon'     => 'bx-group',
        'key'      => 'members',
        'children' => [
            ['label' => 'Quản trị viên', 'url' => '/lms1025edu/admin/pages/members/list.php',  'key' => 'members_list'],
            ['label' => 'Phân quyền',    'url' => '/lms1025edu/admin/pages/members/roles.php', 'key' => 'members_roles'],
        ],
    ],
    [
        'label'    => 'Khóa Học',
        'icon'     => 'bx-book-open',
        'key'      => 'courses',
        'children' => [
            ['label' => 'Chương trình học', 'url' => '/lms1025edu/admin/pages/courses/programs.php', 'key' => 'courses_programs'],
            ['label' => 'Lớp học',          'url' => '/lms1025edu/admin/pages/courses/classes.php',  'key' => 'courses_classes'],
            ['label' => 'Bài kiểm tra',     'url' => '/lms1025edu/admin/pages/courses/quiz.php',     'key' => 'courses_quiz'],
        ],
    ],
    [
        'label'    => 'Sản Phẩm',
        'icon'     => 'bx-package',
        'key'      => 'products',
        'children' => [
            ['label' => 'Danh sách', 'url' => '/lms1025edu/admin/pages/products/list.php', 'key' => 'products_list'],
            ['label' => 'Thêm mới',  'url' => '/lms1025edu/admin/pages/products/add.php',  'key' => 'products_add'],
        ],
    ],
    [
        'label'    => 'Giảng Viên',
        'icon'     => 'bx-chalkboard',
        'key'      => 'instructors',
        'children' => [
            ['label' => 'Giáo viên',    'url' => '/lms1025edu/admin/pages/instructors/teachers.php',     'key' => 'inst_teachers'],
            ['label' => 'Đại lý',       'url' => '/lms1025edu/admin/pages/instructors/agents.php',       'key' => 'inst_agents'],
            ['label' => 'Nguồn dữ liệu','url' => '/lms1025edu/admin/pages/instructors/data-sources.php','key' => 'inst_sources'],
        ],
    ],
    [
        'label'    => 'Thông Báo',
        'icon'     => 'bx-bell',
        'key'      => 'notifications',
        'children' => [
            ['label' => 'Chung',      'url' => '/lms1025edu/admin/pages/notifications/general.php', 'key' => 'notif_general'],
            ['label' => 'Nhân viên',  'url' => '/lms1025edu/admin/pages/notifications/staff.php',   'key' => 'notif_staff'],
        ],
    ],
    [
        'label'    => 'Khuyến Mãi',
        'icon'     => 'bx-purchase-tag',
        'key'      => 'promotions',
        'children' => [
            ['label' => 'Mã giảm giá', 'url' => '/lms1025edu/admin/pages/promotions/coupons.php', 'key' => 'promo_coupons'],
        ],
    ],
    [
        'label'    => 'Cài Đặt',
        'icon'     => 'bx-cog',
        'key'      => 'settings',
        'children' => [
            ['label' => 'Media',  'url' => '/lms1025edu/admin/pages/settings/media.php',   'key' => 'settings_media'],
            ['label' => 'Lưu trữ','url' => '/lms1025edu/admin/pages/settings/storage.php', 'key' => 'settings_storage'],
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
        <div class="brand-icon">
            <i class='bx bx-graduation'></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">LMS Admin</span>
            <span class="brand-sub">Education Platform</span>
        </div>
    </div>

    <div class="sidebar-nav">
        <?php foreach ($navItems as $item): ?>
            <?php
            $hasChildren  = !empty($item['children']);
            $isActive     = ($activePage === $item['key']) || isParentActive($item, $activePage);
            $collapseId   = 'nav-' . $item['key'];
            ?>

            <?php if (!$hasChildren): ?>
                <a href="<?= $item['url'] ?>" class="sidebar-link <?= $isActive ? 'active' : '' ?>">
                    <i class='bx <?= $item['icon'] ?>'></i>
                    <span><?= $item['label'] ?></span>
                </a>
            <?php else: ?>
                <button class="sidebar-link sidebar-link-toggle <?= $isActive ? 'active' : '' ?>"
                        data-bs-toggle="collapse"
                        data-bs-target="#<?= $collapseId ?>"
                        aria-expanded="<?= $isActive ? 'true' : 'false' ?>">
                    <i class='bx <?= $item['icon'] ?>'></i>
                    <span><?= $item['label'] ?></span>
                    <i class='bx bx-chevron-right sidebar-arrow ms-auto'></i>
                </button>
                <div class="collapse <?= $isActive ? 'show' : '' ?>" id="<?= $collapseId ?>">
                    <div class="sidebar-submenu">
                        <?php foreach ($item['children'] as $child): ?>
                            <a href="<?= $child['url'] ?>"
                               class="sidebar-sublink <?= ($activePage === $child['key']) ? 'active' : '' ?>">
                                <i class='bx bx-circle'></i>
                                <?= $child['label'] ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</aside>
