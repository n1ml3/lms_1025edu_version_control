<?php
/**
 * Admin Sidebar Navigation (Flattened for new design)
 * Variables expected: $activePage (string key)
 */
$activePage = $activePage ?? '';

$navItems = [
    [
        'label' => 'Tổng quan',
        'icon'  => 'bx-home-alt-2',
        'key'   => 'dashboard',
        'url'   => ($baseUrl ?? '/admin') . '/index.php',
        'permission' => 'dashboard'
    ],
    [
        'label' => 'CRM',
        'icon'  => 'bx-target-lock',
        'key'   => 'crm',
        'url'   => ($baseUrl ?? '/admin') . '/pages/crm/leads.php',
        'permission' => 'crm'
    ],
    [
        'label' => 'Thành viên quản trị',
        'icon'  => 'bx-group',
        'key'   => 'members_list',
        'url'   => ($baseUrl ?? '/admin') . '/pages/members/list.php',
        'permission' => 'members'
    ],
    [
        'label' => 'Danh sách quyền',
        'icon'  => 'bx-shield',
        'key'   => 'members_roles',
        'url'   => ($baseUrl ?? '/admin') . '/pages/members/roles.php',
        'permission' => 'members'
    ],
    [
        'label' => 'Thông báo',
        'icon'  => 'bx-bell',
        'key'   => 'notif_general',
        'url'   => ($baseUrl ?? '/admin') . '/pages/notifications/general.php',
        'permission' => 'dashboard'
    ],
    [
        'label' => 'Thông báo nhân viên',
        'icon'  => 'bx-bell',
        'key'   => 'notif_staff',
        'url'   => ($baseUrl ?? '/admin') . '/pages/notifications/staff.php',
        'permission' => 'dashboard'
    ],
    [ 'section' => 'KHÓA HỌC - LỊCH HỌC', 'permission' => 'courses' ],
    [
        'label' => 'Chương trình học',
        'icon'  => 'bx-book-open',
        'key'   => 'courses_programs',
        'url'   => ($baseUrl ?? '/admin') . '/pages/courses/programs.php',
        'permission' => 'courses'
    ],
    [
        'label' => 'Lớp học',
        'icon'  => 'bx-time',
        'key'   => 'courses_classes',
        'url'   => ($baseUrl ?? '/admin') . '/pages/courses/classes.php',
        'permission' => 'courses'
    ],
    [ 'section' => 'SẢN PHẨM', 'permission' => 'products' ],
    [
        'label' => 'Sản phẩm',
        'icon'  => 'bx-package',
        'key'   => 'products_list',
        'url'   => ($baseUrl ?? '/admin') . '/pages/products/list.php',
        'permission' => 'products'
    ],
    [
        'label' => 'Thêm sản phẩm',
        'icon'  => 'bx-cart-add',
        'key'   => 'products_add',
        'url'   => ($baseUrl ?? '/admin') . '/pages/products/add.php',
        'permission' => 'products'
    ],
    [ 'section' => 'GIẢNG VIÊN - ĐẠI LÝ', 'permission' => 'instructors' ],
    [
        'label' => 'Giảng viên',
        'icon'  => 'bx-chalkboard',
        'key'   => 'inst_teachers',
        'url'   => ($baseUrl ?? '/admin') . '/pages/instructors/teachers.php',
        'permission' => 'instructors'
    ],
    [
        'label' => 'Đại lý - Nguồn',
        'icon'  => 'bx-buildings',
        'key'   => 'inst_agents',
        'url'   => ($baseUrl ?? '/admin') . '/pages/instructors/agents.php',
        'permission' => 'instructors'
    ],
    [
        'label' => 'Nguồn data đầu vào',
        'icon'  => 'bx-data',
        'key'   => 'inst_sources',
        'url'   => ($baseUrl ?? '/admin') . '/pages/instructors/data-sources.php',
        'permission' => 'instructors'
    ],
    [
        'label' => 'Học sinh',
        'icon'  => 'bx-user',
        'key'   => 'students_list',
        'url'   => ($baseUrl ?? '/admin') . '/pages/students/list.php',
        'permission' => 'instructors'
    ],
    [ 'section' => 'MÃ', 'permission' => 'promotions' ],
    [
        'label' => 'Mã khuyến mãi',
        'icon'  => 'bx-gift',
        'key'   => 'promo_coupons',
        'url'   => ($baseUrl ?? '/admin') . '/pages/promotions/coupons.php',
        'permission' => 'promotions'
    ],
    [ 'section' => 'SETTING', 'permission' => 'settings' ],
    [
        'label' => 'Cơ sở',
        'icon'  => 'bx-store',
        'key'   => 'settings_branches',
        'url'   => ($baseUrl ?? '/admin') . '/pages/settings/branches.php',
        'permission' => 'settings'
    ],
    [
        'label' => 'Media',
        'icon'  => 'bx-image',
        'key'   => 'settings_media',
        'url'   => ($baseUrl ?? '/admin') . '/pages/settings/media.php',
        'permission' => 'settings'
    ],
    [
        'label' => 'Lưu trữ',
        'icon'  => 'bx-hdd',
        'key'   => 'settings_storage',
        'url'   => ($baseUrl ?? '/admin') . '/pages/settings/storage.php',
        'permission' => 'settings'
    ],
    [
        'label' => 'Kiểm tra Database',
        'icon'  => 'bx-data',
        'key'   => 'settings_dbcheck',
        'url'   => ($baseUrl ?? '/admin') . '/pages/settings/db_check.php',
        'permission' => 'settings'
    ],
];
?>

<aside class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-brand">
        <a href="<?= $baseUrl ?? '/admin' ?>/index.php" class="d-block text-center w-100 pb-2">
            <img src="<?= $baseUrl ?? '/admin' ?>/images/logo-2.png" alt="Logo" class="sidebar-logo-img w-100" style="max-height: 45px; object-fit: contain;">
            <img src="<?= $baseUrl ?? '/admin' ?>/images/favicon.png" alt="Icon" class="sidebar-logo-icon" style="max-height: 32px; object-fit: contain; display: none;">
        </a>
    </div>

    <div class="sidebar-nav" id="sidebarNav">
        <?php foreach ($navItems as $item): ?>
            <?php
            // Check permission for the item
            if (isset($item['permission']) && !hasPermission($item['permission'])) {
                continue;
            }

            if (isset($item['section'])) {
                echo '<div class="sidebar-section">' . htmlspecialchars($item['section']) . '</div>';
                continue;
            }

            $isActive = ($activePage === $item['key']);
            ?>

            <a href="<?= $item['url'] ?>" class="sidebar-link <?= $isActive ? 'active' : '' ?>" data-page-key="<?= $item['key'] ?>">
                <i class='bx <?= $item['icon'] ?>'></i>
                <span><?= $item['label'] ?></span>
                <?php if (isset($item['badge'])): ?>
                    <span class="sidebar-badge text-white bg-danger rounded-pill" style="font-size: 10px; padding: 2px 6px; position: absolute; right: 15px;"><?= $item['badge'] ?></span>
                <?php endif; ?>
            </a>

        <?php endforeach; ?>
    </div>
</aside>
