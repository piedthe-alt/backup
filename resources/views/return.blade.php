<!-- PRODUK -->
<div class="col-md-5">

    <label class="form-label fw-semibold">
        Cari Produk
    </label>

    <!-- SEARCH -->
    <input
        type="text"
        id="search-product"
        class="form-control mb-2"
        placeholder="Cari nama / ID produk..."
        autocomplete="off"
    >

    <!-- HASIL SEARCH MOBILE FRIENDLY -->
    <div
        id="search-results"
        class="border rounded-3 bg-white shadow-sm"
        style="
            max-height:260px;
            overflow-y:auto;
            display:none;
        "
    >

        @foreach (DB::table('product')->orderBy('name')->get() as $p)

            <div
                class="product-item p-2 border-bottom"
                data-id="{{ $p->id }}"
                data-name="{{ strtolower($p->name) }}"
                style="
                    cursor:pointer;
                    font-size:14px;
                "
            >

                <strong>{{ $p->id }}</strong>
                -
                {{ $p->name }}

            </div>

        @endforeach

    </div>

    <!-- VALUE YANG DIKIRIM -->
    <input
        type="hidden"
        name="product_id"
        id="selected-product-id"
        required
    >

    <!-- PRODUK TERPILIH -->
    <div
        id="selected-product"
        class="mt-2 text-success fw-semibold"
        style="display:none;"
    >
    </div>

</div>

<script>

    const searchInput =
        document.getElementById('search-product');

    const searchResults =
        document.getElementById('search-results');

    const productItems =
        document.querySelectorAll('.product-item');

    const selectedProductId =
        document.getElementById('selected-product-id');

    const selectedProduct =
        document.getElementById('selected-product');

    /*
    |--------------------------------------------------------------------------
    | SEARCH REALTIME
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener('input', function () {

        const keyword =
            this.value.toLowerCase().trim();

        let found = false;

        /*
        |--------------------------------------------------------------------------
        | JIKA KOSONG
        |--------------------------------------------------------------------------
        */

        if (keyword === '') {

            searchResults.style.display = 'none';

            return;

        }

        /*
        |--------------------------------------------------------------------------
        | FILTER ITEM
        |--------------------------------------------------------------------------
        */

        productItems.forEach(item => {

            const id =
                item.dataset.id.toLowerCase();

            const name =
                item.dataset.name;

            if (
                id.includes(keyword)
                ||
                name.includes(keyword)
            ) {

                item.style.display = 'block';

                found = true;

            } else {

                item.style.display = 'none';

            }

        });

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HASIL
        |--------------------------------------------------------------------------
        */

        searchResults.style.display =
            found
            ?
            'block'
            :
            'none';

    });

    /*
    |--------------------------------------------------------------------------
    | PILIH PRODUK
    |--------------------------------------------------------------------------
    */

    productItems.forEach(item => {

        item.addEventListener('click', function () {

            const id =
                this.dataset.id;

            const text =
                this.innerText.trim();

            /*
            |--------------------------------------------------------------------------
            | SET VALUE
            |--------------------------------------------------------------------------
            */

            selectedProductId.value = id;

            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN TERPILIH
            |--------------------------------------------------------------------------
            */

            selectedProduct.style.display = 'block';

            selectedProduct.innerHTML =
                '✔ Produk dipilih: ' + text;

            /*
            |--------------------------------------------------------------------------
            | ISI INPUT
            |--------------------------------------------------------------------------
            */

            searchInput.value = text;

            /*
            |--------------------------------------------------------------------------
            | HIDE RESULT
            |--------------------------------------------------------------------------
            */

            searchResults.style.display = 'none';

        });

    });

    /*
    |--------------------------------------------------------------------------
    | KLIK LUAR = TUTUP
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (e) {

        if (
            !searchResults.contains(e.target)
            &&
            e.target !== searchInput
        ) {

            searchResults.style.display = 'none';

        }

    });

</script>
