@extends('layouts.pos')

@section('title', 'تعديل المنتج')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-700">تعديل المنتج: {{ $product->name }}</h3>
    </div>

    <div class="p-8">
        @include('pos.components.product-form', [
            'action' => route('pos.update', $product->id),
            'method' => 'PUT',
            'product' => $product
        ])
    </div>
</div>
@endsection
