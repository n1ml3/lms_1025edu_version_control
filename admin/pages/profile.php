<?php
/**
 * Profile Page
 */
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

$pageTitle  = 'Hồ sơ cá nhân';
$activePage = 'profile'; 
$breadcrumb = [['label' => 'Hồ sơ', 'url' => '']];

$admin_id = $_SESSION['admin']['id'] ?? 0;
$adminData = null;

try {
    $stmt = $pdo->prepare("SELECT id, name, email, phone, department, position FROM admins WHERE id = ?");
    $stmt->execute([$admin_id]);
    $adminData = $stmt->fetch();
} catch (Exception $e) {
    // handled in view if null
}

require_once __DIR__ . '/../html/pages/profile.php';
