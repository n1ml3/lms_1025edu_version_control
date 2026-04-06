<?php
/**
 * Logout
 */
if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION = [];
session_destroy();
header('Location: /lms1025edu/admin/login.php');
exit;
