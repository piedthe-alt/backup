/*
|--------------------------------------------------------------------------
| ADD PRODUCT ROW
|--------------------------------------------------------------------------
*/

function addProductRow() {

productRowCount++;

const container =
document.getElementById('productsContainer');

const row = document.createElement('div');

row.className = 'product-item';

row.id = `product-row-${productRowCount}`;

row.innerHTML = `

<div class="product-select position-relative">

    <input type="text" class="form-control product-search-input" placeholder="Cari produk / barcode..."
        onkeyup="searchProduct(this, ${productRowCount})" autocomplete="off">

    <input type="hidden" class="selected-product-id">

    <div class="product-search-result" id="search-result-${productRowCount}"
        style="
                    position:absolute;
                    top:100%;
                    left:0;
                    right:0;
                    background:white;
                    border:1px solid #ddd;
                    border-radius:10px;
                    z-index:9999;
                    max-height:300px;
                    overflow-y:auto;
                    display:none;
                    box-shadow:0 10px 25px rgba(0,0,0,0.12);
                    margin-top:4px;
                ">
    </div>

</div>

<div class="product-qty">

    <input type="number" class="qty-input" placeholder="Jumlah" min="1" value="1" required>

    <button type="button" class="btn-remove-product" onclick="removeProductRow(${productRowCount})">
        <i class="fas fa-times"></i>
    </button>

</div>
`;

container.appendChild(row);
}

/*
|--------------------------------------------------------------------------
| SEARCH PRODUCT
|--------------------------------------------------------------------------
*/

function searchProduct(input, rowId) {

const keyword =
input.value.toLowerCase().trim();

const resultBox =
document.getElementById(`search-result-${rowId}`);

/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

if (!keyword) {

resultBox.style.display = 'none';

return;
}

/*
|--------------------------------------------------------------------------
| FILTER PRODUCTS
|--------------------------------------------------------------------------
*/

const filtered = products.filter(product => {

return (

product.name
.toLowerCase()
.includes(keyword)

||

String(product.id)
.includes(keyword)
);
});

/*
|--------------------------------------------------------------------------
| LIMIT RESULTS
|--------------------------------------------------------------------------
*/

const limited =
filtered.slice(0, 30);

/*
|--------------------------------------------------------------------------
| NO RESULT
|--------------------------------------------------------------------------
*/

if (limited.length === 0) {

resultBox.innerHTML = `
<div style="
                padding:14px;
                color:#64748b;
                font-size:14px;
            ">
    Produk tidak ditemukan
</div>
`;

resultBox.style.display = 'block';

return;
}

/*
|--------------------------------------------------------------------------
| RENDER RESULT
|--------------------------------------------------------------------------
*/

resultBox.innerHTML = limited.map(product => `

<div onclick="
                selectProduct(
                    ${rowId},
                    '${product.id}',
                    \`${product.name.replace(/`/g, '')}\`
                )
            "
    style="
                padding:12px;
                cursor:pointer;
                border-bottom:1px solid #f1f5f9;
                transition:0.2s;
            "
    onmouseover="
                this.style.background='#eff6ff'
            "
    onmouseout="
                this.style.background='white'
            ">

    <div
        style="
                font-weight:600;
                color:#1e293b;
                font-size:14px;
            ">
        ${product.name}
    </div>

    <div
        style="
                font-size:12px;
                color:#64748b;
                margin-top:4px;
            ">
        ID: ${product.id}
        |
        Stock: ${product.stock}
        |
        Rp ${numberFormat(product.salesprice1)}
    </div>

</div>

`).join('');

resultBox.style.display = 'block';
}

/*
|--------------------------------------------------------------------------
| SELECT PRODUCT
|--------------------------------------------------------------------------
*/

function selectProduct(
rowId,
productId,
productName
) {

const row =
document.getElementById(`product-row-${rowId}`);

/*
|--------------------------------------------------------------------------
| SET INPUT TEXT
|--------------------------------------------------------------------------
*/

row.querySelector('.product-search-input')
.value = productName;

/*
|--------------------------------------------------------------------------
| SET PRODUCT ID
|--------------------------------------------------------------------------
*/

row.querySelector('.selected-product-id')
.value = productId;

/*
|--------------------------------------------------------------------------
| HIDE RESULT
|--------------------------------------------------------------------------
*/

document.getElementById(
`search-result-${rowId}`
).style.display = 'none';
}

/*
|--------------------------------------------------------------------------
| REMOVE PRODUCT ROW
|--------------------------------------------------------------------------
*/

function removeProductRow(id) {

const row =
document.getElementById(`product-row-${id}`);

if (row) {

row.remove();
}
}

/*
|--------------------------------------------------------------------------
| CLOSE DROPDOWN WHEN CLICK OUTSIDE
|--------------------------------------------------------------------------
*/

document.addEventListener('click', function(e) {

if (!e.target.closest('.product-select')) {

document
.querySelectorAll('.product-search-result')

.forEach(el => {

el.style.display = 'none';
});
}
});

/*
|--------------------------------------------------------------------------
| FORM SUBMIT
|--------------------------------------------------------------------------
*/

document.getElementById('pesananForm')

.addEventListener('submit', async (e) => {

e.preventDefault();

const productRows =
document.querySelectorAll(
'#productsContainer .product-item'
);

/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if (productRows.length === 0) {

alert('Tambahkan minimal 1 produk');

return;
}

/*
|--------------------------------------------------------------------------
| ARRAY
|--------------------------------------------------------------------------
*/

const idProduk = [];

const jumlahProduk = [];

/*
|--------------------------------------------------------------------------
| LOOP PRODUCT ROWS
|--------------------------------------------------------------------------
*/

productRows.forEach(row => {

const select =
row.querySelector('.selected-product-id');

const qty =
row.querySelector('.qty-input');

if (
select.value &&
qty.value
) {

idProduk.push(
parseInt(select.value)
);

jumlahProduk.push(
parseInt(qty.value)
);
}
});

/*
|--------------------------------------------------------------------------
| VALIDASI PRODUK
|--------------------------------------------------------------------------
*/

if (idProduk.length === 0) {

alert(
'Pilih produk terlebih dahulu'
);

return;
}

/*
|--------------------------------------------------------------------------
| VALIDASI JENIS
|--------------------------------------------------------------------------
*/

const jenis =
document.getElementById('jenis').value;

if (!jenis) {

alert(
'Pilih jenis pengiriman'
);

return;
}

/*
|--------------------------------------------------------------------------
| REQUEST
|--------------------------------------------------------------------------
*/

try {

const response =
await fetch(
'/pesanan-shopee/store',
{
method: 'POST',

headers: {

'Content-Type':
'application/json',

'X-CSRF-TOKEN':
'{{ csrf_token() }}'
},

body: JSON.stringify({

id_produk: idProduk,

jumlah_produk:
jumlahProduk,

jenis: jenis,

nama_pembeli:
document
.getElementById(
'namaPembeli'
).value || null,

alamat:
document
.getElementById(
'alamat'
).value || null,

catatan:
document
.getElementById(
'catatan'
).value || null
})
}
);

const data =
await response.json();

/*
|--------------------------------------------------------------------------
| SUCCESS
|--------------------------------------------------------------------------
*/

if (data.success) {

alert(
'Pesanan berhasil dibuat'
);

/*
|--------------------------------------------------------------------------
| RESET FORM
|--------------------------------------------------------------------------
*/

document
.getElementById(
'pesananForm'
)
.reset();

document
.getElementById(
'productsContainer'
)
.innerHTML = '';

productRowCount = 0;

/*
|--------------------------------------------------------------------------
| RELOAD PESANAN
|--------------------------------------------------------------------------
*/

loadPesanan();

} else {

alert(
data.message ||
'Gagal membuat pesanan'
);
}

} catch (error) {

console.error(error);

alert(
'Terjadi kesalahan server'
);
}
});
