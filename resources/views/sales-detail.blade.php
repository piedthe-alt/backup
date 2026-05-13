<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Nota Penjualan</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet"
    >

    <style>

        body {

            background:
                linear-gradient(
                    135deg,
                    #f8fafc 0%,
                    #e2e8f0 100%
                );

            min-height: 100vh;

        }

        .nota-card {

            border: none;

            border-radius: 20px;

            overflow: hidden;

            box-shadow:
                0 10px 30px rgba(0,0,0,0.08);

        }

        .nota-header {

            background:
                linear-gradient(
                    135deg,
                    #0f172a 0%,
                    #1e293b 100%
                );

            color: white;

        }

        .summary-box {

            border-radius: 14px;

            padding: 18px;

            color: white;

            height: 100%;

        }

        .table th {

            white-space: nowrap;

        }

        .table td {

            vertical-align: middle;

        }

        .product-name {

            font-weight: 600;

        }

        .money {

            font-weight: 700;

            white-space: nowrap;

        }

    </style>

</head>

<body>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-receipt me-2"></i>
                Detail Nota Penjualan

            </h2>

            <div class="text-muted">

                Tanggal:
                {{ $tanggal }}

            </div>

        </div>

        <a
            href="/sales-chart"
            class="btn btn-dark"
        >

            <i class="fas fa-arrow-left me-2"></i>
            Kembali

        </a>

    </div>

    @foreach($notas as $nota)

        <div class="card nota-card mb-5">

            <!-- HEADER -->
            <div class="nota-header p-4">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <small class="opacity-75 d-block">

                            No Nota

                        </small>

                        <h5 class="fw-bold mb-0">

                            {{ $nota->salesid }}

                        </h5>

                    </div>

                    <div class="col-md-3 mb-3">

                        <small class="opacity-75 d-block">

                            Jam

                        </small>

                        <h5 class="fw-bold mb-0">

                            {{ \Carbon\Carbon::parse($nota->transdate)->format('H:i:s') }}

                        </h5>

                    </div>

                    <div class="col-md-3 mb-3">

                        <small class="opacity-75 d-block">

                            Total Qty

                        </small>

                        <h5 class="fw-bold mb-0">

                            {{ number_format($nota->total_qty,0,',','.') }}

                        </h5>

                    </div>

                    <div class="col-md-3 mb-3">

                        <small class="opacity-75 d-block">

                            Jumlah Item

                        </small>

                        <h5 class="fw-bold mb-0">

                            {{ count($nota->items) }}

                        </h5>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <!-- SUMMARY -->
                <div class="row mb-4">

                    <div class="col-md-4 mb-3">

                        <div
                            class="summary-box"
                            style="background:#16a34a;"
                        >

                            <small>Total Belanja</small>

                            <h3 class="fw-bold mb-0">

                                Rp {{ number_format($nota->total_belanja,0,',','.') }}

                            </h3>

                        </div>

                    </div>

                    <div class="col-md-4 mb-3">

                        <div
                            class="summary-box"
                            style="background:#dc2626;"
                        >

                            <small>Total HPP</small>

                            <h3 class="fw-bold mb-0">

                                Rp {{ number_format($nota->total_hpp,0,',','.') }}

                            </h3>

                        </div>

                    </div>

                    <div class="col-md-4 mb-3">

                        <div
                            class="summary-box"
                            style="background:#2563eb;"
                        >

                            <small>Total Margin</small>

                            <h3 class="fw-bold mb-0">

                                Rp {{ number_format($nota->total_margin,0,',','.') }}

                            </h3>

                        </div>

                    </div>

                </div>

                <!-- TABLE -->
                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>Kode</th>

                                <th>Nama Barang</th>

                                <th>Qty</th>

                                <th>Harga</th>

                                <th>Gross</th>

                                <th>Net</th>

                                <th>HPP</th>

                                <th>Margin</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach($nota->items as $item)

                            <tr>

                                <td>

                                    {{ $item->productid }}

                                </td>

                                <td class="product-name">

                                    {{ $item->product_name }}

                                </td>

                                <td>

                                    {{ number_format($item->salesqty,0,',','.') }}

                                </td>

                                <td>

                                    Rp {{ number_format($item->price,0,',','.') }}

                                </td>

                                <td class="money text-secondary">

                                    Rp {{ number_format($item->grossamount,0,',','.') }}

                                </td>

                                <td class="money text-success">

                                    Rp {{ number_format($item->netamount,0,',','.') }}

                                </td>

                                <td class="money text-danger">

                                    Rp {{ number_format($item->cogs,0,',','.') }}

                                </td>

                                <td class="money text-primary">

                                    Rp {{ number_format($item->margin,0,',','.') }}

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endforeach

</div>

</body>
</html>
