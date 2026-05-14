<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Retur Barang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

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
            height: 260px;
            overflow-y: scroll;
            -webkit-overflow-scrolling: touch;
            font-size: 16px;
        }

        #product-select option {
            padding: 12px;
            white-space: normal;
        }

        #reader {
            width: 100%;
            display: none;
            overflow: hidden;
            border-radius: 14px;
            margin-bottom: 12px;
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

            .return-title {
                font-weight: 700;
                font-size: 15px;
                margin-bottom: 10px;
                color: #111827;
            }

            .return-group {
                display: inline-block;
                background: #64748b;
                color: white;
                font-size: 11px;
                padding: 5px 10px;
                border-radius: 999px;
                margin-bottom: 12px;
            }

            .return-detail {
                margin-bottom: 8px;
                font-size: 14px;
            }

            .btn-mobile {
                width: 100%;
                margin-top: 10px;
                border-radius: 12px;
                font-weight: 600;
            }

            .btn-back {
                width: 100%;
                text-align: center;
            }

            .header-mobile {
                flex-direction: column;
                align-items: stretch !important;
            }

            #product-select {
                height: 220px;
                overflow-y: scroll;
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
                            Cari Produk / Scan Barcode
                        </label>

                        <div class="d-flex gap-2 mb-2">

                            <input type="text"
                                id="search-product"
                                class="form-control"
                                placeholder="Cari nama / barcode / id produk...">

                            <button type="button"
                                id="start-scanner"
                                class="btn btn-dark">

                                <i class="fas fa-barcode"></i>

                            </button>

                        </div>

                        <!-- SCANNER -->
                        <div id="reader"></div>

                        <select name="product_id"
                            id="product-select"
                            class="form-select"
                            size="8"
                            required>

                            <option value="">
                                Pilih Produk
                            </option>

                            @foreach (DB::table('product')->orderBy('name')->get() as $p)

                                <option value="{{ $p->id }}">

                                    {{ $p->id }} - {{ $p->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- QTY -->
                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Qty
                        </label>

                        <input type="number"
                            name="quantity"
                            class="form-control"
                            placeholder="Jumlah"
                            required>

                    </div>

                    <!-- NOTE -->
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Keterangan
                        </label>

                        <input type="text"
                            name="note"
                            class="form-control"
                            placeholder="Contoh: Kemasan rusak">

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

        <!-- TABLE -->
        <div class="table-card">

            <div class="p-4 border-bottom">

                <h5 class="fw-bold mb-0">

                    <i class="fas fa-table me-2 text-danger"></i>

                    Data Barang Retur

                </h5>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th>Group</th>
                            <th>Qty Retur</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th width="160">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($returns as $r)

                            @if (strtoupper(trim($r->status)) != 'SUDAH_DIAMBIL')

                                <tr>

                                    <td>
                                        <strong>{{ $r->product_id_view }}</strong>
                                    </td>

                                    <td>{{ $r->product_name }}</td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $r->group_name }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-retur">
                                            -{{ $r->quantity }}
                                        </span>
                                    </td>

                                    <td>{{ $r->note ?: '-' }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($r->created_at)->format('d M Y H:i') }}
                                    </td>

                                    <td>
                                        <span class="badge badge-status">
                                            BELUM DIAMBIL
                                        </span>
                                    </td>

                                    <td>

                                        <form method="POST"
                                            action="/returns/taken/{{ $r->id }}"
                                            onsubmit="return confirm('Tandai barang sudah diambil?')">

                                            @csrf

                                            <button class="btn btn-success btn-sm w-100">

                                                <i class="fas fa-check-circle me-1"></i>

                                                SUDAH DIAMBIL

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endif

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- QR LIBRARY -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>

        const searchInput = document.getElementById('search-product');

        const productSelect = document.getElementById('product-select');

        const scannerBtn = document.getElementById('start-scanner');

        const readerDiv = document.getElementById('reader');

        let scannerRunning = false;

        let html5QrCode = null;

        /*
        |--------------------------------------------------------------------------
        | SIMPAN SEMUA DATA PRODUK
        |--------------------------------------------------------------------------
        */

        const allProducts = [];

        for (let option of productSelect.options) {

            if (option.value !== "") {

                allProducts.push({
                    value: option.value,
                    text: option.text
                });

            }

        }

        /*
        |--------------------------------------------------------------------------
        | FILTER PRODUK
        |--------------------------------------------------------------------------
        */

        function filterProducts(keyword) {

            keyword = keyword.toLowerCase();

            productSelect.innerHTML = '';

            const defaultOption = document.createElement('option');

            defaultOption.value = '';

            defaultOption.text = 'Pilih Produk';

            productSelect.appendChild(defaultOption);

            let foundFirst = false;

            allProducts.forEach(product => {

                if (
                    product.text.toLowerCase().includes(keyword)
                ) {

                    const option = document.createElement('option');

                    option.value = product.value;

                    option.text = product.text;

                    if (!foundFirst) {

                        option.selected = true;

                        foundFirst = true;

                    }

                    productSelect.appendChild(option);

                }

            });

        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH MANUAL
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener('input', function () {

            filterProducts(this.value);

        });

        /*
        |--------------------------------------------------------------------------
        | START SCANNER
        |--------------------------------------------------------------------------
        */

        scannerBtn.addEventListener('click', async function () {

            if (scannerRunning) {

                await html5QrCode.stop();

                readerDiv.style.display = 'none';

                scannerRunning = false;

                scannerBtn.innerHTML =
                    '<i class="fas fa-barcode"></i>';

                return;

            }

            readerDiv.style.display = 'block';

            html5QrCode = new Html5Qrcode("reader");

            scannerRunning = true;

            scannerBtn.innerHTML =
                '<i class="fas fa-times"></i>';

            Html5Qrcode.getCameras()

                .then(devices => {

                    if (devices && devices.length) {

                        let cameraId = devices[0].id;

                        const backCamera = devices.find(device =>

                            device.label.toLowerCase().includes('back')
                            ||
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

                                filterProducts(decodedText);

                                if (navigator.vibrate) {
                                    navigator.vibrate(200);
                                }

                                await html5QrCode.stop();

                                scannerRunning = false;

                                readerDiv.style.display = 'none';

                                scannerBtn.innerHTML =
                                    '<i class="fas fa-barcode"></i>';

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

</body>

</html>
