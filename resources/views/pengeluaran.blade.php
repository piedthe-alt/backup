<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengeluaran Toko</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary: #7c3aed;
            --primary-dark: #5b21b6;
            --primary-light: #ede9fe;
        }

        body {
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── HEADER ── */
        .page-header {
            background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
            border-radius: 20px;
            padding: 28px 32px;
            color: white;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.25);
        }

        /* ── TABS ── */
        .tab-nav {
            background: white;
            border-radius: 16px;
            padding: 6px;
            margin-bottom: 20px;
            display: flex;
            gap: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .tab-btn {
            flex: 1;
            border: none;
            background: transparent;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 600;
            font-size: 14px;
            color: #64748b;
            cursor: pointer;
            transition: all .2s;
        }

        .tab-btn.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .tab-btn:hover:not(.active) {
            background: var(--primary-light);
            color: var(--primary);
        }

        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* ── CARDS ── */
        .card-modern {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 24px;
            margin-bottom: 20px;
        }

        .card-modern h5 {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 20px;
            color: #1e293b;
        }

        /* ── FORM ── */
        .form-label { font-weight: 600; font-size: 13px; color: #475569; }

        .form-control, .form-select {
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            min-height: 46px;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            border: none;
            color: white;
            font-weight: 700;
            border-radius: 12px;
            padding: 12px 28px;
            transition: all .2s;
        }

        .btn-primary-custom:hover { opacity: .9; color: white; transform: translateY(-1px); }

        /* ── TABLE ── */
        .table thead th {
            background: #1e293b;
            color: white;
            border: none;
            font-size: 13px;
            padding: 14px 16px;
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px 16px;
            font-size: 14px;
            border-color: #f1f5f9;
        }

        .table tbody tr:hover { background: #faf5ff; }

        /* ── STAT CARDS ── */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary);
            height: 100%;
        }

        .stat-card .stat-value {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
            margin: 6px 0 2px;
        }

        .stat-card .stat-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .stat-card .stat-icon {
            font-size: 28px;
            opacity: .15;
            position: absolute;
            right: 16px;
            top: 16px;
        }

        /* ── STATUS BADGE ── */
        .badge-stabil { background: #dcfce7; color: #15803d; }
        .badge-fluktuatif { background: #fef9c3; color: #92400e; }
        .badge-tidak_menentu { background: #fee2e2; color: #b91c1c; }

        /* ── CATEGORY BAR ── */
        .cat-bar-wrap { margin-bottom: 12px; }
        .cat-bar-label { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px; }
        .cat-bar-label .cat-name { font-weight: 600; color: #1e293b; }
        .cat-bar-label .cat-amount { color: #64748b; }
        .cat-bar-track { background: #f1f5f9; border-radius: 99px; height: 10px; overflow: hidden; }
        .cat-bar-fill { border-radius: 99px; height: 10px; transition: width .6s ease; }

        /* ── ALERT FLASH ── */
        .flash-alert {
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            padding: 14px 20px;
        }

        /* ── MODAL ── */
        .modal-header-custom {
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            color: white;
            border-radius: 16px 16px 0 0;
        }

        .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }

        .cat-list-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px 14px;
            margin-bottom: 8px;
        }

        .cat-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .page-header { padding: 20px; }
            .card-modern { padding: 16px; }
            .tab-btn { font-size: 12px; padding: 9px 10px; }
            .stat-card .stat-value { font-size: 18px; }
            .table-wrap-mobile { display: none; }
            .mobile-list { display: block; }
        }

        @media (min-width: 769px) {
            .mobile-list { display: none; }
        }

        .mobile-expense-card {
            background: #f8fafc;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 10px;
            border: 1px solid #e2e8f0;
        }

        .btn-back-white {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-back-white:hover { background: rgba(255,255,255,0.25); color: white; }
    </style>
</head>

<body>
<div class="container py-4">

    {{-- ===================== HEADER ===================== --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-wallet me-2"></i>
                    Pengeluaran Toko
                </h2>
                <div class="opacity-75" style="font-size:14px">
                    Catat & analisis biaya operasional harian toko Anda
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-back-white px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalKategori">
                    <i class="fas fa-tags me-2"></i>Kelola Kategori
                </button>
                <a href="/" class="btn btn-back-white px-4 py-2">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    {{-- ===================== FLASH ALERTS ===================== --}}
    @if(session('success'))
        <div class="alert flash-alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert flash-alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ===================== TAB NAV ===================== --}}
    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchTab('catat', this)" id="btn-catat">
            <i class="fas fa-plus-circle me-1"></i>Catat Pengeluaran
        </button>
        <button class="tab-btn" onclick="switchTab('riwayat', this)" id="btn-riwayat">
            <i class="fas fa-list me-1"></i>Riwayat
        </button>
        <button class="tab-btn" onclick="switchTab('analisis', this)" id="btn-analisis">
            <i class="fas fa-chart-line me-1"></i>Analisis Biaya
        </button>
    </div>

    {{-- ===================== TAB: CATAT ===================== --}}
    <div class="tab-pane active" id="tab-catat">
        <div class="card-modern">
            <h5><i class="fas fa-plus-circle me-2 text-purple" style="color:var(--primary)"></i>Input Pengeluaran Baru</h5>

            @if($categories->isEmpty())
                <div class="alert alert-warning border-0" style="border-radius:12px">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada kategori. Silakan <strong>tambah kategori terlebih dahulu</strong> via tombol <em>"Kelola Kategori"</em> di atas.
                </div>
            @else
                <form method="POST" action="/pengeluaran/store" id="form-catat">
                    @csrf
                    <div class="row g-3">
                        {{-- KATEGORI --}}
                        <div class="col-md-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required id="select-category">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- NOMINAL --}}
                        <div class="col-md-3">
                            <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="text" name="amount" id="amount-input" class="form-control"
                                placeholder="Contoh: 150.000" required
                                oninput="formatRupiah(this)" autocomplete="off">
                        </div>

                        {{-- TANGGAL --}}
                        <div class="col-md-2">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- KETERANGAN --}}
                        <div class="col-md-3">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="description" class="form-control"
                                placeholder="Opsional — misal: Token PLN Mei">
                        </div>

                        {{-- TOMBOL --}}
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-custom w-100" style="min-height:46px">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>

        {{-- QUICK SUMMARY --}}
        @if($categories->isNotEmpty())
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="stat-card position-relative">
                    <i class="fas fa-calendar-day stat-icon"></i>
                    <div class="stat-label">Estimasi / Hari</div>
                    <div class="stat-value">Rp {{ number_format($bestEstimate, 0, ',', '.') }}</div>
                    <small class="text-muted" style="font-size:11px">Weighted avg (7 hari)</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card position-relative" style="border-color:#f59e0b">
                    <i class="fas fa-calendar-alt stat-icon" style="color:#f59e0b"></i>
                    <div class="stat-label">Bulan Ini</div>
                    <div class="stat-value" style="color:#d97706">Rp {{ number_format($spentThisMonth, 0, ',', '.') }}</div>
                    <small class="text-muted" style="font-size:11px">Sudah dicatat</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card position-relative" style="border-color:#10b981">
                    <i class="fas fa-chart-bar stat-icon" style="color:#10b981"></i>
                    <div class="stat-label">Proyeksi Bulan Ini</div>
                    <div class="stat-value" style="color:#059669">Rp {{ number_format($projectedMonth, 0, ',', '.') }}</div>
                    <small class="text-muted" style="font-size:11px">Estimasi total</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card position-relative" style="border-color:#e11d48">
                    <i class="fas fa-history stat-icon" style="color:#e11d48"></i>
                    <div class="stat-label">Total (90 hari)</div>
                    <div class="stat-value" style="color:#be123c">Rp {{ number_format($totalAll, 0, ',', '.') }}</div>
                    <small class="text-muted" style="font-size:11px">{{ $activeDays }} hari aktif</small>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ===================== TAB: RIWAYAT ===================== --}}
    <div class="tab-pane" id="tab-riwayat">
        <div class="card-modern">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                <h5 class="mb-0"><i class="fas fa-list me-2" style="color:var(--primary)"></i>Riwayat Pengeluaran</h5>
                <form method="GET" action="/pengeluaran" class="d-flex gap-2 flex-wrap">
                    <input type="hidden" name="tab" value="riwayat">
                    <input type="date" name="start" class="form-control form-control-sm" value="{{ $filterStart }}" style="min-height:36px;border-radius:10px">
                    <input type="date" name="end" class="form-control form-control-sm" value="{{ $filterEnd }}" style="min-height:36px;border-radius:10px">
                    <button type="submit" class="btn btn-sm btn-primary-custom px-3">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </form>
            </div>

            @if($expenses->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                    <h6 class="fw-bold">Belum ada data pengeluaran</h6>
                    <small>pada periode {{ \Carbon\Carbon::parse($filterStart)->format('d M Y') }} – {{ \Carbon\Carbon::parse($filterEnd)->format('d M Y') }}</small>
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                    <small class="text-muted">{{ $expenses->count() }} transaksi</small>
                    <span class="fw-bold" style="color:var(--primary)">
                        Total: Rp {{ number_format($totalPeriod, 0, ',', '.') }}
                    </span>
                </div>

                {{-- DESKTOP TABLE --}}
                <div class="table-wrap-mobile table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th class="text-end">Nominal</th>
                                <th width="80" class="text-center">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $exp)
                            <tr>
                                <td>
                                    <span class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($exp->expense_date)->format('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge px-3 py-2" style="background:{{ $exp->category_color }}20; color:{{ $exp->category_color }}; border-radius:8px; font-size:12px; font-weight:700">
                                        <i class="fas fa-{{ $exp->category_icon }} me-1"></i>
                                        {{ $exp->category_name }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $exp->description ?: '—' }}</td>
                                <td class="text-end fw-bold" style="color:var(--primary)">
                                    Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="/pengeluaran/delete/{{ $exp->id }}"
                                        onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#faf5ff">
                                <td colspan="3" class="fw-bold text-end pe-3">Total Periode:</td>
                                <td class="text-end fw-bold fs-6" style="color:var(--primary)">
                                    Rp {{ number_format($totalPeriod, 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- MOBILE LIST --}}
                <div class="mobile-list">
                    @foreach($expenses as $exp)
                    <div class="mobile-expense-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge px-3 py-2" style="background:{{ $exp->category_color }}20; color:{{ $exp->category_color }}; border-radius:8px; font-size:12px; font-weight:700">
                                <i class="fas fa-{{ $exp->category_icon }} me-1"></i>
                                {{ $exp->category_name }}
                            </span>
                            <strong style="color:var(--primary)">Rp {{ number_format($exp->amount, 0, ',', '.') }}</strong>
                        </div>
                        <div class="text-muted" style="font-size:13px">{{ $exp->description ?: '—' }}</div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M Y') }}</small>
                            <form method="POST" action="/pengeluaran/delete/{{ $exp->id }}"
                                onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:11px">
                                    <i class="fas fa-trash-alt me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-end fw-bold mt-2" style="color:var(--primary)">
                        Total: Rp {{ number_format($totalPeriod, 0, ',', '.') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ===================== TAB: ANALISIS ===================== --}}
    <div class="tab-pane" id="tab-analisis">

        {{-- STATUS PENGELUARAN --}}
        <div class="card-modern">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h5 class="mb-0"><i class="fas fa-brain me-2" style="color:var(--primary)"></i>Analisis Cerdas Biaya Operasional</h5>
                <span class="badge px-3 py-2 badge-{{ $stabilityStatus }}" style="border-radius:99px; font-size:13px">
                    @if($stabilityStatus === 'stabil')
                        <i class="fas fa-check-circle me-1"></i>Pengeluaran Stabil
                    @elseif($stabilityStatus === 'fluktuatif')
                        <i class="fas fa-exclamation-triangle me-1"></i>Pengeluaran Fluktuatif
                    @else
                        <i class="fas fa-times-circle me-1"></i>Pengeluaran Tidak Menentu
                    @endif
                    (CV {{ number_format($cvPercent, 1) }}%)
                </span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card position-relative">
                        <i class="fas fa-chart-line stat-icon"></i>
                        <div class="stat-label">Avg Sederhana / Hari</div>
                        <div class="stat-value">Rp {{ number_format($avgSimple, 0, ',', '.') }}</div>
                        <small class="text-muted" style="font-size:11px">90 hari ÷ {{ $activeDays }} hari aktif</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card position-relative" style="border-color:#8b5cf6">
                        <i class="fas fa-wave-square stat-icon" style="color:#8b5cf6"></i>
                        <div class="stat-label">Moving Avg 7 Hari</div>
                        <div class="stat-value" style="color:#7c3aed">Rp {{ number_format($movingAvg7, 0, ',', '.') }}</div>
                        <small class="text-muted" style="font-size:11px">Tren terkini</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card position-relative" style="border-color:#f59e0b">
                        <i class="fas fa-bullseye stat-icon" style="color:#f59e0b"></i>
                        <div class="stat-label">Estimasi Terbaik / Hari</div>
                        <div class="stat-value" style="color:#d97706">Rp {{ number_format($bestEstimate, 0, ',', '.') }}</div>
                        <small class="text-muted" style="font-size:11px">60% MA7 + 40% avg</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card position-relative" style="border-color:#10b981">
                        <i class="fas fa-calendar-check stat-icon" style="color:#10b981"></i>
                        <div class="stat-label">Proyeksi Bulan Ini</div>
                        <div class="stat-value" style="color:#059669">Rp {{ number_format($projectedMonth, 0, ',', '.') }}</div>
                        <small class="text-muted" style="font-size:11px">Sudah: Rp {{ number_format($spentThisMonth, 0, ',', '.') }}</small>
                    </div>
                </div>
            </div>

            {{-- PENJELASAN RUMUS --}}
            <div style="background:#f8f4ff; border-radius:14px; padding:16px; border-left:4px solid var(--primary)">
                <div class="fw-bold mb-2" style="color:var(--primary); font-size:13px">
                    <i class="fas fa-info-circle me-1"></i>Cara Kerja Estimasi
                </div>
                <div style="font-size:13px; color:#475569; line-height:1.8">
                    <strong>Estimasi Terbaik/Hari</strong> = (60% × Moving Avg 7 Hari) + (40% × Avg Sederhana 90 Hari)<br>
                    Rumus ini memprioritaskan tren terkini tetapi tetap mempertimbangkan pola jangka panjang.<br>
                    <strong>Koefisien Variasi {{ number_format($cvPercent, 1) }}%</strong> →
                    @if($stabilityStatus === 'stabil') pengeluaran Anda sangat konsisten. ✅
                    @elseif($stabilityStatus === 'fluktuatif') ada variasi moderat, wajar untuk toko. ⚠️
                    @else pengeluaran sangat tidak menentu, pertimbangkan anggaran tetap. ❗
                    @endif
                </div>
            </div>
        </div>

        {{-- BREAKDOWN KATEGORI --}}
        @if($categoryBreakdown->isNotEmpty())
        <div class="card-modern">
            <h5><i class="fas fa-tags me-2" style="color:var(--primary)"></i>Breakdown per Kategori (90 Hari)</h5>
            @php $maxCat = $categoryBreakdown->max('total'); @endphp
            @foreach($categoryBreakdown as $cat)
                @php
                    $pct = $maxCat > 0 ? ($cat->total / $maxCat) * 100 : 0;
                    $pctTotal = $totalAll > 0 ? ($cat->total / $totalAll) * 100 : 0;
                @endphp
                <div class="cat-bar-wrap">
                    <div class="cat-bar-label">
                        <span class="cat-name">
                            <i class="fas fa-{{ $cat->icon }} me-1" style="color:{{ $cat->color }}"></i>
                            {{ $cat->name }}
                        </span>
                        <span class="cat-amount">
                            Rp {{ number_format($cat->total, 0, ',', '.') }}
                            <span class="badge ms-1" style="background:{{ $cat->color }}20; color:{{ $cat->color }}; border-radius:6px; font-size:11px">
                                {{ number_format($pctTotal, 1) }}%
                            </span>
                        </span>
                    </div>
                    <div class="cat-bar-track">
                        <div class="cat-bar-fill" style="width:{{ $pct }}%; background:{{ $cat->color }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        {{-- CHART TREN 30 HARI --}}
        <div class="card-modern">
            <h5><i class="fas fa-chart-area me-2" style="color:var(--primary)"></i>Tren Pengeluaran 30 Hari Terakhir</h5>
            <canvas id="trendChart" height="100"></canvas>
        </div>

    </div>

</div>

{{-- ===================== MODAL KELOLA KATEGORI ===================== --}}
<div class="modal fade" id="modalKategori" tabindex="-1" aria-labelledby="modalKategoriLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom border-0">
                <h5 class="modal-title fw-bold" id="modalKategoriLabel">
                    <i class="fas fa-tags me-2"></i>Kelola Kategori Pengeluaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">

                    {{-- FORM TAMBAH --}}
                    <div class="col-md-5">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-plus-circle me-1 text-success"></i>Tambah Kategori Baru
                        </h6>
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" id="new-cat-name" class="form-control" placeholder="Contoh: Listrik, Internet, Makan...">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-8">
                                <label class="form-label">Icon (Font Awesome)</label>
                                <select id="new-cat-icon" class="form-select">
                                    <option value="bolt">⚡ Listrik (bolt)</option>
                                    <option value="wifi">📶 Internet (wifi)</option>
                                    <option value="utensils">🍽️ Makan (utensils)</option>
                                    <option value="car">🚗 Transport (car)</option>
                                    <option value="box">📦 Barang (box)</option>
                                    <option value="tools">🔧 Peralatan (tools)</option>
                                    <option value="phone">📱 Telepon (phone)</option>
                                    <option value="water">💧 Air (water)</option>
                                    <option value="gas-pump">⛽ BBM (gas-pump)</option>
                                    <option value="broom">🧹 Kebersihan (broom)</option>
                                    <option value="tag" selected>🏷️ Lainnya (tag)</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Warna</label>
                                <input type="color" id="new-cat-color" class="form-control form-control-color w-100" value="#6366f1" style="min-height:46px; border-radius:12px">
                            </div>
                        </div>
                        <div id="cat-error" class="alert alert-danger border-0 py-2" style="border-radius:10px; display:none; font-size:13px"></div>
                        <button class="btn btn-primary-custom w-100" onclick="tambahKategori()" id="btn-tambah-cat">
                            <i class="fas fa-save me-2"></i>Simpan Kategori
                        </button>
                    </div>

                    {{-- LIST KATEGORI --}}
                    <div class="col-md-7">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-list me-1" style="color:var(--primary)"></i>Kategori yang Ada
                        </h6>
                        <div id="cat-list" style="max-height:320px; overflow-y:auto">
                            @forelse($categories as $cat)
                                <div class="cat-list-item" id="cat-item-{{ $cat->id }}">
                                    <div class="cat-dot" style="background:{{ $cat->color }}"></div>
                                    <i class="fas fa-{{ $cat->icon }}" style="color:{{ $cat->color }}; width:16px"></i>
                                    <span class="fw-semibold flex-grow-1" style="font-size:14px">{{ $cat->name }}</span>
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:8px; font-size:12px"
                                        onclick="hapusKategori({{ $cat->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            @empty
                                <div id="empty-cat" class="text-center text-muted py-4">
                                    <i class="fas fa-tags fa-2x mb-2 opacity-25"></i>
                                    <div style="font-size:13px">Belum ada kategori</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" style="border-radius:10px" data-bs-dismiss="modal">
                    Tutup
                </button>
                <small class="text-muted">Kategori yang sudah memiliki data tidak bisa dihapus.</small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ── TAB SWITCHER ──
    function switchTab(name, btn) {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + name).classList.add('active');
        btn.classList.add('active');
    }

    // ── AUTO SWITCH TAB dari URL param ──
    const urlTab = new URLSearchParams(window.location.search).get('tab');
    if (urlTab) {
        const btnEl = document.getElementById('btn-' + urlTab);
        if (btnEl) switchTab(urlTab, btnEl);
    }

    // ── FORMAT RUPIAH ──
    function formatRupiah(el) {
        let val = el.value.replace(/\D/g, '');
        el.value = val ? parseInt(val).toLocaleString('id-ID') : '';
    }

    // ── CSRF TOKEN ──
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    // ── TAMBAH KATEGORI (AJAX) ──
    async function tambahKategori() {
        const name  = document.getElementById('new-cat-name').value.trim();
        const icon  = document.getElementById('new-cat-icon').value;
        const color = document.getElementById('new-cat-color').value;
        const errEl = document.getElementById('cat-error');
        const btn   = document.getElementById('btn-tambah-cat');

        errEl.style.display = 'none';
        if (!name) { showCatError('Nama kategori wajib diisi.'); return; }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';

        try {
            const res  = await fetch('/pengeluaran/kategori/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({ name, icon, color })
            });
            const data = await res.json();

            if (!data.success) {
                showCatError(data.message);
            } else {
                // Tambahkan ke list
                const cat = data.category;
                const listEl = document.getElementById('cat-list');
                const emptyEl = document.getElementById('empty-cat');
                if (emptyEl) emptyEl.remove();

                listEl.insertAdjacentHTML('beforeend', `
                    <div class="cat-list-item" id="cat-item-${cat.id}">
                        <div class="cat-dot" style="background:${cat.color}"></div>
                        <i class="fas fa-${cat.icon}" style="color:${cat.color}; width:16px"></i>
                        <span class="fw-semibold flex-grow-1" style="font-size:14px">${cat.name}</span>
                        <button class="btn btn-sm btn-outline-danger" style="border-radius:8px; font-size:12px"
                            onclick="hapusKategori(${cat.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `);

                // Tambahkan ke dropdown form catat
                const sel = document.getElementById('select-category');
                if (sel) {
                    sel.insertAdjacentHTML('beforeend',
                        `<option value="${cat.id}">${cat.name}</option>`
                    );
                }

                document.getElementById('new-cat-name').value = '';
            }
        } catch (e) {
            showCatError('Terjadi kesalahan. Coba lagi.');
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Kategori';
    }

    function showCatError(msg) {
        const el = document.getElementById('cat-error');
        el.textContent = msg;
        el.style.display = 'block';
    }

    // ── HAPUS KATEGORI (AJAX) ──
    async function hapusKategori(id) {
        if (!confirm('Hapus kategori ini?')) return;

        try {
            const res  = await fetch(`/pengeluaran/kategori/delete/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF }
            });
            const data = await res.json();

            if (!data.success) {
                alert('❌ ' + data.message);
            } else {
                const el = document.getElementById('cat-item-' + id);
                if (el) el.remove();

                // Hapus dari dropdown
                const sel = document.getElementById('select-category');
                if (sel) {
                    const opt = sel.querySelector(`option[value="${id}"]`);
                    if (opt) opt.remove();
                }
            }
        } catch (e) {
            alert('Terjadi kesalahan.');
        }
    }

    // ── CHART TREN ──
    const trendData = @json($trendData);

    const ctx = document.getElementById('trendChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trendData.map(d => d.date),
                datasets: [{
                    label: 'Pengeluaran (Rp)',
                    data: trendData.map(d => d.amount),
                    backgroundColor: trendData.map(d =>
                        d.amount > 0 ? 'rgba(124, 58, 237, 0.7)' : 'rgba(226, 232, 240, 0.5)'
                    ),
                    borderColor: 'rgba(124, 58, 237, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (v) => 'Rp ' + (v/1000).toFixed(0) + 'k'
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }
</script>

</body>
</html>
