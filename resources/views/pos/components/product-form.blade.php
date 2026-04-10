<form method="POST" action="{{ $action }}" id="product-form">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    <div class="fg">
        <label>الباركود *</label>
        <input type="text" name="barcode" id="ef-bc" value="{{ old('barcode', $product->barcode ?? '') }}" required>
        @error('barcode') <span class="ferr" style="display:block">{{ $message }}</span> @enderror
    </div>

    <div class="fg">
        <label>اسم المنتج *</label>
        <input type="text" name="name" id="ef-nm" value="{{ old('name', $product->name ?? '') }}" required>
        @error('name') <span class="ferr" style="display:block">{{ $message }}</span> @enderror
    </div>

    <div class="g2">
        <div class="fg">
            <label>السعر (ر.س) *</label>
            <input type="number" name="price" step="0.01" min="0.01" value="{{ old('price', $product->price ?? '') }}" required>
        </div>
        <div class="fg">
            <label>المخزون</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0">
        </div>
    </div>

    <div class="g2">
        <div class="fg">
            <label>رقم الدفعة *</label>
            <input type="text" name="batch" value="{{ old('batch', $product->batch ?? '') }}" required>
        </div>
        <div class="fg">
            <label>تاريخ الانتهاء *</label>
            <input type="date" name="expiry" value="{{ old('expiry', $product->expiry ?? '') }}" required>
        </div>
    </div>

    <div class="fg">
        <label>ملاحظات</label>
        <input type="text" name="notes" value="{{ old('notes', $product->notes ?? '') }}">
    </div>

    <button type="submit" class="btn btn-p" style="width:100%; padding:12px; font-size:14px;">
        💾 حفظ المنتج
    </button>
</form>
