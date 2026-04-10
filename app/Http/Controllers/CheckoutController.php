<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\StockMovement;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'total_price' => 'required|numeric'
        ]);

        $items = $data['items'];

        $sale = null;

        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'total_price' => $data['total_price'],
                'created_at' => now()
            ]);

            foreach ($items as $it) {
                $qty = isset($it['qty']) ? intval($it['qty']) : (isset($it['quantity']) ? intval($it['quantity']) : 1);
                $price = isset($it['price']) ? floatval($it['price']) : (isset($it['base_price']) ? floatval($it['base_price']) : 0);

                $product = null;
                if (isset($it['id'])) {
                    $product = Product::find($it['id']);
                }
                if (! $product && isset($it['barcode'])) {
                    $product = Product::where('barcode', $it['barcode'])->first();
                }
                if (! $product) {
                    // create lightweight product record if missing
                    $product = Product::create([
                        'name' => $it['name'] ?? 'Unknown',
                        'barcode' => $it['barcode'] ?? null,
                        'base_price' => $price,
                        'quantity' => 0
                    ]);
                }

                // simple stock check
                if (($product->quantity ?? 0) < $qty) {
                    DB::rollBack();
                    return response()->json(['message' => "Insufficient stock for product ID: {$product->id}"], 422);
                }

                $saleItem = SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'batch_id' => $it['batch_id'] ?? null,
                    'quantity' => $qty,
                    'price' => $price
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'batch_id' => $it['batch_id'] ?? null,
                    'type' => 'OUT',
                    'quantity' => $qty,
                    'reference_type' => 'Sale',
                    'reference_id' => $sale->id,
                    'created_at' => now()
                ]);
            }

            DB::commit();
            return response()->json(['id' => $sale->id, 'message' => 'Sale created'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function invoice(Sale $sale, Request $request)
    {
        $sale->load('saleItems.product');
        return view('pos.invoice', compact('sale'));
    }
}
