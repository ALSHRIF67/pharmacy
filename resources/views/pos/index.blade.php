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
            <input type="text" id="barcode-inp" placeholder="📷 باركود + Enter">
            <button class="btn btn-w" id="qr-btn" onclick="window.toggleQr()">📷 مسح QR</button>
            <button class="btn btn-g" onclick="window.loadProducts()" title="تحديث">🔄</button>
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
