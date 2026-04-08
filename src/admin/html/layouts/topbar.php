<?php
/**
 * Admin Topbar & Dashboard Toolbar
 * Included inside the .main-area wrapper
 */

// Fetch latest notifications
$notifications = [];
$unreadCount = 0;
if (isset($pdo)) {
    try {
        // Fetch latest 5 for display
        $notifStmt = $pdo->prepare("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
        $notifStmt->execute();
        $notifications = $notifStmt->fetchAll();

        // Count only unread for the badge
        $unreadCount = (int)$pdo->query("SELECT COUNT(*) FROM notifications WHERE is_read = 0")->fetchColumn();
    } catch (Exception $e) {
        // Silently skip if query fails
    }
}
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
                <?php if ($unreadCount > 0): ?>
                    <span class="notif-badge"><?= $unreadCount ?></span>
                <?php endif; ?>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-sm notif-dropdown">
                <div class="notif-header">
                    <h6 class="mb-0 fw-bold">Thông báo của tôi</h6>
                    <button class="btn-close-notif" data-bs-toggle="dropdown"><i class='bx bx-x'></i></button>
                </div>
                <div class="notif-actions">
                    <a href="javascript:void(0)" class="notif-mark-read" onclick="markAllNotificationsRead()">Đánh dấu tất cả là đã đọc</a>
                </div>
                <div class="notif-body">
                    <?php if (empty($notifications)): ?>
                        <div class="p-4 text-center text-muted">
                            <i class='bx bx-bell-off d-block fs-2 mb-2'></i>
                            <p class="mb-0 small">Không có thông báo nào</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $n): ?>
                        <div class="notif-item <?= $n['is_read'] ? '' : 'unread' ?>" id="notif-<?= $n['id'] ?>">
                            <div class="notif-icon">
                                <img src="/admin/images/logo-2.png" alt="Icon">
                            </div>
                            <div class="notif-content">
                                <a href="javascript:void(0)" class="notif-title"><?= htmlspecialchars($n['title']) ?></a>
                                <p class="notif-desc"><?= htmlspecialchars($n['content']) ?></p>
                                <div class="notif-meta">
                                    <span class="notif-time"><?= date('H:i d/m', strtotime($n['created_at'])) ?></span>
                                    <?php if (!$n['is_read']): ?>
                                        <span class="notif-sep">|</span>
                                        <a href="javascript:void(0)" class="notif-action-link" onclick="markNotifRead(<?= $n['id'] ?>)">Đánh dấu đã đọc</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="notif-footer">
                    <a href="#">Xem tất cả</a>
                </div>
            </div>
        </div>
        <div class="dropdown">
            <button class="topbar-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; border: none; padding: 0;">
                <img src="/admin/images/person.png" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                <div class="d-none d-sm-block text-start ms-2">
                    <span class="d-block fw-semibold" style="font-size: 13px; color: var(--text-dark); line-height: 1.2;"><?= htmlspecialchars($adminName) ?></span>
                    <span class="d-block text-muted" style="font-size: 11px;"><?= htmlspecialchars($adminRole) ?></span>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="/admin/pages/profile.php"><i class='bx bx-user me-2'></i>Hồ sơ</a></li>
                <li><a class="dropdown-item" href="#"><i class='bx bx-cog me-2'></i>Cài đặt</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/admin/logout.php"><i class='bx bx-log-out me-2'></i>Đăng xuất</a></li>
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

<script>
/**
 * Mark a single notification as read
 */
async function markNotifRead(id) {
    try {
        const response = await fetch('/admin/api/notifications.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'mark_read', id: id })
        });
        const result = await response.json();
        if (result.success) {
            // UI Update: remove unread class and the action link
            const item = document.getElementById(`notif-${id}`);
            if (item) {
                item.classList.remove('unread');
                const actionLink = item.querySelector('.notif-action-link');
                const sep = item.querySelector('.notif-sep');
                if (actionLink) actionLink.remove();
                if (sep) sep.remove();
            }
            // Update badge count
            updateNotifBadge(-1);
        }
    } catch (err) {
        console.error('Failed to mark notification as read:', err);
    }
}

/**
 * Mark all notifications as read
 */
async function markAllNotificationsRead() {
    try {
        const response = await fetch('/admin/api/notifications.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'mark_all_read' })
        });
        const result = await response.json();
        if (result.success) {
            // UI Update: remove unread class from all items
            document.querySelectorAll('.notif-item.unread').forEach(item => {
                item.classList.remove('unread');
                const actionLink = item.querySelector('.notif-action-link');
                const sep = item.querySelector('.notif-sep');
                if (actionLink) actionLink.remove();
                if (sep) sep.remove();
            });
            // Update badge
            const badge = document.querySelector('.notif-badge');
            if (badge) badge.remove();
        }
    } catch (err) {
        console.error('Failed to mark all notifications as read:', err);
    }
}

/**
 * Helper to update badge count safely
 */
function updateNotifBadge(delta) {
    const badge = document.querySelector('.notif-badge');
    if (!badge) return;
    
    let currentCount = parseInt(badge.textContent) || 0;
    let newCount = currentCount + delta;
    
    if (newCount <= 0) {
        badge.remove();
    } else {
        badge.textContent = newCount;
    }
}
</script>
