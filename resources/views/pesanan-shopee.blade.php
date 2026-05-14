<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Shopee</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body{
            background:#f1f5f9;
        }

        .product-item{
            background:white;
            border-radius:12px;
            padding:16px;
            margin-bottom:14px;
            border:1px solid #ddd;
        }

        .product-search-result{
            position:absolute;
            top:100%;
            left:0;
            right:0;
            background:white;
            border:1px solid #ddd;
            border-radius:10px;
            z-index:9999;
            max-height:250px;
            overflow-y:auto;
            display:none;
            box-shadow:0 10px 25px rgba(0,0,0,0.1);
        }

        .search-item{
            padding:12px;
            cursor:pointer;
            border-bottom:1px solid #eee;
        }

        .search-item:hover{
            background:#f8fafc;
        }

        .btn-remove{
            width:45px;
        }

        .pesanan-card{
            background:white;
            border-radius:14px;
            padding:18px;
            margin-bottom:15px;
            cursor:pointer;
            transition:0.2s;
            border:1px solid #ddd;
        }

        .pesanan-card:hover{
            transform:translateY(-3px);
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
        }

        .badge-status{
            padding:6px 12px;
            border-radius:999px;
            font-size:13px;
            font-weight:600;
        }

        .status-belum{
            background:#fee2e2;
            color:#dc2626;
        }

        .status-dikirim{
            background:#dcfce7;
            color:#16a34a;
        }
    </style>
</head>

<body>

<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold">
            <i class="fa-solid fa-bag-shopping"></i>
            Pesanan Shopee
        </h2>
    </div>

    <!-- FORM -->

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <h5 class="mb-4">
                Tambah Pesanan
            </h5>

            <form id="pesananForm">

                <div id="productsContainer"></div>

                <button
                    type="button"
                    class="btn btn-primary mb-4"
                    onclick="addProductRow()"
                >
                    <i class="fa-solid fa-plus"></i>
                    Tambah Produk
                </button>

                <div class="mb-4">

                    <label class="form-label">
                        Jenis Pengiriman
                    </label>

                    <select
                        id="jenis"
                        class="form-select"
                        required
                    >
                        <option value="">
                            -- Pilih --
                        </option>

                        <option value="Instant">
                            Instant
                        </option>

                        <option value="SPX">
                            SPX
                        </option>

                        <option value="JNE">
                            JNE
                        </option>

                        <option value="JNT">
                            JNT
                        </option>
                    </select>

                </div>

                <button
                    type="submit"
                    class="btn btn-success w-100"
                >
                    Simpan Pesanan
                </button>

            </form>

        </div>
    </div>

    <!-- FILTER -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-bold">
                        Filter Status
                    </label>

                    <select
                        id="filterStatus"
                        class="form-select"
                        onchange="renderPesanan()"
                    >
                        <option value="ALL">
                            Semua
                        </option>

                        <option value="BELUM DIKIRIM">
                            Belum Dikirim
                        </option>

                        <option value="DIKIRIM">
                            Sudah Dikirim
                        </option>

                    </select>

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-bold">
                        Filter Tanggal
                    </label>

                    <input
                        type="date"
                        id="filterTanggal"
                        class="form-control"
                        onchange="renderPesanan()"
                    >

                </div>

                <div class="col-md-4 mb-3">

                    <label class="form-label fw-bold">
                        Urutkan
                    </label>

                    <select
                        id="sortTanggal"
                        class="form-select"
                        onchange="renderPesanan()"
                    >

                        <option value="DESC">
                            Terbaru
                        </option>

                        <option value="ASC">
                            Terlama
                        </option>

                    </select>

                </div>

            </div>

        </div>

    </div>

    <!-- LIST PESANAN -->

    <div>

        <h4 class="mb-3">
            Daftar Pesanan
        </h4>

        <div id="pesananContainer"></div>

    </div>

</div>

<!-- MODAL -->

<div class="modal fade" id="detailModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Detail Pesanan
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div
                class="modal-body"
                id="detailContent"
            ></div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

                <button
                    type="button"
                    class="btn btn-success"
                    id="updateStatusBtn"
                    onclick="updateStatus()"
                >
                    Atur Pick Up
                </button>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    let products = [];

    let productRowCount = 0;

    let currentPesananId = null;

    document.addEventListener('DOMContentLoaded', async function(){

        const today = new Date();

        const yyyy = today.getFullYear();

        const mm = String(today.getMonth() + 1).padStart(2, '0');

        const dd = String(today.getDate()).padStart(2, '0');

        document.getElementById('filterTanggal').value =
            `${yyyy}-${mm}-${dd}`;

        await loadProducts();

        addProductRow();

        renderPesanan();

    });

    async function loadProducts(){

        try{

            const response =
                await fetch('/api/products');

            products =
                await response.json();

        }catch(error){

            console.error(error);

            alert('Gagal load products');
        }
    }

    function addProductRow(){

        productRowCount++;

        const container =
            document.getElementById('productsContainer');

        const row =
            document.createElement('div');

        row.className = 'product-item';

        row.id = `product-row-${productRowCount}`;

        row.innerHTML = `
            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="position-relative">

                        <input
                            type="text"
                            class="form-control product-search-input"
                            placeholder="Cari produk..."
                            onkeyup="searchProduct(this, ${productRowCount})"
                            autocomplete="off"
                        >

                        <input
                            type="hidden"
                            class="selected-product-id"
                        >

                        <div
                            class="product-search-result"
                            id="search-result-${productRowCount}"
                        ></div>

                    </div>

                </div>

                <div class="col-md-3">

                    <input
                        type="number"
                        class="form-control qty-input"
                        min="1"
                        value="1"
                    >

                </div>

                <div class="col-md-1">

                    <button
                        type="button"
                        class="btn btn-danger btn-remove"
                        onclick="removeProductRow(${productRowCount})"
                    >
                        <i class="fa-solid fa-trash"></i>
                    </button>

                </div>

            </div>
        `;

        container.appendChild(row);
    }

    function removeProductRow(id){

        const row =
            document.getElementById(`product-row-${id}`);

        if(row){

            row.remove();
        }
    }

    function searchProduct(input, rowId){

        const keyword =
            input.value.toLowerCase().trim();

        const resultBox =
            document.getElementById(`search-result-${rowId}`);

        if(!keyword){

            resultBox.style.display = 'none';

            return;
        }

        const filtered =
            products.filter(product => {

                return (
                    product.name.toLowerCase().includes(keyword)
                    ||
                    String(product.id).includes(keyword)
                );
            });

        const limited =
            filtered.slice(0, 30);

        if(limited.length === 0){

            resultBox.innerHTML = `
                <div class="p-3 text-muted">
                    Produk tidak ditemukan
                </div>
            `;

            resultBox.style.display = 'block';

            return;
        }

        resultBox.innerHTML = limited.map(product => `

            <div
                class="search-item"
                onclick="selectProduct(
                    ${rowId},
                    '${product.id}',
                    \`${product.name.replace(/`/g, '')}\`
                )"
            >

                <div class="fw-bold">
                    ${product.name}
                </div>

                <small class="text-muted">
                    ID: ${product.id}
                    |
                    Stock: ${product.stock ?? 0}
                    |
                    Rp ${numberFormat(product.salesprice1)}
                </small>

            </div>

        `).join('');

        resultBox.style.display = 'block';
    }

    function selectProduct(rowId, productId, productName){

        const row =
            document.getElementById(`product-row-${rowId}`);

        row.querySelector('.product-search-input').value =
            productName;

        row.querySelector('.selected-product-id').value =
            productId;

        document.getElementById(
            `search-result-${rowId}`
        ).style.display = 'none';
    }

    document.addEventListener('click', function(e){

        if(!e.target.closest('.position-relative')){

            document
                .querySelectorAll('.product-search-result')

                .forEach(el => {

                    el.style.display = 'none';
                });
        }
    });

    document
        .getElementById('pesananForm')

        .addEventListener('submit', async function(e){

            e.preventDefault();

            const rows =
                document.querySelectorAll('.product-item');

            let idProduk = [];

            let jumlahProduk = [];

            rows.forEach(row => {

                const id =
                    row.querySelector('.selected-product-id').value;

                const qty =
                    row.querySelector('.qty-input').value;

                if(id){

                    idProduk.push(parseInt(id));

                    jumlahProduk.push(parseInt(qty));
                }
            });

            if(idProduk.length === 0){

                alert('Pilih produk');

                return;
            }

            try{

                const response =
                    await fetch('/pesanan-shopee/store', {

                        method:'POST',

                        headers:{
                            'Content-Type':'application/json',
                            'X-CSRF-TOKEN':'{{ csrf_token() }}'
                        },

                        body:JSON.stringify({

                            id_produk:idProduk,

                            jumlah_produk:jumlahProduk,

                            jenis:
                                document.getElementById('jenis').value

                        })
                    });

                const data =
                    await response.json();

                if(data.success){

                    alert('Pesanan berhasil dibuat');

                    document.getElementById('pesananForm').reset();

                    document.getElementById('productsContainer').innerHTML = '';

                    productRowCount = 0;

                    addProductRow();

                    renderPesanan();

                }else{

                    alert(data.message || 'Gagal');
                }

            }catch(error){

                console.error(error);

                alert('Terjadi error');
            }
        });

    async function renderPesanan(){

        try{

            const response =
                await fetch('/api/pesanan-shopee');

            let pesanans =
                await response.json();

            const filterStatus =
                document.getElementById('filterStatus').value;

            const filterTanggal =
                document.getElementById('filterTanggal').value;

            const sortTanggal =
                document.getElementById('sortTanggal').value;

            /*
            |--------------------------------------------------------------------------
            | FILTER STATUS
            |--------------------------------------------------------------------------
            */

            if(filterStatus !== 'ALL'){

                pesanans = pesanans.filter(item => {

                    return item.status === filterStatus;
                });
            }

            /*
            |--------------------------------------------------------------------------
            | FILTER TANGGAL
            |--------------------------------------------------------------------------
            */

            if(filterTanggal){

                pesanans = pesanans.filter(item => {

                    const itemDate =
                        new Date(item.created_at)
                        .toISOString()
                        .split('T')[0];

                    return itemDate === filterTanggal;
                });
            }

            /*
            |--------------------------------------------------------------------------
            | SORTING
            |--------------------------------------------------------------------------
            */

            pesanans.sort((a, b) => {

                const dateA =
                    new Date(a.created_at);

                const dateB =
                    new Date(b.created_at);

                if(sortTanggal === 'ASC'){

                    return dateA - dateB;
                }

                return dateB - dateA;
            });

            const container =
                document.getElementById('pesananContainer');

            if(pesanans.length === 0){

                container.innerHTML = `
                    <div class="alert alert-light border">
                        Tidak ada pesanan
                    </div>
                `;

                return;
            }

            let html = '';

            pesanans.forEach(pesanan => {

                const statusClass =
                    pesanan.status === 'DIKIRIM'
                    ? 'status-dikirim'
                    : 'status-belum';

                html += `
                    <div
                        class="pesanan-card"
                        onclick="showDetail(${pesanan.id_pesanan})"
                    >

                        <div class="d-flex justify-content-between mb-3">

                            <div>

                                <h5 class="mb-1">
                                    #${pesanan.id_pesanan}
                                </h5>

                                <small class="text-muted">
                                    ${pesanan.jenis}
                                </small>

                                <div class="mt-2">
                                    <small class="text-muted">
                                        ${formatTanggal(pesanan.created_at)}
                                    </small>
                                </div>

                            </div>

                            <div>

                                <span class="badge-status ${statusClass}">
                                    ${pesanan.status}
                                </span>

                            </div>

                        </div>

                        <div>
                            <strong>Total:</strong>
                            Rp ${numberFormat(pesanan.total_harga_jual)}
                        </div>

                    </div>
                `;
            });

            container.innerHTML = html;

        }catch(error){

            console.error(error);
        }
    }

    async function showDetail(id){

        currentPesananId = id;

        try{

            const response =
                await fetch(`/pesanan-shopee/detail/${id}`);

            const data =
                await response.json();

            const pesanan =
                data.pesanan;

            const produk =
                data.produk_details;

            let html = `
                <div class="mb-3">
                    <strong>Jenis:</strong>
                    ${pesanan.jenis}
                </div>

                <div class="mb-3">
                    <strong>Tanggal:</strong>
                    ${formatTanggal(pesanan.created_at)}
                </div>

                <div class="mb-4">
                    <strong>Status:</strong>
                    ${pesanan.status}
                </div>
            `;

            produk.forEach(item => {

                html += `
                    <div class="border rounded p-3 mb-3">

                        <div class="fw-bold">
                            ${item.name}
                        </div>

                        <div>
                            Qty: ${item.quantity}
                        </div>

                        <div>
                            Rp ${numberFormat(item.subtotal)}
                        </div>

                    </div>
                `;
            });

            html += `
                <div class="alert alert-primary">

                    Total:
                    <strong>
                        Rp ${numberFormat(pesanan.total_harga_jual)}
                    </strong>

                </div>
            `;

            document.getElementById('detailContent').innerHTML =
                html;

            const updateBtn =
                document.getElementById('updateStatusBtn');

            if(pesanan.status === 'DIKIRIM'){

                updateBtn.style.display = 'none';

            }else{

                updateBtn.style.display = 'inline-block';
            }

            const modal =
                new bootstrap.Modal(
                    document.getElementById('detailModal')
                );

            modal.show();

        }catch(error){

            console.error(error);

            alert('Gagal load detail');
        }
    }

    async function updateStatus(){

        if(!currentPesananId){

            return;
        }

        try{

            const response =
                await fetch(
                    `/pesanan-shopee/update-status/${currentPesananId}`,
                    {
                        method:'POST',
                        headers:{
                            'X-CSRF-TOKEN':'{{ csrf_token() }}'
                        }
                    }
                );

            const data =
                await response.json();

            if(data.success){

                alert('Status berhasil diubah');

                bootstrap.Modal
                    .getInstance(
                        document.getElementById('detailModal')
                    )
                    .hide();

                renderPesanan();
            }

        }catch(error){

            console.error(error);

            alert('Gagal update status');
        }
    }

    function numberFormat(num){

        return new Intl.NumberFormat('id-ID')
            .format(num || 0);
    }

    function formatTanggal(dateString){

        return new Date(dateString)
            .toLocaleString('id-ID',{
                year:'numeric',
                month:'long',
                day:'numeric',
                hour:'2-digit',
                minute:'2-digit'
            });
    }

</script>

</body>
</html>
