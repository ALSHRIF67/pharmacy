@extends('layouts.pos')

@section('title', 'تفاصيل المنتج')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-700">تفاصيل المنتج</h3>
    </div>

    <div class="p-8 space-y-4">
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-700">الاسم</span>
            <span class="text-sm text-gray-500">{{ $product->name }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-700">الباركود</span>
            <span class="text-sm text-gray-500 font-mono">{{ $product->barcode }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-700">التصنيف</span>
            <span class="text-sm text-gray-500">{{ $product->category ?? '—' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-700">السعر</span>
            <span class="text-sm text-emerald-600 font-bold">{{ number_format($product->price, 2) }} ر.س</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-700">رقم الدفعة</span>
            <span class="text-sm text-gray-500">{{ $product->batch ?? '—' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-700">تاريخ الانتهاء</span>
            <span class="text-sm text-gray-500">{{ $product->expiry ?? '—' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-700">المخزون</span>
            <span class="text-sm text-gray-500">{{ $product->stock ?? 0 }} وحدة</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-700">ملاحظات</span>
            <span class="text-sm text-gray-500">{{ $product->notes ?? '—' }}</span>
        </div>
    </div>

    <div class="p-8">
        <h4 class="text-lg font-bold text-gray-700 mb-4">المنتجات</h4>
        <table class="w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-sm font-medium text-gray-700">المنتج</th>
                    <th class="p-3 text-sm font-medium text-gray-700">الكمية</th>
                    <th class="p-3 text-sm font-medium text-gray-700">السعر</th>
                    <th class="p-3 text-sm font-medium text-gray-700">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr class="border-b">
                        <td class="p-3 text-sm text-gray-700">{{ $item->product->name }}</td>
                        <td class="p-3 text-sm text-gray-700">{{ $item->quantity }}</td>
                        <td class="p-3 text-sm text-gray-700">{{ $item->price }}</td>
                        <td class="p-3 text-sm text-emerald-600 font-bold">{{ $item->quantity * $item->price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-8 text-right">
            <h4 class="text-lg font-bold text-gray-700">الإجمالي: <span class="text-emerald-600">{{ $sale->total }}</span></h4>
        </div>
    </div>

    <div class="p-6 border-t border-gray-100 flex justify-end space-x-4">
        <a href="{{ route('pos.edit', $product->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition-all">تعديل</a>
        <button onclick="window.addToCartFromShow({{ $product->id }})" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg shadow-emerald-200 transition-all">إضافة للسلة</button>
        <a href="{{ route('pos.table') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition-all">العودة للقائمة</a>
    </div>
</div>
@endsection
