<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Toko Online</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #0f172a;
            --success: #10b981;
            --danger: #ef4444;
            --gray: #64748b;
            --light: #f8fafc;
            --border: #e2e8f0;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background:
                radial-gradient(circle at top right, #dbeafe 0%, transparent 30%),
                linear-gradient(180deg, #f8fafc 0%, #eef6ff 100%);
            min-height: 100vh;
            color: #0f172a;
        }

        /* NAVBAR */

        .navbar-shop {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, .3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary);
        }

        .nav-btn {
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 600;
        }

        /* HERO */

        .hero-box {
            background:
                linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            border-radius: 24px;
            padding: 32px;
            color: white;
            margin-bottom: 24px;
            overflow: hidden;
            position: relative;
        }

        .hero-box::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
            right: -100px;
            top: -100px;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .hero-subtitle {
            opacity: .9;
            position: relative;
            z-index: 2;
        }

        /* SEARCH */

        .search-wrapper {
            position: relative;
            margin-bottom: 24px;
        }

        .search-input {
            width: 100%;
            border: none;
            border-radius: 18px;
            padding: 16px 20px 16px 50px;
            background: white;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            font-size: .95rem;
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        /* GRID */

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 18px;
        }

        /* CARD */

        .product-card {
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .6);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            transition: .25s;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        }

        .product-image {
            height: 180px;
            background:
                linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--primary);
        }

        .product-info {
            padding: 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .product-name {
            font-size: .95rem;
            font-weight: 700;
            line-height: 1.5;
            min-height: 48px;
            margin-bottom: 10px;
        }

        .product-stock {
            font-size: .8rem;
            color: var(--gray);
            margin-bottom: 12px;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 16px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        .qty-input {
            width: 70px;
            border: 1px solid var(--border);
            border-radius: 12px;
            text-align: center;
            font-weight: 700;
        }

        .btn-add {
            flex: 1;
            border: none;
            border-radius: 14px;
            background:
                linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            font-weight: 700;
            padding: 12px;
            transition: .25s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            opacity: .95;
        }

        /* CART */

        .cart-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            border: none;
            background:
                linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            font-size: 1.3rem;
            box-shadow: 0 20px 30px rgba(37, 99, 235, .3);
            z-index: 999;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            width: 26px;
            height: 26px;
            border-radius: 50%;
            font-size: .75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -420px;
            width: 400px;
            max-width: 100%;
            height: 100vh;
            background: white;
            z-index: 2000;
            transition: .3s;
            display: flex;
            flex-direction: column;
            box-shadow: -10px 0 30px rgba(0, 0, 0, .1);
        }

        .cart-sidebar.open {
            right: 0;
        }

        .cart-header {
            padding: 22px;
            background:
                linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .cart-item {
            display: flex;
            gap: 12px;
            padding: 14px;
            border-radius: 18px;
            background: #f8fafc;
            margin-bottom: 12px;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 700;
            font-size: .9rem;
            margin-bottom: 6px;
        }

        .cart-item-price {
            color: var(--gray);
            font-size: .85rem;
        }

        .cart-summary {
            border-top: 1px solid var(--border);
            padding: 20px;
            background: #f8fafc;
        }

        .summary-row,
        .summary-total {
            display: flex;
            justify-content: space-between;
        }

        .summary-row {
            margin-bottom: 10px;
            color: var(--gray);
        }

        .summary-total {
            font-size: 1.2rem;
            font-weight: 800;
            margin: 16px 0;
            color: var(--primary);
        }

        .btn-checkout {
            width: 100%;
            border: none;
            border-radius: 16px;
            padding: 14px;
            background:
                linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            font-weight: 700;
        }

        /* MODAL */

        .modal-checkout {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3000;
            padding: 16px;
        }

        .modal-checkout.show {
            display: flex;
        }

        .modal-content-custom {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 24px;
            padding: 24px;
        }

        .form-control-custom {
            border-radius: 14px;
            border: 1px solid var(--border);
            padding: 12px 14px;
            width: 100%;
        }

        .btn-submit {
            width: 100%;
            border: none;
            border-radius: 16px;
            padding: 14px;
            background:
                linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            font-weight: 700;
        }

        /* MOBILE */

        @media(max-width:768px) {

            .container-custom {
                padding: 14px;
            }

            .hero-box {
                padding: 24px;
                border-radius: 20px;
            }

            .hero-title {
                font-size: 1.5rem;
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .product-image {
                height: 130px;
                font-size: 2.2rem;
            }

            .product-info {
                padding: 14px;
            }

            .product-name {
                font-size: .82rem;
                min-height: 42px;
            }

            .product-price {
                font-size: 1rem;
            }

            .qty-input {
                width: 55px;
            }

            .btn-add {
                font-size: .8rem;
                padding: 10px;
            }

            .cart-sidebar {
                width: 100%;
            }

            .cart-toggle {
                width: 58px;
                height: 58px;
            }

            .nav-mobile-wrap {
                display: flex;
                gap: 8px;
            }

            .nav-btn {
                padding: 8px 10px;
                font-size: .8rem;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->

    <nav class="navbar-shop">
        <div class="container-fluid px-3 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div class="brand-title">
                    <i class="fas fa-store me-2"></i>
                    SJ MART
                </div>

                <div class="nav-mobile-wrap">

                    <a href="/shop/history" class="btn btn-light nav-btn">
                        <i class="fas fa-history"></i>
                    </a>

                    <a href="/" class="btn btn-light nav-btn">
                        <i class="fas fa-arrow-left"></i>
                    </a>

                </div>

            </div>

        </div>
    </nav>

    <!-- CONTENT -->

    <div class="container-custom p-3 p-md-4">

        <!-- HERO -->

        <div class="hero-box">

            <div class="hero-title">
                Belanja Mudah & Cepat
            </div>

            <div class="hero-subtitle">
                Cari produk favorit Anda dan checkout langsung tanpa ribet.
            </div>

        </div>

        <!-- SEARCH -->

        <div class="search-wrapper">

            <i class="fas fa-search search-icon"></i>

            <input type="text"
                id="searchInput"
                class="search-input"
                placeholder="Cari produk...">

        </div>

        <!-- GRID -->

        <div class="product-grid" id="productGrid">

            @foreach ($products as $product)
                <div class="product-card"
                    data-product-id="{{ $product->id }}"
                    data-product-name="{{ $product->name }}"
                    data-product-price="{{ $product->salesprice1 }}">

                    <div class="product-image">
                        <i class="fas fa-box"></i>
                    </div>

                    <div class="product-info">

                        <div class="product-name">
                            {{ $product->name }}
                        </div>

                        <div class="product-stock">
                            Stock: {{ number_format($product->stock) }}
                        </div>

                        <div class="product-price">
                            Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
                        </div>

                        <div class="product-actions">

                            <input type="number"
                                class="qty-input"
                                value="1"
                                min="1">

                            <button class="btn-add"
                                onclick="addToCart(event)">

                                <i class="fas fa-cart-plus me-1"></i>
                                Beli

                            </button>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

    <!-- CART BUTTON -->

    <button class="cart-toggle" onclick="toggleCart()">

        <i class="fas fa-shopping-bag"></i>

        <div class="cart-badge" id="cartBadge" style="display:none">
            0
        </div>

    </button>

    <!-- CART -->

    <div class="cart-sidebar" id="cartSidebar">

        <div class="cart-header">

            <div>
                <i class="fas fa-shopping-cart me-2"></i>
                Keranjang
            </div>

            <button class="btn btn-sm btn-light"
                onclick="toggleCart()">

                <i class="fas fa-times"></i>

            </button>

        </div>

        <div class="cart-items" id="cartItems"></div>

        <div class="cart-summary">

            <div class="summary-row">
                <span>Total Item</span>
                <span id="totalItems">0</span>
            </div>

            <div class="summary-row">
                <span>Total Qty</span>
                <span id="totalQty">0</span>
            </div>

            <div class="summary-total">
                <span>Total</span>
                <span id="totalPrice">Rp 0</span>
            </div>

            <button class="btn-checkout"
                onclick="openCheckout()">

                <i class="fas fa-credit-card me-2"></i>
                Checkout

            </button>

        </div>

    </div>

    <!-- MODAL -->

    <div class="modal-checkout" id="checkoutModal">

        <div class="modal-content-custom">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h5 class="fw-bold mb-0">
                    Checkout
                </h5>

                <button class="btn btn-light"
                    onclick="closeCheckout()">

                    <i class="fas fa-times"></i>

                </button>

            </div>

            <form onsubmit="processCheckout(event)">

                <div class="mb-3">

                    <label class="fw-semibold mb-2">
                        Nama Pembeli
                    </label>

                    <input type="text"
                        name="customer_name"
                        class="form-control-custom"
                        required>

                </div>

                <div class="mb-3">

                    <label class="fw-semibold mb-2">
                        Nomor HP
                    </label>

                    <input type="text"
                        name="customer_phone"
                        class="form-control-custom">

                </div>

                <div class="mb-3">

                    <label class="fw-semibold mb-2">
                        Alamat
                    </label>

                    <textarea name="customer_address"
                        class="form-control-custom"
                        rows="3"></textarea>

                </div>

                <div class="mb-4">

                    <label class="fw-semibold mb-2">
                        Catatan
                    </label>

                    <textarea name="notes"
                        class="form-control-custom"
                        rows="2"></textarea>

                </div>

                <button type="submit" class="btn-submit">

                    <i class="fas fa-check-circle me-2"></i>
                    Proses Pesanan

                </button>

            </form>

        </div>

    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('shopCart')) || {};

        function addToCart(event) {

            const card = event.target.closest('.product-card');

            const id = card.dataset.productId;
            const name = card.dataset.productName;
            const price = parseFloat(card.dataset.productPrice);

            const qty = parseInt(
                card.querySelector('.qty-input').value
            );

            if (!cart[id]) {

                cart[id] = {
                    id,
                    name,
                    price,
                    quantity: 0
                };
            }

            cart[id].quantity += qty;

            localStorage.setItem(
                'shopCart',
                JSON.stringify(cart)
            );

            updateCart();
        }

        function updateCart() {

            const items = Object.values(cart);

            document.getElementById('totalItems').innerText = items.length;

            document.getElementById('totalQty').innerText =
                items.reduce((a, b) => a + b.quantity, 0);

            const total =
                items.reduce((a, b) => a + (b.price * b.quantity), 0);

            document.getElementById('totalPrice').innerText =
                'Rp ' + total.toLocaleString('id-ID');

            const badge = document.getElementById('cartBadge');

            if (items.length > 0) {

                badge.style.display = 'flex';
                badge.innerText = items.length;

            } else {

                badge.style.display = 'none';
            }

            renderCart();
        }

        function renderCart() {

            const items = Object.values(cart);

            const container = document.getElementById('cartItems');

            if (items.length === 0) {

                container.innerHTML = `
                    <div class="text-center text-secondary mt-5">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
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

                            <div class="cart-item-name">
                                ${item.name}
                            </div>

                            <div class="cart-item-price">
                                Rp ${item.price.toLocaleString('id-ID')}
                            </div>

                            <div class="fw-bold text-primary mt-1">
                                ${item.quantity} x
                            </div>

                        </div>

                        <button class="btn btn-sm btn-danger"
                            onclick="removeItem('${item.id}')">

                            <i class="fas fa-trash"></i>

                        </button>

                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function removeItem(id) {

            delete cart[id];

            localStorage.setItem(
                'shopCart',
                JSON.stringify(cart)
            );

            updateCart();
        }

        function toggleCart() {

            document.getElementById('cartSidebar')
                .classList.toggle('open');
        }

        function openCheckout() {

            if (Object.keys(cart).length === 0) {
                alert('Keranjang kosong');
                return;
            }

            document.getElementById('checkoutModal')
                .classList.add('show');
        }

        function closeCheckout() {

            document.getElementById('checkoutModal')
                .classList.remove('show');
        }

        async function processCheckout(event) {

            event.preventDefault();

            const formData = new FormData(event.target);

            const payload = {
                items: JSON.stringify(Object.values(cart)),
                customer_name: formData.get('customer_name'),
                customer_phone: formData.get('customer_phone'),
                customer_address: formData.get('customer_address'),
                notes: formData.get('notes'),
            };

            const response = await fetch('/shop/order', {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },

                body: JSON.stringify(payload)

            });

            const result = await response.json();

            if (response.ok) {

                alert('Pesanan berhasil dibuat');

                window.open(
                    `/shop/order/${result.order_id}/pdf`,
                    '_blank'
                );

                cart = {};

                localStorage.setItem(
                    'shopCart',
                    JSON.stringify(cart)
                );

                updateCart();

                closeCheckout();

            } else {

                alert(result.error || 'Terjadi kesalahan');
            }
        }

        document.getElementById('searchInput')
            .addEventListener('input', function() {

                const keyword = this.value.toLowerCase();

                document.querySelectorAll('.product-card')
                    .forEach(card => {

                        const name =
                            card.dataset.productName.toLowerCase();

                        card.style.display =
                            name.includes(keyword)
                            ?
                            ''
                            :
                            'none';
                    });
            });

        updateCart();
    </script>

</body>

</html>
