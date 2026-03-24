<?php
/**
 * Admin Header — <head> + Topbar
 * Variables expected: $pageTitle (string)
 */
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — LMS Admin</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Boxicons 2.1 -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" defer></script>

    <!-- Admin CSS -->
    <link href="/lms1025edu/admin/assets/css/admin.css" rel="stylesheet">
</head>
<body class="sidebar-collapsed">
<div class="admin-wrapper">
    <!-- Topbar -->
    <header class="topbar" id="topbar">
        <div class="topbar-left">
            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/lms1025edu/admin/index.php">Dashboard</a></li>
                    <?php if (!empty($breadcrumb)): ?>
                        <?php foreach ($breadcrumb as $b): ?>
                            <?php if (!empty($b['url'])): ?>
                                <li class="breadcrumb-item"><a href="<?= $b['url'] ?>"><?= htmlspecialchars($b['label']) ?></a></li>
                            <?php else: ?>
                                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($b['label']) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
        <div class="topbar-right">
            <button class="topbar-icon-btn" title="Thông báo">
                <i class='bx bx-bell'></i>
                <span class="notif-badge">3</span>
            </button>
            <div class="dropdown">
                <button class="topbar-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar-circle"><?= strtoupper(substr($adminName, 0, 1)) ?></div>
                    <span class="d-none d-sm-inline"><?= htmlspecialchars($adminName) ?></span>
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
