<!-- HEADER -->
<div class="header-section p-4">

    <div class="header-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

            <div>

                <h2 class="mb-2 fw-bold">
                    <i class="fas fa-boxes me-2"></i>Manajemen Stock Produk
                </h2>

                <small class="opacity-75">
                    Kelola dan pantau inventory produk secara real-time
                </small>

            </div>

            <div class="d-flex gap-2 flex-wrap">

                <a href="/ai-dashboard" class="btn btn-action btn-warning">
                    <i class="fas fa-robot"></i> AI Analysis
                </a>

                {{-- <a href="/sales-chart" class="btn btn-action btn-info">
                    <i class="fas fa-chart-line"></i> Grafik
                </a> --}}

                <!-- TOMBOL RETUR -->
                <a href="/return" class="btn btn-action btn-secondary">
                    <i class="fas fa-undo"></i> Retur
                </a>

                <!-- TOMBOL PESANAN SHOPEE -->
                <a href="/pesanan-shopee" class="btn btn-action btn-info">
                    <i class="fas fa-shopping-bag"></i> Pesanan Shopee
                </a>

                {{-- <button class="btn btn-action btn-success" onclick="startScanner()">
                    <i class="fas fa-camera"></i> Scan
                </button> --}}

                <a href="/import-db" class="btn btn-action btn-danger"
                    onclick="return confirm('Yakin mau import database?')">

                    <i class="fas fa-database"></i> Import DB

                </a>

                <button class="btn btn-action btn-primary cart-btn-wrapper" onclick="openCartModal()"
                    style="position: relative;">

                    <i class="fas fa-shopping-cart"></i> Cart

                    <span class="cart-badge" id="cart-count" style="display: none;">
                        0
                    </span>

                </button>

            </div>

        </div>

    </div>

</div>
