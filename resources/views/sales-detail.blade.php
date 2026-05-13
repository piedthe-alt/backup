<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Penjualan</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet"
    >

    <style>

        body{
            background:#f8fafc;
        }

        .card-custom{
            border:none;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,0.08);
        }

        .table thead{
            background:#0f172a;
            color:white;
        }

        .table tbody tr:hover{
            background:#f1f5f9;
        }

    </style>

</head>

<body>

<div class="container py-4">

    <div class="card card-custom">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h3 class="fw-bold mb-1">

                        <i class="fas fa-receipt me-2"></i>
                        Detail Nota Penjualan

                    </h3>

                    <small class="text-muted">

                        Tanggal:
                        {{ $date }}

                    </small>

                </div>

                <a href="/sales-chart" class="btn btn-dark">

                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali

                </a>

            </div>

            <div class="table-responsive">

                <table class="table align-middle table-hover">

                    <thead>

                        <tr>

                            <th>No Nota</th>

                            <th>Jam</th>

                            <th>Total Item</th>

                            <th>Total Qty</th>

                            <th>Omzet</th>

                            <th>Margin</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($salesDetails as $item)

                        <tr>

                            <td>

                                <strong>

                                    {{ $item->salesid }}

                                </strong>

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}

                            </td>

                            <td>

                                {{ number_format($item->total_item,0,',','.') }}

                            </td>

                            <td>

                                {{ number_format($item->total_qty,0,',','.') }}

                            </td>

                            <td class="text-success fw-bold">

                                Rp {{ number_format($item->omzet,0,',','.') }}

                            </td>

                            <td class="text-primary fw-bold">

                                Rp {{ number_format($item->margin,0,',','.') }}

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="mt-4">

                {{ $salesDetails->links() }}

            </div>

        </div>

    </div>

</div>

</body>
</html>
