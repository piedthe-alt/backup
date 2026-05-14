-- SQL untuk membuat tabel pesanan_shopee
CREATE TABLE IF NOT EXISTS pesanan_shopee (
    id_pesanan BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_produk JSON NOT NULL COMMENT 'Array of product IDs',
    jumlah_produk JSON NOT NULL COMMENT 'Array of product quantities',
    status VARCHAR(255) NOT NULL DEFAULT 'BELUM_DIKIRIM' COMMENT 'BELUM_DIKIRIM or DIKIRIM',
    jenis ENUM('Instant', 'SPX', 'JNE', 'JNT') NOT NULL DEFAULT 'Instant',
    total_harga_jual DECIMAL(15, 2) NOT NULL,
    nama_pembeli VARCHAR(255) NULL,
    alamat TEXT NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
