<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;


Route::get('/list-models', function () {

    $response = Http::get(
        'https://generativelanguage.googleapis.com/v1/models?key=AIzaSyCQ3r_3AVc158RGYX2lQ5nT5DfbB4xvbIk'
    );

    return $response->json();
});

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

    $days = $request->days ?? 30;

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

            'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=AIzaSyAftrqP2RANw_PZMOHGZ-izYxtxtYUfp6s',

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

    return "Database berhasil direset & diimport ulang";
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

    $productgroup = $request->productgroup;
    $supplier = $request->supplier;

    $query = DB::table('product')

        ->leftJoin('productgroup', 'product.productgroup', '=', 'productgroup.id')

        ->leftJoin('supplier', 'product.supplier', '=', 'supplier.id')

        ->leftJoin('inventory', 'product.id', '=', 'inventory.productid')

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

            DB::raw('SUM(inventory.invin - inventory.invout) as stock')

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

        ); // Filter Product Group
    if ($productgroup) {
        $query->where('product.productgroup', $productgroup);
    }

    // Filter Supplier
    if ($supplier) {
        $query->where('product.supplier', $supplier);
    }

    $products = $query->get();

    // Ambil data dropdown
    $productgroups = DB::table('productgroup')->get();
    $suppliers = DB::table('supplier')->get();

    return view('product', compact(
        'products',
        'productgroups',
        'suppliers'
    ));
});
