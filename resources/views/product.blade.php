<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Produk - Stock Manage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

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

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-box.stock {
            border-left-color: var(--info-color);
        }

        .stat-box.masuk {
            border-left-color: var(--success-color);
        }

        .stat-box.keluar {
            border-left-color: var(--warning-color);
        }

        .stat-box h6 {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stat-box .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
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

        .copy-btn i {
            font-size: 0.8rem;
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

        .stock-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .stock-high {
            background-color: #d1fae5;
            color: #065f46;
        }

        .stock-medium {
            background-color: #fef3c7;
            color: #92400e;
        }

        .stock-low {
            background-color: #fee2e2;
            color: #991b1b;
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

        .sort-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .sort-buttons .sort-label {
            font-weight: 600;
            color: #1e293b;
            align-self: center;
            white-space: nowrap;
            margin-right: 8px;
            font-size: 0.95rem;
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

        .table {
            font-size: 0.95rem;
        }

        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #1e293b;
            border-color: var(--border-color);
        }

        .table td {
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .pagination {
            gap: 8px;
        }

        .pagination .page-link {
            border-radius: 6px;
            border: 1px solid var(--border-color);
            color: var(--primary-color);
            padding: 8px 12px;
            font-weight: 500;
        }

        .pagination .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .btn-action {
                padding: 8px 12px;
                font-size: 0.9rem;
            }

            .product-name {
                font-size: 0.9rem;
            }

            .stat-box .stat-value {
                font-size: 24px;
            }

            .header-section {
                padding: 1.5rem !important;
            }

            .header-content h2 {
                font-size: 1.5rem;
            }

            .product-card-header {
                padding: 10px;
            }

            .card-body {
                padding: 10px !important;
            }

            .price-badge {
                font-size: 0.75rem;
                padding: 4px 8px;
            }

            .product-info {
                gap: 4px;
            }

            .info-item {
                min-width: 50px;
                padding: 4px;
            }

            .info-item-label {
                font-size: 0.6rem;
            }

            .info-item-value {
                font-size: 0.75rem;
            }

            .header-section::before {
                width: 200px;
                height: 200px;
            }

            .search-input {
                font-size: 0.95rem;
                padding: 10px 12px;
            }

            .btn-action {
                font-size: 0.85rem;
                padding: 8px 10px;
            }

            .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .card-body {
                padding: 1rem !important;
            }
        }

        @media (max-width: 576px) {
            .header-content {
                text-align: center;
            }

            .header-content h2 {
                font-size: 1.3rem;
            }

            .header-section {
                padding: 1rem !important;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                font-size: 0.8rem;
                padding: 6px 8px;
            }

            .row.g-3 {
                gap: 0.5rem !important;
            }

            .col-md-6,
            .col-md-3 {
                padding: 0.25rem;
            }

            .product-info {
                gap: 3px;
            }

            .info-item {
                min-width: 45px;
                padding: 3px;
            }

            .info-item-label {
                font-size: 0.55rem;
            }

            .info-item-value {
                font-size: 0.7rem;
            }

            .product-name {
                font-size: 0.85rem;
            }

            .price-badge {
                font-size: 0.7rem;
                padding: 3px 6px;
            }
        }

        /* Scanner Modal Styles */
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

            0%,
            100% {
                border-color: var(--primary-color);
                box-shadow: 0 0 10px rgba(37, 99, 235, 0.3);
            }

            50% {
                border-color: var(--success-color);
                box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
            }
        }

        .scanner-footer {
            padding: 1rem;
            background: #f8fafc;
            border-top: 1px solid var(--border-color);
            text-align: center;
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

        .scanner-status.success {
            color: var(--success-color);
            font-weight: 600;
        }

        .scanner-status.error {
            color: var(--danger-color);
            font-weight: 600;
        }

        .spinner-small {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid var(--border-color);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-right: 6px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Cart Styles */
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

        .add-to-cart-btn:active {
            transform: translateY(0);
        }

        .add-to-cart-btn.added {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
        }

        /* Cart Modal Styles */
        .cart-empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .cart-empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .cart-item {
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .cart-item-code {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 6px;
        }

        .cart-item-price {
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .cart-item-qty {
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--primary-color);
            min-width: 60px;
            text-align: center;
        }

        .cart-item-remove {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .cart-item-remove:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .cart-summary {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            padding: 16px;
            border-radius: 8px;
            border: 2px solid var(--primary-color);
            margin-top: 20px;
        }

        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .cart-summary-row:last-child {
            margin-bottom: 0;
            padding-top: 8px;
            border-top: 2px solid rgba(37, 99, 235, 0.2);
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .cart-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .cart-copy-btn {
            flex: 1;
            padding: 12px;
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

        .cart-copy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
        }

        .cart-clear-btn {
            flex: 1;
            padding: 12px;
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .cart-clear-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: var(--danger-color);
        }

        /* ============================================
           MODERN STOCK STATUS BADGE
           ============================================ */

        .stock-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            border: 1px solid transparent;
        }

        .stock-status-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stock-status-badge i {
            font-size: 13px;
        }

        /* DEAD STOCK - Abu-abu */
        .stock-status-badge.status-dead-stock {
            background: rgba(108, 117, 125, 0.12);
            color: #495057;
            border-color: rgba(108, 117, 125, 0.2);
        }

        .stock-status-badge.status-dead-stock:hover {
            background: rgba(108, 117, 125, 0.18);
            border-color: rgba(108, 117, 125, 0.3);
        }

        /* KRITIS - Merah */
        .stock-status-badge.status-kritis {
            background: rgba(220, 53, 69, 0.12);
            color: #dc3545;
            border-color: rgba(220, 53, 69, 0.2);
        }

        .stock-status-badge.status-kritis:hover {
            background: rgba(220, 53, 69, 0.18);
            border-color: rgba(220, 53, 69, 0.3);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15);
        }

        /* MENIPIS - Orange */
        .stock-status-badge.status-menipis {
            background: rgba(255, 152, 0, 0.12);
            color: #ff9800;
            border-color: rgba(255, 152, 0, 0.2);
        }

        .stock-status-badge.status-menipis:hover {
            background: rgba(255, 152, 0, 0.18);
            border-color: rgba(255, 152, 0, 0.3);
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.15);
        }

        /* AMAN - Hijau */
        .stock-status-badge.status-aman {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
            border-color: rgba(16, 185, 129, 0.2);
        }

        .stock-status-badge.status-aman:hover {
            background: rgba(16, 185, 129, 0.18);
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        /* Stock info dengan badge modern */
        .stock-info-modern {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px;
        }

        .stock-current {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            color: #64748b;
        }

        .stock-current-value {
            font-weight: 700;
            color: #1e293b;
            font-size: 16px;
        }

        .stock-estimasi {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Animasi pulse untuk KRITIS */
        @keyframes pulse-kritis {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .stock-status-badge.status-kritis {
            animation: pulse-kritis 2s infinite;
        }

        /* TAB STYLING */
        .nav-tabs .nav-link {
            color: #6b7280 !important;
            border: none !important;
            border-bottom: 3px solid transparent !important;
            padding-bottom: 12px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .nav-tabs .nav-link:hover {
            color: #2563eb !important;
            border-bottom: 3px solid #2563eb !important;
        }

        .nav-tabs .nav-link.active {
            color: #2563eb !important;
            border-bottom: 3px solid #2563eb !important;
            background-color: transparent !important;
        }

    </style>

</head>

<body>

    <div class="container-fluid py-4">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="header-section p-4">

                <div class="header-content">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                        <div>

                            <h2 class="mb-2 fw-bold">
                                <i class="fas fa-boxes me-2"></i>Manajemen Stock Produk
                            </h2>

                            <small class="opacity-75">
                                Kelola dan pantau inventory produk secara real-time
                            </small>

                        </div>

                        <div class="d-flex gap-2 flex-wrap">

                            <a href="/ai-dashboard" class="btn btn-action btn-warning">
                                <i class="fas fa-robot"></i> AI Analysis
                            </a>

                            {{-- <a href="/sales-chart" class="btn btn-action btn-info">
                                <i class="fas fa-chart-line"></i> Grafik
                            </a> --}}

                            <!-- TOMBOL RETUR -->
                            <a href="/return" class="btn btn-action btn-secondary">
                                <i class="fas fa-undo"></i> Retur
                            </a>

                            <!-- TOMBOL PESANAN SHOPEE -->
                            <a href="/pesanan-shopee" class="btn btn-action btn-info">
                                <i class="fas fa-shopping-bag"></i> Pesanan Shopee
                            </a>

                            <button class="btn btn-action btn-success" onclick="startScanner()">
                                <i class="fas fa-camera"></i> Scan
                            </button>

                            <a href="/import-db" class="btn btn-action btn-danger"
                                onclick="return confirm('Yakin mau import database?')">

                                <i class="fas fa-database"></i> Import DB

                            </a>

                            <button class="btn btn-action btn-primary cart-btn-wrapper" onclick="openCartModal()"
                                style="position: relative;">

                                <i class="fas fa-shopping-cart"></i> Cart

                                <span class="cart-badge" id="cart-count" style="display: none;">
                                    0
                                </span>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <!-- FORM SEARCH -->
                <form method="GET" action="/" class="mb-5">

                    <div class="row g-3 mb-4">

                        <!-- SEARCH -->
                        <div class="col-md-6">

                            <div class="d-flex gap-2">

                                <input type="text" name="keyword" id="searchInput" class="form-control search-input"
                                    placeholder="🔍 Cari nama produk atau scan barcode..." value="{{ request('keyword') }}">

                                <button type="button" id="start-scanner" class="btn btn-dark" style="white-space: nowrap;">
                                    <i class="fas fa-barcode"></i>
                                </button>

                            </div>

                            <!-- SCANNER -->
                            <div id="reader" style="width: 100%; margin-top: 10px; display: none;"></div>

                        </div>

                        <!-- FILTER GROUP -->
<!-- FILTER GROUP -->
<div class="col-md-3">

    <div class="position-relative">

        <!-- INPUT SEARCH -->
        <input
            type="text"
            id="groupSearch"
            class="form-control search-input"
            placeholder="📂 Cari Group..."
            autocomplete="off"
            value="@php
                $selectedGroup = $productgroups->firstWhere('id', request('productgroup'));
                echo $selectedGroup ? $selectedGroup->name : '';
            @endphp"
        >

        <!-- HIDDEN -->
        <input
            type="hidden"
            name="productgroup"
            id="selectedGroup"
            value="{{ request('productgroup') }}"
        >

        <!-- DROPDOWN -->
        <div
            id="groupDropdown"
            class="bg-white border rounded shadow-sm"
            style="
                position:absolute;
                top:100%;
                left:0;
                right:0;
                max-height:300px;
                overflow-y:auto;
                z-index:9999;
                display:none;
            "
        >

            <!-- SEMUA -->
            <div
                class="group-item p-2 border-bottom"
                data-id=""
                data-name="Semua Group"
                style="cursor:pointer;"
            >
                📂 Semua Group
            </div>

            <!-- GROUP -->
            @foreach ($productgroups as $group)

                <div
                    class="group-item p-2 border-bottom"
                    data-id="{{ $group->id }}"
                    data-name="{{ strtolower($group->name) }}"
                    data-label="{{ $group->name }}"
                    style="cursor:pointer;"
                >

                    {{ $group->name }}

                </div>

            @endforeach

        </div>

    </div>

</div>

<script>

    document.addEventListener('DOMContentLoaded', function(){

        const searchInput =
            document.getElementById('groupSearch');

        const dropdown =
            document.getElementById('groupDropdown');

        const hiddenInput =
            document.getElementById('selectedGroup');

        const items =
            document.querySelectorAll('.group-item');

        /*
        |--------------------------------------------------------------------------
        | SHOW DROPDOWN
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener('focus', function(){

            dropdown.style.display = 'block';
        });

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        searchInput.addEventListener('keyup', function(){

            const keyword =
                this.value.toLowerCase();

            dropdown.style.display = 'block';

            items.forEach(item => {

                const name =
                    item.dataset.name;

                if(name.includes(keyword)){

                    item.style.display = 'block';

                }else{

                    item.style.display = 'none';
                }
            });
        });

        /*
        |--------------------------------------------------------------------------
        | SELECT ITEM
        |--------------------------------------------------------------------------
        */

        items.forEach(item => {

            item.addEventListener('click', function(){

                hiddenInput.value =
                    this.dataset.id;

                searchInput.value =
                    this.dataset.label || 'Semua Group';

                dropdown.style.display =
                    'none';

                /*
                |--------------------------------------------------------------------------
                | AUTO SUBMIT FORM
                |--------------------------------------------------------------------------
                */

                const form =
                    searchInput.closest('form');

                if(form){

                    form.submit();
                }
            });

            /*
            |--------------------------------------------------------------------------
            | HOVER
            |--------------------------------------------------------------------------
            */

            item.addEventListener('mouseenter', function(){

                this.style.background =
                    '#f8fafc';
            });

            item.addEventListener('mouseleave', function(){

                this.style.background =
                    'white';
            });

        });

        /*
        |--------------------------------------------------------------------------
        | CLOSE OUTSIDE
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function(e){

            if(!e.target.closest('.position-relative')){

                dropdown.style.display = 'none';
            }
        });

    });

</script>

                        <!-- BUTTON -->
                        <div class="col-md-3">

                            <button type="submit" class="btn btn-action btn-primary w-100">

                                <i class="fas fa-search"></i> Cari

                            </button>

                        </div>

                    </div>

                </form>

                <!-- SORTING BUTTONS -->
                <div class="sort-buttons mb-5">
                    <span class="sort-label">📊 Urutkan Berdasarkan:</span>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=stock_terendah"
                        class="sort-btn {{ request('sort') == 'stock_terendah' || !request('sort') ? 'active' : '' }}">
                        <i class="fas fa-arrow-down me-1"></i>Stock Terendah
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=stock_tertinggi"
                        class="sort-btn {{ request('sort') == 'stock_tertinggi' ? 'active' : '' }}">
                        <i class="fas fa-arrow-up me-1"></i>Stock Tertinggi
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=paling_laris"
                        class="sort-btn {{ request('sort') == 'paling_laris' ? 'active' : '' }}">
                        <i class="fas fa-fire me-1"></i>Paling Laris
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=gak_jalan"
                        class="sort-btn {{ request('sort') == 'gak_jalan' ? 'active' : '' }}">
                        <i class="fas fa-snooze me-1"></i>Gak Jalan
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=nama_asc"
                        class="sort-btn {{ request('sort') == 'nama_asc' ? 'active' : '' }}">
                        <i class="fas fa-sort-alpha-down me-1"></i>A-Z
                    </a>

                    <a href="?keyword={{ request('keyword') }}&productgroup={{ request('productgroup') }}&sort=nama_desc"
                        class="sort-btn {{ request('sort') == 'nama_desc' ? 'active' : '' }}">
                        <i class="fas fa-sort-alpha-up-alt me-1"></i>Z-A
                    </a>
                </div>

                <!-- QR READER MODAL -->
                <div id="scanner-modal" class="scanner-modal">
                    <div class="scanner-container">
                        <div class="scanner-header">
                            <h5><i class="fas fa-barcode me-2"></i>Scanner QR/Barcode</h5>
                            <button type="button" class="close-btn" onclick="stopScanner()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="scanner-body">
                            <canvas id="video-canvas"></canvas>
                            <div class="scanner-overlay"></div>
                        </div>
                        <div class="scanner-footer">
                            <div class="scanner-status scanning" id="scanner-status">
                                <span class="spinner-small"></span>Scanning...
                            </div>
                            <small class="text-muted">Arahkan kamera ke barcode atau QR code</small>
                        </div>
                    </div>
                </div>

                <!-- PRODUK -->
                <div class="row g-4">

                    @forelse ($products as $product)
                        <div class="col-md-6 col-lg-4 col-xl-3">

                            <div class="product-card shadow-sm">

                                <div class="product-card-header">

                                    <div class="product-header-wrapper">
                                        <h5 class="product-name">
                                            {{ Str::limit($product->name, 35) }}
                                        </h5>
                                        <button type="button" class="copy-btn"
                                            onclick="copyProductName(event, '{{ $product->id }}', '{{ addslashes($product->name) }}')"
                                            title="Copy nama produk">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>

                                </div>

                                <div class="card-body p-3 d-flex flex-column">

                                    <!-- HARGA -->
                                    <div class="price-badge" onclick="event.stopPropagation()" data-bs-toggle="modal"
                                        data-bs-target="#productModal{{ $loop->index }}" data-product-id="{{ $product->id }}" style="cursor: pointer;">
                                        Rp {{ number_format($product->salesprice1, 0, ',', '.') }}
                                    </div>

                                    <!-- INFO HORIZONTAL -->
                                    <div class="product-info" onclick="event.stopPropagation()"
                                        data-bs-toggle="modal" data-bs-target="#productModal{{ $loop->index }}"
                                        data-product-id="{{ $product->id }}" style="cursor: pointer;">

                                        <!-- STOCK -->
                                        <div class="info-item">
                                            <span class="info-item-label">Stock</span>
                                            @php
                                                $stockStatus = \App\Helpers\StockStatusHelper::getStockStatus(
                                                    $product->stock,
                                                    $product->total_keluar ?? 0,
                                                    $product->created_at ?? null
                                                );
                                            @endphp
                                            <div class="stock-info-modern">
                                                <div class="stock-current">
                                                    <span>Sisa: <strong class="stock-current-value">{{ number_format($product->stock, 0, ',', '.') }}</strong></span>
                                                </div>
                                                <span class="stock-status-badge status-{{ strtolower(str_replace('_', '-', $stockStatus['status'])) }}"
                                                    title="Estimasi habis: {{ $stockStatus['estimasi'] }}">
                                                    <i class="fas {{ $stockStatus['icon'] }}"></i>
                                                    <span>{{ $stockStatus['label'] }}</span>
                                                </span>
                                                <span class="stock-estimasi">{{ $stockStatus['estimasi'] }}</span>
                                            </div>
                                        </div>

                                        <!-- MASUK -->
                                        <div class="info-item">
                                            <span class="info-item-label">Masuk</span>
                                            <span
                                                class="info-item-value">{{ number_format($product->total_masuk, 0, ',', '.') }}</span>
                                        </div>

                                        <!-- KELUAR -->
                                        <div class="info-item">
                                            <span class="info-item-label">Keluar</span>
                                            <span
                                                class="info-item-value">{{ number_format($product->total_keluar, 0, ',', '.') }}</span>
                                        </div>

                                    </div>

                                    <!-- QUANTITY SELECTOR -->
                                    <div class="quantity-selector" onclick="event.stopPropagation();">
                                        <button type="button" class="qty-btn" onclick="decreaseQty(this)">−</button>
                                        <input type="number" class="qty-input" value="1" min="1"
                                            max="{{ $product->stock }}"
                                            onchange="validateQty(this, {{ $product->stock }})">
                                        <button type="button" class="qty-btn"
                                            onclick="increaseQty(this, {{ $product->stock }})">+</button>
                                    </div>

                                    <!-- ADD TO CART BUTTONS -->
                                    <div style="display: flex; gap: 8px;">
                                        <button type="button" class="add-to-cart-btn" style="flex: 1;"
                                            onclick="event.stopPropagation(); addToCart(event, '{{ $product->id }}', '{{ addslashes($product->name) }}', {{ $product->salesprice1 }}, '{{ addslashes($product->productgroup_name) }}', 'pcs')">
                                            <i class="fas fa-box"></i> Beli Pcs
                                        </button>
                                        <button type="button" class="add-to-cart-btn" style="flex: 1;"
                                            onclick="event.stopPropagation(); addToCart(event, '{{ $product->id }}', '{{ addslashes($product->name) }}', {{ $product->salesprice1 }}, '{{ addslashes($product->productgroup_name) }}', 'box')">
                                            <i class="fas fa-cubes"></i> Beli Box
                                        </button>
                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">

                            <div class="empty-state">

                                <i class="fas fa-inbox"></i>

                                <h5 class="text-muted mt-3">Produk tidak ditemukan</h5>

                                <p class="text-muted small">Coba gunakan keyword lain atau ubah filter</p>

                            </div>

                        </div>
                    @endforelse

                </div>

                <!-- PAGINATION -->
                <div class="mt-5 d-flex justify-content-center">

                    {{ $products->links('pagination::bootstrap-4') }}

                </div>

            </div>

        </div>

    </div>

    <!-- MODAL -->
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

                        <!-- TABS -->
                        <ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid #e5e7eb;">
                            <li class="nav-item">
                                <a class="nav-link active" id="detail-tab-{{ $loop->index }}" data-bs-toggle="tab" href="#detail-content-{{ $loop->index }}" role="tab">
                                    <i class="fas fa-info-circle me-2"></i>Detail Produk
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="history-in-tab-{{ $loop->index }}" data-bs-toggle="tab" href="#history-in-content-{{ $loop->index }}" role="tab" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-arrow-down me-2"></i>Riwayat Stok Masuk
                                </a>
                            </li>
                        </ul>

                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <!-- DETAIL TAB -->
                            <div class="tab-pane fade show active" id="detail-content-{{ $loop->index }}" role="tabpanel">

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

                        <!-- PRICING STRATA TABLE -->
                        @php
                            // Check if any salesdiscqty is not 0
                            $hasPricingStrata = false;
                            for ($i = 1; $i <= 7; $i++) {
                                $field = 'salesdiscqty' . $i;
                                if (isset($product->$field) && $product->$field != 0) {
                                    $hasPricingStrata = true;
                                    break;
                                }
                            }
                        @endphp

                        @if ($hasPricingStrata)
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
                                            @for ($i = 1; $i <= 7; $i++)
                                                @php
                                                    $qtyField = 'salesdiscqty' . $i;
                                                    $priceField = 'salesdiscprice' . $i;
                                                    $qty = (isset($product->$qtyField) && $product->$qtyField != 0) ? $product->$qtyField : null;
                                                    $price = isset($product->$priceField) ? $product->$priceField : 0;
                                                @endphp
                                                @if ($qty !== null && $qty != 0)
                                                    <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $i % 2 == 0 ? '#f8fafc' : 'white' }};">
                                                        <td style="text-align: center; font-weight: 600; color: #2563eb;">
                                                            {{ number_format($qty, 0, ',', '.') }}
                                                        </td>
                                                        <td style="text-align: center; color: #64748b;">
                                                            Rp {{ number_format($price, 0, ',', '.') }}
                                                        </td>
                                                        <td style="text-align: center; font-weight: 700; color: #10b981;">
                                                            Rp {{ number_format($qty * $price, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endfor
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
                        @endif

                            </div>

                            <!-- HISTORY MASUK TAB -->
                            <div class="tab-pane fade" id="history-in-content-{{ $loop->index }}" role="tabpanel">
                                <div id="inventory-history-in-loading-{{ $loop->index }}" class="text-center py-4">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2 small">Memuat data barang masuk...</p>
                                </div>
                                <div id="inventory-history-in-content-{{ $loop->index }}" style="display: none;">
                                    <!-- Summary Section -->
                                    <div class="row mb-4 g-2">
                                        <div class="col-12">
                                            <div class="p-3 bg-success bg-opacity-10 rounded-3 text-center">
                                                <small class="text-muted">Total Barang Masuk</small>
                                                <p class="mb-0 h6 text-success fw-bold" id="total-masuk-{{ $loop->index }}">0</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Transactions List -->
                                    <div class="inventory-transactions-in-{{ $loop->index }}">
                                        <p class="text-muted small">Tidak ada data barang masuk</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    @endforeach

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

    <!-- SCRIPT -->
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

                    const unitType = item.type === 'box' ? 'Box' : 'Pcs';
                    copyText += `- ${item.name} = ${item.quantity} ${unitType}\n`;
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

                    alert(`Produk "${barcode}" tidak ditemukan`);
                }

            } catch (error) {

                console.error('Error:', error);
                alert('Terjadi kesalahan saat mencari produk');
            }
        }

        // ==========================================================================
        // SHOW DYNAMIC PRODUCT MODAL
        // ==========================================================================

        function showProductModal(product) {

            const modalHtml = `
                <div class="modal fade" id="scanResultModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header bg-primary text-white">
                                <div>
                                    <h5 class="modal-title">
                                        <i class="fas fa-box me-2"></i>${product.name}
                                    </h5>
                                    <small class="text-white-50">Hasil Scan Produk</small>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
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
                                            <th>
                                                <i class="fas fa-tag me-2 text-primary"></i>Group Produk
                                            </th>
                                            <td>
                                                <span class="badge bg-light text-dark">${product.productgroup_name}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-truck me-2 text-primary"></i>Supplier
                                            </th>
                                            <td>
                                                ${product.supplier_name}
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
                                                <strong class="text-success">${parseInt(product.total_masuk).toLocaleString('id-ID')}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-arrow-up me-2 text-warning"></i>Total Barang Keluar
                                            </th>
                                            <td>
                                                <strong class="text-warning">${parseInt(product.total_keluar).toLocaleString('id-ID')}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

</body>

</html>
