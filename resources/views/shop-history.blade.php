@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="mb-4">
        <i class="fas fa-history me-2"></i>Riwayat Belanja
    </h3>

    <form method="GET" class="row g-3 mb-4">

        <div class="col-md-3">
            <input
                type="text"
                name="phone"
                class="form-control"
                placeholder="Nomor Telepon"
                value="{{ request('phone') }}"
            >
        </div>

        <div class="col-md-2">
            <input
                type="date"
                name="start_date"
                class="form-control"
                value="{{ request('start_date') }}"
            >
        </div>

        <div class="col-md-2">
            <input
                type="date"
                name="end_date"
                class="form-control"
                value="{{ request('end_date') }}"
            >
        </div>

        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="latest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="highest">Total Tertinggi</option>
                <option value="lowest">Total Terendah</option>
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i>Cari
            </button>
        </div>

    </form>

    @forelse ($orders as $order)

        <div class="card mb-3 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>
                        <h5>#{{ $order->id }}</h5>

                        <div>
                            {{ $order->customer_name }}
                        </div>

                        <small class="text-muted">
                            {{ $order->customer_phone }}
                        </small>
                    </div>

                    <div class="text-end">

                        <div class="fw-bold text-primary">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </div>

                        <small>
                            {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}
                        </small>

                    </div>

                </div>

                <hr>

                <div class="d-flex gap-2">

                    <a
                        href="/shop/order/{{ $order->id }}/pdf"
                        target="_blank"
                        class="btn btn-sm btn-success"
                    >
                        <i class="fas fa-file-pdf me-1"></i>Invoice
                    </a>

                </div>

            </div>

        </div>

    @empty

        <div class="alert alert-secondary">
            Belum ada riwayat pesanan
        </div>

    @endforelse

    {{ $orders->links() }}

</div>

@endsection
