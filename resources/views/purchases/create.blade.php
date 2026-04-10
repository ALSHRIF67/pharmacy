@extends('layouts.app')

@section('header_title', 'تسجيل فاتورة شراء')

@section('content')
<div class="max-w-6xl mx-auto">
    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Purchase Details -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="font-bold text-gray-700 border-b pb-2">تفاصيل الفاتورة</h3>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">المورد</label>
                        <select name="supplier_id" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">اختر المورد</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">تاريخ الشراء</label>
                        <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required 
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                </div>

                <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-100">
                    <div class="text-sm text-emerald-600 font-bold mb-1">إجمالي الفاتورة التقديري</div>
                    <div class="text-3xl font-black text-emerald-700" id="grand-total">0.00 ر.س</div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1">
                        حفظ الفاتورة وتحديث المخزن
                    </button>
                </div>
            </div>

            <!-- Items List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700">الأصناف المشتراة</h3>
                        <button type="button" id="add-item" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            إضافة صنف
                        </button>
                    </div>

                    <div class="p-6">
                        <div id="items-container" class="space-y-4">
                            <!-- JS will inject items here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Template for list items -->
<template id="item-template">
    <div class="item-row bg-gray-50 p-4 rounded-xl border border-gray-100 relative group">
        <button type="button" class="remove-item absolute -left-2 -top-2 bg-red-100 text-red-500 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-gray-500 mb-1">المنتج</label>
                <select name="items[INDEX][product_id]" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm">
                    <option value="">اختر المنتج</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">رقم التشغيلة (Batch)</label>
                <input type="text" name="items[INDEX][batch_number]" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" placeholder="B-123">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">تاريخ الانتهاء</label>
                <input type="date" name="items[INDEX][expiry_date]" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">الكمية</label>
                <input type="number" name="items[INDEX][quantity]" required class="qty-input w-full bg-white border border-gray-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" value="1">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">سعر التكلفة</label>
                <input type="number" step="0.01" name="items[INDEX][cost_price]" required class="cost-input w-full bg-white border border-gray-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" value="0.00">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">سعر البيع</label>
                <input type="number" step="0.01" name="items[INDEX][selling_price]" required class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" value="0.00">
            </div>
            <div class="flex items-end pb-1">
                <div class="text-sm font-bold text-gray-400">الإجمالي: <span class="row-total text-gray-700">0.00</span></div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('items-container');
    const addButton = document.getElementById('add-item');
    const template = document.getElementById('item-template');
    const grandTotalEl = document.getElementById('grand-total');
    let itemIndex = 0;

    function addItem() {
        const content = template.innerHTML.replace(/INDEX/g, itemIndex++);
        const div = document.createElement('div');
        div.innerHTML = content;
        const row = div.firstElementChild;
        container.appendChild(row);

        // Attach event listeners for calculations
        row.querySelector('.qty-input').addEventListener('input', calculateTotals);
        row.querySelector('.cost-input').addEventListener('input', calculateTotals);
        row.querySelector('.remove-item').addEventListener('click', () => {
            row.remove();
            calculateTotals();
        });

        calculateTotals();
    }

    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            const total = qty * cost;
            row.querySelector('.row-total').textContent = total.toFixed(2);
            grandTotal += total;
        });
        grandTotalEl.textContent = grandTotal.toFixed(2) + ' ر.س';
    }

    // Initial item
    addItem();

    addButton.addEventListener('click', addItem);
});
</script>
@endsection
