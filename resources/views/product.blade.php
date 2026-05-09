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
            <div
                class="card-header bg-primary text-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h2 class="mb-1 fw-bold">
                        📦 Daftar Produk
                    </h2>

                    <small>
                        Scan barcode atau cari produk
                    </small>

                </div>

                <!-- MENU -->
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

                    <a href="/import-db" class="btn btn-danger btn-lg"
                        onclick="return confirm('Yakin mau import database? Data lama akan diganti!')">
                        🗄️ Import DB
                    </a>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <!-- SEARCH -->
                <div class="row mb-4">

                    <div class="col-md-8 mb-2">

                        <input type="text" id="searchInput" class="form-control form-control-lg"
                            placeholder="Scan barcode / cari nama produk..." autofocus>

                    </div>

                    <div class="col-md-4 mb-2">

                        <button class="btn btn-primary btn-lg w-100" onclick="searchProduct()">
                            Cari Produk
                        </button>

                    </div>

                </div>

                <!-- CAMERA -->
                <div id="reader" class="mb-4" style="width:100%; max-width:400px;"></div>

                <!-- TABLE -->
                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th width="120">
                                    ID
                                </th>

                                <th>
                                    Produk
                                </th>

                                <th width="180">
                                    Harga
                                </th>

                            </tr>

                        </thead>

                        <tbody id="tableBody">

                            <tr>

                                <td colspan="3" class="text-center text-muted py-5">

                                    🔍 Silakan cari produk terlebih dahulu

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- MODAL -->
    @foreach ($products as $product)
        <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    <!-- HEADER -->
                    <div class="modal-header bg-primary text-white">

                        <div>

                            <h5 class="modal-title fw-bold">

                                📦 {{ $product->name }}

                            </h5>

                            <small>

                                ID:
                                {{ $product->id }}

                            </small>

                        </div>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                    </div>

                    <!-- BODY -->
                    <div class="modal-body p-4">

                        <!-- INFO -->
                        <table class="table table-bordered align-middle">

                            <tr>

                                <th width="250">
                                    Group Produk
                                </th>

                                <td>

                                    <span class="badge bg-primary fs-6">

                                        {{ $product->productgroup_name }}

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Supplier
                                </th>

                                <td>

                                    <span class="badge bg-dark fs-6">

                                        {{ $product->supplier_name }}

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Stock Saat Ini
                                </th>

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

                                <th>
                                    Harga Modal
                                </th>

                                <td>

                                    Rp {{ number_format($product->costprice, 0, ',', '.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Harga Jual
                                </th>

                                <td>

                                    <strong>

                                        Rp {{ number_format($product->salesprice1, 0, ',', '.') }}

                                    </strong>

                                </td>

                            </tr>

                        </table>

                        <!-- TINGKAT HARGA -->
                        <h5 class="fw-bold mt-4 mb-3">

                            💰 Tingkatan Harga

                        </h5>

                        <table class="table table-striped table-hover">

                            <thead class="table-dark">

                                <tr>

                                    <th>Minimal Beli</th>

                                    <th>Harga / pcs</th>

                                </tr>

                            </thead>

                            <tbody>

                                @if ($product->salesdiscqty1 > 0)
                                    <tr>

                                        <td>
                                            {{ $product->salesdiscqty1 }} pcs
                                        </td>

                                        <td>

                                            Rp {{ number_format($product->salesdiscprice1, 0, ',', '.') }}

                                        </td>

                                    </tr>
                                @endif

                                @if ($product->salesdiscqty2 > 0)
                                    <tr>

                                        <td>
                                            {{ $product->salesdiscqty2 }} pcs
                                        </td>

                                        <td>

                                            Rp {{ number_format($product->salesdiscprice2, 0, ',', '.') }}

                                        </td>

                                    </tr>
                                @endif

                                @if ($product->salesdiscqty3 > 0)
                                    <tr>

                                        <td>
                                            {{ $product->salesdiscqty3 }} pcs
                                        </td>

                                        <td>

                                            Rp {{ number_format($product->salesdiscprice3, 0, ',', '.') }}

                                        </td>

                                    </tr>
                                @endif

                            </tbody>

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
        | DATA PRODUK
        |--------------------------------------------------------------------------
        */

        const products = @json($products);

        /*
        |--------------------------------------------------------------------------
        | SEARCH PRODUK
        |--------------------------------------------------------------------------
        */

        function searchProduct(customValue = null) {

            const input = document.getElementById('searchInput');

            const keyword = (
                customValue
                ?
                customValue
                :
                input.value
            ).toLowerCase().trim();

            const tableBody = document.getElementById('tableBody');

            /*
            |--------------------------------------------------------------------------
            | RESET TABLE
            |--------------------------------------------------------------------------
            */

            tableBody.innerHTML = '';

            /*
            |--------------------------------------------------------------------------
            | JIKA KOSONG
            |--------------------------------------------------------------------------
            */

            if (keyword === '') {

                tableBody.innerHTML = `

                    <tr>

                        <td colspan="3" class="text-center text-muted py-5">

                            🔍 Silakan cari produk terlebih dahulu

                        </td>

                    </tr>

                `;

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | FILTER
            |--------------------------------------------------------------------------
            */

            const filtered = products.filter(product => {

                return (

                    product.id.toLowerCase().includes(keyword)

                    ||

                    product.name.toLowerCase().includes(keyword)

                );

            });

            /*
            |--------------------------------------------------------------------------
            | TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            */

            if (filtered.length === 0) {

                tableBody.innerHTML = `

                    <tr>

                        <td colspan="3" class="text-center text-danger py-5">

                            ❌ Produk tidak ditemukan

                        </td>

                    </tr>

                `;

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN PRODUK
            |--------------------------------------------------------------------------
            */

            filtered.forEach(product => {

                /*
                |--------------------------------------------------------------------------
                | STOCK
                |--------------------------------------------------------------------------
                */

                const stock = Number(product.stock ?? 0);

                /*
                |--------------------------------------------------------------------------
                | TOTAL MASUK
                |--------------------------------------------------------------------------
                */

                const totalMasuk = Number(product.total_masuk ?? 0);

                /*
                |--------------------------------------------------------------------------
                | TOTAL KELUAR
                |--------------------------------------------------------------------------
                */

                const totalKeluar = Number(product.total_keluar ?? 0);

                /*
                |--------------------------------------------------------------------------
                | BADGE STOCK
                |--------------------------------------------------------------------------
                */

                let stockBadge = '';

                if (stock <= 0) {

                    stockBadge = `
                        <span class="badge bg-danger">
                            Habis
                        </span>
                    `;

                } else if (stock <= 5) {

                    stockBadge = `
                        <span class="badge bg-warning text-dark">
                            ${stock}
                        </span>
                    `;

                } else {

                    stockBadge = `
                        <span class="badge bg-success">
                            ${stock}
                        </span>
                    `;
                }

                /*
                |--------------------------------------------------------------------------
                | ROW PRODUK
                |--------------------------------------------------------------------------
                */

                tableBody.innerHTML += `

                    <tr style="cursor:pointer"
                        class="border-bottom"
                        data-bs-toggle="modal"
                        data-bs-target="#productModal${product.id}">

                        <td class="fw-bold text-primary">

                            ${product.id}

                        </td>

                        <td width="45%">

                            <div class="fw-bold fs-5 mb-3">

                                📦 ${product.name}

                            </div>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="badge bg-primary fs-6">

                                    📦 Stock:
                                    ${stockBadge}

                                </span>

                                <span class="badge bg-success fs-6">

                                    ⬇ Masuk:
                                    ${totalMasuk.toLocaleString('id-ID')}

                                </span>

                                <span class="badge bg-danger fs-6">

                                    ⬆ Keluar:
                                    ${totalKeluar.toLocaleString('id-ID')}

                                </span>

                            </div>

                        </td>

                        <td class="fw-bold">

                            Rp ${Number(product.salesprice1).toLocaleString('id-ID')}

                        </td>

                    </tr>

                `;

            });

        }

        /*
        |--------------------------------------------------------------------------
        | AUTO SEARCH
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('searchInput')
            .addEventListener('keyup', function() {

                searchProduct();

            });

        /*
        |--------------------------------------------------------------------------
        | START CAMERA SCANNER
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

                    /*
                    |--------------------------------------------------------------------------
                    | MASUKKAN HASIL SCAN
                    |--------------------------------------------------------------------------
                    */

                    document.getElementById('searchInput').value = decodedText;

                    /*
                    |--------------------------------------------------------------------------
                    | SEARCH
                    |--------------------------------------------------------------------------
                    */

                    searchProduct(decodedText);

                    /*
                    |--------------------------------------------------------------------------
                    | AUTO STOP CAMERA
                    |--------------------------------------------------------------------------
                    */

                    html5QrCode.stop();

                }

            ).catch(err => {

                console.log(err);

                alert('Tidak dapat membuka kamera');

            });

        }

    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
