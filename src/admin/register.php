<?php
/**
 * Admin Register Page
 */
if (session_status() === PHP_SESSION_NONE) session_start();

// Already logged in → redirect
if (isset($_SESSION['admin_id'])) {
    header('Location: /admin/index.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';

// Fetch allowed roles for registration (exclude Super Admin ID 1)
$roles = [];
try {
    $roles = $pdo->query("SELECT id, name FROM roles WHERE id != 1 ORDER BY id DESC")->fetchAll();
} catch (Exception $e) { $roles = []; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản — LMS Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/admin/images/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            background: #f1f5f9;
        }

        /* Image panel (Right side) */
        .login-image {
            display: none;
            flex: 1;
            background: url('/admin/images/Group-45936.png') center no-repeat;
            background-size: center;
            position: relative;
        }

        @media (min-width: 992px) { .login-image { display: block; } }

        /* Right panel */
        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            width: 100%;
            max-width: 520px;
            background: #fff;
            overflow-y: auto;
        }

        @media (min-width: 992px) { .login-right { max-width: 480px; } }

        .login-box { width: 100%; max-width: 400px; }

        .login-logo {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .login-box h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 6px;
        }

        .login-box .subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 0 0 24px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .input-wrap { position: relative; }

        .input-wrap i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #94a3b8;
            pointer-events: none;
        }

        .input-wrap input, .input-wrap select {
            width: 100%;
            padding: 10px 12px 10px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all .2s;
            outline: none;
            color: #1e293b;
            background: #f8fafc;
        }

        .input-wrap input:focus, .input-wrap select:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all .2s;
            margin-top: 16px;
        }

        .btn-register:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79,70,229,.3);
        }

        .login-footer {
            margin-top: 24px;
            font-size: 13.5px;
            color: #64748b;
            text-align: center;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-right">
        <div class="login-box">
            <div class="login-logo">
                <img src="/admin/images/logo-2.png" alt="LMS Admin Logo" style="max-height: 42px; border-radius: 10px;">
            </div>

            <h2>Tạo tài khoản mới</h2>
            <p class="subtitle">Đăng đăng ký để truy cập trang quản trị.</p>

            <form id="formRegister" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <i class='bx bx-user'></i>
                        <input type="text" name="name" placeholder="VD: Nguyễn Văn A" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <i class='bx bx-envelope'></i>
                        <input type="email" name="email" placeholder="email@example.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <i class='bx bx-shield-quarter'></i>
                        <select name="role_id" required>
                            <option value="">-- Chọn vai trò --</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="password" id="password" placeholder="Nhập mật khẩu của bạn" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <div class="input-wrap">
                        <i class='bx bx-check-shield'></i>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>

                <button type="submit" class="btn-register" id="btnSubmit">
                    Đăng Ký Ngay
                </button>
            </form>

            <div class="login-footer">
                Bạn đã có tài khoản? <a href="/admin/login.php">Đăng nhập ngay</a>
            </div>
        </div>
    </div>

    <!-- Decorative image -->
    <div class="login-image"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $('#formRegister').on('submit', function(e) {
            e.preventDefault();
            const password = $('#password').val();
            const confirm  = $('#confirm_password').val();

            if (password !== confirm) {
                Toastify({ text: "Mật khẩu xác nhận không khớp!", backgroundColor: "#ef4444" }).showToast();
                return;
            }

            const data = {};
            $(this).serializeArray().forEach(item => data[item.name] = item.value);
            
            $('#btnSubmit').prop('disabled', true).text('Đang xử lý...');

            $.ajax({
                url: '/admin/api/register.php',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(res) {
                    if (res.success) {
                        Toastify({ text: "Đăng ký thành công! Đang chuyển hướng...", backgroundColor: "#10b981" }).showToast();
                        setTimeout(() => window.location.href = '/admin/login.php', 1500);
                    } else {
                        Toastify({ text: res.error || "Có lỗi xảy ra!", backgroundColor: "#ef4444" }).showToast();
                        $('#btnSubmit').prop('disabled', false).text('Đăng Ký Ngay');
                    }
                },
                error: function() {
                    Toastify({ text: "Lỗi kết nối máy chủ!", backgroundColor: "#ef4444" }).showToast();
                    $('#btnSubmit').prop('disabled', false).text('Đăng Ký Ngay');
                }
            });
        });
    </script>
</body>
</html>
