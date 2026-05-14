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

    @include('partials.product-styles')

</head>

<body>

    <div class="container-fluid py-4">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            @include('partials.product-header')

            <!-- BODY -->
            <div class="card-body p-4">

                @include('partials.product-search')

                @include('partials.product-sorting')

                @include('partials.product-grid')

            </div>

        </div>

    </div>

    @include('partials.product-modals')

    @include('partials.cart-modal')

    <!-- JavaScript Files -->
    <script src="{{ asset('js/product-utilities.js') }}"></script>
    <script src="{{ asset('js/product-cart.js') }}"></script>
    <script src="{{ asset('js/product-scanner.js') }}"></script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
