<?php $__env->startSection('title', 'نقطة البيع'); ?>

<?php $__env->startSection('content'); ?>
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
    <?php echo $__env->make('pos.components.cart', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<!-- Recent Sales -->
<div style="margin-top: 20px;">
    <h3>Recent Sales</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($sale->product->name); ?></td>
                    <td><?php echo e($sale->quantity); ?></td>
                    <td><?php echo e($sale->price); ?></td>
                    <td><?php echo e($sale->total); ?></td>
                    <td><?php echo e($sale->created_at); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.initPOS === 'function') {
            window.initPOS();
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/pos/index.blade.php ENDPATH**/ ?>