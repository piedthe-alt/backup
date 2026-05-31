<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode Label - Stock Manage</title>

    {{-- Bootstrap & Utilities --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Styles Component --}}
    @include('products.styles')

    <style>
        .suggestions-box {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 320px;
            overflow-y: auto;
            display: none;
        }

        .suggestion-item {
            transition: background 0.2s ease;
        }

        .suggestion-item:hover {
            background: rgba(37, 99, 235, 0.05);
        }

        .suggestion-item:last-child {
            border-bottom: none !important;
        }

        .print-btn-badge {
            background: rgba(37, 99, 235, 0.1);
            border-color: rgba(37, 99, 235, 0.2);
            color: var(--primary-color);
        }
    </style>
</head>

<body>

    <div class="container-fluid py-4">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER SECTION --}}
            <div class="header-section p-4">
                <div class="header-content">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="mb-2 fw-bold">
                                <i class="fas fa-barcode me-2"></i>Cetak Barcode Label Harga
                            </h2>
                            <small class="opacity-75">
                                Cari produk, kumpulkan daftar cetak, dan hasilkan lembar print label harga dengan barcode.
                            </small>
                        </div>
                        <div>
                            <a href="/" class="btn btn-action btn-secondary bg-white text-dark border-0">
                                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- SEARCH SECTION --}}
                <div class="row mb-4 justify-content-center">
                    <div class="col-lg-8 position-relative">
                        <label for="product-search" class="form-label fw-semibold mb-2">
                            <i class="fas fa-search me-1"></i> Cari Produk (Nama atau Kode)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 border-2">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="product-search" class="form-control search-input border-start-0 border-2 ps-0" 
                                   placeholder="Ketik minimal 2 karakter kode barang atau nama barang..." autocomplete="off">
                        </div>
                        <div id="search-suggestions" class="suggestions-box"></div>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-color);">

                {{-- PRINT LIST TABLE --}}
                <div class="row">
                    <div class="col-12">
                        <h4 class="fw-bold mb-3 d-flex align-items-center justify-content-between">
                            <span><i class="fas fa-list-ol me-2 text-primary"></i>Daftar Label yang Akan Dicetak</span>
                            <span class="badge print-btn-badge fs-6 px-3 py-2" id="total-badge">0 Item</span>
                        </h4>

                        {{-- EMPTY STATE --}}
                        <div id="print-empty-state" class="empty-state border border-dashed rounded-4 p-5 bg-light">
                            <i class="fas fa-barcode d-block mb-3" style="font-size: 4rem; color: #cbd5e1;"></i>
                            <h5 class="fw-semibold text-secondary">Belum ada produk terpilih</h5>
                            <p class="text-muted mb-0">Gunakan pencarian di atas untuk memasukkan produk ke dalam daftar cetak.</p>
                        </div>

                        {{-- TABLE CONTAINER --}}
                        <div id="print-list-container" style="display: none;">
                            <div class="table-responsive border rounded-3 overflow-hidden shadow-sm bg-white mb-4">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 20%">Kode Barang</th>
                                            <th style="width: 40%">Nama Barang</th>
                                            <th style="width: 15%">Harga Barang</th>
                                            <th style="width: 15%">Jumlah Label</th>
                                            <th style="width: 10%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="print-list-body">
                                        <!-- Will be rendered dynamically via JS -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end gap-3 flex-wrap">
                                <button class="btn btn-action btn-danger btn-outline-danger bg-transparent text-danger border-danger px-4" onclick="clearAll()">
                                    <i class="fas fa-trash-alt"></i> Hapus Semua
                                </button>
                                <button class="btn btn-action btn-success px-4" onclick="saveToDatabase(true)">
                                    <i class="fas fa-save"></i> Simpan Daftar
                                </button>
                                <button class="btn btn-action btn-primary px-5 shadow" onclick="printLabels()">
                                    <i class="fas fa-print"></i> Cetak Label (PDF)
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Script logic for search, database sync, and print --}}
    <script>
        let printList = [];

        document.addEventListener('DOMContentLoaded', () => {
            loadFromDatabase();
            setupSearch();
        });

        async function loadFromDatabase() {
            try {
                const response = await fetch('/api/barcode-print/load');
                const data = await response.json();
                if (data && Array.isArray(data)) {
                    printList = data.map(item => ({
                        id: item.id,
                        name: item.name,
                        price: item.price,
                        qty: item.qty
                    }));
                }
                updatePrintTable();
            } catch (error) {
                console.error('Error loading print list from database:', error);
                // Fallback to local storage if API fails
                printList = JSON.parse(localStorage.getItem('barcode_print_list')) || [];
                updatePrintTable();
            }
        }

        async function saveToDatabase(showAlert = true) {
            try {
                const response = await fetch('/api/barcode-print/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ items: printList })
                });
                const result = await response.json();
                if (result.success) {
                    if (showAlert) {
                        alert('✓ Daftar cetak berhasil disimpan ke database!');
                    }
                    return true;
                } else {
                    console.error('Failed to save:', result.error);
                    alert('Gagal menyimpan ke database: ' + result.error);
                    return false;
                }
            } catch (error) {
                console.error('Error saving print list:', error);
                alert('Terjadi kesalahan saat menghubungkan ke database.');
                return false;
            }
        }

        function setupSearch() {
            const searchInput = document.getElementById('product-search');
            const suggestionsContainer = document.getElementById('search-suggestions');

            searchInput.addEventListener('input', async function() {
                const query = this.value.trim();
                if (query.length < 2) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }
                
                try {
                    const response = await fetch(`/api/search-products?keyword=${encodeURIComponent(query)}`);
                    const products = await response.json();
                    
                    if (products.length === 0) {
                        suggestionsContainer.innerHTML = '<div class="p-3 text-center text-muted"><i class="fas fa-info-circle me-1"></i>Produk tidak ditemukan</div>';
                        suggestionsContainer.style.display = 'block';
                        return;
                    }
                    
                    suggestionsContainer.innerHTML = '';
                    products.forEach(product => {
                        const div = document.createElement('div');
                        div.className = 'suggestion-item p-3 border-bottom d-flex justify-content-between align-items-center';
                        div.style.cursor = 'pointer';
                        
                        div.innerHTML = `
                            <div>
                                <div class="fw-semibold text-dark">${product.name}</div>
                                <small class="text-muted">Kode: ${product.id} | Harga: Rp ${formatNumber(product.salesprice1)}</small>
                            </div>
                            <button class="btn btn-sm btn-primary btn-action m-0 py-1 px-2" style="font-size: 0.8rem;">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        `;
                        
                        div.addEventListener('click', (e) => {
                            addProductToPrintList(product);
                            searchInput.value = '';
                            suggestionsContainer.style.display = 'none';
                            searchInput.focus();
                        });
                        
                        suggestionsContainer.appendChild(div);
                    });
                    suggestionsContainer.style.display = 'block';
                } catch (error) {
                    console.error('Error searching products:', error);
                }
            });

            // Close suggestions on click outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                    suggestionsContainer.style.display = 'none';
                }
            });
        }

        function addProductToPrintList(product) {
            const existingIndex = printList.findIndex(item => item.id === product.id);
            if (existingIndex > -1) {
                printList[existingIndex].qty += 1;
            } else {
                printList.push({
                    id: product.id,
                    name: product.name,
                    price: product.salesprice1,
                    qty: 1
                });
            }
            updatePrintTable();
        }

        function updatePrintTable() {
            const tbody = document.getElementById('print-list-body');
            const container = document.getElementById('print-list-container');
            const emptyState = document.getElementById('print-empty-state');
            const totalBadge = document.getElementById('total-badge');
            
            totalBadge.textContent = `${printList.length} Item`;
            
            if (printList.length === 0) {
                container.style.display = 'none';
                emptyState.style.display = 'block';
                return;
            }
            
            container.style.display = 'block';
            emptyState.style.display = 'none';
            
            tbody.innerHTML = '';
            printList.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong class="text-primary font-monospace">${item.id}</strong></td>
                    <td class="fw-semibold">${item.name}</td>
                    <td class="text-success fw-bold">Rp ${formatNumber(item.price)}</td>
                    <td>
                        <div class="quantity-selector m-0" style="width: 120px;">
                            <button class="qty-btn" onclick="adjustQty(${index}, -1)">-</button>
                            <input type="number" class="qty-input w-50 border-0" value="${item.qty}" min="1" onchange="setQty(${index}, this.value)">
                            <button class="qty-btn" onclick="adjustQty(${index}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 py-1 px-2 border-0" onclick="removeItem(${index})">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            localStorage.setItem('barcode_print_list', JSON.stringify(printList));
        }

        function adjustQty(index, delta) {
            printList[index].qty = Math.max(1, printList[index].qty + delta);
            updatePrintTable();
        }

        function setQty(index, value) {
            printList[index].qty = Math.max(1, parseInt(value) || 1);
            updatePrintTable();
        }

        function removeItem(index) {
            printList.splice(index, 1);
            updatePrintTable();
        }

        function clearAll() {
            if (confirm('Apakah Anda yakin ingin mengosongkan seluruh daftar cetak?')) {
                printList = [];
                updatePrintTable();
            }
        }

        async function printLabels() {
            if (printList.length === 0) {
                alert('Daftar cetak kosong!');
                return;
            }
            
            // Auto-save to database first
            const saved = await saveToDatabase(false);
            if (saved) {
                // Open print preview in a new window
                window.open('/products/print-barcode/pdf', '_blank');
            }
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    </script>
</body>

</html>
