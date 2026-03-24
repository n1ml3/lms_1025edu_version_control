<?php
/**
 * Settings — Media Upload
 */
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../../config/db.php';

$pageTitle  = 'Quản Lý Media';
$activePage = 'settings_media';
$breadcrumb = [['label'=>'Cài Đặt'],['label'=>'Media']];

$success = $error = '';
$uploadDir = __DIR__ . '/../../assets/img/uploads/';
@mkdir($uploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media'])) {
    $file = $_FILES['media'];
    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    if (!in_array($file['type'], $allowed)) {
        $error = 'Chỉ chấp nhận file hình ảnh (JPG, PNG, GIF, WebP).';
    } elseif ($file['size'] > 5 * 1024 * 1024) {
        $error = 'File không được vượt quá 5MB.';
    } else {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('media_') . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO media (filename, path, size, uploaded_at) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$filename, '/lms1025edu/admin/assets/img/uploads/' . $filename, $file['size']]);
            } catch (Exception $e) {}
            $success = 'Đã tải lên thành công: ' . htmlspecialchars($filename);
        } else {
            $error = 'Không thể lưu file. Kiểm tra quyền thư mục.';
        }
    }
}

try {
    $mediaFiles = $pdo->query("SELECT * FROM media ORDER BY uploaded_at DESC LIMIT 48")->fetchAll();
} catch (Exception $e) { $mediaFiles = []; }

require_once __DIR__ . '/../../html/pages/settings/media.php';
