@extends('layouts.app')

@section('header_title', 'فواتير المشتريات')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-700">سجل المشتريات</h3>
            <a href="{{ route('purchases.create') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                تسجيل فاتورة شراء
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">رقم الفاتورة</th>
                        <th class="px-6 py-4 font-semibold">المورد</th>
                        <th class="px-6 py-4 font-semibold">التاريخ</th>
                        <th class="px-6 py-4 font-semibold">الإجمالي</th>
                        <th class="px-6 py-4 font-semibold text-center">العمليات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($purchases as $purchase)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-emerald-600">
                                #PUR-{{ $purchase->id }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $purchase->supplier->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $purchase->purchase_date->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-700">
                                {{ number_format($purchase->total_amount, 2) }} ر.س
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('purchases.show', $purchase->id) }}" class="inline-block text-emerald-500 hover:text-emerald-700 font-bold text-sm">
                                    تفاصيل الفاتورة
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                لا توجد فواتير شراء مسجلة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
