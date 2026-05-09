<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Produk</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- HTML5 QR CODE -->
    <script src="https://unpkg.com/html5-qrcode"></script>

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card border-0 shadow-lg rounded-4">

        <!-- HEADER -->
        <div
            class="card-header bg-primary text-white p-4 rounded-top-4 d-flex justify-content-between align-items-center"
        >

            <div>

                <h2 class="mb-0">
                    Daftar
                </h2>

                <small>
                    Scan barcode
                </small>

            </div>

            <!-- MENU -->
            <div class="d-flex gap-2">

                <a
                    href="/sales-chart"
                    class="btn btn-light btn-lg"
                >
                    📈 Grafik
                </a>

                <button
                    class="btn btn-success btn-lg"
                    onclick="startScanner()"
                >
                    📷 Scan
                </button>

            </div>

        </div>

        <!-- BODY -->
        <div class="card-body">

            <!-- SEARCH -->
            <div class="row mb-4">

                <div class="col-md-8">

                    <input
                        type="text"
                        id="searchInput"
                        class="form-control form-control-lg"
                        placeholder="Scan barcode / cari nama produk..."
                        autofocus
                    >

                </div>

                <div class="col-md-4">

                    <button
                        class="btn btn-primary btn-lg w-100"
                        onclick="searchProduct()"
                    >
                        Cari Produk
                    </button>

                </div>

            </div>

            <!-- CAMERA -->
            <div
                id="reader"
                class="mb-4"
                style="width:100%; max-width:400px;"
            ></div>

            <!-- TABLE -->
            <div class="table-responsive">

                <table
                    class="table table-hover align-middle"
                    id="productTable"
                >

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Nama Produk</th>

                            <th>Group</th>

                            <th>Supplier</th>

                            <th>Harga</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($products as $product)

                    <tr
                        style="cursor:pointer"

                        data-bs-toggle="modal"

                        data-bs-target="#productModal{{ $product->id }}"
                    >

                        <td>
                            {{ $product->id }}
                        </td>

                        <td>

                            <strong>
                                {{ $product->name }}
                            </strong>

                        </td>

                        <td>
                            {{ $product->productgroup_name }}
                        </td>

                        <td>
                            {{ $product->supplier_name }}
                        </td>

                        <td>

                            Rp {{ number_format($product->salesprice1, 0, ',', '.') }}

                        </td>

                    </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- MODAL -->
@foreach($products as $product)

<div
    class="modal fade"
    id="productModal{{ $product->id }}"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">

                    {{ $product->name }}

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- INFO -->
                <table class="table table-bordered">

                    <tr>

                        <th width="250">
                            Stok Saat Ini
                        </th>

                        <td>

                            @if(($product->stock ?? 0) > 0)

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

                            Rp {{ number_format($product->salesprice1, 0, ',', '.') }}

                        </td>

                    </tr>

                </table>

                <!-- TINGKAT HARGA -->
                <h5 class="fw-bold mt-4 mb-3">
                    Tingkatan Harga
                </h5>

                <table class="table table-striped table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>Minimal Beli</th>

                            <th>Harga / pcs</th>

                        </tr>

                    </thead>

                    <tbody>

                        @if($product->salesdiscqty1 > 0)

                        <tr>

                            <td>
                                {{ $product->salesdiscqty1 }} pcs
                            </td>

                            <td>

                                Rp {{ number_format($product->salesdiscprice1, 0, ',', '.') }}

                            </td>

                        </tr>

                        @endif

                        @if($product->salesdiscqty2 > 0)

                        <tr>

                            <td>
                                {{ $product->salesdiscqty2 }} pcs
                            </td>

                            <td>

                                Rp {{ number_format($product->salesdiscprice2, 0, ',', '.') }}

                            </td>

                        </tr>

                        @endif

                        @if($product->salesdiscqty3 > 0)

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
| SEARCH PRODUK
|--------------------------------------------------------------------------
*/

function searchProduct(customValue = null) {

    const input = document.getElementById('searchInput');

    const filter = (
        customValue
        ? customValue
        : input.value
    ).toLowerCase();

    const rows = document.querySelectorAll(
        '#productTable tbody tr'
    );

    rows.forEach(row => {

        const id = row.cells[0].textContent.toLowerCase();

        const name = row.cells[1].textContent.toLowerCase();

        if (

            id.includes(filter)

            ||

            name.includes(filter)

        ) {

            row.style.display = '';

        } else {

            row.style.display = 'none';

        }

    });

}

/*
|--------------------------------------------------------------------------
| AUTO SEARCH
|--------------------------------------------------------------------------
*/

document
    .getElementById('searchInput')
    .addEventListener('keyup', function () {

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
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>
