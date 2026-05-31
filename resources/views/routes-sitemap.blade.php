<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap Portal Pengembang - Stock Manage</title>

    {{-- Bootstrap & Fonts --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, #0f172a 0%, #020617 100%);
            min-height: 100vh;
            color: #f8fafc;
            padding-bottom: 3rem;
        }

        .text-muted {
            color: #94a3b8 !important;
        }

        small.text-muted {
            color: #cbd5e1 !important;
            opacity: 0.85;
        }

        .route-card p.text-muted {
            color: #94a3b8 !important;
            opacity: 0.9;
        }

        .code-font {
            font-family: 'Fira Code', monospace;
        }

        .header-glow {
            border-bottom: 1px solid rgba(99, 102, 241, 0.2);
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
        }

        .dev-badge {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #818cf8;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 1px;
        }

        .route-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(8px);
            height: 100%;
        }

        .route-card:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 15px 30px -10px rgba(99, 102, 241, 0.2);
            background: rgba(30, 41, 59, 0.6);
        }

        .route-badge {
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #38bdf8;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: space-between;
        }

        .btn-open {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-open:hover {
            opacity: 0.9;
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .category-title {
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-left: 3px solid #6366f1;
            padding-left: 10px;
        }

        .copy-icon {
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .copy-icon:hover {
            color: #818cf8;
        }

        .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header-glow sticky-top py-3 mb-5">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-terminal text-indigo fs-3 text-indigo" style="color: #818cf8;"></i>
                <div>
                    <h4 class="mb-0 fw-bold">Sitemap Portal Pengembang</h4>
                    <small class="text-muted">Peta rute navigasi dan dashboard sistem</small>
                </div>
            </div>
            <span class="dev-badge"><i class="fas fa-shield-alt me-1"></i> PRIVATE ACCESS</span>
        </div>
    </div>

    <div class="container">

        {{-- KATEGORI 1: UTAMA & DASHBOARD --}}
        <div class="row mb-5">
            <div class="col-12 mb-3">
                <h5 class="category-title">Dashboard Utama & Intelijen</h5>
            </div>

            <!-- Dashboard Produk -->
            <div class="col-md-4 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Manajemen Stok Utama</h5>
                        <p class="text-muted small mb-3">Daftar produk, pemantauan kuantitas stock, import database, pencarian instan, dan kasir cart belanja.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/')" title="Salin rute"></i>
                        </div>
                        <a href="/" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>

            <!-- AI Analysis -->
            <div class="col-md-4 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h5 class="fw-bold mb-2">AI Analysis Dashboard</h5>
                        <p class="text-muted small mb-3">Analisis data stock dan pola belanja produk menggunakan algoritma AI Gemini untuk prediksi stok habis.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/ai-dashboard</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/ai-dashboard')" title="Salin rute"></i>
                        </div>
                        <a href="/ai-dashboard" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>

            <!-- Retail Intelligence -->
            <div class="col-md-4 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-info bg-opacity-10 text-info">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Retail Intelligence</h5>
                        <p class="text-muted small mb-3">Dashboard analitik bisnis tingkat lanjut yang menyajikan data performa toko dan ringkasan keuangan cerdas.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/retail-intelligence</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/retail-intelligence')" title="Salin rute"></i>
                        </div>
                        <a href="/retail-intelligence" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KATEGORI 2: OPERASIONAL & FITUR BARU --}}
        <div class="row mb-5">
            <div class="col-12 mb-3">
                <h5 class="category-title">Operasional & Fitur Cetak</h5>
            </div>

            <!-- Cetak Barcode -->
            <div class="col-md-4 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-success bg-opacity-10 text-success">
                            <i class="fas fa-barcode"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Cetak Barcode Label</h5>
                        <p class="text-muted small mb-3">Daftar kelola cetak label harga ber-barcode. Menyimpan list cetak ke database dan terintegrasi cetak PDF.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/products/print-barcode</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/products/print-barcode')" title="Salin rute"></i>
                        </div>
                        <a href="/products/print-barcode" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>

            <!-- Retur Barang -->
            <div class="col-md-4 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-secondary bg-opacity-10 text-secondary">
                            <i class="fas fa-undo"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Retur Barang</h5>
                        <p class="text-muted small mb-3">Pencatatan barang retur rusak atau kadaluwarsa dari pelanggan beserta pembaruan status pengembalian.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/return</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/return')" title="Salin rute"></i>
                        </div>
                        <a href="/return" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>

            <!-- Shopee Integration -->
            <div class="col-md-4 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-danger bg-opacity-10 text-danger" style="color: #ea580c !important;">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Pesanan Shopee</h5>
                        <p class="text-muted small mb-3">Integrasi pesanan toko online Shopee, sinkronisasi pesanan masuk, dan detail produk yang terpesan.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/pesanan-shopee</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/pesanan-shopee')" title="Salin rute"></i>
                        </div>
                        <a href="/pesanan-shopee" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KATEGORI 3: ANALIS LAPORAN --}}
        <div class="row mb-5">
            <div class="col-12 mb-3">
                <h5 class="category-title">Analisis Laporan Penjualan</h5>
            </div>

            <!-- Analisis Jam Ramai -->
            <div class="col-md-4 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-primary bg-opacity-10 text-primary" style="color: #a855f7 !important;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Analisis Jam Ramai</h5>
                        <p class="text-muted small mb-3">Analisis waktu transaksi teramai per jam untuk membantu optimalisasi jadwal operasional dan staf.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/sales-hour-analysis</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/sales-hour-analysis')" title="Salin rute"></i>
                        </div>
                        <a href="/sales-hour-analysis" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>

            <!-- Market Basket Analysis -->
            <div class="col-md-4 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-success bg-opacity-10 text-success" style="color: #14b8a6 !important;">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Market Basket Analysis</h5>
                        <p class="text-muted small mb-3">Melihat produk-produk yang sering dibeli secara bersamaan dalam satu transaksi (analisis afinitas).</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/market-basket-analysis</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/market-basket-analysis')" title="Salin rute"></i>
                        </div>
                        <a href="/market-basket-analysis" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>

            <!-- Grafik Penjualan -->
            <div class="col-md-4 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Grafik Penjualan</h5>
                        <p class="text-muted small mb-3">Grafik visual ringkasan pendapatan, omset kotor, pengeluaran modal, dan fluktuasi laba bulanan.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/sales-chart</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/sales-chart')" title="Salin rute"></i>
                        </div>
                        <a href="/sales-chart" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KATEGORI 4: DEMO TOKO ONLINE --}}
        <div class="row">
            <div class="col-12 mb-3">
                <h5 class="category-title">Demo Tampilan Toko Online</h5>
            </div>

            <!-- Shop Interface -->
            <div class="col-md-6 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-success bg-opacity-10 text-success">
                            <i class="fas fa-store"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Tampilan Belanja Pelanggan</h5>
                        <p class="text-muted small mb-3">Antarmuka demo toko online untuk simulasi pemesanan oleh pelanggan, lengkap dengan struk belanja invoice.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/shop</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/shop')" title="Salin rute"></i>
                        </div>
                        <a href="/shop" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>

            <!-- Shop History -->
            <div class="col-md-6 mb-4">
                <div class="route-card p-4 d-flex flex-col justify-content-between">
                    <div>
                        <div class="icon-wrapper bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-history"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Histori Belanja Online</h5>
                        <p class="text-muted small mb-3">Daftar riwayat pemesanan yang pernah disimulasikan dari halaman belanja pelanggan sebelumnya.</p>
                    </div>
                    <div>
                        <div class="route-badge code-font mb-3">
                            <span>/shop/history</span>
                            <i class="far fa-copy copy-icon" onclick="copyRoute('/shop/history')" title="Salin rute"></i>
                        </div>
                        <a href="/shop/history" class="btn-open">
                            <i class="fas fa-external-link-alt"></i> Buka Halaman
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function copyRoute(route) {
            const fullUrl = window.location.origin + route;
            navigator.clipboard.writeText(fullUrl).then(() => {
                alert('✓ Link rute berhasil disalin: ' + fullUrl);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
</body>

</html>
