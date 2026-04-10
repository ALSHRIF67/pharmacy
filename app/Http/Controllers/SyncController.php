<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Batch;
use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function index()
    {
        $syncStats = [
            'products' => Product::where('sync_status', 'pending')->count(),
            'batches' => Batch::where('sync_status', 'pending')->count(),
            'sales' => Sale::where('sync_status', 'pending')->count(),
            'purchases' => Purchase::where('sync_status', 'pending')->count(),
        ];

        $totalPending = array_sum($syncStats);

        return view('sync.index', compact('syncStats', 'totalPending'));
    }

    public function push()
    {
        // Mocking the sync process for now
        // In a real scenario, this would loop through models and call an external API
        
        Product::where('sync_status', 'pending')->update(['sync_status' => 'synched']);
        Batch::where('sync_status', 'pending')->update(['sync_status' => 'synched']);
        Sale::where('sync_status', 'pending')->update(['sync_status' => 'synched']);
        Purchase::where('sync_status', 'pending')->update(['sync_status' => 'synched']);

        return redirect()->route('sync.index')->with('success', 'تمت مزامنة جميع البيانات المحلية مع السحابة بنجاح');
    }
}
