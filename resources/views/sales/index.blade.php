@extends('layouts.pos')

@section('title', 'سجل الفواتير')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden p-6 mt-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">سجل الفواتير (المبيعات)</h3>
        <a href="{{ route('pos.index') }}" class="btn btn-g">العودة لنقطة البيع</a>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="p-3 text-right">رقم الفاتورة</th>
                <th class="p-3 text-right">عدد المنتجات</th>
                <th class="p-3 text-right">الإجمالي</th>
                <th class="p-3 text-right">التاريخ</th>
                <th class="p-3 text-center">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">#{{ $sale->id }}</td>
                    <td class="p-3">{{ $sale->saleItems->sum('quantity') }}</td>
                    <td class="p-3 font-bold text-emerald-700">{{ number_format($sale->total_price, 2) }} ر.س</td>
                    <td class="p-3 text-gray-600">{{ $sale->created_at->format('Y/m/d H:i') }}</td>
                    <td class="p-3 text-center">
                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-w btn-sm">👁 التفاصيل</a>
                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفاتورة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-d btn-sm">🗑 حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">لا توجد فواتير مسجلة</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
