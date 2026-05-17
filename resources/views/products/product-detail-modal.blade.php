{{-- ========================= --}}
{{-- PRODUCT DETAIL MODAL --}}
{{-- ========================= --}}

<!-- SINGLE PRODUCT MODAL -->
<div class="modal" id="productDetailModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white border-0">

                <div>
                    <h5 class="modal-title">
                        <i class="fas fa-box me-2"></i>
                        <span id="modalProductName"></span>
                    </h5>

                    <small class="text-white-50">
                        Detail Produk
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
                <ul class="nav nav-tabs mb-4">

                    <li class="nav-item">

                        <button class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#detail-tab"
                            type="button">

                            <i class="fas fa-info-circle me-2"></i>
                            Detail Produk

                        </button>

                    </li>

                    <li class="nav-item">

                        <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#history-tab"
                            type="button">

                            <i class="fas fa-arrow-down me-2"></i>
                            Riwayat Barang Masuk

                        </button>

                    </li>

                </ul>

                <!-- TAB CONTENT -->
                <div class="tab-content">

                    <!-- DETAIL TAB -->
                    <div class="tab-pane fade show active"
                        id="detail-tab">

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

                    </div>

                    <!-- HISTORY TAB -->
                    <div class="tab-pane fade"
                        id="history-tab">

                        <div class="text-center py-4">

                            <div class="spinner-border spinner-border-sm text-primary"></div>

                            <p class="small text-muted mt-2">
                                Riwayat barang masuk
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================= --}}
{{-- BUTTON DETAIL PRODUK --}}
{{-- ========================= --}}

@foreach ($products as $product)

    @php
        $margin =
            $product->costprice > 0
                ? (($product->salesprice1 - $product->costprice) / $product->costprice) * 100
                : 0;
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
        data-margin="{{ number_format($margin, 2, ',', '.') }}%">

        Detail Produk

    </button>

@endforeach

{{-- ========================= --}}
{{-- CSS --}}
{{-- ========================= --}}

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

</style>

{{-- ========================= --}}
{{-- BOOTSTRAP JS --}}
{{-- ========================= --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- ========================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================= --}}

<script>

window.addEventListener('load', function () {

    const modalElement =
        document.getElementById('productDetailModal');

    const productModal =
        bootstrap.Modal.getOrCreateInstance(modalElement);

    document.querySelectorAll('.open-product-modal')

        .forEach(button => {

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

                productModal.show();

            });

        });

});
</script>
