<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Dumping barcode_print_queue in mysql_app:\n";
    $queue = DB::connection('mysql_app')->table('barcode_print_queue')->get();
    print_r($queue);
    
    if ($queue->isNotEmpty()) {
        $productIds = $queue->pluck('product_id')->toArray();
        echo "\nProduct IDs in Queue: " . implode(', ', $productIds) . "\n";
        
        echo "\nQuerying products from master mysql:\n";
        $products = DB::table('product')
            ->whereIn('id', $productIds)
            ->select('id', 'name', 'salesprice1')
            ->get();
        print_r($products);
    } else {
        echo "\nQueue is empty!\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
