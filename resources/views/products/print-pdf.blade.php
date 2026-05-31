<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Label Barcode Harga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Libre+Barcode+39&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Page layout */
        .page-container {
            width: 210mm;
            height: 297mm;
            box-sizing: border-box;
            padding-top: 7.5mm;
            padding-bottom: 7.5mm;
            padding-left: 1.5mm;
            padding-right: 1.5mm;
            background: white;
            margin: 15px auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
            page-break-after: always;
        }

        .label-grid {
            display: grid;
            grid-template-columns: repeat(7, 42mm);
            grid-template-rows: repeat(5, 39mm);
            gap: 0;
            width: 294mm;
            height: 195mm;
            box-sizing: border-box;
        }

        .label-box {
            width: 35mm;
            height: 40mm;
            border: 0.08mm solid #000000;
            box-sizing: border-box;
            padding-top: 2.2mm;
            padding-bottom: 2.2mm;
            padding-left: 1mm;
            padding-right: 1mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            overflow: hidden;
            background: white;
            position: relative;
        }

        .empty-box {
            background: #ffffff;
            border: 0.08mm solid #000000;
        }

        .store-name {
            font-size: 5.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 0.15mm solid #e2e8f0;
            width: 100%;
            padding-bottom: 0.4mm;
            margin-bottom: 0.5mm;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-name {
            font-size: 7pt;
            font-weight: 600;
            line-height: 1.15;
            margin-bottom: auto;
            color: #1e293b;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            width: 100%;
            height: 8.2mm;
            text-overflow: ellipsis;
        }

        .product-price {
            font-size: 11pt;
            font-weight: 700;
            margin: 0.2mm 0;
            color: #000000;
            white-space: nowrap;
        }

        .barcode-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .barcode-font {
            font-family: 'Libre Barcode 39', cursive;
            font-size: 25pt;
            line-height: 0.75;
            margin: 0;
            padding: 0;
            color: black;
        }

        .barcode-text {
            font-size: 5.5pt;
            font-weight: 600;
            margin-top: -0.5mm;
            letter-spacing: 0.8px;
            color: #475569;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        /* Printable area override */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            .page-container {
                margin: 0;
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>

    {{-- PRINT BAR HEADER (VISIBLE ON SCREEN ONLY) --}}
    <div class="no-print container-fluid mb-4 py-3 bg-white border border-bottom shadow-sm rounded-3 d-flex justify-content-between align-items-center" style="max-width: 297mm; margin-top: 15px;">
        <div>
            <h5 class="fw-bold mb-1"><i class="fas fa-print me-2 text-primary"></i>Pratinjau Cetak Label Harga (A4 Landscape)</h5>
            <small class="text-muted">Ukuran label: 4.2cm x 3.9cm (Margin: Atas-Bawah 2mm, Kiri-Kanan 1mm). Total 35 label/lembar.</small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-secondary py-2 px-3" onclick="window.close()">
                <i class="fas fa-times me-1"></i> Tutup Halaman
            </button>
            <button class="btn btn-primary py-2 px-4 shadow" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Cetak Label
            </button>
        </div>
    </div>

    {{-- THE LABELS GRID --}}
    @php
        $pages = collect($items)->chunk(35);
    @endphp

    @forelse ($pages as $pageItems)
        <div class="page-container">
            <div class="label-grid">
                @foreach ($pageItems as $item)
                    @php
                        // Format code for Code 39: alphanumeric only, wrapped in asterisks
                        $cleanCode = preg_replace('/[^a-zA-Z0-9]/', '', $item->id);
                        $barcodeValue = "*{$cleanCode}*";
                    @endphp
                    <div class="label-box">
                        <div class="store-name">{{ $businessName }}</div>
                        <div class="product-name">{{ $item->name }}</div>
                        <div class="product-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        <div class="barcode-wrapper">
                            <div class="barcode-font">{{ $barcodeValue }}</div>
                            <div class="barcode-text">{{ $item->id }}</div>
                        </div>
                    </div>
                @endforeach
                
                {{-- Fill the rest of the grid with empty cutting boxes --}}
                @for ($i = count($pageItems); $i < 35; $i++)
                    <div class="label-box empty-box"></div>
                @endfor
            </div>
        </div>
    @empty
        <div class="container mt-4 no-print text-center" style="max-width: 297mm;">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>Tidak ada data label untuk dicetak. Silakan pilih produk terlebih dahulu.
            </div>
        </div>
    @endforelse

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Automatically open printer dialog after a brief delay to ensure font loads
            setTimeout(() => {
                window.print();
            }, 800);
        });
    </script>
</body>

</html>
