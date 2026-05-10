<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Produk - Stock Manager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary-color: #2563eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-color) !important;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(100px, -100px);
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-box.stock { border-left-color: var(--info-color); }
        .stat-box.masuk { border-left-color: var(--success-color); }
        .stat-box.keluar { border-left-color: var(--warning-color); }

        .stat-box h6 {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stat-box .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .product-card {
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .product-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
            transform: translateY(-8px);
        }

        .product-header-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: space-between;
        }

        .product-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: #1e293b;
            margin-bottom: 0;
            line-height: 1.3;
            flex: 1;
        }

        .copy-btn {
            background: rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.3);
            color: var(--primary-color);
            border-radius: 6px;
            padding: 6px 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .copy-btn:hover {
            background: rgba(37, 99, 235, 0.2);
            border-color: var(--primary-color);
        }

        .copy-btn.copied {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: var(--success-color);
        }

        .copy-btn i {
            font-size: 0.8rem;
        }

        .price-badge {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            white-space: nowrap;
        }

        .product-info {
            display: flex;
            flex-direction: row;
            gap: 8px;
            flex: 1;
            align-items: center;
            flex-wrap: wrap;
            font-size: 0.8rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            min-width: 60px;
            padding: 6px;
            background: #f8fafc;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .info-item-label {
            color: #64748b;
            font-weight: 500;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 4px;
        }

        .info-item-value {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 0.85rem;
        }

        .stock-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .stock-high { background-color: #d1fae5; color: #065f46; }
        .stock-medium { background-color: #fef3c7; color: #92400e; }
        .stock-low { background-color: #fee2e2; color: #991b1b; }

        .search-input {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-action {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 20px;
            border: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .sort-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .sort-buttons .sort-label {
            font-weight: 600;
            color: #1e293b;
            align-self: center;
            white-space: nowrap;
            margin-right: 8px;
            font-size: 0.95rem;
        }

        .sort-btn {
            border-radius: 8px;
            padding: 8px 14px;
            border: 1.5px solid var(--border-color);
            background: white;
            color: #1e293b;
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .sort-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: rgba(37, 99, 235, 0.05);
        }

        .sort-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-primary.btn-action {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
        }

        .btn-success.btn-action {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
        }

        .btn-warning.btn-action {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            color: white;
        }

        .btn-danger.btn-action {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: white;
        }

        .btn-info.btn-action {
            background: linear-gradient(135deg, var(--info-color) 0%, #0891b2 100%);
            color: white;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            border: none;
        }

        .table {
            font-size: 0.95rem;
        }

        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #1e293b;
            border-color: var(--border-color);
        }

        .table td {
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .pagination {
            gap: 8px;
        }

        .pagination .page-link {
            border-radius: 6px;
            border: 1px solid var(--border-color);
            color: var(--primary-color);
            padding: 8px 12px;
            font-weight: 500;
        }

        .pagination .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .btn-action {
                padding: 8px 12px;
                font-size: 0.9rem;
            }

            .product-name {
                font-size: 0.9rem;
            }

            .stat-box .stat-value {
                font-size: 24px;
            }

            .header-section {
                padding: 1.5rem !important;
            }

            .header-content h2 {
                font-size: 1.5rem;
            }

            .product-card-header {
                padding: 10px;
            }

            .card-body {
                padding: 10px !important;
            }

            .price-badge {
                font-size: 0.75rem;
                padding: 4px 8px;
            }

            .product-info {
                gap: 4px;
            }

            .info-item {
                min-width: 50px;
                padding: 4px;
            }

            .info-item-label {
                font-size: 0.6rem;
            }

            .info-item-value {
                font-size: 0.75rem;
            }

            .header-section::before {
                width: 200px;
                height: 200px;
            }

            .search-input {
                font-size: 0.95rem;
                padding: 10px 12px;
            }

            .btn-action {
                font-size: 0.85rem;
                padding: 8px 10px;
            }

            .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .card-body {
                padding: 1rem !important;
            }
        }

        @media (max-width: 576px) {
            .header-content {
                text-align: center;
            }

            .header-content h2 {
                font-size: 1.3rem;
            }

            .header-section {
                padding: 1rem !important;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                font-size: 0.8rem;
                padding: 6px 8px;
            }

            .row.g-3 {
                gap: 0.5rem !important;
            }

            .col-md-6, .col-md-3 {
                padding: 0.25rem;
            }

            .product-info {
                gap: 3px;
            }

            .info-item {
                min-width: 45px;
                padding: 3px;
            }

            .info-item-label {
                font-size: 0.55rem;
            }

            .info-item-value {
                font-size: 0.7rem;
            }

            .product-name {
                font-size: 0.85rem;
            }

            .price-badge {
                font-size: 0.7rem;
                padding: 3px 6px;
            }
        }

        /* Scanner Modal Styles */
        .scanner-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            padding: 20px;
        }

        .scanner-modal.active {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .scanner-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            max-width: 90vw;
            width: 100%;
            max-height: 90vh;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
        }

        .scanner-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .scanner-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .scanner-header .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .scanner-header .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .scanner-body {
            flex: 1;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            min-height: 300px;
        }

        #video-canvas {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
        }

        .scanner-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            width: 250px;
            height: 250px;
            animation: pulse-border 2s infinite;
        }

        @keyframes pulse-border {
            0%, 100% {
                border-color: var(--primary-color);
                box-shadow: 0 0 10px rgba(37, 99, 235, 0.3);
            }
            50% {
                border-color: var(--success-color);
                box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
            }
        }

        .scanner-footer {
            padding: 1rem;
            background: #f8fafc;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .scanner-status {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .scanner-status.scanning {
            color: var(--primary-color);
            font-weight: 600;
        }

        .scanner-status.success {
            color: var(--success-color);
            font-weight: 600;
        }

        .scanner-status.error {
            color: var(--danger-color);
            font-weight: 600;
        }

        .spinner-small {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid var(--border-color);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-right: 6px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

</head>

<body>

    <div class="container-fluid py-4">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="header-section p-4">

                <div class="header-content">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                        <div>

                            <h2 class="mb-2 fw-bold">
                                <i class="fas fa-boxes me-2"></i>Manajemen Stok Produk
                            </h2>

                            <small class="opacity-75">
                                Kelola dan pantau inventory produk secara real-time
                            </small>

                        </div>

                        <div class="d-flex gap-2 flex-wrap">

                            <a href="/ai-dashboard" class="btn btn-action btn-warning">
                                <i class="fas fa-robot"></i> AI Analysis
                            </a>

                            <a href="/sales-chart" class="btn btn-action btn-info">
                                <i class="fas fa-chart-line"></i> Grafik
                            </a>

                            <button class="btn btn-action btn-success" onclick="startScanner()">
                                <i class="fas fa-camera"></i> Scan
                            </button>

                            <a href="/import-db"
                                class="btn btn-action btn-danger"
                                onclick="return confirm('Yakin mau import database?')">

                                <i class="fas fa-database"></i> Import DB

                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <!-- FORM SEARCH -->
                <form method="GET" action="/" class="mb-5">

                    <div class="row g-3 mb-4">

                        <!-- SEARCH -->
                        <div class="col-md-6">

                            <input
                                type="text"
                                name="keyword"
                                id="searchInput"
                                class="form-control search-input"
                                placeholder="🔍 Cari nama produk atau scan barcode..."
                                value="{{ request('keyword') }}"
                                autofocus>

                        </div>

                        <!-- FILTER GROUP -->
                        <div class="col-md-3">

                            <select
                                name="productgroup"
                                class="form-select search-input">

                                <option value="">
                                    📂 Semua Group
                                </option>

                                @foreach ($productgroups as $group)

                                    <option
                                        value="{{ $group->id }}"
                                        {{ request('productgroup') == $group->id ? 'selected' : '' }}>

                                        {{ $group->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <!-- BUTTON -->
                        <div class="col-md-3">

                            <button
                                type="submit"
                                class="btn btn-action btn-primary w-100">

                                <i class="fas fa-search"></i> Cari

                            </button>

                        </div>

                    </div>

                </form>

                <!-- SORTING BUTTONS -->
                <div class="sort-buttons mb-5">
                    <span class="sort-label">📊 Urutkan Berdasarkan:</span>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=stock_terendah" class="sort-btn {{ request('sort') == 'stock_terendah' || !request('sort') ? 'active' : '' }}">
                        <i class="fas fa-arrow-down me-1"></i>Stock Terendah
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=stock_tertinggi" class="sort-btn {{ request('sort') == 'stock_tertinggi' ? 'active' : '' }}">
                        <i class="fas fa-arrow-up me-1"></i>Stock Tertinggi
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=paling_laris" class="sort-btn {{ request('sort') == 'paling_laris' ? 'active' : '' }}">
                        <i class="fas fa-fire me-1"></i>Paling Laris
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=gak_jalan" class="sort-btn {{ request('sort') == 'gak_jalan' ? 'active' : '' }}">
                        <i class="fas fa-snooze me-1"></i>Gak Jalan
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=nama_asc" class="sort-btn {{ request('sort') == 'nama_asc' ? 'active' : '' }}">
                        <i class="fas fa-sort-alpha-down me-1"></i>A-Z
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=nama_desc" class="sort-btn {{ request('sort') == 'nama_desc' ? 'active' : '' }}">
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

                            <div
                                class="product-card shadow-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#productModal{{ $product->id }}">

                                <div class="product-card-header">

                                    <div class="product-header-wrapper">
                                        <h5 class="product-name">
                                            {{ Str::limit($product->name, 35) }}
                                        </h5>
                                        <button
                                            type="button"
                                            class="copy-btn"
                                            onclick="copyProductName(event, '{{ addslashes($product->name) }}')"
                                            title="Copy nama produk">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>

                                </div>

                                <div class="card-body p-3 d-flex flex-column">

                                    <!-- HARGA -->
                                    <div class="price-badge">

                                        Rp {{ number_format($product->salesprice1, 0, ',', '.') }}

                                    </div>

                                    <!-- INFO HORIZONTAL -->
                                    <div class="product-info">

                                        <!-- STOCK -->
                                        <div class="info-item">
                                            <span class="info-item-label">Stock</span>
                                            <span class="stock-status {{ $product->stock > 20 ? 'stock-high' : ($product->stock > 5 ? 'stock-medium' : 'stock-low') }}">
                                                {{ number_format($product->stock, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <!-- MASUK -->
                                        <div class="info-item">
                                            <span class="info-item-label">Masuk</span>
                                            <span class="info-item-value">{{ number_format($product->total_masuk, 0, ',', '.') }}</span>
                                        </div>

                                        <!-- KELUAR -->
                                        <div class="info-item">
                                            <span class="info-item-label">Keluar</span>
                                            <span class="info-item-value">{{ number_format($product->total_keluar, 0, ',', '.') }}</span>
                                        </div>

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

        <div
            class="modal fade"
            id="productModal{{ $product->id }}"
            tabindex="-1">

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

                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">

                        </button>

                    </div>

                    <!-- BODY -->
                    <div class="modal-body p-4">

                        <table class="table">

                            <tbody>

                                <tr>

                                    <th width="250">
                                        <i class="fas fa-tag me-2 text-primary"></i>Group Produk
                                    </th>

                                    <td>

                                        <span class="badge bg-light text-dark">{{ $product->productgroup_name }}</span>

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

                                        <strong class="text-info">{{ number_format($product->stock, 0, ',', '.') }} pcs</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-arrow-down me-2 text-success"></i>Total Barang Masuk
                                    </th>

                                    <td>

                                        <strong class="text-success">{{ number_format($product->total_masuk, 0, ',', '.') }}</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-arrow-up me-2 text-warning"></i>Total Barang Keluar
                                    </th>

                                    <td>

                                        <strong class="text-warning">{{ number_format($product->total_keluar, 0, ',', '.') }}</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-coins me-2 text-danger"></i>Harga Modal
                                    </th>

                                    <td>

                                        <strong class="text-danger">Rp {{ number_format($product->costprice, 0, ',', '.') }}</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-money-bill-wave me-2 text-success"></i>Harga Jual
                                    </th>

                                    <td>

                                        <strong class="text-success">Rp {{ number_format($product->salesprice1, 0, ',', '.') }}</strong>

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        <i class="fas fa-percent me-2 text-primary"></i>Margin
                                    </th>

                                    <td>

                                        @php
                                            $margin = $product->costprice > 0 ? (($product->salesprice1 - $product->costprice) / $product->costprice) * 100 : 0;
                                        @endphp

                                        <strong class="text-primary">{{ number_format($margin, 2, ',', '.') }}%</strong>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

    <!-- SCRIPT -->
    <script>
        // Copy Product Name Function
        function copyProductName(event, productName) {
            event.stopPropagation();

            // Copy to clipboard
            navigator.clipboard.writeText(productName).then(() => {
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
                showCopyNotification(productName);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Gagal mengcopy nama produk');
            });
        }

        // Toast Notification Function
        function showCopyNotification(productName) {
            const notification = document.createElement('div');
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
                Nama produk tercopy: "${productName.substring(0, 30)}${productName.length > 30 ? '...' : ''}"
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

        // Global scanner state
        let scannerState = {
            isRunning: false,
            lastScannedTime: 0,
            debounceTime: 1000
        };

        function startScanner() {
            const scannerModal = document.getElementById('scanner-modal');
            scannerModal.classList.add('active');

            Quagga.init(
                {
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.querySelector('#video-canvas'),
                        constraints: {
                            width: { min: 640 },
                            height: { min: 480 },
                            facingMode: "environment"
                        }
                    },
                    decoder: {
                        workers: 2,
                        debug: false,
                        readers: [
                            'code_128_reader',
                            'ean_reader',
                            'ean_8_reader',
                            'code_39_reader',
                            'code_39_vin_reader',
                            'codabar_reader',
                            'upc_reader',
                            'upc_e_reader',
                            'i2of5_reader',
                            'qr_code_reader'
                        ]
                    },
                    locator: {
                        halfSample: true
                    }
                },
                function(err) {
                    if (err) {
                        console.error('Error initializing Quagga:', err);
                        updateScannerStatus('error', '❌ Gagal membuka kamera. Periksa izin akses.');
                        return;
                    }

                    console.log('Quagga initialized');
                    Quagga.start();
                    scannerState.isRunning = true;
                    updateScannerStatus('scanning', '🔍 Scanning...');
                }
            );

            Quagga.onDetected(onScanSuccess);
            Quagga.onProcessed(onProcessed);
        }

        function onProcessed(result) {
            const drawingCtx = Quagga.canvas.ctx.overlay;
            const drawingCanvas = Quagga.canvas.canvas;

            if (result === null) {
                return;
            }

            if (result.boxes) {
                drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute('width')), parseInt(drawingCanvas.getAttribute('height')));
                result.boxes.filter(box => box !== result.box).forEach(box => {
                    Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx);
                });
            }

            if (result.box) {
                Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx);
            }

            if (result.codeResult && result.codeResult.code) {
                Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'green', lineWidth: 3 });
            }
        }

        function onScanSuccess(result) {
            const currentTime = Date.now();

            // Debounce: hindari scan duplicate dalam 1 detik
            if (currentTime - scannerState.lastScannedTime < scannerState.debounceTime) {
                return;
            }

            if (result.codeResult && result.codeResult.code) {
                const scannedValue = result.codeResult.code;

                console.log('Scanned:', scannedValue, 'Format:', result.codeResult.format);

                // Update input dan trigger search
                document.getElementById('searchInput').value = scannedValue;

                // Update status
                updateScannerStatus('success', `✅ Berhasil scan: ${scannedValue}`);
                scannerState.lastScannedTime = currentTime;

                // Auto submit form setelah 1 detik
                setTimeout(() => {
                    document.querySelector('form[method="GET"]').submit();
                }, 1000);
            }
        }

        function updateScannerStatus(type, message) {
            const statusEl = document.getElementById('scanner-status');
            statusEl.className = 'scanner-status ' + type;
            statusEl.textContent = message;
        }

        function stopScanner() {
            try {
                Quagga.stop();
                Quagga.offDetected(onScanSuccess);
                Quagga.offProcessed(onProcessed);
                scannerState.isRunning = false;

                const scannerModal = document.getElementById('scanner-modal');
                scannerModal.classList.remove('active');
            } catch (err) {
                console.error('Error stopping scanner:', err);
                const scannerModal = document.getElementById('scanner-modal');
                scannerModal.classList.remove('active');
            }
        }

        // Close modal when clicking outside
        document.getElementById('scanner-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                stopScanner();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('scanner-modal').classList.contains('active')) {
                stopScanner();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
