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
            0%, 100% {
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

                <!-- SUMMARY STATISTICS -->
                <div class="row mb-4">

                    <div class="col-md-4 mb-3">

                        <div class="stat-card border-start border-primary border-4">

                            <h6>
                                <i class="fas fa-exchange-alt me-2 text-primary"></i>
                                Total Transaksi
                            </h6>

                            <h2 class="fw-bold text-primary mb-0">
                                {{ $totalTransactions }}
                            </h2>

                        </div>

                    </div>

                    <div class="col-md-4 mb-3">

                        <div class="stat-card border-start border-success border-4">

                            <h6>
                                <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                Omzet Bersih
                            </h6>

                            <h2 class="fw-bold text-success mb-0">
                                Rp {{ number_format($totalAmount, 0, ',', '.') }}
                            </h2>

                        </div>

                    </div>

                    <div class="col-md-4 mb-3">

                        <div class="stat-card border-start border-info border-4">

                            <h6>
                                <i class="fas fa-chart-pie me-2 text-info"></i>
                                Margin Bersih
                            </h6>

                            <h2 class="fw-bold text-info mb-0">
                                Rp {{ number_format($totalMargin, 0, ',', '.') }}
                            </h2>

                        </div>

                    </div>

                </div>

                <!-- PEAK HOUR ALERT -->
                @if ($peakHour)
                    <div class="alert alert-warning alert-dismissible fade show border-3 border-warning" role="alert">

                        <div class="d-flex align-items-center gap-3">

                            <i class="fas fa-fire fa-2x text-warning"></i>

                            <div>

                                <h5 class="mb-1 fw-bold">
                                    ⏰ JAM PUNCAK OMZET BERSIH
                                </h5>

                                <p class="mb-0">
                                    <strong>{{ $peakHour->hour_label }}</strong> dengan omzet bersih
                                    <strong>Rp {{ number_format($peakHour->total_amount, 0, ',', '.') }}</strong>
                                    ({{ $peakHour->transaction_count }} transaksi)
                                </p>

                            </div>

                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>
                @endif

                <!-- CHARTS -->
                <div class="row mb-4">

                    <div class="col-lg-6 mb-4">

                        <div class="chart-container">

                            <h6 class="fw-bold mb-3">📊 Omzet Bersih Per Jam</h6>

                            <canvas id="salesChart"></canvas>

                        </div>

                    </div>

                    <div class="col-lg-6 mb-4">

                        <div class="chart-container">

                            <h6 class="fw-bold mb-3">📈 Transaksi Per Jam</h6>

                            <canvas id="transactionChart"></canvas>

                        </div>

                    </div>

                </div>

                <div class="row mb-4">

                    <div class="col-lg-6 mb-4">

                        <div class="chart-container">

                            <h6 class="fw-bold mb-3">💰 Margin Per Jam</h6>

                            <canvas id="marginChart"></canvas>

                        </div>

                    </div>

                    <div class="col-lg-6 mb-4">

                        <div class="chart-container">

                            <h6 class="fw-bold mb-3">📦 Kuantitas Terjual Per Jam</h6>

                            <canvas id="quantityChart"></canvas>

                        </div>

                    </div>

                </div>

                <!-- TABLE DETAIL -->
                <div class="table-responsive mt-5">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>Jam</th>

                                <th class="text-end">Transaksi</th>

                                <th class="text-end">Kuantitas</th>

                                <th class="text-end">Omzet Kotor</th>

                                <th class="text-end">Retur</th>

                                <th class="text-end">Omzet Bersih</th>

                                <th class="text-end">Total HPP</th>

                                <th class="text-end">Margin</th>

                                <th class="text-end">Margin %</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($hourlyAnalysis as $data)
                                <tr @if ($peakHour && $peakHour->hour == $data->hour) class="peak-indicator" @endif>

                                    <td>

                                        <span class="hour-badge">{{ $data->hour_label }}</span>

                                    </td>

                                    <td class="text-end">

                                        <strong>{{ $data->transaction_count }}</strong>

                                    </td>

                                    <td class="text-end">

                                        {{ $data->total_qty }}

                                    </td>

                                    <td class="text-end">

                                        <strong class="text-muted">

                                            Rp {{ number_format($data->omzet_kotor, 0, ',', '.') }}

                                        </strong>

                                    </td>

                                    <td class="text-end">

                                        <strong class="text-danger">

                                            -Rp {{ number_format($data->omzet_kotor - $data->total_amount, 0, ',', '.') }}

                                        </strong>

                                    </td>

                                    <td class="text-end">

                                        <strong class="text-success">

                                            Rp {{ number_format($data->total_amount, 0, ',', '.') }}

                                        </strong>

                                    </td>

                                    <td class="text-end">

                                        Rp {{ number_format($data->total_cogs, 0, ',', '.') }}

                                    </td>

                                    <td class="text-end">

                                        <strong class="text-info">

                                            Rp {{ number_format($data->total_margin, 0, ',', '.') }}

                                        </strong>

                                    </td>

                                    <td class="text-end">

                                        <span class="badge bg-info">{{ $data->margin_percent }}%</span>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <script>
        const hourlyData = @json($hourlyAnalysis);

        const hours = hourlyData.map(d => d.hour_label);

        const amounts = hourlyData.map(d => d.total_amount);

        const transactions = hourlyData.map(d => d.transaction_count);

        const margins = hourlyData.map(d => d.total_margin);

        const quantities = hourlyData.map(d => d.total_qty);

        // Sales Chart
        new Chart(document.getElementById('salesChart'), {

            type: 'bar',

            data: {

                labels: hours,

                datasets: [{

                    label: 'Omzet Bersih (Rp)',

                    data: amounts,

                    backgroundColor: '#8b5cf6',

                    borderColor: '#7c3aed',

                    borderWidth: 2,

                    borderRadius: 8,

                    fill: true,

                }]

            },

            options: {

                responsive: true,

                plugins: {

                    legend: {

                        display: true,

                        labels: {

                            font: {

                                size: 12,

                                weight: 'bold'

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function (value) {

                                return 'Rp ' + value.toLocaleString('id-ID');

                            }

                        }

                    }

                }

            }

        });

        // Transaction Chart
        new Chart(document.getElementById('transactionChart'), {

            type: 'line',

            data: {

                labels: hours,

                datasets: [{

                    label: 'Jumlah Transaksi',

                    data: transactions,

                    borderColor: '#7c3aed',

                    backgroundColor: 'rgba(139, 92, 246, 0.1)',

                    borderWidth: 3,

                    fill: true,

                    tension: 0.4,

                    pointRadius: 6,

                    pointBackgroundColor: '#8b5cf6',

                    pointBorderColor: '#fff',

                    pointBorderWidth: 2,

                }]

            },

            options: {

                responsive: true,

                plugins: {

                    legend: {

                        display: true,

                        labels: {

                            font: {

                                size: 12,

                                weight: 'bold'

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true

                    }

                }

            }

        });

        // Margin Chart
        new Chart(document.getElementById('marginChart'), {

            type: 'bar',

            data: {

                labels: hours,

                datasets: [{

                    label: 'Margin (Rp)',

                    data: margins,

                    backgroundColor: '#06b6d4',

                    borderColor: '#0891b2',

                    borderWidth: 2,

                    borderRadius: 8,

                }]

            },

            options: {

                responsive: true,

                plugins: {

                    legend: {

                        display: true,

                        labels: {

                            font: {

                                size: 12,

                                weight: 'bold'

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function (value) {

                                return 'Rp ' + value.toLocaleString('id-ID');

                            }

                        }

                    }

                }

            }

        });

        // Quantity Chart
        new Chart(document.getElementById('quantityChart'), {

            type: 'line',

            data: {

                labels: hours,

                datasets: [{

                    label: 'Kuantitas',

                    data: quantities,

                    borderColor: '#10b981',

                    backgroundColor: 'rgba(16, 185, 129, 0.1)',

                    borderWidth: 3,

                    fill: true,

                    tension: 0.4,

                    pointRadius: 6,

                    pointBackgroundColor: '#10b981',

                    pointBorderColor: '#fff',

                    pointBorderWidth: 2,

                }]

            },

            options: {

                responsive: true,

                plugins: {

                    legend: {

                        display: true,

                        labels: {

                            font: {

                                size: 12,

                                weight: 'bold'

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true

                    }

                }

            }

        });
    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
