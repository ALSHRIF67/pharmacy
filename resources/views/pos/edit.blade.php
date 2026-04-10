@extends('layouts.pos')

@section('title', 'تعديل المنتج')

@section('content')
<div style="max-width: 600px; margin: 30px auto; padding: 20px;">
    <h2 style="margin-bottom: 20px;">✏️ تعديل المنتج: {{ $product->name }}</h2>
    @include('pos.components.product-form', [
        'action' => route('pos.update', $product->id),
        'method' => 'PUT',
        'product' => $product
    ])
</div>
@endsection
