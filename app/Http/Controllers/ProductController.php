<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAllPaginated();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|unique:products,barcode',
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $this->productService->createProduct($validated);

        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * API: Return all products for POS product grid
     */
    public function apiIndex()
    {
        $products = \App\Models\Product::with(['category', 'batches'])
            ->select('id', 'name', 'barcode', 'base_price', 'category_id')
            ->get()
            ->map(function ($product) {
                return [
                    'id'       => $product->id,
                    'name'     => $product->name,
                    'barcode'  => $product->barcode,
                    'price'    => (float) $product->base_price,
                    'category' => $product->category->name ?? null,
                    'stock'    => $product->batches->count(),
                ];
            });

        return response()->json($products);
    }

    public function getByBarcode($barcode)
    {
        $product = $this->productService->getByBarcode($barcode);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'id'      => $product->id,
            'name'    => $product->name,
            'barcode' => $product->barcode,
            'price'   => (float) $product->base_price,
        ]);
    }

    public function edit($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|unique:products,barcode,' . $id,
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $this->productService->updateProduct($id, $validated);

        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * API Update method used by POS
     */
    public function apiUpdate(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'batch' => 'required|string',
            'expiry' => 'required|date',
            'stock' => 'nullable|numeric',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product = $this->productService->updateProductAndStock($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
}
