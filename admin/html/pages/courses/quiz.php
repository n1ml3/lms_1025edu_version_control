require_once __DIR__ . '/../../html/layouts/header.php';
require_once __DIR__ . '/../../html/layouts/sidebar.php';
?>
<div class="main-area"><main class="page-content">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Bài Kiểm Tra</h1>
            <p class="page-subtitle">Quản lý câu hỏi và đề thi</p>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalQuiz">
            <i class='bx bx-plus'></i> Thêm Câu Hỏi
        </button>
    </div>
    <div class="content-card content-card-body text-center py-5 text-muted">
        <i class='bx bx-question-mark d-block mb-2' style="font-size:48px;color:var(--primary)"></i>
        <p class="mb-0 fw-semibold">Chức năng Quiz đang phát triển</p>
        <p class="fs-13">CRUD câu hỏi & đề thi sẽ được cập nhật sớm.</p>
    </div>
</main></div>
<div class="modal fade" id="modalQuiz" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Thêm Câu Hỏi</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Câu hỏi</label><textarea class="form-control" rows="3" placeholder="Nhập câu hỏi..."></textarea></div>
            <div class="mb-3"><label class="form-label">Loại câu hỏi</label><select class="form-select"><option>Trắc nghiệm</option><option>Đúng/Sai</option><option>Điền vào chỗ trống</option></select></div>
            <div class="row g-2" id="answerOptions">
                <div class="col-md-6"><div class="input-group"><div class="input-group-text"><input type="radio" name="correct_ans"></div><input type="text" class="form-control" placeholder="Đáp án A"></div></div>
                <div class="col-md-6"><div class="input-group"><div class="input-group-text"><input type="radio" name="correct_ans"></div><input type="text" class="form-control" placeholder="Đáp án B"></div></div>
                <div class="col-md-6"><div class="input-group"><div class="input-group-text"><input type="radio" name="correct_ans"></div><input type="text" class="form-control" placeholder="Đáp án C"></div></div>
                <div class="col-md-6"><div class="input-group"><div class="input-group-text"><input type="radio" name="correct_ans"></div><input type="text" class="form-control" placeholder="Đáp án D"></div></div>
            </div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button><button class="btn-primary-custom">Lưu</button></div>
    </div></div>
</div>
<?php require_once __DIR__ . '/../../html/layouts/footer.php'; ?>
