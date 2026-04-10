<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $p = App\Models\Product::create(['name'=>'بنادول إكسترا 500 ملغ', 'barcode'=>'628100000000', 'base_price'=>15.50, 'sync_status'=>'pending']);
    $b = App\Models\Batch::create(['product_id'=>$p->id, 'batch_number'=>'PAN-2026', 'expiry_date'=>now()->addYear(), 'cost_price'=>10, 'selling_price'=>15.50]);
    App\Models\StockMovement::create(['product_id'=>$p->id, 'batch_id'=>$b->id, 'type'=>'IN', 'quantity'=>150, 'reference_type'=>'Manual Addition', 'created_at'=>now()]);
    
    $saleRepo = app(App\Repositories\SaleRepository::class);
    $batchRepo = app(App\Repositories\BatchRepository::class);
    $invService = app(App\Services\InventoryService::class);
    
    $s = new App\Services\SaleService($saleRepo, $batchRepo, $invService);
    $sale = $s->processSale([
        ['product_id' => $p->id, 'quantity' => 2, 'price' => 15.50]
    ]);
    
    echo "Successfully created product '" . $p->name . "' and added a sale/invoice with ID: " . $sale->id;
} catch (\Throwable $e) {
    dump("Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
}
