<?php
/**
 * Auth Guard — Session Check
 * Include this at the top of every protected admin page.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    $loginUrl = '/admin/login.php';
    header('Location: ' . $loginUrl);
    exit;
}

// Convenience variables available to all pages
$adminId     = $_SESSION['admin_id'];
$adminName   = $_SESSION['admin_name'] ?? 'Admin';
$adminRoleId = $_SESSION['admin_role'] ?? 0;

/**
 * Lazy load permissions into session if not exists
 */
if (!isset($_SESSION['permissions'])) {
    require_once __DIR__ . '/../../config/db.php';
    $stmt = $pdo->prepare("SELECT permissions FROM roles WHERE id = ?");
    $stmt->execute([$adminRoleId]);
    $role = $stmt->fetch();
    if ($role) {
        $_SESSION['permissions'] = json_decode($role['permissions'] ?: '[]', true);
    } else {
        $_SESSION['permissions'] = [];
    }
}

/**
 * Check if current user has a specific permission node
 */
function hasPermission($node) {
    if ($_SESSION['admin_role'] == 1) return true; // Super Admin bypass
    $perms = $_SESSION['permissions'] ?? [];
    return in_array($node, $perms);
}

/**
 * Guard page/action with permission check
 */
function guardPermission($node) {
    if (!hasPermission($node)) {
        header('Location: /admin/403.php');
        exit;
    }
}
