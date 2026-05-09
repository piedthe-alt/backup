<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Produk - Stock Manager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/html5-qrcode"></script>

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
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .product-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
            transform: translateY(-8px);
        }

        .product-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .price-badge {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            display: inline-block;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 16px;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            font-weight: 700;
            color: var(--primary-color);
        }

        .stock-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
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
                font-size: 1rem;
            }

            .stat-box .stat-value {
                font-size: 24px;
            }
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

                <!-- QR READER -->
                <div
                    id="reader"
                    class="mb-4 d-none"
                    style="width:100%; max-width:400px;">

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

                                    <h5 class="product-name mb-0">

                                        {{ $product->name }}

                                    </h5>

                                </div>

                                <div class="card-body p-4 d-flex flex-column">

                                    <!-- HARGA -->
                                    <div class="mb-3">

                                        <span class="price-badge">

                                            Rp {{ number_format($product->salesprice1, 0, ',', '.') }}

                                        </span>

                                    </div>

                                    <!-- INFO -->
                                    <div class="product-info">

                                        <div class="info-row">

                                            <span class="info-label">

                                                <i class="fas fa-cubes me-2"></i>Stock
                                            </span>

                                            <span class="stock-status {{ $product->stock > 20 ? 'stock-high' : ($product->stock > 5 ? 'stock-medium' : 'stock-low') }}">

                                                {{ number_format($product->stock, 0, ',', '.') }} pcs

                                            </span>

                                        </div>

                                        <div class="info-row">

                                            <span class="info-label">

                                                <i class="fas fa-arrow-down me-2 text-success"></i>Masuk
                                            </span>

                                            <span class="info-value">

                                                {{ number_format($product->total_masuk, 0, ',', '.') }}

                                            </span>

                                        </div>

                                        <div class="info-row">

                                            <span class="info-label">

                                                <i class="fas fa-arrow-up me-2 text-warning"></i>Keluar
                                            </span>

                                            <span class="info-value">

                                                {{ number_format($product->total_keluar, 0, ',', '.') }}

                                            </span>

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

        function startScanner() {

            const html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(

                {
                    facingMode: "environment"
                },

                {
                    fps: 10,
                    qrbox: 250
                },

                function(decodedText) {

                    document.getElementById('searchInput').value = decodedText;

                    html5QrCode.stop();

                }

            ).catch(err => {

                console.log(err);

                alert('Tidak dapat membuka kamera');

            });

        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
