<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $saleRepo = app(App\Repositories\SaleRepository::class);
    $batchRepo = app(App\Repositories\BatchRepository::class);
    $invService = app(App\Services\InventoryService::class);
    
    $product = App\Models\Product::first();
    if (!$product) {
       echo "No product\n";
       exit;
    }
    dump($product->toArray());
    
    $s = new App\Services\SaleService($saleRepo, $batchRepo, $invService);
    $sale = $s->processSale([
        ['product_id' => $product->id, 'quantity' => 1, 'price' => 10]
    ]);
    dump("Sale processed successfully", $sale->id);
} catch (\Throwable $e) {
    dump("Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
}
