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
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/index.php',
    ],
    [
        'label' => 'CRM',
        'icon'  => 'bx-target-lock',
        'key'   => 'crm',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/crm/leads.php',
    ],
    [
        'label' => 'Thành viên quản trị',
        'icon'  => 'bx-group',
        'key'   => 'members_list',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/members/list.php',
    ],
    [
        'label' => 'Danh sách quyền',
        'icon'  => 'bx-shield',
        'key'   => 'members_roles',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/members/roles.php',
    ],
    [
        'label' => 'Thông báo',
        'icon'  => 'bx-bell',
        'key'   => 'notif_general',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/notifications/general.php',
    ],
    [
        'label' => 'Thông báo nhân viên',
        'icon'  => 'bx-bell',
        'key'   => 'notif_staff',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/notifications/staff.php',
    ],
    [ 'section' => 'KHÓA HỌC - LỊCH HỌC' ],
    [
        'label' => 'Chương trình học',
        'icon'  => 'bx-book-open',
        'key'   => 'courses_programs',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/courses/programs.php',
    ],
    [
        'label' => 'Lớp học',
        'icon'  => 'bx-time',
        'key'   => 'courses_classes',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/courses/classes.php',
    ],
    [ 'section' => 'SẢN PHẨM' ],
    [
        'label' => 'Sản phẩm',
        'icon'  => 'bx-package',
        'key'   => 'products_list',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/products/list.php',
    ],
    [
        'label' => 'Thêm sản phẩm',
        'icon'  => 'bx-cart-add',
        'key'   => 'products_add',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/products/add.php',
    ],
    [ 'section' => 'GIẢNG VIÊN - ĐẠI LÝ' ],
    [
        'label' => 'Giảng viên',
        'icon'  => 'bx-chalkboard',
        'key'   => 'inst_teachers',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/instructors/teachers.php',
    ],
    [
        'label' => 'Đại lý - Nguồn',
        'icon'  => 'bx-buildings',
        'key'   => 'inst_agents',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/instructors/agents.php',
    ],
    [
        'label' => 'Nguồn data đầu vào',
        'icon'  => 'bx-data',
        'key'   => 'inst_sources',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/instructors/data-sources.php',
    ],
    [
        'label' => 'Học sinh',
        'icon'  => 'bx-user',
        'key'   => 'students_list',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/students/list.php',
    ],
    [ 'section' => 'MÃ' ],
    [
        'label' => 'Mã khuyến mãi',
        'icon'  => 'bx-gift',
        'key'   => 'promo_coupons',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/promotions/coupons.php',
    ],
    [ 'section' => 'SETTING' ],
    [
        'label' => 'Cơ sở',
        'icon'  => 'bx-store',
        'key'   => 'settings_branches',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/settings/branches.php',
    ],
    [
        'label' => 'Media',
        'icon'  => 'bx-image',
        'key'   => 'settings_media',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/settings/media.php',
    ],
    [
        'label' => 'Lưu trữ',
        'icon'  => 'bx-hdd',
        'key'   => 'settings_storage',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/settings/storage.php',
    ],
    [
        'label' => 'Kiểm tra Database',
        'icon'  => 'bx-data',
        'key'   => 'settings_dbcheck',
        'url'   => ($baseUrl ?? '/lms1025edu/admin') . '/pages/settings/db_check.php',
    ],
];
?>

<aside class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-brand">
        <a href="<?= $baseUrl ?? '/lms1025edu/admin' ?>/index.php" class="d-block text-center w-100 pb-2">
            <img src="<?= $baseUrl ?? '/lms1025edu/admin' ?>/images/logo-2.png" alt="Logo" class="sidebar-logo-img w-100" style="max-height: 45px; object-fit: contain;">
            <img src="<?= $baseUrl ?? '/lms1025edu/admin' ?>/images/favicon.png" alt="Icon" class="sidebar-logo-icon" style="max-height: 32px; object-fit: contain; display: none;">
        </a>
    </div>

    <div class="sidebar-nav" id="sidebarNav">
        <?php foreach ($navItems as $item): ?>
            <?php
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
