<!-- PRODUK -->
<div class="row g-4">

    @forelse ($products as $product)

        @php

            /*
            |--------------------------------------------------------------------------
            | STOCK STATUS
            |--------------------------------------------------------------------------
            */

            $stockStatus =
                \App\Helpers\StockStatusHelper::getStockStatus(
                    $product->stock,
                    $product->total_keluar ?? 0,
                    $product->created_at ?? null
                );

            /*
            |--------------------------------------------------------------------------
            | MARGIN
            |--------------------------------------------------------------------------
            */

            $margin =
                $product->costprice > 0
                    ? (($product->salesprice1 - $product->costprice) /
                            $product->costprice) *
                        100
                    : 0;

            $marginColor = 'danger';

            if ($margin >= 30) {

                $marginColor = 'success';

            } elseif ($margin >= 15) {

                $marginColor = 'primary';

            } elseif ($margin >= 5) {

                $marginColor = 'warning';
            }

        @endphp

        <div class="col-md-6 col-lg-4 col-xl-3">

            <div
                class="product-card shadow-lg border-0 h-100 overflow-hidden"
                style="
                    border-radius: 24px;
                    background: white;
                    transition: 0.25s ease;
                    position: relative;
                "
            >

                <!-- TOP BADGE -->
                <div
                    style="
                        position:absolute;
                        top:14px;
                        right:14px;
                        z-index:10;
                    "
                >

                    <span
                        class="badge bg-{{ $marginColor }} rounded-pill px-3 py-2 shadow-sm"
                    >

                        {{ number_format($margin,1) }}%

                    </span>

                </div>

                <!-- HEADER -->
                <div
                    class="product-card-header text-white"
                    style="
                        background:
                            linear-gradient(
                                135deg,
                                #2563eb,
                                #1d4ed8
                            );
                        padding: 18px;
                    "
                >

                    <div
                        class="d-flex justify-content-between align-items-start gap-2"
                    >

                        <div>

                            <small class="text-white-50 d-block mb-1">

                                {{ $product->productgroup_name }}

                            </small>

                            <h5
                                class="fw-bold mb-0"
                                style="
                                    line-height:1.4;
                                    min-height:48px;
                                "
                            >

                                {{ Str::limit($product->name, 40) }}

                            </h5>

                        </div>

                        <button
                            type="button"
                            class="btn btn-light btn-sm rounded-circle shadow-sm"
                            onclick="
                                copyProductName(
                                    event,
                                    '{{ $product->id }}',
                                    '{{ addslashes($product->name) }}'
                                )
                            "
                            title="Copy nama produk"
                            style="
                                width:40px;
                                height:40px;
                                flex-shrink:0;
                            "
                        >

                            <i class="fas fa-copy text-primary"></i>

                        </button>

                    </div>

                </div>

                <!-- BODY -->
                <div class="card-body p-4 d-flex flex-column">

                    <!-- PRICE -->
                    <div
                        class="mb-4"
                        onclick="event.stopPropagation()"
                        data-bs-toggle="modal"
                        data-bs-target="#productModal{{ $loop->index }}"
                        data-product-id="{{ $product->id }}"
                        style="cursor:pointer;"
                    >

                        <div
                            class="rounded-4 p-3 text-center"
                            style="
                                background:
                                    linear-gradient(
                                        135deg,
                                        rgba(16,185,129,0.1),
                                        rgba(5,150,105,0.08)
                                    );
                                border:
                                    2px solid rgba(16,185,129,0.15);
                            "
                        >

                            <small class="text-muted d-block mb-1">

                                Harga Jual

                            </small>

                            <h3
                                class="fw-bold text-success mb-0"
                            >

                                Rp {{ number_format($product->salesprice1,0,',','.') }}

                            </h3>

                        </div>

                    </div>

                    <!-- STOCK STATUS -->
                    <div
                        class="mb-4"
                        onclick="event.stopPropagation()"
                        data-bs-toggle="modal"
                        data-bs-target="#productModal{{ $loop->index }}"
                        style="cursor:pointer;"
                    >

                        <div
                            class="p-3 rounded-4 border"
                            style="
                                background:
                                    rgba(248,250,252,0.8);
                            "
                        >

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <small class="text-muted fw-semibold">

                                    Status Stock

                                </small>

                                <span
                                    class="
                                        stock-status-badge
                                        status-{{ strtolower(str_replace('_', '-', $stockStatus['status'])) }}
                                    "
                                >

                                    <i class="fas {{ $stockStatus['icon'] }}"></i>

                                    {{ $stockStatus['label'] }}

                                </span>

                            </div>

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <small class="text-muted d-block">

                                        Sisa Stock

                                    </small>

                                    <h4 class="fw-bold mb-0 text-dark">

                                        {{ number_format($product->stock,0,',','.') }}

                                    </h4>

                                </div>

                                <div class="text-end">

                                    <small class="text-muted d-block">

                                        Estimasi

                                    </small>

                                    <strong class="text-primary">

                                        {{ $stockStatus['estimasi'] }}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- STATS -->
                    <div class="row g-2 mb-4">

                        <!-- MASUK -->
                        <div class="col-6">

                            <div
                                class="p-3 rounded-4 text-center h-100"
                                style="
                                    background:
                                        rgba(16,185,129,0.08);
                                "
                            >

                                <small class="text-muted d-block mb-1">

                                    Barang Masuk

                                </small>

                                <h5 class="fw-bold text-success mb-0">

                                    {{ number_format($product->total_masuk,0,',','.') }}

                                </h5>

                            </div>

                        </div>

                        <!-- KELUAR -->
                        <div class="col-6">

                            <div
                                class="p-3 rounded-4 text-center h-100"
                                style="
                                    background:
                                        rgba(239,68,68,0.08);
                                "
                            >

                                <small class="text-muted d-block mb-1">

                                    Terjual

                                </small>

                                <h5 class="fw-bold text-danger mb-0">

                                    {{ number_format($product->total_keluar,0,',','.') }}

                                </h5>

                            </div>

                        </div>

                    </div>

                    <!-- QUANTITY -->
                    <div
                        class="quantity-selector mb-4"
                        onclick="event.stopPropagation();"
                    >

                        <div
                            class="d-flex align-items-center justify-content-between rounded-pill px-3 py-2"
                            style="
                                background:#f8fafc;
                                border:1px solid #e2e8f0;
                            "
                        >

                            <button
                                type="button"
                                class="btn btn-sm btn-light rounded-circle"
                                onclick="decreaseQty(this)"
                            >

                                <i class="fas fa-minus"></i>

                            </button>

                            <input
                                type="number"
                                class="qty-input border-0 text-center fw-bold"
                                value="1"
                                min="1"
                                max="{{ $product->stock }}"
                                onchange="validateQty(this, {{ $product->stock }})"
                                style="
                                    width:70px;
                                    background:transparent;
                                "
                            >

                            <button
                                type="button"
                                class="btn btn-sm btn-light rounded-circle"
                                onclick="increaseQty(this, {{ $product->stock }})"
                            >

                                <i class="fas fa-plus"></i>

                            </button>

                        </div>

                    </div>

                    <!-- BUTTONS -->
                    <div class="mt-auto">

                        <div class="d-grid gap-2">

                            <!-- PCS -->
                            <button
                                type="button"
                                class="btn btn-primary rounded-pill py-3 fw-semibold shadow-sm"
                                onclick="
                                    event.stopPropagation();

                                    addToCart(
                                        event,
                                        '{{ $product->id }}',
                                        '{{ addslashes($product->name) }}',
                                        {{ $product->salesprice1 }},
                                        '{{ addslashes($product->productgroup_name) }}',
                                        'pcs'
                                    )
                                "
                            >

                                <i class="fas fa-shopping-cart me-2"></i>

                                Beli Pcs

                            </button>

                            <!-- BOX -->
                            <button
                                type="button"
                                class="btn btn-outline-dark rounded-pill py-3 fw-semibold"
                                onclick="
                                    event.stopPropagation();

                                    addToCart(
                                        event,
                                        '{{ $product->id }}',
                                        '{{ addslashes($product->name) }}',
                                        {{ $product->salesprice1 }},
                                        '{{ addslashes($product->productgroup_name) }}',
                                        'box'
                                    )
                                "
                            >

                                <i class="fas fa-boxes me-2"></i>

                                Beli Box

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div
                class="text-center py-5 rounded-4 shadow-sm"
                style="
                    background:white;
                "
            >

                <i
                    class="fas fa-box-open mb-3"
                    style="
                        font-size:70px;
                        color:#cbd5e1;
                    "
                ></i>

                <h4 class="fw-bold text-secondary">

                    Produk Tidak Ditemukan

                </h4>

                <p class="text-muted mb-0">

                    Coba ubah keyword pencarian atau filter produk

                </p>

            </div>

        </div>

    @endforelse

</div>

<!-- PAGINATION -->
<div class="mt-5 d-flex justify-content-center">

    {{ $products->links('pagination::bootstrap-4') }}

</div>

<style>

.product-card:hover{

    transform:
        translateY(-6px);

    box-shadow:
        0 20px 40px rgba(0,0,0,0.08);

}

.stock-status-badge{

    display:inline-flex;
    align-items:center;
    gap:6px;

    padding:6px 12px;

    border-radius:999px;

    font-size:12px;
    font-weight:700;

}

.status-fast-moving{

    background:
        rgba(16,185,129,0.12);

    color:#059669;

}

.status-slow-moving{

    background:
        rgba(245,158,11,0.12);

    color:#d97706;

}

.status-dead-stock{

    background:
        rgba(239,68,68,0.12);

    color:#dc2626;

}

.qty-input:focus{

    outline:none;
    box-shadow:none;

}

</style>
