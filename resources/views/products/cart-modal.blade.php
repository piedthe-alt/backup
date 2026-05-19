<!-- CART MODAL -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- HEADER -->
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">
                        <i class="fas fa-shopping-cart me-2"></i>Keranjang Order
                    </h5>
                    <small class="text-white-50">Daftar produk yang akan di-order</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- BODY -->
            <div class="modal-body p-4">
                <div id="cart-items-container">
                    <div class="cart-empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h5 class="text-muted mt-3">Keranjang kosong</h5>
                        <p class="text-muted small">Tambahkan produk dengan menekan tombol "Tambah ke Cart"</p>
                    </div>
                </div>

                <div id="cart-summary-container" style="display: none;">
                    <div class="cart-summary">
                        <div class="cart-summary-row">
                            <span>Total Item:</span>
                            <span id="cart-total-items">0</span>
                        </div>
                        <div class="cart-summary-row">
                            <span>Total Jumlah:</span>
                            <span id="cart-total-qty">0</span>
                        </div>
                    </div>

                    <div class="cart-actions">
                        <button type="button" class="cart-copy-btn" id="cart-copy-btn"
                            onclick="copyCartList(this)">
                            <i class="fas fa-copy"></i> Copy List
                        </button>
                        <button type="button" class="cart-clear-btn" onclick="clearCart()">
                            <i class="fas fa-trash"></i> Kosongkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
