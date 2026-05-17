<!-- PRODUCT DETAIL MODALS -->
@foreach ($products as $product)
    <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" data-product-id="{{ $product->id }}">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- HEADER -->
                <div class="modal-header">

                    <div>
                        <h5 class="modal-title">

                            <i class="fas fa-box me-2"></i>{{ $product->name }}

                        </h5>
                        <small class="text-white-50">Detail Produk</small>
                    </div>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">

                    </button>

                </div>

                <!-- BODY -->
                <div class="modal-body p-4">

                    <!-- TABS -->
                    <ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid #e5e7eb;">
                        <li class="nav-item">
                            <a class="nav-link active" id="detail-tab-{{ $product->id }}" data-bs-toggle="tab" href="#detail-content-{{ $product->id }}" role="tab">
                                <i class="fas fa-info-circle me-2"></i>Detail Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="history-in-tab-{{ $product->id }}" data-bs-toggle="tab" href="#history-in-content-{{ $product->id }}" role="tab" data-product-id="{{ $product->id }}">
                                <i class="fas fa-arrow-down me-2"></i>Riwayat Stok Masuk
                            </a>
                        </li>
                    </ul>

                    <!-- TAB CONTENT -->
                    <div class="tab-content">
                        <!-- DETAIL TAB -->
                        <div class="tab-pane fade show active" id="detail-content-{{ $product->id }}" role="tabpanel">

                    <table class="table">

                        <tbody>

                            <tr>

                                <th width="250">
                                    <i class="fas fa-barcode me-2 text-success"></i>Kode Barang
                                </th>

                                <td>

                                    <span class="badge bg-success text-white">{{ $product->id }}</span>

                                </td>

                            </tr>

                            <tr>

                                <th width="250">
                                    <i class="fas fa-tag me-2 text-primary"></i>Group Produk
                                </th>

                                <td>

                                    <span
                                        class="badge bg-light text-dark">{{ $product->productgroup_name }}</span>

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    <i class="fas fa-truck me-2 text-primary"></i>Supplier
                                </th>

                                <td>

                                    {{ $product->supplier_name }}

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    <i class="fas fa-warehouse me-2 text-info"></i>Stock Saat Ini
                                </th>

                                <td>

                                    <strong class="text-info">{{ number_format($product->stock, 0, ',', '.') }}
                                        pcs</strong>

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    <i class="fas fa-arrow-down me-2 text-success"></i>Total Barang Masuk
                                </th>

                                <td>

                                    <strong
                                        class="text-success">{{ number_format($product->total_masuk, 0, ',', '.') }}</strong>

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    <i class="fas fa-arrow-up me-2 text-warning"></i>Total Barang Keluar
                                </th>

                                <td>

                                    <strong
                                        class="text-warning">{{ number_format($product->total_keluar, 0, ',', '.') }}</strong>

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    <i class="fas fa-coins me-2 text-danger"></i>Harga Modal
                                </th>

                                <td>

                                    <strong class="text-danger">Rp
                                        {{ number_format($product->costprice, 0, ',', '.') }}</strong>

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    <i class="fas fa-money-bill-wave me-2 text-success"></i>Harga Jual
                                </th>

                                <td>

                                    <strong class="text-success">Rp
                                        {{ number_format($product->salesprice1, 0, ',', '.') }}</strong>

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    <i class="fas fa-percent me-2 text-primary"></i>Margin
                                </th>

                                <td>

                                    @php
                                        $margin =
                                            $product->costprice > 0
                                                ? (($product->salesprice1 - $product->costprice) /
                                                        $product->costprice) *
                                                    100
                                                : 0;
                                    @endphp

                                    <strong
                                        class="text-primary">{{ number_format($margin, 2, ',', '.') }}%</strong>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                    <!-- PRICING STRATA TABLE -->
                    @php
                        // Check if any salesdiscqty is not 0
                        $hasPricingStrata = false;
                        for ($i = 1; $i <= 7; $i++) {
                            $field = 'salesdiscqty' . $i;
                            if (isset($product->$field) && $product->$field != 0) {
                                $hasPricingStrata = true;
                                break;
                            }
                        }
                    @endphp

                    @if ($hasPricingStrata)
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                            <h6 style="margin-bottom: 15px; font-weight: 700; color: #1e293b;">
                                <i class="fas fa-layer-group me-2 text-success"></i>Harga Bertingkat (Strata)
                            </h6>
                            <div style="overflow-x: auto;">
                                <table class="table table-sm" style="font-size: 0.9rem; margin-bottom: 0;">
                                    <thead style="background-color: #f8fafc;">
                                        <tr>
                                            <th style="text-align: center; color: #1e293b; font-weight: 600;">
                                                <i class="fas fa-cube me-1"></i>Jumlah Beli
                                            </th>
                                            <th style="text-align: center; color: #1e293b; font-weight: 600;">
                                                <i class="fas fa-tag me-1"></i>Harga Satuan
                                            </th>
                                            <th style="text-align: center; color: #1e293b; font-weight: 600;">
                                                <i class="fas fa-calculator me-1"></i>Total Harga
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 1; $i <= 7; $i++)
                                            @php
                                                $qtyField = 'salesdiscqty' . $i;
                                                $priceField = 'salesdiscprice' . $i;
                                                $qty = (isset($product->$qtyField) && $product->$qtyField != 0) ? $product->$qtyField : null;
                                                $price = isset($product->$priceField) ? $product->$priceField : 0;
                                            @endphp
                                            @if ($qty !== null && $qty != 0)
                                                <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $i % 2 == 0 ? '#f8fafc' : 'white' }};">
                                                    <td style="text-align: center; font-weight: 600; color: #2563eb;">
                                                        {{ number_format($qty, 0, ',', '.') }}
                                                    </td>
                                                    <td style="text-align: center; color: #64748b;">
                                                        Rp {{ number_format($price, 0, ',', '.') }}
                                                    </td>
                                                    <td style="text-align: center; font-weight: 700; color: #10b981;">
                                                        Rp {{ number_format($qty * $price, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            <div style="margin-top: 12px; padding: 12px; background-color: rgba(16, 185, 129, 0.08); border-left: 4px solid #10b981; border-radius: 6px;">
                                <small style="color: #64748b;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Tabel ini menunjukkan diskon bertingkat. Semakin banyak jumlah pembelian, harga satuan semakin murah.
                                </small>
                            </div>
                        </div>
                    @endif

                        </div>

                        <!-- HISTORY MASUK TAB -->
                            <div class="tab-pane fade" id="history-in-content-{{ $product->id }}" role="tabpanel">
                                <div id="inventory-history-in-loading-{{ $product->id }}" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted mt-2 small">Memuat data barang masuk...</p>
                            </div>
                                <div id="inventory-history-in-content-{{ $product->id }}" style="display: none;">
                                    <!-- Summary Section -->
                                    <div class="row mb-4 g-2">
                                        <div class="col-12">
                                            <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center">
                                                <small class="text-muted">Total Barang Masuk</small>
                                                <p class="mb-0 h6 text-success fw-bold" id="total-masuk-{{ $product->id }}">0</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transactions List -->
                                <div class="inventory-transactions-in-{{ $product->id }}">
                                    <p class="text-muted small">Tidak ada data barang masuk</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
@endforeach
