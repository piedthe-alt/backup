-- ============================================================
-- PENGELUARAN TOKO — Setup Database
-- Jalankan di phpMyAdmin pada database: u990824557_db_app
-- ============================================================

-- 1. Tabel Kategori Pengeluaran
CREATE TABLE IF NOT EXISTS `expense_categories` (
    `id`         INT AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(100) NOT NULL COMMENT 'Nama kategori, misal: Listrik, Makan, Internet',
    `icon`       VARCHAR(50)  NOT NULL DEFAULT 'tag'     COMMENT 'Font Awesome icon name, tanpa prefix fa-',
    `color`      VARCHAR(20)  NOT NULL DEFAULT '#6366f1' COMMENT 'Hex color untuk badge & bar chart',
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Transaksi Pengeluaran
CREATE TABLE IF NOT EXISTS `expenses` (
    `id`           INT AUTO_INCREMENT PRIMARY KEY,
    `category_id`  INT             NOT NULL,
    `amount`       DECIMAL(15,2)   NOT NULL              COMMENT 'Nominal pengeluaran dalam Rupiah',
    `description`  TEXT                NULL              COMMENT 'Keterangan opsional',
    `expense_date` DATE            NOT NULL              COMMENT 'Tanggal pengeluaran terjadi',
    `created_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_expenses_category`
        FOREIGN KEY (`category_id`)
        REFERENCES `expense_categories` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    INDEX `idx_expense_date` (`expense_date`),
    INDEX `idx_category_id`  (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Seed kategori default (opsional)
INSERT INTO `expense_categories` (`name`, `icon`, `color`) VALUES
    ('Listrik',      'bolt',     '#f59e0b'),
    ('Internet',     'wifi',     '#3b82f6'),
    ('Makan',        'utensils', '#10b981'),
    ('Transport',    'car',      '#8b5cf6'),
    ('Air (PDAM)',   'water',    '#06b6d4'),
    ('Lainnya',      'tag',      '#6366f1');
