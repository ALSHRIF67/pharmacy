@extends('layouts.pos')

@section('title', 'تفاصيل المنتج')

@section('content')
<div style="max-width: 600px; margin: 30px auto; padding: 20px; background: var(--surf); border-radius: var(--r);">
    <div class="irow"><span class="ilbl">الاسم</span><span class="ival">{{ $product->name }}</span></div>
    <div class="irow"><span class="ilbl">الباركود</span><span class="ival" style="font-family:monospace">{{ $product->barcode }}</span></div>
    <div class="irow"><span class="ilbl">التصنيف</span><span class="ival">{{ $product->category ?? '—' }}</span></div>
    <div class="irow"><span class="ilbl">السعر</span><span class="ival price">{{ number_format($product->price, 2) }} ر.س</span></div>
    <div class="irow"><span class="ilbl">رقم الدفعة</span><span class="ival">{{ $product->batch ?? '—' }}</span></div>
    <div class="irow"><span class="ilbl">تاريخ الانتهاء</span><span class="ival">{{ $product->expiry ?? '—' }}</span></div>
    <div class="irow"><span class="ilbl">المخزون</span><span class="ival">{{ $product->stock ?? 0 }} وحدة</span></div>
    <div class="irow"><span class="ilbl">ملاحظات</span><span class="ival">{{ $product->notes ?? '—' }}</span></div>

    <div style="display: flex; gap: 12px; margin-top: 30px;">
        <a href="{{ route('pos.edit', $product->id) }}" class="btn btn-w">✏️ تعديل</a>
        <button onclick="window.addToCartFromShow({{ $product->id }})" class="btn btn-s">🛒 إضافة للسلة</button>
        <a href="{{ route('pos.table') }}" class="btn btn-g">← العودة للقائمة</a>
    </div>
</div>
@endsection
