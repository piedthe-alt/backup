<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Retur Barang</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />

    <!-- html5-qrcode -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>

        body {
            background: #f1f5f9;
        }

        .page-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border-radius: 20px;
            padding: 30px;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.2);
        }

        .form-card,
        .table-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .form-card {
            padding: 25px;
        }

        .table thead th {
            background: #111827 !important;
            color: white;
            border: none;
            font-size: 14px;
            padding: 15px;
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 14px;
            font-size: 14px;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .btn-save {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 12px;
        }

        .btn-save:hover {
            opacity: 0.9;
            color: white;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            border-radius: 12px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
        }

        .badge-retur {
            background: #dc2626;
            font-size: 13px;
            padding: 8px 12px;
        }

        .badge-status {
            background: #facc15;
            color: #111827;
            font-size: 12px;
            padding: 8px 12px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            min-height: 48px;
        }

        .empty-state {
            padding: 40px;
            text-align: center;
            color: #64748b;
        }

        .mobile-card {
            display: none;
        }

        #product-select {
            display: none;
            margin-top: 10px;
            max-height: 280px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .product-item {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: 0.2s;
            background: white;
        }

        .product-item:hover {
            background: #f8fafc;
        }

        .product-id {
            font-size: 12px;
            color: #64748b;
        }

        #reader {
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            display: none;
            margin-top: 15px;
        }

        .scanner-box {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 15px;
            margin-top: 10px;
        }

        .selected-product {
            background: #ecfdf5;
            border: 1px solid #10b981;
            padding: 12px;
            border-radius: 12px;
            margin-top: 10px;
            display: none;
        }

        @media (max-width: 768px) {

            .container {
                padding-left: 12px;
                padding-right: 12px;
            }

            .page-header {
                padding: 20px;
                border-radius: 16px;
            }

            .page-header h2 {
                font-size: 1.4rem;
            }

            .form-card {
                padding: 18px;
                border-radius: 16px;
            }

            .table-responsive {
                display: none;
            }

            .mobile-card {
                display: block;
                padding: 15px;
            }

            .return-item {
                background: #f8fafc;
                border-radius: 16px;
                padding: 16px;
                margin-bottom: 14px;
                border: 1px solid #e2e8f0;
            }

            .btn-back {
                width: 100%;
                text-align: center;
            }

            .header-mobile {
                flex-direction: column;
                align-items: stretch !important;
            }

        }

    </style>

</head>

<body>

<div class="container py-4">

    <!-- HEADER -->
    <div class="page-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 header-mobile">

            <div>

                <h2 class="fw-bold mb-2">

                    <i class="fas fa-rotate-left me-2"></i>

                    Retur Barang

                </h2>

                <div class="opacity-75">

                    Kelola barang retur dan status pengambilannya

                </div>

            </div>

            <a href="/" class="btn btn-back px-4 py-2">

                <i class="fas fa-arrow-left me-2"></i>

                Back

            </a>

        </div>

    </div>

    <!-- FORM -->
    <div class="form-card mb-4">

        <h5 class="fw-bold mb-4">

            <i class="fas fa-plus-circle me-2 text-danger"></i>

            Tambah Retur Baru

        </h5>

        <form method="POST" action="/returns/store">

            @csrf

            <div class="row g-3">

                <!-- PRODUK -->
                <div class="col-md-5">

                    <label class="form-label fw-semibold">

                        Cari / Scan Produk

                    </label>

                    <!-- SEARCH -->
                    <input
                        type="text"
                        id="search-product"
                        class="form-control"
                        placeholder="Cari nama / barcode / ID produk..."
                        autocomplete="off"
                    >

                    <!-- HIDDEN INPUT -->
                    <input
                        type="hidden"
                        name="product_id"
                        id="selected-product-id"
                        required
                    >

                    <!-- BUTTON SCAN -->
                    <button
                        type="button"
                        class="btn btn-dark w-100 mt-2"
                        id="start-scan"
                    >

                        <i class="fas fa-barcode me-2"></i>

                        Scan Barcode

                    </button>

                    <!-- CAMERA -->
                    <div class="scanner-box">

                        <div id="reader"></div>

                    </div>

                    <!-- SELECTED -->
                    <div class="selected-product" id="selected-product">

                        <strong>Produk Dipilih:</strong>

                        <div id="selected-product-text"></div>

                    </div>

                    <!-- RESULT -->
                    <div id="product-select">

                        @foreach (DB::table('product')->orderBy('name')->get() as $p)

                            <div
                                class="product-item"
                                data-id="{{ $p->id }}"
                                data-name="{{ strtolower($p->name) }}"
                            >

                                <div class="fw-semibold">

                                    {{ $p->name }}

                                </div>

                                <div class="product-id">

                                    {{ $p->id }}

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

                <!-- QTY -->
                <div class="col-md-2">

                    <label class="form-label fw-semibold">
                        Qty
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        class="form-control"
                        placeholder="Jumlah"
                        required
                    >

                </div>

                <!-- NOTE -->
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Keterangan
                    </label>

                    <input
                        type="text"
                        name="note"
                        class="form-control"
                        placeholder="Contoh: Kemasan rusak"
                    >

                </div>

                <!-- BUTTON -->
                <div class="col-md-2 d-flex align-items-end">

                    <button class="btn btn-save w-100 py-3">

                        <i class="fas fa-save me-2"></i>

                        Simpan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<script>

    const searchInput = document.getElementById('search-product');

    const productSelect = document.getElementById('product-select');

    const productItems = document.querySelectorAll('.product-item');

    const selectedProductId = document.getElementById('selected-product-id');

    const selectedProduct = document.getElementById('selected-product');

    const selectedProductText = document.getElementById('selected-product-text');

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener('input', function () {

        const keyword = this.value.toLowerCase().trim();

        let found = false;

        if (keyword.length > 0) {

            productSelect.style.display = 'block';

        } else {

            productSelect.style.display = 'none';

        }

        productItems.forEach(item => {

            const text = item.innerText.toLowerCase();

            if (text.includes(keyword)) {

                item.style.display = 'block';
                found = true;

            } else {

                item.style.display = 'none';

            }

        });

        if (!found) {

            productSelect.style.display = 'none';

        }

    });

    /*
    |--------------------------------------------------------------------------
    | SELECT PRODUCT
    |--------------------------------------------------------------------------
    */

    productItems.forEach(item => {

        item.addEventListener('click', function () {

            const productId = this.dataset.id;

            const productName = this.querySelector('.fw-semibold').innerText;

            selectedProductId.value = productId;

            searchInput.value = productName;

            selectedProduct.style.display = 'block';

            selectedProductText.innerHTML =
                '<strong>' + productName + '</strong><br>ID: ' + productId;

            productSelect.style.display = 'none';

        });

    });

    /*
    |--------------------------------------------------------------------------
    | BARCODE SCANNER
    |--------------------------------------------------------------------------
    */

    let scannerStarted = false;
    let html5QrCode;

    document.getElementById('start-scan').addEventListener('click', async function () {

        const reader = document.getElementById('reader');

        if (!scannerStarted) {

            reader.style.display = 'block';

            html5QrCode = new Html5Qrcode("reader");

            try {

                await html5QrCode.start(

                    {
                        facingMode: "environment"
                    },

                    {
                        fps: 10,
                        qrbox: 250
                    },

                    function(decodedText) {

                        searchInput.value = decodedText;

                        searchInput.dispatchEvent(new Event('input'));

                        /*
                        |--------------------------------------------------------------------------
                        | AUTO PILIH PRODUK
                        |--------------------------------------------------------------------------
                        */

                        productItems.forEach(item => {

                            const text = item.innerText.toLowerCase();

                            if (text.includes(decodedText.toLowerCase())) {

                                item.click();

                            }

                        });

                        html5QrCode.stop();

                        scannerStarted = false;

                        reader.style.display = 'none';

                    }

                );

                scannerStarted = true;

            } catch (err) {

                alert('Camera gagal dibuka');

            }

        } else {

            await html5QrCode.stop();

            scannerStarted = false;

            reader.style.display = 'none';

        }

    });

</script>

</body>

</html>
