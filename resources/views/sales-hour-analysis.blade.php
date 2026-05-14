<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Analisis Jam Ramai Toko - Stock Manager</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .header-section {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            position: relative;
            overflow: hidden;
            border-radius: 12px;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(100px, -100px);
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .btn-action {
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }

        .stat-card h6 {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stat-card h3,
        .stat-card h2 {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .table thead th {
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .table tbody td {
            padding: 1rem;
            border: none;
        }

        .hour-badge {
            display: inline-block;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .peak-indicator {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        @media (max-width: 768px) {

            .chart-container {
                padding: 1rem;
            }

            .table {
                font-size: 0.85rem;
            }

        }
    </style>

</head>

<body>

    <div class="container-fluid py-4">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="header-section p-4">

                <div class="header-content">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h2 class="mb-2 fw-bold">
                                <i class="fas fa-clock me-2"></i>
                                Analisis Jam Ramai Toko
                            </h2>

                            <small class="opacity-75">
                                Identifikasi jam-jam puncak penjualan untuk optimasi operasional
                            </small>

                        </div>

                        <a href="/" class="btn btn-action btn-light">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <!-- FILTER -->
                <form method="GET" action="/sales-hour-analysis" class="row g-3 mb-5">

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            📅 Dari Tanggal
                        </label>

                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            📅 Sampai Tanggal
                        </label>

                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">

                    </div>

                    <div class="col-md-6 d-flex align-items-end">

                        <button class="btn btn-action btn-primary fw-bold w-100">
                            <i class="fas fa-search"></i>
                            Analisis Periode
                        </button>

                    </div>

                </form>

                <!-- SUMMARY -->
                <div class="row mb-4">

                    <div class="col-md-4 mb-3">

                        <div class="stat-card border-start border-primary border-4">

                            <h6>Total Transaksi</h6>

                            <h2 class="fw-bold text-primary mb-0">
                                {{ $totalTransactions }}
                            </h2>

                        </div>

                    </div>

                    <div class="col-md-4 mb-3">

                        <div class="stat-card border-start border-success border-4">

                            <h6>Omzet Bersih</h6>

                            <h2 class="fw-bold text-success mb-0">
                                Rp {{ number_format($totalAmount, 0, ',', '.') }}
                            </h2>

                        </div>

                    </div>

                    <div class="col-md-4 mb-3">

                        <div class="stat-card border-start border-info border-4">

                            <h6>Margin Bersih</h6>

                            <h2 class="fw-bold text-info mb-0">
                                Rp {{ number_format($totalMargin, 0, ',', '.') }}
                            </h2>

                        </div>

                    </div>

                </div>

                <!-- TABLE -->
                <div class="table-responsive mt-5">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>Jam</th>

                                <th class="text-end">Transaksi</th>

                                <th class="text-end">Qty</th>

                                <th class="text-end">Omzet Kotor</th>

                                <th class="text-end">Retur</th>

                                <th class="text-end">Omzet Bersih</th>

                                <th class="text-end">HPP</th>

                                <th class="text-end">Margin Bersih</th>

                                <th class="text-end">Margin %</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($hourlyAnalysis as $data)

                                @php
                                    $retur = $data->retur ?? 0;

                                    $omzetBersih = $data->omzet_bersih ?? 0;

                                    $marginBersih = $data->margin_bersih ?? 0;

                                    $marginPercent = $omzetBersih > 0
                                        ? round(($marginBersih / $omzetBersih) * 100, 2)
                                        : 0;
                                @endphp

                                <tr>

                                    <td>
                                        <span class="hour-badge">
                                            {{ $data->hour_label }}
                                        </span>
                                    </td>

                                    <td class="text-end">
                                        {{ $data->transaction_count }}
                                    </td>

                                    <td class="text-end">
                                        {{ $data->total_qty }}
                                    </td>

                                    <td class="text-end text-muted">
                                        Rp {{ number_format($data->omzet_kotor, 0, ',', '.') }}
                                    </td>

                                    <td class="text-end text-danger">
                                        -Rp {{ number_format($retur, 0, ',', '.') }}
                                    </td>

                                    <td class="text-end text-success fw-bold">
                                        Rp {{ number_format($omzetBersih, 0, ',', '.') }}
                                    </td>

                                    <td class="text-end">
                                        Rp {{ number_format($data->total_cogs, 0, ',', '.') }}
                                    </td>

                                    <td class="text-end text-info fw-bold">
                                        Rp {{ number_format($marginBersih, 0, ',', '.') }}
                                    </td>

                                    <td class="text-end">
                                        <span class="badge bg-info">
                                            {{ $marginPercent }}%
                                        </span>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
