@extends('layouts.app')

@section('header_title', 'تفاصيل فاتورة الشراء')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-700">فاتورة #PUR-{{ $purchase->id }}</h3>
            <a href="{{ route('purchases.index') }}" class="text-sm text-gray-500 hover:text-emerald-500 flex items-center transition-colors">
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                العودة للقائمة
            </a>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-1">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">المورد</div>
                <div class="text-lg font-bold text-gray-800">{{ $purchase->supplier->name }}</div>
                <div class="text-sm text-gray-500">{{ $purchase->supplier->phone }}</div>
            </div>
            <div class="space-y-1">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">تاريخ الفاتورة</div>
                <div class="text-lg font-bold text-gray-800">{{ $purchase->purchase_date->format('Y/m/d') }}</div>
            </div>
            <div class="space-y-1">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">إجمالي المبلغ</div>
                <div class="text-2xl font-black text-emerald-600">{{ number_format($purchase->total_amount, 2) }} ر.س</div>
            </div>
        </div>

        <div class="border-t border-gray-100 overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">المنتج</th>
                        <th class="px-6 py-4 font-semibold">رقم التشغيلة</th>
                        <th class="px-6 py-4 font-semibold text-center">الكمية</th>
                        <th class="px-6 py-4 font-semibold text-left">التكلفة للوحدة</th>
                        <th class="px-6 py-4 font-semibold text-left">الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($purchase->items as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $item->product->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-mono">
                                {{ $item->batch->batch_number }}
                                <span class="text-gray-400 text-xs">(انتهاء: {{ $item->batch->expiry_date->format('Y/m') }})</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-gray-700">{{ $item->quantity }}</span>
                            </td>
                            <td class="px-6 py-4 text-left text-gray-600">
                                {{ number_format($item->cost_price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-left font-bold text-emerald-700">
                                {{ number_format($item->quantity * $item->cost_price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
