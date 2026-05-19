<!-- CART MODAL -->
<div class="modal fade" id="cartModal" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div
                class="modal-header border-0 text-white"
                style="
                    background:
                        linear-gradient(
                            135deg,
                            #2563eb,
                            #1d4ed8
                        );
                    padding:1.3rem 1.5rem;
                "
            >

                <div>

                    <h4 class="modal-title fw-bold mb-1">

                        <i class="fas fa-shopping-cart me-2"></i>
                        Keranjang Order

                    </h4>

                    <small class="text-white-50">

                        Daftar produk yang akan di-order

                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <!-- BODY -->
            <div class="modal-body p-4">

                <!-- EMPTY -->
                <div id="cart-empty-state" class="text-center py-5">

                    <i
                        class="fas fa-shopping-cart mb-3"
                        style="
                            font-size:70px;
                            color:#cbd5e1;
                        "
                    ></i>

                    <h4 class="fw-bold text-secondary">

                        Keranjang Kosong

                    </h4>

                    <p class="text-muted mb-0">

                        Tambahkan produk terlebih dahulu

                    </p>

                </div>

                <!-- CART ITEMS -->
                <div id="cart-items-container"></div>

                <!-- SUMMARY -->
                <div
                    id="cart-summary-container"
                    style="display:none;"
                >

                    <div
                        class="rounded-4 p-4 mt-4"
                        style="
                            background:#f8fafc;
                            border:1px solid #e2e8f0;
                        "
                    >

                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">

                                Total Item

                            </span>

                            <strong id="cart-total-items">

                                0

                            </strong>

                        </div>

                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">

                                Total Qty

                            </span>

                            <strong id="cart-total-qty">

                                0

                            </strong>

                        </div>

                    </div>

                    <!-- ACTION -->
                    <div class="d-flex gap-2 mt-3">

                        <button
                            type="button"
                            class="btn btn-primary rounded-pill px-4"
                            onclick="copyCartList(this)"
                        >

                            <i class="fas fa-copy me-2"></i>
                            Copy List

                        </button>

                        <button
                            type="button"
                            class="btn btn-danger rounded-pill px-4"
                            onclick="clearCart()"
                        >

                            <i class="fas fa-trash me-2"></i>
                            Kosongkan

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */

    let cart =
        JSON.parse(localStorage.getItem('cart')) || [];

    /*
    |--------------------------------------------------------------------------
    | SAVE CART
    |--------------------------------------------------------------------------
    */

    function saveCart(){

        localStorage.setItem(
            'cart',
            JSON.stringify(cart)
        );

        renderCart();
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER CART
    |--------------------------------------------------------------------------
    */

    function renderCart(){

        const container =
            document.getElementById('cart-items-container');

        const emptyState =
            document.getElementById('cart-empty-state');

        const summary =
            document.getElementById('cart-summary-container');

        container.innerHTML = '';

        /*
        |--------------------------------------------------------------------------
        | EMPTY
        |--------------------------------------------------------------------------
        */

        if(cart.length === 0){

            emptyState.style.display = 'block';

            summary.style.display = 'none';

            return;
        }

        emptyState.style.display = 'none';

        summary.style.display = 'block';

        let totalQty = 0;

        /*
        |--------------------------------------------------------------------------
        | LOOP
        |--------------------------------------------------------------------------
        */

        cart.forEach((item,index)=>{

            totalQty += item.qty;

            container.innerHTML += `

                <div
                    class="p-3 rounded-4 border mb-3"
                    style="
                        background:white;
                    "
                >

                    <div class="d-flex justify-content-between align-items-start gap-3">

                        <div style="flex:1;">

                            <div class="d-flex align-items-center gap-2 mb-2">

                                <span class="badge bg-primary rounded-pill">

                                    ${item.unit}

                                </span>

                                <span class="badge bg-light text-dark rounded-pill">

                                    ${item.group || '-'}
                                </span>

                            </div>

                            <h5 class="fw-bold mb-1">

                                ${item.name}

                            </h5>

                            <small class="text-muted d-block mb-3">

                                ID:
                                ${item.id}

                            </small>

                            <div class="d-flex align-items-center gap-2">

                                <!-- MINUS -->
                                <button
                                    class="btn btn-light rounded-circle"
                                    onclick="changeCartQty(${index}, -1)"
                                >

                                    <i class="fas fa-minus"></i>

                                </button>

                                <!-- INPUT -->
                                <input
                                    type="number"
                                    class="form-control text-center fw-bold"
                                    value="${item.qty}"
                                    min="1"
                                    onchange="setCartQty(${index}, this.value)"
                                    style="
                                        width:80px;
                                    "
                                >

                                <!-- PLUS -->
                                <button
                                    class="btn btn-light rounded-circle"
                                    onclick="changeCartQty(${index}, 1)"
                                >

                                    <i class="fas fa-plus"></i>

                                </button>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="text-end">

                            <h4 class="fw-bold text-success mb-3">

                                Rp ${Number(item.price * item.qty).toLocaleString('id-ID')}

                            </h4>

                            <button
                                class="btn btn-danger rounded-pill"
                                onclick="removeCartItem(${index})"
                            >

                                <i class="fas fa-trash me-2"></i>
                                Hapus

                            </button>

                        </div>

                    </div>

                </div>

            `;
        });

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'cart-total-items'
        ).innerText = cart.length;

        document.getElementById(
            'cart-total-qty'
        ).innerText = totalQty;
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE ITEM
    |--------------------------------------------------------------------------
    */

    function removeCartItem(index){

        cart.splice(index,1);

        saveCart();
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAR CART
    |--------------------------------------------------------------------------
    */

    function clearCart(){

        if(!confirm('Kosongkan keranjang?')){

            return;
        }

        cart = [];

        saveCart();
    }

    /*
    |--------------------------------------------------------------------------
    | CHANGE QTY
    |--------------------------------------------------------------------------
    */

    function changeCartQty(index,change){

        let qty =
            cart[index].qty + change;

        if(qty < 1){

            qty = 1;
        }

        cart[index].qty = qty;

        saveCart();
    }

    /*
    |--------------------------------------------------------------------------
    | SET QTY
    |--------------------------------------------------------------------------
    */

    function setCartQty(index,value){

        let qty =
            parseInt(value);

        if(isNaN(qty) || qty < 1){

            qty = 1;
        }

        cart[index].qty = qty;

        saveCart();
    }

    /*
    |--------------------------------------------------------------------------
    | COPY CART
    |--------------------------------------------------------------------------
    */

    function copyCartList(btn){

        if(cart.length === 0){

            return;
        }

        let text = '';

        cart.forEach(item=>{

            text +=
                `• ${item.name} (${item.unit}) x${item.qty}\n`;
        });

        navigator.clipboard.writeText(text);

        btn.innerHTML =
            '<i class="fas fa-check me-2"></i>Tersalin';

        setTimeout(()=>{

            btn.innerHTML =
                '<i class="fas fa-copy me-2"></i>Copy List';

        },2000);
    }

    /*
    |--------------------------------------------------------------------------
    | INIT
    |--------------------------------------------------------------------------
    */

    renderCart();

</script>
