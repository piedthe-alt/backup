<!-- PRODUCT DETAIL MODAL (SINGLE DYNAMIC MODAL) -->
<div class="modal" id="productDetailModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white border-0">

                <div>
                    <h5 class="modal-title">
                        <i class="fas fa-box me-2"></i>
                        <span id="modalProductName">Detail Produk</span>
                    </h5>

                    <small class="text-white-50">
                        Informasi Produk
                    </small>
                </div>

                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body p-4">

                <!-- TABS -->
                <ul class="nav nav-tabs mb-4" role="tablist">

                    <li class="nav-item">

                        <button class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#detail-tab-content"
                            type="button">

                            <i class="fas fa-info-circle me-2"></i>
                            Detail Produk

                        </button>

                    </li>

                    <li class="nav-item">

                        <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#history-tab-content"
                            type="button">

                            <i class="fas fa-arrow-down me-2"></i>
                            Riwayat Stok Masuk

                        </button>

                    </li>

                </ul>

                <!-- TAB CONTENT -->
                <div class="tab-content">

                    <!-- DETAIL -->
                    <div class="tab-pane fade show active"
                        id="detail-tab-content">

                        <table class="table align-middle">

                            <tbody>

                                <tr>
                                    <th width="250">
                                        <i class="fas fa-barcode me-2 text-success"></i>
                                        Kode Barang
                                    </th>

                                    <td>
                                        <span class="badge bg-success"
                                            id="modalProductCode">
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <i class="fas fa-tag me-2 text-primary"></i>
                                        Group Produk
                                    </th>

                                    <td>
                                        <span class="badge bg-light text-dark"
                                            id="modalProductGroup">
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <i class="fas fa-truck me-2 text-primary"></i>
                                        Supplier
                                    </th>

                                    <td id="modalSupplier"></td>
                                </tr>

                                <tr>
                                    <th>
                                        <i class="fas fa-warehouse me-2 text-info"></i>
                                        Stock Saat Ini
                                    </th>

                                    <td>
                                        <strong class="text-info"
                                            id="modalStock">
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <i class="fas fa-arrow-down me-2 text-success"></i>
                                        Total Barang Masuk
                                    </th>

                                    <td>
                                        <strong class="text-success"
                                            id="modalMasuk">
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <i class="fas fa-arrow-up me-2 text-warning"></i>
                                        Total Barang Keluar
                                    </th>

                                    <td>
                                        <strong class="text-warning"
                                            id="modalKeluar">
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <i class="fas fa-coins me-2 text-danger"></i>
                                        Harga Modal
                                    </th>

                                    <td>
                                        <strong class="text-danger"
                                            id="modalCostPrice">
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                        Harga Jual
                                    </th>

                                    <td>
                                        <strong class="text-success"
                                            id="modalSalePrice">
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <i class="fas fa-percent me-2 text-primary"></i>
                                        Margin
                                    </th>

                                    <td>
                                        <strong class="text-primary"
                                            id="modalMargin">
                                        </strong>
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                        <!-- STRATA -->
                        <div id="pricingStrataWrapper" class="mt-4 d-none">

                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-layer-group me-2 text-success"></i>
                                Harga Bertingkat
                            </h6>

                            <div class="table-responsive">

                                <table class="table table-sm">

                                    <thead class="table-light">

                                        <tr>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Total</th>
                                        </tr>

                                    </thead>

                                    <tbody id="pricingStrataBody">

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                    <!-- HISTORY -->
                    <div class="tab-pane fade"
                        id="history-tab-content">

                        <div class="text-center py-4">

                            <div class="spinner-border spinner-border-sm text-primary"></div>

                            <p class="small text-muted mt-2">
                                Riwayat stok masuk akan ditampilkan di sini
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- PRODUCT BUTTON -->
@foreach ($products as $product)

    @php
        $margin =
            $product->costprice > 0
                ? (($product->salesprice1 - $product->costprice) / $product->costprice) * 100
                : 0;

        $pricingStrata = [];

        for ($i = 1; $i <= 7; $i++) {

            $qtyField = 'salesdiscqty' . $i;
            $priceField = 'salesdiscprice' . $i;

            if (!empty($product->$qtyField)) {

                $pricingStrata[] = [
                    'qty' => $product->$qtyField,
                    'price' => $product->$priceField
                ];
            }
        }
    @endphp

    <button
        type="button"
        class="btn btn-primary open-product-modal"

        data-name="{{ $product->name }}"
        data-code="{{ $product->id }}"
        data-group="{{ $product->productgroup_name }}"
        data-supplier="{{ $product->supplier_name }}"
        data-stock="{{ number_format($product->stock, 0, ',', '.') }}"
        data-masuk="{{ number_format($product->total_masuk, 0, ',', '.') }}"
        data-keluar="{{ number_format($product->total_keluar, 0, ',', '.') }}"
        data-cost="Rp {{ number_format($product->costprice, 0, ',', '.') }}"
        data-sale="Rp {{ number_format($product->salesprice1, 0, ',', '.') }}"
        data-margin="{{ number_format($margin, 2, ',', '.') }}%"
        data-strata='@json($pricingStrata)'>

        Detail Produk

    </button>

@endforeach

<style>
    .modal-dialog {
        transform: none !important;
    }

    .modal.fade .modal-dialog {
        transition: none !important;
    }

    .modal-content {
        border-radius: 18px;
    }

    .nav-tabs .nav-link.active {
        font-weight: 700;
        color: #0d6efd;
    }
</style>

<script>
window.addEventListener('load', function () {

    const modalElement = document.getElementById('productDetailModal');

    if (!modalElement) {
        console.error('Modal tidak ditemukan');
        return;
    }

    const productModal =
        bootstrap.Modal.getOrCreateInstance(modalElement);

    document.querySelectorAll('.open-product-modal').forEach(button => {

        button.addEventListener('click', function () {

            document.getElementById('modalProductName').innerText =
                this.dataset.name;

            document.getElementById('modalProductCode').innerText =
                this.dataset.code;

            document.getElementById('modalProductGroup').innerText =
                this.dataset.group;

            document.getElementById('modalSupplier').innerText =
                this.dataset.supplier;

            document.getElementById('modalStock').innerText =
                this.dataset.stock + ' pcs';

            document.getElementById('modalMasuk').innerText =
                this.dataset.masuk;

            document.getElementById('modalKeluar').innerText =
                this.dataset.keluar;

            document.getElementById('modalCostPrice').innerText =
                this.dataset.cost;

            document.getElementById('modalSalePrice').innerText =
                this.dataset.sale;

            document.getElementById('modalMargin').innerText =
                this.dataset.margin;

            // STRATA
            const strata = JSON.parse(this.dataset.strata || '[]');

            const tbody =
                document.getElementById('pricingStrataBody');

            tbody.innerHTML = '';

            if (strata.length > 0) {

                document.getElementById('pricingStrataWrapper')
                    .classList.remove('d-none');

                strata.forEach(item => {

                    const total = item.qty * item.price;

                    tbody.innerHTML += `
                        <tr>
                            <td class="text-center fw-bold">
                                ${Number(item.qty).toLocaleString('id-ID')}
                            </td>

                            <td class="text-center">
                                Rp ${Number(item.price).toLocaleString('id-ID')}
                            </td>

                            <td class="text-center text-success fw-bold">
                                Rp ${Number(total).toLocaleString('id-ID')}
                            </td>
                        </tr>
                    `;
                });

            } else {

                document.getElementById('pricingStrataWrapper')
                    .classList.add('d-none');
            }

            productModal.show();

        });

    });

});
</script>
