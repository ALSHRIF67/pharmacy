<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Batch;
use App\Models\StockMovement;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $list = [
            ['name' => 'Paracetamol', 'barcode' => 'PARA-001', 'base_price' => 5.00, 'stock' => 100],
            ['name' => 'Amoxicillin', 'barcode' => 'AMOX-001', 'base_price' => 12.50, 'stock' => 50],
            ['name' => 'Vitamin C', 'barcode' => 'VITC-001', 'base_price' => 8.00, 'stock' => 75],
        ];

        foreach ($list as $m) {
            $product = Product::create([
                'name' => $m['name'],
                'barcode' => $m['barcode'],
                'base_price' => $m['base_price'],
            ]);

            $batch = Batch::create([
                'product_id' => $product->id,
                'expiry_date' => now()->addYear(),
                'selling_price' => $m['base_price'],
            ]);

            StockMovement::create([
                'product_id' => $product->id,
                'batch_id' => $batch->id,
                'type' => 'IN',
                'quantity' => $m['stock'],
                'created_at' => now(),
            ]);
        }
    }
}
