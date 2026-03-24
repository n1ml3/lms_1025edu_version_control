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

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header">
        <h1 class="page-title">Quản Lý Media</h1>
        <p class="page-subtitle">Tải lên và quản lý hình ảnh của hệ thống</p>
    </div>

    <?php if ($success): ?><div class="alert alert-success border-0 rounded-3 mb-4"><?= $success ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert alert-danger  border-0 rounded-3 mb-4"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <!-- Upload Zone -->
    <div class="content-card mb-4">
        <div class="content-card-header"><h3 class="content-card-title">Tải Lên File</h3></div>
        <div class="content-card-body">
            <form method="POST" enctype="multipart/form-data">
                <div id="dropzone" style="border:2px dashed var(--border);border-radius:14px;padding:48px;text-align:center;cursor:pointer;transition:all .2s"
                     onclick="$('#fileInput').click()">
                    <i class='bx bx-cloud-upload' style="font-size:48px;color:var(--primary)"></i>
                    <p class="mt-2 mb-1 fw-semibold">Kéo thả file vào đây hoặc click để chọn</p>
                    <p class="fs-13 text-muted mb-0">PNG, JPG, GIF, WebP — Tối đa 5MB</p>
                </div>
                <input type="file" id="fileInput" name="media" accept="image/*" class="d-none" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Thư Viện Ảnh <span class="badge bg-primary ms-2"><?= count($mediaFiles) ?></span></h3>
        </div>
        <div class="content-card-body">
            <div class="row g-3" id="mediaGrid">
                <?php if ($mediaFiles): foreach ($mediaFiles as $m): ?>
                <div class="col-6 col-md-3 col-xl-2">
                    <div style="position:relative;border-radius:12px;overflow:hidden;border:1px solid var(--border)">
                        <img src="<?= htmlspecialchars($m['path']) ?>" style="width:100%;height:110px;object-fit:cover" onerror="this.style.display='none'">
                        <div style="padding:6px 8px;font-size:11px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($m['filename']) ?></div>
                    </div>
                </div>
                <?php endforeach; else: ?>
                <div class="col-12 text-center py-4 text-muted fs-13">Chưa có file nào được tải lên</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main></div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
