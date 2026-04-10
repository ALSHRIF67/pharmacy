<div class="pcard" data-barcode="{{ $product->barcode }}">
    <span class="pc-cat">{{ $product->category ?? 'عام' }}</span>
    <span class="pc-name">{{ $product->name }}</span>
    <span class="pc-bc">{{ $product->barcode }}</span>
    <span class="pc-price">{{ number_format($product->price, 2) }} ر.س</span>
    <button class="pc-edit" data-bc="{{ $product->barcode }}">✏️ تعديل</button>
</div>
