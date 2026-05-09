<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Produk</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 QR CODE -->
    <script src="https://unpkg.com/html5-qrcode"></script>

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="bg-primary text-white p-4">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div>

                        <h2 class="fw-bold mb-1">
                            📦 Daftar Produk
                        </h2>

                        <small>
                            Cari produk berdasarkan nama / barcode
                        </small>

                    </div>

                    <div class="d-flex gap-2 flex-wrap">

                        <a href="/ai-dashboard" class="btn btn-warning btn-lg">
                            🤖 AI Analysis
                        </a>

                        <a href="/sales-chart" class="btn btn-light btn-lg">
                            📈 Grafik
                        </a>

                        <button class="btn btn-success btn-lg" onclick="startScanner()">
                            📷 Scan
                        </button>

                        <a href="/import-db"
                            class="btn btn-danger btn-lg"
                            onclick="return confirm('Yakin mau import database? Data lama akan diganti!')">

                            🗄️ Import DB

                        </a>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <!-- SEARCH -->
                <div class="row g-3 mb-4">

                    <div class="col-md-9">

                        <input
                            type="text"
                            id="searchInput"
                            class="form-control form-control-lg"
                            placeholder="Masukkan nama produk / barcode..."
                            autofocus>

                    </div>

                    <div class="col-md-3">

                        <button
                            class="btn btn-primary btn-lg w-100"
                            onclick="searchProduct()">

                            🔍 Cari Produk

                        </button>

                    </div>

                </div>

                <!-- CAMERA -->
                <div id="reader"
                    class="mb-4"
                    style="width:100%; max-width:400px;">
                </div>

                <!-- INFO -->
                <div id="emptyInfo" class="text-center py-5">

                    <h4 class="fw-bold text-muted">
                        🔎 Cari Produk
                    </h4>

                    <p class="text-secondary mb-0">
                        Ketik nama produk lalu tekan tombol
                        <strong>Cari Produk</strong>
                    </p>

                </div>

                <!-- RESULT -->
                <div class="row g-4" id="productContainer">

                    @foreach ($products as $product)
                        <div
                            class="col-md-6 col-xl-4 product-item d-none"
                            data-id="{{ strtolower($product->id) }}"
                            data-name="{{ strtolower($product->name) }}">

                            <div
                                class="card border-0 shadow-sm rounded-4 h-100"
                                style="cursor:pointer"
                                data-bs-toggle="modal"
                                data-bs-target="#productModal{{ $product->id }}">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-start mb-3">

                                        <div>

                                            <h5 class="fw-bold mb-1">
                                                {{ $product->name }}
                                            </h5>

                                            <small class="text-muted">
                                                ID: {{ $product->id }}
                                            </small>

                                        </div>

                                        <span class="badge bg-primary fs-6">
                                            Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
                                        </span>

                                    </div>

                                    <hr>

                                    <div class="row text-center">

                                        <div class="col-4">

                                            <div class="fw-bold text-success">
                                                📦
                                            </div>

                                            <small class="text-muted d-block">
                                                Stock
                                            </small>

                                            <strong>
                                                {{ number_format($product->stock ?? 0, 0, ',', '.') }}
                                            </strong>

                                        </div>

                                        <div class="col-4">

                                            <div class="fw-bold text-primary">
                                                ⬇️
                                            </div>

                                            <small class="text-muted d-block">
                                                Masuk
                                            </small>

                                            <strong>
                                                {{ number_format($product->total_in ?? 0, 0, ',', '.') }}
                                            </strong>

                                        </div>

                                        <div class="col-4">

                                            <div class="fw-bold text-danger">
                                                ⬆️
                                            </div>

                                            <small class="text-muted d-block">
                                                Keluar
                                            </small>

                                            <strong>
                                                {{ number_format($product->total_out ?? 0, 0, ',', '.') }}
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

        </div>

    </div>

    <!-- MODAL -->
    @foreach ($products as $product)

        <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content border-0 shadow-lg rounded-4">

                    <!-- HEADER -->
                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title fw-bold">
                            {{ $product->name }}
                        </h5>

                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <table class="table table-bordered align-middle">

                            <tr>
                                <th width="250">Group Produk</th>
                                <td>{{ $product->productgroup_name }}</td>
                            </tr>

                            <tr>
                                <th>Supplier</th>
                                <td>{{ $product->supplier_name }}</td>
                            </tr>

                            <tr>

                                <th>Stok Saat Ini</th>

                                <td>

                                    @if (($product->stock ?? 0) > 0)

                                        <span class="badge bg-success fs-6">

                                            {{ number_format($product->stock, 0, ',', '.') }}

                                        </span>

                                    @else

                                        <span class="badge bg-danger fs-6">

                                            Habis

                                        </span>

                                    @endif

                                </td>

                            </tr>

                            <tr>

                                <th>Harga Modal</th>

                                <td>
                                    Rp {{ number_format($product->costprice, 0, ',', '.') }}
                                </td>

                            </tr>

                            <tr>

                                <th>Harga Jual</th>

                                <td>
                                    Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

    <!-- SCRIPT -->
    <script>

        /*
        |--------------------------------------------------------------------------
        | SEARCH PRODUK
        |--------------------------------------------------------------------------
        */

        function searchProduct(customValue = null) {

            const input = document.getElementById('searchInput');

            const keyword = (
                customValue
                ? customValue
                : input.value
            ).toLowerCase().trim();

            const products = document.querySelectorAll('.product-item');

            const emptyInfo = document.getElementById('emptyInfo');

            let found = 0;

            /*
            |--------------------------------------------------------------------------
            | VALIDASI
            |--------------------------------------------------------------------------
            */

            if (keyword === '') {

                products.forEach(product => {

                    product.classList.add('d-none');

                });

                emptyInfo.innerHTML = `

                    <h4 class="fw-bold text-muted">
                        🔎 Cari Produk
                    </h4>

                    <p class="text-secondary mb-0">
                        Ketik nama produk lalu tekan tombol
                        <strong>Cari Produk</strong>
                    </p>

                `;

                emptyInfo.style.display = 'block';

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LOOP PRODUK
            |--------------------------------------------------------------------------
            */

            products.forEach(product => {

                const id = product.dataset.id;

                const name = product.dataset.name;

                if (
                    id.includes(keyword)
                    ||
                    name.includes(keyword)
                ) {

                    product.classList.remove('d-none');

                    found++;

                } else {

                    product.classList.add('d-none');

                }

            });

            /*
            |--------------------------------------------------------------------------
            | HASIL KOSONG
            |--------------------------------------------------------------------------
            */

            if (found === 0) {

                emptyInfo.innerHTML = `

                    <h4 class="fw-bold text-danger">
                        ❌ Produk Tidak Ditemukan
                    </h4>

                `;

                emptyInfo.style.display = 'block';

            } else {

                emptyInfo.style.display = 'none';

            }

        }

        /*
        |--------------------------------------------------------------------------
        | SCAN BARCODE
        |--------------------------------------------------------------------------
        */

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

                    searchProduct(decodedText);

                    html5QrCode.stop();

                }

            ).catch(err => {

                console.log(err);

                alert('Tidak dapat membuka kamera');

            });

        }

    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
