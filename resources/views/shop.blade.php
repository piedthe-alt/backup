<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online - Belanja Sekarang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .navbar-shop {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #e0f2fe 0%, #cffafe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .product-info {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 8px;
            color: #1e293b;
            line-height: 1.4;
            flex-grow: 1;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 12px;
        }

        .product-actions {
            display: flex;
            gap: 8px;
        }

        .qty-input {
            width: 50px;
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-add {
            flex: 1;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px;
            font-size: 0.9rem;
        }

        .btn-add:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .cart-sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: 350px;
            height: 100vh;
            background: white;
            box-shadow: -4px 0 12px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .cart-sidebar.open {
            transform: translateX(0);
        }

        .cart-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 20px;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .cart-item {
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 8px;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .cart-item-price {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 4px;
        }

        .cart-item-qty {
            font-weight: 600;
            color: #2563eb;
        }

        .cart-item-remove {
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .cart-summary {
            border-top: 2px solid #e2e8f0;
            padding: 16px;
            background: #f8fafc;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
            font-weight: 700;
            color: #2563eb;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            margin-bottom: 16px;
        }

        .btn-checkout {
            width: 100%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 8px;
        }

        .btn-checkout:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .btn-checkout:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-close-cart {
            width: 100%;
            background: #e2e8f0;
            color: #1e293b;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .cart-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            z-index: 999;
            transition: all 0.3s ease;
        }

        .cart-toggle:hover {
            transform: scale(1.1);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .modal-checkout {
            display: none;
        }

        .modal-checkout.show {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal-content-custom {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
        }

        .modal-header-custom {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-custom h4 {
            margin: 0;
            font-weight: 700;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-submit:hover {
            opacity: 0.9;
        }

        .search-section {
            margin-bottom: 30px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .overlay.show {
            display: block;
        }

        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 12px;
            }

            .cart-sidebar {
                width: 100%;
            }

            .cart-toggle {
                bottom: 20px;
                right: 20px;
            }
        }
    </style>

</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar-shop sticky-top p-3">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-white fw-bold" style="font-size: 1.3rem;">
                    <i class="fas fa-store me-2"></i>Toko Online
                </div>
                <a href="/" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container-fluid py-4">

        <!-- SEARCH -->
        <div class="search-section">
            <input type="text" id="searchInput" class="search-input" placeholder="🔍 Cari produk...">
        </div>

        <!-- PRODUCTS GRID -->
        <div class="product-grid" id="productGrid">
            @foreach ($products as $product)
                <div class="product-card" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                    data-product-price="{{ $product->salesprice1 }}">

                    <div class="product-image">
                        <i class="fas fa-box" style="color: #0284c7;"></i>
                    </div>

                    <div class="product-info">

                        <div class="product-name">{{ $product->name }}</div>

                        <div class="product-price">
                            Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
                        </div>

                        <div class="product-actions">
                            <input type="number" class="qty-input" value="1" min="1" max="{{ $product->stock }}">
                            <button class="btn-add" onclick="addToCart(event)">
                                <i class="fas fa-shopping-cart me-1"></i>Beli
                            </button>
                        </div>

                    </div>

                </div>
            @endforeach
        </div>

    </div>

    <!-- CART SIDEBAR -->
    <div class="cart-sidebar" id="cartSidebar">

        <div class="cart-header">
            <span><i class="fas fa-shopping-cart me-2"></i>Keranjang</span>
            <button class="cart-close" onclick="toggleCart()"><i class="fas fa-times"></i></button>
        </div>

        <div class="cart-items" id="cartItems">
            <div style="text-align: center; color: #64748b; padding: 20px;">
                <i class="fas fa-shopping-cart fa-3x mb-3" style="opacity: 0.3;"></i>
                <p>Keranjang kosong</p>
            </div>
        </div>

        <div class="cart-summary">
            <div class="summary-row">
                <span>Total Item:</span>
                <span id="totalItems">0</span>
            </div>
            <div class="summary-row">
                <span>Total Qty:</span>
                <span id="totalQty">0</span>
            </div>
            <div class="summary-total">
                <span>Total:</span>
                <span id="totalPrice">Rp 0</span>
            </div>
            <button class="btn-checkout" id="checkoutBtn" onclick="openCheckout()" disabled>
                <i class="fas fa-credit-card me-2"></i>Checkout
            </button>
            <button class="btn-close-cart" onclick="toggleCart()">Tutup</button>
        </div>

    </div>

    <!-- CART TOGGLE BUTTON -->
    <button class="cart-toggle" onclick="toggleCart()">
        <i class="fas fa-shopping-cart"></i>
        <div class="cart-badge" id="cartBadge" style="display: none;">0</div>
    </button>

    <!-- OVERLAY -->
    <div class="overlay" id="overlay" onclick="toggleCart()"></div>

    <!-- CHECKOUT MODAL -->
    <div class="modal-checkout" id="checkoutModal">
        <div class="modal-content-custom">

            <div class="modal-header-custom">
                <h4>Form Checkout</h4>
                <button class="modal-close" onclick="closeCheckout()"><i class="fas fa-times"></i></button>
            </div>

            <form id="checkoutForm" onsubmit="processCheckout(event)">

                <div class="form-group">
                    <label>Nama Pembeli <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="customer_name" required placeholder="Masukkan nama Anda">
                </div>

                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="tel" name="customer_phone" placeholder="Masukkan nomor telepon">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="customer_address" placeholder="Masukkan alamat pengiriman"></textarea>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="notes" placeholder="Catatan tambahan (opsional)"></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-check-circle me-2"></i>Proses Pesanan
                </button>

            </form>

        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('shopCart')) || {};

        // Add to cart
        function addToCart(event) {
            const card = event.target.closest('.product-card');
            const productId = card.dataset.productId;
            const productName = card.dataset.productName;
            const productPrice = parseFloat(card.dataset.productPrice);
            const qtyInput = card.querySelector('.qty-input');
            const quantity = parseInt(qtyInput.value) || 1;

            if (!cart[productId]) {
                cart[productId] = {
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 0
                };
            }

            cart[productId].quantity += quantity;
            localStorage.setItem('shopCart', JSON.stringify(cart));
            updateCart();
            qtyInput.value = 1;

            // Show feedback
            event.target.innerHTML = '<i class="fas fa-check"></i> Ditambahkan';
            setTimeout(() => {
                event.target.innerHTML = '<i class="fas fa-shopping-cart me-1"></i>Beli';
            }, 2000);
        }

        // Update cart UI
        function updateCart() {
            const items = Object.values(cart);
            const totalItems = items.length;
            const totalQty = items.reduce((sum, item) => sum + item.quantity, 0);
            const totalPrice = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('totalQty').textContent = totalQty;
            document.getElementById('totalPrice').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');

            if (totalItems > 0) {
                document.getElementById('cartBadge').textContent = totalItems;
                document.getElementById('cartBadge').style.display = 'flex';
                document.getElementById('checkoutBtn').disabled = false;
            } else {
                document.getElementById('cartBadge').style.display = 'none';
                document.getElementById('checkoutBtn').disabled = true;
            }

            renderCartItems();
        }

        // Render cart items
        function renderCartItems() {
            const items = Object.values(cart);
            const container = document.getElementById('cartItems');

            if (items.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; color: #64748b; padding: 20px;">
                        <i class="fas fa-shopping-cart fa-3x mb-3" style="opacity: 0.3;"></i>
                        <p>Keranjang kosong</p>
                    </div>
                `;
                return;
            }

            let html = '';
            items.forEach(item => {
                html += `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">Rp ${item.price.toLocaleString('id-ID')}</div>
                            <div class="cart-item-qty">${item.quantity} x</div>
                        </div>
                        <button class="cart-item-remove" onclick="removeFromCart('${item.id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        // Remove from cart
        function removeFromCart(productId) {
            delete cart[productId];
            localStorage.setItem('shopCart', JSON.stringify(cart));
            updateCart();
        }

        // Toggle cart sidebar
        function toggleCart() {
            document.getElementById('cartSidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('show');
        }

        // Open checkout modal
        function openCheckout() {
            if (Object.keys(cart).length === 0) {
                alert('Keranjang kosong');
                return;
            }
            document.getElementById('checkoutModal').classList.add('show');
            document.getElementById('cartSidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('show');
        }

        // Close checkout modal
        function closeCheckout() {
            document.getElementById('checkoutModal').classList.remove('show');
        }

        // Process checkout
        async function processCheckout(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            const data = {
                items: JSON.stringify(Object.values(cart)),
                customer_name: formData.get('customer_name'),
                customer_phone: formData.get('customer_phone'),
                customer_address: formData.get('customer_address'),
                notes: formData.get('notes'),
            };

            try {
                const btn = form.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                const response = await fetch('/shop/order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Pesanan berhasil dibuat!\nNomor Pesanan: ' + result.order_id);

                    // Download PDF
                    const pdfUrl = `/shop/order/${result.order_id}/pdf`;
                    window.open(pdfUrl, '_blank');

                    // Clear cart
                    cart = {};
                    localStorage.setItem('shopCart', JSON.stringify(cart));
                    updateCart();

                    closeCheckout();
                } else {
                    alert('Error: ' + result.error);
                }

                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Proses Pesanan';

            } catch (error) {
                alert('Error: ' + error.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Proses Pesanan';
            }
        }

        // Search products
        document.getElementById('searchInput').addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');

            cards.forEach(card => {
                const name = card.dataset.productName.toLowerCase();
                if (name.includes(keyword)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Initialize cart on page load
        updateCart();

        // Add CSRF token meta tag if not exists
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.head.appendChild(meta);
        }
    </script>

</body>

</html>
