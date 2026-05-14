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

    // Add animation if not already added
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
