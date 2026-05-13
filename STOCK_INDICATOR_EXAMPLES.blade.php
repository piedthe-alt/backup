<?php

/**
 * CONTOH IMPLEMENTASI STOCK INDICATOR DI BERBAGAI KONTEKS
 * File ini berisi copy-paste ready code examples
 */

?>

<!-- ============================================
     CONTOH 1: PRODUCT LIST CARD (Current Implementation)
     ============================================ -->

<div class="product-card shadow-sm">
    <div class="card-body p-3">
        <div class="product-info">
            <div class="info-item">
                <span class="info-item-label">Stock</span>
                @php
                    $stockStatus = \App\Helpers\StockStatusHelper::getStockStatus(
                        $product->stock,
                        $product->total_keluar ?? 0,
                        $product->created_at ?? null
                    );
                @endphp
                <div class="stock-info-modern">
                    <div class="stock-current">
                        <span>Unit: <strong class="stock-current-value">{{ number_format($product->stock, 0, ',', '.') }}</strong></span>
                    </div>
                    <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $stockStatus['status'])) }}"
                        title="Estimasi habis: {{ $stockStatus['estimasi'] }}">
                        <i class="fas {{ $stockStatus['icon'] }}"></i>
                        <span>{{ $stockStatus['label'] }}</span>
                    </span>
                    <span class="stock-estimasi">{{ $stockStatus['estimasi'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ============================================
     CONTOH 2: DETAIL MODAL (dalam productModal)
     ============================================ -->

<div class="modal-body p-4">
    @php
        $stockStatus = \App\Helpers\StockStatusHelper::getStockStatus(
            $product->stock,
            $product->total_keluar ?? 0,
            $product->created_at ?? null
        );
    @endphp

    <!-- STOCK STATUS CARD MODERN -->
    <div style="background: {{ \App\Helpers\StockStatusHelper::getBadgeBackground($stockStatus['status']) }};
                padding: 16px;
                border-radius: 12px;
                margin-bottom: 20px;
                border-left: 4px solid {{ $stockStatus['textColor'] }};">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                📊 Status Stock
            </span>
            <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $stockStatus['status'])) }}">
                <i class="fas {{ $stockStatus['icon'] }}"></i>
                {{ $stockStatus['label'] }}
            </span>
        </div>

        <!-- Grid Info -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <!-- Kolom 1: Stock -->
            <div>
                <small style="color: #94a3b8; font-weight: 500;">Stock Saat Ini</small>
                <div style="font-size: 20px; font-weight: 700; color: #1e293b; margin-top: 4px;">
                    {{ number_format($product->stock, 0, ',', '.') }}<span style="font-size: 12px; color: #94a3b8;"> unit</span>
                </div>
            </div>

            <!-- Kolom 2: Estimasi -->
            <div>
                <small style="color: #94a3b8; font-weight: 500;">Estimasi Habis</small>
                <div style="font-size: 20px; font-weight: 700; color: {{ $stockStatus['textColor'] }}; margin-top: 4px;">
                    {{ $stockStatus['estimasi'] }}
                </div>
            </div>
        </div>

        <!-- Statistik Penjualan -->
        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,0.05);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 12px;">
                <div>
                    <span style="color: #94a3b8;">Total Penjualan</span>
                    <div style="color: #1e293b; font-weight: 600;">{{ number_format($product->total_keluar ?? 0, 0, ',', '.') }}</div>
                </div>
                <div>
                    <span style="color: #94a3b8;">Total Masuk</span>
                    <div style="color: #1e293b; font-weight: 600;">{{ number_format($product->total_masuk ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ============================================
     CONTOH 3: TABLE VIEW (Dashboard/Report)
     ============================================ -->

<table class="table table-hover">
    <thead>
        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
            <th style="color: #64748b; font-weight: 600;">Produk</th>
            <th style="color: #64748b; font-weight: 600;">Stock</th>
            <th style="color: #64748b; font-weight: 600;">Status</th>
            <th style="color: #64748b; font-weight: 600;">Estimasi</th>
            <th style="color: #64748b; font-weight: 600;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            @php
                $status = \App\Helpers\StockStatusHelper::getStockStatus(
                    $product->stock,
                    $product->total_keluar ?? 0,
                    $product->created_at ?? null
                );
            @endphp
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px; color: #1e293b; font-weight: 500;">
                    {{ Str::limit($product->name, 40) }}
                </td>
                <td style="padding: 12px;">
                    <strong>{{ number_format($product->stock, 0, ',', '.') }}</strong>
                </td>
                <td style="padding: 12px;">
                    <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $status['status'])) }}">
                        <i class="fas {{ $status['icon'] }}"></i>
                        {{ $status['label'] }}
                    </span>
                </td>
                <td style="padding: 12px; color: #64748b; font-weight: 500;">
                    {{ $status['estimasi'] }}
                </td>
                <td style="padding: 12px;">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#productModal{{ $loop->index }}">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


<!-- ============================================
     CONTOH 4: ALERT NOTIFICATION UNTUK KRITIS
     ============================================ -->

@php
    $criticalProducts = $products->filter(function($product) {
        $status = \App\Helpers\StockStatusHelper::getStockStatus(
            $product->stock,
            $product->total_keluar ?? 0,
            $product->created_at ?? null
        );
        return $status['status'] === 'KRITIS';
    });
@endphp

@if ($criticalProducts->count() > 0)
    <div style="background: rgba(220, 53, 69, 0.1);
                border: 1px solid rgba(220, 53, 69, 0.2);
                border-radius: 8px;
                padding: 16px;
                margin-bottom: 20px;">

        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; color: #dc3545;">
            <i class="fas fa-exclamation-triangle" style="font-size: 20px;"></i>
            <h6 style="margin: 0; font-weight: 700;">
                ⚠️ {{ $criticalProducts->count() }} Produk dalam Status KRITIS
            </h6>
        </div>

        <ul style="margin: 0; padding-left: 20px; color: #dc3545;">
            @foreach ($criticalProducts as $product)
                <li style="margin-bottom: 6px;">
                    <strong>{{ $product->name }}</strong>
                    (Stock: {{ $product->stock }})
                </li>
            @endforeach
        </ul>
    </div>
@endif


<!-- ============================================
     CONTOH 5: SIMPLE INLINE BADGE
     ============================================ -->

@php
    $status = \App\Helpers\StockStatusHelper::getStockStatus(
        $product->stock,
        $product->total_keluar ?? 0,
        $product->created_at ?? null
    );
@endphp

<!-- Hanya badge saja, minimal styling -->
<span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $status['status'])) }}">
    <i class="fas {{ $status['icon'] }}"></i>
    {{ $status['label'] }}
</span>


<!-- ============================================
     CONTOH 6: CUSTOM CARD DENGAN PROGRESS BAR
     ============================================ -->

@php
    $status = \App\Helpers\StockStatusHelper::getStockStatus(
        $product->stock,
        $product->total_keluar ?? 0,
        $product->created_at ?? null
    );

    // Hitung percentage untuk visual progress
    $stockPercentage = min(($product->stock / ($product->stock + ($product->total_keluar ?? 1))) * 100, 100);
@endphp

<div style="background: white; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 12px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h6 style="margin: 0; font-weight: 600; color: #1e293b;">{{ $product->name }}</h6>
        <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $status['status'])) }}">
            <i class="fas {{ $status['icon'] }}"></i>
            {{ $status['label'] }}
        </span>
    </div>

    <!-- Progress Bar -->
    <div style="background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 12px;">
        <div style="background: {{ $status['textColor'] }};
                    height: 100%;
                    width: {{ $stockPercentage }}%;
                    transition: width 0.3s ease;">
        </div>
    </div>

    <!-- Info -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; font-size: 12px;">
        <div>
            <span style="color: #94a3b8;">Stock</span>
            <div style="font-weight: 700; color: #1e293b;">{{ $product->stock }} unit</div>
        </div>
        <div>
            <span style="color: #94a3b8;">Estimasi</span>
            <div style="font-weight: 700; color: {{ $status['textColor'] }};">{{ $status['estimasi'] }}</div>
        </div>
        <div>
            <span style="color: #94a3b8;">Penjualan</span>
            <div style="font-weight: 700; color: #1e293b;">{{ $product->total_keluar ?? 0 }}</div>
        </div>
    </div>
</div>


<!-- ============================================
     CONTOH 7: USING IN BLADE COMPONENT
     ============================================ -->

@php
    // Buat reusable component di resources/views/components/stock-badge.blade.php
    // Atau gunakan seperti ini:

    function getStockBadge($product) {
        $status = \App\Helpers\StockStatusHelper::getStockStatus(
            $product->stock,
            $product->total_keluar ?? 0,
            $product->created_at ?? null
        );

        return view('components.stock-badge', compact('status', 'product'));
    }
@endphp


<!-- ============================================
     CONTOH 8: API RESPONSE (untuk AJAX update)
     ============================================ -->

<?php
// Di Controller: app/Http/Controllers/ProductController.php

public function getStockStatus($productId)
{
    $product = Product::find($productId);

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    $status = \App\Helpers\StockStatusHelper::getStockStatus(
        $product->stock,
        $product->total_keluar ?? 0,
        $product->created_at ?? null
    );

    return response()->json([
        'product_id' => $product->id,
        'product_name' => $product->name,
        'stock' => $product->stock,
        'status' => $status['status'],
        'label' => $status['label'],
        'icon' => $status['icon'],
        'estimasi' => $status['estimasi'],
        'textColor' => $status['textColor'],
    ]);
}
?>

<!-- JavaScript untuk consume API -->
<script>
async function updateStockBadge(productId, badgeElement) {
    try {
        const response = await fetch(`/api/product-stock/${productId}`);
        const data = await response.json();

        const statusClass = `status-${data.status.toLowerCase().replace('_', '-')}`;

        badgeElement.innerHTML = `
            <i class="fas ${data.icon}"></i>
            <span>${data.label}</span>
        `;

        badgeElement.className = `stock-status-badge ${statusClass}`;
        badgeElement.title = `Estimasi habis: ${data.estimasi}`;
    } catch (error) {
        console.error('Error updating stock badge:', error);
    }
}

// Update setiap 30 detik
setInterval(() => {
    document.querySelectorAll('[data-product-id]').forEach(badge => {
        const productId = badge.getAttribute('data-product-id');
        updateStockBadge(productId, badge);
    });
}, 30000);
</script>
