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

-- 3. Seed kategori default (opsional, hapus jika tidak mau)
INSERT INTO `expense_categories` (`name`, `icon`, `color`) VALUES
    ('Listrik',      'bolt',     '#f59e0b'),
    ('Internet',     'wifi',     '#3b82f6'),
    ('Makan',        'utensils', '#10b981'),
    ('Transport',    'car',      '#8b5cf6'),
    ('Air (PDAM)',   'water',    '#06b6d4'),
    ('Lainnya',      'tag',      '#6366f1');

-- ============================================================
-- CEK ID KATEGORI (jalankan dulu untuk tahu id-nya)
-- ============================================================
-- SELECT id, name FROM expense_categories;
--
-- Hasil biasanya:
-- id | name
-- ---+----------
--  1 | Listrik
--  2 | Internet
--  3 | Makan
--  4 | Transport
--  5 | Air (PDAM)
--  6 | Lainnya
-- ============================================================


-- ============================================================
-- 4. INPUT PENGELUARAN MANUAL
-- ============================================================
-- Ganti nilai:
--   category_id  → id dari tabel expense_categories (lihat tabel di atas)
--   amount       → nominal dalam Rupiah (tanpa titik/koma)
--   description  → keterangan bebas (boleh NULL)
--   expense_date → format YYYY-MM-DD
-- ============================================================

INSERT INTO `expenses` (`category_id`, `amount`, `description`, `expense_date`) VALUES

-- CONTOH DATA — edit sesuai kebutuhan Anda:
-- FORMAT: (category_id, amount, 'keterangan', 'YYYY-MM-DD'),

(1, 250000,  'Token listrik bulan Mei',     '2025-05-01'),
(2, 150000,  'Tagihan internet Mei',         '2025-05-05'),
(3, 50000,   'Makan siang karyawan',         '2025-05-07'),
(4, 80000,   'Bensin motor delivery',        '2025-05-08'),
(5, 45000,   'Tagihan air PDAM',             '2025-05-10'),
(1, 250000,  'Token listrik bulan Juni',     '2025-06-01'),
(2, 150000,  'Tagihan internet Juni',        '2025-06-05'),
(6, 30000,   'Beli plastik kresek',          '2025-06-06'),
(3, 75000,   'Makan siang + snack',          '2025-06-10'),
(4, 60000,   'Parkir + bensin',              '2025-06-12');

-- ============================================================
-- TAMBAH BARIS BARU — copy baris di bawah ini lalu edit:
-- ============================================================
-- (category_id, amount, 'keterangan', 'YYYY-MM-DD'),

-- ============================================================
-- CEK HASIL SETELAH INSERT
-- ============================================================
SELECT
    e.id,
    c.name  AS kategori,
    e.amount,
    e.description,
    e.expense_date
FROM expenses e
JOIN expense_categories c ON e.category_id = c.id
ORDER BY e.expense_date DESC;
