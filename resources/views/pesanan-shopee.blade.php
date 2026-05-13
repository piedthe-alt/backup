<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Shopee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #0ea5e9;
            --border-color: #e2e8f0;
            --light-bg: #f8fafc;
        }

        body {
            background: linear-gradient(135deg, #f3f4f6 0%, #e0f2fe 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
        }

        .header-section h1 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header-section small {
            opacity: 0.9;
        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .form-section h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-item {
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .product-item:hover {
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
            transform: translateY(-2px);
        }

        .product-select {
            flex: 1;
            margin-right: 15px;
        }

        .product-qty {
            display: flex;
            gap: 8px;
            align-items: center;
            min-width: 150px;
        }

        .qty-input {
            width: 80px;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            text-align: center;
        }

        .btn-remove-product {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-remove-product:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .pesanan-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .pesanan-card:hover {
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.15);
            transform: translateY(-4px);
        }

        .pesanan-card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .pesanan-card-header h6 {
            margin: 0;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-belum-dikirim {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }

        .status-dikirim {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success-color);
        }

        .pesanan-card-body {
            padding: 20px;
        }

        .pesanan-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-item {
            background: var(--light-bg);
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .info-label {
            font-size: 0.85rem;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .info-value {
            font-size: 1.1rem;
            color: #1e293b;
            font-weight: 600;
        }

        .jenis-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 5px;
        }

        .jenis-instant {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .jenis-spx {
            background: rgba(168, 85, 247, 0.1);
            color: #a855f7;
        }

        .jenis-jne {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .jenis-jnt {
            background: rgba(249, 115, 22, 0.1);
            color: #f97316;
        }

        .btn-action {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
        }

        .btn-primary-action {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
        }

        .btn-primary-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .btn-success-action {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
        }

        .btn-success-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .btn-sm-action {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
        }

        .modal-title {
            font-weight: 600;
        }

        .product-list-item {
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-list-item-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .product-list-item-price {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 4px;
        }

        .pesanan-detail-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .detail-summary {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .summary-row:last-child {
            border-top: 2px solid rgba(37, 99, 235, 0.2);
            padding-top: 8px;
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .pesanan-info {
                grid-template-columns: 1fr;
            }

            .product-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .product-qty {
                width: 100%;
                margin-top: 10px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }

        .form-group-inline {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .form-group-inline>div {
            flex: 1;
            min-width: 200px;
        }

        .input-error {
            border-color: var(--danger-color) !important;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .add-button-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn-add-product {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-add-product:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-submit-pesanan {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit-pesanan:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-submit-pesanan:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .products-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <!-- HEADER -->
        <div class="header-section p-4">
            <div>
                <h1 class="mb-2">
                    <i class="fas fa-shopping-bag me-2"></i>Pesanan Shopee
                </h1>
                <small>Kelola pesanan Shopee dengan mudah</small>
            </div>
        </div>

        <!-- FORM CREATE PESANAN -->
        <div class="form-section">
            <h5>
                <i class="fas fa-plus-circle"></i> Buat Pesanan Baru
            </h5>

            <form id="pesananForm">
                <div id="productsContainer">
                    <!-- Product items will be added here -->
                </div>

                <div class="add-button-container">
                    <button type="button" class="btn-add-product" onclick="addProductRow()">
                        <i class="fas fa-plus me-2"></i>Tambah Produk
                    </button>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-600">Nama Pembeli</label>
                        <input type="text" id="namaPembeli" class="form-control"
                            placeholder="Nama pembeli (opsional)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-600">Jenis Pengiriman</label>
                        <select id="jenis" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Instant">Instant</option>
                            <option value="SPX">SPX</option>
                            <option value="JNE">JNE</option>
                            <option value="JNT">JNT</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-600">Alamat</label>
                    <textarea id="alamat" class="form-control" rows="3" placeholder="Alamat pengiriman (opsional)"></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-600">Catatan</label>
                    <textarea id="catatan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                </div>

                <button type="submit" class="btn-submit-pesanan">
                    <i class="fas fa-check me-2"></i>Simpan Pesanan
                </button>
            </form>
        </div>

        <!-- PESANAN LIST -->
        <div>
            <h5 class="mb-4" style="color: var(--primary-color); font-weight: 600;">
                <i class="fas fa-list me-2"></i>Daftar Pesanan
            </h5>

            <div id="pesananContainer">
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5 class="text-muted mt-3">Belum ada pesanan</h5>
                    <p class="text-muted small">Buat pesanan baru dengan mengisi form di atas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAIL MODAL -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle me-2"></i>Detail Pesanan
                        </h5>
                        <small class="text-white-50">Informasi lengkap pesanan</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="detailContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="updateStatusBtn" onclick="updateStatus()">
                        <i class="fas fa-check me-2"></i>Atur Pick up
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let products = [];
        let currentPesananId = null;
        let productRowCount = 0;

        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            renderPesanan();
        });

        // Load products from API
        async function loadProducts() {
            try {
                const response = await fetch('/api/products');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                products = await response.json();
                console.log('Products loaded:', products);
            } catch (error) {
                console.error('Error loading products:', error);
                alert('Gagal memuat daftar produk');
            }
        }

        // Load pesanan list
        async function loadPesanan() {
            renderPesanan();
        }

        // Add new product row
        function addProductRow() {

            productRowCount++;

            const container =
                document.getElementById('productsContainer');

            const row = document.createElement('div');

            row.className = 'product-item';

            row.id = `product-row-${productRowCount}`;

            row.innerHTML = `

        <div class="product-select position-relative">

            <input
                type="text"
                class="form-control product-search-input"
                placeholder="Cari produk..."
                onkeyup="searchProduct(this, ${productRowCount})"
                autocomplete="off"
            >

            <input
                type="hidden"
                class="selected-product-id"
            >

            <div
                class="product-search-result"
                id="search-result-${productRowCount}"
                style="
                    position:absolute;
                    top:100%;
                    left:0;
                    right:0;
                    background:white;
                    border:1px solid #ddd;
                    border-radius:8px;
                    z-index:9999;
                    max-height:250px;
                    overflow-y:auto;
                    display:none;
                    box-shadow:0 8px 20px rgba(0,0,0,0.1);
                "
            ></div>

        </div>

        <div class="product-qty">

            <input
                type="number"
                class="qty-input"
                placeholder="Jumlah"
                min="1"
                value="1"
                required
            >

            <button
                type="button"
                class="btn-remove-product"
                onclick="removeProductRow(${productRowCount})"
            >
                <i class="fas fa-times"></i>
            </button>

        </div>
    `;

            container.appendChild(row);
        }

        function searchProduct(input, rowId) {

            const keyword =
                input.value.toLowerCase().trim();

            const resultBox =
                document.getElementById(`search-result-${rowId}`);

            if (!keyword) {

                resultBox.style.display = 'none';

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | FILTER PRODUCTS
            |--------------------------------------------------------------------------
            */

            const filtered = products.filter(product => {

                return (

                    product.name
                    .toLowerCase()
                    .includes(keyword)

                    ||

                    String(product.id)
                    .includes(keyword)
                );
            });

            /*
            |--------------------------------------------------------------------------
            | LIMIT
            |--------------------------------------------------------------------------
            */

            const limited =
                filtered.slice(0, 30);

            /*
            |--------------------------------------------------------------------------
            | EMPTY
            |--------------------------------------------------------------------------
            */

            if (limited.length === 0) {

                resultBox.innerHTML = `
            <div style="
                padding:12px;
                color:#64748b;
            ">
                Produk tidak ditemukan
            </div>
        `;

                resultBox.style.display = 'block';

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | RENDER
            |--------------------------------------------------------------------------
            */

            resultBox.innerHTML = limited.map(product => `

        <div
            onclick="
                selectProduct(
                    ${rowId},
                    '${product.id}',
                    \`${product.name.replace(/`/g, '')}\`
                        )
                    "

                    style="
                        padding:12px;
                        cursor:pointer;
                        border-bottom:1px solid #eee;
                        transition:0.2s;
                    "

                    onmouseover="
                        this.style.background='#f1f5f9'
                    "

                    onmouseout="
                        this.style.background='white'
                    "
                >

                    <div style="
                        font-weight:600;
                    ">
                        ${product.name}
                    </div>

                    <div style="
                        font-size:13px;
                        color:#64748b;
                    ">
                        ID: ${product.id}
                        |
                        Stock: ${product.stock}
                        |
                        Rp ${numberFormat(product.salesprice1)}
                    </div>

                </div>

            `).join('');

    resultBox.style.display = 'block';
}

function selectProduct(rowId, productId, productName) {

    const row =
        document.getElementById(`
                    product - row - $ {
                        rowId
                    }
                    `);

    /*
    |--------------------------------------------------------------------------
    | INPUT TEXT
    |--------------------------------------------------------------------------
    */

    row.querySelector('.product-search-input')
        .value = productName;

    /*
    |--------------------------------------------------------------------------
    | HIDDEN ID
    |--------------------------------------------------------------------------
    */

    row.querySelector('.selected-product-id')
        .value = productId;

    /*
    |--------------------------------------------------------------------------
    | HIDE RESULT
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        `
                    search - result - $ {
                        rowId
                    }
                    `
    ).style.display = 'none';
}

document.addEventListener('click', function(e) {

    if (!e.target.closest('.product-select')) {

        document
            .querySelectorAll('.product-search-result')

            .forEach(el => {

                el.style.display = 'none';
            });
    }
});

        // Remove product row
        function removeProductRow(id) {
            const row = document.getElementById(`
                    product - row - $ {
                        id
                    }
                    `);
            if (row) {
                row.remove();
            }
        }

        // Form submission
        document.getElementById('pesananForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const productRows = document.querySelectorAll('#productsContainer .product-item');
            if (productRows.length === 0) {
                alert('Tambahkan minimal 1 produk');
                return;
            }

            const idProduk = [];
            const jumlahProduk = [];

            productRows.forEach(row => {
                const select =
    row.querySelector('.selected-product-id');
                const qty = row.querySelector('.qty-input');

                if (select.value && qty.value) {
                    idProduk.push(parseInt(select.value));
                    jumlahProduk.push(parseInt(qty.value));
                }
            });

            if (idProduk.length === 0) {
                alert('Pilih produk dan jumlah dengan benar');
                return;
            }

            const jenis = document.getElementById('jenis').value;
            if (!jenis) {
                alert('Pilih jenis pengiriman');
                return;
            }

            try {
                const response = await fetch('/pesanan-shopee/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_produk: idProduk,
                        jumlah_produk: jumlahProduk,
                        jenis: jenis,
                        nama_pembeli: document.getElementById('namaPembeli').value || null,
                        alamat: document.getElementById('alamat').value || null,
                        catatan: document.getElementById('catatan').value || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Pesanan berhasil dibuat');
                    document.getElementById('pesananForm').reset();
                    document.getElementById('productsContainer').innerHTML = '';
                    productRowCount = 0;
                    // Refresh pesanan list tanpa reload halaman
                    loadPesanan();
                } else {
                    alert('Error: ' + (data.message || 'Gagal membuat pesanan'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        });

        // Show detail modal
        async function showDetail(pesananId) {
            currentPesananId = pesananId;
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));

            try {
                const response = await fetch(` / pesanan - shopee / detail / $ {
                        pesananId
                    }
                    `);
                const data = await response.json();

                const pesanan = data.pesanan;
                const produkDetails = data.produk_details;

                let html = ` <
                    div class = "pesanan-detail-body" >
                    <
                    h6 class = "mb-3" > Informasi Pesanan < /h6> <
                    div class = "info-item mb-3" >
                    <
                    div class = "info-label" > ID Pesanan < /div> <
                    div class = "info-value" > #$ {
                        pesanan.id_pesanan
                    } < /div> < /
                    div > <
                    div class = "row" >
                    <
                    div class = "col-md-6" >
                    <
                    div class = "info-item mb-3" >
                    <
                    div class = "info-label" > Nama Pembeli < /div> <
                    div class = "info-value" > $ {
                        pesanan.nama_pembeli || '-'
                    } < /div> < /
                    div > <
                    /div> <
                    div class = "col-md-6" >
                    <
                    div class = "info-item mb-3" >
                    <
                    div class = "info-label" > Jenis Pengiriman < /div> <
                    div class = "info-value" >
                    <
                    span class = "jenis-badge jenis-${pesanan.jenis.toLowerCase()}" > $ {
                        pesanan.jenis
                    } < /span> < /
                    div > <
                    /div> < /
                    div > <
                    /div> <
                    div class = "info-item mb-3" >
                    <
                    div class = "info-label" > Status < /div> <
                    div class = "info-value" >
                    <
                    span class = "status-badge status-${pesanan.status === 'DIKIRIM' ? 'dikirim' : 'belum-dikirim'}" >
                    $ {
                        pesanan.status
                    } <
                    /span> < /
                    div > <
                    /div>
                    $ {
                        pesanan.alamat ? `
                                    <div class="info-item mb-3">
                                        <div class="info-label">Alamat</div>
                                        <div class="info-value">${pesanan.alamat}</div>
                                    </div>
                                ` : ''
                    }
                    $ {
                        pesanan.catatan ? `
                                    <div class="info-item mb-3">
                                        <div class="info-label">Catatan</div>
                                        <div class="info-value">${pesanan.catatan}</div>
                                    </div>
                                ` : ''
                    }

                    <
                    hr class = "my-4" >
                    <
                    h6 class = "mb-3" > Produk yang dipesan < /h6>
                    $ {
                        produkDetails.map(prod => `
                                    <div class="product-list-item">
                                        <div>
                                            <div class="product-list-item-name">${prod.name}</div>
                                            <div class="product-list-item-price">Rp ${numberFormat(prod.price)}</div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-weight: 600;">${prod.quantity} x</div>
                                            <div style="font-size: 0.9rem; color: #64748b;">Rp ${numberFormat(prod.subtotal)}</div>
                                        </div>
                                    </div>
                                `).join('')
                    }

                    <
                    div class = "detail-summary" >
                    <
                    div class = "summary-row" >
                    <
                    span > Total Harga Jual: < /span> <
                    span > Rp $ {
                        numberFormat(pesanan.total_harga_jual)
                    } < /span> < /
                    div > <
                    /div> < /
                    div >
                    `;

                document.getElementById('detailContent').innerHTML = html;

                // Show/hide update status button based on current status
                const updateBtn = document.getElementById('updateStatusBtn');
                if (pesanan.status === 'DIKIRIM') {
                    updateBtn.style.display = 'none';
                } else {
                    updateBtn.style.display = 'block';
                }

                modal.show();
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat detail pesanan');
            }
        }

        // Update status to DIKIRIM
        async function updateStatus() {
            if (!currentPesananId) return;

            try {
                const response = await fetch(` / pesanan - shopee / update - status / $ {
                        currentPesananId
                    }
                    `, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            alert('Status pesanan diperbarui menjadi DIKIRIM');
                            bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
                            // Refresh pesanan list tanpa reload halaman
                            loadPesanan();
                        } else {
                            alert('Gagal memperbarui status');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    }
                }

                // Format number to Indonesian format
                function numberFormat(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                }

                // Update product price display
                function updateProductPrice(rowId) {
                    // Implementation for price display if needed
                }
    </script>

    <!-- Display pesanan dynamically -->
    <script>
        async function renderPesanan() {

            try {

                const response = await fetch('/api/pesanan-shopee');

                const pesanans = await response.json();

                const container =
                    document.getElementById('pesananContainer');

                if (pesanans.length === 0) {

                    container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5 class="text-muted mt-3">
                        Belum ada pesanan
                    </h5>
                    <p class="text-muted small">
                        Buat pesanan baru
                    </p>
                </div>
            `;

                    return;
                }

                let html = '';

                pesanans.forEach(pesanan => {

                    /*
                    |--------------------------------------------------------------------------
                    | FIX JSON STRING
                    |--------------------------------------------------------------------------
                    */

                    if (typeof pesanan.id_produk === 'string') {

                        pesanan.id_produk =
                            JSON.parse(pesanan.id_produk);
                    }

                    if (typeof pesanan.jumlah_produk === 'string') {

                        pesanan.jumlah_produk =
                            JSON.parse(pesanan.jumlah_produk);
                    }

                    const jenisBadgeClass =
                        `jenis-${pesanan.jenis.toLowerCase()}`;

                    const statusBadgeClass =
                        pesanan.status === 'DIKIRIM' ?
                        'status-dikirim' :
                        'status-belum-dikirim';

                    html += `
                <div class="pesanan-card"
                    onclick="showDetail(${pesanan.id_pesanan})">

                    <div class="pesanan-card-header">

                        <div>

                            <h6>#${pesanan.id_pesanan}</h6>

                            <small>
                                ${new Date(pesanan.created_at)
                                    .toLocaleDateString(
                                        'id-ID',
                                        {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        }
                                    )}
                            </small>

                        </div>

                        <span class="status-badge ${statusBadgeClass}">
                            ${pesanan.status}
                        </span>

                    </div>

                    <div class="pesanan-card-body">

                        <div class="pesanan-info">

                            <div class="info-item">

                                <div class="info-label">
                                    Jumlah Produk
                                </div>

                                <div class="info-value">
                                    ${pesanan.id_produk.length} item
                                </div>

                            </div>

                            <div class="info-item">

                                <div class="info-label">
                                    Jenis
                                </div>

                                <div class="info-value">

                                    <span class="jenis-badge ${jenisBadgeClass}">
                                        ${pesanan.jenis}
                                    </span>

                                </div>

                            </div>

                            <div class="info-item">

                                <div class="info-label">
                                    Total Harga
                                </div>

                                <div class="info-value"
                                    style="color: var(--primary-color);">

                                    Rp ${numberFormat(
                                        pesanan.total_harga_jual
                                    )}

                                </div>

                            </div>

                        </div>

                        ${pesanan.nama_pembeli
                            ? `
                                        <div style="
                                            margin-top:10px;
                                            padding:10px;
                                            background:var(--light-bg);
                                            border-radius:6px;
                                        ">
                                            <strong>Pembeli:</strong>
                                            ${pesanan.nama_pembeli}
                                        </div>
                                        `
                            : ''
                        }

                    </div>

                </div>
            `;
                });

                container.innerHTML = html;

            } catch (error) {

                console.error(error);
            }
        }
    </script>
</body>

</html>
