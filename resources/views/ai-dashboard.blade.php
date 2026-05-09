<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Analysis - Stock Manager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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

        .form-select, .form-control {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-select:focus, .form-control:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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

            .form-select, .form-control {
                font-size: 0.9rem;
            }

            .row.g-4 {
                gap: 0.5rem !important;
            }

            .col-md-5, .col-md-3, .col-md-4 {
                padding: 0.25rem;
            }

            .card-body {
                padding: 1rem !important;
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

            .form-select, .form-control {
                font-size: 0.85rem;
            }

            .card-header {
                padding: 1rem !important;
            }

            .card-body {
                padding: 0.75rem !important;
            }

            .card-body .fs-5 {
                font-size: 0.9rem !important;
            }
        }
    </style>

</head>

<body>

    <div class="container-fluid py-4">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="header-section p-4 rounded-top-4">

                <div class="header-content">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h2 class="mb-2 fw-bold">
                                <i class="fas fa-robot me-2"></i>AI Inventory Analysis
                            </h2>

                            <small class="opacity-75">
                                Analisis stok & penjualan menggunakan Gemini AI
                            </small>

                        </div>

                        <a href="/" class="btn btn-action btn-dark">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <div class="row g-4 align-items-end">

                    <!-- GROUP -->
                    <div class="col-md-5">

                        <label class="form-label fw-bold">
                            📂 Pilih Group Produk
                        </label>

                        <select id="group" class="form-select">

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
                            📅 Periode Analisis
                        </label>

                        <select id="days" class="form-select">

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

                        <button class="btn btn-action btn-warning fw-bold w-100" onclick="generateAnalysis()">
                            <i class="fas fa-bolt"></i> Generate Analysis
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

                        /(^|\n)(\d+\.\s.*?)(?=\n\d+\.\s|$)/gs,

                        function(_, __, match) {

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
