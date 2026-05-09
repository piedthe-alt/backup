<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://unpkg.com/html5-qrcode"></script>

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="bg-primary text-white p-4">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div>

                        <h2 class="mb-1 fw-bold">
                            Daftar Produk
                        </h2>

                        <small>
                            Scan barcode atau cari produk
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
                            onclick="return confirm('Yakin mau import database?')">

                            🗄️ Import DB

                        </a>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <!-- FORM SEARCH -->
                <form method="GET" action="/">

                    <div class="row g-3 mb-4">

                        <!-- SEARCH -->
                        <div class="col-md-6">

                            <input
                                type="text"
                                name="keyword"
                                id="searchInput"
                                class="form-control form-control-lg"
                                placeholder="Scan barcode / cari nama produk..."
                                value="{{ request('keyword') }}"
                                autofocus>

                        </div>

                        <!-- FILTER GROUP -->
                        <div class="col-md-3">

                            <select
                                name="productgroup"
                                class="form-select form-select-lg">

                                <option value="">
                                    Semua Group
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
                                class="btn btn-primary btn-lg w-100">

                                🔍 Cari Produk

                            </button>

                        </div>

                    </div>

                </form>

                <!-- QR READER -->
                <div
                    id="reader"
                    class="mb-4"
                    style="width:100%; max-width:400px;">

                </div>

                <!-- PRODUK -->
                <div class="row g-4">

                    @forelse ($products as $product)

                        <div class="col-md-6 col-lg-4">

                            <div
                                class="card border-0 shadow-sm rounded-4 h-100"
                                style="cursor:pointer"
                                data-bs-toggle="modal"
                                data-bs-target="#productModal{{ $product->id }}">

                                <div class="card-body p-4">

                                    <!-- NAMA -->
                                    <h5 class="fw-bold mb-3">

                                        {{ $product->name }}

                                    </h5>

                                    <!-- HARGA -->
                                    <div class="mb-3">

                                        <span class="badge bg-primary fs-6 px-3 py-2">

                                            Rp {{ number_format($product->salesprice1, 0, ',', '.') }}

                                        </span>

                                    </div>

                                    <!-- INFO -->
                                    <div class="d-flex flex-column gap-2">

                                        <div class="d-flex justify-content-between">

                                            <span>
                                                📦 Stock
                                            </span>

                                            <strong>

                                                {{ number_format($product->stock, 0, ',', '.') }}

                                            </strong>

                                        </div>

                                        <div class="d-flex justify-content-between">

                                            <span>
                                                📥 Masuk
                                            </span>

                                            <strong>

                                                {{ number_format($product->total_masuk, 0, ',', '.') }}

                                            </strong>

                                        </div>

                                        <div class="d-flex justify-content-between">

                                            <span>
                                                📤 Keluar
                                            </span>

                                            <strong>

                                                {{ number_format($product->total_keluar, 0, ',', '.') }}

                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">

                            <div class="alert alert-danger rounded-4">

                                Produk tidak ditemukan

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
                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title">

                            {{ $product->name }}

                        </h5>

                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">

                        </button>

                    </div>

                    <!-- BODY -->
                    <div class="modal-body p-4">

                        <table class="table table-bordered align-middle">

                            <tr>

                                <th width="250">
                                    Group Produk
                                </th>

                                <td>

                                    {{ $product->productgroup_name }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Supplier
                                </th>

                                <td>

                                    {{ $product->supplier_name }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Stock Saat Ini
                                </th>

                                <td>

                                    {{ number_format($product->stock, 0, ',', '.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Total Barang Masuk
                                </th>

                                <td>

                                    {{ number_format($product->total_masuk, 0, ',', '.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Total Barang Keluar
                                </th>

                                <td>

                                    {{ number_format($product->total_keluar, 0, ',', '.') }}

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
