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
            background: #f1f5f9;
        }

        .nota-card {

            border-radius: 16px;
            border: none;

            box-shadow:
                0 4px 12px rgba(0,0,0,0.05);

        }

        .item-table td,
        .item-table th {

            vertical-align: middle;

        }

    </style>

</head>

<body>

<div class="container py-4">

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

        <a href="/sales-chart" class="btn btn-dark">

            <i class="fas fa-arrow-left me-2"></i>
            Kembali

        </a>

    </div>

    @foreach($notas as $nota)

        <div class="card nota-card mb-4">

            <div class="card-body">

                <div class="row mb-4">

                    <div class="col-md-3 mb-3">

                        <div class="bg-light p-3 rounded">

                            <small class="text-muted d-block">
                                No Nota
                            </small>

                            <strong>
                                {{ $nota->salesid }}
                            </strong>

                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <div class="bg-light p-3 rounded">

                            <small class="text-muted d-block">
                                Total Belanja
                            </small>

                            <strong class="text-success">

                                Rp {{ number_format($nota->total_belanja,0,',','.') }}

                            </strong>

                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <div class="bg-light p-3 rounded">

                            <small class="text-muted d-block">
                                Total HPP
                            </small>

                            <strong class="text-danger">

                                Rp {{ number_format($nota->total_hpp,0,',','.') }}

                            </strong>

                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <div class="bg-light p-3 rounded">

                            <small class="text-muted d-block">
                                Total Margin
                            </small>

                            <strong class="text-primary">

                                Rp {{ number_format($nota->total_margin,0,',','.') }}

                            </strong>

                        </div>

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered item-table">

                        <thead class="table-dark">

                            <tr>

                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
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

                                <td>

                                    {{ $item->snproduct }}

                                </td>

                                <td>

                                    {{ number_format($item->salesqty,0,',','.') }}

                                </td>

                                <td>

                                    Rp {{ number_format($item->price,0,',','.') }}

                                </td>

                                <td class="text-success fw-bold">

                                    Rp {{ number_format($item->netamount,0,',','.') }}

                                </td>

                                <td class="text-danger fw-bold">

                                    Rp {{ number_format($item->cogs,0,',','.') }}

                                </td>

                                <td class="text-primary fw-bold">

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
