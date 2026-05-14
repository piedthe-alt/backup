{{-- ============================================
    MODERN LAYOUT TEMPLATES
    Reusable layouts untuk semua halaman
    ============================================ --}}

{{--
    Template 1: Main Dashboard Layout
    Digunakan untuk: product page, dashboard, inventory
--}}

@php
    $pageTitle = $pageTitle ?? 'Dashboard';
    $pageIcon = $pageIcon ?? 'fa-box';
    $breadcrumbs = $breadcrumbs ?? [];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} - POS Inventory</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Include Modern Design System --}}
    <link href="{{ asset('css/modern-design-system.css') }}" rel="stylesheet">

    <style>
        /* ---- CUSTOM LAYOUT STYLES ---- */

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            color: white;
            padding: var(--spacing-xl) var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(100px, -100px);
        }

        .page-header-content {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
        }

        .page-title {
            font-size: var(--font-size-3xl);
            font-weight: var(--font-weight-bold);
            margin: 0;
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .page-title i {
            font-size: var(--font-size-3xl);
        }

        .page-subtitle {
            font-size: var(--font-size-sm);
            opacity: 0.9;
            margin-top: var(--spacing-xs);
        }

        .page-content {
            flex: 1;
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
            padding: 0 var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
        }

        @media (max-width: 639px) {
            .page-header {
                padding: var(--spacing-lg) var(--spacing-md);
                margin-bottom: var(--spacing-lg);
            }

            .page-title {
                font-size: var(--font-size-2xl);
            }

            .page-content {
                padding: 0 var(--spacing-md);
                margin-bottom: var(--spacing-lg);
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="page-wrapper">
        {{-- PAGE HEADER --}}
        <div class="page-header">
            <div class="page-header-content">
                <h1 class="page-title">
                    <i class="fas {{ $pageIcon }}"></i>
                    {{ $pageTitle }}
                </h1>
                @if(isset($pageSubtitle))
                    <p class="page-subtitle">{{ $pageSubtitle }}</p>
                @endif
            </div>
        </div>

        {{-- PAGE CONTENT --}}
        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>


{{-- ============================================
    COMPONENT: Card Grid Layout
    Responsive grid untuk product cards
    ============================================ --}}

<div class="grid grid-cols-1" style="--grid-cols-sm: 2; --grid-cols-md: 3; --grid-cols-lg: 4;">
    @forelse ($items as $item)
        <div class="card card-hover">
            {{-- Card Image/Thumbnail --}}
            <div style="
                background: linear-gradient(135deg, var(--slate-100) 0%, var(--slate-200) 100%);
                height: 200px;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            ">
                @if(isset($item->image))
                    <img src="{{ $item->image }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="fas fa-image" style="font-size: 3rem; color: var(--slate-400);"></i>
                @endif

                {{-- Badge --}}
                @if(isset($item->status))
                    <div style="position: absolute; top: var(--spacing-md); right: var(--spacing-md);">
                        <span class="badge badge-primary">{{ $item->status }}</span>
                    </div>
                @endif
            </div>

            {{-- Card Body --}}
            <div class="card-body">
                <h5 class="mb-2">{{ Str::limit($item->name, 35) }}</h5>

                @if(isset($item->description))
                    <p class="text-muted text-sm mb-3" style="line-clamp: 2;">
                        {{ Str::limit($item->description, 60) }}
                    </p>
                @endif

                {{-- Price / Info Row --}}
                <div class="flex flex-between mb-3">
                    @if(isset($item->price))
                        <div>
                            <small class="text-muted">Harga</small>
                            <div class="font-bold text-primary">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endif

                    @if(isset($item->stock))
                        <div class="text-right">
                            <small class="text-muted">Stock</small>
                            <div class="font-bold">{{ $item->stock }}</div>
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-sm">
                    <button class="btn btn-primary btn-sm flex-1">
                        <i class="fas fa-eye"></i> Lihat
                    </button>
                    <button class="btn btn-secondary btn-sm">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div style="
                text-align: center;
                padding: var(--spacing-3xl) var(--spacing-lg);
                background: var(--slate-50);
                border-radius: var(--radius-lg);
                border: 1px dashed var(--slate-300);
            ">
                <i class="fas fa-inbox" style="font-size: 3rem; color: var(--slate-400); margin-bottom: var(--spacing-md); display: block;"></i>
                <h5 class="text-muted">Tidak ada data</h5>
                <p class="text-muted text-sm">Silakan tambahkan data atau ubah filter</p>
            </div>
        </div>
    @endforelse
</div>


{{-- ============================================
    COMPONENT: Modern Data Table
    Responsive table untuk list view
    ============================================ --}}

<div style="overflow-x: auto; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">
                    <input type="checkbox" class="form-check-input">
                </th>
                <th>Nama Produk</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: right;">Stock</th>
                <th>Status</th>
                <th style="width: 120px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td style="text-align: center;">
                        <input type="checkbox" class="form-check-input">
                    </td>
                    <td class="font-medium">{{ $item->name }}</td>
                    <td style="text-align: right;">
                        <span class="text-primary font-semibold">
                            Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <span class="font-semibold">{{ $item->stock ?? 0 }}</span>
                    </td>
                    <td>
                        @if($item->stock > 20)
                            <span class="badge badge-success">Aman</span>
                        @elseif($item->stock > 5)
                            <span class="badge badge-warning">Menipis</span>
                        @else
                            <span class="badge badge-danger">Kritis</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div class="flex gap-xs" style="justify-content: center;">
                            <button class="btn btn-icon btn-sm" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-icon btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-icon btn-sm" title="Hapus">
                                <i class="fas fa-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-lg">
                        <i class="fas fa-inbox" style="font-size: 2rem; color: var(--slate-300); margin-bottom: var(--spacing-md); display: block;"></i>
                        <span class="text-muted">Tidak ada data</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


{{-- ============================================
    COMPONENT: Modern Search & Filter Bar
    ============================================ --}}

<div class="card card-filter" style="margin-bottom: var(--spacing-lg);">
    <div class="card-body">
        <form method="GET" class="row g-md-3">
            {{-- Search Input --}}
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Cari nama produk..."
                        value="{{ request('keyword') }}"
                    >
                </div>
            </div>

            {{-- Filter Dropdown --}}
            <div class="col-12 col-sm-6 col-md-3">
                <select name="category" class="form-select">
                    <option value="">📂 Semua Kategori</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Submit Button --}}
            <div class="col-12 col-sm-6 col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============================================
    COMPONENT: Action Header
    ============================================ --}}

<div class="flex flex-between mb-lg" style="flex-wrap: wrap; gap: var(--spacing-md);">
    {{-- Left Side: Title --}}
    <div>
        <h3 class="mb-0">{{ $title ?? 'Title' }}</h3>
        @if(isset($subtitle))
            <small class="text-muted">{{ $subtitle }}</small>
        @endif
    </div>

    {{-- Right Side: Actions --}}
    <div class="flex gap-sm" style="flex-wrap: wrap;">
        <button class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah
        </button>
        <button class="btn btn-secondary">
            <i class="fas fa-download"></i> Export
        </button>
        <button class="btn btn-outline-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
</div>


{{-- ============================================
    COMPONENT: Stat Card
    ============================================ --}}

<div class="grid grid-cols-1" style="--sm: 2; --md: 4;">
    {{-- Stat Item --}}
    <div class="card">
        <div class="card-body">
            <div class="flex flex-between mb-md">
                <div>
                    <small class="text-muted text-uppercase font-semibold">Total Produk</small>
                    <h3 class="mb-0 mt-sm">1,234</h3>
                </div>
                <div style="
                    width: 50px;
                    height: 50px;
                    border-radius: var(--radius-lg);
                    background: var(--primary-100);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <i class="fas fa-box" style="color: var(--primary-600); font-size: 1.5rem;"></i>
                </div>
            </div>
            <small class="text-success">
                <i class="fas fa-arrow-up"></i> +12% dari bulan lalu
            </small>
        </div>
    </div>

    {{-- Repeat for other stats --}}
</div>


{{-- ============================================
    COMPONENT: Modal Modern
    ============================================ --}}

<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Judul Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Content --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ============================================
    RESPONSIVE BREAKPOINT HELPER
    ============================================ --}}

@media (max-width: 639px) {
    {{-- Mobile adjustments --}}
    .grid {
        --grid-cols: 1;
    }
}

@media (min-width: 640px) and (max-width: 1023px) {
    {{-- Tablet adjustments --}}
    .grid {
        --grid-cols: 2;
    }
}

@media (min-width: 1024px) {
    {{-- Desktop adjustments --}}
    .grid {
        --grid-cols: 4;
    }
}
