<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f8fafc;
            color: #1e293b;
        }

        .navbar-shop {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
            contain: content;
        }

        .product-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            transition: 0.25s;
            display: flex;
            flex-direction: column;
            will-change: transform;
        }

        .product-card:hover {
            transform: translateY(-3px);
        }

        .product-image {
            height: 150px;
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #2563eb;
        }

        .product-info {
            padding: 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            font-size: 0.95rem;
            min-height: 48px;
            margin-bottom: 10px;
        }

        .product-price {
            color: #2563eb;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .product-stock {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 12px;
        }

        .product-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .qty-input {
            width: 55px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-add {
            flex: 1;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            font-weight: 600;
            padding: 10px;
        }

        .btn-add:hover {
            opacity: 0.92;
        }

        .cart-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 62px;
            height: 62px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            font-size: 1.4rem;
            z-index: 999;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            color: white;
            font-size: 0.75rem;
            display: none;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -100%;
            width: 360px;
            max-width: 100%;
            height: 100vh;
            background: white;
            z-index: 1000;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
        }

        .cart-sidebar.open {
            right: 0;
        }

        .cart-header {
            background: #2563eb;
            color: white;
            padding: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .cart-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 12px;
        }

        .cart-summary {
            border-top: 1px solid #e2e8f0;
            padding: 16px;
            background: white;
        }

        .btn-checkout {
            width: 100%;
            border: none;
            border-radius: 10px;
            background: #10b981;
            color: white;
            font-weight: 600;
            padding: 12px;
        }

        .search-box {
            background: white;
            padding: 18px;
            border-radius: 14px;
            margin-bottom: 22px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        @media(max-width:768px) {

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .product-image {
                height: 120px;
                font-size: 2rem;
            }

            .product-name {
                font-size: 0.85rem;
                min-height: 42px;
            }

            .product-price {
                font-size: 1rem;
            }

            .cart-sidebar {
                width: 100%;
            }

            .btn-add {
                font-size: 0.85rem;
                padding: 8px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar-shop p-3 sticky-top">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <div class="text-white fw-bold fs-5">
                    <i class="fas fa-store me-2"></i>Toko Online
                </div>

                <div class="d-flex gap-2">
                    <a href="/shop/history" class="btn btn-light btn-sm">
                        <i class="fas fa-history"></i>
                    </a>

                    <a href="/" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>

            </div>

        </div>
    </nav>

    <div class="container py-4">

        <!-- SEARCH -->
        <div class="search-box">

            <form method="GET" action="/shop">

                <div class="input-group">

                    <input type="text" class="form-control"
                        name="keyword"
                        placeholder="Cari produk..."
                        value="{{ request('keyword') }}">

                    <button class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>

                </div>

            </form>

        </div>

        <!-- PRODUCT GRID -->
        <div class="product-grid">

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

                        <div class="product-price">
                            Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
                        </div>

                        <div class="product-stock">
                            Stok: {{ number_format($product->stock) }}
                        </div>

                        <div class="product-actions">

                            <input type="number"
                                class="qty-input"
                                min="1"
                                value="1">

                            <button class="btn-add"
                                onclick="addToCart(event)">
                                <i class="fas fa-cart-plus"></i>
                            </button>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>

    </div>

    <!-- CART -->
    <div class="cart-sidebar" id="cartSidebar">

        <div class="cart-header">
            <strong>Keranjang</strong>

            <button class="btn btn-sm btn-light"
                onclick="toggleCart()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="cart-items" id="cartItems">
        </div>

        <div class="cart-summary">

            <div class="d-flex justify-content-between mb-2">
                <span>Total Item</span>
                <strong id="totalItems">0</strong>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <span>Total Harga</span>
                <strong id="totalPrice">Rp 0</strong>
            </div>

            <button class="btn-checkout"
                onclick="checkout()">
                Checkout
            </button>

        </div>

    </div>

    <!-- FLOAT BUTTON -->
    <button class="cart-toggle"
        onclick="toggleCart()">

        <i class="fas fa-shopping-cart"></i>

        <div class="cart-badge" id="cartBadge">
            0
        </div>

    </button>

    <script>
        let cart = JSON.parse(localStorage.getItem('shopCart')) || {};

        function saveCart() {
            localStorage.setItem('shopCart', JSON.stringify(cart));
        }

        function toggleCart() {
            document.getElementById('cartSidebar')
                .classList.toggle('open');
        }

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

            saveCart();

            renderCart();

            event.target.innerHTML =
                '<i class="fas fa-check"></i>';

            setTimeout(() => {

                event.target.innerHTML =
                    '<i class="fas fa-cart-plus"></i>';

            }, 1000);
        }

        function removeCart(id) {

            delete cart[id];

            saveCart();

            renderCart();
        }

        function renderCart() {

            const items = Object.values(cart);

            const cartItems =
                document.getElementById('cartItems');

            const totalItems =
                document.getElementById('totalItems');

            const totalPrice =
                document.getElementById('totalPrice');

            const badge =
                document.getElementById('cartBadge');

            if (items.length === 0) {

                cartItems.innerHTML = `
                    <div class="text-center text-muted mt-5">
                        Keranjang kosong
                    </div>
                `;

                totalItems.innerText = 0;
                totalPrice.innerText = 'Rp 0';

                badge.style.display = 'none';

                return;
            }

            let html = '';

            let totalQty = 0;
            let total = 0;

            items.forEach(item => {

                totalQty += item.quantity;

                total += item.quantity * item.price;

                html += `
                    <div class="cart-item">

                        <div class="fw-semibold">
                            ${item.name}
                        </div>

                        <div class="small text-muted mb-2">
                            ${item.quantity} x Rp ${item.price.toLocaleString('id-ID')}
                        </div>

                        <div class="d-flex justify-content-between align-items-center">

                            <strong>
                                Rp ${(item.quantity * item.price).toLocaleString('id-ID')}
                            </strong>

                            <button class="btn btn-sm btn-danger"
                                onclick="removeCart('${item.id}')">

                                <i class="fas fa-trash"></i>

                            </button>

                        </div>

                    </div>
                `;
            });

            cartItems.innerHTML = html;

            totalItems.innerText = totalQty;

            totalPrice.innerText =
                'Rp ' + total.toLocaleString('id-ID');

            badge.innerText = totalQty;

            badge.style.display = 'flex';
        }

        function checkout() {

            if (Object.keys(cart).length === 0) {

                alert('Keranjang kosong');

                return;
            }

            alert('Lanjutkan checkout');
        }

        renderCart();
    </script>

</body>

</html>
