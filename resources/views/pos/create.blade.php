@extends('layouts.pos')

@section('title', 'إضافة منتج جديد')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-700">إضافة منتج جديد</h3>
        <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-emerald-500 flex items-center transition-colors">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            العودة للقائمة
        </a>
    </div>

    <div class="p-8">
        <p class="text-sm text-gray-500 mb-6">لإضافة منتج جديد، يرجى الانتقال إلى صفحة <a href="{{ route('products.create') }}" class="text-emerald-500 hover:underline">إضافة منتج</a>.</p>

        <form action="{{ route('sales.store') }}" method="POST">
            @csrf
            <div id="cart" class="space-y-4">
                <!-- Cart items will be dynamically added here -->
            </div>

            <div class="flex justify-between items-center mt-6">
                <div>
                    <label for="barcode" class="block text-sm font-medium text-gray-700">إدخال باركود</label>
                    <input type="text" id="barcode" name="barcode" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
                </div>
                <button type="button" id="add-to-cart" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-1">إضافة للسلة</button>
            </div>

            <div class="mt-8">
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-1">إتمام البيع</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.getElementById('add-to-cart').addEventListener('click', async function() {
            const barcode = document.getElementById('barcode').value;
            const response = await fetch(`/api/products/search?barcode=${barcode}`);
            const product = await response.json();

            if (response.ok) {
                // Add product to cart
            } else {
                alert(product.message);
            }
        });
    </script>
    @endpush
</div>
@endsection
