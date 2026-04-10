<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SaleService;
use App\Models\Sale;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric',
        ]);

        try {
            $sale = $this->saleService->processSale($validated['items']);
            return response()->json([
                'message' => 'Sale processed successfully',
                'sale' => $sale
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function index()
    {
        $sales = Sale::latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return redirect()->route('pos.index');
    }

    public function show(Sale $sale)
    {
        $sale->load('saleItems.product');
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        // Sales should not be editable. Redirect to view or index.
        return redirect()->route('sales.show', $sale->id)->with('error', 'لا يمكن تعديل الفواتير بعد إصدارها.');
    }

    public function destroy(\Illuminate\Http\Request $request, Sale $sale)
    {
        $sale->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Sale deleted'], 200);
        }

        return redirect()->route('sales.index');
    }
}
