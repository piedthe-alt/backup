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

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        *{
            font-family: 'Inter', sans-serif;
        }

        body{
            background: #f8fafc;
            color: #0f172a;
        }

        .page-title{
            font-size: 2rem;
            font-weight: 700;
        }

        .card-nota{
            background: white;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header-custom{
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .nota-number{
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
        }

        .meta-text{
            font-size: .9rem;
            color: #64748b;
        }

        .table{
            margin-bottom: 0;
        }

        .table thead th{
            background: #f8fafc;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            font-size: .85rem;
            font-weight: 600;
            padding: 14px;
            white-space: nowrap;
        }

        .table tbody td{
            padding: 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: .93rem;
        }

        .table tbody tr:hover{
            background: #fafafa;
        }

        .summary-box{
            border-top: 1px solid #e2e8f0;
            background: #fafafa;
            padding: 18px 24px;
        }

        .summary-item small{
            color: #64748b;
            display: block;
            margin-bottom: 4px;
        }

        .summary-item strong{
            font-size: 1.05rem;
        }

        .text-margin{
            color: #2563eb;
            font-weight: 600;
        }

        .text-hpp{
            color: #dc2626;
            font-weight: 600;
        }

        .text-net{
            color: #16a34a;
            font-weight: 600;
        }

        .badge-margin{
            background: #eff6ff;
            color: #2563eb;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 600;
        }

        @media(max-width:768px){

            .page-title{
                font-size: 1.5rem;
            }

            .table{
                font-size: .82rem;
            }

            .table thead th,
            .table tbody td{
                padding: 10px;
            }

            .summary-box{
                padding: 16px;
            }

        }

    </style>

</head>

<body>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="page-title mb-1">
                Detail Nota Penjualan
            </h1>

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

    @foreach($sales as $nota)

        @php

            $totalBelanja = $nota->sum('netamount');

            $totalHpp = $nota->sum('cogs');

            $totalMargin = $totalBelanja - $totalHpp;

            $marginPercent =
                $totalBelanja > 0
                ? ($totalMargin / $totalBelanja) * 100
                : 0;

            $firstItem = $nota->first();

        @endphp

        <div class="card-nota">

            <!-- HEADER -->
            <div class="card-header-custom">

                <div class="row align-items-center">

                    <div class="col-md-4 mb-2 mb-md-0">

                        <div class="meta-text mb-1">
                            No Nota
                        </div>

                        <div class="nota-number">
                            {{ $firstItem->salesid }}
                        </div>

                    </div>

                    <div class="col-md-4 mb-2 mb-md-0">

                        <div class="meta-text mb-1">
                            Jam
                        </div>

                        <strong>
                            {{ \Carbon\Carbon::parse($firstItem->transdate)->format('H:i:s') }}
                        </strong>

                    </div>

                    <div class="col-md-4">

                        <div class="meta-text mb-1">
                            Total Item
                        </div>

                        <strong>
                            {{ $nota->count() }}
                            Barang
                        </strong>

                    </div>

                </div>

            </div>

            <!-- TABLE -->
            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Net</th>
                            <th>HPP</th>
                            <th>Margin</th>
                            <th>Margin %</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($nota as $item)

                        @php

                            $margin =
                                $item->netamount
                                - $item->cogs;

                            $marginPersen =
                                $item->netamount > 0
                                ? ($margin / $item->netamount) * 100
                                : 0;

                        @endphp

                        <tr>

                            <td>
                                {{ $item->productid }}
                            </td>

                            <td>

                                <strong>
                                    {{ $item->product_name }}
                                </strong>

                            </td>

                            <td>
                                {{ number_format($item->salesqty,0,',','.') }}
                            </td>

                            <td>
                                Rp {{ number_format($item->price,0,',','.') }}
                            </td>

                            <td class="text-net">
                                Rp {{ number_format($item->netamount,0,',','.') }}
                            </td>

                            <td class="text-hpp">
                                Rp {{ number_format($item->cogs,0,',','.') }}
                            </td>

                            <td class="text-margin">
                                Rp {{ number_format($margin,0,',','.') }}
                            </td>

                            <td>

                                <span class="badge-margin">

                                    {{ number_format($marginPersen,2,',','.') }}%

                                </span>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

            <!-- SUMMARY -->
            <div class="summary-box">

                <div class="row">

                    <div class="col-md-3 col-6 mb-3 mb-md-0">

                        <div class="summary-item">

                            <small>Total Belanja</small>

                            <strong class="text-net">
                                Rp {{ number_format($totalBelanja,0,',','.') }}
                            </strong>

                        </div>

                    </div>

                    <div class="col-md-3 col-6 mb-3 mb-md-0">

                        <div class="summary-item">

                            <small>Total HPP</small>

                            <strong class="text-hpp">
                                Rp {{ number_format($totalHpp,0,',','.') }}
                            </strong>

                        </div>

                    </div>

                    <div class="col-md-3 col-6">

                        <div class="summary-item">

                            <small>Total Margin</small>

                            <strong class="text-margin">
                                Rp {{ number_format($totalMargin,0,',','.') }}
                            </strong>

                        </div>

                    </div>

                    <div class="col-md-3 col-6">

                        <div class="summary-item">

                            <small>Margin %</small>

                            <strong>
                                {{ number_format($marginPercent,2,',','.') }}%
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

</div>

</body>
</html>
