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
}
