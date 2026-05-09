<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Analysis</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card border-0 shadow-lg rounded-4">

            <!-- HEADER -->
            <div class="card-header bg-warning p-4 rounded-top-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h2 class="mb-0 fw-bold">
                            🤖 AI Inventory Analysis
                        </h2>

                        <small>
                            Analisis stok & penjualan menggunakan Gemini AI
                        </small>

                    </div>

                    <a href="/" class="btn btn-dark btn-lg">
                        ← Kembali
                    </a>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <div class="row g-4 align-items-end">

                    <!-- GROUP -->
                    <div class="col-md-5">

                        <label class="form-label fw-bold">
                            Pilih Group Produk
                        </label>

                        <select id="group" class="form-select form-select-lg">

                            @foreach ($groups as $group)
                                <option value="{{ $group->name }}">
                                    {{ $group->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <!-- HARI -->
                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Periode Analisis
                        </label>

                        <select id="days" class="form-select form-select-lg">

                            <option value="7">7 Hari</option>

                            <option value="30" selected>
                                30 Hari
                            </option>

                            <option value="90">
                                90 Hari
                            </option>

                            <option value="180">
                                180 Hari
                            </option>

                        </select>

                    </div>

                    <!-- BUTTON -->
                    <div class="col-md-4">

                        <button class="btn btn-primary btn-lg w-100" onclick="generateAnalysis()">
                            ⚡ Generate Analysis
                        </button>

                    </div>

                </div>

                <!-- RESULT -->
                <div id="result" class="mt-5"></div>

            </div>

        </div>

    </div>

    <script>
        async function generateAnalysis() {

            const group = document.getElementById('group').value;

            const days = document.getElementById('days').value;

            const result = document.getElementById('result');

            /*
            |--------------------------------------------------------------------------
            | LOADING
            |--------------------------------------------------------------------------
            */

            result.innerHTML = `

        <div class="text-center py-5">

            <div class="spinner-border text-primary mb-4"></div>

            <h4 class="fw-bold">
                AI Sedang Menganalisis...
            </h4>

            <p class="text-muted">
                Mohon tunggu beberapa saat
            </p>

        </div>

    `;

            /*
            |--------------------------------------------------------------------------
            | FETCH
            |--------------------------------------------------------------------------
            */

            try {

                const response = await fetch(

                    `/ai-analysis?group=${encodeURIComponent(group)}&days=${days}`

                );

                const data = await response.json();

                /*
                |--------------------------------------------------------------------------
                | ERROR
                |--------------------------------------------------------------------------
                */

                if (!data.success) {

                    result.innerHTML = `

                <div class="alert alert-danger">

                    Gagal mengambil data AI

                </div>

            `;

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | FORMAT ANALYSIS
                |--------------------------------------------------------------------------
                */

                let analysis = data.analysis;


                /*
    |--------------------------------------------------------------------------
    | FORMAT NOMOR MENJADI CARD
    |--------------------------------------------------------------------------
    */

                analysis = analysis

                    /*
                    |--------------------------------------------------------------------------
                    | BOLD
                    |--------------------------------------------------------------------------
                    */

                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')

                    /*
                    |--------------------------------------------------------------------------
                    | ICON STATUS
                    |--------------------------------------------------------------------------
                    */

                    .replace(/KRITIS/g, '🔴 KRITIS')
                    .replace(/MENIPIS/g, '🟠 MENIPIS')
                    .replace(/AMAN/g, '🟢 AMAN')
                    .replace(/OVERSTOCK/g, '🟡 OVERSTOCK')
                    .replace(/SLOW MOVING/g, '⚪ SLOW MOVING')
                    .replace(/PALING LARIS/g, '🔥 PALING LARIS')
                    .replace(/FAST MOVING/g, '🚀 FAST MOVING')

                    /*
                    |--------------------------------------------------------------------------
                    | ICON LABEL
                    |--------------------------------------------------------------------------
                    */

                    .replace(/Stock sekarang:/g, '📦 Stock sekarang:')
                    .replace(/Estimasi habis:/g, '⏳ Estimasi habis:')
                    .replace(/Trend:/g, '📈 Trend:')
                    .replace(/Status:/g, '🏷️ Status:')
                    .replace(/Rekomendasi:/g, '🛒 Rekomendasi:')

                    /*
                    |--------------------------------------------------------------------------
                    | ENTER
                    |--------------------------------------------------------------------------
                    */

                    .replace(/\n/g, '<br>')

                    /*
                    |--------------------------------------------------------------------------
                    | FORMAT CARD
                    |--------------------------------------------------------------------------
                    */

                    .replace(

                        /(\d+\.\s.*?)(?=\d+\.|$)/gs,

                        function(match) {

                            /*
                            |--------------------------------------------------------------------------
                            | AMBIL NAMA PRODUK
                            |--------------------------------------------------------------------------
                            */

                            const titleMatch = match.match(/\d+\.\s(.*?)(<br>|-)/);

                            const title = titleMatch

                                ?
                                titleMatch[1]

                                :
                                'Produk';

                            return `

                <div class="card mb-4 border-0 shadow rounded-4 overflow-hidden">

                    <div class="card-header bg-dark text-white p-3">

                        <h5 class="mb-0 fw-bold">

                            📦 ${title}

                        </h5>

                    </div>

                    <div class="card-body p-4">

                        <div class="fs-5 lh-lg">

                            ${match.trim()}

                        </div>

                    </div>

                </div>

            `;
                        }

                    );

                /*
                |--------------------------------------------------------------------------
                | SHOW
                |--------------------------------------------------------------------------
                */

                result.innerHTML = `

            <div class="card border-0 shadow rounded-4">

                <div class="card-header bg-dark text-white p-4 rounded-top-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h4 class="mb-1">

                                📊 Hasil AI Analysis

                            </h4>

                            <small>

                                Group:
                                ${data.group}

                                •

                                ${data.days} Hari

                                •

                                ${data.total_products} Produk

                            </small>

                        </div>

                    </div>

                </div>

                <div class="card-body p-4 fs-5">

                    ${analysis}

                </div>

            </div>

        `;

            } catch (err) {

                console.log(err);

                result.innerHTML = `

            <div class="alert alert-danger">

                Terjadi kesalahan saat mengambil analisis AI

            </div>

        `;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
