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

        ->groupBy('salesdetail.salesid')

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

Route::get('/api/pesanan-shopee', [PesananShopeeController::class, 'apiList']);

Route::get('/api/get-returns-by-group', function (Request $request) {

    $groupName = trim($request->group);

    $masterDb = config('database.connections.mysql.database');

    $returns = DB::connection('u990824557_db_app')

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
    return DB::connection('u990824557_db_app')->select("SELECT DATABASE() as db");
});

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
            ->where('productid', $productId)
            ->orderBy('transdate', 'DESC')
            ->orderBy('id', 'DESC')
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

            if (strpos($trans->transid, 'I/PC') === 0) {
                // Pembelian
                $type = 'Pembelian';
                $category = 'pembelian';
                $direction = 'in';
                $quantity = $trans->invin;
                $icon = 'fa-arrow-down';
                $color = 'success';
                $summary['pembelian'] += $quantity;
            } elseif (strpos($trans->transid, 'I/SL') === 0) {
                // Penjualan
                $type = 'Penjualan';
                $category = 'penjualan';
                $direction = 'out';
                $quantity = $trans->invout;
                $icon = 'fa-arrow-up';
                $color = 'warning';
                $summary['penjualan'] += $quantity;
            } elseif (strpos($trans->transid, 'I/SR') === 0) {
                // Retur Penjualan (barang masuk)
                $type = 'Retur Penjualan';
                $category = 'retur_penjualan';
                $direction = 'in';
                $quantity = $trans->invin;
                $icon = 'fa-undo';
                $color = 'info';
                $summary['retur_penjualan'] += $quantity;
            } elseif (strpos($trans->transid, 'I/PR') === 0) {
                // Retur Pembelian (barang keluar)
                $type = 'Retur Pembelian';
                $category = 'retur_pembelian';
                $direction = 'out';
                $quantity = $trans->invout;
                $icon = 'fa-share';
                $color = 'danger';
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
            }

            if ($type) {
                $categorized[] = [
                    'id' => $trans->id,
                    'transid' => $trans->transid,
                    'type' => $type,
                    'category' => $category,
                    'direction' => $direction,
                    'quantity' => $quantity,
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

// Pesanan Shopee Routes
Route::get('/pesanan-shopee', [PesananShopeeController::class, 'index']);
Route::post('/pesanan-shopee/store', [PesananShopeeController::class, 'store']);
Route::get('/pesanan-shopee/detail/{id}', [PesananShopeeController::class, 'show']);
Route::post('/pesanan-shopee/update-status/{id}', [PesananShopeeController::class, 'updateStatus']);
Route::get('/api/products', [PesananShopeeController::class, 'getProducts']);
