<style>
    * {
        font-family: 'Inter', sans-serif;
    }

    :root {
        --primary-color: #2563eb;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #06b6d4;
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
        min-height: 100vh;
        color: #1e293b;
    }

    .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border-color) !important;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
    }

    .header-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .header-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(100px, -100px);
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .product-card {
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
        padding: 12px;
        border-bottom: 1px solid var(--border-color);
    }

    .product-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
        transform: translateY(-8px);
    }

    .product-header-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: space-between;
    }

    .product-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1e293b;
        margin-bottom: 0;
        line-height: 1.3;
        flex: 1;
    }

    .copy-btn {
        background: rgba(37, 99, 235, 0.1);
        border: 1px solid rgba(37, 99, 235, 0.3);
        color: var(--primary-color);
        border-radius: 6px;
        padding: 6px 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .copy-btn:hover {
        background: rgba(37, 99, 235, 0.2);
        border-color: var(--primary-color);
    }

    .copy-btn.copied {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.3);
        color: var(--success-color);
    }

    .price-badge {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        color: white;
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 8px;
        white-space: nowrap;
    }

    .product-info {
        display: flex;
        flex-direction: row;
        gap: 8px;
        flex: 1;
        align-items: center;
        flex-wrap: wrap;
        font-size: 0.8rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        min-width: 60px;
        padding: 6px;
        background: #f8fafc;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }

    .info-item-label {
        color: #64748b;
        font-weight: 500;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 4px;
    }

    .info-item-value {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 0.85rem;
    }

    .stock-high {
        background-color: #d1fae5;
        color: #065f46;
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .stock-medium {
        background-color: #fef3c7;
        color: #92400e;
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .stock-low {
        background-color: #fee2e2;
        color: #991b1b;
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .search-input {
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-action {
        border-radius: 8px;
        font-weight: 600;
        padding: 10px 20px;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .btn-primary.btn-action {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-success.btn-action {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
    }

    .btn-warning.btn-action {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        color: white;
    }

    .btn-danger.btn-action {
        background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        color: white;
    }

    .btn-info.btn-action {
        background: linear-gradient(135deg, var(--info-color) 0%, #0891b2 100%);
        color: white;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        color: white;
        border: none;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .cart-btn-wrapper {
        position: relative;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .qty-btn {
        background: white;
        border: 1px solid var(--border-color);
        color: var(--primary-color);
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .qty-btn:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .qty-input {
        width: 50px;
        text-align: center;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        padding: 6px;
        font-weight: 600;
        color: var(--primary-color);
    }

    .qty-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .add-to-cart-btn {
        width: 100%;
        padding: 10px;
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
    }

    .sort-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .sort-btn {
        border-radius: 8px;
        padding: 8px 14px;
        border: 1.5px solid var(--border-color);
        background: white;
        color: #1e293b;
        font-weight: 500;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .sort-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        background: rgba(37, 99, 235, 0.05);
    }

    .sort-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* Scanner Styles */
    .scanner-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        padding: 20px;
    }

    .scanner-modal.active {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .scanner-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        max-width: 90vw;
        width: 100%;
        max-height: 90vh;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
    }

    .scanner-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        color: white;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .scanner-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .scanner-header .close-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: all 0.3s ease;
    }

    .scanner-header .close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .scanner-body {
        flex: 1;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        min-height: 300px;
    }

    #video-canvas {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
    }

    .scanner-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border: 2px solid var(--primary-color);
        border-radius: 12px;
        width: 250px;
        height: 250px;
        animation: pulse-border 2s infinite;
    }

    @keyframes pulse-border {
        0%, 100% {
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.3);
        }
        50% {
            border-color: var(--success-color);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
        }
    }

    .scanner-status {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .scanner-status.scanning {
        color: var(--primary-color);
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .btn-action {
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .product-name {
            font-size: 0.9rem;
        }

        .header-section {
            padding: 1.5rem !important;
        }

        .card-body {
            padding: 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .btn-action {
            width: 100%;
            font-size: 0.8rem;
            padding: 6px 8px;
        }

        .product-name {
            font-size: 0.85rem;
        }

        .header-section {
            padding: 1rem !important;
        }
    }
</style>
