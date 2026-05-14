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
    for (let productId in cart) {
        const item = cart[productId];
        const group = item.group || 'Uncategorized';

        if (!groupedItems[group]) {
            groupedItems[group] = [];
        }
        groupedItems[group].push({
            id: productId,
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
            html += `
                <div class="cart-item">
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-code">Kode: ${item.id}</div>
                    </div>
                    <div class="cart-item-qty">${item.quantity} pcs</div>
                    <button type="button" class="cart-item-remove" onclick="removeFromCart('${item.id}')">
                        <i class="fas fa-times"></i>
                    </button>
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

    /* GROUP CART */
    for (let productId in cart) {
        const item = cart[productId];
        const group = item.group || 'Uncategorized';

        if (!groupedItems[group]) {
            groupedItems[group] = [];
        }

        groupedItems[group].push(item);
    }

    /* KERANJANG KOSONG */
    if (Object.keys(groupedItems).length === 0) {
        alert('Keranjang kosong');
        return;
    }

    /* LOOP GROUP */
    for (let group in groupedItems) {

        /* HEADER GROUP */
        copyText += `Orderan ${group}\n`;

        /* ITEM ORDER */
        for (let item of groupedItems[group]) {
            const unitType = item.type === 'box' ? 'Box' : 'Pcs';
            copyText += `- ${item.name} = ${item.quantity} ${unitType}\n`;
        }

        /* AMBIL RETUR GROUP DARI cartReturns */
        if (cartReturns[group] && cartReturns[group].length > 0) {
            copyText += `Barang Retur : \n`;
            for (let ret of cartReturns[group]) {
                const qty = ret.quantity_retur || ret.quantity || ret.qty || ret.jumlah || 0;
                copyText += `- ${ret.product_name} = ${qty} Pcs\n`;
            }
        }

        /* SPASI ANTAR GROUP */
        copyText += `\n`;
    }

    /* COPY */
    navigator.clipboard.writeText(copyText)
        .then(() => {
            const originalIcon = btn.innerHTML;
            const originalBg = btn.style.background;

            btn.innerHTML = '<i class="fas fa-check"></i> Tersalin';
            btn.style.background = 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)';

            setTimeout(() => {
                btn.innerHTML = originalIcon;
                btn.style.background = originalBg;
            }, 2000);

            showCopyNotification('Cart', 'Daftar order berhasil di-copy');
        })
        .catch(err => {
            console.error(err);
            alert('Gagal mengcopy list order');
        });
}

// Initialize cart badge on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
});
