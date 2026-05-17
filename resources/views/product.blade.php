<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Produk - Stock Manage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    @include('products.styles')

</head>

<body>

    <div class="container-fluid py-4">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            @include('products.header')

            <!-- BODY -->
            <div class="card-body p-4">

<!-- SEARCH & FILTER -->
            @include('products.search-form')

                            <div class="d-flex gap-2">

                                <input type="text" name="keyword" id="searchInput" class="form-control search-input"
                                    placeholder="🔍 Cari nama produk atau scan barcode..." value="{{ request('keyword') }}">

                                <button type="button" id="start-scanner" class="btn btn-dark" style="white-space: nowrap;">
                                    <i class="fas fa-barcode"></i>
                                </button>

                            </div>

                            <!-- SCANNER -->
                            <div id="reader" style="width: 100%; margin-top: 10px; display: none;"></div>

                        </div>

                        <!-- FILTER GROUP -->
<!-- FILTER GROUP -->
<div class="col-md-3">

    <div class="position-relative">

        <!-- INPUT SEARCH -->
        <input
            type="text"
            id="groupSearch"
            class="form-control search-input"
            placeholder="📂 Cari Group..."
            autocomplete="off"
            value="@php
                $selectedGroup = $productgroups->firstWhere('id', request('productgroup'));
                echo $selectedGroup ? $selectedGroup->name : '';
            @endphp"
        >

        <!-- HIDDEN -->
        <input
            type="hidden"
            name="productgroup"
            id="selectedGroup"
            value="{{ request('productgroup') }}"
        >

        <!-- DROPDOWN -->
        <div
            id="groupDropdown"
            class="bg-white border rounded shadow-sm"
            style="
                position:absolute;
                top:100%;
                left:0;
                right:0;
                max-height:300px;
                overflow-y:auto;
                z-index:9999;
                display:none;
            "
        >

            <!-- SEMUA -->
            <div
                class="group-item p-2 border-bottom"
                data-id=""
                data-name="Semua Group"
                style="cursor:pointer;"
            >
                📂 Semua Group
            </div>

            <!-- GROUP -->
            @foreach ($productgroups as $group)

                <div
                    class="group-item p-2 border-bottom"
                    data-id="{{ $group->id }}"
                    data-name="{{ strtolower($group->name) }}"
                    data-label="{{ $group->name }}"
                    style="cursor:pointer;"
                >

                    {{ $group->name }}

                </div>

            @endforeach

        </div>

    </div>

</div>

<script>

    document.addEventListener('DOMContentLoaded', function(){

        const searchInput =
            document.getElementById('groupSearch');

        const dropdown =
            document.getElementById('groupDropdown');

        const hiddenInput =
            document.getElementById('selectedGroup');

        const items =
            document.querySelectorAll('.group-item');

        /*
        |--------------------------------------------------------------------------
        | SHOW DROPDOWN
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener('focus', function(){

            dropdown.style.display = 'block';
        });

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener('keyup', function(){

            const keyword =
                this.value.toLowerCase();

            dropdown.style.display = 'block';

            items.forEach(item => {

                const name =
                    item.dataset.name;

                if(name.includes(keyword)){

                    item.style.display = 'block';

                }else{

                    item.style.display = 'none';
                }
            });
        });

        /*
        |--------------------------------------------------------------------------
        | SELECT ITEM
        |--------------------------------------------------------------------------
        */

        items.forEach(item => {

            item.addEventListener('click', function(){

                hiddenInput.value =
                    this.dataset.id;

                searchInput.value =
                    this.dataset.label || 'Semua Group';

                dropdown.style.display =
                    'none';

                /*
                |--------------------------------------------------------------------------
                | AUTO SUBMIT FORM
                |--------------------------------------------------------------------------
                */

                const form =
                    searchInput.closest('form');

                if(form){

                    form.submit();
                }
            });

            /*
            |--------------------------------------------------------------------------
            | HOVER
            |--------------------------------------------------------------------------
            */

            item.addEventListener('mouseenter', function(){

                this.style.background =
                    '#f8fafc';
            });

            item.addEventListener('mouseleave', function(){

                this.style.background =
                    'white';
            });

        });

        /*
        |--------------------------------------------------------------------------
        | CLOSE OUTSIDE
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function(e){

            if(!e.target.closest('.position-relative')){

                dropdown.style.display = 'none';
            }
        });

    });

</script>

                        <!-- BUTTON -->
                        <div class="col-md-3">

                            <button type="submit" class="btn btn-action btn-primary w-100">

                                <i class="fas fa-search"></i> Cari

                            </button>

                        </div>

                    </div>

                </form>

                <!-- SORTING BUTTONS -->
                <div class="sort-buttons mb-5">
                    <span class="sort-label">📊 Urutkan Berdasarkan:</span>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=stock_terendah"
                        class="sort-btn {{ request('sort') == 'stock_terendah' || !request('sort') ? 'active' : '' }}">
                        <i class="fas fa-arrow-down me-1"></i>Stock Terendah
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=stock_tertinggi"
                        class="sort-btn {{ request('sort') == 'stock_tertinggi' ? 'active' : '' }}">
                        <i class="fas fa-arrow-up me-1"></i>Stock Tertinggi
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=paling_laris"
                        class="sort-btn {{ request('sort') == 'paling_laris' ? 'active' : '' }}">
                        <i class="fas fa-fire me-1"></i>Paling Laris
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=gak_jalan"
                        class="sort-btn {{ request('sort') == 'gak_jalan' ? 'active' : '' }}">
                        <i class="fas fa-snooze me-1"></i>Gak Jalan
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=nama_asc"
                        class="sort-btn {{ request('sort') == 'nama_asc' ? 'active' : '' }}">
                        <i class="fas fa-sort-alpha-down me-1"></i>A-Z
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=nama_desc"
                        class="sort-btn {{ request('sort') == 'nama_desc' ? 'active' : '' }}">
                        <i class="fas fa-sort-alpha-up-alt me-1"></i>Z-A
                    </a>
                </div>

                <!-- QR READER MODAL -->
                <div id="scanner-modal" class="scanner-modal">
                    <div class="scanner-container">
                        <div class="scanner-header">
                            <h5><i class="fas fa-barcode me-2"></i>Scanner QR/Barcode</h5>
                            <button type="button" class="close-btn" onclick="stopScanner()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="scanner-body">
                            <canvas id="video-canvas"></canvas>
                            <div class="scanner-overlay"></div>
                        </div>
                        <div class="scanner-footer">
                            <div class="scanner-status scanning" id="scanner-status">
                                <span class="spinner-small"></span>Scanning...
                            </div>
                            <small class="text-muted">Arahkan kamera ke barcode atau QR code</small>
                        </div>
                    </div>
                </div>

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

            </div>

        </div>

    </div>

    <!-- MODAL -->
    @foreach ($products as $product)
        <div class="modal fade" id="productModal{{ $loop->index }}" tabindex="-1" data-product-id="{{ $product->id }}">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content border-0 shadow-lg rounded-4">

                    <!-- HEADER -->
                    <div class="modal-header">

                        <div>
                            <h5 class="modal-title">

                                <i class="fas fa-box me-2"></i>{{ $product->name }}

                            </h5>
                            <small class="text-white-50">Detail Produk</small>
                        </div>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">

                        </button>

                    </div>

                    <!-- BODY -->
                    <div class="modal-body p-4">

                        <!-- TABS -->
                        <ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid #e5e7eb;">
                            <li class="nav-item">
                                <a class="nav-link active" id="detail-tab-{{ $loop->index }}" data-bs-toggle="tab" href="#detail-content-{{ $loop->index }}" role="tab">
                                    <i class="fas fa-info-circle me-2"></i>Detail Produk
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="history-in-tab-{{ $loop->index }}" data-bs-toggle="tab" href="#history-in-content-{{ $loop->index }}" role="tab" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-arrow-down me-2"></i>Riwayat Stok Masuk
                                </a>
                            </li>
                        </ul>

                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <!-- DETAIL TAB -->
                            <div class="tab-pane fade show active" id="detail-content-{{ $loop->index }}" role="tabpanel">

                        <table class="table">

                            <tbody>

                                <tr>

                                    <th width="250">
                                        <i class="fas fa-barcode me-2 text-success"></i>Kode Barang
                                    </th>

                                    <td>

                                        <span class="badge bg-success text-white">{{ $product->id }}</span>

                                    </td>

                                </tr>

                                <tr>

                                    <th width="250">
                                        <i class="fas fa-tag me-2 text-primary"></i>Group Produk
                                    </th>

                                    <td>

                                        <span
                                            class="badge bg-light text-dark">{{ $product->productgroup_name }}</span>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-truck me-2 text-primary"></i>Supplier
                                    </th>

                                    <td>

                                        {{ $product->supplier_name }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-warehouse me-2 text-info"></i>Stock Saat Ini
                                    </th>

                                    <td>

                                        <strong class="text-info">{{ number_format($product->stock, 0, ',', '.') }}
                                            pcs</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-arrow-down me-2 text-success"></i>Total Barang Masuk
                                    </th>

                                    <td>

                                        <strong
                                            class="text-success">{{ number_format($product->total_masuk, 0, ',', '.') }}</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-arrow-up me-2 text-warning"></i>Total Barang Keluar
                                    </th>

                                    <td>

                                        <strong
                                            class="text-warning">{{ number_format($product->total_keluar, 0, ',', '.') }}</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-coins me-2 text-danger"></i>Harga Modal
                                    </th>

                                    <td>

                                        <strong class="text-danger">Rp
                                            {{ number_format($product->costprice, 0, ',', '.') }}</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-money-bill-wave me-2 text-success"></i>Harga Jual
                                    </th>

                                    <td>

                                        <strong class="text-success">Rp
                                            {{ number_format($product->salesprice1, 0, ',', '.') }}</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-percent me-2 text-primary"></i>Margin
                                    </th>

                                    <td>

                                        @php
                                            $margin =
                                                $product->costprice > 0
                                                    ? (($product->salesprice1 - $product->costprice) /
                                                            $product->costprice) *
                                                        100
                                                    : 0;
                                        @endphp

                                        <strong
                                            class="text-primary">{{ number_format($margin, 2, ',', '.') }}%</strong>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                        <!-- PRICING STRATA TABLE -->
                        @php
                            // Check if any salesdiscqty is not 0
                            $hasPricingStrata = false;
                            for ($i = 1; $i <= 7; $i++) {
                                $field = 'salesdiscqty' . $i;
                                if (isset($product->$field) && $product->$field != 0) {
                                    $hasPricingStrata = true;
                                    break;
                                }
                            }
                        @endphp

                        @if ($hasPricingStrata)
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                                <h6 style="margin-bottom: 15px; font-weight: 700; color: #1e293b;">
                                    <i class="fas fa-layer-group me-2 text-success"></i>Harga Bertingkat (Strata)
                                </h6>
                                <div style="overflow-x: auto;">
                                    <table class="table table-sm" style="font-size: 0.9rem; margin-bottom: 0;">
                                        <thead style="background-color: #f8fafc;">
                                            <tr>
                                                <th style="text-align: center; color: #1e293b; font-weight: 600;">
                                                    <i class="fas fa-cube me-1"></i>Jumlah Beli
                                                </th>
                                                <th style="text-align: center; color: #1e293b; font-weight: 600;">
                                                    <i class="fas fa-tag me-1"></i>Harga Satuan
                                                </th>
                                                <th style="text-align: center; color: #1e293b; font-weight: 600;">
                                                    <i class="fas fa-calculator me-1"></i>Total Harga
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 1; $i <= 7; $i++)
                                                @php
                                                    $qtyField = 'salesdiscqty' . $i;
                                                    $priceField = 'salesdiscprice' . $i;
                                                    $qty = (isset($product->$qtyField) && $product->$qtyField != 0) ? $product->$qtyField : null;
                                                    $price = isset($product->$priceField) ? $product->$priceField : 0;
                                                @endphp
                                                @if ($qty !== null && $qty != 0)
                                                    <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $i % 2 == 0 ? '#f8fafc' : 'white' }};">
                                                        <td style="text-align: center; font-weight: 600; color: #2563eb;">
                                                            {{ number_format($qty, 0, ',', '.') }}
                                                        </td>
                                                        <td style="text-align: center; color: #64748b;">
                                                            Rp {{ number_format($price, 0, ',', '.') }}
                                                        </td>
                                                        <td style="text-align: center; font-weight: 700; color: #10b981;">
                                                            Rp {{ number_format($qty * $price, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                                <div style="margin-top: 12px; padding: 12px; background-color: rgba(16, 185, 129, 0.08); border-left: 4px solid #10b981; border-radius: 6px;">
                                    <small style="color: #64748b;">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Tabel ini menunjukkan diskon bertingkat. Semakin banyak jumlah pembelian, harga satuan semakin murah.
                                    </small>
                                </div>
                            </div>
                        @endif

                            </div>

                            <!-- HISTORY MASUK TAB -->
                            <div class="tab-pane fade" id="history-in-content-{{ $loop->index }}" role="tabpanel">
                                <div id="inventory-history-in-loading-{{ $loop->index }}" class="text-center py-4">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2 small">Memuat data barang masuk...</p>
                                </div>
                                <div id="inventory-history-in-content-{{ $loop->index }}" style="display: none;">
                                    <!-- Summary Section -->
                                    <div class="row mb-4 g-2">
                                        <div class="col-12">
                                            <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center">
                                                <small class="text-muted">Total Barang Masuk</small>
                                                <p class="mb-0 h6 text-success fw-bold" id="total-masuk-{{ $loop->index }}">0</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Transactions List -->
                                    <div class="inventory-transactions-in-{{ $loop->index }}">
                                        <p class="text-muted small">Tidak ada data barang masuk</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    @endforeach

    <!-- CART MODAL -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <!-- HEADER -->
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">
                            <i class="fas fa-shopping-cart me-2"></i>Keranjang Order
                        </h5>
                        <small class="text-white-50">Daftar produk yang akan di-order</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- BODY -->
                <div class="modal-body p-4">
                    <div id="cart-items-container">
                        <div class="cart-empty-state">
                            <i class="fas fa-shopping-cart"></i>
                            <h5 class="text-muted mt-3">Keranjang kosong</h5>
                            <p class="text-muted small">Tambahkan produk dengan menekan tombol "Tambah ke Cart"</p>
                        </div>
                    </div>

                    <div id="cart-summary-container" style="display: none;">
                        <div class="cart-summary">
                            <div class="cart-summary-row">
                                <span>Total Item:</span>
                                <span id="cart-total-items">0</span>
                            </div>
                            <div class="cart-summary-row">
                                <span>Total Jumlah:</span>
                                <span id="cart-total-qty">0</span>
                            </div>
                        </div>

                        <div class="cart-actions">
                            <button type="button" class="cart-copy-btn" id="cart-copy-btn"
                                onclick="copyCartList(this)">
                                <i class="fas fa-copy"></i> Copy List
                            </button>
                            <button type="button" class="cart-clear-btn" onclick="clearCart()">
                                <i class="fas fa-trash"></i> Kosongkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        // ============ CART MANAGEMENT ============
        let cart = JSON.parse(localStorage.getItem('orderCart')) || {};
        let cartReturns = {}; // Simpan returns data per group

        function addToCart(event, productId, productName, price, groupName, type = 'pcs') {
            event.stopPropagation();

            // Get quantity from the input field
            const qtyInput = event.target.closest('.card-body').querySelector('.qty-input');
            const quantity = parseInt(qtyInput.value) || 1;

            if (quantity <= 0) {
                alert('Masukkan jumlah yang valid');
                return;
            }

            // Create unique key for cart item (productId + type)
            const cartKey = `${productId}_${type}`;

            // Add or update cart item
            if (cart[cartKey]) {
                cart[cartKey].quantity += quantity;
            } else {
                cart[cartKey] = {
                    id: productId,
                    name: productName,
                    price: price,
                    quantity: quantity,
                    group: groupName,
                    type: type
                };
            }

            // Save to localStorage
            localStorage.setItem('orderCart', JSON.stringify(cart));

            // Update badge
            updateCartBadge();

            // Reset quantity
            qtyInput.value = 1;

            // Show notification
            showAddToCartNotification(productName, quantity, type);
        }

        function updateCartBadge() {
            const badge = document.getElementById('cart-count');
            const itemCount = Object.keys(cart).length;

            if (itemCount > 0) {
                badge.textContent = itemCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        function openCartModal() {
            renderCartItems();
            const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            cartModal.show();
        }

        function renderCartItems() {
            const container = document.getElementById('cart-items-container');
            const summaryContainer = document.getElementById('cart-summary-container');

            if (Object.keys(cart).length === 0) {
                container.innerHTML = `
                    <div class="cart-empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h5 class="text-muted mt-3">Keranjang kosong</h5>
                        <p class="text-muted small">Tambahkan produk dengan menekan tombol "Tambah ke Cart"</p>
                    </div>
                `;
                summaryContainer.style.display = 'none';
                return;
            }

            let html = '';
            let totalItems = 0;
            let totalQty = 0;
            let groupedItems = {};

            // Group items by group name
            for (let productId in cart) {
                const item = cart[productId];
                const group = item.group || 'Uncategorized';

                if (!groupedItems[group]) {
                    groupedItems[group] = [];
                }
                groupedItems[group].push({
                    id: productId,
                    ...item
                });
                totalItems++;
                totalQty += item.quantity;
            }

            // Render grouped items with returns
            for (let group in groupedItems) {
                html += `<div style="margin-bottom: 20px;">`;
                html +=
                    `<h6 style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; padding: 10px 12px; border-radius: 6px; margin-bottom: 12px; font-weight: 600; margin-top: 0;"><i class="fas fa-folder me-2"></i>Orderan ${group}</h6>`;

                for (let item of groupedItems[group]) {
                    html += `
                        <div class="cart-item">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${item.name}</div>
                                <div class="cart-item-code">Kode: ${item.id}</div>
                            </div>
                            <div class="cart-item-qty">${item.quantity} pcs</div>
                            <button type="button" class="cart-item-remove" onclick="removeFromCart('${item.id}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                }

                // Load returns for this group
                loadReturnsForGroupDisplay(group, groupedItems[group], (returnsHtml) => {
                    if (returnsHtml) {
                        const groupDiv = container.querySelector(`[data-group="${group}"]`);
                        if (groupDiv) {
                            groupDiv.insertAdjacentHTML('beforeend', returnsHtml);
                        }
                    }
                });

                html += `<div data-group="${group}"></div></div>`;
            }

            container.innerHTML = html;
            document.getElementById('cart-total-items').textContent = totalItems;
            document.getElementById('cart-total-qty').textContent = totalQty;
            summaryContainer.style.display = 'block';
        }

        // Load and display returns for a group
        async function loadReturnsForGroupDisplay(groupName, items, callback) {
            try {
                let returnsHtml = '';
                const returns = await getReturnsForGroup(groupName, items);

                // Simpan returns ke variable global
                if (returns && returns.length > 0) {
                    cartReturns[groupName] = returns;
                } else {
                    cartReturns[groupName] = [];
                }

                if (returns && returns.length > 0) {
                    returnsHtml = `
                        <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.3); padding: 12px; border-radius: 8px; margin-top: 12px;">
                            <div style="font-weight: 600; color: #ef4444; font-size: 0.9rem; margin-bottom: 8px;">
                                <i class="fas fa-undo me-2"></i>Returan:
                            </div>
                    `;

                    for (let ret of returns) {
                        // Cek berbagai kemungkinan field name untuk quantity
                        const qty = ret.quantity_retur || ret.quantity || ret.qty || ret.jumlah || 0;
                        returnsHtml += `
                            <div style="color: #1e293b; font-size: 0.85rem; margin-bottom: 4px;">
                                - ${ret.product_name} = ${qty} Pcs
                            </div>
                        `;
                    }

                    returnsHtml += `</div>`;
                }

                callback(returnsHtml);
            } catch (error) {
                console.error('Error loading returns:', error);
                callback('');
            }
        }

        function removeFromCart(productId) {
            delete cart[productId];
            localStorage.setItem('orderCart', JSON.stringify(cart));
            updateCartBadge();
            renderCartItems();
        }

        function clearCart() {
            if (confirm('Yakin ingin mengosongkan keranjang?')) {
                cart = {};
                localStorage.setItem('orderCart', JSON.stringify(cart));
                updateCartBadge();
                renderCartItems();
            }
        }

        // Fetch returns data for a group
        async function getReturnsForGroup(groupName, items) {
            try {
                let returns = [];

                // Fetch returns for each product in the group
                for (let item of items) {
                    const response = await fetch(`/api/get-returns?product_name=${encodeURIComponent(item.name)}`);

                    if (!response.ok) {
                        console.error(`API error for ${item.name}: Status ${response.status}`);
                        continue;
                    }

                    const data = await response.json();

                    if (data.returns && Array.isArray(data.returns)) {
                        // Jika returns adalah array, spread ke returns utama
                        returns = [...returns, ...data.returns];
                    } else if (data.returns) {
                        // Jika returns adalah single object, push ke array
                        returns.push(data.returns);
                    }

                    if (data.error) {
                        console.error(`API error: ${data.error}`);
                    }
                }

                return returns;
            } catch (error) {
                console.error('Error fetching returns:', error);
                return [];
            }
        }

        async function copyCartList(btn) {

            let copyText = '';

            let groupedItems = {};

            /*
            |--------------------------------------------------------------------------
            | GROUP CART
            |--------------------------------------------------------------------------
            */

            for (let productId in cart) {

                const item = cart[productId];

                const group = item.group || 'Uncategorized';

                if (!groupedItems[group]) {
                    groupedItems[group] = [];
                }

                groupedItems[group].push(item);
            }

            /*
            |--------------------------------------------------------------------------
            | KERANJANG KOSONG
            |--------------------------------------------------------------------------
            */

            if (Object.keys(groupedItems).length === 0) {

                alert('Keranjang kosong');

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LOOP GROUP
            |--------------------------------------------------------------------------
            */

            for (let group in groupedItems) {

                /*
                |--------------------------------------------------------------------------
                | HEADER GROUP
                |--------------------------------------------------------------------------
                */

                copyText += `Orderan ${group}\n`;

                /*
                |--------------------------------------------------------------------------
                | ITEM ORDER
                |--------------------------------------------------------------------------
                */

                for (let item of groupedItems[group]) {

                    const unitType = item.type === 'box' ? 'Box' : 'Pcs';
                    copyText += `- ${item.name} = ${item.quantity} ${unitType}\n`;
                }

                /*
                |--------------------------------------------------------------------------
                | AMBIL RETUR GROUP DARI cartReturns
                |--------------------------------------------------------------------------
                */

                if (cartReturns[group] && cartReturns[group].length > 0) {
                    copyText += `Barang Retur : \n`;
                    for (let ret of cartReturns[group]) {
                        const qty = ret.quantity_retur || ret.quantity || ret.qty || ret.jumlah || 0;
                        copyText += `- ${ret.product_name} = ${qty} Pcs\n`;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | SPASI ANTAR GROUP
                |--------------------------------------------------------------------------
                */

                copyText += `\n`;
            }

            /*
            |--------------------------------------------------------------------------
            | COPY
            |--------------------------------------------------------------------------
            */

            navigator.clipboard.writeText(copyText)

                .then(() => {

                    const originalIcon = btn.innerHTML;

                    const originalBg = btn.style.background;

                    btn.innerHTML =
                        '<i class="fas fa-check"></i> Tersalin';

                    btn.style.background =
                        'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)';

                    setTimeout(() => {

                        btn.innerHTML = originalIcon;

                        btn.style.background = originalBg;

                    }, 2000);

                    showCopyNotification(
                        'Cart',
                        'Daftar order berhasil di-copy'
                    );

                })

                .catch(err => {

                    console.error(err);

                    alert('Gagal mengcopy list order');
                });
        }

        function number_format(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function showAddToCartNotification(productName, quantity, type = 'pcs') {
            const notification = document.createElement('div');
            const displayText = `${productName.substring(0, 25)}${productName.length > 25 ? '...' : ''}`;
            const unitType = type === 'box' ? 'Box' : 'Pcs';

            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
                font-weight: 500;
                max-width: 300px;
            `;
            notification.innerHTML = `
                <i class="fas fa-plus-circle me-2"></i>
                ${displayText} (${quantity} ${unitType}) ditambahkan ke cart
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Initialize cart badge on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartBadge();
        });

        // ============ QUANTITY SELECTOR FUNCTIONS ============
        function increaseQty(btn) {

            const input = btn.closest('.quantity-selector').querySelector('.qty-input');

            let currentVal = parseInt(input.value) || 1;

            input.value = currentVal + 1;
        }

        function decreaseQty(btn) {
            const input = btn.closest('.quantity-selector').querySelector('.qty-input');
            let currentVal = parseInt(input.value) || 1;
            if (currentVal > 1) {
                input.value = currentVal - 1;
            }
        }

        function validateQty(input) {

            let value = parseInt(input.value) || 1;

            if (value < 1) {
                value = 1;
            }

            input.value = value;
        }

        // Copy Product Name Function
        function copyProductName(event, productCode, productName) {
            event.stopPropagation();

            // Format: "- [kode] - [nama produk] :\n"
            const formattedText = `- ${productCode} - ${productName} :\n`;

            // Copy to clipboard
            navigator.clipboard.writeText(formattedText).then(() => {
                const btn = event.target.closest('.copy-btn');
                const originalIcon = btn.innerHTML;

                // Change button appearance
                btn.classList.add('copied');
                btn.innerHTML = '<i class="fas fa-check"></i>';

                // Reset after 2 seconds
                setTimeout(() => {
                    btn.classList.remove('copied');
                    btn.innerHTML = originalIcon;
                }, 2000);

                // Optional: Show toast notification
                showCopyNotification(productCode, productName);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Gagal mengcopy nama produk');
            });
        }

        // Toast Notification Function
        function showCopyNotification(productCode, productName) {
            const notification = document.createElement('div');
            const displayText =
                `- ${productCode} - ${productName.substring(0, 20)}${productName.length > 20 ? '...' : ''} :`;

            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
                font-weight: 500;
                max-width: 300px;
            `;
            notification.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Tercopy: "${displayText}"
            `;

            document.body.appendChild(notification);

            // Add animation
            const style = document.createElement('style');
            if (!document.querySelector('style[data-copy-animation]')) {
                style.setAttribute('data-copy-animation', 'true');
                style.textContent = `
                    @keyframes slideIn {
                        from {
                            transform: translateX(400px);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                `;
                document.head.appendChild(style);
            }

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // ==========================================================================
        // GLOBAL SCANNER STATE
        // ==========================================================================

        let scannerState = {
            isRunning: false,
            lastScannedTime: 0,
            debounceTime: 1500,
            stream: null
        };

        // ==========================================================================
        // START SCANNER
        // ==========================================================================

        async function startScanner() {

            const scannerModal =
                document.getElementById('scanner-modal');

            scannerModal.classList.add('active');

            updateScannerStatus(
                'scanning',
                '📷 Membuka kamera belakang...'
            );

            /*
            |--------------------------------------------------------------------------
            | STOP DULU JIKA MASIH ADA
            |--------------------------------------------------------------------------
            */

            try {

                if (scannerState.isRunning) {

                    Quagga.stop();

                    scannerState.isRunning = false;
                }

            } catch (e) {}

            /*
            |--------------------------------------------------------------------------
            | INIT QUAGGA
            |--------------------------------------------------------------------------
            */

            Quagga.init({

                    inputStream: {

                        name: "Live",

                        type: "LiveStream",

                        target: document.querySelector('#video-canvas'),

                        constraints: {

                            width: {
                                ideal: 1920
                            },

                            height: {
                                ideal: 1080
                            },

                            /*
                            |--------------------------------------------------------------------------
                            | PAKSA KAMERA BELAKANG
                            |--------------------------------------------------------------------------
                            */

                            facingMode: {
                                exact: "environment"
                            }

                        }
                    },

                    locator: {

                        patchSize: "medium",

                        halfSample: false
                    },

                    numOfWorkers: navigator.hardwareConcurrency || 4,

                    frequency: 10,

                    decoder: {

                        readers: [

                            "ean_reader",

                            "ean_8_reader",

                            "code_128_reader",

                            "upc_reader",

                            "upc_e_reader",

                            "code_39_reader",

                            "codabar_reader",

                            "i2of5_reader"

                        ]
                    },

                    locate: true

                },

                function(err) {

                    /*
                    |--------------------------------------------------------------------------
                    | FALLBACK JIKA exact environment GAGAL
                    |--------------------------------------------------------------------------
                    */

                    if (err) {

                        console.warn(
                            'Environment camera gagal, fallback...',
                            err
                        );

                        Quagga.init({

                                inputStream: {

                                    name: "Live",

                                    type: "LiveStream",

                                    target: document.querySelector('#video-canvas'),

                                    constraints: {

                                        width: {
                                            ideal: 1280
                                        },

                                        height: {
                                            ideal: 720
                                        },

                                        facingMode: "environment"
                                    }
                                },

                                locator: {

                                    patchSize: "medium",

                                    halfSample: false
                                },

                                decoder: {

                                    readers: [

                                        "ean_reader",

                                        "ean_8_reader",

                                        "code_128_reader",

                                        "upc_reader",

                                        "upc_e_reader",

                                        "code_39_reader"

                                    ]
                                },

                                locate: true

                            },

                            function(err2) {

                                if (err2) {

                                    console.error(err2);

                                    updateScannerStatus(
                                        'error',
                                        '❌ Kamera gagal dibuka'
                                    );

                                    return;
                                }

                                startQuaggaEngine();
                            }
                        );

                        return;
                    }

                    startQuaggaEngine();
                }
            );
        }

        // ==========================================================================
        // START ENGINE
        // ==========================================================================

        function startQuaggaEngine() {

            Quagga.start();

            scannerState.isRunning = true;

            updateScannerStatus(
                'success',
                '✅ Kamera siap, arahkan ke barcode'
            );

            /*
            |--------------------------------------------------------------------------
            | REMOVE EVENT LAMA
            |--------------------------------------------------------------------------
            */

            Quagga.offDetected(onScanSuccess);

            Quagga.offProcessed(onProcessed);

            /*
            |--------------------------------------------------------------------------
            | EVENT
            |--------------------------------------------------------------------------
            */

            Quagga.onDetected(onScanSuccess);

            Quagga.onProcessed(onProcessed);
        }

        // ==========================================================================
        // DRAW BOX
        // ==========================================================================

        function onProcessed(result) {

            const drawingCtx =
                Quagga.canvas.ctx.overlay;

            const drawingCanvas =
                Quagga.canvas.canvas.overlay;

            if (!drawingCtx || !drawingCanvas) {
                return;
            }

            drawingCtx.clearRect(
                0,
                0,
                drawingCanvas.width,
                drawingCanvas.height
            );

            if (result && result.boxes) {

                result.boxes

                    .filter(box => box !== result.box)

                    .forEach(box => {

                        Quagga.ImageDebug.drawPath(

                            box,

                            {
                                x: 0,
                                y: 1
                            },

                            drawingCtx,

                            {
                                lineWidth: 2
                            }
                        );
                    });
            }

            if (result && result.box) {

                Quagga.ImageDebug.drawPath(

                    result.box,

                    {
                        x: 0,
                        y: 1
                    },

                    drawingCtx,

                    {
                        lineWidth: 3
                    }
                );
            }

            if (
                result &&
                result.codeResult &&
                result.codeResult.code
            ) {

                Quagga.ImageDebug.drawPath(

                    result.line,

                    {
                        x: 'x',
                        y: 'y'
                    },

                    drawingCtx,

                    {
                        color: 'green',
                        lineWidth: 4
                    }
                );
            }
        }

        // ==========================================================================
        // SCAN SUCCESS
        // ==========================================================================

        function onScanSuccess(result) {

            const currentTime = Date.now();

            /*
            |--------------------------------------------------------------------------
            | DEBOUNCE
            |--------------------------------------------------------------------------
            */

            if (
                currentTime - scannerState.lastScannedTime <
                scannerState.debounceTime
            ) {

                return;
            }

            if (
                result.codeResult &&
                result.codeResult.code
            ) {

                const scannedValue =
                    result.codeResult.code;

                console.log(
                    'Scanned:',
                    scannedValue
                );

                /*
                |--------------------------------------------------------------------------
                | SOUND
                |--------------------------------------------------------------------------
                */

                playBeep();

                /*
                |--------------------------------------------------------------------------
                | INPUT SEARCH
                |--------------------------------------------------------------------------
                */

                document.getElementById('searchInput').value =
                    scannedValue;

                updateScannerStatus(
                    'success',
                    `✅ Barcode: ${scannedValue}`
                );

                scannerState.lastScannedTime =
                    currentTime;

                /*
                |--------------------------------------------------------------------------
                | CLOSE SCANNER & FETCH PRODUCT
                |--------------------------------------------------------------------------
                */

                setTimeout(() => {

                    stopScanner();

                    // Fetch product data via API
                    searchProductByBarcode(scannedValue);

                }, 700);
            }
        }

        // ==========================================================================
        // SEARCH PRODUCT BY BARCODE VIA API
        // ==========================================================================

        async function searchProductByBarcode(barcode) {

            try {

                const response = await fetch(
                    `/api/search-product-by-barcode?keyword=${encodeURIComponent(barcode)}`
                );

                const result = await response.json();

                if (result.success && result.data) {

                    // Show dynamic modal with product info
                    showProductModal(result.data);

                } else {

                    alert(`Produk "${barcode}" tidak ditemukan`);
                }

            } catch (error) {

                console.error('Error:', error);
                alert('Terjadi kesalahan saat mencari produk');
            }
        }

        // ==========================================================================
        // SHOW DYNAMIC PRODUCT MODAL
        // ==========================================================================

        function showProductModal(product) {

            const modalHtml = `
                <div class="modal fade" id="scanResultModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header bg-primary text-white">
                                <div>
                                    <h5 class="modal-title">
                                        <i class="fas fa-box me-2"></i>${product.name}
                                    </h5>
                                    <small class="text-white-50">Hasil Scan Produk</small>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th width="250">
                                                <i class="fas fa-barcode me-2 text-success"></i>Kode Barang
                                            </th>
                                            <td>
                                                <span class="badge bg-success text-white">${product.id}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-tag me-2 text-primary"></i>Group Produk
                                            </th>
                                            <td>
                                                <span class="badge bg-light text-dark">${product.productgroup_name}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-truck me-2 text-primary"></i>Supplier
                                            </th>
                                            <td>
                                                ${product.supplier_name}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-warehouse me-2 text-info"></i>Stock Saat Ini
                                            </th>
                                            <td>
                                                <strong class="text-info">${parseInt(product.stock).toLocaleString('id-ID')} pcs</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-arrow-down me-2 text-success"></i>Total Barang Masuk
                                            </th>
                                            <td>
                                                <strong class="text-success">${parseInt(product.total_masuk).toLocaleString('id-ID')}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-arrow-up me-2 text-warning"></i>Total Barang Keluar
                                            </th>
                                            <td>
                                                <strong class="text-warning">${parseInt(product.total_keluar).toLocaleString('id-ID')}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove old modal if exists
            const oldModal = document.getElementById('scanResultModal');
            if (oldModal) {
                oldModal.remove();
            }

            // Add new modal to page
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Show modal
            const modal = new bootstrap.Modal(
                document.getElementById('scanResultModal')
            );

            modal.show();

            // Remove modal from DOM when hidden
            document.getElementById('scanResultModal')
                .addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
        }

        // ==========================================================================
        // SOUND BEEP
        // ==========================================================================

        // function playBeep() {

        //     try {

        //         const audio = new Audio(
        //             'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YVYGAACBhYqFbF1fd5W...
        //             '
        //         );

        //         audio.volume = 0.3;

        //         audio.play();

        //     } catch (e) {}
        // }

        // ==========================================================================
        // UPDATE STATUS
        // ==========================================================================

        function updateScannerStatus(type, message) {

            const statusEl =
                document.getElementById('scanner-status');

            statusEl.className =
                'scanner-status ' + type;

            statusEl.textContent =
                message;
        }

        // ==========================================================================
        // STOP SCANNER
        // ==========================================================================

        function stopScanner() {

            try {

                Quagga.offDetected(onScanSuccess);

                Quagga.offProcessed(onProcessed);

                Quagga.stop();

            } catch (err) {

                console.error(err);
            }

            scannerState.isRunning = false;

            const scannerModal =
                document.getElementById('scanner-modal');

            scannerModal.classList.remove('active');
        }

        // ==========================================================================
        // CLOSE MODAL
        // ==========================================================================

        document
            .getElementById('scanner-modal')

            .addEventListener('click', function(e) {

                if (e.target === this) {

                    stopScanner();
                }
            });

        // ==========================================================================
        // ESC
        // ==========================================================================

        document.addEventListener('keydown', function(e) {

            if (
                e.key === 'Escape' &&
                document
                .getElementById('scanner-modal')
                .classList.contains('active')
            ) {

                stopScanner();
            }
        });

        // ============ INVENTORY HISTORY TAB EVENTS ============
        document.addEventListener('shown.bs.tab', function (e) {
            // Check if it's history-in tab
            if (e.target && e.target.id && e.target.id.startsWith('history-in-tab-')) {
                const productId = e.target.getAttribute('data-product-id');
                const tabIndex = e.target.id.replace('history-in-tab-', '');
                loadInventoryHistoryTab(productId, tabIndex);
            }
        });

        // ============ INVENTORY HISTORY ============
        function loadInventoryHistoryTab(productId, tabIndex) {
            const loadingId = `inventory-history-in-loading-${tabIndex}`;
            const contentId = `inventory-history-in-content-${tabIndex}`;
            const containerId = `inventory-transactions-in-${tabIndex}`;
            const totalId = `total-masuk-${tabIndex}`;

            const loadingDiv = document.getElementById(loadingId);
            const contentDiv = document.getElementById(contentId);
            const transactionsContainer = document.querySelector(`.${containerId}`);

            if (!loadingDiv || !contentDiv) {
                console.error('Elements not found', {loadingId, contentId});
                return;
            }

            // Reset state setiap kali di-click
            loadingDiv.style.display = 'block';
            contentDiv.style.display = 'none';
            transactionsContainer.innerHTML = '';

            fetch(`/api/product-inventory-history?product_id=${productId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load inventory history');
                    return response.json();
                })
                .then(data => {
                    // Filter transactions yang masuk saja (in)
                    const filteredTransactions = data.transactions.filter(trans => trans.direction === 'in');

                    // Calculate total
                    let total = 0;
                    filteredTransactions.forEach(trans => {
                        total += trans.quantity;
                    });

                    // Update summary
                    document.getElementById(totalId).textContent = number_format(total);

                    // Render transactions
                    if (filteredTransactions.length === 0) {
                        transactionsContainer.innerHTML = `<p class="text-muted small">Tidak ada data barang masuk</p>`;
                        loadingDiv.style.display = 'none';
                        contentDiv.style.display = 'block';
                        return;
                    }

                    let html = '';
                    filteredTransactions.forEach((trans, index) => {
                        const badgeClass = 'bg-success';
                        const directionText = 'MASUK';
                        const colorClass = 'text-success';

                        html += `
                            <div class="transaction-item mb-3 p-3 border rounded-3" style="border-color: #e5e7eb !important; background: #fafbfc;">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="d-flex align-items-start gap-3" style="flex: 1;">
                                        <div class="transaction-icon" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f3f4f6; color: #10b981;">
                                            <i class="fas ${trans.icon}"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <strong class="small">${trans.type}</strong>
                                                <span class="badge ${badgeClass} text-white" style="font-size: 10px; padding: 3px 8px;">${directionText}</span>
                                            </div>
                                            <p class="mb-1 small text-muted"><strong>${trans.transid}</strong></p>
                                            <p class="mb-0 small text-muted">
                                                <i class="far fa-calendar me-1"></i>
                                                ${new Date(trans.date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}
                                            </p>
                                            ${trans.memo ? `<p class="mb-0 small text-muted mt-1"><i class="fas fa-comment me-1"></i>${trans.memo}</p>` : ''}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-0 h6 ${colorClass}" style="font-weight: bold;">
                                            +${number_format(trans.quantity)}
                                        </p>
                                        <small class="text-muted">pcs</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    transactionsContainer.innerHTML = html;
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error loading inventory history:', error);
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                    transactionsContainer.innerHTML = `<p class="text-danger small">Gagal memuat data barang masuk</p>`;
                });
        }

        /*
        |--------------------------------------------------------------------------
        | BARCODE SCANNER
        |--------------------------------------------------------------------------
        */

        const searchInput = document.getElementById('searchInput');
        const scannerBtn = document.getElementById('start-scanner');
        const readerDiv = document.getElementById('reader');

        let scannerRunning = false;
        let html5QrCode = null;

        /*
        |--------------------------------------------------------------------------
        | START SCANNER
        |--------------------------------------------------------------------------
        */

        scannerBtn.addEventListener('click', async function() {

            if (scannerRunning) {

                await html5QrCode.stop();

                readerDiv.style.display = 'none';

                scannerRunning = false;

                scannerBtn.innerHTML = '<i class="fas fa-barcode"></i>';

                return;
            }

            readerDiv.style.display = 'block';

            html5QrCode = new Html5Qrcode("reader");

            scannerRunning = true;

            scannerBtn.innerHTML = '<i class="fas fa-times"></i>';

            Html5Qrcode.getCameras()

                .then(devices => {

                    if (devices && devices.length) {

                        let cameraId = devices[0].id;

                        const backCamera = devices.find(device =>

                            device.label.toLowerCase().includes('back') ||
                            device.label.toLowerCase().includes('rear')

                        );

                        if (backCamera) {

                            cameraId = backCamera.id;

                        }

                        html5QrCode.start(

                            cameraId,

                            {

                                fps: 15,

                                qrbox: {
                                    width: 250,
                                    height: 120
                                }

                            },

                            async (decodedText) => {

                                searchInput.value = decodedText;

                                if (navigator.vibrate) {
                                    navigator.vibrate(200);
                                }

                                await html5QrCode.stop();

                                scannerRunning = false;

                                readerDiv.style.display = 'none';

                                scannerBtn.innerHTML = '<i class="fas fa-barcode"></i>';

                                // Auto search product and show modal
                                setTimeout(() => {
                                    searchProductByBarcode(decodedText);
                                }, 500);
                            }

                        );

                    }

                })

                .catch(err => {

                    alert('Kamera gagal dibuka');

                    console.log(err);

                });

        });
    </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
