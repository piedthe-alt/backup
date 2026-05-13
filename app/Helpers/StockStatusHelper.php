<?php

namespace App\Helpers;

class StockStatusHelper
{
    /**
     * Hitung status stock berdasarkan pergerakan barang
     *
     * @param int $stock - Stock saat ini
     * @param int $totalKeluar - Total penjualan selama periode
     * @param string $createdAt - Tanggal produk dibuat
     * @return array - ['status' => string, 'icon' => string, 'color' => string, 'label' => string, 'estimasi' => string]
     */
    public static function getStockStatus($stock, $totalKeluar, $createdAt = null)
    {
        // KONDISI 1: DEAD STOCK
        if ($totalKeluar == 0) {
            return [
                'status' => 'DEAD_STOCK',
                'icon' => 'fa-ban',
                'color' => 'secondary',
                'label' => 'Dead Stock',
                'estimasi' => 'Tidak ada penjualan',
                'textColor' => '#6c757d'
            ];
        }

        // Hitung periode (hari) sejak produk dibuat
        if ($createdAt) {
            $createdDate = is_string($createdAt) ? strtotime($createdAt) : $createdAt->getTimestamp();
            $hari_periode = ceil((time() - $createdDate) / (60 * 60 * 24));
            $hari_periode = max($hari_periode, 1); // Minimal 1 hari
        } else {
            $hari_periode = 30; // Default 30 hari
        }

        // Hitung rata-rata penjualan per hari
        $avg_keluar_per_hari = $totalKeluar / $hari_periode;

        // Hitung estimasi stok habis (dalam hari)
        $estimasi_hari_habis = $avg_keluar_per_hari > 0
            ? round($stock / $avg_keluar_per_hari, 1)
            : 999;

        // KONDISI 2: KRITIS (estimasi <= 3 hari)
        if ($estimasi_hari_habis <= 3) {
            return [
                'status' => 'KRITIS',
                'icon' => 'fa-triangle-exclamation',
                'color' => 'danger',
                'label' => 'Kritis',
                'estimasi' => $estimasi_hari_habis . ' hari',
                'textColor' => '#dc3545'
            ];
        }

        // KONDISI 3: MENIPIS (estimasi <= 7 hari)
        if ($estimasi_hari_habis <= 7) {
            return [
                'status' => 'MENIPIS',
                'icon' => 'fa-exclamation',
                'color' => 'warning',
                'label' => 'Menipis',
                'estimasi' => $estimasi_hari_habis . ' hari',
                'textColor' => '#ff9800'
            ];
        }

        // KONDISI 4: AMAN (estimasi > 7 hari)
        return [
            'status' => 'AMAN',
            'icon' => 'fa-circle-check',
            'color' => 'success',
            'label' => 'Aman',
            'estimasi' => $estimasi_hari_habis . ' hari',
            'textColor' => '#10b981'
        ];
    }

    /**
     * Warna background badge berdasarkan status
     */
    public static function getBadgeBackground($status)
    {
        $colors = [
            'DEAD_STOCK' => 'rgba(108, 117, 125, 0.1)',
            'KRITIS' => 'rgba(220, 53, 69, 0.1)',
            'MENIPIS' => 'rgba(255, 152, 0, 0.1)',
            'AMAN' => 'rgba(16, 185, 129, 0.1)',
        ];

        return $colors[$status] ?? 'rgba(0, 0, 0, 0.05)';
    }
}
