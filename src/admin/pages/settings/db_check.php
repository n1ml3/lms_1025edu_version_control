<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$activePage = 'settings_dbcheck';

// DB info
$status = 'Connected';
$dbInfo = [];
$errorMsg = '';
try {
    $stmt = $pdo->query("SELECT VERSION() as v");
    $dbInfo['version'] = $stmt->fetchColumn();
    $stmt = $pdo->query("SELECT DATABASE() as d");
    $dbInfo['database'] = $stmt->fetchColumn();
    $stmt = $pdo->query("SELECT USER() as u");
    $dbInfo['user'] = $stmt->fetchColumn();
    
    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $dbInfo['tables'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

} catch (Exception $e) {
    $status = 'Error';
    $errorMsg = $e->getMessage();
}

require_once __DIR__ . '/../../html/pages/settings/db_check.php';
