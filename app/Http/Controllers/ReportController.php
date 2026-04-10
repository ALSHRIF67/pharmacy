<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Basic Stats
        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('total_price');
        $monthSales = Sale::whereMonth('created_at', Carbon::now()->month)->sum('total_price');
        $totalPurchases = Purchase::sum('total_amount');
        
        
        // Low Stock Monitoring: Get batches with low stock (< 10 units)
        $activeBatches = Batch::with('product')->get()->filter(function($batch) {
            return $batch->quantity < 10;
        });

        // 3. Top Selling Products
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return view('reports.index', compact(
            'todaySales', 
            'monthSales', 
            'totalPurchases', 
            'activeBatches',
            'topProducts'
        ));
    }
}
