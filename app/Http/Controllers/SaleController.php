<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SaleService;

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
        return view('pos.index', compact('sales'));
    }

    public function create()
    {
        return view('pos.create');
    }

    public function show(Sale $sale)
    {
        $sale->load('items.product');
        return view('pos.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('items.product');
        return view('pos.edit', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index');
    }
}
