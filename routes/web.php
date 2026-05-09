<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

Route::get('/ai-analysis', function (Request $request) {

    /*
    |--------------------------------------------------------------------------
    | PARAMETER
    |--------------------------------------------------------------------------
    */

    $groupName = $request->group ?? 'ROKOK';

    $days = $request->days ?? 90;

    /*
    |--------------------------------------------------------------------------
    | TANGGAL
    |--------------------------------------------------------------------------
    */

    $startDate = now()->subDays($days - 1)->startOfDay();

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

        ->leftJoin(
            'supplier',
            'product.supplier',
            '=',
            'supplier.id'
        )

        ->select(

            'product.id',
            'product.name',

            'supplier.name as supplier_name',

            'productgroup.name as group_name'

        )

        ->where('product.isactive', 1)

        ->where('productgroup.name', $groupName)

        ->get();

    /*
    |--------------------------------------------------------------------------
    | ARRAY FINAL
    |--------------------------------------------------------------------------
    */

    $final = [

        'group' => $groupName,

        'days' => (int) $days,

        'products' => []

    ];

    /*
    |--------------------------------------------------------------------------
    | LOOP PRODUK
    |--------------------------------------------------------------------------
    */

    foreach ($products as $product) {

        /*
        |--------------------------------------------------------------------------
        | STOCK
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
        | SALES HARIAN
        |--------------------------------------------------------------------------
        */

        $sales = DB::table('salesdetail')

            ->select(

                DB::raw('DATE(updatetimestamp) as tanggal'),

                DB::raw('SUM(salesqty) as terjual'),

                DB::raw('SUM(returnqty) as retur'),

                DB::raw('SUM(netamount) as omzet'),

                DB::raw('SUM(netamount - cogs) as margin')

            )

            ->where('productid', $product->id)

            ->whereBetween(

                'updatetimestamp',

                [$startDate, $endDate]

            )

            ->groupBy(
                DB::raw('DATE(updatetimestamp)')
            )

            ->orderBy('tanggal', 'ASC')

            ->get()

            ->keyBy('tanggal');

        /*
        |--------------------------------------------------------------------------
        | HISTORY
        |--------------------------------------------------------------------------
        */

        $history = [];

        for ($i = 0; $i < $days; $i++) {

            $date = now()

                ->subDays($days - 1 - $i)

                ->format('Y-m-d');

            $row = $sales[$date] ?? null;

            $terjual = $row->terjual ?? 0;

            $retur = $row->retur ?? 0;

            $omzet = round($row->omzet ?? 0);

            /*
            |--------------------------------------------------------------------------
            | SKIP JIKA TIDAK ADA PENJUALAN
            |--------------------------------------------------------------------------
            */

            if (

                $terjual <= 0

                &&

                $retur <= 0

                &&

                $omzet <= 0

            ) {

                continue;

            }

            $history[] = [

                'tanggal' => $date,

                'terjual' => $terjual,

                'retur' => $retur,

                'omzet' => $omzet

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SKIP PRODUK TANPA PENJUALAN
        |--------------------------------------------------------------------------
        */

        if (count($history) <= 0) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | MASUKKAN PRODUK
        |--------------------------------------------------------------------------
        */

        $final['products'][] = [

            'product_id' => $product->id,

            'nama' => $product->name,

            'stock' => round($stock),

            'history' => $history

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

    Analisis data penjualan toko berikut.

    Fokus:
    - produk paling laris
    - slow moving
    - rekomendasi reorder
    - stok terlalu banyak
    - tren penjualan

    Berikan hasil singkat dan jelas.

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

            'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash-preview:generateContent?key=AIzaSyAftrqP2RANw_PZMOHGZ-izYxtxtYUfp6s',

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
    | RETURN HASIL
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' => true,

        'group' => $groupName,

        'days' => $days,

        'total_products' => count($final['products']),

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
