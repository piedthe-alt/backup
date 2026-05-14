# 📊 Modern Stock Status Indicator - Dokumentasi

## Overview

Sistem indicator stock yang intelligent dan modern yang menampilkan status stock berdasarkan **pergerakan barang (velocity)** bukan hanya jumlah unit saja.

---

## 🎯 Logika Status Stock

### Rumus Perhitungan

```
1. DEAD STOCK
   ├─ Kondisi: total_keluar = 0
   ├─ Status: "DEAD STOCK"
   ├─ Warna: Abu-abu (#6c757d)
   └─ Icon: fa-ban

2. KRITIS
   ├─ Kondisi: estimasi_hari_habis ≤ 3 hari
   ├─ Status: "KRITIS"
   ├─ Warna: Merah (#dc3545)
   ├─ Icon: fa-triangle-exclamation
   └─ Animasi: Pulse setiap 2 detik (alert)

3. MENIPIS
   ├─ Kondisi: estimasi_hari_habis ≤ 7 hari
   ├─ Status: "MENIPIS"
   ├─ Warna: Orange (#ff9800)
   ├─ Icon: fa-exclamation
   └─ Hover: Smooth shadow effect

4. AMAN
   ├─ Kondisi: estimasi_hari_habis > 7 hari
   ├─ Status: "AMAN"
   ├─ Warna: Hijau (#10b981)
   ├─ Icon: fa-circle-check
   └─ Hover: Smooth shadow effect
```

### Formula Detail

```php
// 1. Hitung periode (hari sejak produk dibuat)
hari_periode = (sekarang - created_at) / 86400
hari_periode = max(hari_periode, 1) // minimal 1 hari

// 2. Hitung rata-rata penjualan per hari
avg_keluar_per_hari = total_keluar / hari_periode

// 3. Hitung estimasi stock habis (dalam hari)
estimasi_hari_habis = stock / avg_keluar_per_hari

// 4. Tentukan status berdasarkan estimasi
if (estimasi_hari_habis <= 3) → KRITIS
else if (estimasi_hari_habis <= 7) → MENIPIS
else → AMAN
```

---

## 🔧 Helper Function

**File:** `app/Helpers/StockStatusHelper.php`

```php
// Static method yang mengembalikan array dengan semua info status
$status = \App\Helpers\StockStatusHelper::getStockStatus(
    $stock,           // int - stock saat ini
    $totalKeluar,     // int - total penjualan
    $createdAt        // string/Carbon - tanggal produk dibuat
);

// Return array:
[
    'status' => 'KRITIS|MENIPIS|AMAN|DEAD_STOCK',
    'icon' => 'fa-triangle-exclamation',
    'color' => 'danger|warning|success|secondary',
    'label' => 'Kritis|Menipis|Aman|Dead Stock',
    'estimasi' => '2.5 hari',
    'textColor' => '#dc3545'
]
```

---

## 📱 Implementasi di Blade

### Contoh 1: Di Halaman Product List (Current)

```blade
@php
    $stockStatus = \App\Helpers\StockStatusHelper::getStockStatus(
        $product->stock,
        $product->total_keluar ?? 0,
        $product->created_at ?? null
    );
@endphp

<div class="stock-info-modern">
    <div class="stock-current">
        <span>Unit: <strong class="stock-current-value">
            {{ number_format($product->stock, 0, ',', '.') }}
        </strong></span>
    </div>
    <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $stockStatus['status'])) }}"
        title="Estimasi habis: {{ $stockStatus['estimasi'] }}">
        <i class="fas {{ $stockStatus['icon'] }}"></i>
        <span>{{ $stockStatus['label'] }}</span>
    </span>
    <span class="stock-estimasi">{{ $stockStatus['estimasi'] }}</span>
</div>
```

### Contoh 2: Di Modal Detail Product

```blade
<div class="modal-body">
    @php
        $stockStatus = \App\Helpers\StockStatusHelper::getStockStatus(
            $product->stock,
            $product->total_keluar ?? 0,
            $product->created_at ?? null
        );
    @endphp
    
    <!-- Stock Status Card -->
    <div style="background: {{ \App\Helpers\StockStatusHelper::getBadgeBackground($stockStatus['status']) }}; 
                padding: 16px; 
                border-radius: 12px; 
                margin-bottom: 16px;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <span style="font-size: 12px; color: #64748b; font-weight: 600;">STOCK STATUS</span>
            <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $stockStatus['status'])) }}">
                <i class="fas {{ $stockStatus['icon'] }}"></i>
                {{ $stockStatus['label'] }}
            </span>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <small style="color: #94a3b8;">Stock Saat Ini</small>
                <div style="font-size: 18px; font-weight: 700; color: #1e293b;">
                    {{ number_format($product->stock, 0, ',', '.') }} unit
                </div>
            </div>
            <div>
                <small style="color: #94a3b8;">Estimasi Habis</small>
                <div style="font-size: 18px; font-weight: 700; color: {{ $stockStatus['textColor'] }};">
                    {{ $stockStatus['estimasi'] }}
                </div>
            </div>
        </div>
    </div>
</div>
```

### Contoh 3: Simple Inline Badge

```blade
@php
    $status = \App\Helpers\StockStatusHelper::getStockStatus(
        $product->stock, 
        $product->total_keluar ?? 0, 
        $product->created_at
    );
@endphp

<span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $status['status'])) }}">
    <i class="fas {{ $status['icon'] }}"></i>
    {{ $status['label'] }}
</span>
```

---

## 🎨 CSS Classes

### Badge Container
```css
.stock-status-badge {
    /* Styling umum untuk semua badge */
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
```

### Status Variants
```css
.status-dead-stock  /* Abu-abu */
.status-kritis      /* Merah dengan pulse animation */
.status-menipis     /* Orange */
.status-aman        /* Hijau */
```

### Info Elements
```css
.stock-info-modern        /* Container untuk stock info */
.stock-current            /* Baris stock unit */
.stock-current-value      /* Value stock (large) */
.stock-estimasi           /* Estimasi habis (small) */
```

---

## 🔄 Customization

### 1. Mengubah Threshold (Batas Hari)

Edit di `app/Helpers/StockStatusHelper.php`:

```php
// Ubah threshold di bagian ini:
if ($estimasi_hari_habis <= 3) {  // ← ubah angka 3
    return ['status' => 'KRITIS', ...];
}

if ($estimasi_hari_habis <= 7) {  // ← ubah angka 7
    return ['status' => 'MENIPIS', ...];
}
```

### 2. Mengubah Warna Badge

Edit CSS di `resources/views/product.blade.php`:

```css
.stock-status-badge.status-kritis {
    background: rgba(220, 53, 69, 0.12);  /* Ubah RGB values */
    color: #dc3545;                        /* Ubah hex color */
    border-color: rgba(220, 53, 69, 0.2);
}
```

### 3. Mengubah Icon

Edit di helper function:

```php
'icon' => 'fa-triangle-exclamation',  // Ganti dengan icon FontAwesome lain
```

### 4. Mengubah Label

```php
'label' => 'Kritis',  // Ganti dengan text yang diinginkan
```

### 5. Menghapus Animasi Pulse

Hapus atau comment kode ini di CSS:

```css
/* @keyframes pulse-kritis { ... } */
/* .stock-status-badge.status-kritis { animation: pulse-kritis 2s infinite; } */
```

---

## 📊 Contoh Output Visual

```
┌─────────────────────────────────────┐
│ STOCK INDICATOR EXAMPLES            │
├─────────────────────────────────────┤
│ Unit: 500                           │
│ ◆ AMAN 10.5 hari                   │  ← Hijau, icon check
│                                     │
├─────────────────────────────────────┤
│ Unit: 45                            │
│ ◆ MENIPIS 5.2 hari                 │  ← Orange, icon warning
│                                     │
├─────────────────────────────────────┤
│ Unit: 8 (pulse animation)           │
│ ◆ KRITIS 1.8 hari                  │  ← Merah, icon danger
│                                     │
├─────────────────────────────────────┤
│ Unit: 320                           │
│ ◆ DEAD STOCK No sales              │  ← Abu-abu, icon ban
└─────────────────────────────────────┘
```

---

## 🚀 Implementation Tips

### Tip 1: Jika Data total_keluar Tidak Tersedia
Modifikasi helper untuk handle null:

```php
$keluar = $totalKeluar ?? 0;
```

### Tip 2: Jika Ingin Real-time Update
Gunakan AJAX untuk polling status:

```javascript
setInterval(async () => {
    const response = await fetch('/api/product-stock/' + productId);
    const status = await response.json();
    // Update badge dengan status baru
}, 30000); // Update setiap 30 detik
```

### Tip 3: Untuk Alert/Notification
Buat notifikasi untuk produk KRITIS:

```blade
@foreach ($products as $product)
    @php
        $status = \App\Helpers\StockStatusHelper::getStockStatus(
            $product->stock, 
            $product->total_keluar ?? 0
        );
    @endphp
    
    @if ($status['status'] === 'KRITIS')
        <!-- Show alert toast -->
        <div class="alert alert-danger">
            {{ $product->name }} sudah {{ $status['label'] }}!
        </div>
    @endif
@endforeach
```

### Tip 4: Export ke Report
Buat report dengan stock status:

```blade
<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Estimasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            @php $status = \App\Helpers\StockStatusHelper::getStockStatus(...); @endphp
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $status['label'] }}</td>
                <td>{{ $status['estimasi'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
```

---

## 📋 File yang Dimodifikasi

1. **app/Helpers/StockStatusHelper.php** - Helper function baru
2. **resources/views/product.blade.php**
   - Added: CSS untuk stock badge modern
   - Modified: Stock indicator section

---

## 🎓 Testing

### Test 1: Dead Stock
```
stock: 100
total_keluar: 0
Expected: DEAD STOCK (Abu-abu)
```

### Test 2: Kritis
```
stock: 5
total_keluar: 100
created_at: 30 hari yang lalu
avg_per_hari: 100/30 = 3.33
estimasi: 5/3.33 = 1.5 hari
Expected: KRITIS (Merah) ✓
```

### Test 3: Menipis
```
stock: 30
total_keluar: 90
created_at: 30 hari yang lalu
avg_per_hari: 90/30 = 3
estimasi: 30/3 = 10 hari
Expected: AMAN (Hijau) ✓
```

---

## 📞 Support

Jika ada masalah atau ingin menambah fitur:
- Semua logika ada di `app/Helpers/StockStatusHelper.php`
- Semua styling ada di `resources/views/product.blade.php` dalam `<style>` tag
- Helper function sudah auto-loaded via Composer PSR-4

---

**Dibuat:** 14 Mei 2026
**Status:** Production Ready
**Compatibility:** Bootstrap 5.3.3, Laravel 12, PHP 8.2+
