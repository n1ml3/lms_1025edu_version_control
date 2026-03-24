<?php
/**
 * Admin Topbar & Dashboard Toolbar
 * Included inside the .main-area wrapper
 */
?>
<!-- Topbar -->
<header class="topbar" id="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
            <i class='bx bx-menu'></i>
        </button>
    </div>
    <div class="topbar-right">
        <button class="topbar-icon-btn" title="Toàn màn hình" onclick="document.documentElement.requestFullscreen()">
            <i class='bx bx-fullscreen'></i>
        </button>
        <button class="topbar-icon-btn" id="darkModeToggle" title="Chế độ tối">
            <i class='bx bx-moon'></i>
        </button>
        <button class="topbar-icon-btn" title="Thông báo">
            <i class='bx bx-bell'></i>
            <span class="notif-badge">0</span>
        </button>
        <div class="dropdown">
            <button class="topbar-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; border: none; padding: 0;">
                <img src="/lms1025edu/admin/assets/images/person.png" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                <div class="d-none d-sm-block text-start ms-2">
                    <span class="d-block fw-semibold" style="font-size: 13px; color: var(--text-dark); line-height: 1.2;"><?= htmlspecialchars($adminName) ?></span>
                    <span class="d-block text-muted" style="font-size: 11px;"><?= htmlspecialchars($adminRole) ?></span>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="#"><i class='bx bx-user me-2'></i>Hồ sơ</a></li>
                <li><a class="dropdown-item" href="#"><i class='bx bx-cog me-2'></i>Cài đặt</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/lms1025edu/admin/logout.php"><i class='bx bx-log-out me-2'></i>Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- Dashboard Toolbar -->
<div class="dashboard-toolbar">
    <h2 class="toolbar-title"><?= htmlspecialchars($pageTitle) ?></h2>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/lms1025edu/admin/index.php">CRM</a></li>
            <?php if (!empty($breadcrumb)): ?>
                <?php foreach ($breadcrumb as $b): ?>
                    <?php if (!empty($b['url'])): ?>
                        <li class="breadcrumb-item"><a href="<?= $b['url'] ?>"><?= htmlspecialchars($b['label']) ?></a></li>
                    <?php else: ?>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($b['label']) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            <?php endif; ?>
        </ol>
    </nav>
</div>
