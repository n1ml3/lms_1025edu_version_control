# LMS Admin Panel — Kế Hoạch Triển Khai

## Mô Tả

Xây dựng giao diện Admin cho hệ thống LMS (Learning Management System) từ đầu. Tech stack: **HTML5 + Bootstrap 5 + Boxicons + jQuery** (frontend), **PHP 8 + MySQL** (backend), chạy trên **XAMPP**.

Thiết kế hiện đại hơn bản gốc với: Dark sidebar gradient, card glassmorphism nhẹ, animation hover, màu sắc tinh tế hơn, typography rõ ràng.

---

## Design System

### Color Palette
| Token | Giá trị | Dùng cho |
|---|---|---|
| `--primary` | `#4f46e5` (Indigo) | Sidebar, buttons, active states |
| `--primary-dark` | `#3730a3` | Sidebar gradient end |
| `--accent` | `#06b6d4` (Cyan) | Icon badges, highlights |
| `--success` | `#10b981` | Trạng thái tốt, revenue |
| `--warning` | `#f59e0b` | Cảnh báo nhẹ |
| `--danger` | `#ef4444` | Lỗi, xóa |
| `--bg-main` | `#f1f5f9` | Nền trang chính |
| `--text-dark` | `#1e293b` | Tiêu đề |
| `--text-muted` | `#64748b` | Label phụ |

### Typography
- Font: **Inter** (Google Fonts)
- Heading: 600–700 weight
- Body: 400, 14–15px

### UI Patterns
- Sidebar: gradient `#4f46e5 → #3730a3`, icon + label, collapsible
- Cards: `border-radius: 16px`, shadow nhẹ, hover lift
- Topbar: trắng, shadow bottom, avatar + notification bell
- Tables: striped nhẹ, action buttons nhỏ gọn
- Breadcrumbs: dạng `/` phân tách

---

## Cấu Trúc Thư Mục

```
lms1025edu/
├── admin/
│   ├── index.php                  # Dashboard
│   ├── login.php
│   │
│   ├── pages/
│   │   ├── crm/
│   │   │   ├── leads.php
│   │   │   └── appointments.php
│   │   ├── members/
│   │   │   ├── list.php
│   │   │   └── roles.php
│   │   ├── notifications/
│   │   │   ├── general.php
│   │   │   └── staff.php
│   │   ├── courses/
│   │   │   ├── programs.php
│   │   │   ├── classes.php
│   │   │   └── quiz.php
│   │   ├── products/
│   │   │   ├── list.php
│   │   │   └── add.php
│   │   ├── instructors/
│   │   │   ├── teachers.php
│   │   │   ├── agents.php
│   │   │   └── data-sources.php
│   │   ├── promotions/
│   │   │   └── coupons.php
│   │   └── settings/
│   │       ├── media.php
│   │       └── storage.php
│   │
│   ├── includes/
│   │   ├── header.php             # <head> + topbar HTML
│   │   ├── sidebar.php            # Sidebar navigation
│   │   ├── footer.php             # Scripts + closing tags
│   │   └── auth_check.php        # Session guard
│   │
│   ├── assets/
│   │   ├── css/
│   │   │   ├── admin.css          # Custom styles
│   │   │   └── sidebar.css
│   │   ├── js/
│   │   │   ├── admin.js           # Global jQuery logic
│   │   │   └── dashboard.js      # Dashboard charts
│   │   └── img/
│   │       └── logo.svg
│   │
│   └── api/                       # PHP JSON APIs (AJAX)
│       ├── dashboard_stats.php
│       ├── leads.php
│       ├── members.php
│       ├── courses.php
│       └── ...
│
├── config/
│   └── db.php                     # PDO connection
│
└── public/
    └── (frontend học viên - sau này)
```

---

## Các Module & Trang

### 1. Login (`login.php`)
- Form đăng nhập, kiểm tra session
- Redirect sau login → dashboard

### 2. Dashboard (`index.php`)
**Stat cards (4 cards hàng đầu):**
- Cơ sở, Nguồn, Khóa học, Giảng viên

**Bộ lọc thống kê:**
- Dropdown: Cơ sở, Nguồn, Nhân viên
- Datepicker: Từ ngày – Đến ngày
- Button Lọc → AJAX gọi `api/dashboard_stats.php`

**Mini Stats (6 cards):**
- Lead liên hệ, Khách hàng, Lịch hẹn, Đơn hàng, Dự thu, Đã thu

**Lịch tabs:**
- Lịch trực tuần | Lịch hẹn | Lịch học trực tiếp

**Chart:**
- Line/Bar chart doanh thu theo tuần (Chart.js)

### 3. CRM (leads.php)
- Bảng danh sách Lead, phân trang
- Filter: trạng thái, nguồn, ngày
- Modal thêm/sửa/xóa Lead

### 4. Thành Viên Quản Trị (`members/list.php`)
- Danh sách admin/staff
- Gán quyền (roles)

### 5. Danh Sách Quyền (`members/roles.php`)
- Quản lý permission matrix

### 6. Chương Trình Học (`courses/programs.php`)
- Tree/accordion chương trình → môn → bài

### 7. Lớp Học (`courses/classes.php`)
- Danh sách lớp, giảng viên, lịch, học viên

### 8. Bài Test (`courses/quiz.php`)
- CRUD câu hỏi & đề thi

### 9. Sản Phẩm (`products/list.php`)
- Grid/Table sản phẩm, thêm mới

### 10. Giảng Viên · Đại Lý
- `teachers.php`, `agents.php`, `data-sources.php`

### 11. Mã Khuyến Mãi (`promotions/coupons.php`)
- Tạo/quản lý coupon, % hoặc cố định

### 12. Setting
- Upload/quản lý hình ảnh
- Kho lưu trữ file

---

## Database Schema (MySQL)

```sql
-- Các bảng chính
admins          (id, name, email, password_hash, role_id, created_at)
roles           (id, name, permissions JSON)
branches        (id, name, address)
lead_sources    (id, name)
leads           (id, name, phone, email, source_id, status, branch_id, staff_id, created_at)
appointments    (id, lead_id, datetime, note, status)
courses         (id, name, branch_id, price, created_at)
programs        (id, course_id, name, order)
classes         (id, program_id, teacher_id, schedule JSON, max_students)
students        (id, name, phone, email, class_id, enrolled_at)
teachers        (id, name, phone, email, bio)
agents          (id, name, phone, commission_rate)
products        (id, name, description, price, image, stock)
orders          (id, student_id, product_id, amount, status, created_at)
coupons         (id, code, type, value, expires_at, usage_limit, used_count)
notifications   (id, title, content, type, created_at)
media           (id, filename, path, size, uploaded_at)
```

---

## Proposed Changes

### Frontend (HTML/CSS/JS)

#### [NEW] `admin/assets/css/admin.css`
CSS Variables, sidebar styles, card components, topbar, utility classes

#### [NEW] `admin/includes/sidebar.php`
Sidebar HTML với: logo, nav groups, active state, collapse toggle

#### [NEW] `admin/includes/header.php`
`<head>` meta + CDN links (Bootstrap 5.3, Boxicons 2.1, Inter font, Chart.js)

#### [NEW] `admin/includes/footer.php`
jQuery CDN + `admin.js` + closing tags

#### [NEW] `admin/index.php` — Dashboard page

#### [NEW] `admin/login.php` — Login page

#### [NEW] `admin/pages/**/*.php` — Tất cả các trang module

### Backend (PHP)

#### [NEW] `config/db.php`
```php
$pdo = new PDO("mysql:host=localhost;dbname=lms_db;charset=utf8mb4", "root", "");
```

#### [NEW] `admin/includes/auth_check.php`
```php
session_start();
if (!isset($_SESSION['admin_id'])) header('Location: /admin/login.php');
```

#### [NEW] `admin/api/dashboard_stats.php`
Trả JSON stats theo filter (branch, source, date range)

---

## Verification Plan

### Bước 1: Mở Browser thủ công
```
http://localhost/lms1025edu/admin/login.php
```
Kiểm tra:
- [ ] Giao diện login hiển thị đúng, có form email/password
- [ ] Đăng nhập với tài khoản admin → redirect sang dashboard

### Bước 2: Dashboard
```
http://localhost/lms1025edu/admin/index.php
```
Kiểm tra:
- [ ] Sidebar hiển thị đủ menu theo thiết kế
- [ ] 4 stat cards đầu trang hiển thị số liệu
- [ ] Bộ lọc hoạt động (click Lọc → AJAX cập nhật stats)
- [ ] Chart hiển thị đúng

### Bước 3: Responsive
- Resize browser xuống 768px → sidebar collapse tự động

### Bước 4: Các trang module
Mở từng link menu và kiểm tra:
- [ ] Layout đồng nhất (sidebar + topbar + breadcrumb)
- [ ] Bảng dữ liệu có phân trang
- [ ] Nút thêm/sửa/xóa hoạt động (modal AJAX)
