<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ProductReturnController;
use App\Http\Controllers\PesananShopeeController;

Route::get('/sales-detail/{tanggal}', function ($tanggal) {

    /*
    |--------------------------------------------------------------------------
    | AMBIL SEMUA NOTA
    |--------------------------------------------------------------------------
    */

    $notas = DB::table('salesdetail')

        ->join('sales', 'salesdetail.salesid', '=', 'sales.salesidref')

        ->select(

            'salesdetail.salesid',

            'sales.salestime as transdate',

            DB::raw('SUM(salesdetail.netamount) as total_belanja'),

            DB::raw('SUM(salesdetail.cogs) as total_hpp'),

            DB::raw('SUM(salesdetail.netamount - salesdetail.cogs) as total_margin'),

            DB::raw('SUM(salesdetail.salesqty) as total_qty'),

            DB::raw('
                (
                    (
                        SUM(salesdetail.netamount - salesdetail.cogs)
                        / NULLIF(SUM(salesdetail.netamount),0)
                    ) * 100
                )
                as margin_percent
            ')

        )

        ->whereDate(
            'salesdetail.updatetimestamp',
            $tanggal
        )

        ->groupBy('salesdetail.salesid', 'sales.salestime')

        ->orderByRaw("
            CAST(
                SUBSTRING_INDEX(salesdetail.salesid, '-', -1)
                AS UNSIGNED
            ) ASC
        ")

        ->get();

    /*
    |--------------------------------------------------------------------------
    | AMBIL ITEM PER NOTA
    |--------------------------------------------------------------------------
    */

    foreach ($notas as $nota) {

        $nota->items = DB::table('salesdetail')

            ->leftJoin(
                'product',
                'salesdetail.productid',
                '=',
                'product.id'
            )

            ->select(

                'salesdetail.productid',

                'product.name as product_name',

                'salesdetail.salesqty',

                'salesdetail.price',

                'salesdetail.grossamount',

                'salesdetail.netamount',

                'salesdetail.cogs',

                DB::raw('
                    (
                        salesdetail.netamount
                        - salesdetail.cogs
                    )
                    as margin
                '),

                DB::raw('
                    (
                        (
                            salesdetail.netamount
                            - salesdetail.cogs
                        )
                        / NULLIF(salesdetail.netamount,0)
                    ) * 100
                    as margin_percent
                ')

            )

            ->where(
                'salesdetail.salesid',
                $nota->salesid
            )

            ->get();
    }

    return view(

        'sales-detail',

        compact(
            'tanggal',
            'notas'
        )

    );
});

Route::get('/sales-hour-analysis', function (Request $request) {

    $startDate = $request->start_date ?? now()->subDays(30)->format('Y-m-d');
    $endDate = $request->end_date ?? now()->format('Y-m-d');

    /*
    |--------------------------------------------------------------------------
    | ANALISIS JAM RAMAI TOKO - PENJUALAN
    |--------------------------------------------------------------------------
    | omzet_kotor  = SUM(netamount)
    | margin_kotor = SUM(netamount - cogs)
    |--------------------------------------------------------------------------
    */

    $hourlyData = DB::table('salesdetail')

        ->join('sales', 'salesdetail.salesid', '=', 'sales.salesidref')

        ->select(

            DB::raw('HOUR(sales.salestime) as hour'),

            DB::raw('DAYNAME(sales.salestime) as day_name'),

            DB::raw('DATE_FORMAT(sales.salestime, "%H:00") as hour_label'),

            DB::raw('COUNT(DISTINCT sales.salesidref) as transaction_count'),

            DB::raw('SUM(salesdetail.salesqty) as total_qty'),

            DB::raw('SUM(salesdetail.netamount) as omzet_kotor'),

            DB::raw('SUM(salesdetail.cogs) as total_cogs'),

            DB::raw('SUM(salesdetail.netamount - salesdetail.cogs) as margin_kotor')

        )

        ->whereDate('sales.salestime', '>=', $startDate)

        ->whereDate('sales.salestime', '<=', $endDate)

        ->groupByRaw('HOUR(sales.salestime), DAYNAME(sales.salestime), DATE_FORMAT(sales.salestime, "%H:00")')

        ->orderBy('hour', 'ASC')

        ->get();

    /*
    |--------------------------------------------------------------------------
    | RETUR PER JAM
    |--------------------------------------------------------------------------
    | retur = harga jual sekarang * invin
    | margin_retur = (harga jual - modal) * qty retur
    |--------------------------------------------------------------------------
    */

    $hourlyRetur = DB::table('inventory')

        ->leftJoin('product', 'inventory.productid', '=', 'product.id')

        ->select(

            DB::raw('HOUR(inventory.updatetimestamp) as hour'),

            DB::raw('SUM(product.salesprice1 * inventory.invin) as total_return'),

            DB::raw('SUM((product.salesprice1 - inventory.invvalue) * inventory.invin) as margin_return')

        )

        ->where('inventory.transid', 'like', 'I/SR-%')

        ->whereDate('inventory.updatetimestamp', '>=', $startDate)

        ->whereDate('inventory.updatetimestamp', '<=', $endDate)

        ->groupByRaw('HOUR(inventory.updatetimestamp)')

        ->get()

        ->keyBy('hour');

    /*
    |--------------------------------------------------------------------------
    | GABUNGKAN PENJUALAN + RETUR
    |--------------------------------------------------------------------------
    */

    $hourlyAnalysis = $hourlyData->map(function ($item) use ($hourlyRetur) {

        $retur = 0;
        $marginRetur = 0;

        if (isset($hourlyRetur[$item->hour])) {

            $retur = $hourlyRetur[$item->hour]->total_return;

            $marginRetur = $hourlyRetur[$item->hour]->margin_return;
        }

        // Total dengan retur
        $item->total_amount = $item->omzet_kotor - $retur;

        $item->total_margin = $item->margin_kotor - $marginRetur;

        $item->margin_percent = $item->total_amount > 0
            ? round(($item->total_margin / $item->total_amount) * 100, 2)
            : 0;

        return $item;
    });

    /*
    |--------------------------------------------------------------------------
    | SUMMARY STATISTIK
    |--------------------------------------------------------------------------
    */

    $totalTransactions = $hourlyAnalysis->sum('transaction_count');

    $totalAmount = $hourlyAnalysis->sum('total_amount');

    $totalMargin = $hourlyAnalysis->sum('total_margin');

    $peakHour = $hourlyAnalysis->sortByDesc('total_amount')->first();

    return view('sales-hour-analysis', compact(

        'hourlyAnalysis',

        'startDate',

        'endDate',

        'totalTransactions',

        'totalAmount',

        'totalMargin',

        'peakHour'

    ));
});

Route::get('/api/pesanan-shopee', [PesananShopeeController::class, 'apiList']);

Route::get('/api/get-returns-by-group', function (Request $request) {

    $groupName = trim($request->group);

    $masterDb = config('database.connections.mysql.database');

    $returns = DB::connection('mysql_app')

        ->table('product_returns')

        ->leftJoin(
            DB::raw("{$masterDb}.product as product"),
            'product_returns.product_id',
            '=',
            'product.id'
        )

        ->leftJoin(
            DB::raw("{$masterDb}.productgroup as productgroup"),
            'product.productgroup',
            '=',
            'productgroup.id'
        )

        ->select(
            'product_returns.*',
            'product.name as product_name',
            'productgroup.name as group_name'
        )

        ->whereRaw(
            'LOWER(TRIM(productgroup.name)) = LOWER(?)',
            [trim($groupName)]
        )

        ->whereRaw(
            'LOWER(TRIM(product_returns.status)) = ?',
            ['BELUM_DIAMBIL']
        )

        ->get();
    return response()->json($returns);
});

Route::get('/test-db-app', function () {
    return DB::connection('mysql_app')->select("SELECT DATABASE() as db");
});

/**
 * ============================================================================
 * RETURN PRODUK (RETUR BARANG) ROUTE
 * ============================================================================
 * Controller: App\Http\Controllers\ProductReturnController
 * View: resources/views/return.blade.php
 *
 * 📝 DOKUMENTASI:
 * Edit view: resources/views/return.blade.php
 * Edit form fields: Lihat ProductReturnController@index
 *
 * Features:
 * - List semua product returns
 * - Create return baru
 * - Update return status
 * ============================================================================
 */
Route::post('/returns/store', [ProductReturnController::class, 'store']);
Route::get('/return', [ProductReturnController::class, 'index']);
Route::post('/returns/taken/{id}', function ($id) {

    DB::connection('mysql_app')

        ->table('product_returns')

        ->where('id', $id)

        ->update([

            'status' => 'SUDAH_DIAMBIL',

            'updated_at' => now()

        ]);

    return redirect('/return');
});

// API untuk mendapatkan returan berdasarkan product name
Route::get('/api/get-returns', function (Request $request) {
    $productName = $request->product_name;

    if (!$productName) {
        return response()->json(['returns' => null]);
    }

    try {
        // Step 1: Cari product dari master DB
        $product = DB::connection('mysql')->table('product')
            ->where('name', 'like', "%{$productName}%")
            ->first();

        if (!$product) {
            return response()->json(['returns' => null]);
        }

        // Step 2: Cari returns dari app DB menggunakan product_id
        $return = DB::connection('mysql_app')->table('product_returns')
            ->where('product_id', $product->id)
            ->first();

        // Step 3: Tambah product name ke return object
        if ($return) {
            $return->product_name = $product->name;
        }

        return response()->json(['returns' => $return]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// API untuk mendapatkan inventory transaction history
Route::get('/api/product-inventory-history', function (Request $request) {
    $productId = $request->product_id;

    if (!$productId) {
        return response()->json(['error' => 'Product ID required'], 400);
    }

    try {
        $transactions = DB::connection('mysql')->table('inventory')
            ->leftJoin('product', 'inventory.productid', '=', 'product.id')
            ->leftJoin('purchasedetail', function($join) {
                $join->on('inventory.transid', '=', 'purchasedetail.purchaseid')
                     ->on('inventory.productid', '=', 'purchasedetail.productid');
            })
            ->where('inventory.productid', $productId)
            ->orderBy('inventory.transdate', 'DESC')
            ->orderBy('inventory.id', 'DESC')
            ->select(
                'inventory.*',
                'product.costprice',
                'product.salesprice1',
                'purchasedetail.price as purchase_price'
            )
            ->get();

        // Kategorisasi transaksi dan hitung ringkasan
        $summary = [
            'pembelian' => 0,      // I/PC
            'penjualan' => 0,      // I/SL
            'retur_penjualan' => 0, // I/SR
            'retur_pembelian' => 0, // I/PR
            'adjustment_masuk' => 0, // I/IM dengan invin
            'adjustment_keluar' => 0  // I/IM dengan invout
        ];

        $categorized = [];
        foreach ($transactions as $trans) {
            $type = null;
            $category = null;
            $direction = null;
            $quantity = 0;
            $icon = null;
            $color = null;
            $unitPrice = 0;
            $totalPrice = 0;

            if (strpos($trans->transid, 'I/PC') === 0) {
                // Pembelian - gunakan harga dari purchasedetail
                $type = 'Pembelian';
                $category = 'pembelian';
                $direction = 'in';
                $quantity = $trans->invin;
                $icon = 'fa-arrow-down';
                $color = 'success';
                $unitPrice = $trans->purchase_price ?? $trans->costprice ?? 0;
                $totalPrice = $quantity * $unitPrice;
                $summary['pembelian'] += $quantity;
            } elseif (strpos($trans->transid, 'I/SL') === 0) {
                // Penjualan
                $type = 'Penjualan';
                $category = 'penjualan';
                $direction = 'out';
                $quantity = $trans->invout;
                $icon = 'fa-arrow-up';
                $color = 'warning';
                $unitPrice = $trans->salesprice1 ?? 0;
                $totalPrice = $quantity * $unitPrice;
                $summary['penjualan'] += $quantity;
            } elseif (strpos($trans->transid, 'I/SR') === 0) {
                // Retur Penjualan (barang masuk)
                $type = 'Retur Penjualan';
                $category = 'retur_penjualan';
                $direction = 'in';
                $quantity = $trans->invin;
                $icon = 'fa-undo';
                $color = 'info';
                $unitPrice = $trans->salesprice1 ?? 0;
                $totalPrice = $quantity * $unitPrice;
                $summary['retur_penjualan'] += $quantity;
            } elseif (strpos($trans->transid, 'I/PR') === 0) {
                // Retur Pembelian (barang keluar) - gunakan harga dari purchasedetail
                $type = 'Retur Pembelian';
                $category = 'retur_pembelian';
                $direction = 'out';
                $quantity = $trans->invout;
                $icon = 'fa-share';
                $color = 'danger';
                $unitPrice = $trans->purchase_price ?? $trans->costprice ?? 0;
                $totalPrice = $quantity * $unitPrice;
                $summary['retur_pembelian'] += $quantity;
            } elseif (strpos($trans->transid, 'I/IM') === 0) {
                // Adjustment
                $type = 'Adjustment';
                if ($trans->invin > 0) {
                    $direction = 'in';
                    $quantity = $trans->invin;
                    $category = 'adjustment_masuk';
                    $summary['adjustment_masuk'] += $quantity;
                } else {
                    $direction = 'out';
                    $quantity = $trans->invout;
                    $category = 'adjustment_keluar';
                    $summary['adjustment_keluar'] += $quantity;
                }
                $icon = 'fa-sliders-h';
                $color = 'secondary';
                $unitPrice = $trans->costprice ?? 0;
                $totalPrice = $quantity * $unitPrice;
            }

            if ($type) {
                $categorized[] = [
                    'id' => $trans->id,
                    'transid' => $trans->transid,
                    'type' => $type,
                    'category' => $category,
                    'direction' => $direction,
                    'quantity' => $quantity,
                    'unitPrice' => $unitPrice,
                    'totalPrice' => $totalPrice,
                    'date' => $trans->transdate,
                    'memo' => $trans->memo,
                    'icon' => $icon,
                    'color' => $color,
                    'reference' => $trans->reference,
                    'invin' => $trans->invin,
                    'invout' => $trans->invout
                ];
            }
        }

        return response()->json([
            'transactions' => $categorized,
            'summary' => $summary,
            'total_transactions' => count($categorized)
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Route::get('/list-models', function () {

//     $response = Http::get(
//         'https://generativelanguage.googleapis.com/v1/models?key=AIzaSyCQ3r_3AVc158RGYX2lQ5nT5DfbB4xvbIk'
//     );

//     return $response->json();
// });

Route::get('/test-gemini', function () {

    $response = Http::post(

        'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=AIzaSyCQ3r_3AVc158RGYX2lQ5nT5DfbB4xvbIk',

        [

            'contents' => [

                [

                    'parts' => [

                        [
                            'text' => 'Halo Gemini'
                        ]

                    ]

                ]

            ]

        ]

    );

    return $response->json();
});


Route::get('/ai-analysis', function (Request $request) {

    /*
    |--------------------------------------------------------------------------
    | PARAMETER
    |--------------------------------------------------------------------------
    */

    $groupName = $request->group ?? 'ROKOK';

    $days = $request->days ?? 360;

    /*
    |--------------------------------------------------------------------------
    | TANGGAL
    |--------------------------------------------------------------------------
    */

    $startDate = now()->subDays($days)->startOfDay();

    $endDate = now()->endOfDay();

    /*
    |--------------------------------------------------------------------------
    | AMBIL PRODUK
    |--------------------------------------------------------------------------
    */

    $products = DB::table('product')

        ->leftJoin(
            'productgroup',
            'product.productgroup',
            '=',
            'productgroup.id'
        )

        ->select(
            'product.id',
            'product.name'
        )

        ->where('product.isactive', 1)

        ->where('productgroup.name', $groupName)

        ->limit(100)

        ->get();

    /*
    |--------------------------------------------------------------------------
    | ARRAY FINAL
    |--------------------------------------------------------------------------
    */

    $final = [];

    /*
    |--------------------------------------------------------------------------
    | LOOP PRODUK
    |--------------------------------------------------------------------------
    */

    foreach ($products as $product) {

        /*
        |--------------------------------------------------------------------------
        | STOCK SEKARANG
        |--------------------------------------------------------------------------
        */

        $stock = DB::table('inventory')

            ->where('productid', $product->id)

            ->selectRaw('
                COALESCE(SUM(invin - invout),0)
                as stock
            ')

            ->value('stock');

        /*
        |--------------------------------------------------------------------------
        | TOTAL BARANG MASUK
        |--------------------------------------------------------------------------
        */

        $incoming = DB::table('inventory')

            ->where('productid', $product->id)

            ->whereBetween(
                'updatetimestamp',
                [$startDate, $endDate]
            )

            ->sum('invin');

        /*
        |--------------------------------------------------------------------------
        | PENJUALAN
        |--------------------------------------------------------------------------
        */

        $sales = DB::table('salesdetail')

            ->where('productid', $product->id)

            ->whereBetween(
                'updatetimestamp',
                [$startDate, $endDate]
            );

        $totalKeluar = (clone $sales)->sum('salesqty');

        /*
        |--------------------------------------------------------------------------
        | SKIP JIKA TIDAK ADA PENJUALAN
        |--------------------------------------------------------------------------
        */

        if ($totalKeluar <= 0) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVE SALES DAYS
        |--------------------------------------------------------------------------
        */

        $activeSalesDays = (clone $sales)

            ->selectRaw('DATE(updatetimestamp) as tanggal')

            ->groupBy(DB::raw('DATE(updatetimestamp)'))

            ->get()

            ->count();

        /*
        |--------------------------------------------------------------------------
        | AVG DAILY SALES
        |--------------------------------------------------------------------------
        */

        $avgDailySales = $totalKeluar / $days;

        /*
        |--------------------------------------------------------------------------
        | ESTIMASI HARI STOK HABIS
        |--------------------------------------------------------------------------
        */

        $estimatedDaysLeft =

            $avgDailySales > 0

            ?

            $stock / $avgDailySales

            :

            999;

        /*
        |--------------------------------------------------------------------------
        | TREND
        |--------------------------------------------------------------------------
        */

        $middleDate = now()->subDays($days / 2);

        $oldSales = DB::table('salesdetail')

            ->where('productid', $product->id)

            ->whereBetween(
                'updatetimestamp',
                [$startDate, $middleDate]
            )

            ->sum('salesqty');

        $newSales = DB::table('salesdetail')

            ->where('productid', $product->id)

            ->whereBetween(
                'updatetimestamp',
                [$middleDate, $endDate]
            )

            ->sum('salesqty');

        $trendPercent = 0;

        if ($oldSales > 0) {

            $trendPercent =

                (($newSales - $oldSales) / $oldSales) * 100;
        }

        $trend =

            $trendPercent > 5

            ?

            'NAIK'

            : (

                $trendPercent < -5

                ?

                'TURUN'

                :

                'STABIL'

            );

        /*
        |--------------------------------------------------------------------------
        | SELL THROUGH RATE
        |--------------------------------------------------------------------------
        */

        $sellThroughRate =

            $incoming > 0

            ?

            ($totalKeluar / $incoming) * 100

            :

            0;

        /*
        |--------------------------------------------------------------------------
        | STOCK STATUS
        |--------------------------------------------------------------------------
        */

        if ($estimatedDaysLeft <= 3) {

            $stockStatus = 'KRITIS';

            $reorderRecommendation = 'SEGERA ORDER';
        } elseif ($estimatedDaysLeft <= 7) {

            $stockStatus = 'MENIPIS';

            $reorderRecommendation = 'ORDER NORMAL';
        } else {

            $stockStatus = 'AMAN';

            $reorderRecommendation = 'TUNDA ORDER';
        }

        /*
        |--------------------------------------------------------------------------
        | MASUKKAN JSON
        |--------------------------------------------------------------------------
        */

        $final[] = [

            'nama' => $product->name,

            'periode_analisis_hari' => $days,

            'stock_sekarang' => round($stock),

            'total_barang_keluar' => round($totalKeluar),

            'total_barang_masuk' => round($incoming),

            'avg_daily_sales' => round($avgDailySales, 2),

            'estimated_days_left' => round($estimatedDaysLeft, 2),

            'active_sales_days' => $activeSalesDays,

            'trend_percent' => round($trendPercent, 2),

            'trend' => $trend,

            'sell_through_rate' => round($sellThroughRate, 2),

            'stock_status' => $stockStatus,

            'reorder_recommendation' => $reorderRecommendation

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | JSON STRING
    |--------------------------------------------------------------------------
    */

    $jsonData = json_encode(
        $final,
        JSON_UNESCAPED_UNICODE
    );

    /*
    |--------------------------------------------------------------------------
    | PROMPT GEMINI
    |--------------------------------------------------------------------------
    */

    $prompt = "

Anda adalah AI analis inventory toko.

Jawaban HARUS:
- singkat
- padat
- tanpa pembukaan
- tanpa kesimpulan panjang
- tanpa bahasa dramatis
- langsung ke poin
- format numbering

FORMAT WAJIB:

1. NAMA PRODUK
   - Stock sekarang:
   - Estimasi habis:
   - Trend:
   - Status:
   - Rekomendasi:

2. NAMA PRODUK
   - dst

Fokus hanya pada:
- stok kritis
- produk paling laris
- trend naik tinggi
- slow moving
- overstock

Jangan tampilkan produk normal.

Gunakan bahasa sederhana.

DATA:
" . $jsonData;

    /*
    |--------------------------------------------------------------------------
    | REQUEST GEMINI
    |--------------------------------------------------------------------------
    */

    $response = Http::timeout(300)

        ->withHeaders([
            'Content-Type' => 'application/json',
        ])

        ->post(

            'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=AIzaSyCQ3r_3AVc158RGYX2lQ5nT5DfbB4xvbIk',

            [

                'contents' => [

                    [

                        'parts' => [

                            [
                                'text' => $prompt
                            ]

                        ]

                    ]

                ]

            ]

        );

    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    if (!$response->successful()) {

        return response()->json([

            'error' => true,

            'status' => $response->status(),

            'response' => $response->body()

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HASIL GEMINI
    |--------------------------------------------------------------------------
    */

    $result =

        $response['candidates'][0]['content']['parts'][0]['text']

        ??

        'Tidak ada respon AI';

    /*
    |--------------------------------------------------------------------------
    | RETURN
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' => true,

        'group' => $groupName,

        'days' => $days,

        'total_products' => count($final),

        'analysis' => $result

    ]);
});

Route::get('/import-db', function () {

    // ⚠️ AMBIL FILE SQL
    $path = resource_path('views/backup/BackUp SJ MART.sql');

    if (!File::exists($path)) {
        return "File backup tidak ditemukan!";
    }

    $sql = File::get($path);

    // ⚠️ MATIKAN FOREIGN KEY CHECK
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    // ⚠️ DROP SEMUA TABLE
    $tables = DB::select('SHOW TABLES');
    $dbName = env('DB_DATABASE');
    $key = "Tables_in_$dbName";

    foreach ($tables as $table) {
        $tableName = $table->$key;
        DB::statement("DROP TABLE `$tableName`");
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    // ⚠️ IMPORT ULANG SQL
    DB::unprepared($sql);

    return redirect('/')->with('success', 'Database berhasil direset & diimport ulang');
});

Route::get('/ai-dashboard', function () {

    $groups = DB::table('productgroup')

        ->orderBy('name')

        ->get();

    return view(

        'ai-dashboard',

        compact('groups')

    );
});

Route::get('/sales-chart', function (Request $request) {

    $start = $request->start_date;
    $end   = $request->end_date;

    /*
    |--------------------------------------------------------------------------
    | PENJUALAN DARI SALESDETAIL
    |--------------------------------------------------------------------------
    | omzet_kotor  = SUM(netamount)
    | margin_kotor = SUM(netamount - cogs)
    |--------------------------------------------------------------------------
    */

    $salesQuery = DB::table('salesdetail')

        ->select(

            DB::raw('DATE(updatetimestamp) as tanggal'),

            DB::raw('
                SUM(netamount)
                as omzet_kotor
            '),

            DB::raw('
                SUM(netamount - cogs)
                as margin_kotor
            ')

        )

        ->groupBy(
            DB::raw('DATE(updatetimestamp)')
        )

        ->orderBy('tanggal', 'ASC');

    /*
    |--------------------------------------------------------------------------
    | FILTER TANGGAL
    |--------------------------------------------------------------------------
    */

    if ($start && $end) {

        $salesQuery->whereBetween(

            DB::raw('DATE(updatetimestamp)'),

            [$start, $end]

        );
    }

    $salesData = $salesQuery->get();

    /*
    |--------------------------------------------------------------------------
    | RETUR
    |--------------------------------------------------------------------------
    | Menggunakan inventory
    | I/SR-
    |--------------------------------------------------------------------------
    |
    | retur = harga jual sekarang * invin
    |
    | margin_retur =
    | (harga jual - modal) * qty retur
    |--------------------------------------------------------------------------
    */

    $returnQuery = DB::table('inventory')

        ->leftJoin(
            'product',
            'inventory.productid',
            '=',
            'product.id'
        )

        ->select(

            DB::raw('DATE(inventory.updatetimestamp) as tanggal'),

            /*
            |--------------------------------------------------------------------------
            | TOTAL RETUR
            |--------------------------------------------------------------------------
            */

            DB::raw('
                SUM(
                    product.salesprice1 * inventory.invin
                )
                as total_return
            '),

            /*
            |--------------------------------------------------------------------------
            | MARGIN RETUR
            |--------------------------------------------------------------------------
            */

            DB::raw('
                SUM(
                    (
                        product.salesprice1
                        - inventory.invvalue
                    )
                    * inventory.invin
                )
                as margin_return
            ')

        )

        ->where(
            'inventory.transid',
            'like',
            'I/SR-%'
        )

        ->groupBy(
            DB::raw('DATE(inventory.updatetimestamp)')
        );

    /*
    |--------------------------------------------------------------------------
    | FILTER TANGGAL RETUR
    |--------------------------------------------------------------------------
    */

    if ($start && $end) {

        $returnQuery->whereBetween(

            DB::raw('DATE(inventory.updatetimestamp)'),

            [$start, $end]

        );
    }

    $returnData = $returnQuery->get()->keyBy('tanggal');

    /*
    |--------------------------------------------------------------------------
    | GABUNGKAN
    |--------------------------------------------------------------------------
    */

    $finalSales = $salesData->map(function ($item) use ($returnData) {

        $retur = 0;
        $marginRetur = 0;

        /*
        |--------------------------------------------------------------------------
        | AMBIL RETUR
        |--------------------------------------------------------------------------
        */

        if (isset($returnData[$item->tanggal])) {

            $retur = $returnData[$item->tanggal]->total_return;

            $marginRetur = $returnData[$item->tanggal]->margin_return;
        }

        /*
        |--------------------------------------------------------------------------
        | RETUR
        |--------------------------------------------------------------------------
        */

        $item->retur = $retur;

        /*
        |--------------------------------------------------------------------------
        | OMZET BERSIH
        |--------------------------------------------------------------------------
        */

        $item->omzet_bersih =
            $item->omzet_kotor
            - $retur;

        /*
        |--------------------------------------------------------------------------
        | MARGIN BERSIH
        |--------------------------------------------------------------------------
        */

        $item->margin_bersih =
            $item->margin_kotor
            - $marginRetur;

        return $item;
    });

    return view(

        'sales-chart',

        [

            'sales' => $finalSales

        ]

    );
});

/**
 * ============================================================================
 * MANAJEMEN STOCK PRODUK ROUTE
 * ============================================================================
 * View: resources/views/product.blade.php
 * Status: Modular (split into reusable components)
 *
 * 📝 DOCUMENTATION - Tempat Edit Setiap Bagian:
 * ============================================================================
 *
 * 🎨 STYLING / CSS
 *    File: resources/views/products/styles.blade.php
 *    Deskripsi: Semua CSS untuk product page, including variables, media queries
 *    Edit untuk: Mengubah warna, ukuran font, layout, animations
 *
 * 📌 HEADER (Judul + Tombol Navigasi)
 *    File: resources/views/products/header.blade.php
 *    Deskripsi: "Manajemen Stock Produk" title + 5 action buttons (AI, Retur, Pesanan, Import, Cart)
 *    Edit untuk: Menambah/mengubah tombol header, mengubah title
 *
 * 🔍 SEARCH & FILTER FORM
 *    File: resources/views/products/search-form.blade.php
 *    Deskripsi: Search input, scanner button, group dropdown dengan JavaScript
 *    Edit untuk: Mengubah search placeholder, menambah filter, dropdown behavior
 *
 * 📊 SORTING BUTTONS
 *    File: resources/views/products/sorting-buttons.blade.php
 *    Deskripsi: 6 sort options (Stock Terendah, Tertinggi, Laris, etc)
 *    Edit untuk: Menambah sort options, mengubah sort labels/icons
 *
 * 📱 BARCODE/QR SCANNER MODAL
 *    File: resources/views/products/scanner-modal.blade.php
 *    Deskripsi: Canvas-based barcode/QR scanner UI
 *    Edit untuk: Mengubah scanner styling, status messages
 *
 * 🛍️ PRODUCT CARD (Reusable)
 *    File: resources/views/products/product-card.blade.php
 *    Deskripsi: Single product card (name, price, stock, qty selector, add to cart buttons)
 *    Edit untuk: Mengubah card layout, menambah info items, styling
 *    Note: Digunakan di product-grid melalui @include
 *
 * 📦 PRODUCT GRID & PAGINATION
 *    File: resources/views/products/product-grid.blade.php
 *    Deskripsi: @forelse loop untuk render product cards dengan pagination
 *    Edit untuk: Mengubah grid columns, pagination, empty state
 *
 * 📋 PRODUCT DETAIL MODAL (dengan tabs)
 *    File: resources/views/products/product-detail-modal.blade.php
 *    Deskripsi: Detail modal dengan 2 tabs: (1) Detail Produk, (2) Riwayat Stok Masuk
 *              Includes pricing strata table dengan isset validation
 *    Edit untuk: Menambah/menghapus kolom detail, mengubah tab content, pricing table
 *    Note: Modal ID menggunakan $product->id (unik per produk, tidak berdasar index)
 *
 * 🛒 SHOPPING CART MODAL
 *    File: resources/views/products/cart-modal.blade.php
 *    Deskripsi: Cart display, cart items list, summary, copy/clear buttons
 *    Edit untuk: Mengubah cart layout, buttons, styling
 *
 * ⚙️ JAVASCRIPT FUNCTIONS
 *    File: resources/views/product.blade.php (bagian <script> di akhir)
 *    Deskripsi: 18+ functions untuk cart management, scanner, modals
 *    Functions: addToCart, removeFromCart, clearCart, copyCartList,
 *              startScanner, stopScanner, searchProductByBarcode, showProductModal,
 *              copyProductName, validateQty, increaseQty, decreaseQty, etc
 *    Edit untuk: Mengubah cart logic, scanner behavior, modal interactions
 *
 * ============================================================================
 * TROUBLESHOOTING:
 * ============================================================================
 *
 * ❌ Modal flickering saat klik product?
 *    → Sudah diperbaiki! Modal ID sekarang menggunakan $product->id (unique)
 *    → Bukan lagi $loop->index (berubah setiap halaman pagination)
 *
 * ❌ Scanner tidak bekerja?
 *    → Check loadInventoryHistoryTab() function di bottom product.blade.php
 *    → Pastikan librari Quagga dan html5-qrcode sudah ter-load
 *
 * ❌ Cart tidak menyimpan data?
 *    → Check localStorage implementation di JavaScript section
 *    → Pastikan browser allow localStorage
 *
 * ============================================================================
 */
Route::get('/', function (Request $request) {

    $keyword = $request->keyword;
    $productgroup = $request->productgroup;
    $sort = $request->sort ?? 'nama_asc'; // Default sorting

    $query = DB::table('product')

        ->leftJoin(
            'productgroup',
            'product.productgroup',
            '=',
            'productgroup.id'
        )

        ->leftJoin(
            'supplier',
            'product.supplier',
            '=',
            'supplier.id'
        )

        ->leftJoin(
            'inventory',
            'product.id',
            '=',
            'inventory.productid'
        )

        ->select(

            'product.id',
            'product.name',

            'product.costprice',
            'product.salesprice1',

            'product.salesdiscqty1',
            'product.salesdiscprice1',

            'product.salesdiscqty2',
            'product.salesdiscprice2',

            'product.salesdiscqty3',
            'product.salesdiscprice3',

            'productgroup.name as productgroup_name',
            'supplier.name as supplier_name',

            DB::raw('COALESCE(SUM(inventory.invin - inventory.invout),0) as stock'),

            DB::raw('COALESCE(SUM(inventory.invin),0) as total_masuk'),

            DB::raw('COALESCE(SUM(inventory.invout),0) as total_keluar')

        )

        ->where('product.isactive', 1)

        ->groupBy(

            'product.id',
            'product.name',

            'product.costprice',
            'product.salesprice1',

            'product.salesdiscqty1',
            'product.salesdiscprice1',

            'product.salesdiscqty2',
            'product.salesdiscprice2',

            'product.salesdiscqty3',
            'product.salesdiscprice3',

            'productgroup.name',
            'supplier.name'

        );

    // Apply sorting based on selected option
    switch ($sort) {
        case 'stock_terendah':
            $query->orderBy('stock', 'ASC');
            break;
        case 'stock_tertinggi':
            $query->orderBy('stock', 'DESC');
            break;
        case 'paling_laris':
            $query->orderByRaw('COALESCE(SUM(inventory.invout),0) DESC');
            break;
        case 'gak_jalan':
            $query->orderByRaw('COALESCE(SUM(inventory.invout),0) ASC');
            break;
        case 'nama_asc':
            $query->orderBy('product.name', 'ASC');
            break;
        case 'nama_desc':
            $query->orderBy('product.name', 'DESC');
            break;
        default:
            $query->orderBy('product.name', 'ASC');
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($keyword) {

        $query->where(function ($q) use ($keyword) {

            $q->where('product.name', 'like', "%{$keyword}%")

                ->orWhere('product.id', 'like', "%{$keyword}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER GROUP
    |--------------------------------------------------------------------------
    */

    if ($productgroup) {

        $query->where(
            'product.productgroup',
            $productgroup
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    $products = $query

        ->paginate(100)

        ->withQueryString();

    $productgroups = DB::table('productgroup')

        ->orderBy('name')

        ->get();

    return view(

        'product',

        compact(
            'products',
            'productgroups',
            'sort'
        )
    );
});

/**
 * ============================================================================
 * PESANAN SHOPEE ROUTES
 * ============================================================================
 * Controller: App\Http\Controllers\PesananShopeeController
 * View: resources/views/pesanan-shopee.blade.php
 *
 * 📝 DOKUMENTASI:
 * Edit view: resources/views/pesanan-shopee.blade.php
 * Edit form fields: Lihat PesananShopeeController
 * Edit models: App\Models\PesananShopee
 *
 * Routes:
 * - GET  /pesanan-shopee           → List semua pesanan
 * - POST /pesanan-shopee/store     → Create pesanan baru
 * - GET  /pesanan-shopee/detail/{id} → View detail pesanan
 * - POST /pesanan-shopee/update-status/{id} → Update status pesanan
 * - GET  /api/products             → API untuk ambil product list
 *
 * Features:
 * - List pesanan dari Shopee
 * - Create/edit pesanan
 * - Update status pesanan (pending, processing, shipped, completed)
 * - Integration dengan product inventory
 * ============================================================================
 */
Route::get('/pesanan-shopee', [PesananShopeeController::class, 'index']);
Route::post('/pesanan-shopee/store', [PesananShopeeController::class, 'store']);
Route::get('/pesanan-shopee/detail/{id}', [PesananShopeeController::class, 'show']);
Route::post('/pesanan-shopee/update-status/{id}', [PesananShopeeController::class, 'updateStatus']);
Route::get('/api/products', [PesananShopeeController::class, 'getProducts']);

/*
|--------------------------------------------------------------------------
| SHOPPING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/shop', function (Request $request) {

    $keyword = $request->keyword;

    $query = DB::table('product')

        ->leftJoin(
            'inventory',
            'product.id',
            '=',
            'inventory.productid'
        )

        ->select(

            'product.id',
            'product.name',
            'product.salesprice1',

            DB::raw('
                COALESCE(
                    SUM(inventory.invin - inventory.invout),
                    0
                ) as stock
            ')

        )

        ->where('product.isactive', 1);

    /*
    |------------------------------------------------------------------
    | SEARCH GLOBAL
    |------------------------------------------------------------------
    */

    if ($keyword) {

        $query->where(function ($q) use ($keyword) {

            $q->where('product.name', 'like', "%{$keyword}%")
                ->orWhere('product.id', 'like', "%{$keyword}%");
        });
    }

    $products = $query

        ->groupBy(
            'product.id',
            'product.name',
            'product.salesprice1'
        )

        ->orderBy('product.name')

        ->paginate(50)

        ->withQueryString();

    return view(
        'shop',
        compact('products')
    );
});
/*
|--------------------------------------------------------------------------
| CREATE ORDER FROM SHOP
|--------------------------------------------------------------------------
*/

Route::post('/shop/order', function (Request $request) {

    try {

        $data = $request->validate([
            'items' => 'required|json',
            'customer_name' => 'required|string',
            'customer_phone' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $items = json_decode($data['items'], true);

        if (empty($items)) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE TO mysql_app (mysql_app)
        |--------------------------------------------------------------------------
        */

        $productIds = [];
        $productQtys = [];
        $totalAmount = 0;
        $orderDetails = [];

        foreach ($items as $item) {

            $productIds[] = $item['id'];
            $productQtys[] = $item['quantity'];
            $totalAmount += $item['price'] * $item['quantity'];

            $orderDetails[] = [
                'productid' => $item['id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
                'created_at' => now(),
            ];
        }

        // Save ke mysql_app
        $orderId = DB::connection('mysql_app')->table('shop_orders')->insertGetId([
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_address' => $data['customer_address'],
            'notes' => $data['notes'],
            'product_ids' => json_encode($productIds),
            'quantities' => json_encode($productQtys),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'order_id' => $orderId,
            'total' => $totalAmount,
            'items' => $orderDetails,
        ]);
    } catch (\Exception $e) {

        return response()->json(['error' => $e->getMessage()], 500);
    }
});

/*
|--------------------------------------------------------------------------
| SHOP ORDER HISTORY
|--------------------------------------------------------------------------
*/

Route::get('/shop/history', function (Request $request) {

    $phone = $request->phone;
    $sort = $request->sort ?? 'latest';
    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $query = DB::connection('mysql_app')
        ->table('shop_orders');

    /*
    |--------------------------------------------------------------------------
    | FILTER PHONE
    |--------------------------------------------------------------------------
    */

    if ($phone) {
        $query->where('customer_phone', 'like', "%{$phone}%");
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER DATE
    |--------------------------------------------------------------------------
    */

    if ($startDate && $endDate) {

        $query->whereBetween(
            DB::raw('DATE(created_at)'),
            [$startDate, $endDate]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SORTING
    |--------------------------------------------------------------------------
    */

    switch ($sort) {

        case 'oldest':
            $query->orderBy('created_at', 'ASC');
            break;

        case 'highest':
            $query->orderBy('total_amount', 'DESC');
            break;

        case 'lowest':
            $query->orderBy('total_amount', 'ASC');
            break;

        default:
            $query->orderBy('created_at', 'DESC');
    }

    $orders = $query->paginate(20)->withQueryString();

    return view('shop-history', compact(
        'orders',
        'phone',
        'sort',
        'startDate',
        'endDate'
    ));
});

/*
|--------------------------------------------------------------------------
| GENERATE PDF
|--------------------------------------------------------------------------
*/

Route::get('/shop/order/{orderId}/pdf', function ($orderId) {

    try {

        $order = DB::connection('mysql_app')
            ->table('shop_orders')
            ->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        $productIds = json_decode($order->product_ids, true);
        $quantities = json_decode($order->quantities, true);

        $items = [];
        foreach ($productIds as $key => $productId) {
            $product = DB::table('product')->find($productId);
            if ($product) {
                $items[] = [
                    'name' => $product->name,
                    'quantity' => $quantities[$key],
                    'price' => $product->salesprice1,
                    'subtotal' => $product->salesprice1 * $quantities[$key],
                ];
            }
        }

        return view('shop-invoice', [
            'order' => $order,
            'items' => $items,
        ]);
    } catch (\Exception $e) {

        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/api/all-sales', function (Illuminate\Http\Request $request) {

    /*
    |--------------------------------------------------------------------------
    | FILTER OPTIONAL
    |--------------------------------------------------------------------------
    */

    $startDate = $request->start_date;
    $endDate   = $request->end_date;

    /*
    |--------------------------------------------------------------------------
    | AMBIL SEMUA TRANSAKSI
    |--------------------------------------------------------------------------
    */

    $rows = DB::table('salesdetail')

        /*
        |--------------------------------------------------------------------------
        | JOIN SALES
        |--------------------------------------------------------------------------
        */

        ->join(
            'sales',
            'salesdetail.salesid',
            '=',
            'sales.salesidref'
        )

        /*
        |--------------------------------------------------------------------------
        | JOIN PRODUCT
        |--------------------------------------------------------------------------
        */

        ->leftJoin(
            'product',
            'salesdetail.productid',
            '=',
            'product.id'
        )

        /*
        |--------------------------------------------------------------------------
        | SELECT
        |--------------------------------------------------------------------------
        */

        ->select(

            /*
            |--------------------------------------------------------------------------
            | SALES
            |--------------------------------------------------------------------------
            */

            'salesdetail.salesid',

            'sales.salesdate',

            'sales.salestime',

            /*
            |--------------------------------------------------------------------------
            | PRODUCT
            |--------------------------------------------------------------------------
            */

            'salesdetail.productid',

            'product.name as namabarang',

            /*
            |--------------------------------------------------------------------------
            | QTY
            |--------------------------------------------------------------------------
            */

            'salesdetail.salesqty',

            /*
            |--------------------------------------------------------------------------
            | PRICE
            |--------------------------------------------------------------------------
            */

            'salesdetail.price',

            /*
            |--------------------------------------------------------------------------
            | GROSS
            |--------------------------------------------------------------------------
            */

            'salesdetail.grossamount',

            /*
            |--------------------------------------------------------------------------
            | DISCOUNT
            |--------------------------------------------------------------------------
            */

            'salesdetail.valuedisc',

            /*
            |--------------------------------------------------------------------------
            | NET
            |--------------------------------------------------------------------------
            */

            'salesdetail.netamount',

            /*
            |--------------------------------------------------------------------------
            | COGS TOTAL
            |--------------------------------------------------------------------------
            */

            'salesdetail.cogs',

            /*
            |--------------------------------------------------------------------------
            | HPP PER PCS
            |--------------------------------------------------------------------------
            */

            DB::raw('
                CASE
                    WHEN salesdetail.salesqty > 0
                    THEN salesdetail.cogs / salesdetail.salesqty
                    ELSE 0
                END as hpp
            '),

            /*
            |--------------------------------------------------------------------------
            | HARGA BARANG PER PCS
            |--------------------------------------------------------------------------
            */

            DB::raw('
                CASE
                    WHEN salesdetail.salesqty > 0
                    THEN salesdetail.grossamount / salesdetail.salesqty
                    ELSE 0
                END as hargabarang
            '),

            /*
            |--------------------------------------------------------------------------
            | DISCOUNT PER PCS
            |--------------------------------------------------------------------------
            */

            DB::raw('
                CASE
                    WHEN salesdetail.salesqty > 0
                    THEN salesdetail.valuedisc / salesdetail.salesqty
                    ELSE 0
                END as discbarang
            '),

            /*
            |--------------------------------------------------------------------------
            | MARGIN PER PCS
            |--------------------------------------------------------------------------
            */

            DB::raw('
                (
                    (
                        CASE
                            WHEN salesdetail.salesqty > 0
                            THEN salesdetail.netamount / salesdetail.salesqty
                            ELSE 0
                        END
                    )
                    -
                    (
                        CASE
                            WHEN salesdetail.salesqty > 0
                            THEN salesdetail.cogs / salesdetail.salesqty
                            ELSE 0
                        END
                    )
                ) as marginbarang
            '),

            /*
            |--------------------------------------------------------------------------
            | TOTAL MARGIN
            |--------------------------------------------------------------------------
            */

            DB::raw('
                salesdetail.netamount
                -
                salesdetail.cogs
                as totalmargin
            '),

            /*
            |--------------------------------------------------------------------------
            | MARGIN PERCENT
            |--------------------------------------------------------------------------
            */

            DB::raw('
                CASE
                    WHEN salesdetail.netamount > 0
                    THEN
                    (
                        (
                            salesdetail.netamount
                            - salesdetail.cogs
                        )
                        /
                        salesdetail.netamount
                    ) * 100
                    ELSE 0
                END as marginpercent
            ')
        )

        /*
        |--------------------------------------------------------------------------
        | HANYA SALES
        |--------------------------------------------------------------------------
        */

        ->where(
            'salesdetail.salesid',
            'like',
            'I/SL-%'
        )

        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL OPTIONAL
        |--------------------------------------------------------------------------
        */

        ->when(
            $startDate && $endDate,
            function ($query) use ($startDate, $endDate) {

                $query->whereBetween(
                    DB::raw('DATE(sales.salesdate)'),
                    [$startDate, $endDate]
                );
            }
        )

        /*
        |--------------------------------------------------------------------------
        | SORT ASCENDING
        |--------------------------------------------------------------------------
        */

        ->orderByRaw('
            CAST(
                SUBSTRING_INDEX(salesdetail.salesid, "-", -1)
                AS UNSIGNED
            ) ASC
        ')

        ->get();

    /*
    |--------------------------------------------------------------------------
    | GROUP BY SALESID
    |--------------------------------------------------------------------------
    */

    $grouped = [];

    foreach ($rows as $row) {

        /*
        |--------------------------------------------------------------------------
        | JIKA TRANSAKSI BELUM ADA
        |--------------------------------------------------------------------------
        */

        if (!isset($grouped[$row->salesid])) {

            $grouped[$row->salesid] = [

                /*
                |--------------------------------------------------------------------------
                | HEADER TRANSAKSI
                |--------------------------------------------------------------------------
                */

                'salesid' => $row->salesid,

                'salesdate' => $row->salesdate,

                'salestime' => $row->salestime,

                /*
                |--------------------------------------------------------------------------
                | TOTAL TRANSAKSI
                |--------------------------------------------------------------------------
                */

                'total_grossamount' => 0,

                'total_discount' => 0,

                'total_netamount' => 0,

                'total_cogs' => 0,

                'total_margin' => 0,

                /*
                |--------------------------------------------------------------------------
                | ITEMS
                |--------------------------------------------------------------------------
                */

                'items' => []
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | TOTALKAN HEADER
        |--------------------------------------------------------------------------
        */

        $grouped[$row->salesid]['total_grossamount']
            += $row->grossamount;

        $grouped[$row->salesid]['total_discount']
            += $row->valuedisc;

        $grouped[$row->salesid]['total_netamount']
            += $row->netamount;

        $grouped[$row->salesid]['total_cogs']
            += $row->cogs;

        $grouped[$row->salesid]['total_margin']
            += $row->totalmargin;

        /*
        |--------------------------------------------------------------------------
        | ITEMS
        |--------------------------------------------------------------------------
        */

        $grouped[$row->salesid]['items'][] = [

            /*
            |--------------------------------------------------------------------------
            | PRODUCT
            |--------------------------------------------------------------------------
            */

            'productid' => $row->productid,

            'namabarang' => $row->namabarang,

            /*
            |--------------------------------------------------------------------------
            | QTY
            |--------------------------------------------------------------------------
            */

            'salesqty' => $row->salesqty,

            /*
            |--------------------------------------------------------------------------
            | PRICE
            |--------------------------------------------------------------------------
            */

            'price' => $row->price,

            /*
            |--------------------------------------------------------------------------
            | GROSS
            |--------------------------------------------------------------------------
            */

            'grossamount' => $row->grossamount,

            /*
            |--------------------------------------------------------------------------
            | DISCOUNT
            |--------------------------------------------------------------------------
            */

            'valuedisc' => $row->valuedisc,

            /*
            |--------------------------------------------------------------------------
            | NET
            |--------------------------------------------------------------------------
            */

            'netamount' => $row->netamount,

            /*
            |--------------------------------------------------------------------------
            | COGS
            |--------------------------------------------------------------------------
            */

            'cogs' => $row->cogs,

            /*
            |--------------------------------------------------------------------------
            | HPP
            |--------------------------------------------------------------------------
            */

            'hpp' => round($row->hpp, 2),

            /*
            |--------------------------------------------------------------------------
            | HARGA BARANG
            |--------------------------------------------------------------------------
            */

            'hargabarang' => round($row->hargabarang, 2),

            /*
            |--------------------------------------------------------------------------
            | DISKON PER PCS
            |--------------------------------------------------------------------------
            */

            'discbarang' => round($row->discbarang, 2),

            /*
            |--------------------------------------------------------------------------
            | MARGIN PER PCS
            |--------------------------------------------------------------------------
            */

            'marginbarang' => round($row->marginbarang, 2),

            /*
            |--------------------------------------------------------------------------
            | TOTAL MARGIN
            |--------------------------------------------------------------------------
            */

            'totalmargin' => round($row->totalmargin, 2),

            /*
            |--------------------------------------------------------------------------
            | MARGIN %
            |--------------------------------------------------------------------------
            */

            'marginpercent' => round($row->marginpercent, 2),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RESET INDEX ARRAY
    |--------------------------------------------------------------------------
    */

    $final = array_values($grouped);

    /*
    |--------------------------------------------------------------------------
    | RESPONSE JSON
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' => true,

        'total_transactions' => count($final),

        'data' => $final

    ]);
});

Route::get('/market-basket-analysis', function () {

    /*
    |--------------------------------------------------------------------------
    | AMBIL TRANSAKSI
    |--------------------------------------------------------------------------
    */

    $transactions = DB::table('salesdetail')

        ->leftJoin(
            'product',
            'salesdetail.productid',
            '=',
            'product.id'
        )

        ->select(
            'salesdetail.salesid',
            'salesdetail.productid',
            'product.name as product_name'
        )

        ->where('salesdetail.salesid', 'like', 'I/SL-%')

        ->orderBy('salesdetail.salesid')

        ->get()

        ->groupBy('salesid');

    /*
    |--------------------------------------------------------------------------
    | TOTAL TRANSAKSI
    |--------------------------------------------------------------------------
    */

    $totalTransactions = count($transactions);

    /*
    |--------------------------------------------------------------------------
    | HITUNG PRODUK INDIVIDU
    |--------------------------------------------------------------------------
    */

    $singleCounts = [];

    foreach ($transactions as $salesid => $items) {

        $uniqueProducts = [];

        foreach ($items as $item) {

            $uniqueProducts[$item->productid] = $item->product_name;
        }

        foreach ($uniqueProducts as $productId => $productName) {

            if (!isset($singleCounts[$productId])) {

                $singleCounts[$productId] = [

                    'product_name' => $productName,

                    'count' => 0
                ];
            }

            $singleCounts[$productId]['count']++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG PAIR PRODUK
    |--------------------------------------------------------------------------
    */

    $pairs = [];

    foreach ($transactions as $salesid => $items) {

        $products = [];

        foreach ($items as $item) {

            $products[$item->productid] = $item->product_name;
        }

        $productIds = array_keys($products);

        $countProducts = count($productIds);

        /*
        |--------------------------------------------------------------------------
        | LOOP KOMBINASI
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < $countProducts; $i++) {

            for ($j = $i + 1; $j < $countProducts; $j++) {

                $a = $productIds[$i];
                $b = $productIds[$j];

                $sorted = [$a, $b];

                sort($sorted);

                $pairKey = $sorted[0] . '|' . $sorted[1];

                if (!isset($pairs[$pairKey])) {

                    $pairs[$pairKey] = [

                        'product_a' => $sorted[0],
                        'product_b' => $sorted[1],

                        'product_a_name' => $products[$sorted[0]],
                        'product_b_name' => $products[$sorted[1]],

                        'frequency' => 0
                    ];
                }

                $pairs[$pairKey]['frequency']++;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ANALISIS
    |--------------------------------------------------------------------------
    */

    $analysis = [];

    foreach ($pairs as $pair) {

        /*
        |--------------------------------------------------------------------------
        | FILTER MINIMAL 5x
        |--------------------------------------------------------------------------
        */

        if ($pair['frequency'] < 5) {
            continue;
        }

        $freqAB = $pair['frequency'];

        $countA = $singleCounts[$pair['product_a']]['count'] ?? 1;

        $countB = $singleCounts[$pair['product_b']]['count'] ?? 1;

        /*
        |--------------------------------------------------------------------------
        | SUPPORT
        |--------------------------------------------------------------------------
        */

        $support = ($freqAB / $totalTransactions) * 100;

        /*
        |--------------------------------------------------------------------------
        | CONFIDENCE
        |--------------------------------------------------------------------------
        */

        $confidenceAtoB = ($freqAB / $countA) * 100;

        $confidenceBtoA = ($freqAB / $countB) * 100;

        /*
        |--------------------------------------------------------------------------
        | LIFT
        |--------------------------------------------------------------------------
        */

        $lift = (

            $freqAB / $totalTransactions

        ) /

        (

            ($countA / $totalTransactions)

            *

            ($countB / $totalTransactions)

        );

        /*
        |--------------------------------------------------------------------------
        | REKOMENDASI
        |--------------------------------------------------------------------------
        */

        $recommendation = 'Normal';

        if ($lift >= 2 && $confidenceAtoB >= 30) {

            $recommendation = 'Sangat Direkomendasikan Dekat';
        }
        elseif ($lift >= 1.5) {

            $recommendation = 'Cocok Dekat';
        }
        elseif ($lift < 1) {

            $recommendation = 'Kurang Berkaitan';
        }

        $analysis[] = [

            'product_a' => $pair['product_a_name'],

            'product_b' => $pair['product_b_name'],

            'frequency' => $freqAB,

            'support' => round($support, 2),

            'confidence_a_to_b' => round($confidenceAtoB, 2),

            'confidence_b_to_a' => round($confidenceBtoA, 2),

            'lift' => round($lift, 2),

            'recommendation' => $recommendation
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SORTING
    |--------------------------------------------------------------------------
    */

    usort($analysis, function ($a, $b) {

        return $b['frequency'] <=> $a['frequency'];
    });

    return view(

        'market-basket-analysis',

        compact(

            'analysis',

            'totalTransactions'
        )
    );
});


Route::get('/retail-intelligence', function () {

    /*
    |--------------------------------------------------------------------------
    | AMBIL TRANSAKSI
    |--------------------------------------------------------------------------
    */

    $transactions = DB::table('salesdetail')

        ->leftJoin(
            'product',
            'salesdetail.productid',
            '=',
            'product.id'
        )

        ->leftJoin(
            'sales',
            'salesdetail.salesid',
            '=',
            'sales.salesidref'
        )

        ->select(
            'salesdetail.salesid',
            'salesdetail.productid',
            'salesdetail.salesqty',
            'salesdetail.netamount',
            'salesdetail.cogs',
            'sales.salestime',
            'product.name as product_name'
        )

        ->where('salesdetail.salesid', 'like', 'I/SL-%')

        ->get();

    /*
    |--------------------------------------------------------------------------
    | FAST MOVING
    |--------------------------------------------------------------------------
    */

    $fastMoving = DB::table('salesdetail')

        ->leftJoin(
            'product',
            'salesdetail.productid',
            '=',
            'product.id'
        )

        ->select(
            'product.name',
            DB::raw('SUM(salesdetail.salesqty) as total_qty'),
            DB::raw('SUM(salesdetail.netamount) as omzet')
        )

        ->where('salesdetail.salesid', 'like', 'I/SL-%')

        ->groupBy('product.id', 'product.name')

        ->orderByDesc('total_qty')

        ->limit(10)

        ->get();

    /*
    |--------------------------------------------------------------------------
    | PROFIT TERTINGGI
    |--------------------------------------------------------------------------
    */

    $highestMargin = DB::table('salesdetail')

        ->leftJoin(
            'product',
            'salesdetail.productid',
            '=',
            'product.id'
        )

        ->select(
            'product.name',
            DB::raw('SUM(salesdetail.netamount - salesdetail.cogs) as total_margin')
        )

        ->where('salesdetail.salesid', 'like', 'I/SL-%')

        ->groupBy('product.id', 'product.name')

        ->orderByDesc('total_margin')

        ->limit(10)

        ->get();

    /*
    |--------------------------------------------------------------------------
    | PRODUK SERING DISKON
    |--------------------------------------------------------------------------
    */

    $discountProducts = DB::table('salesdetail')

        ->leftJoin(
            'product',
            'salesdetail.productid',
            '=',
            'product.id'
        )

        ->select(
            'product.name',
            DB::raw('SUM(salesdetail.valuedisc) as total_discount')
        )

        ->groupBy('product.id', 'product.name')

        ->orderByDesc('total_discount')

        ->limit(10)

        ->get();

    /*
    |--------------------------------------------------------------------------
    | JAM RAMAI
    |--------------------------------------------------------------------------
    */

    $busyHours = DB::table('sales')

        ->select(
            DB::raw('HOUR(salestime) as hour'),
            DB::raw('COUNT(*) as total_transactions')
        )

        ->groupBy(DB::raw('HOUR(salestime)'))

        ->orderBy('hour')

        ->get();

    /*
    |--------------------------------------------------------------------------
    | MARKET BASKET
    |--------------------------------------------------------------------------
    */

    $grouped = $transactions->groupBy('salesid');

    $pairs = [];

    foreach ($grouped as $salesid => $items) {

        $products = [];

        foreach ($items as $item) {

            $products[$item->productid] = $item->product_name;
        }

        $ids = array_keys($products);

        for ($i = 0; $i < count($ids); $i++) {

            for ($j = $i + 1; $j < count($ids); $j++) {

                $combo = [$ids[$i], $ids[$j]];

                sort($combo);

                $key = $combo[0] . '|' . $combo[1];

                if (!isset($pairs[$key])) {

                    $pairs[$key] = [

                        'a' => $products[$combo[0]],

                        'b' => $products[$combo[1]],

                        'freq' => 0
                    ];
                }

                $pairs[$key]['freq']++;
            }
        }
    }

    $pairAnalysis = collect($pairs)

        ->filter(function ($item) {

            return $item['freq'] >= 5;
        })

        ->sortByDesc('freq')

        ->values();

    return view(

        'retail-intelligence',

        compact(

            'fastMoving',

            'highestMargin',

            'discountProducts',

            'busyHours',

            'pairAnalysis'
        )
    );
});

/*
|--------------------------------------------------------------------------
| API - SEARCH PRODUCT BY BARCODE/NAME
|--------------------------------------------------------------------------
*/

Route::get('/api/search-product-by-barcode', function (Request $request) {

    $keyword = $request->query('keyword');

    if (!$keyword) {
        return response()->json(['success' => false, 'error' => 'Keyword required'], 400);
    }

    try {
        // First, get the product basic info
        $product = DB::table('product')
            ->leftJoin('productgroup', 'product.productgroup', '=', 'productgroup.id')
            ->leftJoin('supplier', 'product.supplier', '=', 'supplier.id')
            ->select(
                'product.id',
                'product.name',
                'product.productgroup',
                'product.costprice',
                'product.salesprice1',
                'product.salesdiscqty1',
                'product.salesdiscprice1',
                'product.salesdiscqty2',
                'product.salesdiscprice2',
                'product.salesdiscqty3',
                'product.salesdiscprice3',
                DB::raw('COALESCE(productgroup.name, "-") as productgroup_name'),
                DB::raw('COALESCE(supplier.name, "-") as supplier_name')
            )
            ->where('product.isactive', 1)
            ->where(function ($q) use ($keyword) {
                $q->where('product.id', 'like', "%{$keyword}%")
                  ->orWhere('product.name', 'like', "%{$keyword}%");
            })
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        // Get inventory stats separately
        $inventoryStats = DB::table('inventory')
            ->where('productid', $product->id)
            ->select(
                DB::raw('COALESCE(SUM(invin - invout), 0) as stock'),
                DB::raw('COALESCE(SUM(CASE WHEN transtype = "IN" THEN invin ELSE 0 END), 0) as total_masuk'),
                DB::raw('COALESCE(SUM(CASE WHEN transtype = "OUT" THEN invout ELSE 0 END), 0) as total_keluar')
            )
            ->first();

        if ($inventoryStats) {
            $product->stock = $inventoryStats->stock;
            $product->total_masuk = $inventoryStats->total_masuk;
            $product->total_keluar = $inventoryStats->total_keluar;
        } else {
            $product->stock = 0;
            $product->total_masuk = 0;
            $product->total_keluar = 0;
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

/*
|--------------------------------------------------------------------------
| API - GET PRODUCT SALES DATA (dengan date range filter)
|--------------------------------------------------------------------------
*/

Route::get('/api/products/{productId}/sales', function (Request $request, $productId) {

    $days = $request->query('days', 7); // Default 7 hari

    if (!$productId) {
        return response()->json(['error' => 'Product ID required'], 400);
    }

    try {
        $startDate = now()->subDays($days)->startOfDay();
        $endDate = now()->endOfDay();

        // Get sales data dari salesdetail
        $sales = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesidref')
            ->where('salesdetail.productid', $productId)
            ->whereBetween('salesdetail.updatetimestamp', [$startDate, $endDate])
            ->select(
                'salesdetail.salesid',
                'salesdetail.productid',
                'salesdetail.salesqty as quantity',
                'salesdetail.price',
                'salesdetail.netamount',
                'salesdetail.cogs',
                DB::raw('(salesdetail.netamount - salesdetail.cogs) as margin'),
                'sales.salestime as date',
                DB::raw('DATE(sales.salestime) as trans_date')
            )
            ->orderBy('sales.salestime', 'DESC')
            ->get();

        // Summary statistics
        $summary = [
            'total_quantity' => 0,
            'total_amount' => 0,
            'total_margin' => 0,
            'transaction_count' => 0,
            'avg_quantity' => 0,
            'avg_price' => 0
        ];

        if ($sales->count() > 0) {
            $summary['total_quantity'] = $sales->sum('quantity');
            $summary['total_amount'] = $sales->sum('netamount');
            $summary['total_margin'] = $sales->sum('margin');
            $summary['transaction_count'] = $sales->count();
            $summary['avg_quantity'] = round($summary['total_quantity'] / $summary['transaction_count'], 2);
            $summary['avg_price'] = round($summary['total_amount'] / $summary['transaction_count'], 0);
        }

        // Daily aggregate
        $dailyAggregate = $sales->groupBy('trans_date')->map(function ($daySales) {
            $firstSale = $daySales->first();
            return [
                'date' => $firstSale->trans_date,
                'quantity' => $daySales->sum('quantity'),
                'amount' => $daySales->sum('netamount'),
                'margin' => $daySales->sum('margin'),
                'transactions' => $daySales->count()
            ];
        })->values()->sortBy('date')->values(); // Sort ascending (oldest to newest)

        return response()->json([
            'success' => true,
            'data' => $sales,
            'summary' => $summary,
            'daily_aggregate' => $dailyAggregate,
            'period_days' => $days,
            'chart_data' => [
                'dates' => $dailyAggregate->pluck('date'),
                'quantities' => $dailyAggregate->pluck('quantity'),
                'amounts' => $dailyAggregate->pluck('amount'),
                'margins' => $dailyAggregate->pluck('margin')
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

/**
 * ============================================================================
 * PRINT BARCODE LABEL ROUTE
 * ============================================================================
 */
Route::get('/products/print-barcode', function () {
    // Auto-create queue table if not exists in mysql_app
    DB::connection('mysql_app')->statement('CREATE TABLE IF NOT EXISTS barcode_print_queue (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(255) NULL,
        product_id VARCHAR(50) NOT NULL,
        qty INT NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )');
    
    // Add session_id column if it doesn't exist
    $hasSessionId = false;
    try {
        $columns = DB::connection('mysql_app')->select("SHOW COLUMNS FROM barcode_print_queue LIKE 'session_id'");
        $hasSessionId = !empty($columns);
    } catch (\Exception $e) {}

    if (!$hasSessionId) {
        try {
            DB::connection('mysql_app')->statement("ALTER TABLE barcode_print_queue ADD COLUMN session_id VARCHAR(255) NULL AFTER id");
        } catch (\Exception $e) {}
    }
    
    return view('products.print-barcode');
});

Route::get('/products/print-barcode/pdf', function () {
    $businessName = 'SJ MART';
    $sessionId = session()->getId();
    
    // Fetch queue items from mysql_app for this session
    $queueItems = DB::connection('mysql_app')->table('barcode_print_queue')
        ->where('session_id', $sessionId)
        ->get();
    $productIds = $queueItems->pluck('product_id')->toArray();
    
    // Fetch products details from mysql (master)
    $products = DB::table('product')
        ->whereIn('id', $productIds)
        ->select('id', 'name', 'salesprice1 as price')
        ->get()
        ->keyBy('id');
        
    $items = [];
    foreach ($queueItems as $qItem) {
        if (isset($products[$qItem->product_id])) {
            $p = $products[$qItem->product_id];
            $items[] = (object)[
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'qty' => $qItem->qty
            ];
        }
    }

    return view('products.print-pdf', compact('businessName', 'items'));
});

Route::get('/api/search-products', function (Request $request) {
    $keyword = $request->query('keyword');
    if (!$keyword) {
        return response()->json([]);
    }

    try {
        $products = DB::table('product')
            ->select('id', 'name', 'salesprice1')
            ->where('isactive', 1)
            ->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%{$keyword}%")
                  ->orWhere('name', 'like', "%{$keyword}%");
            })
            ->limit(20)
            ->get();

        return response()->json($products);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::post('/api/barcode-print/save', function (Request $request) {
    try {
        $items = $request->input('items', []);
        
        // Auto-create queue table if not exists in mysql_app
        DB::connection('mysql_app')->statement('CREATE TABLE IF NOT EXISTS barcode_print_queue (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(255) NULL,
            product_id VARCHAR(50) NOT NULL,
            qty INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )');

        // Add session_id column if it doesn't exist
        $hasSessionId = false;
        try {
            $columns = DB::connection('mysql_app')->select("SHOW COLUMNS FROM barcode_print_queue LIKE 'session_id'");
            $hasSessionId = !empty($columns);
        } catch (\Exception $e) {}

        if (!$hasSessionId) {
            try {
                DB::connection('mysql_app')->statement("ALTER TABLE barcode_print_queue ADD COLUMN session_id VARCHAR(255) NULL AFTER id");
            } catch (\Exception $e) {}
        }

        $sessionId = session()->getId();

        DB::connection('mysql_app')->transaction(function () use ($items, $sessionId) {
            // Delete existing items for THIS session only in mysql_app
            DB::connection('mysql_app')->table('barcode_print_queue')
                ->where('session_id', $sessionId)
                ->delete();
            
            // Insert new items
            $insertData = [];
            foreach ($items as $item) {
                $insertData[] = [
                    'session_id' => $sessionId,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'] ?? 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            
            if (!empty($insertData)) {
                DB::connection('mysql_app')->table('barcode_print_queue')->insert($insertData);
            }
        });
        
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

Route::get('/api/barcode-print/load', function () {
    try {
        // Auto-create table if not exists just in case
        DB::connection('mysql_app')->statement('CREATE TABLE IF NOT EXISTS barcode_print_queue (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(255) NULL,
            product_id VARCHAR(50) NOT NULL,
            qty INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )');

        // Add session_id column if it doesn't exist
        $hasSessionId = false;
        try {
            $columns = DB::connection('mysql_app')->select("SHOW COLUMNS FROM barcode_print_queue LIKE 'session_id'");
            $hasSessionId = !empty($columns);
        } catch (\Exception $e) {}

        if (!$hasSessionId) {
            try {
                DB::connection('mysql_app')->statement("ALTER TABLE barcode_print_queue ADD COLUMN session_id VARCHAR(255) NULL AFTER id");
            } catch (\Exception $e) {}
        }

        $sessionId = session()->getId();

        // Fetch queue items from mysql_app for this session
        $queueItems = DB::connection('mysql_app')->table('barcode_print_queue')
            ->where('session_id', $sessionId)
            ->get();
        $productIds = $queueItems->pluck('product_id')->toArray();

        // Fetch products details from mysql (master)
        $products = DB::table('product')
            ->whereIn('id', $productIds)
            ->select('id', 'name', 'salesprice1 as price')
            ->get()
            ->keyBy('id');

        $items = [];
        foreach ($queueItems as $qItem) {
            if (isset($products[$qItem->product_id])) {
                $p = $products[$qItem->product_id];
                $items[] = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'qty' => $qItem->qty
                ];
            }
        }
            
        return response()->json($items);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

/**
 * ============================================================================
 * PRIVATE DEVELOPER SITEMAP ROUTE
 * ============================================================================
 */
Route::get('/route', function () {
    return view('routes-sitemap');
});

Route::get('/clear-all-caches', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        return "✓ All Laravel caches cleared successfully on the remote server!";
    } catch (\Exception $e) {
        return "Error clearing cache: " . $e->getMessage();
    }
});

