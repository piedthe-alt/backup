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

        .product-code {
            font-size: 0.78rem;
            color: #64748b;
            margin-top: 4px;
            letter-spacing: 0.02em;
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
            align-items: flex-start;
            gap: 12px;
        }

        .cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .cart-item-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
            word-break: break-word;
        }

        .cart-item-code {
            font-size: 0.85rem;
            color: #64748b;
        }

        .cart-item-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            min-width: 150px;
        }

        .cart-item-qty-controls {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            padding: 6px 8px;
        }

        .cart-item-unit {
            font-size: 0.9rem;
            color: #475569;
            font-weight: 600;
            text-align: right;
        }

        .cart-qty-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            cursor: pointer;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .cart-qty-btn:hover {
            opacity: 0.9;
        }

        .cart-qty-input {
            width: 54px;
            border: none;
            text-align: center;
            font-weight: 700;
            color: #1f2937;
            background: transparent;
        }

        .cart-item-remove {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
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
