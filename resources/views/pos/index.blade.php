@extends('layouts.pos')

@section('title', 'نقطة البيع')

@section('content')
<div id="pos-view" style="display:flex; flex:1; overflow:hidden; height:100%;">
    <!-- Products Panel -->
    <div id="products-panel">
        <div class="toolbar">
            <div class="sbox">
                <span class="sico">🔍</span>
                <input type="text" id="srch" placeholder="بحث بالاسم أو الباركود..." oninput="window.renderGrid()">
            </div>
            <input type="text" id="barcode-input" placeholder="📷 باركود + Enter">
            <button class="btn btn-w" id="qr-btn" onclick="window.toggleQr()">📷 مسح QR</button>
            <button class="btn btn-g" onclick="window.loadProducts()" title="تحديث">🔄</button>

            <a href="{{ route('products.create') }}" class="btn btn-p" style="text-decoration:none; margin-right:auto;">+ إضافة منتج</a>
            <a href="{{ route('sales.index') }}" class="btn btn-w" style="text-decoration:none;">📜 الفواتير</a>
        </div>
        <!-- QR Reader -->
        <div id="qr-wrap"><button id="qr-x" onclick="window.stopQr()">✕</button><div id="reader"></div></div>
        <!-- Grid -->
        <div id="prod-grid"><div class="g-load"><div class="spin"></div><p>جاري التحميل...</p></div></div>
    </div>

    <!-- Cart Panel -->
    @include('pos.components.cart')
</div>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.initPOS === 'function') {
            window.initPOS();
        }
    });
</script>
@endpush
