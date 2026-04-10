<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $sales = Sale::with('product')->latest()->get();
        return view('pos.index', compact('products', 'sales'));
    }

    public function table()
    {
        return view('pos.table');
    }

    public function create()
    {
        return view('pos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['total'] = $validated['quantity'] * $validated['price'];

        Sale::create($validated);

        return redirect()->route('pos.create')->with('success', 'Sale recorded successfully.');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('pos.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('pos.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'barcode' => 'required|string|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|numeric|min:0',
            'batch' => 'required|string',
            'expiry' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        
        $product->update($validated);
        
        return redirect()->route('pos.table')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('pos.table')->with('success', 'تم حذف المنتج بنجاح');
    }
}
