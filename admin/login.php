<?php
/**
 * Admin Login Page
 */
if (session_status() === PHP_SESSION_NONE) session_start();

// Already logged in → redirect
if (isset($_SESSION['admin_id'])) {
    header('Location: /lms1025edu/admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/db.php';

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT id, name, email, password_hash, role_id FROM admins WHERE email = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_role'] = $admin['role_id'];
            header('Location: /lms1025edu/admin/index.php');
            exit;
        }
    }
    $error = 'Email hoặc mật khẩu không đúng.';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập — LMS Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

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

        /* Left panel */
        .login-left {
            display: none;
            flex: 1;
            background: linear-gradient(145deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 992px) { .login-left { display: flex; align-items: center; justify-content: center; } }

        .login-left-content {
            text-align: center;
            color: #fff;
            padding: 40px;
            position: relative;
            z-index: 2;
        }

        .login-left-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,.18);
            border-radius: 24px;
            margin: 0 auto 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
        }

        .login-left h1 { font-size: 28px; font-weight: 700; margin: 0 0 10px; }
        .login-left p  { font-size: 15px; opacity: .75; margin: 0; max-width: 280px; }

        /* Decorative circles */
        .circle-deco {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }

        .circle-deco.c1 { width: 300px; height: 300px; top: -80px; right: -80px; }
        .circle-deco.c2 { width: 200px; height: 200px; bottom: -60px; left: -60px; }

        /* Right panel */
        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            width: 100%;
            max-width: 480px;
            background: #fff;
        }

        @media (min-width: 992px) { .login-right { max-width: 420px; } }

        .login-box { width: 100%; max-width: 360px; }

        .login-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }

        .login-logo-icon {
            width: 44px;
            height: 44px;
            background: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 26px;
        }

        .login-logo-text span:first-child {
            display: block;
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
        }

        .login-logo-text span:last-child {
            display: block;
            font-size: 12px;
            color: #64748b;
        }

        .login-box h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 6px;
        }

        .login-box .subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 0 0 28px;
        }

        .form-label {
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .input-wrap { position: relative; }

        .input-wrap i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 19px;
            color: #94a3b8;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 11px 12px 11px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all .2s;
            outline: none;
            color: #1e293b;
            background: #f8fafc;
        }

        .input-wrap input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(79,70,229,.12);
        }

        .input-wrap .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 19px;
            color: #94a3b8;
            padding: 0;
        }

        .error-alert {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13.5px;
            color: #b91c1c;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-login {
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
            margin-top: 8px;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(79,70,229,.35);
        }

        .login-footer {
            margin-top: 24px;
            font-size: 12.5px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Left decorative panel -->
    <div class="login-left">
        <div class="circle-deco c1"></div>
        <div class="circle-deco c2"></div>
        <div class="login-left-content">
            <div class="login-left-icon"><i class='bx bx-graduation'></i></div>
            <h1>LMS Admin Panel</h1>
            <p>Hệ thống quản lý đào tạo toàn diện — Quản lý học viên, khóa học, giảng viên và doanh thu.</p>
        </div>
    </div>

    <!-- Right login panel -->
    <div class="login-right">
        <div class="login-box">
            <div class="login-logo">
                <div class="login-logo-icon"><i class='bx bx-graduation'></i></div>
                <div class="login-logo-text">
                    <span>LMS Admin</span>
                    <span>Education Platform</span>
                </div>
            </div>

            <h2>Chào mừng trở lại!</h2>
            <p class="subtitle">Đăng nhập để quản lý hệ thống.</p>

            <?php if ($error): ?>
            <div class="error-alert">
                <i class='bx bx-error-circle'></i>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="mb-4">
                    <label class="form-label">Email</label>
                    <div class="input-wrap">
                        <i class='bx bx-envelope'></i>
                        <input type="email" name="email" id="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="admin@example.com"
                               required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Mật khẩu</label>
                    <div class="input-wrap">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="password" id="password"
                               placeholder="••••••••"
                               required>
                        <button type="button" class="toggle-pw" id="togglePw" title="Hiện/ẩn mật khẩu">
                            <i class='bx bx-show' id="pwIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Đăng Nhập
                </button>
            </form>

            <div class="login-footer">
                &copy; <?= date('Y') ?> LMS Admin · All rights reserved
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePw').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon  = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bx bx-hide';
            } else {
                input.type = 'password';
                icon.className = 'bx bx-show';
            }
        });
    </script>
</body>
</html>
