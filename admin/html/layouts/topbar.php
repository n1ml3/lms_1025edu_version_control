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
            <i class='bx bx-menu toggle-icon-expanded'></i>
            <i class='bx bx-right-arrow-alt toggle-icon-collapsed d-none'></i>
        </button>
    </div>
    <div class="topbar-right">
        <button class="topbar-icon-btn" title="Toàn màn hình" onclick="document.documentElement.requestFullscreen()">
            <i class='bx bx-fullscreen'></i>
        </button>
        <button class="topbar-icon-btn" id="darkModeToggle" title="Chế độ tối">
            <i class='bx bx-moon'></i>
        </button>
        
        <!-- Notification Dropdown -->
        <div class="dropdown">
            <button class="topbar-icon-btn notif-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                <i class='bx bx-bell'></i>
                <span class="notif-badge">3</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-sm notif-dropdown">
                <div class="notif-header">
                    <h6 class="mb-0 fw-bold">Thông báo của tôi</h6>
                    <button class="btn-close-notif" data-bs-toggle="dropdown"><i class='bx bx-x'></i></button>
                </div>
                <div class="notif-actions">
                    <a href="#" class="notif-mark-read">Đánh dấu tất cả là đã đọc</a>
                </div>
                <div class="notif-body">
                    <!-- Item 1 -->
                    <div class="notif-item unread">
                        <div class="notif-icon">
                            <img src="/lms1025edu/admin/images/logo-2.png" alt="Icon">
                        </div>
                        <div class="notif-content">
                            <a href="#" class="notif-title">Bạn có lịch hỗ trợ học viên</a>
                            <p class="notif-desc">HV - HVG test - 0900000020 - - Thời gian: 2025-10-30 15:35</p>
                            <div class="notif-meta">
                                <span class="notif-time">15:35 30/10</span>
                                <span class="notif-sep">|</span>
                                <a href="#" class="notif-action-link">Đánh dấu chưa đọc</a>
                            </div>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="notif-item unread">
                        <div class="notif-icon">
                            <img src="/lms1025edu/admin/images/logo-2.png" alt="Icon">
                        </div>
                        <div class="notif-content">
                            <a href="#" class="notif-title">Bạn có lịch hỗ trợ học viên</a>
                            <p class="notif-desc">HV - Trịnh Thị Kim Ngọc - 0967565434 - - Thời gian: 2025-10-30 15:24</p>
                            <div class="notif-meta">
                                <span class="notif-time">15:24 30/10</span>
                                <span class="notif-sep">|</span>
                                <a href="#" class="notif-action-link">Đánh dấu chưa đọc</a>
                            </div>
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="notif-item unread">
                        <div class="notif-icon">
                            <img src="/lms1025edu/admin/images/logo-2.png" alt="Icon">
                        </div>
                        <div class="notif-content">
                            <a href="#" class="notif-title">Bạn có lịch hỗ trợ học viên</a>
                            <p class="notif-desc">HV - Nguyễn Tuyết Nhung - 0962867598 - - Thời gian: 2025-08-20 10:00</p>
                            <div class="notif-meta">
                                <span class="notif-time">22:36 19/08</span>
                                <span class="notif-sep">|</span>
                                <a href="#" class="notif-action-link">Đánh dấu chưa đọc</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="notif-footer">
                    <a href="#">Xem tất cả</a>
                </div>
            </div>
        </div>
        <div class="dropdown">
            <button class="topbar-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; border: none; padding: 0;">
                <img src="/lms1025edu/admin/images/person.png" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
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
<div class="dashboard-toolbar d-flex align-items-center justify-content-between">
    <div>
        <h2 class="toolbar-title"><?= htmlspecialchars($pageTitle) ?></h2>
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb mb-0">
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
    <div class="toolbar-actions">
        <?php if (isset($pageAction)) echo $pageAction; ?>
    </div>
</div>
