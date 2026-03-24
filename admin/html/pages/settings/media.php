<?php
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$pageAction = <<<HTML
<button class="btn-primary-custom" onclick="$('#fileInput').click()">
    <i class='bx bx-upload'></i> Tải Lên Media
</button>
HTML;
?>
<div class="main-area">
    <?php require_once __DIR__ . '/../../layouts/topbar.php'; ?>
    <main class="page-content">
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
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
