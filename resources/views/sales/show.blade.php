@extends('layouts.pos')

@section('title', 'تفاصيل الفاتورة')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden p-6 mt-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">تفاصيل الفاتورة #{{ $sale->id }}</h3>
        <a href="{{ route('sales.index') }}" class="btn btn-w">العودة للسجل</a>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg mb-6 flex justify-between">
        <div><span class="font-bold text-gray-700">التاريخ: </span> <span class="text-gray-600">{{ $sale->created_at->format('Y/m/d H:i') }}</span></div>
        <div><span class="font-bold text-gray-700">الإجمالي: </span> <span class="text-emerald-700 font-bold">{{ number_format($sale->total_price, 2) }} ر.س</span></div>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="p-3 text-right">المنتج</th>
                <th class="p-3 text-center">الكمية</th>
                <th class="p-3 text-center">السعر</th>
                <th class="p-3 text-center">الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->saleItems as $item)
                <tr class="border-b">
                    <td class="p-3">{{ $item->product->name ?? 'منتج محذوف' }}</td>
                    <td class="p-3 text-center">{{ $item->quantity }}</td>
                    <td class="p-3 text-center">{{ number_format($item->price, 2) }}</td>
                    <td class="p-3 text-center font-bold text-emerald-700">{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
