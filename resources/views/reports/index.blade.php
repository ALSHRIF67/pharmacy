@extends('layouts.app')

@section('header_title', 'التقارير والإحصائيات')

@section('content')
<div class="space-y-8">
    <!-- Top Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border-r-4 border-emerald-500">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">مبيعات اليوم</div>
            <div class="text-3xl font-black text-emerald-600">{{ number_format($todaySales, 2) }} ر.س</div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border-r-4 border-blue-500">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">مبيعات الشهر</div>
            <div class="text-3xl font-black text-blue-600">{{ number_format($monthSales, 2) }} ر.س</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-r-4 border-amber-500">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">إجمالي المشتريات</div>
            <div class="text-3xl font-black text-amber-600">{{ number_format($totalPurchases, 2) }} ر.س</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Selling -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-700">الأصناف الأكثر مبيعاً</h3>
            </div>
            <div class="p-0">
                <table class="w-full text-right">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($topProducts as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-700">{{ $product->name }}</td>
                                <td class="px-6 py-4 text-left font-bold text-emerald-600">{{ $product->total_sold }} وحدة</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stock Alerts -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-red-100">
            <div class="p-6 border-b border-red-50 bg-red-50/50">
                <h3 class="font-bold text-red-700 flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    تنبيهات المخزون المنخفض
                </h3>
            </div>
            <div class="p-0">
                <table class="w-full text-right">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($activeBatches as $batch)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 font-medium">{{ $batch->product->name }}</div>
                                    <div class="text-xs text-gray-400 font-mono">تشغيلة: {{ $batch->batch_number }}</div>
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <span class="text-red-600 font-black">{{ $batch->quantity }}</span>
                                    <span class="text-xs text-gray-400">متبقي</span>
                                </td>
                            </tr>
                        @endforeach
                        @if($activeBatches->isEmpty())
                            <tr>
                                <td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">لا توجد تنبيهات حالياً</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
