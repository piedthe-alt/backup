<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Retail Intelligence Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container-fluid py-4">

    <div class="mb-4">

        <h1 class="fw-bold">
            Retail Intelligence Dashboard
        </h1>

        <div class="text-muted">
            Analisis penjualan dan perilaku pembelian
        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Fast Moving
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ number_format($fastMoving->sum('total_qty')) }}
                            </div>

                        </div>

                        <i class="fa-solid fa-fire text-danger fs-1"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Margin
                            </div>

                            <div class="fs-3 fw-bold">
                                Rp {{ number_format($highestMargin->sum('total_margin')) }}
                            </div>

                        </div>

                        <i class="fa-solid fa-money-bill-trend-up text-success fs-1"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Total Diskon
                            </div>

                            <div class="fs-3 fw-bold">
                                Rp {{ number_format($discountProducts->sum('total_discount')) }}
                            </div>

                        </div>

                        <i class="fa-solid fa-tags text-primary fs-1"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Pair Analysis
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $pairAnalysis->count() }}
                            </div>

                        </div>

                        <i class="fa-solid fa-layer-group text-warning fs-1"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-bold">
                    Fast Moving
                </div>

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>Produk</th>

                                <th>Qty</th>

                                <th>Omzet</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($fastMoving as $item)

                                <tr>

                                    <td>{{ $item->name }}</td>

                                    <td>{{ number_format($item->total_qty) }}</td>

                                    <td>
                                        Rp {{ number_format($item->omzet) }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-bold">
                    Profit Tertinggi
                </div>

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>Produk</th>

                                <th>Margin</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($highestMargin as $item)

                                <tr>

                                    <td>{{ $item->name }}</td>

                                    <td>
                                        Rp {{ number_format($item->total_margin) }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-bold">
                    Produk Sering Diskon
                </div>

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>Produk</th>

                                <th>Total Diskon</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($discountProducts as $item)

                                <tr>

                                    <td>{{ $item->name }}</td>

                                    <td>
                                        Rp {{ number_format($item->total_discount) }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-bold">
                    Jam Ramai
                </div>

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>Jam</th>

                                <th>Total Transaksi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($busyHours as $item)

                                <tr>

                                    <td>
                                        {{ str_pad($item->hour, 2, '0', STR_PAD_LEFT) }}:00
                                    </td>

                                    <td>
                                        {{ number_format($item->total_transactions) }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-bold">
                    Produk Dibeli Bersamaan
                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark text-center">

                            <tr>

                                <th>No</th>

                                <th>Produk A</th>

                                <th>Produk B</th>

                                <th>Frekuensi</th>

                                <th>Rekomendasi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($pairAnalysis as $index => $item)

                                <tr>

                                    <td class="text-center">
                                        {{ $index + 1 }}
                                    </td>

                                    <td>{{ $item['a'] }}</td>

                                    <td>{{ $item['b'] }}</td>

                                    <td class="text-center fw-bold">
                                        {{ number_format($item['freq']) }}
                                    </td>

                                    <td class="text-center">

                                        @if($item['freq'] >= 20)

                                            <span class="badge bg-success">
                                                Sangat Cocok Didekatkan
                                            </span>

                                        @elseif($item['freq'] >= 10)

                                            <span class="badge bg-primary">
                                                Cocok Untuk Bundle
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Berkaitan
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>
