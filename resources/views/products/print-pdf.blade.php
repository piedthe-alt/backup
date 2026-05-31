<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Label Barcode Harga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Libre+Barcode+39&display=swap" rel="stylesheet">
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
            padding-top: 14mm;
            padding-bottom: 14mm;
            padding-left: 1mm;
            padding-right: 1mm;
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
            grid-template-columns: repeat(5, 40mm);
            grid-template-rows: repeat(7, 35mm);
            column-gap: 2mm;
            row-gap: 4mm;
            width: 208mm;
            height: 269mm;
            box-sizing: border-box;
            position: relative;
        }

        .guide-line-v {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 0;
            border-left: 0.15mm dotted #7f8c8d;
            pointer-events: none;
            z-index: 5;
        }

        .guide-line-h {
            position: absolute;
            left: 0;
            right: 0;
            height: 0;
            border-top: 0.15mm dotted #7f8c8d;
            pointer-events: none;
            z-index: 5;
        }

        .label-box {
            width: 40mm;
            height: 35mm;
            border: 0.08mm solid #000000;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: white;
        }

        .empty-box {
            background: #ffffff;
            border: 0.08mm solid #000000;
        }

        .label-top {
            height: 20mm;
            width: 100%;
            box-sizing: border-box;
            padding: 2.2mm 1.5mm 1mm 1.5mm;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 0.08mm solid #000000;
        }

        .label-bottom {
            height: 15mm;
            width: 100%;
            box-sizing: border-box;
            padding: 2.5mm 1.5mm 1.5mm 1.5mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            overflow: hidden;
        }

        .product-name {
            font-size: 8.5pt;
            font-weight: 700;
            line-height: 1.2;
            color: #0f172a;
            text-align: center;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        .product-price {
            font-size: 13pt;
            font-weight: 800;
            color: #000000;
            white-space: nowrap;
            margin: 0;
            line-height: 1;
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
            font-size: 20pt;
            line-height: 0.7;
            margin: 0;
            padding: 0;
            color: black;
            white-space: nowrap;
            overflow: hidden;
        }

        .barcode-text {
            font-size: 1mm;
            font-weight: 800;
            margin-top: 0.2mm;
            letter-spacing: 0.5px;
            color: #000000;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        /* Printable area override */
        @media print {
            @page {
                size: A4 portrait;
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
    <div class="no-print container-fluid mb-4 py-3 bg-white border border-bottom shadow-sm rounded-3 d-flex justify-content-between align-items-center" style="max-width: 210mm; margin-top: 15px;">
        <div>
            <h5 class="fw-bold mb-1"><i class="fas fa-print me-2 text-primary"></i>Pratinjau Cetak Label Harga (A4 Portrait)</h5>
            <small class="text-muted">Ukuran label: 4.0cm x 3.5cm (Margin: Atas-Bawah 2mm, Kiri-Kanan 1mm). Total 35 label/lembar.</small>
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
                        <div class="label-top">
                            <div class="product-name">{{ $item->name }}</div>
                        </div>
                        <div class="label-bottom">
                            <div class="product-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            <div class="barcode-wrapper">
                                <div class="barcode-text">{{ $item->id }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
            </div>

            {{-- Assist lines for cutting (spanning the entire page) --}}
            <div class="guide-line-v" style="left: 42mm;"></div>
            <div class="guide-line-v" style="left: 84mm;"></div>
            <div class="guide-line-v" style="left: 126mm;"></div>
            <div class="guide-line-v" style="left: 168mm;"></div>

            <div class="guide-line-h" style="top: 51mm;"></div>
            <div class="guide-line-h" style="top: 90mm;"></div>
            <div class="guide-line-h" style="top: 129mm;"></div>
            <div class="guide-line-h" style="top: 168mm;"></div>
            <div class="guide-line-h" style="top: 207mm;"></div>
            <div class="guide-line-h" style="top: 246mm;"></div>
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
