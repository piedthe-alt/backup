<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Grafik Penjualan - Stock Manager</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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

        .stat-card h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #10b981;
        }

        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table {
            font-size: 0.95rem;
        }

        .table thead {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }

        .table thead th {
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .table tbody td {
            padding: 1rem;
            border: none;
        }

        @media (max-width: 768px) {
            .header-section {
                padding: 1.5rem !important;
            }

            .header-content h2 {
                font-size: 1.3rem;
            }

            .header-section::before {
                width: 200px;
                height: 200px;
            }

            .btn-action {
                font-size: 0.9rem;
                padding: 8px 12px;
                width: 100%;
                margin-top: 0.5rem;
            }

            .form-control, .form-select {
                font-size: 0.9rem;
            }

            .stat-card h3 {
                font-size: 1.3rem;
            }

            .chart-container {
                padding: 1rem;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th, .table tbody td {
                padding: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .header-content {
                text-align: center;
            }

            .header-content h2 {
                font-size: 1.2rem;
            }

            .header-content small {
                font-size: 0.75rem;
            }

            .header-section {
                padding: 1rem !important;
            }

            .btn-action {
                font-size: 0.8rem;
                padding: 6px 8px;
                width: 100%;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .form-control, .form-select {
                font-size: 0.85rem;
            }

            .col-md-3, .col-md-4 {
                padding: 0.25rem;
            }

            .row.g-3 {
                gap: 0.5rem !important;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-card h6 {
                font-size: 0.75rem;
            }

            .stat-card h3 {
                font-size: 1.1rem;
            }

            .chart-container {
                padding: 0.75rem;
            }

            .table {
                font-size: 0.75rem;
            }

            .table thead th, .table tbody td {
                padding: 0.5rem;
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
                            <i class="fas fa-chart-line me-2"></i>Grafik Penjualan
                        </h2>

                        <small class="opacity-75">
                            Analisis penjualan dan margin dalam periode waktu
                        </small>

                    </div>

                    <a href="/" class="btn btn-action btn-light">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>

                </div>

            </div>

        </div>

        <!-- BODY -->
        <div class="card-body p-4">

            <!-- FILTER -->
            <form
                method="GET"
                action="/sales-chart"
                class="row g-3 mb-5"
            >

                <!-- START DATE -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        📅 Dari Tanggal
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        class="form-control"
                        value="{{ request('start_date') }}"
                    >

                </div>

                <!-- END DATE -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        📅 Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        class="form-control"
                        value="{{ request('end_date') }}"
                    >

                </div>

                <!-- TYPE -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        📊 Jenis Grafik
                    </label>

                    <select
                        name="type"
                        class="form-select"
                    >

                        <option
                            value="omzet"
                            {{ request('type') == 'omzet' ? 'selected' : '' }}
                        >
                            Omzet Bersih
                        </option>

                        <option
                            value="margin"
                            {{ request('type') == 'margin' ? 'selected' : '' }}
                        >
                            Margin Bersih
                        </option>

                        <option
                            value="retur"
                            {{ request('type') == 'retur' ? 'selected' : '' }}
                        >
                            Retur
                        </option>

                    </select>

                </div>

                <!-- BUTTON -->
                <div class="col-md-3 d-flex align-items-end">

                    <button class="btn btn-action btn-success fw-bold w-100">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>

                </div>

            </form>

            <!-- SUMMARY -->
            <div class="row mb-4">

                <!-- OMZET -->
                <div class="col-md-4 col-sm-6 mb-3 mb-md-0">

                    <div class="stat-card">

                        <h6>
                            <i class="fas fa-money-bill-wave me-2 text-success"></i>Total Omzet Bersih
                        </h6>

                        <h3 class="text-success">

                            Rp {{ number_format($sales->sum('omzet_bersih'), 0, ',', '.') }}

                        </h3>

                    </div>

                </div>

                <!-- MARGIN -->
                <div class="col-md-4 col-sm-6 mb-3 mb-md-0">

                    <div class="stat-card">

                        <h6>
                            <i class="fas fa-percent me-2 text-info"></i>Total Margin Bersih
                        </h6>

                        <h3 class="text-info">

                            Rp {{ number_format($sales->sum('margin_bersih'), 0, ',', '.') }}

                        </h3>

                    </div>

                </div>

                <!-- RETUR -->
                <div class="col-md-4 col-sm-6">

                    <div class="stat-card">

                        <h6>
                            <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Total Retur
                        </h6>

                        <h3 class="text-danger">

                            Rp {{ number_format($sales->sum('retur'), 0, ',', '.') }}

                        </h3>

                    </div>

                </div>

            </div>

            <!-- CHART -->
            <div class="chart-container mb-4">

                <canvas id="salesChart" height="100"></canvas>

            </div>

            <!-- TABLE -->
            <div class="table-responsive mt-5">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th><i class="fas fa-calendar me-2"></i>Tanggal</th>

                            <th><i class="fas fa-money-bill-wave me-2 text-success"></i>Omzet Bersih</th>

                            <th><i class="fas fa-percent me-2 text-info"></i>Margin Bersih</th>

                            <th><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Retur</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($sales as $item)

                        <tr>

                            <td>
                                {{ $item->tanggal }}
                            </td>

                            <td>

                                <strong class="text-success">Rp {{ number_format($item->omzet_bersih, 0, ',', '.') }}</strong>

                            </td>

                            <td>

                                <strong class="text-info">Rp {{ number_format($item->margin_bersih, 0, ',', '.') }}</strong>

                            </td>

                            <td class="text-danger">

                                <strong>Rp {{ number_format($item->retur, 0, ',', '.') }}</strong>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- CHART -->
<script>

const chartType = "{{ request('type', 'omzet') }}";

const labels = [

    @foreach($sales as $item)
        '{{ $item->tanggal }}',
    @endforeach

];

const omzetData = [

    @foreach($sales as $item)
        {{ $item->omzet_bersih }},
    @endforeach

];

const marginData = [

    @foreach($sales as $item)
        {{ $item->margin_bersih }},
    @endforeach

];

const returData = [

    @foreach($sales as $item)
        {{ $item->retur }},
    @endforeach

];

let selectedData = omzetData;
let selectedLabel = 'Omzet Bersih';

if (chartType === 'margin') {

    selectedData = marginData;
    selectedLabel = 'Margin Bersih';

}

if (chartType === 'retur') {

    selectedData = returData;
    selectedLabel = 'Retur';

}

const ctx = document.getElementById('salesChart');

new Chart(ctx, {

    type: 'line',

    data: {

        labels: labels,

        datasets: [

            {

                label: selectedLabel,

                data: selectedData,

                borderWidth: 3,

                tension: 0.3,

                fill: true

            }

        ]

    },

    options: {

        responsive: true,

        plugins: {

            legend: {

                display: true

            }

        },

        scales: {

            y: {

                ticks: {

                    callback: function(value) {

                        return 'Rp ' + value.toLocaleString('id-ID');

                    }

                }

            }

        }

    }

});

</script>

</body>
</html>
