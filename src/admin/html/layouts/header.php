<?php
/**
 * Admin Header — <head> + Topbar
 * Variables expected: $pageTitle (string)
 */
$pageTitle = $pageTitle ?? 'DASHBOARD';

// Retrieve settings or session data
$adminVars = isset($_SESSION['admin']) ? $_SESSION['admin'] : [];
$adminName = $adminVars['name'] ?? 'HVG Admin';
$adminRole = $adminVars['role'] ?? 'Admin';

// Calculate base URL dynamically to prevent CSS / JS 404s
$baseUrl = '/lms1025edu/admin';
if (isset($_SERVER['SCRIPT_NAME'])) {
    $scriptPath = $_SERVER['SCRIPT_NAME'];
    $adminPos = strpos($scriptPath, '/admin/');
    if ($adminPos !== false) {
        $baseUrl = substr($scriptPath, 0, $adminPos) . '/admin';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <script>
        if (localStorage.getItem('lms_theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — LMS Admin</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $baseUrl ?>/images/favicon.png">

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
    <link href="<?= $baseUrl ?>/css/admin.css?v=<?= time() ?>" rel="stylesheet">
</head>
<body>
<div class="admin-wrapper">
    <!-- header.php only sets up the wrapper. Include sidebar and topbar in your pages! -->
