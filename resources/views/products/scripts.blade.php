<script>
        // ============ CART MANAGEMENT ============
        let cart = JSON.parse(localStorage.getItem('orderCart')) || {};
        let cartReturns = {}; // Simpan returns data per group

        function addToCart(event, productId, productName, price, groupName, type = 'pcs') {
            event.stopPropagation();

            // Get quantity from the input field
            const qtyInput = event.target.closest('.card-body').querySelector('.qty-input');
            const quantity = parseInt(qtyInput.value) || 1;

            if (quantity <= 0) {
                alert('Masukkan jumlah yang valid');
                return;
            }

            // Create unique key for cart item (productId + type)
            const cartKey = `${productId}_${type}`;

            // Add or update cart item
            if (cart[cartKey]) {
                cart[cartKey].quantity += quantity;
            } else {
                cart[cartKey] = {
                    id: productId,
                    name: productName,
                    price: price,
                    quantity: quantity,
                    group: groupName,
                    type: type
                };
            }

            // Save to localStorage
            localStorage.setItem('orderCart', JSON.stringify(cart));

            // Update badge
            updateCartBadge();

            // Reset quantity
            qtyInput.value = 1;

            // Show notification
            showAddToCartNotification(productName, quantity, type);
        }

        function updateCartBadge() {
            const badge = document.getElementById('cart-count');
            const itemCount = Object.keys(cart).length;

            if (itemCount > 0) {
                badge.textContent = itemCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        function titleCase(text) {
            return text
                .toLowerCase()
                .replace(/\b(\w)/g, char => char.toUpperCase());
        }

        function openCartModal() {
            renderCartItems();
            const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            cartModal.show();
        }

        function renderCartItems() {
            const container = document.getElementById('cart-items-container');
            const summaryContainer = document.getElementById('cart-summary-container');

            if (Object.keys(cart).length === 0) {
                container.innerHTML = `
                    <div class="cart-empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h5 class="text-muted mt-3">Keranjang kosong</h5>
                        <p class="text-muted small">Tambahkan produk dengan menekan tombol "Tambah ke Cart"</p>
                    </div>
                `;
                summaryContainer.style.display = 'none';
                return;
            }

            let html = '';
            let totalItems = 0;
            let totalQty = 0;
            let groupedItems = {};

            // Group items by group name
            for (let cartKey in cart) {
                const item = cart[cartKey];
                const group = item.group || 'Uncategorized';

                if (!groupedItems[group]) {
                    groupedItems[group] = [];
                }
                groupedItems[group].push({
                    cartKey,
                    ...item
                });
                totalItems++;
                totalQty += item.quantity;
            }

            // Render grouped items with returns
            for (let group in groupedItems) {
                html += `<div style="margin-bottom: 20px;">`;
                html +=
                    `<h6 style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; padding: 10px 12px; border-radius: 6px; margin-bottom: 12px; font-weight: 600; margin-top: 0;"><i class="fas fa-folder me-2"></i>Orderan ${group}</h6>`;

                for (let item of groupedItems[group]) {
                    const displayName = titleCase(item.name);
                    const unitType = item.type === 'box' ? 'Box' : 'Pcs';

                    html += `
                        <div class="cart-item">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${displayName}</div>
                                <div class="cart-item-code">Kode: ${item.id}</div>
                            </div>
                            <div class="cart-item-meta">
                                <div class="cart-item-qty-controls">
                                    <button type="button" class="cart-qty-btn" onclick="changeCartQty('${item.cartKey}', -1)">-</button>
                                    <input type="number" class="cart-qty-input" value="${item.quantity}" min="1" onchange="setCartQty('${item.cartKey}', this.value)">
                                    <button type="button" class="cart-qty-btn" onclick="changeCartQty('${item.cartKey}', 1)">+</button>
                                </div>
                                <div class="cart-item-unit">${item.quantity} ${unitType}</div>
                                <button type="button" class="cart-item-remove" onclick="removeFromCart('${item.cartKey}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }

                // Load returns for this group
                loadReturnsForGroupDisplay(group, groupedItems[group], (returnsHtml) => {
                    if (returnsHtml) {
                        const groupDiv = container.querySelector(`[data-group="${group}"]`);
                        if (groupDiv) {
                            groupDiv.insertAdjacentHTML('beforeend', returnsHtml);
                        }
                    }
                });

                html += `<div data-group="${group}"></div></div>`;
            }

            container.innerHTML = html;
            document.getElementById('cart-total-items').textContent = totalItems;
            document.getElementById('cart-total-qty').textContent = totalQty;
            summaryContainer.style.display = 'block';
        }

        function changeCartQty(cartKey, change) {
            if (!cart[cartKey]) {
                return;
            }

            let newQty = cart[cartKey].quantity + change;
            if (newQty < 1) {
                newQty = 1;
            }

            cart[cartKey].quantity = newQty;
            localStorage.setItem('orderCart', JSON.stringify(cart));
            updateCartBadge();
            renderCartItems();
        }

        function setCartQty(cartKey, value) {
            if (!cart[cartKey]) {
                return;
            }

            let qty = parseInt(value, 10);
            if (!Number.isInteger(qty) || qty < 1) {
                qty = cart[cartKey].quantity;
            }

            const maxStock = cart[cartKey].stock;
            if (maxStock && qty > maxStock) {
                alert(`Stock ${cart[cartKey].name} hanya tersedia ${maxStock}`);
                qty = maxStock;
            }

            cart[cartKey].quantity = qty;
            localStorage.setItem('orderCart', JSON.stringify(cart));
            updateCartBadge();
            renderCartItems();
        }

        // Load and display returns for a group
        async function loadReturnsForGroupDisplay(groupName, items, callback) {
            try {
                let returnsHtml = '';
                const returns = await getReturnsForGroup(groupName, items);

                // Simpan returns ke variable global
                if (returns && returns.length > 0) {
                    cartReturns[groupName] = returns;
                } else {
                    cartReturns[groupName] = [];
                }

                if (returns && returns.length > 0) {
                    returnsHtml = `
                        <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.3); padding: 12px; border-radius: 8px; margin-top: 12px;">
                            <div style="font-weight: 600; color: #ef4444; font-size: 0.9rem; margin-bottom: 8px;">
                                <i class="fas fa-undo me-2"></i>Returan:
                            </div>
                    `;

                    for (let ret of returns) {
                        // Cek berbagai kemungkinan field name untuk quantity
                        const qty = ret.quantity_retur || ret.quantity || ret.qty || ret.jumlah || 0;
                        returnsHtml += `
                            <div style="color: #1e293b; font-size: 0.85rem; margin-bottom: 4px;">
                                - ${ret.product_name} = ${qty} Pcs
                            </div>
                        `;
                    }

                    returnsHtml += `</div>`;
                }

                callback(returnsHtml);
            } catch (error) {
                console.error('Error loading returns:', error);
                callback('');
            }
        }

        function removeFromCart(productId) {
            delete cart[productId];
            localStorage.setItem('orderCart', JSON.stringify(cart));
            updateCartBadge();
            renderCartItems();
        }

        function clearCart() {
            if (confirm('Yakin ingin mengosongkan keranjang?')) {
                cart = {};
                localStorage.setItem('orderCart', JSON.stringify(cart));
                updateCartBadge();
                renderCartItems();
            }
        }

        // Fetch returns data for a group
        async function getReturnsForGroup(groupName, items) {
            try {
                let returns = [];

                // Fetch returns for each product in the group
                for (let item of items) {
                    const response = await fetch(`/api/get-returns?product_name=${encodeURIComponent(item.name)}`);

                    if (!response.ok) {
                        console.error(`API error for ${item.name}: Status ${response.status}`);
                        continue;
                    }

                    const data = await response.json();

                    if (data.returns && Array.isArray(data.returns)) {
                        // Jika returns adalah array, spread ke returns utama
                        returns = [...returns, ...data.returns];
                    } else if (data.returns) {
                        // Jika returns adalah single object, push ke array
                        returns.push(data.returns);
                    }

                    if (data.error) {
                        console.error(`API error: ${data.error}`);
                    }
                }

                return returns;
            } catch (error) {
                console.error('Error fetching returns:', error);
                return [];
            }
        }

        async function copyCartList(btn) {

            let copyText = '';

            let groupedItems = {};

            /*
            |--------------------------------------------------------------------------
            | GROUP CART
            |--------------------------------------------------------------------------
            */

            for (let productId in cart) {

                const item = cart[productId];

                const group = item.group || 'Uncategorized';

                if (!groupedItems[group]) {
                    groupedItems[group] = [];
                }

                groupedItems[group].push(item);
            }

            /*
            |--------------------------------------------------------------------------
            | KERANJANG KOSONG
            |--------------------------------------------------------------------------
            */

            if (Object.keys(groupedItems).length === 0) {

                alert('Keranjang kosong');

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LOOP GROUP
            |--------------------------------------------------------------------------
            */

            for (let group in groupedItems) {

                /*
                |--------------------------------------------------------------------------
                | HEADER GROUP
                |--------------------------------------------------------------------------
                */

                copyText += `Orderan ${group}\n`;

                /*
                |--------------------------------------------------------------------------
                | ITEM ORDER
                |--------------------------------------------------------------------------
                */

                for (let item of groupedItems[group]) {
                    const displayName = titleCase(item.name);
                    const unitType = item.type === 'box' ? 'Box' : 'Pcs';
                    copyText += `- ${item.id} - ${displayName} : ${item.quantity} ${unitType}\n`;
                }

                /*
                |--------------------------------------------------------------------------
                | AMBIL RETUR GROUP DARI cartReturns
                |--------------------------------------------------------------------------
                */

                if (cartReturns[group] && cartReturns[group].length > 0) {
                    copyText += `Barang Retur : \n`;
                    for (let ret of cartReturns[group]) {
                        const qty = ret.quantity_retur || ret.quantity || ret.qty || ret.jumlah || 0;
                        copyText += `- ${ret.product_name} = ${qty} Pcs\n`;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | SPASI ANTAR GROUP
                |--------------------------------------------------------------------------
                */

                copyText += `\n`;
            }

            /*
            |--------------------------------------------------------------------------
            | COPY
            |--------------------------------------------------------------------------
            */

            navigator.clipboard.writeText(copyText)

                .then(() => {

                    const originalIcon = btn.innerHTML;

                    const originalBg = btn.style.background;

                    btn.innerHTML =
                        '<i class="fas fa-check"></i> Tersalin';

                    btn.style.background =
                        'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)';

                    setTimeout(() => {

                        btn.innerHTML = originalIcon;

                        btn.style.background = originalBg;

                    }, 2000);

                    showCopyNotification(
                        'Cart',
                        'Daftar order berhasil di-copy'
                    );

                })

                .catch(err => {

                    console.error(err);

                    alert('Gagal mengcopy list order');
                });
        }

        function number_format(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function showAddToCartNotification(productName, quantity, type = 'pcs') {
            const notification = document.createElement('div');
            const displayText = `${productName.substring(0, 25)}${productName.length > 25 ? '...' : ''}`;
            const unitType = type === 'box' ? 'Box' : 'Pcs';

            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
                font-weight: 500;
                max-width: 300px;
            `;
            notification.innerHTML = `
                <i class="fas fa-plus-circle me-2"></i>
                ${displayText} (${quantity} ${unitType}) ditambahkan ke cart
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Initialize cart badge on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartBadge();
        });

        // ============ QUANTITY SELECTOR FUNCTIONS ============
        function increaseQty(btn) {

            const input = btn.closest('.quantity-selector').querySelector('.qty-input');

            let currentVal = parseInt(input.value) || 1;

            input.value = currentVal + 1;
        }

        function decreaseQty(btn) {
            const input = btn.closest('.quantity-selector').querySelector('.qty-input');
            let currentVal = parseInt(input.value) || 1;
            if (currentVal > 1) {
                input.value = currentVal - 1;
            }
        }

        function validateQty(input) {

            let value = parseInt(input.value) || 1;

            if (value < 1) {
                value = 1;
            }

            input.value = value;
        }

        // Copy Product Name Function
        function copyProductName(event, productCode, productName) {
            event.stopPropagation();

            // Format: "- [kode] - [nama produk] :\n"
            const formattedText = `- ${productCode} - ${productName} :\n`;

            // Copy to clipboard
            navigator.clipboard.writeText(formattedText).then(() => {
                const btn = event.target.closest('.copy-btn');
                const originalIcon = btn.innerHTML;

                // Change button appearance
                btn.classList.add('copied');
                btn.innerHTML = '<i class="fas fa-check"></i>';

                // Reset after 2 seconds
                setTimeout(() => {
                    btn.classList.remove('copied');
                    btn.innerHTML = originalIcon;
                }, 2000);

                // Optional: Show toast notification
                showCopyNotification(productCode, productName);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Gagal mengcopy nama produk');
            });
        }

        // Toast Notification Function
        function showCopyNotification(productCode, productName) {
            const notification = document.createElement('div');
            const displayText =
                `- ${productCode} - ${productName.substring(0, 20)}${productName.length > 20 ? '...' : ''} :`;

            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
                font-weight: 500;
                max-width: 300px;
            `;
            notification.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Tercopy: "${displayText}"
            `;

            document.body.appendChild(notification);

            // Add animation
            const style = document.createElement('style');
            if (!document.querySelector('style[data-copy-animation]')) {
                style.setAttribute('data-copy-animation', 'true');
                style.textContent = `
                    @keyframes slideIn {
                        from {
                            transform: translateX(400px);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                `;
                document.head.appendChild(style);
            }

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // ==========================================================================
        // GLOBAL SCANNER STATE
        // ==========================================================================

        let scannerState = {
            isRunning: false,
            lastScannedTime: 0,
            debounceTime: 1500,
            stream: null
        };

        // ==========================================================================
        // START SCANNER
        // ==========================================================================

        async function startScanner() {

            const scannerModal =
                document.getElementById('scanner-modal');

            scannerModal.classList.add('active');

            updateScannerStatus(
                'scanning',
                '📷 Membuka kamera belakang...'
            );

            /*
            |--------------------------------------------------------------------------
            | STOP DULU JIKA MASIH ADA
            |--------------------------------------------------------------------------
            */

            try {

                if (scannerState.isRunning) {

                    Quagga.stop();

                    scannerState.isRunning = false;
                }

            } catch (e) {}

            /*
            |--------------------------------------------------------------------------
            | INIT QUAGGA
            |--------------------------------------------------------------------------
            */

            Quagga.init({

                    inputStream: {

                        name: "Live",

                        type: "LiveStream",

                        target: document.querySelector('#video-canvas'),

                        constraints: {

                            width: {
                                ideal: 1920
                            },

                            height: {
                                ideal: 1080
                            },

                            /*
                            |--------------------------------------------------------------------------
                            | PAKSA KAMERA BELAKANG
                            |--------------------------------------------------------------------------
                            */

                            facingMode: {
                                exact: "environment"
                            }

                        }
                    },

                    locator: {

                        patchSize: "medium",

                        halfSample: false
                    },

                    numOfWorkers: navigator.hardwareConcurrency || 4,

                    frequency: 10,

                    decoder: {

                        readers: [

                            "ean_reader",

                            "ean_8_reader",

                            "code_128_reader",

                            "upc_reader",

                            "upc_e_reader",

                            "code_39_reader",

                            "codabar_reader",

                            "i2of5_reader"

                        ]
                    },

                    locate: true

                },

                function(err) {

                    /*
                    |--------------------------------------------------------------------------
                    | FALLBACK JIKA exact environment GAGAL
                    |--------------------------------------------------------------------------
                    */

                    if (err) {

                        console.warn(
                            'Environment camera gagal, fallback...',
                            err
                        );

                        Quagga.init({

                                inputStream: {

                                    name: "Live",

                                    type: "LiveStream",

                                    target: document.querySelector('#video-canvas'),

                                    constraints: {

                                        width: {
                                            ideal: 1280
                                        },

                                        height: {
                                            ideal: 720
                                        },

                                        facingMode: "environment"
                                    }
                                },

                                locator: {

                                    patchSize: "medium",

                                    halfSample: false
                                },

                                decoder: {

                                    readers: [

                                        "ean_reader",

                                        "ean_8_reader",

                                        "code_128_reader",

                                        "upc_reader",

                                        "upc_e_reader",

                                        "code_39_reader"

                                    ]
                                },

                                locate: true

                            },

                            function(err2) {

                                if (err2) {

                                    console.error(err2);

                                    updateScannerStatus(
                                        'error',
                                        '❌ Kamera gagal dibuka'
                                    );

                                    return;
                                }

                                startQuaggaEngine();
                            }
                        );

                        return;
                    }

                    startQuaggaEngine();
                }
            );
        }

        // ==========================================================================
        // START ENGINE
        // ==========================================================================

        function startQuaggaEngine() {

            Quagga.start();

            scannerState.isRunning = true;

            updateScannerStatus(
                'success',
                '✅ Kamera siap, arahkan ke barcode'
            );

            /*
            |--------------------------------------------------------------------------
            | REMOVE EVENT LAMA
            |--------------------------------------------------------------------------
            */

            Quagga.offDetected(onScanSuccess);

            Quagga.offProcessed(onProcessed);

            /*
            |--------------------------------------------------------------------------
            | EVENT
            |--------------------------------------------------------------------------
            */

            Quagga.onDetected(onScanSuccess);

            Quagga.onProcessed(onProcessed);
        }

        // ==========================================================================
        // DRAW BOX
        // ==========================================================================

        function onProcessed(result) {

            const drawingCtx =
                Quagga.canvas.ctx.overlay;

            const drawingCanvas =
                Quagga.canvas.canvas.overlay;

            if (!drawingCtx || !drawingCanvas) {
                return;
            }

            drawingCtx.clearRect(
                0,
                0,
                drawingCanvas.width,
                drawingCanvas.height
            );

            if (result && result.boxes) {

                result.boxes

                    .filter(box => box !== result.box)

                    .forEach(box => {

                        Quagga.ImageDebug.drawPath(

                            box,

                            {
                                x: 0,
                                y: 1
                            },

                            drawingCtx,

                            {
                                lineWidth: 2
                            }
                        );
                    });
            }

            if (result && result.box) {

                Quagga.ImageDebug.drawPath(

                    result.box,

                    {
                        x: 0,
                        y: 1
                    },

                    drawingCtx,

                    {
                        lineWidth: 3
                    }
                );
            }

            if (
                result &&
                result.codeResult &&
                result.codeResult.code
            ) {

                Quagga.ImageDebug.drawPath(

                    result.line,

                    {
                        x: 'x',
                        y: 'y'
                    },

                    drawingCtx,

                    {
                        color: 'green',
                        lineWidth: 4
                    }
                );
            }
        }

        // ==========================================================================
        // SCAN SUCCESS
        // ==========================================================================

        function onScanSuccess(result) {

            const currentTime = Date.now();

            /*
            |--------------------------------------------------------------------------
            | DEBOUNCE
            |--------------------------------------------------------------------------
            */

            if (
                currentTime - scannerState.lastScannedTime <
                scannerState.debounceTime
            ) {

                return;
            }

            if (
                result.codeResult &&
                result.codeResult.code
            ) {

                const scannedValue =
                    result.codeResult.code;

                console.log(
                    'Scanned:',
                    scannedValue
                );

                /*
                |--------------------------------------------------------------------------
                | SOUND
                |--------------------------------------------------------------------------
                */

                playBeep();

                /*
                |--------------------------------------------------------------------------
                | INPUT SEARCH
                |--------------------------------------------------------------------------
                */

                document.getElementById('searchInput').value =
                    scannedValue;

                updateScannerStatus(
                    'success',
                    `✅ Barcode: ${scannedValue}`
                );

                scannerState.lastScannedTime =
                    currentTime;

                /*
                |--------------------------------------------------------------------------
                | CLOSE SCANNER & FETCH PRODUCT
                |--------------------------------------------------------------------------
                */

                setTimeout(() => {

                    stopScanner();

                    // Fetch product data via API
                    searchProductByBarcode(scannedValue);

                }, 700);
            }
        }

        // ==========================================================================
        // SEARCH PRODUCT BY BARCODE VIA API
        // ==========================================================================

        async function searchProductByBarcode(barcode) {

            try {

                const response = await fetch(
                    `/api/search-product-by-barcode?keyword=${encodeURIComponent(barcode)}`
                );

                const result = await response.json();

                if (result.success && result.data) {

                    // Show dynamic modal with product info
                    showProductModal(result.data);

                } else {

                    const errorMsg = result.message || result.error || 'Produk tidak ditemukan';
                    alert(`❌ ${errorMsg}`);
                    console.error('API Error:', result);
                }

            } catch (error) {

                console.error('Fetch Error:', error);
                alert('❌ Terjadi kesalahan saat mencari produk: ' + error.message);
            }
        }

        // ==========================================================================
        // SHOW DYNAMIC PRODUCT MODAL
        // ==========================================================================

        function showProductModal(product) {

            // Hitung margin
            const costprice = product.costprice || 0;
            const salesprice = product.salesprice1 || 0;
            const margin = costprice > 0 ? ((salesprice - costprice) / costprice) * 100 : 0;

            // Check if ada pricing strata
            let hasPricingStrata = false;
            let pricingStrataHtml = '';

            for (let i = 1; i <= 3; i++) {
                const qtyField = `salesdiscqty${i}`;
                const priceField = `salesdiscprice${i}`;
                const qty = product[qtyField];
                const price = product[priceField];

                if (qty && qty != 0) {
                    hasPricingStrata = true;
                    const bgColor = i % 2 == 0 ? '#f8fafc' : 'white';
                    pricingStrataHtml += `
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: ${bgColor};">
                            <td style="text-align: center; font-weight: 600; color: #2563eb;">
                                ${parseInt(qty).toLocaleString('id-ID')}
                            </td>
                            <td style="text-align: center; color: #64748b;">
                                Rp ${parseInt(price).toLocaleString('id-ID')}
                            </td>
                            <td style="text-align: center; font-weight: 700; color: #10b981;">
                                Rp ${(qty * price).toLocaleString('id-ID')}
                            </td>
                        </tr>
                    `;
                }
            }

            const modalHtml = `
                <div class="modal fade" id="scanResultModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <!-- HEADER -->
                            <div class="modal-header" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; border: none;">
                                <div>
                                    <h5 class="modal-title">
                                        <i class="fas fa-box me-2"></i>${product.name}
                                    </h5>
                                    <small class="text-white-50">Hasil Scan Produk</small>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- BODY -->
                            <div class="modal-body p-4">
                                <!-- TABS -->
                                <ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid #e5e7eb;">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="scan-detail-tab" data-bs-toggle="tab" href="#scan-detail-content" role="tab">
                                            <i class="fas fa-info-circle me-2"></i>Detail Produk
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="scan-history-in-tab" data-bs-toggle="tab" href="#scan-history-in-content" role="tab">
                                            <i class="fas fa-arrow-down me-2"></i>Riwayat Stok Masuk
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="scan-sales-tab" data-bs-toggle="tab" href="#scan-sales-content" role="tab">
                                            <i class="fas fa-chart-line me-2"></i>Penjualan
                                        </a>
                                    </li>
                                </ul>

                                <!-- TAB CONTENT -->
                                <div class="tab-content">
                                    <!-- DETAIL TAB -->
                                    <div class="tab-pane fade show active" id="scan-detail-content" role="tabpanel">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th width="250">
                                                        <i class="fas fa-barcode me-2 text-success"></i>Kode Barang
                                                    </th>
                                                    <td>
                                                        <span class="badge bg-success text-white">${product.id}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="250">
                                                        <i class="fas fa-tag me-2 text-primary"></i>Group Produk
                                                    </th>
                                                    <td>
                                                        <span class="badge bg-light text-dark">${product.productgroup_name || '-'}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <i class="fas fa-truck me-2 text-primary"></i>Supplier
                                                    </th>
                                                    <td>
                                                        ${product.supplier_name || '-'}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <i class="fas fa-warehouse me-2 text-info"></i>Stock Saat Ini
                                                    </th>
                                                    <td>
                                                        <strong class="text-info">${parseInt(product.stock).toLocaleString('id-ID')} pcs</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <i class="fas fa-arrow-down me-2 text-success"></i>Total Barang Masuk
                                                    </th>
                                                    <td>
                                                        <strong class="text-success">${parseInt(product.total_masuk || 0).toLocaleString('id-ID')}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <i class="fas fa-arrow-up me-2 text-warning"></i>Total Barang Keluar
                                                    </th>
                                                    <td>
                                                        <strong class="text-warning">${parseInt(product.total_keluar || 0).toLocaleString('id-ID')}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <i class="fas fa-coins me-2 text-danger"></i>Harga Modal
                                                    </th>
                                                    <td>
                                                        <strong class="text-danger">Rp ${parseInt(costprice).toLocaleString('id-ID')}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <i class="fas fa-money-bill-wave me-2 text-success"></i>Harga Jual
                                                    </th>
                                                    <td>
                                                        <strong class="text-success">Rp ${parseInt(salesprice).toLocaleString('id-ID')}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <i class="fas fa-percent me-2 text-primary"></i>Margin
                                                    </th>
                                                    <td>
                                                        <strong class="text-primary">${margin.toFixed(2).replace('.', ',')}%</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        ${hasPricingStrata ? `
                                            <!-- PRICING STRATA TABLE -->
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
                                                            ${pricingStrataHtml}
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
                                        ` : ''}
                                    </div>

                                    <!-- HISTORY MASUK TAB -->
                                    <div class="tab-pane fade" id="scan-history-in-content" role="tabpanel">
                                        <div id="scan-inventory-history-in-loading" class="text-center py-4">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="text-muted mt-2 small">Memuat data barang masuk...</p>
                                        </div>
                                        <div id="scan-inventory-history-in-content" style="display: none;">
                                            <!-- Summary Section -->
                                            <div class="row mb-4 g-2">
                                                <div class="col-12">
                                                    <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center">
                                                        <small class="text-muted">Total Barang Masuk</small>
                                                        <p class="mb-0 h6 text-success fw-bold" id="scan-total-masuk">0</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Transactions List -->
                                            <div id="scan-inventory-transactions-in">
                                                <p class="text-muted small">Tidak ada data barang masuk</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SALES TAB -->
                                    <div class="tab-pane fade" id="scan-sales-content" role="tabpanel">
                                        <!-- Sales Filter -->
                                        <div class="mb-4 p-3 bg-light rounded-3" style="border-left: 4px solid #2563eb;">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold mb-2">Filter Periode (Hari)</label>
                                                    <select class="form-select form-select-sm" id="scan-sales-days-filter" onchange="loadScanSalesData(document.querySelector('[data-product-id]').getAttribute('data-product-id'), this.value)">
                                                        <option value="7" selected>7 Hari Terakhir</option>
                                                        <option value="14">14 Hari Terakhir</option>
                                                        <option value="30">30 Hari Terakhir</option>
                                                        <option value="60">60 Hari Terakhir</option>
                                                        <option value="90">90 Hari Terakhir</option>
                                                        <option value="365">1 Tahun Terakhir</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Loading State -->
                                        <div id="scan-sales-loading" class="text-center py-4">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="text-muted mt-2 small">Memuat data penjualan...</p>
                                        </div>

                                        <!-- Sales Content -->
                                        <div id="scan-sales-content-data" style="display: none;">
                                            <!-- Summary Cards -->
                                            <div class="row mb-4 g-2">
                                                <div class="col-md-3">
                                                    <div class="p-3 bg-info bg-opacity-10 rounded-3 text-center">
                                                        <small class="text-muted d-block mb-1">Total Qty</small>
                                                        <p class="mb-0 h6 text-info fw-bold" id="scan-sales-total-qty">0</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center">
                                                        <small class="text-muted d-block mb-1">Total Penjualan</small>
                                                        <p class="mb-0 h6 text-success fw-bold" id="scan-sales-total-amount">Rp 0</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-center">
                                                        <small class="text-muted d-block mb-1">Total Margin</small>
                                                        <p class="mb-0 h6 text-warning fw-bold" id="scan-sales-total-margin">Rp 0</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="p-3 bg-secondary bg-opacity-10 rounded-3 text-center">
                                                        <small class="text-muted d-block mb-1">Transaksi</small>
                                                        <p class="mb-0 h6 text-secondary fw-bold" id="scan-sales-transaction-count">0</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Daily Aggregate Table -->
                                            <div class="mt-4">
                                                <h6 class="mb-3" style="font-weight: 700; color: #1e293b;">
                                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Penjualan Per Hari
                                                </h6>
                                                <div style="max-height: 400px; overflow-y: auto;">
                                                    <table class="table table-sm table-hover" style="font-size: 0.9rem; margin-bottom: 0;">
                                                        <thead style="background-color: #f8fafc; position: sticky; top: 0;">
                                                            <tr>
                                                                <th style="text-align: left; color: #1e293b; font-weight: 600; width: 30%;">
                                                                    <i class="fas fa-calendar me-1"></i>Tanggal
                                                                </th>
                                                                <th style="text-align: center; color: #1e293b; font-weight: 600; width: 15%;">
                                                                    <i class="fas fa-cube me-1"></i>Qty
                                                                </th>
                                                                <th style="text-align: right; color: #1e293b; font-weight: 600; width: 25%;">
                                                                    <i class="fas fa-money-bill me-1"></i>Jumlah
                                                                </th>
                                                                <th style="text-align: right; color: #1e293b; font-weight: 600; width: 25%;">
                                                                    <i class="fas fa-chart-line me-1"></i>Margin
                                                                </th>
                                                                <th style="text-align: center; color: #1e293b; font-weight: 600; width: 10%;">
                                                                    <i class="fas fa-exchange me-1"></i>#
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="scan-sales-daily-table">
                                                            <tr>
                                                                <td colspan="5" class="text-center text-muted py-3 small">Tidak ada data penjualan</td>
                                                            </tr>
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
                </div>
            `;

            // Remove old modal if exists
            const oldModal = document.getElementById('scanResultModal');
            if (oldModal) {
                oldModal.remove();
            }

            // Add new modal to page
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Setup tab click handler for history
            document.getElementById('scan-history-in-tab').addEventListener('click', function() {
                loadScanInventoryHistory(product.id);
            });

            // Setup tab click handler for sales
            document.getElementById('scan-sales-tab').addEventListener('click', function() {
                loadScanSalesData(product.id);
            });

            // Show modal
            const modal = new bootstrap.Modal(
                document.getElementById('scanResultModal')
            );

            modal.show();

            // Remove modal from DOM when hidden
            document.getElementById('scanResultModal')
                .addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
        }

        // ==========================================================================
        // LOAD SCAN INVENTORY HISTORY
        // ==========================================================================

        async function loadScanInventoryHistory(productId) {
            const loadingEl = document.getElementById('scan-inventory-history-in-loading');
            const contentEl = document.getElementById('scan-inventory-history-in-content');

            if (contentEl.style.display !== 'none') {
                return; // Already loaded
            }

            try {
                const response = await fetch(`/api/products/${productId}/inventory-history`);
                const result = await response.json();

                if (result.success && result.data) {
                    let totalMasuk = 0;
                    let historyHtml = '';

                    result.data.forEach((item) => {
                        if (item.transtype === 'IN') {
                            totalMasuk += item.invin;
                            historyHtml += `
                                <div class="card border-0 mb-2" style="background-color: #f8fafc;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center g-0">
                                            <div class="col">
                                                <div>
                                                    <p class="mb-0 fw-bold text-dark">${item.notes || 'Barang Masuk'}</p>
                                                    <small class="text-muted">${new Date(item.transdate).toLocaleDateString('id-ID', {year: 'numeric', month: 'long', day: 'numeric'})}</small>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="badge bg-success">${item.invin} pcs</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    });

                    document.getElementById('scan-total-masuk').textContent = `${totalMasuk.toLocaleString('id-ID')} pcs`;
                    document.getElementById('scan-inventory-transactions-in').innerHTML = historyHtml || '<p class="text-muted small">Tidak ada data barang masuk</p>';
                    loadingEl.style.display = 'none';
                    contentEl.style.display = 'block';
                } else {
                    loadingEl.style.display = 'none';
                    contentEl.style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading inventory history:', error);
                loadingEl.style.display = 'none';
                contentEl.style.display = 'block';
            }
        }

        // ==========================================================================
        // SOUND BEEP
        // ==========================================================================

        // function playBeep() {

        //     try {

        //         const audio = new Audio(
        //             'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YVYGAACBhYqFbF1fd5W...
        //             '
        //         );

        //         audio.volume = 0.3;

        //         audio.play();

        //     } catch (e) {}
        // }

        // ==========================================================================
        // UPDATE STATUS
        // ==========================================================================

        function updateScannerStatus(type, message) {

            const statusEl =
                document.getElementById('scanner-status');

            statusEl.className =
                'scanner-status ' + type;

            statusEl.textContent =
                message;
        }

        // ==========================================================================
        // STOP SCANNER
        // ==========================================================================

        function stopScanner() {

            try {

                Quagga.offDetected(onScanSuccess);

                Quagga.offProcessed(onProcessed);

                Quagga.stop();

            } catch (err) {

                console.error(err);
            }

            scannerState.isRunning = false;

            const scannerModal =
                document.getElementById('scanner-modal');

            scannerModal.classList.remove('active');
        }

        // ==========================================================================
        // CLOSE MODAL
        // ==========================================================================

        document
            .getElementById('scanner-modal')

            .addEventListener('click', function(e) {

                if (e.target === this) {

                    stopScanner();
                }
            });

        // ==========================================================================
        // ESC
        // ==========================================================================

        document.addEventListener('keydown', function(e) {

            if (
                e.key === 'Escape' &&
                document
                .getElementById('scanner-modal')
                .classList.contains('active')
            ) {

                stopScanner();
            }
        });

        // ============ INVENTORY HISTORY TAB EVENTS ============
        document.addEventListener('shown.bs.tab', function (e) {
            // Check if it's history-in tab
            if (e.target && e.target.id && e.target.id.startsWith('history-in-tab-')) {
                const productId = e.target.getAttribute('data-product-id');
                const tabIndex = e.target.id.replace('history-in-tab-', '');
                loadInventoryHistoryTab(productId, tabIndex);
            }

            // Check if it's sales tab
            if (e.target && e.target.id && e.target.id.startsWith('sales-tab-')) {
                const productId = e.target.getAttribute('data-product-id');
                const tabIndex = e.target.id.replace('sales-tab-', '');
                const daysFilter = document.getElementById(`sales-days-filter-${tabIndex}`);
                const days = daysFilter ? daysFilter.value : 7;
                loadSalesData(productId, tabIndex, parseInt(days));
            }

            // Check if it's scan sales tab
            if (e.target && e.target.id && e.target.id === 'scan-sales-tab') {
                // Get product ID from the modal's data attribute or from product variable
                const modal = document.getElementById('scanResultModal');
                if (modal && typeof showProductModal !== 'undefined') {
                    // The product ID should be available in the modal
                    const daysFilter = document.getElementById('scan-sales-days-filter');
                    const days = daysFilter ? daysFilter.value : 7;
                    // We'll use the last scanned product ID from the URL or event
                    const productIdElements = document.querySelectorAll('[data-product-id]');
                    if (productIdElements.length > 0) {
                        const productId = productIdElements[0].getAttribute('data-product-id');
                        if (productId) loadScanSalesData(productId, parseInt(days));
                    }
                }
            }
        });

        // ============ INVENTORY HISTORY ============
        function loadInventoryHistoryTab(productId, tabIndex) {
            const loadingId = `inventory-history-in-loading-${tabIndex}`;
            const contentId = `inventory-history-in-content-${tabIndex}`;
            const containerId = `inventory-transactions-in-${tabIndex}`;
            const totalId = `total-masuk-${tabIndex}`;

            const loadingDiv = document.getElementById(loadingId);
            const contentDiv = document.getElementById(contentId);
            const transactionsContainer = document.querySelector(`.${containerId}`);

            if (!loadingDiv || !contentDiv) {
                console.error('Elements not found', {loadingId, contentId});
                return;
            }

            // Reset state setiap kali di-click
            loadingDiv.style.display = 'block';
            contentDiv.style.display = 'none';
            transactionsContainer.innerHTML = '';

            fetch(`/api/product-inventory-history?product_id=${productId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load inventory history');
                    return response.json();
                })
                .then(data => {
                    // Filter transactions yang masuk saja (in)
                    const filteredTransactions = data.transactions.filter(trans => trans.direction === 'in');

                    // Calculate total
                    let total = 0;
                    filteredTransactions.forEach(trans => {
                        total += trans.quantity;
                    });

                    // Update summary
                    document.getElementById(totalId).textContent = number_format(total);

                    // Render transactions
                    if (filteredTransactions.length === 0) {
                        transactionsContainer.innerHTML = `<p class="text-muted small">Tidak ada data barang masuk</p>`;
                        loadingDiv.style.display = 'none';
                        contentDiv.style.display = 'block';
                        return;
                    }

                    let html = '';
                    filteredTransactions.forEach((trans, index) => {
                        const badgeClass = 'bg-success';
                        const directionText = 'MASUK';
                        const colorClass = 'text-success';

                        html += `
                            <div class="transaction-item mb-3 p-3 border rounded-3" style="border-color: #e5e7eb !important; background: #fafbfc;">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="d-flex align-items-start gap-3" style="flex: 1;">
                                        <div class="transaction-icon" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f3f4f6; color: #10b981;">
                                            <i class="fas ${trans.icon}"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <strong class="small">${trans.type}</strong>
                                                <span class="badge ${badgeClass} text-white" style="font-size: 10px; padding: 3px 8px;">${directionText}</span>
                                            </div>
                                            <p class="mb-1 small text-muted"><strong>${trans.transid}</strong></p>
                                            <p class="mb-0 small text-muted">
                                                <i class="far fa-calendar me-1"></i>
                                                ${new Date(trans.date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}
                                            </p>
                                            ${trans.memo ? `<p class="mb-0 small text-muted mt-1"><i class="fas fa-comment me-1"></i>${trans.memo}</p>` : ''}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-0 h6 ${colorClass}" style="font-weight: bold;">
                                            +${number_format(trans.quantity)}
                                        </p>
                                        <small class="text-muted">pcs</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    transactionsContainer.innerHTML = html;
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error loading inventory history:', error);
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                    transactionsContainer.innerHTML = `<p class="text-danger small">Gagal memuat data barang masuk</p>`;
                });
        }

        // ============ SALES DATA ============
        function loadSalesData(productId, tabIndex, days = 7) {
            const loadingId = `sales-loading-${tabIndex}`;
            const contentId = `sales-content-data-${tabIndex}`;
            const loadingDiv = document.getElementById(loadingId);
            const contentDiv = document.getElementById(contentId);

            if (!loadingDiv || !contentDiv) {
                console.error('Sales elements not found', {loadingId, contentId});
                return;
            }

            loadingDiv.style.display = 'block';
            contentDiv.style.display = 'none';

            fetch(`/api/products/${productId}/sales?days=${days}`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load sales data');
                    return response.json();
                })
                .then(data => {
                    if (!data.success) throw new Error(data.error || 'Unknown error');

                    const summary = data.summary;
                    const dailyData = data.daily_aggregate;
                    const chartData = data.chart_data;

                    // Update summary cards
                    document.getElementById(`sales-total-qty-${tabIndex}`).textContent = number_format(summary.total_quantity);
                    document.getElementById(`sales-total-amount-${tabIndex}`).textContent = 'Rp ' + number_format(Math.round(summary.total_amount));
                    document.getElementById(`sales-total-margin-${tabIndex}`).textContent = 'Rp ' + number_format(Math.round(summary.total_margin));
                    document.getElementById(`sales-transaction-count-${tabIndex}`).textContent = summary.transaction_count;

                    // Destroy existing chart if any
                    const chartCanvasId = `sales-chart-${tabIndex}`;
                    const chartCanvas = document.getElementById(chartCanvasId);
                    const existingChart = Chart.helpers.getChart(chartCanvas);
                    if (existingChart) {
                        existingChart.destroy();
                    }

                    // Create chart if we have data
                    if (dailyData.length > 0) {
                        const ctx = chartCanvas.getContext('2d');

                        // Format dates for display
                        const formattedDates = chartData.dates.map(date => {
                            const d = new Date(date + 'T00:00:00');
                            return d.toLocaleDateString('id-ID', { month: 'short', day: 'numeric' });
                        });

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: formattedDates,
                                datasets: [
                                    {
                                        label: 'Omzet (Rp)',
                                        data: chartData.amounts,
                                        borderColor: '#10b981',
                                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4,
                                        yAxisID: 'y',
                                        pointBackgroundColor: '#10b981',
                                        pointBorderColor: '#059669',
                                        pointRadius: 5,
                                        pointHoverRadius: 7
                                    },
                                    {
                                        label: 'Qty (pcs)',
                                        data: chartData.quantities,
                                        borderColor: '#2563eb',
                                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4,
                                        yAxisID: 'y1',
                                        pointBackgroundColor: '#2563eb',
                                        pointBorderColor: '#1d4ed8',
                                        pointRadius: 5,
                                        pointHoverRadius: 7
                                    },
                                    {
                                        label: 'Margin (Rp)',
                                        data: chartData.margins,
                                        borderColor: '#f59e0b',
                                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4,
                                        yAxisID: 'y',
                                        pointBackgroundColor: '#f59e0b',
                                        pointBorderColor: '#d97706',
                                        pointRadius: 5,
                                        pointHoverRadius: 7
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        labels: {
                                            font: { size: 12, weight: '600' },
                                            color: '#1e293b',
                                            padding: 15,
                                            usePointStyle: true
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        titleFont: { size: 13, weight: 'bold' },
                                        bodyFont: { size: 12 },
                                        borderColor: '#e2e8f0',
                                        borderWidth: 1,
                                        callbacks: {
                                            label: function(context) {
                                                if (context.datasetIndex === 0 || context.datasetIndex === 2) {
                                                    return context.dataset.label + ': Rp ' + number_format(Math.round(context.parsed.y));
                                                } else {
                                                    return context.dataset.label + ': ' + number_format(context.parsed.y);
                                                }
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        type: 'linear',
                                        position: 'left',
                                        title: {
                                            display: true,
                                            text: 'Omzet & Margin (Rp)',
                                            font: { size: 12, weight: 'bold' }
                                        },
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp ' + number_format(value);
                                            }
                                        }
                                    },
                                    y1: {
                                        type: 'linear',
                                        position: 'right',
                                        title: {
                                            display: true,
                                            text: 'Qty (pcs)',
                                            font: { size: 12, weight: 'bold' }
                                        },
                                        grid: {
                                            drawOnChartArea: false
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Tanggal',
                                            font: { size: 12, weight: 'bold' }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Render daily data table
                    const tableBody = document.getElementById(`sales-daily-table-${tabIndex}`);

                    if (dailyData.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3 small">Tidak ada data penjualan</td></tr>`;
                        loadingDiv.style.display = 'none';
                        contentDiv.style.display = 'block';
                        return;
                    }

                    let tableHtml = '';
                    dailyData.forEach(day => {
                        const dateObj = new Date(day.date + 'T00:00:00');
                        const formattedDate = dateObj.toLocaleDateString('id-ID', {
                            weekday: 'short',
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });

                        tableHtml += `
                            <tr>
                                <td style="text-align: left; color: #1e293b;">${formattedDate}</td>
                                <td style="text-align: center; font-weight: 600; color: #2563eb;">${number_format(day.quantity)}</td>
                                <td style="text-align: right; font-weight: 600; color: #10b981;">Rp ${number_format(Math.round(day.amount))}</td>
                                <td style="text-align: right; font-weight: 600; color: #f59e0b;">Rp ${number_format(Math.round(day.margin))}</td>
                                <td style="text-align: center; font-weight: 600; color: #6b7280;">${day.transactions}</td>
                            </tr>
                        `;
                    });

                    tableBody.innerHTML = tableHtml;
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error loading sales data:', error);
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                    const tableBody = document.getElementById(`sales-daily-table-${tabIndex}`);
                    tableBody.innerHTML = `<tr><td colspan="5" class="text-danger small text-center py-3">Gagal memuat data penjualan</td></tr>`;
                });
        }

        // ============ SALES DATA FOR SCAN MODAL ============
        function loadScanSalesData(productId, days = 7) {
            const loadingDiv = document.getElementById('scan-sales-loading');
            const contentDiv = document.getElementById('scan-sales-content-data');

            if (!loadingDiv || !contentDiv) {
                console.error('Scan sales elements not found');
                return;
            }

            loadingDiv.style.display = 'block';
            contentDiv.style.display = 'none';

            fetch(`/api/products/${productId}/sales?days=${days}`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load sales data');
                    return response.json();
                })
                .then(data => {
                    if (!data.success) throw new Error(data.error || 'Unknown error');

                    const summary = data.summary;
                    const dailyData = data.daily_aggregate;

                    // Update summary cards
                    document.getElementById('scan-sales-total-qty').textContent = number_format(summary.total_quantity);
                    document.getElementById('scan-sales-total-amount').textContent = 'Rp ' + number_format(Math.round(summary.total_amount));
                    document.getElementById('scan-sales-total-margin').textContent = 'Rp ' + number_format(Math.round(summary.total_margin));
                    document.getElementById('scan-sales-transaction-count').textContent = summary.transaction_count;

                    // Render daily data table
                    const tableBody = document.getElementById('scan-sales-daily-table');

                    if (dailyData.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3 small">Tidak ada data penjualan</td></tr>`;
                        loadingDiv.style.display = 'none';
                        contentDiv.style.display = 'block';
                        return;
                    }

                    let tableHtml = '';
                    dailyData.forEach(day => {
                        const dateObj = new Date(day.date + 'T00:00:00');
                        const formattedDate = dateObj.toLocaleDateString('id-ID', {
                            weekday: 'short',
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });

                        tableHtml += `
                            <tr>
                                <td style="text-align: left; color: #1e293b;">${formattedDate}</td>
                                <td style="text-align: center; font-weight: 600; color: #2563eb;">${number_format(day.quantity)}</td>
                                <td style="text-align: right; font-weight: 600; color: #10b981;">Rp ${number_format(Math.round(day.amount))}</td>
                                <td style="text-align: right; font-weight: 600; color: #f59e0b;">Rp ${number_format(Math.round(day.margin))}</td>
                                <td style="text-align: center; font-weight: 600; color: #6b7280;">${day.transactions}</td>
                            </tr>
                        `;
                    });

                    tableBody.innerHTML = tableHtml;
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error loading sales data:', error);
                    loadingDiv.style.display = 'none';
                    contentDiv.style.display = 'block';
                    const tableBody = document.getElementById('scan-sales-daily-table');
                    tableBody.innerHTML = `<tr><td colspan="5" class="text-danger small text-center py-3">Gagal memuat data penjualan</td></tr>`;
                });
        }

        /*
        |--------------------------------------------------------------------------
        | BARCODE SCANNER
        |--------------------------------------------------------------------------
        */

        const searchInput = document.getElementById('searchInput');
        const scannerBtn = document.getElementById('start-scanner');
        const readerDiv = document.getElementById('reader');

        let scannerRunning = false;
        let html5QrCode = null;

        /*
        |--------------------------------------------------------------------------
        | START SCANNER
        |--------------------------------------------------------------------------
        */

        scannerBtn.addEventListener('click', async function() {

            if (scannerRunning) {

                await html5QrCode.stop();

                readerDiv.style.display = 'none';

                scannerRunning = false;

                scannerBtn.innerHTML = '<i class="fas fa-barcode"></i>';

                return;
            }

            readerDiv.style.display = 'block';

            html5QrCode = new Html5Qrcode("reader");

            scannerRunning = true;

            scannerBtn.innerHTML = '<i class="fas fa-times"></i>';

            Html5Qrcode.getCameras()

                .then(devices => {

                    if (devices && devices.length) {

                        let cameraId = devices[0].id;

                        const backCamera = devices.find(device =>

                            device.label.toLowerCase().includes('back') ||
                            device.label.toLowerCase().includes('rear')

                        );

                        if (backCamera) {

                            cameraId = backCamera.id;

                        }

                        html5QrCode.start(

                            cameraId,

                            {

                                fps: 15,

                                qrbox: {
                                    width: 250,
                                    height: 120
                                }

                            },

                            async (decodedText) => {

                                searchInput.value = decodedText;

                                if (navigator.vibrate) {
                                    navigator.vibrate(200);
                                }

                                await html5QrCode.stop();

                                scannerRunning = false;

                                readerDiv.style.display = 'none';

                                scannerBtn.innerHTML = '<i class="fas fa-barcode"></i>';

                                // Auto search product and show modal
                                setTimeout(() => {
                                    searchProductByBarcode(decodedText);
                                }, 500);
                            }

                        );

                    }

                })

                .catch(err => {

                    alert('Kamera gagal dibuka');

                    console.log(err);

                });

        });
    </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
