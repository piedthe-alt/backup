<!-- PRODUK GRID -->
<div class="row g-4">

    @forelse ($products as $product)
        @include('products.product-card')
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
