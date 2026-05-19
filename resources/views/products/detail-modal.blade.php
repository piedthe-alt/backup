<!-- MODAL -->
@foreach ($products as $product)

    @php

        /*
        |--------------------------------------------------------------------------
        | STOCK STATUS
        |--------------------------------------------------------------------------
        */

        $stockColor = 'success';
        $stockLabel = 'STOCK AMAN';

        if ($product->stock <= 0) {

            $stockColor = 'danger';
            $stockLabel = 'STOCK HABIS';

        } elseif ($product->stock <= 5) {

            $stockColor = 'warning';
            $stockLabel = 'STOCK MENIPIS';
        }

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

    <div
        class="modal fade"
        id="productModal{{ $loop->index }}"
        tabindex="-1"
        data-product-id="{{ $product->id }}"
    >

        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div
                    class="modal-header border-0 text-white"
                    style="
                        background: linear-gradient(135deg,#2563eb,#1d4ed8);
                        padding: 1.3rem 1.5rem;
                    "
                >

                    <div>

                        <h4 class="modal-title fw-bold mb-1">

                            <i class="fas fa-box-open me-2"></i>
                            {{ $product->name }}

                        </h4>

                        <small class="text-white-50">

                            Detail Produk & Analitik Penjualan

                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <!-- BODY -->
                <div class="modal-body p-4">

                    <!-- SUMMARY -->
                    <div class="row g-3 mb-4">

                        <!-- STOCK -->
                        <div class="col-md-4">

                            <div
                                class="p-4 rounded-4 border"
                                style="
                                    background:
                                        rgba(
                                            {{ $stockColor == 'success' ? '16,185,129' : ($stockColor == 'warning' ? '245,158,11' : '239,68,68') }},
                                            0.08
                                        );
                                "
                            >

                                <small class="text-muted d-block mb-2">

                                    Status Stock

                                </small>

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <h5 class="fw-bold text-{{ $stockColor }} mb-1">

                                            {{ $stockLabel }}

                                        </h5>

                                        <small class="text-muted">

                                            {{ number_format($product->stock,0,',','.') }} pcs tersedia

                                        </small>

                                    </div>

                                    <i class="fas fa-boxes fs-1 text-{{ $stockColor }}"></i>

                                </div>

                            </div>

                        </div>

                        <!-- HARGA -->
                        <div class="col-md-4">

                            <div
                                class="p-4 rounded-4 border"
                                style="
                                    background: rgba(37,99,235,0.08);
                                "
                            >

                                <small class="text-muted d-block mb-2">

                                    Harga Jual

                                </small>

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <h4 class="fw-bold text-primary mb-1">

                                            Rp {{ number_format($product->salesprice1,0,',','.') }}

                                        </h4>

                                        <small class="text-muted">

                                            Modal:
                                            Rp {{ number_format($product->costprice,0,',','.') }}

                                        </small>

                                    </div>

                                    <i class="fas fa-money-bill-wave fs-1 text-primary"></i>

                                </div>

                            </div>

                        </div>

                        <!-- MARGIN -->
                        <div class="col-md-4">

                            <div
                                class="p-4 rounded-4 border"
                                style="
                                    background:
                                        rgba(
                                            {{ $marginColor == 'success' ? '16,185,129' : ($marginColor == 'warning' ? '245,158,11' : ($marginColor == 'primary' ? '37,99,235' : '239,68,68')) }},
                                            0.08
                                        );
                                "
                            >

                                <small class="text-muted d-block mb-2">

                                    Margin Profit

                                </small>

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <h4 class="fw-bold text-{{ $marginColor }} mb-1">

                                            {{ number_format($margin,2,',','.') }}%

                                        </h4>

                                        <small class="text-muted">

                                            Profit per item

                                        </small>

                                    </div>

                                    <i class="fas fa-chart-line fs-1 text-{{ $marginColor }}"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- TABS -->
                    <ul
                        class="nav nav-pills mb-4 gap-2"
                        role="tablist"
                    >

                        <li class="nav-item">

                            <a
                                class="nav-link active rounded-pill px-4 py-2"
                                data-bs-toggle="tab"
                                href="#detail-content-{{ $loop->index }}"
                            >

                                <i class="fas fa-info-circle me-2"></i>
                                Detail

                            </a>

                        </li>

                        <li class="nav-item">

                            <a
                                class="nav-link rounded-pill px-4 py-2"
                                data-bs-toggle="tab"
                                href="#history-in-content-{{ $loop->index }}"
                                data-product-id="{{ $product->id }}"
                            >

                                <i class="fas fa-arrow-down me-2"></i>
                                Barang Masuk

                            </a>

                        </li>

                        <li class="nav-item">

                            <a
                                class="nav-link rounded-pill px-4 py-2"
                                data-bs-toggle="tab"
                                href="#sales-content-{{ $loop->index }}"
                                data-product-id="{{ $product->id }}"
                            >

                                <i class="fas fa-chart-bar me-2"></i>
                                Penjualan

                            </a>

                        </li>

                    </ul>

                    <!-- TAB CONTENT -->
                    <div class="tab-content">

                        <!-- DETAIL TAB -->
                        <div
                            class="tab-pane fade show active"
                            id="detail-content-{{ $loop->index }}"
                        >

                            <div class="table-responsive">

                                <table class="table align-middle">

                                    <tbody>

                                        <tr>

                                            <th width="260" class="text-secondary">

                                                <i class="fas fa-barcode me-2 text-success"></i>
                                                Kode Barang

                                            </th>

                                            <td>

                                                <span class="badge bg-success rounded-pill px-3 py-2">

                                                    {{ $product->id }}

                                                </span>

                                            </td>

                                        </tr>

                                        <tr>

                                            <th class="text-secondary">

                                                <i class="fas fa-layer-group me-2 text-primary"></i>
                                                Group Produk

                                            </th>

                                            <td>

                                                <span class="badge bg-primary rounded-pill px-3 py-2">

                                                    {{ $product->productgroup_name }}

                                                </span>

                                            </td>

                                        </tr>

                                        <tr>

                                            <th class="text-secondary">

                                                <i class="fas fa-truck me-2 text-warning"></i>
                                                Supplier

                                            </th>

                                            <td class="fw-semibold">

                                                {{ $product->supplier_name }}

                                            </td>

                                        </tr>

                                        <tr>

                                            <th class="text-secondary">

                                                <i class="fas fa-warehouse me-2 text-info"></i>
                                                Stock Saat Ini

                                            </th>

                                            <td>

                                                <span class="badge bg-info rounded-pill px-3 py-2">

                                                    {{ number_format($product->stock,0,',','.') }} pcs

                                                </span>

                                            </td>

                                        </tr>

                                        <tr>

                                            <th class="text-secondary">

                                                <i class="fas fa-arrow-down me-2 text-success"></i>
                                                Total Barang Masuk

                                            </th>

                                            <td>

                                                <span class="badge bg-success rounded-pill px-3 py-2">

                                                    {{ number_format($product->total_masuk,0,',','.') }}

                                                </span>

                                            </td>

                                        </tr>

                                        <tr>

                                            <th class="text-secondary">

                                                <i class="fas fa-arrow-up me-2 text-danger"></i>
                                                Total Barang Keluar

                                            </th>

                                            <td>

                                                <span class="badge bg-danger rounded-pill px-3 py-2">

                                                    {{ number_format($product->total_keluar,0,',','.') }}

                                                </span>

                                            </td>

                                        </tr>

                                        <tr>

                                            <th class="text-secondary">

                                                <i class="fas fa-coins me-2 text-danger"></i>
                                                Harga Modal

                                            </th>

                                            <td>

                                                <strong class="fs-5 text-danger">

                                                    Rp {{ number_format($product->costprice,0,',','.') }}

                                                </strong>

                                            </td>

                                        </tr>

                                        <tr>

                                            <th class="text-secondary">

                                                <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                                Harga Jual

                                            </th>

                                            <td>

                                                <strong class="fs-5 text-success">

                                                    Rp {{ number_format($product->salesprice1,0,',','.') }}

                                                </strong>

                                            </td>

                                        </tr>

                                        <tr>

                                            <th class="text-secondary">

                                                <i class="fas fa-percent me-2 text-primary"></i>
                                                Margin

                                            </th>

                                            <td>

                                                <span class="badge bg-{{ $marginColor }} rounded-pill px-3 py-2 fs-6">

                                                    {{ number_format($margin,2,',','.') }}%

                                                </span>

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                        <!-- HISTORY TAB -->
                        <div
                            class="tab-pane fade"
                            id="history-in-content-{{ $loop->index }}"
                        >

                            <div
                                id="inventory-history-in-loading-{{ $loop->index }}"
                                class="text-center py-5"
                            >

                                <div class="spinner-border text-primary"></div>

                                <p class="text-muted mt-3">

                                    Memuat riwayat barang masuk...

                                </p>

                            </div>

                            <div
                                id="inventory-history-in-content-{{ $loop->index }}"
                                style="display:none;"
                            >

                                <div class="inventory-transactions-in-{{ $loop->index }}"></div>

                            </div>

                        </div>

                        <!-- SALES TAB -->
                        <div
                            class="tab-pane fade"
                            id="sales-content-{{ $loop->index }}"
                        >

                            <div
                                class="p-3 rounded-4 mb-4"
                                style="
                                    background:#f8fafc;
                                    border-left:4px solid #2563eb;
                                "
                            >

                                <div class="row align-items-center">

                                    <div class="col-md-4">

                                        <label class="small fw-bold mb-2">

                                            Filter Penjualan

                                        </label>

                                        <select
                                            class="form-select"
                                            onchange="
                                                loadSalesData(
                                                    {{ $product->id }},
                                                    {{ $loop->index }},
                                                    this.value
                                                )
                                            "
                                        >

                                            <option value="7">7 Hari</option>
                                            <option value="14">14 Hari</option>
                                            <option value="30" selected>30 Hari</option>
                                            <option value="90">90 Hari</option>

                                        </select>

                                    </div>

                                </div>

                            </div>

                            <div
                                id="sales-loading-{{ $loop->index }}"
                                class="text-center py-5"
                            >

                                <div class="spinner-border text-primary"></div>

                                <p class="text-muted mt-3">

                                    Memuat data penjualan...

                                </p>

                            </div>

                            <div
                                id="sales-content-data-{{ $loop->index }}"
                                style="display:none;"
                            >

                                <canvas
                                    id="sales-chart-{{ $loop->index }}"
                                    height="120"
                                ></canvas>

                                <div class="mt-4">

                                    <table class="table table-hover">

                                        <thead class="table-light">

                                            <tr>

                                                <th>Tanggal</th>
                                                <th>Qty</th>
                                                <th>Jumlah</th>
                                                <th>Margin</th>

                                            </tr>

                                        </thead>

                                        <tbody id="sales-daily-table-{{ $loop->index }}">

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endforeach
