<!-- PRODUK -->
<div class="row g-4">

    @forelse ($products as $product)
        <div class="col-md-6 col-lg-4 col-xl-3">

            <div class="product-card shadow-sm">

                <div class="product-card-header">

                    <div class="product-header-wrapper">
                        <h5 class="product-name">
                            {{ Str::limit($product->name, 35) }}
                        </h5>
                        <button type="button" class="copy-btn"
                            onclick="copyProductName(event, '{{ $product->id }}', '{{ addslashes($product->name) }}')"
                            title="Copy nama produk">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>

                </div>

                <div class="card-body p-3 d-flex flex-column">

                    <!-- HARGA -->
                    <div class="price-badge" onclick="event.stopPropagation()" data-bs-toggle="modal"
                        data-bs-target="#productModal{{ $loop->index }}" data-product-id="{{ $product->id }}" style="cursor: pointer;">
                        Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
                    </div>

                    <!-- INFO HORIZONTAL -->
                    <div class="product-info" onclick="event.stopPropagation()"
                        data-bs-toggle="modal" data-bs-target="#productModal{{ $loop->index }}"
                        data-product-id="{{ $product->id }}" style="cursor: pointer;">

                        <!-- STOCK -->
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
                                    <span>Sisa: <strong class="stock-current-value">{{ number_format($product->stock, 0, ',', '.') }}</strong></span>
                                </div>
                                <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $stockStatus['status'])) }}"
                                    title="Estimasi habis: {{ $stockStatus['estimasi'] }}">
                                    <i class="fas {{ $stockStatus['icon'] }}"></i>
                                    <span>{{ $stockStatus['label'] }}</span>
                                </span>
                                <span class="stock-estimasi">{{ $stockStatus['estimasi'] }}</span>
                            </div>
                        </div>

                        <!-- MASUK -->
                        <div class="info-item">
                            <span class="info-item-label">Masuk</span>
                            <span
                                class="info-item-value">{{ number_format($product->total_masuk, 0, ',', '.') }}</span>
                        </div>

                        <!-- KELUAR -->
                        <div class="info-item">
                            <span class="info-item-label">Keluar</span>
                            <span
                                class="info-item-value">{{ number_format($product->total_keluar, 0, ',', '.') }}</span>
                        </div>

                    </div>

                    <!-- QUANTITY SELECTOR -->
                    <div class="quantity-selector" onclick="event.stopPropagation();">
                        <button type="button" class="qty-btn" onclick="decreaseQty(this)">−</button>
                        <input type="number" class="qty-input" value="1" min="1"
                            max="{{ $product->stock }}"
                            onchange="validateQty(this, {{ $product->stock }})">
                        <button type="button" class="qty-btn"
                            onclick="increaseQty(this, {{ $product->stock }})">+</button>
                    </div>

                    <!-- ADD TO CART BUTTONS -->
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="add-to-cart-btn" style="flex: 1;"
                            onclick="event.stopPropagation(); addToCart(event, '{{ $product->id }}', '{{ addslashes($product->name) }}', {{ $product->salesprice1 }}, '{{ addslashes($product->productgroup_name) }}', 'pcs')">
                            <i class="fas fa-box"></i> Beli Pcs
                        </button>
                        <button type="button" class="add-to-cart-btn" style="flex: 1;"
                            onclick="event.stopPropagation(); addToCart(event, '{{ $product->id }}', '{{ addslashes($product->name) }}', {{ $product->salesprice1 }}, '{{ addslashes($product->productgroup_name) }}', 'box')">
                            <i class="fas fa-cubes"></i> Beli Box
                        </button>
                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="empty-state">

                <i class="fas fa-inbox"></i>

                <h5 class="text-muted mt-3">Produk tidak ditemukan</h5>

                <p class="text-muted small">Coba gunakan keyword lain atau ubah filter</p>

            </div>

        </div>
    @endforelse

</div>

<!-- PAGINATION -->
<div class="mt-5 d-flex justify-content-center">

    {{ $products->links('pagination::bootstrap-4') }}

</div>
