-- =====================================================
-- LMS Admin Panel — Database Schema
-- Engine: MySQL 8 | Charset: utf8mb4
-- =====================================================

CREATE DATABASE IF NOT EXISTS lms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lms_db;

-- ── Roles & Permissions ──────────────────────────────
CREATE TABLE roles (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(80) NOT NULL,
    permissions JSON,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO roles (name, permissions) VALUES
('Super Admin', '["dashboard","crm","members","courses","products","instructors","promotions","settings"]'),
('Manager',     '["dashboard","crm","courses","products"]'),
('Staff',       '["dashboard","crm"]');

-- ── Admins ───────────────────────────────────────────
CREATE TABLE admins (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120) NOT NULL,
    email         VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role_id       INT DEFAULT 1,
    is_active     TINYINT(1) DEFAULT 1,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin password: Admin@1234  (bcrypt)
INSERT INTO admins (name, email, password_hash, role_id) VALUES
('Super Admin', 'admin@lms.vn', '$2y$12$LrUkQ5R0Bq4SkUsE1vMiouWuRBwb12eOXWgWLH4wZh0Sb0rGKcNey', 1);

-- ── Branches ─────────────────────────────────────────
CREATE TABLE branches (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(120) NOT NULL,
    address    TEXT,
    phone      VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO branches (name, address) VALUES
('Cơ sở Hà Nội', '123 Đường ABC, Quận 1, Hà Nội'),
('Cơ sở TP.HCM', '456 Đường XYZ, Quận 3, TP.HCM'),
('Cơ sở Đà Nẵng', '789 Đường LMN, Quận Hải Châu, Đà Nẵng');

-- ── Lead Sources ─────────────────────────────────────
CREATE TABLE lead_sources (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO lead_sources (name) VALUES ('Facebook'),('Google'),('Zalo'),('Giới thiệu'),('Website'),('TikTok'),('Instagram'),('Khác');

-- ── Leads ────────────────────────────────────────────
CREATE TABLE leads (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(120) NOT NULL,
    phone      VARCHAR(20),
    email      VARCHAR(180),
    source_id  INT,
    branch_id  INT,
    staff_id   INT,
    status     ENUM('new','contacted','converted','lost') DEFAULT 'new',
    note       TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (source_id) REFERENCES lead_sources(id) ON DELETE SET NULL,
    FOREIGN KEY (branch_id) REFERENCES branches(id)     ON DELETE SET NULL,
    FOREIGN KEY (staff_id)  REFERENCES admins(id)       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Appointments ─────────────────────────────────────
CREATE TABLE appointments (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    lead_id  INT,
    datetime DATETIME NOT NULL,
    note     TEXT,
    status   ENUM('pending','done','cancelled') DEFAULT 'pending',
    FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Courses ──────────────────────────────────────────
CREATE TABLE courses (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(180) NOT NULL,
    branch_id  INT,
    price      DECIMAL(12,2) DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Programs ─────────────────────────────────────────
CREATE TABLE programs (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    name      VARCHAR(180) NOT NULL,
    `order`   INT DEFAULT 0,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Teachers ─────────────────────────────────────────
CREATE TABLE teachers (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(120) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(180),
    bio   TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Classes ──────────────────────────────────────────
CREATE TABLE classes (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    program_id   INT,
    teacher_id   INT,
    schedule     JSON,
    max_students INT DEFAULT 30,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Students ─────────────────────────────────────────
CREATE TABLE students (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(120) NOT NULL,
    phone       VARCHAR(20),
    email       VARCHAR(180),
    class_id    INT,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Agents ───────────────────────────────────────────
CREATE TABLE agents (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(120) NOT NULL,
    phone           VARCHAR(20),
    commission_rate DECIMAL(5,2) DEFAULT 10.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Products ─────────────────────────────────────────
CREATE TABLE products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(180) NOT NULL,
    description TEXT,
    price       DECIMAL(12,2) DEFAULT 0,
    image       VARCHAR(255),
    stock       INT DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Orders ───────────────────────────────────────────
CREATE TABLE orders (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    product_id INT,
    branch_id  INT,
    amount     DECIMAL(12,2) DEFAULT 0,
    status     ENUM('pending','paid','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    FOREIGN KEY (branch_id)  REFERENCES branches(id)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Coupons ──────────────────────────────────────────
CREATE TABLE coupons (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    code        VARCHAR(30) NOT NULL UNIQUE,
    type        ENUM('percent','fixed') DEFAULT 'percent',
    value       DECIMAL(10,2) DEFAULT 0,
    expires_at  DATE,
    usage_limit INT,
    used_count  INT DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Notifications ─────────────────────────────────────
CREATE TABLE notifications (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(255) NOT NULL,
    content    TEXT,
    type       ENUM('general','staff') DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Media ────────────────────────────────────────────
CREATE TABLE media (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    filename    VARCHAR(255) NOT NULL,
    path        VARCHAR(500) NOT NULL,
    size        BIGINT DEFAULT 0,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
