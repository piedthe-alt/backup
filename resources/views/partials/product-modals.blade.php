<!-- PRODUCT DETAIL MODALS -->
@foreach ($products as $product)
    <div class="modal fade" id="productModal{{ $loop->index }}" tabindex="-1" data-product-id="{{ $product->id }}">

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

                </div>

            </div>

        </div>

    </div>
@endforeach
