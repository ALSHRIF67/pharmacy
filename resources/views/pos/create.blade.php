@extends('layouts.pos')

@section('title', 'إضافة منتج جديد')

@section('content')
<div style="max-width: 600px; margin: 30px auto; padding: 20px;">
    <h2 style="margin-bottom: 20px;">➕ إضافة منتج جديد</h2>
    @include('pos.components.product-form', ['action' => route('pos.create'), 'method' => 'POST', 'product' => null])
</div>
@endsection
