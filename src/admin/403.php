<?php
/**
 * Forbidden Page
 */
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Không Có Quyền Truy Cập</title>
    <link rel="icon" type="image/png" href="/admin/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #1e293b;
        }
        .container {
            text-align: center;
            max-width: 500px;
            padding: 40px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        .icon {
            font-size: 80px;
            color: #ef4444;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 12px;
        }
        p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn-back {
            display: inline-block;
            padding: 12px 24px;
            background: #4f46e5;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all .2s;
        }
        .btn-back:hover {
            background: #3730a3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon"><i class='bx bx-shield-x'></i></div>
        <h1>Truy cập bị từ chối</h1>
        <p>Rất tiếc, bạn không có quyền truy cập vào chức năng hoặc trang này. Vui lòng liên hệ quản trị viên cấp cao nếu bạn tin rằng đây là một sự nhầm lẫn.</p>
        <a href="/admin/index.php" class="btn-back">Quay lại Trang chủ</a>
    </div>
</body>
</html>
