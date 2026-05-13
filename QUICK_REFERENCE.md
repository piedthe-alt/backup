# 📌 Stock Indicator - Quick Reference Card

## 🚀 30-Detik Setup

```blade
@php
    $status = \App\Helpers\StockStatusHelper::getStockStatus(
        $product->stock,
        $product->total_keluar ?? 0,
        $product->created_at ?? null
    );
@endphp

<span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $status['status'])) }}">
    <i class="fas {{ $status['icon'] }}"></i>
    {{ $status['label'] }}
</span>
```

---

## 📊 Status Reference

| Status | Kondisi | Warna | Icon | Animasi |
|--------|---------|-------|------|---------|
| **DEAD STOCK** | Penjualan = 0 | 🩶 Abu-abu | ban | - |
| **KRITIS** | ≤ 3 hari | 🔴 Merah | triangle-exclamation | Pulse ⚡ |
| **MENIPIS** | 3-7 hari | 🟠 Orange | exclamation | - |
| **AMAN** | > 7 hari | 🟢 Hijau | circle-check | - |

---

## 🎨 CSS Classes

```css
.stock-status-badge              /* Main container */
.stock-status-badge.status-dead-stock
.stock-status-badge.status-kritis
.stock-status-badge.status-menipis
.stock-status-badge.status-aman

.stock-info-modern               /* Info wrapper */
.stock-current                   /* Unit display */
.stock-estimasi                  /* Estimasi text */
```

---

## 🔧 Helper Method

```php
// Get status
$status = \App\Helpers\StockStatusHelper::getStockStatus(
    $stock,      // int - unit stock
    $keluar,     // int - total penjualan
    $createdAt   // string/Carbon - tanggal dibuat
);

// Returns array
[
    'status' => 'KRITIS',
    'label' => 'Kritis',
    'icon' => 'fa-triangle-exclamation',
    'color' => 'danger',
    'estimasi' => '2.5 hari',
    'textColor' => '#dc3545'
]

// Get background color
$bg = \App\Helpers\StockStatusHelper::getBadgeBackground($status['status']);
```

---

## 📱 Copy-Paste Templates

### Simple Badge (1 line)
```blade
@php $s = \App\Helpers\StockStatusHelper::getStockStatus($product->stock, $product->total_keluar ?? 0, $product->created_at); @endphp
<span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $s['status'])) }}"><i class="fas {{ $s['icon'] }}"></i>{{ $s['label'] }}</span>
```

### Complete Info (5 lines)
```blade
@php $s = \App\Helpers\StockStatusHelper::getStockStatus($product->stock, $product->total_keluar ?? 0, $product->created_at); @endphp
<div class="stock-info-modern">
    <div class="stock-current"><span>Unit: <strong>{{ $product->stock }}</strong></span></div>
    <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $s['status'])) }}"><i class="fas {{ $s['icon'] }}"></i>{{ $s['label'] }}</span>
    <span class="stock-estimasi">{{ $s['estimasi'] }}</span>
</div>
```

### With Background (8 lines)
```blade
@php $s = \App\Helpers\StockStatusHelper::getStockStatus($product->stock, $product->total_keluar ?? 0, $product->created_at); @endphp
<div style="background: {{ \App\Helpers\StockStatusHelper::getBadgeBackground($s['status']) }}; padding: 12px; border-radius: 8px; border-left: 3px solid {{ $s['textColor'] }};">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">Stock Status</span>
        <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $s['status'])) }}"><i class="fas {{ $s['icon'] }}"></i>{{ $s['label'] }}</span>
    </div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 8px; font-size: 12px;">
        <div><small style="color: #94a3b8;">Stock: {{ $product->stock }}</small></div>
        <div><small style="color: #94a3b8;">Estimasi: {{ $s['estimasi'] }}</small></div>
    </div>
</div>
```

---

## 🔄 Customization Cheat Sheet

### Change Threshold
```php
// File: app/Helpers/StockStatusHelper.php, line ~50
if ($estimasi_hari_habis <= 5) {   // ← Change 3 to 5
    return ['status' => 'KRITIS', ...];
}

if ($estimasi_hari_habis <= 10) {  // ← Change 7 to 10
    return ['status' => 'MENIPIS', ...];
}
```

### Change Color
```css
/* File: resources/views/product.blade.php, line ~1000 */
.stock-status-badge.status-kritis {
    background: rgba(220, 53, 69, 0.12);  /* Change RGBA */
    color: #dc3545;
}
```

### Change Icon
```php
// File: app/Helpers/StockStatusHelper.php
'icon' => 'fa-triangle-exclamation',  // ← Change to other FA icon
```

### Disable Pulse Animation
```css
/* Comment out in product.blade.php */
/* @keyframes pulse-kritis { 0%,100% { opacity: 1; } 50% { opacity: 0.7; } } */
/* .stock-status-badge.status-kritis { animation: pulse-kritis 2s infinite; } */
```

---

## 🧮 Calculation Examples

### Example 1: Aman
```
stock: 500
total_keluar: 300
created_at: 30 hari lalu

→ avg/hari = 300/30 = 10/hari
→ estimasi = 500/10 = 50 hari
→ Result: AMAN ✓
```

### Example 2: Menipis
```
stock: 40
total_keluar: 200
created_at: 30 hari lalu

→ avg/hari = 200/30 = 6.67/hari
→ estimasi = 40/6.67 = 6 hari
→ Result: MENIPIS ✓
```

### Example 3: Kritis
```
stock: 5
total_keluar: 100
created_at: 30 hari lalu

→ avg/hari = 100/30 = 3.33/hari
→ estimasi = 5/3.33 = 1.5 hari
→ Result: KRITIS ✓
```

### Example 4: Dead Stock
```
stock: 100
total_keluar: 0
created_at: any

→ No sales ever
→ Result: DEAD STOCK ✓
```

---

## 🎨 Color Reference

```
DEAD STOCK:  #6c757d (Gray)    - rgba(108, 117, 125, 0.12)
KRITIS:      #dc3545 (Red)     - rgba(220, 53, 69, 0.12)
MENIPIS:     #ff9800 (Orange)  - rgba(255, 152, 0, 0.12)
AMAN:        #10b981 (Green)   - rgba(16, 185, 129, 0.12)
```

---

## 📍 File Locations

| Component | File | Line |
|-----------|------|------|
| Helper Function | app/Helpers/StockStatusHelper.php | - |
| CSS Styling | resources/views/product.blade.php | 960-1050 |
| Current Usage | resources/views/product.blade.php | 1365-1385 |
| Documentation | STOCK_INDICATOR_DOCS.md | - |
| Examples | STOCK_INDICATOR_EXAMPLES.blade.php | - |

---

## ✅ Quick Checklist

- [ ] Helper function exists di `app/Helpers/`
- [ ] CSS sudah di product.blade.php
- [ ] FontAwesome 6.4.0 loaded
- [ ] Bootstrap 5.3.3 loaded
- [ ] Product model punya `total_keluar` field
- [ ] Data `created_at` tersedia

---

## 🆘 Common Issues

| Issue | Solution |
|-------|----------|
| Class not found | `composer dumpautoload` |
| Icon tidak muncul | Cek FontAwesome di head |
| Badge tidak styled | Cek CSS section di product.blade.php |
| Wrong calculation | Pastikan `created_at` ada |
| No pulse animation | Cek @keyframes di CSS |

---

## 💡 Pro Tips

1. **Copy helper output ke variable** untuk reusability
2. **Cache hasil** untuk dashboard real-time
3. **Gunakan di report** untuk stock analysis
4. **Add email alert** untuk KRITIS status
5. **Integrate dengan API** untuk mobile app

---

## 🔗 Quick Links

- **Full Documentation**: STOCK_INDICATOR_DOCS.md
- **Code Examples**: STOCK_INDICATOR_EXAMPLES.blade.php
- **Implementation Guide**: IMPLEMENTATION_SUMMARY.md

---

**Version**: 1.0
**Last Updated**: 14 Mei 2026
**Status**: Ready to Use ✅
