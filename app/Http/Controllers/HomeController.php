<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Batch;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'today_sales' => Sale::whereDate('created_at', Carbon::today())->sum('total_price'),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'low_stock_count' => Batch::get()->filter(function($batch) {
                return $batch->quantity < 10;
            })->count(),
        ];

        return view('welcome', compact('stats'));
    }
}
