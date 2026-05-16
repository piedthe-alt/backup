<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Market Basket Analysis</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Analisis Produk Dibeli Bersamaan
            </h2>

            <div class="text-muted">
                Total Transaksi:
                {{ number_format($totalTransactions) }}
            </div>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>

                            <th>No</th>

                            <th>Produk A</th>

                            <th>Produk B</th>

                            <th>Frekuensi</th>

                            <th>Support %</th>

                            <th>Confidence A→B %</th>

                            <th>Confidence B→A %</th>

                            <th>Lift</th>

                            <th>Rekomendasi Rak</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($analysis as $index => $item)

                            <tr>

                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $item['product_a'] }}
                                </td>

                                <td>
                                    {{ $item['product_b'] }}
                                </td>

                                <td class="text-center fw-bold">
                                    {{ number_format($item['frequency']) }}
                                </td>

                                <td class="text-center">
                                    {{ $item['support'] }}%
                                </td>

                                <td class="text-center">
                                    {{ $item['confidence_a_to_b'] }}%
                                </td>

                                <td class="text-center">
                                    {{ $item['confidence_b_to_a'] }}%
                                </td>

                                <td class="text-center fw-bold">
                                    {{ $item['lift'] }}
                                </td>

                                <td class="text-center">

                                    @if($item['recommendation'] == 'Sangat Direkomendasikan Dekat')

                                        <span class="badge bg-success">
                                            {{ $item['recommendation'] }}
                                        </span>

                                    @elseif($item['recommendation'] == 'Cocok Dekat')

                                        <span class="badge bg-primary">
                                            {{ $item['recommendation'] }}
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $item['recommendation'] }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="text-center py-5">
                                    Tidak ada data
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>

</html>
