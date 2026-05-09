<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Grafik Penjualan</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card border-0 shadow-lg rounded-4">

        <!-- HEADER -->
        <div
            class="card-header bg-success text-white p-4 rounded-top-4 d-flex justify-content-between align-items-center"
        >

            <h2 class="mb-0">
                📈 Grafik Penjualan
            </h2>

            <a href="/" class="btn btn-light">
                ← Kembali Produk
            </a>

        </div>

        <!-- BODY -->
        <div class="card-body">

            <!-- FILTER -->
            <form
                method="GET"
                action="/sales-chart"
                class="row g-3 mb-5"
            >

                <!-- START DATE -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Dari Tanggal
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        class="form-control form-control-lg"
                        value="{{ request('start_date') }}"
                    >

                </div>

                <!-- END DATE -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        class="form-control form-control-lg"
                        value="{{ request('end_date') }}"
                    >

                </div>

                <!-- TYPE -->
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Jenis Grafik
                    </label>

                    <select
                        name="type"
                        class="form-select form-select-lg"
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

                    <button class="btn btn-success btn-lg w-100">
                        Tampilkan Grafik
                    </button>

                </div>

            </form>

            <!-- SUMMARY -->
            <div class="row mb-4">

                <!-- OMZET -->
                <div class="col-md-4">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Total Omzet Bersih
                            </h6>

                            <h3 class="fw-bold">

                                Rp {{ number_format($sales->sum('omzet_bersih'), 0, ',', '.') }}

                            </h3>

                        </div>

                    </div>

                </div>

                <!-- MARGIN -->
                <div class="col-md-4">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Total Margin Bersih
                            </h6>

                            <h3 class="fw-bold">

                                Rp {{ number_format($sales->sum('margin_bersih'), 0, ',', '.') }}

                            </h3>

                        </div>

                    </div>

                </div>

                <!-- RETUR -->
                <div class="col-md-4">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Total Retur
                            </h6>

                            <h3 class="fw-bold text-danger">

                                Rp {{ number_format($sales->sum('retur'), 0, ',', '.') }}

                            </h3>

                        </div>

                    </div>

                </div>

            </div>

            <!-- CHART -->
            <div class="bg-white p-4 rounded-4 shadow-sm">

                <canvas id="salesChart" height="100"></canvas>

            </div>

            <!-- TABLE -->
            <div class="table-responsive mt-5">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Tanggal</th>

                            <th>Omzet Bersih</th>

                            <th>Margin Bersih</th>

                            <th>Retur</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($sales as $item)

                        <tr>

                            <td>
                                {{ $item->tanggal }}
                            </td>

                            <td>

                                Rp {{ number_format($item->omzet_bersih, 0, ',', '.') }}

                            </td>

                            <td>

                                Rp {{ number_format($item->margin_bersih, 0, ',', '.') }}

                            </td>

                            <td class="text-danger">

                                Rp {{ number_format($item->retur, 0, ',', '.') }}

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
