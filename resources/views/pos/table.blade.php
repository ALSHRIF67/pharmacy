@extends('layouts.pos')

@section('title', 'إدارة المنتجات')

@section('content')
<div id="table-view" style="display:flex; flex:1; flex-direction:column; overflow:hidden; height:100%;">
    <div class="toolbar">
        <div class="sbox" style="flex:1">
            <span class="sico">🔍</span>
            <input type="text" id="tsrch" placeholder="بحث في المنتجات..." oninput="window.currentPage=1;window.renderTable()">
        </div>
        <a href="{{ route('pos.create') }}" class="btn btn-p">＋ منتج جديد</a>
        <button class="btn btn-g" onclick="window.loadProducts()">🔄 تحديث</button>
    </div>
    <div class="tbl-wrap">
        @include('pos.components.product-table')
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.loadProducts === 'function') {
            window.loadProducts();
        }
    });
</script>
@endpush
