<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #1d4ed8;
            opacity: 0.9;
        }

        .btn-success {
            background-color: #10b981;
        }

        .btn-success:hover {
            background-color: #059669;
        }

        @media print {
            .toolbar {
                display: none;
            }

            body {
                background: white;
            }

            .container {
                max-width: 100%;
                padding: 0;
            }
        }
    </style>

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }

        .store-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 10px;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .info-block {
            width: 48%;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 3px;
        }

        .info-value {
            margin-bottom: 8px;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background-color: #f0f0f0;
            border-bottom: 2px solid #2563eb;
        }

        th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            width: 40%;
            margin-left: auto;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 10px;
        }

        .summary-label {
            font-weight: bold;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #2563eb;
            color: white;
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 9px;
            color: #777;
        }

        .notes {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            font-size: 9px;
            margin-bottom: 20px;
        }

        .notes-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- TOOLBAR -->
        <div class="toolbar">
            <button class="btn" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            <button class="btn btn-success" onclick="downloadPDF()">
                <i class="fas fa-download"></i> Download PDF
            </button>
            <a href="/shop" class="btn" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left"></i> Kembali ke Toko
            </a>
        </div>

        <!-- HEADER -->
        <div class="header">
            <div class="store-name">🏪 TOKO ONLINE</div>
            <p style="font-size: 11px; color: #666;">Terima kasih telah berbelanja bersama kami</p>
        </div>

        <!-- INVOICE INFO -->
        <div class="invoice-info">

            <div class="info-block">
                <div class="invoice-title">NOTA PENJUALAN</div>
                <div class="info-value">
                    <strong>Nomor Pesanan:</strong> #{{ $order->id }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}<br>
                    <strong>Status:</strong> <span
                        style="color: #f59e0b; font-weight: bold;">{{ strtoupper($order->status) }}</span>
                </div>
            </div>

            <div class="info-block">
                <div class="info-label">Informasi Pembeli</div>
                <div class="info-value">
                    <strong>Nama:</strong> {{ $order->customer_name }}<br>
                    @if ($order->customer_phone)
                        <strong>Telepon:</strong> {{ $order->customer_phone }}<br>
                    @endif
                    @if ($order->customer_address)
                        <strong>Alamat:</strong> {{ $order->customer_address }}
                    @endif
                </div>
            </div>

        </div>

        <!-- ITEMS TABLE -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="45%">Produk</th>
                    <th width="15%" class="text-right">Harga</th>
                    <th width="15%" class="text-center">Qty</th>
                    <th width="20%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $item['product_name'] ?? $item['name'] }}</td>
                        <td class="text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY -->
        <div class="summary">
            <div class="summary-row">
                <span class="summary-label">Jumlah Item:</span>
                <span>{{ count($items) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Quantity:</span>
                <span>{{ collect($items)->sum('quantity') }}</span>
            </div>
            <div class="summary-total">
                <span>TOTAL PEMBAYARAN</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- NOTES -->
        @if ($order->notes)
            <div class="notes">
                <div class="notes-label">Catatan:</div>
                <div>{{ $order->notes }}</div>
            </div>
        @endif

        <!-- FOOTER -->
        <div class="footer">
            <p>Nota ini adalah bukti sah dari transaksi yang telah dilakukan</p>
            <p style="margin-top: 5px;">Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
        </div>

    </div>

</body>

</html>

<script>
    function downloadPDF() {
        const element = document.querySelector('.container');
        const opt = {
            margin: 10,
            filename: 'Invoice-{{ $order->id }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>
