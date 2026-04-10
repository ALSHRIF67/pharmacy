@extends('layouts.pos')

@section('title', 'إدارة المنتجات')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-700">إدارة المنتجات</h3>
        <div class="flex space-x-4">
            <input type="text" id="tsrch" placeholder="بحث في المنتجات..."
                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
            <a href="{{ route('pos.create') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-1">
                + منتج جديد
            </a>
        </div>
    </div>

    <div class="p-8">
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
