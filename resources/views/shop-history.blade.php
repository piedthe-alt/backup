<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Belanja</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            min-height: 100vh;
        }

        .navbar-history {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }

        .filter-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .history-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .history-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .amount {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2563eb;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .btn-invoice {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .btn-invoice:hover {
            opacity: 0.9;
            color: white;
            transform: translateY(-2px);
        }

        .empty-box {
            background: white;
            border-radius: 16px;
            padding: 60px 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .pagination {
            justify-content: center;
        }

        @media (max-width: 768px) {

            .amount {
                font-size: 1.1rem;
            }

            .history-header {
                flex-direction: column;
                align-items: start !important;
            }

            .history-right {
                width: 100%;
                margin-top: 15px;
                text-align: left !important;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar-history sticky-top p-3">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <div class="text-white fw-bold" style="font-size: 1.2rem;">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Belanja
                </div>

                <div class="d-flex gap-2">

                    <a href="/shop" class="btn btn-light btn-sm">
                        <i class="fas fa-store me-1"></i>
                        Toko
                    </a>

                    <a href="/" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>

                </div>

            </div>

        </div>

    </nav>

    <div class="container py-4">

        <!-- FILTER -->
        <div class="card filter-card mb-4">

            <div class="card-body">

                <form method="GET" class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Nomor Telepon
                        </label>

                        <input type="text"
                            name="phone"
                            class="form-control"
                            placeholder="08xxxxxxxxxx"
                            value="{{ request('phone') }}">

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            Dari Tanggal
                        </label>

                        <input type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ request('start_date') }}">

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            Sampai
                        </label>

                        <input type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ request('end_date') }}">

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            Urutkan
                        </label>

                        <select name="sort" class="form-select">

                            <option value="latest"
                                {{ request('sort') == 'latest' ? 'selected' : '' }}>
                                Terbaru
                            </option>

                            <option value="oldest"
                                {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                Terlama
                            </option>

                            <option value="highest"
                                {{ request('sort') == 'highest' ? 'selected' : '' }}>
                                Total Tertinggi
                            </option>

                            <option value="lowest"
                                {{ request('sort') == 'lowest' ? 'selected' : '' }}>
                                Total Terendah
                            </option>

                        </select>

                    </div>

                    <div class="col-md-3 d-flex align-items-end">

                        <button class="btn btn-primary w-100">

                            <i class="fas fa-search me-1"></i>
                            Cari Riwayat

                        </button>

                    </div>

                </form>

            </div>

        </div>

        <!-- LIST HISTORY -->
        @forelse ($orders as $order)

            <div class="card history-card mb-3">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start history-header">

                        <!-- LEFT -->
                        <div>

                            <h5 class="fw-bold mb-1">
                                #{{ $order->id }}
                            </h5>

                            <div class="mb-1">
                                {{ $order->customer_name }}
                            </div>

                            @if ($order->customer_phone)
                                <small class="text-muted d-block">
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $order->customer_phone }}
                                </small>
                            @endif

                            @if ($order->customer_address)
                                <small class="text-muted d-block">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $order->customer_address }}
                                </small>
                            @endif

                            <small class="text-muted d-block mt-2">

                                <i class="fas fa-calendar me-1"></i>

                                {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}

                            </small>

                        </div>

                        <!-- RIGHT -->
                        <div class="text-end history-right">

                            <div class="amount mb-2">

                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}

                            </div>

                            <div class="mb-3">

                                @php
                                    $statusColor = match (strtolower($order->status)) {
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp

                                <span class="badge bg-{{ $statusColor }} status-badge">

                                    {{ strtoupper($order->status) }}

                                </span>

                            </div>

                            <a href="/shop/order/{{ $order->id }}/pdf"
                                target="_blank"
                                class="btn-invoice">

                                <i class="fas fa-file-pdf me-1"></i>
                                Invoice

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="empty-box">

                <i class="fas fa-box-open fa-4x mb-3 text-secondary opacity-50"></i>

                <h4 class="fw-bold mb-2">
                    Belum Ada Riwayat
                </h4>

                <p class="text-muted">
                    Riwayat pesanan akan muncul di sini
                </p>

            </div>

        @endforelse

        <!-- PAGINATION -->
        <div class="mt-4">

            {{ $orders->links() }}

        </div>

    </div>

    <script>

        // Auto isi nomor telepon terakhir dari localStorage
        const lastPhone = localStorage.getItem('shopCustomerPhone');

        const phoneInput = document.querySelector('input[name="phone"]');

        if (lastPhone && phoneInput && !phoneInput.value) {

            phoneInput.value = lastPhone;

        }

    </script>

</body>

</html>
