<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Retur Barang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <script src="https://unpkg.com/@zxing/library@latest"></script>

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
        .table-card,
        .scanner-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .form-card,
        .scanner-card {
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
        }

        #product-select option {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        #reader {

            width: 100%;
            max-width: 420px;

            margin: auto;

            border-radius: 18px;

            overflow: hidden;

            border: 3px solid #dc2626;

            background: black;
        }

        .scan-result {

            font-size: 18px;

            font-weight: 700;

            color: #dc2626;
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

            .form-card,
            .scanner-card {
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

            .header-mobile {
                flex-direction: column;
                align-items: stretch !important;
            }

            #product-select {
                height: 220px;
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

        <!-- SCANNER -->
        <div class="scanner-card mb-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold mb-0">

                    <i class="fas fa-barcode me-2 text-danger"></i>

                    Scanner Barcode

                </h5>

                <button type="button" class="btn btn-danger" id="startScan">

                    Start Scan

                </button>

            </div>

            <div id="reader"></div>

            <div class="mt-3">

                <div class="text-muted small mb-1">

                    Hasil Scan

                </div>

                <div id="scanResult" class="scan-result">

                    -

                </div>

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
                            Cari Produk
                        </label>

                        <input type="text" id="search-product" class="form-control mb-2"
                            placeholder="Cari nama / ID produk...">

                        <select name="product_id" id="product-select" class="form-select" size="8" required>

                            <option value="">
                                Pilih Produk
                            </option>

                            @foreach (DB::table('product')->orderBy('name')->get() as $p)
                                <option value="{{ $p->id }}" data-barcode="{{ $p->barcode ?? '' }}">

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

                        <input type="number" name="quantity" class="form-control" placeholder="Jumlah" required>

                    </div>

                    <!-- NOTE -->
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Keterangan
                        </label>

                        <input type="text" name="note" class="form-control" placeholder="Contoh: Kemasan rusak">

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

            <!-- DESKTOP -->
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

                        @php
                            $hasData = false;
                        @endphp

                        @foreach ($returns as $r)
                            @if (strtoupper(trim($r->status)) != 'SUDAH_DIAMBIL')
                                @php
                                    $hasData = true;
                                @endphp

                                <tr>

                                    <td>
                                        <strong>
                                            {{ $r->product_id_view }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $r->product_name }}
                                    </td>

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

                                    <td>
                                        {{ $r->note ?: '-' }}
                                    </td>

                                    <td>

                                        {{ \Carbon\Carbon::parse($r->created_at)->format('d M Y H:i') }}

                                    </td>

                                    <td>

                                        <span class="badge badge-status">
                                            BELUM DIAMBIL
                                        </span>

                                    </td>

                                    <td>

                                        <form method="POST" action="/returns/taken/{{ $r->id }}"
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

                        @if (!$hasData)
                            <tr>

                                <td colspan="8">

                                    <div class="empty-state">

                                        <i class="fas fa-box-open fa-3x mb-3"></i>

                                        <h5 class="fw-bold">
                                            Tidak ada barang retur
                                        </h5>

                                        <div>
                                            Semua barang retur sudah diambil
                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endif

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <script>
        const searchInput = document.getElementById('search-product');

        const productSelect = document.getElementById('product-select');

        /*
        |--------------------------------------------------------------------------
        | SEARCH PRODUCT
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener('input', function() {

            const keyword = this.value.toLowerCase();

            const options = productSelect.options;

            for (let i = 0; i < options.length; i++) {

                const text = options[i].text.toLowerCase();

                if (
                    text.includes(keyword) ||
                    options[i].value === ""
                ) {

                    options[i].style.display = '';

                } else {

                    options[i].style.display = 'none';

                }

            }

        });

        /*
        |--------------------------------------------------------------------------
        | BARCODE SCANNER
        |--------------------------------------------------------------------------
        */

        const codeReader = new ZXing.BrowserMultiFormatReader();

        const resultEl = document.getElementById('scanResult');

        async function startScanner() {

            try {

                const videoInputDevices =
                    await ZXing.BrowserCodeReader.listVideoInputDevices();

                if (videoInputDevices.length === 0) {

                    alert('Camera tidak ditemukan');

                    return;

                }

                /*
                |--------------------------------------------------------------------------
                | PILIH KAMERA BELAKANG
                |--------------------------------------------------------------------------
                */

                let selectedDeviceId = videoInputDevices[0].deviceId;

                for (const device of videoInputDevices) {

                    const label = device.label.toLowerCase();

                    if (
                        label.includes('back') ||
                        label.includes('rear')
                    ) {

                        selectedDeviceId = device.deviceId;

                        break;

                    }

                }

                /*
                |--------------------------------------------------------------------------
                | START SCAN
                |--------------------------------------------------------------------------
                */

                codeReader.decodeFromVideoDevice(

                    selectedDeviceId,

                    'reader',

                    (result, err) => {

                        if (result) {

                            const barcode = result.text;

                            resultEl.innerHTML = barcode;

                            findProductByBarcode(barcode);

                            navigator.vibrate?.(100);

                            /*
                            |--------------------------------------------------------------------------
                            | STOP SETELAH BERHASIL
                            |--------------------------------------------------------------------------
                            */

                            codeReader.reset();

                        }

                    }

                );

            } catch (error) {

                console.log(error);

                alert('Gagal membuka scanner');

            }

        }

        /*
        |--------------------------------------------------------------------------
        | FIND PRODUCT BY BARCODE
        |--------------------------------------------------------------------------
        */

        function findProductByBarcode(barcode) {

            const options = productSelect.options;

            let found = false;

            for (let i = 0; i < options.length; i++) {

                const option = options[i];

                const optionBarcode =
                    option.dataset.barcode;

                if (optionBarcode == barcode) {

                    option.selected = true;

                    option.scrollIntoView({

                        behavior: 'smooth',

                        block: 'center'

                    });

                    found = true;

                    break;

                }

            }

            if (!found) {

                alert('Produk barcode tidak ditemukan');

            }

        }

        document
            .getElementById('startScan')
            .addEventListener('click', startScanner);
    </script>

</body>

</html>
