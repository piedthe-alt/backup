<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Produk - Stock Manage</title>

    {{-- Bootstrap & Utilities --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- External Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Styles Component --}}
    @include('products.styles')

</head>

<body>

    <div class="container-fluid py-4">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- Header Component --}}
            @include('products.header', compact('productgroups'))

            <div class="card-body p-4">

                {{-- Search Form Component --}}
                @include('products.search-form', compact('productgroups'))

                {{-- Sorting Buttons Component --}}
                @include('products.sorting', compact('sort'))

                {{-- Scanner Modal Component --}}
                @include('products.scanner-modal')

                {{-- Product Grid Component --}}
                @include('products.product-grid', compact('products', 'productgroups'))

            </div>

        </div>

    </div>

    {{-- Modals Components --}}
    @include('products.detail-modal', compact('products'))

    @include('products.cart-modal')

    {{-- Scripts Component --}}
    @include('products.scripts')

    {{-- Bootstrap JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
